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

namespace Fuse\Filesystem;

use Fuse\FilesystemDefaultImplementationTrait;
use Fuse\FilesystemInterface;
use Fuse\Libc\Sys\Stat\Stat;
use PHPUnit\Framework\TestCase;

final class ReflectionFilesystemTest extends TestCase
{
    public function testIsDefault()
    {
        $filesystem = new class implements FilesystemInterface {
            use FilesystemDefaultImplementationTrait;

            public function getattr(string $path, Stat $stat): int
            {
                return 0;
            }
        };

        $sut = ReflectionFilesystem::instance($filesystem);

        $this->assertFalse($sut->isDefault('getattr'));
        $this->assertTrue($sut->isDefault('readlink'));
    }
}
