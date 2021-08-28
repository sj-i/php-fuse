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
use Fuse\Libc\Sys\Stat\Stat;
use TypedCData\TypedCDataInterface;

/**
 * typedef int (*fuse_fill_dir_t) (void *buf, const char *name, const struct stat *stbuf, off_t off);
 */
final class FuseFillDir implements TypedCDataInterface
{
    /** @var \Fuse\FuseFillDirCData */
    private CData $cdata;

    public function __invoke(FuseReadDirBuffer $buf, string $name, ?Stat $stbuf, int $off): int
    {
        if (!is_null($stbuf)) {
            $stbuf = $stbuf->toCData($stbuf->newCData());
        }
        /** @var int */
        return ($this->cdata)($buf->toCData(null), $name, $stbuf, $off);
    }

    public static function getCTypeName(): string
    {
        return 'fuse_fill_dir_t';
    }

    /** @param \Fuse\FuseFillDirCData $cdata */
    public function __construct(CData $cdata)
    {
        $this->cdata = $cdata;
    }

    /** @return static */
    public static function fromCData(CData $cdata): self
    {
        /** @var \Fuse\FuseFillDirCData $cdata */
        return new self($cdata);
    }

    public function toCData(CData $cdata): CData
    {
        return $cdata;
    }

    public static function newCData(): CData
    {
        throw new \LogicException('this type doesn\'t support creation of CData');
    }
}
