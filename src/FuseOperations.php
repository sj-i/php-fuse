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

use Closure;
use FFI\CData;

final class FuseOperations
{
    /**
     * int (*getattr) (const char *, struct stat *);
     *
     * @var null|callable(string $path, CData $stat): int
     */
    public $getattr = null;

    /**
     * int (*readlink) (const char *, char *, size_t);
     *
     * @var null|callable(string $path, CData $buffer, int $size): int
     */
    public $readlink = null;

    /**
     * int (*getdir) (const char *, fuse_dirh_t, fuse_dirfil_t);
     *
     * @deprecated
     * @var null|callable(string $path, CData $dirhandle, callable(CData $dirhandle, string $name, int $type, int $ino)): int
     */
    public $getdir = null;

    /**
     * int (*mknod) (const char *, mode_t, dev_t);
     *
     * @var null|callable
     */
    public $mknod = null;

    /**
     * int (*mkdir) (const char *, mode_t);
     *
     * @var null|callable
     */
    public $mkdir = null;

    /**
     * int (*unlink) (const char *);
     *
     * @var null|callable
     */
    public $unlink = null;

    /**
     * int (*rmdir) (const char *);
     *
     * @var null|callable
     */
    public $rmdir = null;

    /**
     * int (*symlink) (const char *, const char *);
     *
     * @var null|callable
     */
    public $symlink = null;

    /**
     * int (*rename) (const char *, const char *);
     *
     * @var null|callable
     */
    public $rename = null;

    /**
     * int (*link) (const char *, const char *);
     *
     * @var null|callable
     */
    public $link = null;

    /**
     * int (*chmod) (const char *, mode_t);
     *
     * @var null|callable
     */
    public $chmod = null;

    /**
     * int (*chown) (const char *, uid_t, gid_t);
     *
     * @var null|callable
     */
    public $chown = null;

    /**
     * int (*truncate) (const char *, off_t);
     *
     * @var null|callable
     */
    public $truncate = null;

    /**
     * int (*utime) (const char *, struct utimbuf *);
     *
     * @var null|callable
     */
    public $utime = null;

    /**
     * int (*open) (const char *, struct fuse_file_info *);
     *
     * @var null|callable
     */
    public $open = null;

    /**
     * int (*read) (const char *, char *, size_t, off_t, struct fuse_file_info *);
     *
     * @var null|callable
     */
    public $read = null;

    /**
     * int (*write) (const char *, const char *, size_t, off_t, struct fuse_file_info *);
     *
     * @var null|callable
     */
    public $write = null;

    /**
     * int (*statfs) (const char *, struct statvfs *);
     *
     * @var null|callable
     */
    public $statfs = null;

    /**
     * int (*flush) (const char *, struct fuse_file_info *);
     *
     * @var null|callable
     */
    public $flush = null;

    /**
     * int (*release) (const char *, struct fuse_file_info *);
     *
     * @var null|callable
     */
    public $release = null;

    /**
     * int (*fsync) (const char *, int, struct fuse_file_info *);
     *
     * @var null|callable
     */
    public $fsync = null;

    /**
     * int (*setxattr) (const char *, const char *, const char *, size_t, int);
     *
     * @var null|callable
     */
    public $setxattr = null;

    /**
     * int (*getxattr) (const char *, const char *, char *, size_t);
     *
     * @var null|callable
     */
    public $getxattr = null;

    /**
     * int (*listxattr) (const char *, char *, size_t);
     *
     * @var null|callable
     */
    public $listxattr = null;

    /**
     * int (*removexattr) (const char *, const char *);
     *
     * @var null|callable
     */
    public $removexattr = null;

    /**
     * int (*opendir) (const char *, struct fuse_file_info *);
     *
     * @var null|callable
     */
    public $opendir = null;

    /**
     * int (*readdir) (const char *, void *, fuse_fill_dir_t, off_t, struct fuse_file_info *);
     *
     * @var null|callable
     */
    public $readdir = null;

    /**
     * int (*releasedir) (const char *, struct fuse_file_info *);
     *
     * @var null|callable
     */
    public $releasedir = null;

    /**
     * int (*fsyncdir) (const char *, int, struct fuse_file_info *);
     *
     * @var null|callable
     */
    public $fsyncdir = null;

    /**
     * void *(*init) (struct fuse_conn_info *conn);
     *
     * @var null|callable
     */
    public $init = null;

    /**
     * void (*destroy) (void *);
     *
     * @var null|callable
     */
    public $destroy = null;

    /**
     * int (*access) (const char *, int);
     *
     * @var null|callable
     */
    public $access = null;

    /**
     * int (*create) (const char *, mode_t, struct fuse_file_info *);
     *
     * @var null|callable
     */
    public $create = null;

    /**
     * int (*ftruncate) (const char *, off_t, struct fuse_file_info *);
     *
     * @var null|callable
     */
    public $ftruncate = null;

    /**
     * int (*fgetattr) (const char *, struct stat *, struct fuse_file_info *);
     *
     * @var null|callable
     */
    public $fgetattr = null;

    /**
     * int (*lock) (const char *, struct fuse_file_info *, int cmd, struct flock *);
     *
     * @var null|callable
     */
    public $lock = null;

    /**
     * int (*utimens) (const char *, const struct timespec tv[2]);
     *
     * @var null|callable
     */
    public $utimens = null;

    /**
     * int (*bmap) (const char *, size_t blocksize, uint64_t *idx);
     *
     * @var null|callable
     */
    public $bmap = null;

    /**
     * unsigned int flag_nullpath_ok:1;
     * unsigned int flag_nopath:1;
     * unsigned int flag_utime_omit_ok:1;
     * unsigned int flag_reserved:29;
     */
    public bool $flag_nullpath_ok;
    public bool $flag_nopath;
    public bool $flag_utime_omit_ok;

    /**
     * int (*ioctl) (const char *, int cmd, void *arg, struct fuse_file_info *, unsigned int flags, void *data);
     *
     * @var null|callable
     */
    public $ioctl = null;

    /**
     * int (*poll) (const char *, struct fuse_file_info *, struct fuse_pollhandle *ph, unsigned *reventsp);
     *
     * @var null|callable
     */
    public $poll = null;

    /**
     * int (*write_buf) (const char *, struct fuse_bufvec *buf, off_t off, struct fuse_file_info *);
     *
     * @var null|callable
     */
    public $write_buf = null;

    /**
     * int (*read_buf) (const char *, struct fuse_bufvec **bufp, size_t size, off_t off, struct fuse_file_info *);
     *
     * @var null|callable
     */
    public $read_buf = null;

    /**
     * int (*flock) (const char *, struct fuse_file_info *, int op);
     *
     * @var null|callable
     */
    public $flock = null;

    /**
     * int (*fallocate) (const char *, int, off_t, off_t, struct fuse_file_info *);
     *
     * @var null|callable
     */
    public $fallocate = null;

    private CData $cdata_cache;


    public function getCData(): CData
    {
        $fuse_operations = Fuse::getInstance()->ffi->new('struct fuse_operations');
        foreach ($this as $name => $callable) {
            if (is_null($callable)) {
                continue;
            }
            $fuse_operations->$name = Closure::fromCallable($callable);
        }
        return $this->cdata_cache = $fuse_operations;
    }
}
