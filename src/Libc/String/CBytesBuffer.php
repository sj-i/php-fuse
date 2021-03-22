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

namespace Fuse\Libc\String;

use FFI\CData;
use Fuse\FFI\TypedCDataInterface;

final class CBytesBuffer implements TypedCDataInterface
{
    private CData $cdata;

    public function __construct(CData $cdata)
    {
        $this->cdata = $cdata;
    }

    /** @return static */
    public static function fromCData(CData $cdata): self
    {
        return new self($cdata);
    }

    public static function getCTypeName(): string
    {
        return 'char *';
    }

    public function write(string $content, $size): void
    {
        \FFI::memcpy($this->cdata, $content, $size);
    }

    public function toCData(CData $cdata): CData
    {
        return $cdata;
    }

    public static function newCData(): CData
    {
        return \FFI::new('char[1]');
    }
}