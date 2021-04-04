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

use Closure;
use FFI\CData;
use FFI\CDataArray;
use PhpDocTypeReader\Context\IdentifierContextFactory;
use PhpDocTypeReader\PhpDocTypeReader;
use PhpDocTypeReader\Type\GenericType;
use PhpDocTypeReader\Type\ObjectType;
use ReflectionClass;

final class TypedCDataWrapper
{
    public function createWrapper(callable $callable): \Closure
    {
        if (!is_array($callable) or !is_object($callable[0])) {
            return Closure::fromCallable($callable);
        }
        $class = new ReflectionClass(get_class($callable[0]));
        $method = $class->getMethod($callable[1]);

        $input_converters = [];
        $outout_converters = [];
        foreach ($method->getParameters() as $parameter) {
            $class = $parameter->getClass();
            if (!is_null($class) and $class->isSubclassOf(TypedCDataInterface::class)) {
                $fromCData = $class->getMethod('fromCData');
                $input_converters[] = function (CData $cdata) use ($fromCData): TypedCDataInterface {
                    /** @var TypedCDataInterface */
                    return $fromCData->invoke(null, $cdata);
                };
                $outout_converters[] = function (TypedCDataInterface $typed_c_data, CData $toCData): CData {
                    return $typed_c_data->toCData($toCData);
                };
            } elseif (!is_null($class) and $class->getName() === TypedCDataArray::class) {
                $fromCData = $class->getMethod('fromCData');
                $elementType = $this->getElementTypeForCDataArrayParameter($parameter);
                $input_converters[] = function (CData $cdata) use ($fromCData, $elementType): TypedCDataArray {
                    /** @var TypedCDataArray */
                    return $fromCData->invoke(null, $cdata, $elementType);
                };
                $outout_converters[] = function (TypedCDataArray $typed_c_data_array, CData $toCData): CData {
                    return $typed_c_data_array->toCData($toCData);
                };
            } elseif ($parameter->isPassedByReference()) {
                $input_converters[] = function (CData $cdata): CData {
                    /** @var \FFI\CDataArray $cdata */
                    return $cdata[0];
                };
                $outout_converters[] =
                    /**
                      * @template T
                      * @param T $value
                      * @param \FFI\CDataArray $toCData
                      * @return T
                      */
                    function ($value, CData $toCData) {
                        return $toCData[0] = $value;
                    };
            } else {
                $input_converters[] =
                    /**
                      * @template T
                      * @param T $data
                      * @return T
                      */
                    fn ($data) => $data;
                $outout_converters[] =
                    /**
                      * @template T
                      * @template T2
                      * @param T $_1
                      * @param T2 $_2
                      * @return T
                      */
                    fn ($_1, $_2) => $_1;
            }
        }

        return
            /** @param mixed $args */
            function (...$args) use ($input_converters, $outout_converters, $callable) {
                $new_args = [];
                foreach ($input_converters as $key => $converter) {
                    /** @psalm-suppress MixedArgument */
                    $new_args[$key] = $converter($args[$key]);
                }
                /** @psalm-suppress MixedAssignment */
                $result = $callable(...$new_args);
                foreach ($outout_converters as $key => $converter) {
                    /**
                      * @psalm-suppress PossiblyInvalidArgument
                      * @psalm-suppress MixedArgument
                      */
                    $args[$key] = $converter($new_args[$key], $args[$key]);
                }
                if ($result instanceof TypedCDataInterface) {
                    $result = $result->newCData();
                }
                return $result;
            };
    }

    public function getElementTypeForCDataArrayParameter(\ReflectionParameter $parameter): string
    {
        $parameter_name = $parameter->getName();
        $doc_comment = $parameter->getDeclaringFunction()->getDocComment();
        $declaring_file_name = $parameter->getDeclaringFunction()->getFileName();
        $identifier_context_factory = new IdentifierContextFactory();
        $php_doc_type_reader = new PhpDocTypeReader();
        $param_types = $php_doc_type_reader->getParamTypes(
            $doc_comment,
            $identifier_context_factory->createFromFile($declaring_file_name)
        );
        $parameter_type = $param_types[$parameter_name];
        if (!($parameter_type instanceof GenericType)) {
            throw new \LogicException('TypedCDataArray must have a generic type annotation');
        }
        $element_type = $parameter_type->parameter_types[0];
        if (!($element_type instanceof ObjectType)) {
            throw new \LogicException('TypedCDataArray must have a object parameter type');
        }
        return $element_type->class_name;
    }
}
