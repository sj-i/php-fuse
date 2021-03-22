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

use FFI\CData;
use Fuse\FFI\TypedCDataArray;
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
use Fuse\Libc\String\CStringBuffer;
use Fuse\Libc\Sys\Stat\Stat;
use Fuse\Libc\Sys\StatVfs\StatVfs;
use Fuse\Libc\Time\TimeSpec;
use Fuse\Libc\Utime\UtimBuf;

interface FilesystemInterface extends Mountable
{
    /**
     * int (*getattr) (const char *, struct stat *);
     */
    public function getattr(string $path, Stat $stat): int;

    /**
     * int (*readlink) (const char *, char *, size_t);
     */
    public function readlink(string $path, CStringBuffer $buffer, int $size): int;

    /**
     * int (*getdir) (const char *, fuse_dirh_t, fuse_dirfil_t);
     *
     * @deprecated
     */
    public function getdir(string $path, FuseDirHandle $dirhandle, FuseDirFill $dirfill): int;

    /**
     * int (*mknod) (const char *, mode_t, dev_t);
     */
    public function mknod(string $path, int $mode, int $dev): int;

    /**
     * int (*mkdir) (const char *, mode_t);
     */
    public function mkdir(string $path, int $mode): int;

    /**
     * int (*unlink) (const char *);
     */
    public function unlink(string $path): int;

    /**
     * int (*rmdir) (const char *);
     */
    public function rmdir(string $path): int;

    /**
     * int (*symlink) (const char *, const char *);
     */
    public function symlink(string $path, string $link): int;

    /**
     * int (*rename) (const char *, const char *);
     */
    public function rename(string $from, string $to): int;

    /**
     * int (*link) (const char *, const char *);
     */
    public function link(string $path, string $link): int;

    /**
     * int (*chmod) (const char *, mode_t);
     */
    public function chmod(string $path, int $mode): int;

    /**
     * int (*chown) (const char *, uid_t, gid_t);
     */
    public function chown(string $path, int $uid, int $gid): int;

    /**
     * int (*truncate) (const char *, off_t);
     */
    public function truncate(string $path, int $offset): int;

    /**
     * int (*utime) (const char *, struct utimbuf *);
     */
    public function utime(string $path, UtimBuf $utime_buf): int;

    /**
     * int (*open) (const char *, struct fuse_file_info *);
     */
    public function open(string $path, FuseFileInfo $fuse_file_info): int;

    /**
     * int (*read) (const char *, char *, size_t, off_t, struct fuse_file_info *);
     */
    public function read(string $path, CData $buffer, int $size, int $offset, FuseFileInfo $fuse_file_info): int;

    /**
     * int (*write) (const char *, const char *, size_t, off_t, struct fuse_file_info *);
     */
    public function write(string $path, string $buffer, int $size, int $offset, FuseFileInfo $fuse_file_info): int;

    /**
     * int (*statfs) (const char *, struct statvfs *);
     */
    public function statfs(string $path, StatVfs $statvfs): int;

    /**
     * int (*flush) (const char *, struct fuse_file_info *);
     */
    public function flush(string $path, FuseFileInfo $fuse_file_info): int;

    /**
     * int (*release) (const char *, struct fuse_file_info *);
     */
    public function release(string $path, FuseFileInfo $fuse_file_info): int;

    /**
     * int (*fsync) (const char *, int, struct fuse_file_info *);
     */
    public function fsync(string $path, int $flags, FuseFileInfo $fuse_file_info): int;

    /**
     * int (*setxattr) (const char *, const char *, const char *, size_t, int);
     */
    public function setxattr(string $path, string $name, string $value, int $size): int;

    /**
     * int (*getxattr) (const char *, const char *, char *, size_t);
     */
    public function getxattr(string $path, string $name, string &$value, int $size): int;

    /**
     * int (*listxattr) (const char *, char *, size_t);*
     */
    public function listxattr(string $path, int $size): int;

    /**
     * int (*removexattr) (const char *, const char *);
     */
    public function removexattr(string $size, string $name): int;

    /**
     * int (*opendir) (const char *, struct fuse_file_info *);
     */
    public function opendir(string $path, FuseFileInfo $fuse_file_info): int;

    /**
     * int (*readdir) (const char *, void *, fuse_fill_dir_t, off_t, struct fuse_file_info *);
     *
     * @psalm-param callable(CData $buf, string $name, Stat $stbuf, int $off):int $filler
     */
    public function readdir(string $path, FuseReadDirBuffer $buf, FuseFillDir $filler, int $offset, FuseFileInfo $fuse_file_info): int;

    /**
     * int (*releasedir) (const char *, struct fuse_file_info *);
     */
    public function releasedir(string $path, FuseFileInfo $fuse_file_info): int;

    /**
     * int (*fsyncdir) (const char *, int, struct fuse_file_info *);
     */
    public function fsyncdir(string $path, FuseFileInfo $fuse_file_info): int;

    /**
     * void *(*init) (struct fuse_conn_info *conn);
     */
    public function init(FuseConnInfo $conn): ?FusePrivateData;

    /**
     * void (*destroy) (void *);
     */
    public function destroy(FusePrivateData $private_data): void;

    /**
     * int (*access) (const char *, int);
     */
    public function access(string $path, int $mode): int;

    /**
     * int (*create) (const char *, mode_t, struct fuse_file_info *);
     */
    public function create(string $path, int $mode, FuseFileInfo $fuse_file_info): int;

    /**
     * int (*ftruncate) (const char *, off_t, struct fuse_file_info *);
     */
    public function ftruncate(string $path, int $offset, FuseFileInfo $fuse_file_info): int;

    /**
     * int (*fgetattr) (const char *, struct stat *, struct fuse_file_info *);
     */
    public function fgetattr(string $path, Stat $stat, FuseFileInfo $fuse_file_info): int;

    /**
     * int (*lock) (const char *, struct fuse_file_info *, int cmd, struct flock *);
     */
    public function lock(string $path, FuseFileInfo $fuse_file_info, int $cmd, Flock $flock): int;

    /**
     * int (*utimens) (const char *, const struct timespec tv[2]);
     *
     * @param TypedCDataArray<TimeSpec> $tv
     */
    public function utimens(string $path, TypedCDataArray $tv): int;

    /**
     * int (*bmap) (const char *, size_t blocksize, uint64_t *idx);
     */
    public function bmap(string $path, int $blocksize, CData $idx): int;

    /**
     * unsigned int flag_nullpath_ok:1;
     * unsigned int flag_nopath:1;
     * unsigned int flag_utime_omit_ok:1;
     * unsigned int flag_reserved:29;
     */
    public function setFlagNullpathOk(bool $flag): void;
    public function getFlagNullpathOk(): bool;
    public function setFlagNopath(bool $flag): void;
    public function getFlagNopath(): bool;
    public function setFlagUtimeOmitOk(bool $flag): void;
    public function getFlagUtimeOmitOk(): bool;

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
    ): int;

    /**
     * int (*poll) (const char *, struct fuse_file_info *, struct fuse_pollhandle *ph, unsigned *reventsp);
     */
    public function poll(string $path, FuseFileInfo $fuse_file_info, FusePollHandle $fuse_pollhandle, int &$reventsp): int;

    /**
     * int (*write_buf) (const char *, struct fuse_bufvec *buf, off_t off, struct fuse_file_info *);
     */
    public function writeBuf(string $path, FuseBufVec $buf, int $offset, FuseFileInfo $fuse_file_info): int;

    /**
     * int (*read_buf) (const char *, struct fuse_bufvec **bufp, size_t size, off_t off, struct fuse_file_info *);
     */
    public function readBuf(string $path, FuseBufVec $bufp, int $size, int $offset, FuseFileInfo $fuse_file_info): int;

    /**
     * int (*flock) (const char *, struct fuse_file_info *, int op);
     */
    public function flock(string $path, FuseFileInfo $fuse_file_info, int $op): int;

    /**
     * int (*fallocate) (const char *, int, off_t, off_t, struct fuse_file_info *);
     */
    public function fallocate(string $path, int $mode, int $offset, FuseFileInfo $fuse_file_info): int;
}
