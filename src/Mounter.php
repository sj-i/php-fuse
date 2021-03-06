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

class Mounter
{
    private Fuse $fuse;

    public function __construct(?Fuse $fuse = null)
    {
        $this->fuse = $fuse ?? Fuse::getInstance();
    }

    /**
     * @param string $path
     * @param Mountable $mountable
     * @param list<string>|null $args
     * @return int
     */
    public function mount(string $path, Mountable $mountable, array $args = null): int
    {
        $args ??= [
            '',
            '-s',
            '-f',
            $path
        ];
        return $this->fuse->main($args, $mountable->getOperations());
    }
}
