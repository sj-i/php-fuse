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

use Fuse\Fuse;
use Fuse\Libc\Fuse\FuseBufVec;
use Fuse\Libc\Sys\Stat\Stat;
use PHPUnit\Framework\TestCase;

class TypedCDataWrapperTest extends TestCase
{
    public function testCreateWrapperStat()
    {
        $test_class = new class() {
            public function test_method(Stat $stat): void {
                $stat->st_blocks = 123;
            }
        };
        $wrapper_creator = new TypedCDataWrapper();
        $wrapper = $wrapper_creator->createWrapper([$test_class, 'test_method']);
        $cdata_stat = Fuse::getInstance()->ffi->new(Stat::getCTypeName());
        $wrapper($cdata_stat);
        $this->assertSame(123, $cdata_stat->st_blocks);
    }

    public function testCreateWrapperFuseBufVec()
    {
        $test_class = new class($this) {
            private TestCase $test_case;
            public function __construct(TestCase $test_case) {
                $this->test_case = $test_case;
            }
            public function test_method(FuseBufVec $fuse_buf_vec): void {
                $this->test_case->assertSame(789, $fuse_buf_vec->buf[0]->size);
                $fuse_buf_vec->count = 123;
                $fuse_buf_vec->buf[0]->size = 456;
            }
        };
        $wrapper_creator = new TypedCDataWrapper();
        $wrapper = $wrapper_creator->createWrapper([$test_class, 'test_method']);
        $cdata_stat = Fuse::getInstance()->ffi->new(FuseBufVec::getCTypeName());
        $cdata_stat->buf[0]->size = 789;
        $wrapper($cdata_stat);
        $this->assertSame(123, $cdata_stat->count);
        $this->assertSame(456, $cdata_stat->buf[0]->size);
    }
}
