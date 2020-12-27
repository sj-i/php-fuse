# php-fuse

![Minimum PHP version: 7.4.0](https://img.shields.io/badge/php-7.4.0%2B-blue.svg)
[![Packagist](https://img.shields.io/packagist/v/sj-i/php-fuse.svg)](https://packagist.org/packages/sj-i/php-fuse)

PHP FFI bindings for libfuse

## Installation
```bash
composer require sj-i/php-fuse
```

## Requirements
- PHP-7.4 64bit Linux x86_64 (NTS / ZTS)
- FFI extension
- libfuse

# LICENSE
- MIT

## Example
```bash
php example/dummy_file.php -s -f /tmp/example/
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
