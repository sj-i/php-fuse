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

use FFI\CData;

class FuseDirFillCData extends CData
{
    public function __invoke(CData $dirhandle, string $name, int $type, int $ino): int
    {

    }
}

class FuseFillDirCData extends CData
{
    public function __invoke(CData $buf, string $name, ?CData $stbuf, int $off): int
    {

    }
}