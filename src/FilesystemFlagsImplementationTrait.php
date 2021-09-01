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

trait FilesystemFlagsImplementationTrait
{
    /**
     * unsigned int flag_nullpath_ok:1;
     * unsigned int flag_nopath:1;
     * unsigned int flag_utime_omit_ok:1;
     * unsigned int flag_reserved:29;
     */
    private bool $flag_nullpath_ok = false;
    private bool $flag_nopath = false;
    private bool $flag_utime_omit_ok = false;

    public function setFlagNullpathOk(bool $flag): void
    {
        $this->flag_nullpath_ok = $flag;
    }

    public function getFlagNullpathOk(): bool
    {
        return $this->flag_nullpath_ok;
    }

    public function setFlagNopath(bool $flag): void
    {
        $this->flag_nopath = $flag;
    }

    public function getFlagNopath(): bool
    {
        return $this->flag_nopath;
    }

    public function setFlagUtimeOmitOk(bool $flag): void
    {
        $this->flag_utime_omit_ok = $flag;
    }

    public function getFlagUtimeOmitOk(): bool
    {
        return $this->flag_utime_omit_ok;
    }
}
