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

use Fuse\Libc\Errno\Errno;
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
use Fuse\Libc\Time\TimeSpec;
use Fuse\Libc\Utime\UtimBuf;
use TypedCData\TypedCDataArray;

trait FilesystemDefaultImplementationTrait
{
    use MountableFilesystemTrait;
    use FilesystemFlagsImplementationTrait;

    /**
     * int (*getattr) (const char *, struct stat *);
     */
    public function getattr(string $path, Stat $stat): int
    {
        return -Errno::ENOSYS;
    }

    /**
     * int (*readlink) (const char *, char *, size_t);
     */
    public function readlink(string $path, CStringBuffer $buffer, int $size): int
    {
        return -Errno::ENOSYS;
    }

    /**
     * int (*getdir) (const char *, fuse_dirh_t, fuse_dirfil_t);
     *
     * @deprecated
     */
    public function getdir(string $path, FuseDirHandle $dirhandle, FuseDirFill $dirfill): int
    {
        return -Errno::ENOSYS;
    }

    /**
     * int (*mknod) (const char *, mode_t, dev_t);
     */
    public function mknod(string $path, int $mode, int $dev): int
    {
        return -Errno::ENOSYS;
    }

    /**
     * int (*mkdir) (const char *, mode_t);
     */
    public function mkdir(string $path, int $mode): int
    {
        return -Errno::ENOSYS;
    }

    /**
     * int (*unlink) (const char *);
     */
    public function unlink(string $path): int
    {
        return -Errno::ENOSYS;
    }

    /**
     * int (*rmdir) (const char *);
     */
    public function rmdir(string $path): int
    {
        return -Errno::ENOSYS;
    }

    /**
     * int (*symlink) (const char *, const char *);
     */
    public function symlink(string $path, string $link): int
    {
        return -Errno::ENOSYS;
    }

    /**
     * int (*rename) (const char *, const char *);
     */
    public function rename(string $from, string $to): int
    {
        return -Errno::ENOSYS;
    }

    /**
     * int (*link) (const char *, const char *);
     */
    public function link(string $path, string $link): int
    {
        return -Errno::ENOSYS;
    }

    /**
     * int (*chmod) (const char *, mode_t);
     */
    public function chmod(string $path, int $mode): int
    {
        return -Errno::ENOSYS;
    }

    /**
     * int (*chown) (const char *, uid_t, gid_t);
     */
    public function chown(string $path, int $uid, int $gid): int
    {
        return -Errno::ENOSYS;
    }

    /**
     * int (*truncate) (const char *, off_t);
     */
    public function truncate(string $path, int $offset): int
    {
        return -Errno::ENOSYS;
    }

    /**
     * int (*utime) (const char *, struct utimbuf *);
     */
    public function utime(string $path, UtimBuf $utime_buf): int
    {
        return -Errno::ENOSYS;
    }

    /**
     * int (*open) (const char *, struct fuse_file_info *);
     */
    public function open(string $path, FuseFileInfo $fuse_file_info): int
    {
        return 0;
    }

    /**
     * int (*read) (const char *, char *, size_t, off_t, struct fuse_file_info *);
     */
    public function read(string $path, CBytesBuffer $buffer, int $size, int $offset, FuseFileInfo $fuse_file_info): int
    {
        return -Errno::ENOSYS;
    }

    /**
     * int (*write) (const char *, const char *, size_t, off_t, struct fuse_file_info *);
     */
    public function write(string $path, string $buffer, int $size, int $offset, FuseFileInfo $fuse_file_info): int
    {
        return -Errno::ENOSYS;
    }

    /**
     * int (*statfs) (const char *, struct statvfs *);
     */
    public function statfs(string $path, StatVfs $statvfs): int
    {
        return -Errno::ENOSYS;
    }

    /**
     * int (*flush) (const char *, struct fuse_file_info *);
     */
    public function flush(string $path, FuseFileInfo $fuse_file_info): int
    {
        return -Errno::ENOSYS;
    }

    /**
     * int (*release) (const char *, struct fuse_file_info *);
     */
    public function release(string $path, FuseFileInfo $fuse_file_info): int
    {
        return -Errno::ENOSYS;
    }

    /**
     * int (*fsync) (const char *, int, struct fuse_file_info *);
     */
    public function fsync(string $path, int $flags, FuseFileInfo $fuse_file_info): int
    {
        return -Errno::ENOSYS;
    }

    /**
     * int (*setxattr) (const char *, const char *, const char *, size_t, int);
     */
    public function setxattr(string $path, string $name, string $value, int $size): int
    {
        return -Errno::ENOSYS;
    }

    /**
     * int (*getxattr) (const char *, const char *, char *, size_t);
     */
    public function getxattr(string $path, string $name, ?string &$value, int $size): int
    {
        return -Errno::ENOSYS;
    }

    /**
     * int (*listxattr) (const char *, char *, size_t);*
     */
    public function listxattr(string $path, ?string &$value, int $size): int
    {
        return -Errno::ENOSYS;
    }

    /**
     * int (*removexattr) (const char *, const char *);
     */
    public function removexattr(string $path, string $name): int
    {
        return -Errno::ENOSYS;
    }

    /**
     * int (*opendir) (const char *, struct fuse_file_info *);
     */
    public function opendir(string $path, FuseFileInfo $fuse_file_info): int
    {
        return 0;
    }

    /**
     * int (*readdir) (const char *, void *, fuse_fill_dir_t, off_t, struct fuse_file_info *);
     */
    public function readdir(
        string $path,
        FuseReadDirBuffer $buf,
        FuseFillDir $filler,
        int $offset,
        FuseFileInfo $fuse_file_info
    ): int {
        return -Errno::ENOSYS;
    }

    /**
     * int (*releasedir) (const char *, struct fuse_file_info *);
     */
    public function releasedir(string $path, FuseFileInfo $fuse_file_info): int
    {
        return -Errno::ENOSYS;
    }

    /**
     * int (*fsyncdir) (const char *, int, struct fuse_file_info *);
     */
    public function fsyncdir(string $path, FuseFileInfo $fuse_file_info): int
    {
        return -Errno::ENOSYS;
    }

    /**
     * void *(*init) (struct fuse_conn_info *conn);
     */
    public function init(FuseConnInfo $conn): ?FusePrivateData
    {
        return null;
    }

    /**
     * void (*destroy) (void *);
     */
    public function destroy(?FusePrivateData $private_data): void
    {
    }

    /**
     * int (*access) (const char *, int);
     */
    public function access(string $path, int $mode): int
    {
        return -Errno::ENOSYS;
    }

    /**
     * int (*create) (const char *, mode_t, struct fuse_file_info *);
     */
    public function create(string $path, int $mode, FuseFileInfo $fuse_file_info): int
    {
        return -Errno::ENOSYS;
    }

    /**
     * int (*ftruncate) (const char *, off_t, struct fuse_file_info *);
     */
    public function ftruncate(string $path, int $offset, FuseFileInfo $fuse_file_info): int
    {
        return -Errno::ENOSYS;
    }

    /**
     * int (*fgetattr) (const char *, struct stat *, struct fuse_file_info *);
     */
    public function fgetattr(string $path, Stat $stat, FuseFileInfo $fuse_file_info): int
    {
        return -Errno::ENOSYS;
    }

    /**
     * int (*lock) (const char *, struct fuse_file_info *, int cmd, struct flock *);
     */
    public function lock(string $path, FuseFileInfo $fuse_file_info, int $cmd, Flock $flock): int
    {
        return -Errno::ENOSYS;
    }

    /**
     * int (*utimens) (const char *, const struct timespec tv[2]);
     *
     * @param TypedCDataArray<TimeSpec> $tv
     */
    public function utimens(string $path, TypedCDataArray $tv): int
    {
        return -Errno::ENOSYS;
    }

    /**
     * int (*bmap) (const char *, size_t blocksize, uint64_t *idx);
     */
    public function bmap(string $path, int $blocksize, int &$idx): int
    {
        return -Errno::ENOSYS;
    }

    /**
     * int (*ioctl) (const char *, int cmd, void *arg, struct fuse_file_info *, unsigned int flags, void *data);
     */
    public function ioctl(
        string $path,
        int $cmd,
        FuseIoctlArgPointer $arg,
        FuseFileInfo $fuse_file_info,
        int $flags,
        FuseIoctlDataPointer $data
    ): int {
        return -Errno::ENOSYS;
    }

    /**
     * int (*poll) (const char *, struct fuse_file_info *, struct fuse_pollhandle *ph, unsigned *reventsp);
     */
    public function poll(
        string $path,
        FuseFileInfo $fuse_file_info,
        FusePollHandle $fuse_pollhandle,
        int &$reventsp
    ): int {
        return -Errno::ENOSYS;
    }

    /**
     * int (*write_buf) (const char *, struct fuse_bufvec *buf, off_t off, struct fuse_file_info *);
     */
    public function writeBuf(string $path, FuseBufVec $buf, int $offset, FuseFileInfo $fuse_file_info): int
    {
        return -Errno::ENOSYS;
    }

    /**
     * int (*read_buf) (const char *, struct fuse_bufvec **bufp, size_t size, off_t off, struct fuse_file_info *);
     *
     * @param TypedCDataArray<FuseBufVec> $bufp
     */
    public function readBuf(
        string $path,
        TypedCDataArray $bufp,
        int $size,
        int $offset,
        FuseFileInfo $fuse_file_info
    ): int {
        return -Errno::ENOSYS;
    }

    /**
     * int (*flock) (const char *, struct fuse_file_info *, int op);
     */
    public function flock(string $path, FuseFileInfo $fuse_file_info, int $op): int
    {
        return -Errno::ENOSYS;
    }

    /**
     * int (*fallocate) (const char *, int, off_t, off_t, struct fuse_file_info *);
     */
    public function fallocate(string $path, int $mode, int $offset, FuseFileInfo $fuse_file_info): int
    {
        return -Errno::ENOSYS;
    }
}
