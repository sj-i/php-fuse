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

use FFI;
use FFI\CData;
use Fuse\Libc\Fuse\FuseIoctlDataPointer;

final class Fuse
{
    private static ?self $instance;
    /** @var FuseFFI */
    public FFI $ffi;

    /**
     * @param FuseFFI $ffi
     */
    private function __construct(FFI $ffi)
    {
        $this->ffi = $ffi;
    }

    /**
     * @return FuseFFI
     */
    private static function load(): FFI
    {
        /** @var FuseFFI */
        return FFI::cdef(
            file_get_contents(__DIR__ . '/Headers/fuse.h'),
            'libfuse.so'
        );
    }

    public static function getInstance(): self
    {
        if (!isset(self::$instance)) {
            self::$instance = new self(self::load());
        }
        return self::$instance;
    }

    /**
     * @param list<string> $args
     * @param FuseOperations $fuse_operations
     * @param CData|null $user_data
     * @return int
     */
    public function main(array $args, FuseOperations $fuse_operations, ?CData $user_data = null): int
    {
        $argc = count($args);
        /** @var \FFI\CDataArray $argv_real */
        $argv_real = FFI::new('char *[' . count($args) . ']');
        foreach ($args as $key => $item) {
            $item_len = strlen($item);
            $item_len_nul = $item_len + 1;
            /** @var \FFI\CDataArray $argv_item */
            $argv_item = FFI::new("char[{$item_len_nul}]", false, true);
            FFI::memcpy($argv_item, $item, $item_len);
            $argv_item[$item_len] = "\0";
            $argv_real[$key] = $argv_item;
        }

        return Fuse::getInstance()->ffi->fuse_main_real(
            $argc,
            $argv_real,
            FFI::addr($fuse_operations->getCData()),
            $fuse_operations->getSize(),
            $user_data
        );
    }
}
