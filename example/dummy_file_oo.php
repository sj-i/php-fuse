<?php

declare(strict_types=1);

include __DIR__ . "/../vendor/autoload.php";
include __DIR__ . "/DummyFs.php";

use Fuse\Mounter;

$mounter = new Mounter();
return $mounter->mount('/tmp/example/', new DummyFs());
