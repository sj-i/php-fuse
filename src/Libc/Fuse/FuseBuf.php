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

namespace Fuse\Libc\Fuse;

use FFI\CData;
use Fuse\FFI\TypedCDataDefaultImplementationTrait;
use Fuse\FFI\TypedCDataInterface;

/**
 * struct fuse_buf
 * {
 *     size_t size;
 *     enum fuse_buf_flags flags;
 *     void *mem;
 *     int fd;
 *     off_t pos;
 * };
 */
final class FuseBuf implements TypedCDataInterface
{
    use TypedCDataDefaultImplementationTrait;

    public int $size;
    public int $flags;
    public ?CData $mem;
    public int $fd;
    public int $pos;

    public static function getCTypeName(): string
    {
        return 'struct fuse_buf';
    }

    public function __construct(
        int $size = 0,
        int $flags = 0,
        ?CData $mem = null,
        int $fd = 0,
        int $pos = 0
    ) {
        $this->size = $size;
        $this->flags = $flags;
        $this->mem = $mem;
        $this->fd = $fd;
        $this->pos = $pos;
    }
}
