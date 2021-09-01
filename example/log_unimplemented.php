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

include __DIR__ . "/../vendor/autoload.php";
include __DIR__ . "/DummyFs.php";

use Fuse\Filesystem\Log\LogUnimplementedFilesystem;
use Fuse\Mounter;
use Psr\Log\LoggerInterface;
use Psr\Log\LoggerTrait;

$mounter = new Mounter();

return $mounter->mount(
    '/tmp/example/',
    new LogUnimplementedFilesystem(
        new DummyFs(),
        new class() implements LoggerInterface {
            use LoggerTrait;

            public function log($level, $message, array $context = [])
            {
                echo \json_encode(['message' => $message] + $context), PHP_EOL;
            }
        }
    )
);
