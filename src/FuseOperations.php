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

namespace Fuse;

use FFI;
use FFI\CData;
use Fuse\Libc\Fcntl\Flock;
use Fuse\Libc\Fuse\FuseBufVec;
use Fuse\Libc\Fuse\FuseConnInfo;
use Fuse\Libc\Fuse\FuseDirFill;
use Fuse\Libc\Fuse\FuseDirHandle;
use Fuse\Libc\Fuse\FuseFileInfo;
use Fuse\Libc\Fuse\FuseFillDir;
use Fuse\Libc\Fuse\FuseIoctlArgPointer;
use Fuse\Libc\Fuse\FuseIoctlDataPointer;
use Fuse\Libc\Fuse\FusePollHandle;
use Fuse\Libc\Fuse\FusePrivateData;
use Fuse\Libc\Fuse\FuseReadDirBuffer;
use Fuse\Libc\String\CBytesBuffer;
use Fuse\Libc\String\CStringBuffer;
use Fuse\Libc\Sys\Stat\Stat;
use Fuse\Libc\Sys\StatVfs\StatVfs;
use Fuse\Libc\Utime\UtimBuf;
use ReflectionClass;
use TypedCData\TypedCDataArray;
use TypedCData\TypedCDataWrapper;

// phpcs:disable Generic.Files.LineLength
/**
 * @psalm-type getattr_op          = callable(string $path, CData $stat): int
 * @psalm-type getattr_typed_op    = callable(string $path, Stat $stat): int
 * @psalm-type readlink_op         = callable(string $path, CData $buffer, int $size): int
 * @psalm-type readlink_typed_op   = callable(string $path, CStringBuffer $buffer, int $size): int
 * @psalm-type getdir_op           = callable(string $path, CData $dirhandle, CData $dirfill): int
 * @psalm-type getdir_typed_op     = callable(string $path, FuseDirHandle $dirhandle, FuseDirFill $dirfill): int
 * @psalm-type utime_op            = callable(string $path, CData $utime_buf): int
 * @psalm-type utime_typed_op      = callable(string $path, UtimBuf $utime_buf): int
 * @psalm-type open_op             = callable(string $path, CData $fuse_file_info): int
 * @psalm-type open_typed_op       = callable(string $path, FuseFileInfo $fuse_file_info): int
 * @psalm-type read_op             = callable(string $path, CData $buffer, int $size, int $offset, CData $fuse_file_info): int
 * @psalm-type read_typed_op       = callable(string $path, CBytesBuffer $buffer, int $size, int $offset, FuseFileInfo $fuse_file_info): int
 * @psalm-type write_op            = callable(string $path, string $buffer, int $size, int $offset, CData $fuse_file_info): int
 * @psalm-type write_typed_op      = callable(string $path, string $buffer, int $size, int $offset, FuseFileInfo $fuse_file_info): int
 * @psalm-type statfs_op           = callable(string $path, CData $statvfs): int
 * @psalm-type statfs_typed_op     = callable(string $path, StatVfs $statvfs): int
 * @psalm-type flush_op            = callable(string $path, CData $fuse_file_info): int
 * @psalm-type flush_typed_op      = callable(string $path, FuseFileInfo $fuse_file_info): int
 * @psalm-type release_op          = callable(string $path, CData $fuse_file_info): int
 * @psalm-type release_typed_op    = callable(string $path, FuseFileInfo $fuse_file_info): int
 * @psalm-type fsync_op            = callable(string $path, int $flags, CData $fuse_file_info): int
 * @psalm-type fsync_typed_op      = callable(string $path, int $flags, FuseFileInfo $fuse_file_info): int
 * @psalm-type opendir_op          = callable(string $path, CData $fuse_file_info): int
 * @psalm-type opendir_typed_op    = callable(string $path, FuseFileInfo $fuse_file_info): int
 * @psalm-type readdir_op          = callable(string $path, CData $buf, CData $filler, int $offset, CData $fuse_file_info): int
 * @psalm-type readdir_typed_op    = callable(string $path, FuseReadDirBuffer $buf, FuseFillDir $filler, int $offset, FuseFileInfo $fuse_file_info): int
 * @psalm-type releasedir_op       = callable(string $path, CData $fuse_file_info): int
 * @psalm-type releasedir_typed_op = callable(string $path, FuseFileInfo $fuse_file_info): int
 * @psalm-type fsyncdir_op         = callable(string $path, CData $fuse_file_info): int
 * @psalm-type fsyncdir_typed_op   = callable(string $path, FuseFileInfo $fuse_file_info): int
 * @psalm-type init_op             = callable(CData $conn): ?CData
 * @psalm-type init_typed_op       = callable(FuseConnInfo $conn): ?FusePrivateData
 * @psalm-type destroy_op          = callable(CData $private_data): void
 * @psalm-type destroy_typed_op    = callable(FusePrivateData $private_data): void
 * @psalm-type create_op           = callable(string $path, int $mode, CData $fuse_file_info): int
 * @psalm-type create_typed_op     = callable(string $path, int $mode, FuseFileInfo $fuse_file_info): int
 * @psalm-type ftruncate_op        = callable(string $path, int $offset, CData $fuse_file_info): int
 * @psalm-type ftruncate_typed_op  = callable(string $path, int $offset, FuseFileInfo $fuse_file_info): int
 * @psalm-type fgetattr_op         = callable(string $path, CData $stat, CData $fuse_file_info): int
 * @psalm-type fgetattr_typed_op   = callable(string $path, Stat $stat, FuseFileInfo $fuse_file_info): int
 * @psalm-type lock_op             = callable(string $path, CData $fuse_file_info, int $cmd, CData $flock): int
 * @psalm-type lock_typed_op       = callable(string $path, FuseFileInfo $fuse_file_info, int $cmd, Flock $flock): int
 * @psalm-type utimens_op          = callable(string $path, CData $tv): int
 * @psalm-type utimens_typed_op    = callable(string $path, TypedCDataArray $tv): int
 * @psalm-type bmap_op             = callable(string $path, int $blocksize, CData $idx): int
 * @psalm-type bmap_typed_op       = callable(string $path, int $blocksize, int $idx): int
 * @psalm-type ioctl_op            = callable(string $path, int $cmd, CData $arg, CData $fuse_file_info, int $flags, CData $data): int
 * @psalm-type ioctl_typed_op      = callable(string $path, int $cmd, FuseIoctlArgPointer $arg, FuseFileInfo $fuse_file_info, int $flags, FuseIoctlDataPointer $data): int
 * @psalm-type poll_op             = callable(string $path, CData $fuse_file_info, CData $fuse_pollhandle, int $reventsp): int
 * @psalm-type poll_typed_op       = callable(string $path, FuseFileInfo $fuse_file_info, FusePollHandle $fuse_pollhandle, int $reventsp): int
 * @psalm-type write_buf_op        = callable(string $path, CData $buf, int $offset, CData $fuse_file_info): int
 * @psalm-type write_buf_typed_op  = callable(string $path, FuseBufVec $buf, int $offset, FuseFileInfo $fuse_file_info): int
 * @psalm-type read_buf_op         = callable(string $path, CData $bufp, int $size, int $offset, CData $fuse_file_info): int
 * @psalm-type read_buf_typed_op   = callable(string $path, FuseBufVec $bufp, int $size, int $offset, FuseFileInfo $fuse_file_info): int
 * @psalm-type flock_op            = callable(string $path, CData $fuse_file_info, int $op): int
 * @psalm-type flock_typed_op      = callable(string $path, FuseFileInfo $fuse_file_info, int $op): int
 * @psalm-type fallocate_op        = callable(string $path, int $mode, int $offset, CData $fuse_file_info): int
 * @psalm-type fallocate_typed_op  = callable(string $path, int $mode, int $offset, FuseFileInfo $fuse_file_info): int
 */
// phpcs:enable Generic.Files.LineLength
final class FuseOperations implements Mountable
{
    /**
     * int (*getattr) (const char *, struct stat *);
     *
     * @var null|getattr_op|getattr_typed_op
     */
    public $getattr = null;

    /**
     * int (*readlink) (const char *, char *, size_t);
     *
     * @var null|readlink_op|readlink_typed_op
     */
    public $readlink = null;

    /**
     * int (*getdir) (const char *, fuse_dirh_t, fuse_dirfil_t);
     *
     * @deprecated
     * @var null|getdir_op|getdir_typed_op
     */
    public $getdir = null;

    /**
     * int (*mknod) (const char *, mode_t, dev_t);
     *
     * @var null|callable(string $path, int $mode, int $dev): int
     */
    public $mknod = null;

    /**
     * int (*mkdir) (const char *, mode_t);
     *
     * @var null|callable(string $path, int $mode): int
     */
    public $mkdir = null;

    /**
     * int (*unlink) (const char *);
     *
     * @var null|callable(string $path): int
     */
    public $unlink = null;

    /**
     * int (*rmdir) (const char *);
     *
     * @var null|callable(string $path): int
     */
    public $rmdir = null;

    /**
     * int (*symlink) (const char *, const char *);
     *
     * @var null|callable(string $path, string $link): int
     */
    public $symlink = null;

    /**
     * int (*rename) (const char *, const char *);
     *
     * @var null|callable(string $from, string $to): int
     */
    public $rename = null;

    /**
     * int (*link) (const char *, const char *);
     *
     * @var null|callable(string $path, string $link): int
     */
    public $link = null;

    /**
     * int (*chmod) (const char *, mode_t);
     *
     * @var null|callable(string $path, int $mode): int
     */
    public $chmod = null;

    /**
     * int (*chown) (const char *, uid_t, gid_t);
     *
     * @var null|callable(string $path, int $uid, int $gid): int
     */
    public $chown = null;

    /**
     * int (*truncate) (const char *, off_t);
     *
     * @var null|callable(string $path, int $offset): int
     */
    public $truncate = null;

    /**
     * int (*utime) (const char *, struct utimbuf *);
     *
     * @var null|utime_op|utime_typed_op
     */
    public $utime = null;

    /**
     * int (*open) (const char *, struct fuse_file_info *);
     *
     * @var null|open_op|open_typed_op
     */
    public $open = null;

    /**
     * int (*read) (const char *, char *, size_t, off_t, struct fuse_file_info *);
     *
     * @var null|read_op|read_typed_op
     */
    public $read = null;

    /**
     * int (*write) (const char *, const char *, size_t, off_t, struct fuse_file_info *);
     *
     * @var null|write_op|write_typed_op
     */
    public $write = null;

    /**
     * int (*statfs) (const char *, struct statvfs *);
     *
     * @var null|statfs_op|statfs_typed_op
     */
    public $statfs = null;

    /**
     * int (*flush) (const char *, struct fuse_file_info *);
     *
     * @var null|flush_op|flush_typed_op
     */
    public $flush = null;

    /**
     * int (*release) (const char *, struct fuse_file_info *);
     *
     * @var null|release_op|release_typed_op
     */
    public $release = null;

    /**
     * int (*fsync) (const char *, int, struct fuse_file_info *);
     *
     * @var null|fsync_op|fsync_typed_op
     */
    public $fsync = null;

    /**
     * int (*setxattr) (const char *, const char *, const char *, size_t, int);
     *
     * @var null|callable(string $path, string $name, string $value, int $size): int
     */
    public $setxattr = null;

    /**
     * int (*getxattr) (const char *, const char *, char *, size_t);
     *
     * @var null|callable(string $path, string $name, string $value, int $size): int
     */
    public $getxattr = null;

    /**
     * int (*listxattr) (const char *, char *, size_t);
     *
     * @var null|callable(string $path, int $size): int
     */
    public $listxattr = null;

    /**
     * int (*removexattr) (const char *, const char *);
     *
     * @var null|callable(string $size, string $name): int
     */
    public $removexattr = null;

    /**
     * int (*opendir) (const char *, struct fuse_file_info *);
     *
     * @var null|opendir_op|opendir_typed_op
     */
    public $opendir = null;

    /**
     * int (*readdir) (const char *, void *, fuse_fill_dir_t, off_t, struct fuse_file_info *);
     *
     * @var null|readdir_op|readdir_typed_op
     */
    public $readdir = null;

    /**
     * int (*releasedir) (const char *, struct fuse_file_info *);
     *
     * @var null|releasedir_op|releasedir_typed_op
     */
    public $releasedir = null;

    /**
     * int (*fsyncdir) (const char *, int, struct fuse_file_info *);
     *
     * @var null|fsyncdir_op|fsyncdir_typed_op
     */
    public $fsyncdir = null;

    /**
     * void *(*init) (struct fuse_conn_info *conn);
     *
     * @var null|init_op|init_typed_op
     */
    public $init = null;

    /**
     * void (*destroy) (void *);
     *
     * @var null|destroy_op|destroy_typed_op
     */
    public $destroy = null;

    /**
     * int (*access) (const char *, int);
     *
     * @var null|callable(string $path, int $mode): int
     */
    public $access = null;

    /**
     * int (*create) (const char *, mode_t, struct fuse_file_info *);
     *
     * @var null|create_op|create_typed_op
     */
    public $create = null;

    /**
     * int (*ftruncate) (const char *, off_t, struct fuse_file_info *);
     *
     * @var null|ftruncate_op|ftruncate_typed_op
     */
    public $ftruncate = null;

    /**
     * int (*fgetattr) (const char *, struct stat *, struct fuse_file_info *);
     *
     * @var null|fgetattr_op|fgetattr_typed_op
     */
    public $fgetattr = null;

    /**
     * int (*lock) (const char *, struct fuse_file_info *, int cmd, struct flock *);
     *
     * @var null|lock_op|lock_typed_op
     */
    public $lock = null;

    /**
     * int (*utimens) (const char *, const struct timespec tv[2]);
     *
     * @var null|utimens_op|utimens_typed_op
     */
    public $utimens = null;

    /**
     * int (*bmap) (const char *, size_t blocksize, uint64_t *idx);
     *
     * @var null|bmap_op|bmap_typed_op
     */
    public $bmap = null;

    /**
     * unsigned int flag_nullpath_ok:1;
     * unsigned int flag_nopath:1;
     * unsigned int flag_utime_omit_ok:1;
     * unsigned int flag_reserved:29;
     */
    public bool $flag_nullpath_ok = false;
    public bool $flag_nopath = false;
    public bool $flag_utime_omit_ok = false;

    /**
     * int (*ioctl) (const char *, int cmd, void *arg, struct fuse_file_info *, unsigned int flags, void *data);
     *
     * @var null|ioctl_op|ioctl_typed_op
     */
    public $ioctl = null;

    /**
     * int (*poll) (const char *, struct fuse_file_info *, struct fuse_pollhandle *ph, unsigned *reventsp);
     *
     * @var null|poll_op|poll_typed_op
     */
    public $poll = null;

    /**
     * int (*write_buf) (const char *, struct fuse_bufvec *buf, off_t off, struct fuse_file_info *);
     *
     * @var null|write_buf_op|write_buf_typed_op
     */
    public $write_buf = null;

    /**
     * int (*read_buf) (const char *, struct fuse_bufvec **bufp, size_t size, off_t off, struct fuse_file_info *);
     *
     * @var null|read_buf_op|read_buf_typed_op
     */
    public $read_buf = null;

    /**
     * int (*flock) (const char *, struct fuse_file_info *, int op);
     *
     * @var null|flock_op|flock_typed_op
     */
    public $flock = null;

    /**
     * int (*fallocate) (const char *, int, off_t, off_t, struct fuse_file_info *);
     *
     * @var null|fallocate_op|fallocate_typed_op
     */
    public $fallocate = null;

    private ?CData $cdata_cache = null;


    public function getCData(): CData
    {
        $fuse_operations = Fuse::getInstance()->ffi->new('struct fuse_operations');
        $typed_cdata_wrapper = new TypedCDataWrapper();
        /**
         * @psalm-suppress RawObjectIteration
         * @var string $name
         * @var callable|null|bool $callable
         */
        foreach ($this as $name => $callable) {
            if (is_null($callable)) {
                continue;
            }
            if (substr_compare($name, 'flag_', 0, 5) === 0) {
                continue;
            }
            assert(is_callable($callable));
            if ($this->isDefault($callable)) {
                continue;
            }
            $fuse_operations->$name = $typed_cdata_wrapper->createWrapper($callable);
        }
        return $this->cdata_cache = $fuse_operations;
    }

    private function isDefault(callable $callable): bool
    {
        if (!is_array($callable)) {
            return false;
        }
        if (!is_object($callable[0])) {
            return false;
        }
        $class = new ReflectionClass(get_class($callable[0]));
        $method = $class->getMethod($callable[1]);
        $trait = new ReflectionClass(FilesystemDefaultImplementationTrait::class);
        return $method->getFileName() === $trait->getFileName();
    }

    public function getSize(): int
    {
        $typename = 'struct fuse_operations';
        $type = Fuse::getInstance()->ffi->type(
            $typename
        );
        $size = FFI::sizeof(
            $type
        );
        return $size;
    }

    public function getOperations(): FuseOperations
    {
        return $this;
    }
}
