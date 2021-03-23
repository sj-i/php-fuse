<?php

/**
 * This file is part of the sj-i/php-fuse package.
 *
 * (c) sji <sji@sj-i.dev>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace Fuse\FFI;

use FFI;
use FFI\CData;
use Fuse\Fuse;
use PhpDocTypeReader\Context\IdentifierContextFactory;
use PhpDocTypeReader\PhpDocTypeReader;
use PhpDocTypeReader\Type\GenericType;
use PhpDocTypeReader\Type\ObjectType;
use ReflectionProperty;

trait TypedCDataDefaultImplementationTrait
{
    /** @return static */
    public static function fromCData(CData $cdata): self
    {
        $class = new \ReflectionClass(self::class);
        $self = new self();
        foreach ($class->getProperties() as $property) {
            $name = $property->getName();
            $type = $property->getType();
            if (is_null($type)) {
                throw new \LogicException('property of TypedCData must have types');
            }
            assert($type instanceof \ReflectionNamedType);
            $type_name = $type->getName();
            if (is_a($type_name, TypedCDataInterface::class, true)) {
                /** @var CData $original_cdata */
                $original_cdata = $cdata->$name;
                $self->$name = $type_name::fromCData($original_cdata);
            } elseif (is_a($type_name, TypedCDataArray::class, true)) {
                /** @var \FFI\CDataArray $original_cdata */
                $original_cdata = $cdata->$name;
                $self->$name = $type_name::fromCData($original_cdata, self::getElementTypeOfCDataArray($property));
            } else {
                $self->$name = $cdata->$name;
            }
        }
        return $self;
    }

    /**
     * @return class-string<TypedCDataInterface>
     */
    private static function getElementTypeOfCDataArray(ReflectionProperty $property): string
    {
        $doc_comment = $property->getDocComment();
        if (!is_string($doc_comment)) {
            throw new \LogicException('TypedCDataArray property needs type annotation');
        }
        $php_doc_type_reader = new PhpDocTypeReader();
        $identifier_context_factory = new IdentifierContextFactory();
        $identifier_context = $identifier_context_factory->createFromFile(
            $property->getDeclaringClass()->getFileName()
        );
        $type = $php_doc_type_reader->getVarTypes($doc_comment, $identifier_context);
        if (!($type instanceof GenericType)) {
            throw new \LogicException('TypedCDataArray property must have a generic type annotation');
        }
        if (count($type->parameter_types) !== 1) {
            throw new \LogicException('TypedCDataArray property must have one parameter type');
        }
        $parameter_type = $type->parameter_types[0];
        if (!($parameter_type instanceof ObjectType)) {
            throw new \LogicException('parameter type of TypedCDataArray property must be a object type');
        }
        $element_type = $parameter_type->class_name;
        if (!is_a($element_type, TypedCDataInterface::class, true)) {
            throw new \LogicException(
                'parameter type of TypedCDataArray property must be a TypedCDataInterface'
            );
        }
        return $element_type;
    }

    abstract public static function getCTypeName(): string;

    public function toCData(CData $cdata): CData
    {
        /**
         * @psalm-suppress RawObjectIteration
         * @var string $key
         * @var mixed $value
         */
        foreach ($this as $key => $value) {
            if (is_a($value, TypedCDataInterface::class)) {
                /** @psalm-suppress MixedAssignment */
                $cdata_property = $cdata->$key;
                if (!($cdata_property instanceof CData)) {
                    throw new \LogicException(
                        'TypedCData must correspond to a CData'
                    );
                }
                $cdata->$key = $value->toCData($cdata_property);
            } elseif (is_a($value, TypedCDataArray::class)) {
                /** @psalm-suppress MixedAssignment */
                $cdata_array_property = $cdata->$key;
                if (!($cdata_array_property instanceof CData)) {
                    throw new \LogicException(
                        'TypedCDataArray must correspond to a CData'
                    );
                }
                /** @var \FFI\CDataArray $cdata_array_property */
                $cdata->$key = $value->toCData($cdata_array_property);
            } else {
                $cdata->$key = $this->$key;
            }
        }
        return $cdata;
    }

    public static function newCData(): CData
    {
        $typename = static::getCTypeName();
        $cdata = Fuse::getInstance()->ffi->new($typename);
        $type = Fuse::getInstance()->ffi->type(
            $typename
        );
        $size = FFI::sizeof(
            $type
        );
        FFI::memset($cdata, 0, $size);
        return $cdata;
    }
}
