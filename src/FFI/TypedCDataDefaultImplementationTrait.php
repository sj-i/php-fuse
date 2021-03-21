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
    /** @return self */
    public static function fromCData(CData $cdata): self
    {
        $class = new \ReflectionClass(self::class);
        $self = new self();
        foreach ($class->getProperties() as $property) {
            $name = $property->getName();
            $type = $property->getType()->getName();
            if (is_a($type, TypedCDataInterface::class, true)) {
                $self->$name = $type::fromCData($cdata->$name);
            } elseif (is_a($type, TypedCDataArray::class, true)) {
                $self->$name = $type::fromCData($cdata->$name, self::getElementTypeOfCDataArray($property));
            } else {
                $self->$name = $cdata->$name;
            }
        }
        return $self;
    }

    private static function getElementTypeOfCDataArray(ReflectionProperty $property)
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
        return $parameter_type->class_name;
    }

    abstract public static function getCTypeName(): string;

    public function toCData(CData $cdata): CData
    {
        foreach ($this as $key => $value) {
            if (is_a($value, TypedCDataInterface::class, true)) {
                $cdata->$key = $value->toCData($cdata->$key);
            } elseif (is_a($value, TypedCDataArray::class, true)) {
                $cdata->$key = $value->toCData($cdata->$key);
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
