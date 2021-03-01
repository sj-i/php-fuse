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

use FFI\CData;
use Fuse\FilesystemDefaultImplementationTrait;
use Fuse\FilesystemInterface;
use Fuse\Fuse;
use Fuse\Mounter;

require 'vendor/autoload.php';

const ENOENT = 2;
const S_IFDIR = 0040000;
const S_IFREG = 0100000;

class ArrayFs implements FilesystemInterface
{
    use FilesystemDefaultImplementationTrait;

    private array $array;

    public function __construct(array $array)
    {
        $this->array = $array;
    }

    public function getArray(): array
    {
        return $this->array;
    }

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
            $stbuf->st_mode = S_IFDIR | 0777;
            $stbuf->st_nlink = 2;
            $stbuf->st_uid = getmyuid();
            $stbuf->st_gid = getmygid();
            return 0;
        }

        $element = $this->getEntry($path);
        if (is_null($element)) {
            return -ENOENT;
        }
        if (is_array($element)) {
            $stbuf->st_mode = S_IFDIR | 0777;
            $stbuf->st_nlink = 2;
            $stbuf->st_uid = getmyuid();
            $stbuf->st_gid = getmygid();
            return 0;
        }
        $stbuf->st_mode = S_IFREG | 0777;
        $stbuf->st_nlink = 1;
        $stbuf->st_size = strlen((string)$element);
        $stbuf->st_uid = getmyuid();
        $stbuf->st_gid = getmygid();
        return 0;
    }

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
                    return $array[$offsets[0]];
                }
            } else {
                return $null;
            }
        }

        $offset = array_shift($offsets);
        if (is_array($array[$offset])) {
            return $this->getRecursive($array[$offset], $offsets);
        } else {
            return $null;
        }
    }

    /**
     * @param string $path
     * @return string|array|null
     */
    private function &getEntry(string $path)
    {
        $splitted = explode('/', $path);
        array_shift($splitted);
        return $this->getRecursive($this->array, $splitted);
    }

    /**
     * @param string $path
     * @return string|array|null
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
        $this->getRecursive($this->array, $splitted, function &(array &$array, $index) {
            $null = null;
            unset($array[$index]);
            return $null;
        });
    }

    public function readdir(string $path, CData $buf, CData $filler, int $offset, CData $fi): int
    {
        $filler($buf, '.', null, 0);
        $filler($buf, '..', null, 0);
        foreach ($this->array as $key => $value) {
            $filler($buf, (string)$key, null, 0);
        }

        return 0;
    }

    public function open(string $path, CData $fi): int
    {
        $entry = $this->getEntry($path);
        if (!is_string($entry)) {
            return -ENOENT;
        }

        echo "open {$path}" . PHP_EOL;
        return 0;
    }

    public function read(string $path, CData $buf, int $size, int $offset, CData $fi): int
    {
        $entry = $this->getEntry($path);

        echo "read {$path}" . PHP_EOL;

        $len = strlen($entry);

        if ($offset + $size > $len) {
            $size = ($len - $offset);
        }

        $content = substr($entry, $offset, $size);
        FFI::memcpy($buf, $content, $size);

        return $size;
    }

    public function write(string $path, string $buffer, int $size, int $offset, CData $fuse_file_info): int
    {
        $entry = &$this->getEntry($path);
        $entry = substr_replace($entry, $buffer, $offset, $size);

        return $size;
    }

    public function create(string $path, int $mode, CData $fuse_file_info): int
    {
        $entry = &$this->getParentEntry($path);
        if (is_array($entry)) {
            $segments = explode('/', $path);
            $filename = array_pop($segments);
            $entry[$filename] = '';
            return 0;
        } else {
            return ENOENT;
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
            return ENOENT;
        }
    }
}


$mounter = new Mounter();
$array_fs = new ArrayFs([
    1,
    2,
    'foo' => 'bar',
]);
$result = $mounter->mount('/tmp/example/', $array_fs);
var_dump($array_fs->getArray());
return $result;