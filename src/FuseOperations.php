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

final class FuseOperations
{
    /**
     * int (*getattr) (const char *, struct stat *);
     *
     * @var null|callable(string $path, CData $stat): int
     */
    public $getattr;

    /**
     * int (*readlink) (const char *, char *, size_t);
     *
     * @var null|callable(string $path, CData $buffer, int $size): int
     */
    public $readlink;

    /**
     * int (*getdir) (const char *, fuse_dirh_t, fuse_dirfil_t);
     *
     * @deprecated
     * @var null|callable(string $path, CData $dirhandle, callable(CData $dirhandle, string $name, int $type, int $ino)): int
     */
    public $getdir;

    /**
     * int (*mknod) (const char *, mode_t, dev_t);
     *
     * @var null|callable
     */
    public $mknod;

    /**
     * int (*mkdir) (const char *, mode_t);
     *
     * @var null|callable
     */
    public $mkdir;

    /**
     * int (*unlink) (const char *);
     *
     * @var null|callable
     */
    public $unlink;

    /**
     * int (*rmdir) (const char *);
     *
     * @var null|callable
     */
    public $rmdir;

    /**
     * int (*symlink) (const char *, const char *);
     *
     * @var null|callable
     */
    public $symlink;

    /**
     * int (*rename) (const char *, const char *);
     *
     * @var null|callable
     */
    public $rename;

    /**
     * int (*link) (const char *, const char *);
     *
     * @var null|callable
     */
    public $link;

    /**
     * int (*chmod) (const char *, mode_t);
     *
     * @var null|callable
     */
    public $chmod;

    /**
     * int (*chown) (const char *, uid_t, gid_t);
     *
     * @var null|callable
     */
    public $chown;

    /**
     * int (*truncate) (const char *, off_t);
     *
     * @var null|callable
     */
    public $truncate;

    /**
     * int (*utime) (const char *, struct utimbuf *);
     *
     * @var null|callable
     */
    public $utime;

    /**
     * int (*open) (const char *, struct fuse_file_info *);
     *
     * @var null|callable
     */
    public $open;

    /**
     * int (*read) (const char *, char *, size_t, off_t, struct fuse_file_info *);
     *
     * @var null|callable
     */
    public $read;

    /**
     * int (*write) (const char *, const char *, size_t, off_t, struct fuse_file_info *);
     *
     * @var null|callable
     */
    public $write;

    /**
     * int (*statfs) (const char *, struct statvfs *);
     *
     * @var null|callable
     */
    public $statfs;

    /**
     * int (*flush) (const char *, struct fuse_file_info *);
     *
     * @var null|callable
     */
    public $flush;

    /**
     * int (*release) (const char *, struct fuse_file_info *);
     *
     * @var null|callable
     */
    public $release;

    /**
     * int (*fsync) (const char *, int, struct fuse_file_info *);
     *
     * @var null|callable
     */
    public $fsync;

    /**
     * int (*setxattr) (const char *, const char *, const char *, size_t, int);
     *
     * @var null|callable
     */
    public $setxattr;

    /**
     * int (*getxattr) (const char *, const char *, char *, size_t);
     *
     * @var null|callable
     */
    public $getxattr;

    /**
     * int (*listxattr) (const char *, char *, size_t);
     *
     * @var null|callable
     */
    public $listxattr;

    /**
     * int (*removexattr) (const char *, const char *);
     *
     * @var null|callable
     */
    public $removexattr;

    /**
     * int (*opendir) (const char *, struct fuse_file_info *);
     *
     * @var null|callable
     */
    public $opendir;

    /**
     * int (*readdir) (const char *, void *, fuse_fill_dir_t, off_t, struct fuse_file_info *);
     *
     * @var null|callable
     */
    public $readdir;

    /**
     * int (*releasedir) (const char *, struct fuse_file_info *);
     *
     * @var null|callable
     */
    public $releasedir;

    /**
     * int (*fsyncdir) (const char *, int, struct fuse_file_info *);
     *
     * @var null|callable
     */
    public $fsyncdir;

    /**
     * void *(*init) (struct fuse_conn_info *conn);
     *
     * @var null|callable
     */
    public $init;

    /**
     * void (*destroy) (void *);
     *
     * @var null|callable
     */
    public $destroy;

    /**
     * int (*access) (const char *, int);
     *
     * @var null|callable
     */
    public $access;

    /**
     * int (*create) (const char *, mode_t, struct fuse_file_info *);
     *
     * @var null|callable
     */
    public $create;

    /**
     * int (*ftruncate) (const char *, off_t, struct fuse_file_info *);
     *
     * @var null|callable
     */
    public $ftruncate;

    /**
     * int (*fgetattr) (const char *, struct stat *, struct fuse_file_info *);
     *
     * @var null|callable
     */
    public $fgetattr;

    /**
     * int (*lock) (const char *, struct fuse_file_info *, int cmd, struct flock *);
     *
     * @var null|callable
     */
    public $lock;

    /**
     * int (*utimens) (const char *, const struct timespec tv[2]);
     *
     * @var null|callable
     */
    public $utimens;

    /**
     * int (*bmap) (const char *, size_t blocksize, uint64_t *idx);
     *
     * @var null|callable
     */
    public $bmap;

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
    public $ioctl;

    /**
     * int (*poll) (const char *, struct fuse_file_info *, struct fuse_pollhandle *ph, unsigned *reventsp);
     *
     * @var null|callable
     */
    public $poll;

    /**
     * int (*write_buf) (const char *, struct fuse_bufvec *buf, off_t off, struct fuse_file_info *);
     *
     * @var null|callable
     */
    public $write_buf;

    /**
     * int (*read_buf) (const char *, struct fuse_bufvec **bufp, size_t size, off_t off, struct fuse_file_info *);
     *
     * @var null|callable
     */
    public $read_buf;

    /**
     * int (*flock) (const char *, struct fuse_file_info *, int op);
     *
     * @var null|callable
     */
    public $flock;

    /**
     * int (*fallocate) (const char *, int, off_t, off_t, struct fuse_file_info *);
     *
     * @var null|callable
     */
    public $fallocate;



    public function toCData(): CData
    {
        $fuse_my_operations = Fuse::getInstance()->ffi->new('struct fuse_operations');

    }
}
