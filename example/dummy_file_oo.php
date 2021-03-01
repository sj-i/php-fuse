<?php

include __DIR__ . "/../vendor/autoload.php";

use FFI\CData;
use Fuse\FilesystemDefaultImplementationTrait;
use Fuse\FilesystemInterface;
use Fuse\Fuse;
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

    public function getattr(string $path, \FFI\CData $stbuf): int
    {
        $typename = 'struct stat';
        $type = Fuse::getInstance()->ffi->type(
            $typename
        );
        $size = FFI::sizeof(
            $type
        );
        echo "attr read {$path}" . PHP_EOL;

        FFI::memset($stbuf, 0, $size);
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

    public function readdir(string $path, CData $buf, CData $filler, int $offset, CData $fi): int
    {
        $filler($buf, '.', null, 0);
        $filler($buf, '..', null, 0);
        $filler($buf, FILE_NAME, null, 0);

        return 0;
    }

    public function open(string $path, CData $fi): int
    {
        if ($path !== FILE_PATH) {
            return -ENOENT;
        }

        echo "open {$path}" . PHP_EOL;
        return 0;
    }

    public function read(string $path, CData $buf, int $size, int $offset, CData $fi): int
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
