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

class FuseFFI extends FFI
{
    public function fuse_main_real(int $argc, CData $argv, CData $fuse_operation, int $size, ?CData $user_data): int
    {

    }
}