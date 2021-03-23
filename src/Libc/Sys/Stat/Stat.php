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

namespace Fuse\Libc\Sys\Stat;

use FFI\CData;
use Fuse\FFI\TypedCDataInterface;
use Fuse\Libc\Time\TimeSpec;
use Fuse\FFI\TypedCDataDefaultImplementationTrait;

/**
 * struct stat
 * {
 *     dev_t st_dev;
 *     ino_t st_ino;
 *     nlink_t st_nlink;
 *     mode_t st_mode;
 *     uid_t st_uid;
 *     gid_t st_gid;
 *     int __pad0;
 *     dev_t st_rdev;
 *     off_t st_size;
 *     blksize_t st_blksize;
 *     blkcnt_t st_blocks;
 *     struct timespec st_atim;
 *     struct timespec st_mtim;
 *     struct timespec st_ctim;
 *     long int reserved[3];
 * };
 */
final class Stat implements TypedCDataInterface
{
    use TypedCDataDefaultImplementationTrait;

    public const S_IFDIR = 0040000;
    public const S_IFREG = 0100000;

    public static function getCTypeName(): string
    {
        return 'struct stat';
    }

    public int $st_dev;
    public int $st_ino;
    public int $st_nlink;
    public int $st_mode;
    public int $st_uid;
    public int $st_gid;
    // phpcs:ignore PSR2.Classes.PropertyDeclaration
    public int $__pad0;
    public int $st_rdev;
    public int $st_size;
    public int $st_blksize;
    public int $st_blocks;
    public TimeSpec $st_atim;
    public TimeSpec $st_mtim;
    public TimeSpec $st_ctim;
    public ?CData $reserved;

    public function __construct(
        int $st_dev = 0,
        int $st_ino = 0,
        int $st_nlink = 0,
        int $st_mode = 0,
        int $st_uid = 0,
        int $st_gid = 0,
        int $__pad0 = 0,
        int $st_rdev = 0,
        int $st_size = 0,
        int $st_blksize = 0,
        int $st_blocks = 0,
        ?TimeSpec $st_atim = null,
        ?TimeSpec $st_mtim = null,
        ?TimeSpec $st_ctim = null,
        ?CData $reserved = null
    ) {
        $this->st_dev = $st_dev;
        $this->st_ino = $st_ino;
        $this->st_nlink = $st_nlink;
        $this->st_mode = $st_mode;
        $this->st_uid = $st_uid;
        $this->st_gid = $st_gid;
        $this->__pad0 = $__pad0;
        $this->st_rdev = $st_rdev;
        $this->st_size = $st_size;
        $this->st_blksize = $st_blksize;
        $this->st_blocks = $st_blocks;
        $this->st_atim = $st_atim ?? new TimeSpec();
        $this->st_mtim = $st_mtim ?? new TimeSpec();
        $this->st_ctim = $st_ctim ?? new TimeSpec();
        $this->reserved = $reserved;
    }
}
