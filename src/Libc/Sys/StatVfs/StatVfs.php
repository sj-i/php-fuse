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

namespace Fuse\Libc\Sys\StatVfs;

use FFI\CData;
use Fuse\FFI\TypedCDataDefaultImplementationTrait;
use Fuse\FFI\TypedCDataInterface;

/**
 * struct statvfs
 * {
 *     unsigned long int f_bsize;
 *     unsigned long int f_frsize;
 *     fsblkcnt64_t f_blocks;
 *     fsblkcnt64_t f_bfree;
 *     fsblkcnt64_t f_bavail;
 *     fsblkcnt64_t f_files;
 *     fsblkcnt64_t f_ffree;
 *     fsblkcnt64_t f_favail;
 *     unsigned long int f_fsid;
 *     unsigned long int f_flag;
 *     unsigned long int f_namemax;
 *     int __f_spare[6];
 * };
 */
final class StatVfs implements TypedCDataInterface
{
    use TypedCDataDefaultImplementationTrait;

    public int $f_bsize;
    public int $f_frsize;
    public int $f_blocks;
    public int $f_bfree;
    public int $f_bavail;
    public int $f_files;
    public int $f_ffree;
    public int $f_favail;
    public int $f_fsid;
    public int $f_flag;
    public int $f_namemax;
    // phpcs:ignore PSR2.Classes.PropertyDeclaration
    public ?CData $__f_spare;

    public function __construct(
        int $f_bsize = 0,
        int $f_frsize = 0,
        int $f_blocks = 0,
        int $f_bfree = 0,
        int $f_bavail = 0,
        int $f_files = 0,
        int $f_ffree = 0,
        int $f_favail = 0,
        int $f_fsid = 0,
        int $f_flag = 0,
        int $f_namemax = 0,
        ?CData $__f_spare = null
    ) {
        $this->f_bsize = $f_bsize;
        $this->f_frsize = $f_frsize;
        $this->f_blocks = $f_blocks;
        $this->f_bfree = $f_bfree;
        $this->f_bavail = $f_bavail;
        $this->f_files = $f_files;
        $this->f_ffree = $f_ffree;
        $this->f_favail = $f_favail;
        $this->f_fsid = $f_fsid;
        $this->f_flag = $f_flag;
        $this->f_namemax = $f_namemax;
        $this->__f_spare = $__f_spare;
    }

    public static function getCTypeName(): string
    {
        return 'struct statvfs *';
    }
}
