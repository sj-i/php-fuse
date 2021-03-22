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

namespace Fuse\Libc\Fcntl;

use Fuse\FFI\TypedCDataDefaultImplementationTrait;
use Fuse\FFI\TypedCDataInterface;

/**
 * struct flock
 * {
 *     short int l_type;
 *     short int l_whence;
 *     off_t l_start;
 *     off_t l_len;
 *     pid_t l_pid;
 * };
 */
final class Flock implements TypedCDataInterface
{
    use TypedCDataDefaultImplementationTrait;

    public int $l_type;
    public int $l_whence;
    public int $l_start;
    public int $l_len;
    public int $l_pid;

    public static function getCTypeName(): string
    {
        return 'struct flock';
    }
}