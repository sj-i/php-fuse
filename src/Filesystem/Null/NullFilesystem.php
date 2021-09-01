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

namespace Fuse\Filesystem\Null;

use Fuse\FilesystemDefaultImplementationTrait;
use Fuse\FilesystemInterface;

final class NullFilesystem implements FilesystemInterface
{
    use FilesystemDefaultImplementationTrait;
}
