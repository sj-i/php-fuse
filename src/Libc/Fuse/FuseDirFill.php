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

namespace Fuse\Libc\Fuse;

use FFI\CData;
use Fuse\FFI\TypedCDataInterface;

final class FuseDirFill implements TypedCDataInterface
{
    /** @var callable(CData $dirhandle, string $name, int $type, int $ino):int */
    private CData $cdata;

    public function __invoke(FuseDirHandle $dirhandle, string $name, int $type, int $ino): int
    {
        return ($this->cdata)($dirhandle->toCData(null), $name, $type, $ino);
    }

    public static function getCTypeName(): string
    {
        return 'fuse_dirfil_t';
    }

    public function __construct(CData $cdata)
    {
        $this->cdata = $cdata;
    }

    /** @return static */
    public static function fromCData(CData $cdata): self
    {
        return new self($cdata);
    }

    public function toCData(CData $cdata): CData
    {
        return $cdata;
    }

    public static function newCData(): CData
    {
        throw new \LogicException();
    }
}