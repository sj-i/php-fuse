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

namespace Fuse\Filesystem\BeforeAll;

use Fuse\FilesystemFlagsImplementationTrait;
use Fuse\FilesystemInterface;
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
use Fuse\MountableFilesystemTrait;
use TypedCData\TypedCDataArray;

final class BeforeAllFilesystem implements FilesystemInterface
{
    use MountableFilesystemTrait;
    use FilesystemFlagsImplementationTrait;

    /** @var callable */
    private $callback;
    private FilesystemInterface $filesystem;

    /**
     * @param callable(string,array):void $callback
     */
    public function __construct(
        callable $callback,
        FilesystemInterface $filesystem
    ) {
        $this->callback = $callback;
        $this->filesystem = $filesystem;
    }

    private function getFilesystem(): FilesystemInterface
    {
        return $this->filesystem;
    }

    public function getattr(string $path, Stat $stat): int
    {
        ($this->callback)(__FUNCTION__, func_get_args());
        return $this->getFilesystem()->getattr($path, $stat);
    }

    public function readlink(string $path, CStringBuffer $buffer, int $size): int
    {
        ($this->callback)(__FUNCTION__, func_get_args());
        return $this->getFilesystem()->readlink($path, $buffer, $size);
    }

    /** @deprecated */
    public function getdir(string $path, FuseDirHandle $dirhandle, FuseDirFill $dirfill): int
    {
        ($this->callback)(__FUNCTION__, func_get_args());
        /** @psalm-suppress DeprecatedMethod */
        return $this->getFilesystem()->getdir($path, $dirhandle, $dirfill);
    }

    public function mknod(string $path, int $mode, int $dev): int
    {
        ($this->callback)(__FUNCTION__, func_get_args());
        return $this->getFilesystem()->mknod($path, $mode, $dev);
    }

    public function mkdir(string $path, int $mode): int
    {
        ($this->callback)(__FUNCTION__, func_get_args());
        return $this->getFilesystem()->mkdir($path, $mode);
    }

    public function unlink(string $path): int
    {
        ($this->callback)(__FUNCTION__, func_get_args());
        return $this->getFilesystem()->unlink($path);
    }

    public function rmdir(string $path): int
    {
        ($this->callback)(__FUNCTION__, func_get_args());
        return $this->getFilesystem()->rmdir($path);
    }

    public function symlink(string $path, string $link): int
    {
        ($this->callback)(__FUNCTION__, func_get_args());
        return $this->getFilesystem()->symlink($path, $link);
    }

    public function rename(string $from, string $to): int
    {
        ($this->callback)(__FUNCTION__, func_get_args());
        return $this->getFilesystem()->rename($from, $to);
    }

    public function link(string $path, string $link): int
    {
        ($this->callback)(__FUNCTION__, func_get_args());
        return $this->getFilesystem()->link($path, $link);
    }

    public function chmod(string $path, int $mode): int
    {
        ($this->callback)(__FUNCTION__, func_get_args());
        return $this->getFilesystem()->chmod($path, $mode);
    }

    public function chown(string $path, int $uid, int $gid): int
    {
        ($this->callback)(__FUNCTION__, func_get_args());
        return $this->getFilesystem()->chown($path, $uid, $gid);
    }

    public function truncate(string $path, int $offset): int
    {
        ($this->callback)(__FUNCTION__, func_get_args());
        return $this->getFilesystem()->truncate($path, $offset);
    }

    public function utime(string $path, UtimBuf $utime_buf): int
    {
        ($this->callback)(__FUNCTION__, func_get_args());
        return $this->getFilesystem()->utime($path, $utime_buf);
    }

    public function open(string $path, FuseFileInfo $fuse_file_info): int
    {
        ($this->callback)(__FUNCTION__, func_get_args());
        return $this->getFilesystem()->open($path, $fuse_file_info);
    }

    public function read(string $path, CBytesBuffer $buffer, int $size, int $offset, FuseFileInfo $fuse_file_info): int
    {
        ($this->callback)(__FUNCTION__, func_get_args());
        return $this->getFilesystem()->read($path, $buffer, $size, $offset, $fuse_file_info);
    }

    public function write(string $path, string $buffer, int $size, int $offset, FuseFileInfo $fuse_file_info): int
    {
        ($this->callback)(__FUNCTION__, func_get_args());
        return $this->getFilesystem()->write($path, $buffer, $size, $offset, $fuse_file_info);
    }

    public function statfs(string $path, StatVfs $statvfs): int
    {
        ($this->callback)(__FUNCTION__, func_get_args());
        return $this->getFilesystem()->statfs($path, $statvfs);
    }

    public function flush(string $path, FuseFileInfo $fuse_file_info): int
    {
        ($this->callback)(__FUNCTION__, func_get_args());
        return $this->getFilesystem()->flush($path, $fuse_file_info);
    }

    public function release(string $path, FuseFileInfo $fuse_file_info): int
    {
        ($this->callback)(__FUNCTION__, func_get_args());
        return $this->getFilesystem()->release($path, $fuse_file_info);
    }

    public function fsync(string $path, int $flags, FuseFileInfo $fuse_file_info): int
    {
        ($this->callback)(__FUNCTION__, func_get_args());
        return $this->getFilesystem()->fsync($path, $flags, $fuse_file_info);
    }

    public function setxattr(string $path, string $name, string $value, int $size): int
    {
        ($this->callback)(__FUNCTION__, func_get_args());
        return $this->getFilesystem()->setxattr($path, $name, $value, $size);
    }

    public function getxattr(string $path, string $name, ?string &$value, int $size): int
    {
        ($this->callback)(__FUNCTION__, func_get_args());
        return $this->getFilesystem()->getxattr($path, $name, $value, $size);
    }

    public function listxattr(string $path, ?string &$value, int $size): int
    {
        ($this->callback)(__FUNCTION__, func_get_args());
        return $this->getFilesystem()->listxattr($path, $value, $size);
    }

    public function removexattr(string $path, string $name): int
    {
        ($this->callback)(__FUNCTION__, func_get_args());
        return $this->getFilesystem()->removexattr($path, $name);
    }

    public function opendir(string $path, FuseFileInfo $fuse_file_info): int
    {
        ($this->callback)(__FUNCTION__, func_get_args());
        return $this->getFilesystem()->opendir($path, $fuse_file_info);
    }

    public function readdir(
        string $path,
        FuseReadDirBuffer $buf,
        FuseFillDir $filler,
        int $offset,
        FuseFileInfo $fuse_file_info
    ): int {
        ($this->callback)(__FUNCTION__, func_get_args());
        return $this->getFilesystem()->readdir($path, $buf, $filler, $offset, $fuse_file_info);
    }

    public function releasedir(string $path, FuseFileInfo $fuse_file_info): int
    {
        ($this->callback)(__FUNCTION__, func_get_args());
        return $this->getFilesystem()->releasedir($path, $fuse_file_info);
    }

    public function fsyncdir(string $path, FuseFileInfo $fuse_file_info): int
    {
        ($this->callback)(__FUNCTION__, func_get_args());
        return $this->getFilesystem()->fsyncdir($path, $fuse_file_info);
    }

    public function init(FuseConnInfo $conn): ?FusePrivateData
    {
        ($this->callback)(__FUNCTION__, func_get_args());
        return $this->getFilesystem()->init($conn);
    }

    public function destroy(?FusePrivateData $private_data): void
    {
        ($this->callback)(__FUNCTION__, func_get_args());
        $this->getFilesystem()->destroy($private_data);
    }

    public function access(string $path, int $mode): int
    {
        ($this->callback)(__FUNCTION__, func_get_args());
        return $this->getFilesystem()->access($path, $mode);
    }

    public function create(string $path, int $mode, FuseFileInfo $fuse_file_info): int
    {
        ($this->callback)(__FUNCTION__, func_get_args());
        return $this->getFilesystem()->create($path, $mode, $fuse_file_info);
    }

    public function ftruncate(string $path, int $offset, FuseFileInfo $fuse_file_info): int
    {
        ($this->callback)(__FUNCTION__, func_get_args());
        return $this->getFilesystem()->ftruncate($path, $offset, $fuse_file_info);
    }

    public function fgetattr(string $path, Stat $stat, FuseFileInfo $fuse_file_info): int
    {
        ($this->callback)(__FUNCTION__, func_get_args());
        return $this->getFilesystem()->fgetattr($path, $stat, $fuse_file_info);
    }

    public function lock(string $path, FuseFileInfo $fuse_file_info, int $cmd, Flock $flock): int
    {
        ($this->callback)(__FUNCTION__, func_get_args());
        return $this->getFilesystem()->lock($path, $fuse_file_info, $cmd, $flock);
    }

    /**
     * @param TypedCDataArray<TimeSpec> $tv
     */
    public function utimens(string $path, TypedCDataArray $tv): int
    {
        ($this->callback)(__FUNCTION__, func_get_args());
        return $this->getFilesystem()->utimens($path, $tv);
    }

    public function bmap(string $path, int $blocksize, int &$idx): int
    {
        ($this->callback)(__FUNCTION__, func_get_args());
        return $this->getFilesystem()->bmap($path, $blocksize, $idx);
    }

    public function ioctl(
        string $path,
        int $cmd,
        FuseIoctlArgPointer $arg,
        FuseFileInfo $fuse_file_info,
        int $flags,
        FuseIoctlDataPointer $data
    ): int {
        ($this->callback)(__FUNCTION__, func_get_args());
        return $this->getFilesystem()->ioctl($path, $cmd, $arg, $fuse_file_info, $flags, $data);
    }

    public function poll(
        string $path,
        FuseFileInfo $fuse_file_info,
        FusePollHandle $fuse_pollhandle,
        int &$reventsp
    ): int {
        ($this->callback)(__FUNCTION__, func_get_args());
        return $this->getFilesystem()->poll($path, $fuse_file_info, $fuse_pollhandle, $reventsp);
    }

    public function writeBuf(string $path, FuseBufVec $buf, int $offset, FuseFileInfo $fuse_file_info): int
    {
        ($this->callback)(__FUNCTION__, func_get_args());
        return $this->getFilesystem()->writeBuf($path, $buf, $offset, $fuse_file_info);
    }

    /**
     * @param TypedCDataArray<FuseBufVec> $bufp
     */
    public function readBuf(
        string $path,
        TypedCDataArray $bufp,
        int $size,
        int $offset,
        FuseFileInfo $fuse_file_info
    ): int {
        ($this->callback)(__FUNCTION__, func_get_args());
        return $this->getFilesystem()->readBuf($path, $bufp, $size, $offset, $fuse_file_info);
    }

    public function flock(string $path, FuseFileInfo $fuse_file_info, int $op): int
    {
        ($this->callback)(__FUNCTION__, func_get_args());
        return $this->getFilesystem()->flock($path, $fuse_file_info, $op);
    }

    public function fallocate(string $path, int $mode, int $offset, FuseFileInfo $fuse_file_info): int
    {
        ($this->callback)(__FUNCTION__, func_get_args());
        return $this->getFilesystem()->fallocate($path, $mode, $offset, $fuse_file_info);
    }
}
