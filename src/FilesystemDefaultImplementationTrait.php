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
use Fuse\Libc\Fuse\FuseDirHandle;
use Fuse\Libc\Fuse\FuseFileInfo;
use Fuse\Libc\Sys\Stat\Stat;

trait FilesystemDefaultImplementationTrait
{
    use MoubtableFilesystemTrait;

    /**
     * int (*getattr) (const char *, struct stat *);
     */
    public function getattr(string $path, Stat $stat): int
    {
        throw new FuseLogicException('not implemented');
    }

    /**
     * int (*readlink) (const char *, char *, size_t);
     */
    public function readlink(string $path, CData $buffer, int $size): int
    {
        throw new FuseLogicException('not implemented');
    }

    /**
     * int (*getdir) (const char *, fuse_dirh_t, fuse_dirfil_t);
     *
     * @psalm-param callable(CData $dirhandle, string $name, int $type, int $ino):int $dirfill
     * @deprecated
     */
    public function getdir(string $path, FuseDirHandle $dirhandle, callable $dirfill): int
    {
        throw new FuseLogicException('not implemented');
    }

    /**
     * int (*mknod) (const char *, mode_t, dev_t);
     */
    public function mknod(string $path, int $mode, int $dev): int
    {
        throw new FuseLogicException('not implemented');
    }

    /**
     * int (*mkdir) (const char *, mode_t);
     */
    public function mkdir(string $path, int $mode): int
    {
        throw new FuseLogicException('not implemented');
    }

    /**
     * int (*unlink) (const char *);
     */
    public function unlink(string $path): int
    {
        throw new FuseLogicException('not implemented');
    }

    /**
     * int (*rmdir) (const char *);
     */
    public function rmdir(string $path): int
    {
        throw new FuseLogicException('not implemented');
    }

    /**
     * int (*symlink) (const char *, const char *);
     */
    public function symlink(string $path, string $link): int
    {
        throw new FuseLogicException('not implemented');
    }

    /**
     * int (*rename) (const char *, const char *);
     */
    public function rename(string $from, string $to): int
    {
        throw new FuseLogicException('not implemented');
    }

    /**
     * int (*link) (const char *, const char *);
     */
    public function link(string $path, string $link): int
    {
        throw new FuseLogicException('not implemented');
    }

    /**
     * int (*chmod) (const char *, mode_t);
     */
    public function chmod(string $path, int $mode): int
    {
        throw new FuseLogicException('not implemented');
    }

    /**
     * int (*chown) (const char *, uid_t, gid_t);
     */
    public function chown(string $path, int $uid, int $gid): int
    {
        throw new FuseLogicException('not implemented');
    }

    /**
     * int (*truncate) (const char *, off_t);
     */
    public function truncate(string $path, int $offset): int
    {
        throw new FuseLogicException('not implemented');
    }

    /**
     * int (*utime) (const char *, struct utimbuf *);
     */
    public function utime(string $path, CData $utime_buf): int
    {
        throw new FuseLogicException('not implemented');
    }

    /**
     * int (*open) (const char *, struct fuse_file_info *);
     */
    public function open(string $path, FuseFileInfo $fuse_file_info): int
    {
        throw new FuseLogicException('not implemented');
    }

    /**
     * int (*read) (const char *, char *, size_t, off_t, struct fuse_file_info *);
     */
    public function read(string $path, CData $buffer, int $size, int $offset, FuseFileInfo $fuse_file_info): int
    {
        throw new FuseLogicException('not implemented');
    }

    /**
     * int (*write) (const char *, const char *, size_t, off_t, struct fuse_file_info *);
     */
    public function write(string $path, string $buffer, int $size, int $offset, FuseFileInfo $fuse_file_info): int
    {
        throw new FuseLogicException('not implemented');
    }

    /**
     * int (*statfs) (const char *, struct statvfs *);
     */
    public function statfs(string $path, CData $statvfs): int
    {
        throw new FuseLogicException('not implemented');
    }

    /**
     * int (*flush) (const char *, struct fuse_file_info *);
     */
    public function flush(string $path, FuseFileInfo $fuse_file_info): int
    {
        throw new FuseLogicException('not implemented');
    }

    /**
     * int (*release) (const char *, struct fuse_file_info *);
     */
    public function release(string $path, FuseFileInfo $fuse_file_info): int
    {
        throw new FuseLogicException('not implemented');
    }

    /**
     * int (*fsync) (const char *, int, struct fuse_file_info *);
     */
    public function fsync(string $path, int $flags, FuseFileInfo $fuse_file_info): int
    {
        throw new FuseLogicException('not implemented');
    }

    /**
     * int (*setxattr) (const char *, const char *, const char *, size_t, int);
     */
    public function setxattr(string $path, string $name, string $value, int $size): int
    {
        throw new FuseLogicException('not implemented');
    }

    /**
     * int (*getxattr) (const char *, const char *, char *, size_t);
     */
    public function getxattr(string $path, string $name, string &$value, int $size): int
    {
        throw new FuseLogicException('not implemented');
    }

    /**
     * int (*listxattr) (const char *, char *, size_t);*
     */
    public function listxattr(string $path, int $size): int
    {
        throw new FuseLogicException('not implemented');
    }

    /**
     * int (*removexattr) (const char *, const char *);
     */
    public function removexattr(string $size, string $name): int
    {
        throw new FuseLogicException('not implemented');
    }

    /**
     * int (*opendir) (const char *, struct fuse_file_info *);
     */
    public function opendir(string $path, FuseFileInfo $fuse_file_info): int
    {
        throw new FuseLogicException('not implemented');
    }

    /**
     * int (*readdir) (const char *, void *, fuse_fill_dir_t, off_t, struct fuse_file_info *);
     */
    public function readdir(string $path, CData $buf, CData $filler, int $offset, FuseFileInfo $fuse_file_info): int
    {
        throw new FuseLogicException('not implemented');
    }

    /**
     * int (*releasedir) (const char *, struct fuse_file_info *);
     */
    public function releasedir(string $path, FuseFileInfo $fuse_file_info): int
    {
        throw new FuseLogicException('not implemented');
    }

    /**
     * int (*fsyncdir) (const char *, int, struct fuse_file_info *);
     */
    public function fsyncdir(string $path, FuseFileInfo $fuse_file_info): int
    {
        throw new FuseLogicException('not implemented');
    }

    /**
     * void *(*init) (struct fuse_conn_info *conn);
     */
    public function init(CData $conn): ?CData
    {
        throw new FuseLogicException('not implemented');
    }

    /**
     * void (*destroy) (void *);
     */
    public function destroy(CData $private_data): void
    {
        throw new FuseLogicException('not implemented');
    }

    /**
     * int (*access) (const char *, int);
     */
    public function access(string $path, int $mode): int
    {
        throw new FuseLogicException('not implemented');
    }

    /**
     * int (*create) (const char *, mode_t, struct fuse_file_info *);
     */
    public function create(string $path, int $mode, FuseFileInfo $fuse_file_info): int
    {
        throw new FuseLogicException('not implemented');
    }

    /**
     * int (*ftruncate) (const char *, off_t, struct fuse_file_info *);
     */
    public function ftruncate(string $path, int $offset, FuseFileInfo $fuse_file_info): int
    {
        throw new FuseLogicException('not implemented');
    }

    /**
     * int (*fgetattr) (const char *, struct stat *, struct fuse_file_info *);
     */
    public function fgetattr(string $path, CData $stat, FuseFileInfo $fuse_file_info): int
    {
        throw new FuseLogicException('not implemented');
    }

    /**
     * int (*lock) (const char *, struct fuse_file_info *, int cmd, struct flock *);
     */
    public function lock(string $path, FuseFileInfo $fuse_file_info, int $cmd, CData $flock): int
    {
        throw new FuseLogicException('not implemented');
    }

    /**
     * int (*utimens) (const char *, const struct timespec tv[2]);
     */
    public function utimens(string $path, CData $tv): int
    {
        throw new FuseLogicException('not implemented');
    }

    /**
     * int (*bmap) (const char *, size_t blocksize, uint64_t *idx);
     */
    public function bmap(string $path, int $blocksize, CData $idx): int
    {
        throw new FuseLogicException('not implemented');
    }


    /**
     * unsigned int flag_nullpath_ok:1;
     * unsigned int flag_nopath:1;
     * unsigned int flag_utime_omit_ok:1;
     * unsigned int flag_reserved:29;
     */
    private bool $flag_nullpath_ok = false;
    private bool $flag_nopath = false;
    private bool $flag_utime_omit_ok = false;

    public function setFlagNullpathOk(bool $flag): void
    {
        $this->flag_nullpath_ok = $flag;
    }

    public function getFlagNullpathOk(): bool
    {
        return $this->flag_nullpath_ok;
    }

    public function setFlagNopath(bool $flag): void
    {
        $this->flag_nopath = $flag;
    }

    public function getFlagNopath(): bool
    {
        return $this->flag_nopath;
    }

    public function setFlagUtimeOmitOk(bool $flag): void
    {
        $this->flag_utime_omit_ok = $flag;
    }

    public function getFlagUtimeOmitOk(): bool
    {
        return $this->flag_utime_omit_ok;
    }

    /**
     * int (*ioctl) (const char *, int cmd, void *arg, struct fuse_file_info *, unsigned int flags, void *data);
     */
    public function ioctl(string $path, int $cmd, CData $arg, FuseFileInfo $fuse_file_info, int $flags, CData $data): int
    {
        throw new FuseLogicException('not implemented');
    }

    /**
     * int (*poll) (const char *, struct fuse_file_info *, struct fuse_pollhandle *ph, unsigned *reventsp);
     */
    public function poll(string $path, FuseFileInfo $fuse_file_info, CData $fuse_pollhandle, int &$reventsp): int
    {
        throw new FuseLogicException('not implemented');
    }

    /**
     * int (*write_buf) (const char *, struct fuse_bufvec *buf, off_t off, struct fuse_file_info *);
     */
    public function writeBuf(string $path, CData $buf, int $offset, FuseFileInfo $fuse_file_info): int
    {
        throw new FuseLogicException('not implemented');
    }

    /**
     * int (*read_buf) (const char *, struct fuse_bufvec **bufp, size_t size, off_t off, struct fuse_file_info *);
     */
    public function readBuf(string $path, CData $bufp, int $size, int $offset, FuseFileInfo $fuse_file_info): int
    {
        throw new FuseLogicException('not implemented');
    }

    /**
     * int (*flock) (const char *, struct fuse_file_info *, int op);
     */
    public function flock(string $path, FuseFileInfo $fuse_file_info, int $op): int
    {
        throw new FuseLogicException('not implemented');
    }

    /**
     * int (*fallocate) (const char *, int, off_t, off_t, struct fuse_file_info *);
     */
    public function fallocate(string $path, int $mode, int $offset, FuseFileInfo $fuse_file_info): int
    {
        throw new FuseLogicException('not implemented');
    }
}