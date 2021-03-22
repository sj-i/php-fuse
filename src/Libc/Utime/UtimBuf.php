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

namespace Fuse\Libc\Utime;

use Fuse\FFI\TypedCDataDefaultImplementationTrait;
use Fuse\FFI\TypedCDataInterface;

/**
 * struct utimbuf
 * {
 *     long actime;
 *     long modtime;
 * };
 */
final class UtimBuf implements TypedCDataInterface
{
    use TypedCDataDefaultImplementationTrait;

    public int $actime;
    public int $modtime;

    public static function getCTypeName(): string
    {
        return 'struct utimbuf';
    }

    public function __construct(int $actime = 0, int $modtime = 0)
    {
        $this->actime = $actime;
        $this->modtime = $modtime;
    }
}