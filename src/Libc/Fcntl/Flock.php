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
use TypedCData\TypedCDataInterface;

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

    /**
     * Flock constructor.
     * @param int $l_type
     * @param int $l_whence
     * @param int $l_start
     * @param int $l_len
     * @param int $l_pid
     */
    public function __construct(
        int $l_type = 0,
        int $l_whence = 0,
        int $l_start = 0,
        int $l_len = 0,
        int $l_pid = 0
    ) {
        $this->l_type = $l_type;
        $this->l_whence = $l_whence;
        $this->l_start = $l_start;
        $this->l_len = $l_len;
        $this->l_pid = $l_pid;
    }

    public static function getCTypeName(): string
    {
        return 'struct flock';
    }
}
