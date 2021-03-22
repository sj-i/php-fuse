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
use Fuse\FFI\TypedCDataInterface;

class FusePrivateData implements TypedCDataInterface
{
    private CData $cdata;

    public static function getCTypeName(): string
    {
        return 'void *';
    }

    public function __construct(CData $cdata)
    {
        $this->cdata = $cdata;
    }

    /** @return static */
    public static function fromCData(CData $cdata): TypedCDataInterface
    {
        return new self($cdata);
    }

    public function toCData(?CData $cdata): CData
    {
        return $this->cdata;
    }

    public static function newCData(): CData
    {
        throw new \LogicException('this type doesn\'t support creation of CData');
    }
}
