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
 * struct fuse_conn_info
 * {
 *     unsigned proto_major;
 *     unsigned proto_minor;
 *     unsigned async_read;
 *     unsigned max_write;
 *     unsigned max_readahead;
 *     unsigned capable;
 *     unsigned want;
 *     unsigned max_background;
 *     unsigned congestion_threshold;
 *     unsigned reserved[23];
 * };
 */
final class FuseConnInfo implements TypedCDataInterface
{
    use TypedCDataDefaultImplementationTrait;

    public int $proto_major;
    public int $proto_minor;
    public int $async_read;
    public int $max_write;
    public int $max_readahead;
    public int $capable;
    public int $want;
    public int $max_background;
    public int $congestion_threshold;
    public ?CData $reserved;

    public static function getCTypeName(): string
    {
        return 'struct fuse_conn_info';
    }

    public function __construct(
        int $proto_major = 0,
        int $proto_minor = 0,
        int $async_read = 0,
        int $max_write = 0,
        int $max_readahead = 0,
        int $capable = 0,
        int $want = 0,
        int $max_background = 0,
        int $congestion_threshold = 0,
        ?CData $reserved = null
    ) {
        $this->proto_major = $proto_major;
        $this->proto_minor = $proto_minor;
        $this->async_read = $async_read;
        $this->max_write = $max_write;
        $this->max_readahead = $max_readahead;
        $this->capable = $capable;
        $this->want = $want;
        $this->max_background = $max_background;
        $this->congestion_threshold = $congestion_threshold;
        $this->reserved = $reserved;
    }
}
