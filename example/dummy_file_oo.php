<?php

include __DIR__ . "/../vendor/autoload.php";

use FFI\CData;
use Fuse\FilesystemDefaultImplementationTrait;
use Fuse\FilesystemInterface;
use Fuse\Fuse;
use Fuse\Libc\Fuse\FuseFileInfo;
use Fuse\Libc\Fuse\FuseFillDir;
use Fuse\Libc\Fuse\FuseReadDirBuffer;
use Fuse\Libc\Sys\Stat\Stat;
use Fuse\Mounter;

const FILE_PATH = '/example';
const FILE_NAME = 'example';
const FILE_CONTENT = 'hello FUSE from PHP' . PHP_EOL;

const ENOENT = 2;
const S_IFDIR = 0040000;
const S_IFREG = 0100000;

class DummyFs implements FilesystemInterface
{
    use FilesystemDefaultImplementationTrait;

    public function getattr(string $path, Stat $stbuf): int
    {
        echo "attr read {$path}" . PHP_EOL;

        if ($path === '/') {
            $stbuf->st_mode = S_IFDIR | 0755;
            $stbuf->st_nlink = 2;
            $stbuf->st_uid = getmyuid();
            $stbuf->st_gid = getmygid();
            return 0;
        }

        if ($path === FILE_PATH) {
            $stbuf->st_mode = S_IFREG | 0777;
            $stbuf->st_nlink = 1;
            $stbuf->st_size = strlen(FILE_CONTENT);
            $stbuf->st_uid = getmyuid();
            $stbuf->st_gid = getmygid();
            return 0;
        }

        return -ENOENT;
    }

    public function readdir(string $path, FuseReadDirBuffer $buf, FuseFillDir $filler, int $offset, FuseFileInfo $fi): int
    {
        $filler($buf, '.', null, 0);
        $filler($buf, '..', null, 0);
        $filler($buf, FILE_NAME, null, 0);

        return 0;
    }

    public function open(string $path, FuseFileInfo $fuse_file_info): int
    {
        return 0;
    }

    public function read(string $path, CData $buf, int $size, int $offset, FuseFileInfo $fi): int
    {
        echo "read {$path}" . PHP_EOL;

        $len = strlen(FILE_CONTENT);

        if ($offset + $size > $len) {
            $size = ($len - $offset);
        }

        $content = substr(FILE_CONTENT, $offset, $size);
        FFI::memcpy($buf, $content, $size);

        return $size;
    }
}

$mounter = new Mounter();
return $mounter->mount('/tmp/example/', new DummyFs());
