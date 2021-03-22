<?php

include __DIR__ . "/../vendor/autoload.php";

use FFI\CData;
use Fuse\FilesystemDefaultImplementationTrait;
use Fuse\FilesystemInterface;
use Fuse\Libc\Errno\Errno;
use Fuse\Libc\Fuse\FuseFileInfo;
use Fuse\Libc\Fuse\FuseFillDir;
use Fuse\Libc\Fuse\FuseReadDirBuffer;
use Fuse\Libc\String\CBytesBuffer;
use Fuse\Libc\Sys\Stat\Stat;
use Fuse\Mounter;

class DummyFs implements FilesystemInterface
{
    use FilesystemDefaultImplementationTrait;

    const FILE_PATH = '/example';
    const FILE_NAME = 'example';
    const FILE_CONTENT = 'hello FUSE from PHP' . PHP_EOL;

    public function getattr(string $path, Stat $stbuf): int
    {
        echo "attr read {$path}" . PHP_EOL;

        if ($path === '/') {
            $stbuf->st_mode = Stat::S_IFDIR | 0755;
            $stbuf->st_nlink = 2;
            $stbuf->st_uid = getmyuid();
            $stbuf->st_gid = getmygid();
            return 0;
        }

        if ($path === self::FILE_PATH) {
            $stbuf->st_mode = Stat::S_IFREG | 0777;
            $stbuf->st_nlink = 1;
            $stbuf->st_size = strlen(self::FILE_CONTENT);
            $stbuf->st_uid = getmyuid();
            $stbuf->st_gid = getmygid();
            return 0;
        }

        return -Errno::ENOENT;
    }

    public function readdir(string $path, FuseReadDirBuffer $buf, FuseFillDir $filler, int $offset, FuseFileInfo $fi): int
    {
        $filler($buf, '.', null, 0);
        $filler($buf, '..', null, 0);
        $filler($buf, self::FILE_NAME, null, 0);

        return 0;
    }

    public function open(string $path, FuseFileInfo $fuse_file_info): int
    {
        return 0;
    }

    public function read(string $path, CBytesBuffer $buf, int $size, int $offset, FuseFileInfo $fi): int
    {
        echo "read {$path}" . PHP_EOL;

        $len = strlen(self::FILE_CONTENT);

        if ($offset + $size > $len) {
            $size = ($len - $offset);
        }

        $content = substr(self::FILE_CONTENT, $offset, $size);
        $buffer->write($content, $size);

        return $size;
    }
}

$mounter = new Mounter();
return $mounter->mount('/tmp/example/', new DummyFs());
