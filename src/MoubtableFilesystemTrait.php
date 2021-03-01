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

trait MoubtableFilesystemTrait
{
    public function getOperations(): FuseOperations
    {
        $fuse_operations = new FuseOperations();

        $fuse_operations->getattr = [$this, 'getattr'];
        $fuse_operations->readlink = [$this, 'readlink'];
        $fuse_operations->getdir = [$this, 'getdir'];
        $fuse_operations->mknod = [$this, 'mknod'];
        $fuse_operations->mkdir = [$this, 'mkdir'];
        $fuse_operations->unlink = [$this, 'unlink'];
        $fuse_operations->rmdir = [$this, 'rmdir'];
        $fuse_operations->symlink = [$this, 'symlink'];
        $fuse_operations->rename = [$this, 'rename'];
        $fuse_operations->link = [$this, 'link'];
        $fuse_operations->chmod = [$this, 'chmod'];
        $fuse_operations->chown = [$this, 'chown'];
        $fuse_operations->truncate = [$this, 'truncate'];
        $fuse_operations->utime = [$this, 'utime'];
        $fuse_operations->open = [$this, 'open'];
        $fuse_operations->read = [$this, 'read'];
        $fuse_operations->write = [$this, 'write'];
        $fuse_operations->statfs = [$this, 'statfs'];
        $fuse_operations->flush = [$this, 'flush'];
        $fuse_operations->release = [$this, 'release'];
        $fuse_operations->fsync = [$this, 'fsync'];
        $fuse_operations->setxattr = [$this, 'setxattr'];
        $fuse_operations->getxattr = [$this, 'getxattr'];
        $fuse_operations->listxattr = [$this, 'listxattr'];
        $fuse_operations->removexattr = [$this, 'removexattr'];
        $fuse_operations->opendir = [$this, 'opendir'];
        $fuse_operations->readdir = [$this, 'readdir'];
        $fuse_operations->releasedir = [$this, 'releasedir'];
        $fuse_operations->fsyncdir = [$this, 'fsyncdir'];
        $fuse_operations->init = [$this, 'init'];
        $fuse_operations->destroy = [$this, 'destroy'];
        $fuse_operations->access = [$this, 'access'];
        $fuse_operations->create = [$this, 'create'];
        $fuse_operations->ftruncate = [$this, 'ftruncate'];
        $fuse_operations->fgetattr = [$this, 'fgetattr'];
        $fuse_operations->lock = [$this, 'lock'];
        $fuse_operations->utimens = [$this, 'utimens'];
        $fuse_operations->bmap = [$this, 'bmap'];
        $fuse_operations->ioctl = [$this, 'ioctl'];
        $fuse_operations->poll = [$this, 'poll'];
        $fuse_operations->write_buf = [$this, 'writeBuf'];
        $fuse_operations->read_buf = [$this, 'readBuf'];
        $fuse_operations->flock = [$this, 'flock'];
        $fuse_operations->fallocate = [$this, 'fallocate'];
        $fuse_operations->flag_nullpath_ok = $this->getFlagNullpathOk();
        $fuse_operations->flag_nopath = $this->getFlagNopath();
        $fuse_operations->flag_utime_omit_ok = $this->getFlagUtimeOmitOk();

        return $fuse_operations;
    }

    /**
     * int (*getattr) (const char *, struct stat *);
     */
    abstract public function getattr(string $path, CData $stat): int;

    /**
     * int (*readlink) (const char *, char *, size_t);
     */
    abstract public function readlink(string $path, CData $buffer, int $size): int;

    /**
     * int (*getdir) (const char *, fuse_dirh_t, fuse_dirfil_t);
     *
     * @psalm-param callable(CData $dirhandle, string $name, int $type, int $ino):int $dirfill
     * @deprecated
     */
    abstract public function getdir(string $path, CData $dirhandle, callable $dirfill): int;

    /**
     * int (*mknod) (const char *, mode_t, dev_t);
     */
    abstract public function mknod(string $path, int $mode, int $dev): int;

    /**
     * int (*mkdir) (const char *, mode_t);
     */
    abstract public function mkdir(string $path, int $mode): int;

    /**
     * int (*unlink) (const char *);
     */
    abstract public function unlink(string $path): int;

    /**
     * int (*rmdir) (const char *);
     */
    abstract public function rmdir(string $path): int;

    /**
     * int (*symlink) (const char *, const char *);
     */
    abstract public function symlink(string $path, string $link): int;

    /**
     * int (*rename) (const char *, const char *);
     */
    abstract public function rename(string $from, string $to): int;

    /**
     * int (*link) (const char *, const char *);
     */
    abstract public function link(string $path, string $link): int;

    /**
     * int (*chmod) (const char *, mode_t);
     */
    abstract public function chmod(string $path, int $mode): int;

    /**
     * int (*chown) (const char *, uid_t, gid_t);
     */
    abstract public function chown(string $path, int $uid, int $gid): int;

    /**
     * int (*truncate) (const char *, off_t);
     */
    abstract public function truncate(string $path, int $offset): int;

    /**
     * int (*utime) (const char *, struct utimbuf *);
     */
    abstract public function utime(string $path, CData $utime_buf): int;

    /**
     * int (*open) (const char *, struct fuse_file_info *);
     */
    abstract public function open(string $path, CData $fuse_file_info): int;

    /**
     * int (*read) (const char *, char *, size_t, off_t, struct fuse_file_info *);
     */
    abstract public function read(string $path, CData $buffer, int $size, int $offset, CData $fuse_file_info): int;

    /**
     * int (*write) (const char *, const char *, size_t, off_t, struct fuse_file_info *);
     */
    abstract public function write(string $path, string $buffer, int $size, int $offset, CData $fuse_file_info): int;

    /**
     * int (*statfs) (const char *, struct statvfs *);
     */
    abstract public function statfs(string $path, CData $statvfs): int;

    /**
     * int (*flush) (const char *, struct fuse_file_info *);
     */
    abstract public function flush(string $path, CData $fuse_file_info): int;

    /**
     * int (*release) (const char *, struct fuse_file_info *);
     */
    abstract public function release(string $path, CData $fuse_file_info): int;

    /**
     * int (*fsync) (const char *, int, struct fuse_file_info *);
     */
    abstract public function fsync(string $path, int $flags, CData $fuse_file_info): int;

    /**
     * int (*setxattr) (const char *, const char *, const char *, size_t, int);
     */
    abstract public function setxattr(string $path, string $name, string $value, int $size): int;

    /**
     * int (*getxattr) (const char *, const char *, char *, size_t);
     */
    abstract public function getxattr(string $path, string $name, string &$value, int $size): int;

    /**
     * int (*listxattr) (const char *, char *, size_t);*
     */
    abstract public function listxattr(string $path, int $size): int;

    /**
     * int (*removexattr) (const char *, const char *);
     */
    abstract public function removexattr(string $size, string $name): int;

    /**
     * int (*opendir) (const char *, struct fuse_file_info *);
     */
    abstract public function opendir(string $path, CData $fuse_file_info): int;

    /**
     * int (*readdir) (const char *, void *, fuse_fill_dir_t, off_t, struct fuse_file_info *);
     */
    abstract public function readdir(string $path, CData $buf, CData $filler, int $offset, CData $fuse_file_info): int;

    /**
     * int (*releasedir) (const char *, struct fuse_file_info *);
     */
    abstract public function releasedir(string $path, CData $fuse_file_info): int;

    /**
     * int (*fsyncdir) (const char *, int, struct fuse_file_info *);
     */
    abstract public function fsyncdir(string $path, CData $fuse_file_info): int;

    /**
     * void *(*init) (struct fuse_conn_info *conn);
     */
    abstract public function init(CData $conn): ?CData;

    /**
     * void (*destroy) (void *);
     */
    abstract public function destroy(CData $private_data): void;

    /**
     * int (*access) (const char *, int);
     */
    abstract public function access(string $path, int $mode): int;

    /**
     * int (*create) (const char *, mode_t, struct fuse_file_info *);
     */
    abstract public function create(string $path, int $mode, CData $fuse_file_info): int;

    /**
     * int (*ftruncate) (const char *, off_t, struct fuse_file_info *);
     */
    abstract public function ftruncate(string $path, int $offset, CData $fuse_file_info): int;

    /**
     * int (*fgetattr) (const char *, struct stat *, struct fuse_file_info *);
     */
    abstract public function fgetattr(string $path, CData $stat, CData $fuse_file_info): int;

    /**
     * int (*lock) (const char *, struct fuse_file_info *, int cmd, struct flock *);
     */
    abstract public function lock(string $path, CData $fuse_file_info, int $cmd, CData $flock): int;

    /**
     * int (*utimens) (const char *, const struct timespec tv[2]);
     */
    abstract public function utimens(string $path, CData $tv): int;

    /**
     * int (*bmap) (const char *, size_t blocksize, uint64_t *idx);
     */
    abstract public function bmap(string $path, int $blocksize, CData $idx): int;

    /**
     * unsigned int flag_nullpath_ok:1;
     * unsigned int flag_nopath:1;
     * unsigned int flag_utime_omit_ok:1;
     * unsigned int flag_reserved:29;
     */
    abstract public function setFlagNullpathOk(bool $flag): void;
    abstract public function getFlagNullpathOk(): bool;
    abstract public function setFlagNopath(bool $flag): void;
    abstract public function getFlagNopath(): bool;
    abstract public function setFlagUtimeOmitOk(bool $flag): void;
    abstract public function getFlagUtimeOmitOk(): bool;

    /**
     * int (*ioctl) (const char *, int cmd, void *arg, struct fuse_file_info *, unsigned int flags, void *data);
     */
    abstract public function ioctl(string $path, int $cmd, CData $arg, CData $fuse_file_info, int $flags, CData $data): int;

    /**
     * int (*poll) (const char *, struct fuse_file_info *, struct fuse_pollhandle *ph, unsigned *reventsp);
     */
    abstract public function poll(string $path, CData $fuse_file_info, CData $fuse_pollhandle, int &$reventsp): int;

    /**
     * int (*write_buf) (const char *, struct fuse_bufvec *buf, off_t off, struct fuse_file_info *);
     */
    abstract public function writeBuf(string $path, CData $buf, int $offset, CData $fuse_file_info): int;

    /**
     * int (*read_buf) (const char *, struct fuse_bufvec **bufp, size_t size, off_t off, struct fuse_file_info *);
     */
    abstract public function readBuf(string $path, CData $bufp, int $size, int $offset, CData $fuse_file_info): int;

    /**
     * int (*flock) (const char *, struct fuse_file_info *, int op);
     */
    abstract public function flock(string $path, CData $fuse_file_info, int $op): int;

    /**
     * int (*fallocate) (const char *, int, off_t, off_t, struct fuse_file_info *);
     */
    abstract public function fallocate(string $path, int $mode, int $offset, CData $fuse_file_info): int;
}