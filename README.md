# php-fuse

![Minimum PHP version: 7.4.0](https://img.shields.io/badge/php-7.4.0%2B-blue.svg)
[![Packagist](https://img.shields.io/packagist/v/sj-i/php-fuse.svg)](https://packagist.org/packages/sj-i/php-fuse)
[![Github Actions](https://github.com/sj-i/php-fuse/workflows/build/badge.svg)](https://github.com/sj-i/php-fuse/actions)
[![Scrutinizer Code Quality](https://scrutinizer-ci.com/g/sj-i/php-fuse/badges/quality-score.png?b=master)](https://scrutinizer-ci.com/g/sj-i/php-fuse/?branch=master)
[![Coverage Status](https://coveralls.io/repos/github/sj-i/php-fuse/badge.svg?branch=master)](https://coveralls.io/github/sj-i/php-fuse?branch=master)
![Psalm coverage](https://shepherd.dev/github/sj-i/php-fuse/coverage.svg?)
![stability-experimental](https://img.shields.io/badge/stability-experimental-orange.svg)

PHP FFI bindings for [libfuse](https://github.com/libfuse/libfuse).

You can write your own filesystems in PHP.

## Installation
```bash
composer require sj-i/php-fuse
```

## Requirements
- PHP 7.4+ (NTS / ZTS)
- 64bit Linux x86_64
- FFI extension
- libfuse(currently based on 2.9.9)

## Documentation
- Currently, no documentation is provided. :-(
- If you want to write a filesystem in PHP by using this library, see [examples](https://github.com/sj-i/php-fuse/tree/master/example) in this repository and [the libfuse API documentation](https://libfuse.github.io/doxygen/index.html) for now.

## Todo
- [ ] bump libfuse to 3.9
- [ ] add more tests
- [ ] add documentation
- [ ] support multithreading


## LICENSE
- MIT

## Example
```bash
mkdir /tmp/example
php example/dummy_file.php
```

```bash
$ ls -la /tmp/example/
total 180
drwxr-xr-x  2 sji  sji       0  1月  1  1970 .
drwxrwxrwt 25 root root 180224 12月 28 07:14 ..
-rwxrwxrwx  1 sji  sji      20  1月  1  1970 example
$ cat /tmp/example/example
hello FUSE from PHP
$ umount /tmp/example
```
