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
use ReflectionClass;

final class ReflectionFilesystem
{
    private FilesystemInterface $filesystem;

    public function __construct(FilesystemInterface $filesystem)
    {
        $this->filesystem = $filesystem;
    }

    public static function instance(FilesystemInterface $filesystem)
    {
        return new self($filesystem);
    }

    public function isDefault(string $method_name): bool
    {
        $class = new ReflectionClass($this->filesystem);
        $method = $class->getMethod($method_name);
        $trait = new ReflectionClass(FilesystemDefaultImplementationTrait::class);
        return $method->getFileName() === $trait->getFileName();
    }
}
