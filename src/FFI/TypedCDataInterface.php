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

namespace Fuse\FFI;

use FFI\CData;

interface TypedCDataInterface
{
    /** @return static */
    public static function fromCData(CData $cdata): self;
    public static function getCTypeName(): string;
    public function toCData(CData $cdata): CData;
    public static function newCData(): CData;
}