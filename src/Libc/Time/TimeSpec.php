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

namespace Fuse\Libc\Time;

use Fuse\FFI\TypedCDataDefaultImplementationTrait;
use Fuse\FFI\TypedCDataInterface;

final class TimeSpec implements TypedCDataInterface
{
    use TypedCDataDefaultImplementationTrait;

    public int $tv_sec = 0;
    public int $tv_nsec = 0;

    public static function getCTypeName(): string
    {
        return 'struct timespec';
    }

    public function __construct(int $tv_sec = 0, int $tv_nsec = 0)
    {
        $this->tv_sec = $tv_sec;
        $this->tv_nsec = $tv_nsec;
    }
}