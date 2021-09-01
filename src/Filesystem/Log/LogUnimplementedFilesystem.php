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

namespace Fuse\Filesystem\Log;

use Fuse\Filesystem\BeforeAll\BeforeAllFilesystem;
use Fuse\Filesystem\Delegation\DelegationFilesystemTrait;
use Fuse\Filesystem\Null\NullFilesystem;
use Fuse\Filesystem\Overlay\OverlayFilesystem;
use Fuse\FilesystemInterface;
use Psr\Log\LoggerInterface;
use Psr\Log\LogLevel;

final class LogUnimplementedFilesystem implements FilesystemInterface
{
    use DelegationFilesystemTrait;

    private const DEFAULT_MESSAGE = 'An unimplemented FUSE API is called';

    private const LOG_LEVELS = [
        LogLevel::EMERGENCY,
        LogLevel::ALERT,
        LogLevel::CRITICAL,
        LogLevel::ERROR,
        LogLevel::WARNING,
        LogLevel::NOTICE,
        LogLevel::INFO,
        LogLevel::DEBUG,
    ];

    private LoggerInterface $logger;
    private string $log_level;
    private string $message;

    /** @param value-of<self::LOG_LEVELS> $log_level */
    public function __construct(
        FilesystemInterface $filesystem,
        LoggerInterface $logger,
        string $log_level = LogLevel::DEBUG,
        string $message = self::DEFAULT_MESSAGE
    ) {
        $this->initialize($filesystem);
        $this->logger = $logger;
        $this->log_level = $log_level;
        $this->message = $message;
    }

    private function initialize(FilesystemInterface $filesystem): void
    {
        $this->setDelegation(
            new OverlayFilesystem(
                $filesystem,
                new BeforeAllFilesystem(
                    fn(string $method, array $args) => $this->log($method, $args),
                    new NullFilesystem()
                )
            )
        );
    }

    private function log(string $method, array $args): void
    {
        $this->logger->log(
            $this->log_level,
            $this->message,
            [
                'method' => $method,
                'args' => $args
            ]
        );
    }
}
