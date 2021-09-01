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

use FFI;
use Fuse\Filesystem\Null\NullFilesystem;
use Fuse\FilesystemDefaultImplementationTrait;
use Fuse\FilesystemInterface;
use Fuse\Libc\Fcntl\Flock;
use Fuse\Libc\Fuse\FuseBufVec;
use Fuse\Libc\Fuse\FuseConnInfo;
use Fuse\Libc\Fuse\FuseDirFill;
use Fuse\Libc\Fuse\FuseDirHandle;
use Fuse\Libc\Fuse\FuseFileInfo;
use Fuse\Libc\Fuse\FuseFillDir;
use Fuse\Libc\Fuse\FuseIoctlArgPointer;
use Fuse\Libc\Fuse\FuseIoctlDataPointer;
use Fuse\Libc\Fuse\FusePollHandle;
use Fuse\Libc\Fuse\FuseReadDirBuffer;
use Fuse\Libc\String\CBytesBuffer;
use Fuse\Libc\String\CStringBuffer;
use Fuse\Libc\Sys\Stat\Stat;
use Fuse\Libc\Sys\StatVfs\StatVfs;
use Fuse\Libc\Time\TimeSpec;
use Fuse\Libc\Utime\UtimBuf;
use PHPUnit\Framework\TestCase;
use Psr\Log\LoggerInterface;
use Psr\Log\LoggerTrait;
use TypedCData\TypedCDataArray;

final class LogUnimplementedFilesystemTest extends TestCase
{
    public function testDoesNotLogImplemented()
    {
        $filesystem = new class implements FilesystemInterface {
            use FilesystemDefaultImplementationTrait;

            public function getattr(string $path, Stat $stat): int
            {
                return 42;
            }
        };

        $sut = new LogUnimplementedFilesystem(
            $filesystem,
            new class ($this) implements LoggerInterface {
                use LoggerTrait;

                private TestCase $test_case;

                public function __construct(TestCase $test_case)
                {
                    $this->test_case = $test_case;
                }

                public function log($level, $message, array $context = [])
                {
                    $this->test_case->assertSame('mkdir', $context['method']);
                }
            }
        );

        $sut->getattr('/', new Stat());
        $sut->mkdir('/', 0);
    }

    /** @dataProvider dataProvider */
    public function testLogUnImplemented(string $method, ...$args)
    {
        $sut = new LogUnimplementedFilesystem(
            new NullFilesystem(),
            new class ($this, $method) implements LoggerInterface {
                use LoggerTrait;

                private TestCase $test_case;
                private string $method;

                public function __construct(TestCase $test_case, string $method)
                {
                    $this->test_case = $test_case;
                    $this->method = $method;
                }

                public function log($level, $message, array $context = [])
                {
                    $this->test_case->assertSame($this->method, $context['method']);
                }
            }
        );

        ([$sut, $method])(...$args);
    }

    public function dataProvider(): array
    {
        return [
            'getattr' => ['getattr', '/', new Stat()],
            'readlink' => ['readlink', '/', new CStringBuffer(CStringBuffer::newCData()), 1],
            'getdir' => [
                'getdir',
                '/',
                new FuseDirHandle(FFI::new(FuseDirHandle::getCTypeName())),
                new FuseDirFill(FFI::new('void *')),
            ],
            'mknod' => ['mknod', '/', 0, 0],
            'mkdir' => ['mkdir', '/', 0],
            'unlink' => ['unlink', '/'],
            'rmdir' => ['rmdir', '/'],
            'symlink' => ['symlink', 'path', 'link'],
            'rename' => ['rename', '/from', '/to'],
            'link' => ['link', 'path', 'link'],
            'chmod' => ['chmod', '/', 0],
            'chown' => ['chown', '/', 1, 1],
            'truncate' => ['truncate', '/', 0],
            'utime' => ['utime', '/', new UtimBuf()],
            'open' => ['open', '/', new FuseFileInfo()],
            'read' => ['read', '/', new CBytesBuffer(CBytesBuffer::newCData()), 1, 0, new FuseFileInfo()],
            'write' => ['write', '/', 'content', 7, 0, new FuseFileInfo()],
            'statfs' => ['statfs', '/', new StatVfs()],
            'flush' => ['flush', '/', new FuseFileInfo()],
            'release' => ['release', '/', new FuseFileInfo()],
            'fsync' => ['fsync', '/', 0, new FuseFileInfo()],
            'setxattr' => ['setxattr', '/', 'name', 'value', 5],
            'getxattr' => ['getxattr', '/', 'name', '', 5],
            'listxattr' => ['listxattr', '/', '', 0],
            'removexattr' => ['removexattr', '/', 'name'],
            'opendir' => ['opendir', '/', new FuseFileInfo()],
            'readdir' => [
                'readdir',
                '/',
                new FuseReadDirBuffer(FFI::new('void *')),
                new FuseFillDir(FFI::new('void *')),
                0,
                new FuseFileInfo()
            ],
            'releasedir' => ['releasedir', '/', new FuseFileInfo()],
            'fsyncdir' => ['fsyncdir', '/', new FuseFileInfo()],
            'init' => ['init', new FuseConnInfo()],
            'destroy' => ['destroy', null],
            'access' => ['access', '/', 0],
            'create' => ['create', '/', 0, new FuseFileInfo()],
            'ftruncate' => ['ftruncate', '/', 0, new FuseFileInfo()],
            'fgetattr' => ['fgetattr', '/', new Stat(), new FuseFileInfo()],
            'lock' => ['lock', '/', new FuseFileInfo(), 0, new Flock()],
            'utimens' => [
                'utimens',
                '/',
                new TypedCDataArray(
                    FFI::new('void *'),
                    TimeSpec::class
                )
            ],
            'bmap' => ['bmap', '/', 0, 0],
            'ioctl' => [
                'ioctl',
                '/',
                0,
                new FuseIoctlArgPointer(FFI::new('void *')),
                new FuseFileInfo(),
                0,
                new FuseIoctlDataPointer(FFI::new('void *'))
            ],
            'poll' => [
                'poll',
                '/',
                new FuseFileInfo(),
                new FusePollHandle(FFI::new('void *')),
                0
            ],
            'writeBuf' => ['writeBuf', '/', new FuseBufVec(), 0, new FuseFileInfo()],
            'readBuf' => [
                'readBuf',
                '/',
                new TypedCDataArray(
                    FFI::new('void *'),
                    FuseBufVec::class
                ),
                0,
                0,
                new FuseFileInfo()
            ],
            'flock' => ['flock', '/', new FuseFileInfo(), 0],
            'fallocate' => ['fallocate', '/', 0, 0, new FuseFileInfo()],
        ];
    }
}
