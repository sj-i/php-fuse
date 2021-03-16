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
            } else {
                $self->$name = $cdata->$name;
            }
        }
        return $self;
    }

    abstract public static function getCTypeName(): string;

    public function toCData(?CData $cdata): CData
    {
        if (is_null($cdata)) {
            $typename = $this->getCTypeName();
            $cdata = FFI::new($typename);
            $type = Fuse::getInstance()->ffi->type(
                $typename
            );
            $size = FFI::sizeof(
                $type
            );
            FFI::memset($cdata, 0, $size);
        }
        foreach ($this as $key => $value) {
            if (is_a($value, TypedCDataInterface::class, true)) {
                $cdata->$key = $value->toCData($cdata->$key);
            } else {
                $cdata->$key = $this->$key;
            }
        }
        return $cdata;
    }
}