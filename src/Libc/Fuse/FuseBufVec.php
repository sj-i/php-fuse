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

use Fuse\FFI\TypedCDataArray;
use Fuse\FFI\TypedCDataDefaultImplementationTrait;
use Fuse\FFI\TypedCDataInterface;

/**
 * struct fuse_bufvec
 * {
 *     size_t count;
 *     size_t idx;
 *     size_t off;
 *     struct fuse_buf buf[1];
 * };
 */
final class FuseBufVec implements TypedCDataInterface
{
    use TypedCDataDefaultImplementationTrait;

    public int $count;
    public int $idx;
    public int $off;
    /** @var TypedCDataArray<FuseBuf> */
    public TypedCDataArray $buf;

    /**
     * @param int $count
     * @param int $idx
     * @param int $off
     * @param TypedCDataArray<FuseBuf>|null $buf
     */
    public function __construct(
        int $count = 0,
        int $idx = 0,
        int $off = 0,
        ?TypedCDataArray $buf = null
    ) {
        $this->count = $count;
        $this->idx = $idx;
        $this->off = $off;
        $this->buf = $buf ?? new TypedCDataArray(
            FuseBuf::newCData(),
            FuseBuf::class
        );
    }

    public static function getCTypeName(): string
    {
        return 'struct fuse_bufvec';
    }
}
