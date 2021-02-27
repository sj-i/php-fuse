<?php

include __DIR__ . "/../vendor/autoload.php";

use FFI\CData;
use Fuse\Fuse;
use Fuse\FuseOperations;
use Fuse\Mounter;

const FILE_PATH = '/example';
const FILE_NAME = 'example';
const FILE_CONTENT = 'hello FUSE from PHP' . PHP_EOL;

const ENOENT = 2;
const S_IFDIR = 0040000;
const S_IFREG = 0100000;

function getattr_cb(string $path, \FFI\CData $stbuf)
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

function readdir_cb(string $path, CData $buf, CData $filler, int $offset, CData $fi)
{
    $filler($buf, '.', null, 0);
    $filler($buf, '..', null, 0);
    $filler($buf, FILE_NAME, null, 0);

    return 0;
}

function open_cb(string $path, CData $fi)
{
    if ($path !== FILE_PATH) {
        return -ENOENT;
    }

    echo "open {$path}" . PHP_EOL;
    return 0;
}

function read_cb(string $path, CData $buf, int $size, int $offset, CData $fi)
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

$fuse_operations = new FuseOperations();
$fuse_operations->getattr = 'getattr_cb';
$fuse_operations->open = 'open_cb';
$fuse_operations->read = 'read_cb';
$fuse_operations->readdir = 'readdir_cb';

$mounter = new Mounter();
return $mounter->mount('/tmp/example/', $fuse_operations);
