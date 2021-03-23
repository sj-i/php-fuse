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
use Fuse\Mounter;

require 'vendor/autoload.php';

/**
 * @psalm-type NodeType=scalar|array|null
 */
class ArrayFs implements FilesystemInterface
{
    use FilesystemDefaultImplementationTrait;

    /** @var NodeType[] */
    private array $array;

    /**
     * ArrayFs constructor.
     * @param NodeType[] $array
     */
    public function __construct(array $array)
    {
        $this->array = $array;
    }

    public function getArray(): array
    {
        return $this->array;
    }

    public function getattr(string $path, Stat $stat): int
    {
        echo "attr read {$path}" . PHP_EOL;

        if ($path === '/') {
            $stat->st_mode = Stat::S_IFDIR | 0777;
            $stat->st_nlink = 2;
            $stat->st_uid = getmyuid();
            $stat->st_gid = getmygid();
            return 0;
        }

        $element = $this->getEntry($path);
        if (is_null($element)) {
            return -Errno::ENOENT;
        }
        if (is_array($element)) {
            $stat->st_mode = Stat::S_IFDIR | 0777;
            $stat->st_nlink = 2;
            $stat->st_uid = getmyuid();
            $stat->st_gid = getmygid();
            return 0;
        }
        $stat->st_mode = Stat::S_IFREG | 0777;
        $stat->st_nlink = 1;
        $stat->st_size = strlen((string)$element);
        $stat->st_uid = getmyuid();
        $stat->st_gid = getmygid();
        return 0;
    }

    /**
     * @param NodeType[] $array
     * @param list<string> $offsets
     * @param callable(array, string):NodeType $operation
     * @return NodeType
     */
    private function &getRecursive(&$array, array $offsets, ?callable $operation = null)
    {
        $null = null;

        $count = count($offsets);

        if ($count === 0) {
            return $null;
        }
        if ($count === 1) {
            if (isset($array[$offsets[0]])) {
                if (!is_null($operation)) {
                    return $operation($array, $offsets[0]);
                } else {
                    /** @var NodeType */
                    return $array[$offsets[0]];
                }
            } else {
                return $null;
            }
        }

        $offset = array_shift($offsets);
        if (is_array($array[$offset])) {
            /** @var NodeType[] $next_array */
            $next_array =& $array[$offset];
            return $this->getRecursive($next_array, $offsets);
        } else {
            return $null;
        }
    }

    /**
     * @param string $path
     * @return scalar|array|null
     */
    private function &getEntry(string $path)
    {
        if ($path === '/') {
            return $this->array;
        }
        $splitted = explode('/', $path);
        array_shift($splitted);
        return $this->getRecursive($this->array, $splitted);
    }

    /**
     * @param string $path
     * @return NodeType
     */
    private function &getParentEntry(string $path)
    {
        $splitted = explode('/', $path);
        array_shift($splitted);
        array_pop($splitted);
        if (count($splitted) === 0) {
            return $this->array;
        }
        return $this->getRecursive($this->array, $splitted);
    }

    /**
     * @param string $path
     */
    private function unsetEntry(string $path): void
    {
        $splitted = explode('/', $path);
        array_shift($splitted);
        $this->getRecursive(
            $this->array,
            $splitted,
            function &(array &$array, string $index) {
                $null = null;
                unset($array[$index]);
                return $null;
            }
        );
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
        $entry = $this->getEntry($path);
        if (!is_array($entry)) {
            return -Errno::ENOTDIR;
        }
        foreach ($entry as $key => $_) {
            $filler($buf, (string)$key, null, 0);
        }

        return 0;
    }

    public function open(string $path, FuseFileInfo $fuse_file_info): int
    {
        $entry = $this->getEntry($path);
        if (!is_scalar($entry)) {
            return Errno::ENOENT;
        }

        echo "open {$path}" . PHP_EOL;
        return 0;
    }

    public function read(string $path, CBytesBuffer $buffer, int $size, int $offset, FuseFileInfo $fuse_file_info): int
    {
        $entry = $this->getEntry($path);

        echo "read {$path}" . PHP_EOL;

        assert(!is_array($entry));
        $len = strlen((string)$entry);

        if ($offset + $size > $len) {
            $size = ($len - $offset);
        }

        $content = substr((string)$entry, $offset, $size);
        $buffer->write($content, $size);

        return $size;
    }

    public function write(string $path, string $buffer, int $size, int $offset, FuseFileInfo $fuse_file_info): int
    {
        $entry = &$this->getEntry($path);
        assert(!is_array($entry));
        $entry = substr_replace((string)$entry, $buffer, $offset, $size);

        return $size;
    }

    public function create(string $path, int $mode, FuseFileInfo $fuse_file_info): int
    {
        $entry = &$this->getParentEntry($path);
        if (is_array($entry)) {
            $segments = explode('/', $path);
            $filename = array_pop($segments);
            $entry[$filename] = '';
            return 0;
        } else {
            return Errno::ENOENT;
        }
    }

    public function unlink(string $path): int
    {
        $this->unsetEntry($path);
        return 0;
    }

    public function rename(string $from, string $to): int
    {
        $fromValue = $this->getEntry($from);
        $parent_entry = &$this->getParentEntry($to);
        if (is_array($parent_entry)) {
            $segments = explode('/', $to);
            $filename = array_pop($segments);
            $parent_entry[$filename] = $fromValue;
            $this->unsetEntry($from);
            return 0;
        } else {
            return -Errno::ENOENT;
        }
    }
}

$e = new \DateTimeImmutable();

$mounter = new Mounter();
/** @psalm-suppress MixedArgumentTypeCoercion */
$array_fs = new ArrayFs([
    1,
    2,
    'foo' => 'bar',
    'e' => json_decode(json_encode($e), true)
]);
$result = $mounter->mount('/tmp/example/', $array_fs);
/** @psalm-suppress ForbiddenCode */
var_dump($array_fs->getArray());
return $result;