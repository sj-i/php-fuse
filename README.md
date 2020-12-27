PHP FFI bindings for libfuse

```
php example/dummy_file.php -s -f /tmp/example/
```

```
$ ls -la /tmp/example/
total 180
drwxr-xr-x  2 sji  sji       0  1月  1  1970 .
drwxrwxrwt 25 root root 180224 12月 28 07:14 ..
-rwxrwxrwx  1 sji  sji      20  1月  1  1970 example
$ cat /tmp/example/example
hello FUSE from PHP
$ umount /tmp/example
```
