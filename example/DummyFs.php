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

use Fuse\FilesystemDefaultImplementationTrait;
use Fuse\FilesystemInterface;
use Fuse\Libc\Errno\Errno;
use Fuse\Libc\Fuse\FuseFileInfo;
use Fuse\Libc\Fuse\FuseFillDir;
use Fuse\Libc\Fuse\FuseReadDirBuffer;
use Fuse\Libc\String\CBytesBuffer;
use Fuse\Libc\Sys\Stat\Stat;

class DummyFs implements FilesystemInterface
{
    use FilesystemDefaultImplementationTrait;

    const FILE_PATH = '/example';
    const FILE_NAME = 'example';
    const FILE_CONTENT = 'hello FUSE from PHP' . PHP_EOL;

    public function getattr(string $path, Stat $stat): int
    {
        echo "attr read {$path}" . PHP_EOL;

        if ($path === '/') {
            $stat->st_mode = Stat::S_IFDIR | 0755;
            $stat->st_nlink = 2;
            $stat->st_uid = getmyuid();
            $stat->st_gid = getmygid();
            return 0;
        }

        if ($path === self::FILE_PATH) {
            $stat->st_mode = Stat::S_IFREG | 0777;
            $stat->st_nlink = 1;
            $stat->st_size = strlen(self::FILE_CONTENT);
            $stat->st_uid = getmyuid();
            $stat->st_gid = getmygid();
            return 0;
        }

        return -Errno::ENOENT;
    }

    public function readdir(
        string $path,
        FuseReadDirBuffer $buf,
        FuseFillDir $filler,
        int $offset,
        FuseFileInfo $fuse_file_info
    ): int {
        $filler($buf, '.', null, 0);
        $filler($buf, '..', null, 0);
        $filler($buf, self::FILE_NAME, null, 0);

        return 0;
    }

    public function open(string $path, FuseFileInfo $fuse_file_info): int
    {
        echo "open {$path}" . PHP_EOL;

        if ($path !== self::FILE_PATH) {
            return -Errno::ENOENT;
        }

        return 0;
    }

    public function read(string $path, CBytesBuffer $buffer, int $size, int $offset, FuseFileInfo $fuse_file_info): int
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
