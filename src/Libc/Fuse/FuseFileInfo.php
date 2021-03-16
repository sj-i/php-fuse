<?php


namespace Fuse\Libc\Fuse;

use Fuse\FFI\TypedCDataDefaultImplementationTrait;
use Fuse\FFI\TypedCDataInterface;

/**
 * struct fuse_file_info
 * {
 *     int flags;
 *     unsigned long fh_old;
 *     int writepage;
 *     unsigned int direct_io : 1;
 *     unsigned int keep_cache : 1;
 *     unsigned int flush : 1;
 *     unsigned int nonseekable : 1;
 *     unsigned int flock_release : 1;
 *     unsigned int padding : 27;
 *     uint64_t fh;
 *     uint64_t lock_owner;
 * };
 */
final class FuseFileInfo implements TypedCDataInterface
{
    use TypedCDataDefaultImplementationTrait;

    public int $flags = 0;
    public int $fh_old = 0;
    public int $writepage = 0;
    public int $direct_io = 0;
    public int $keep_cache = 0;
    public int $nonseekable = 0;
    public int $flock_release = 0;
    public int $padding = 0;
    public int $fh = 0;
    public int $lock_owner = 0;

    public static function getCTypeName(): string
    {
        return 'struct fuse_file_info';
    }
}