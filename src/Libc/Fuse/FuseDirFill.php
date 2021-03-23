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

/**
 * typedef int (*fuse_dirfil_t) (fuse_dirh_t h, const char *name, int type, ino_t ino);
 */
final class FuseDirFill implements TypedCDataInterface
{
    /** @var \Fuse\FuseDirFillCData */
    private CData $cdata;

    public function __invoke(FuseDirHandle $dirhandle, string $name, int $type, int $ino): int
    {
        return ($this->cdata)($dirhandle->toCData(null), $name, $type, $ino);
    }

    public static function getCTypeName(): string
    {
        return 'fuse_dirfil_t';
    }

    /** @param \Fuse\FuseDirFillCData $cdata */
    public function __construct(CData $cdata)
    {
        $this->cdata = $cdata;
    }

    /** @return self */
    public static function fromCData(CData $cdata): self
    {
        /** @var \Fuse\FuseDirFillCData $cdata */
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
