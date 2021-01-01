typedef unsigned int mode_t;
typedef unsigned long int dev_t;
typedef unsigned long int ino_t;
typedef long int off_t;
typedef long int nlink_t;
typedef unsigned int uid_t;
typedef unsigned int gid_t;
typedef int pid_t;
typedef long int blksize_t;
typedef long int blkcnt_t;
typedef unsigned long int fsblkcnt64_t;
typedef unsigned long int uint64_t;

struct timespec
{
    long tv_sec;
    long tv_nsec;
};

struct utimbuf
{
	long actime;
	long modtime;
};

struct stat
{
    dev_t st_dev;
    ino_t st_ino;
    nlink_t st_nlink;
    mode_t st_mode;
    uid_t st_uid;
    gid_t st_gid;
    int __pad0;
    dev_t st_rdev;
    off_t st_size;
    blksize_t st_blksize;
    blkcnt_t st_blocks;
    struct timespec st_atim;
    struct timespec st_mtim;
    struct timespec st_ctim;
    long int reserved[3];
};

struct fuse_file_info
{
	int flags;
	unsigned long fh_old;
	int writepage;
	unsigned int direct_io : 1;
	unsigned int keep_cache : 1;
	unsigned int flush : 1;
	unsigned int nonseekable : 1;
	unsigned int flock_release : 1;
	unsigned int padding : 27;
	uint64_t fh;
	uint64_t lock_owner;
};

struct statvfs
{
    unsigned long int f_bsize;
    unsigned long int f_frsize;
    fsblkcnt64_t f_blocks;
    fsblkcnt64_t f_bfree;
    fsblkcnt64_t f_bavail;
    fsblkcnt64_t f_files;
    fsblkcnt64_t f_ffree;
    fsblkcnt64_t f_favail;
    unsigned long int f_fsid;
    unsigned long int f_flag;
    unsigned long int f_namemax;
    int __f_spare[6];
};

struct fuse_conn_info {
	unsigned proto_major;
	unsigned proto_minor;
	unsigned async_read;
	unsigned max_write;
	unsigned max_readahead;
	unsigned capable;
	unsigned want;
	unsigned max_background;
	unsigned congestion_threshold;
	unsigned reserved[23];
};

struct flock
{
    short int l_type;
    short int l_whence;
    off_t l_start;
    off_t l_len;
    pid_t l_pid;
};

enum fuse_buf_flags
{
	FUSE_BUF_IS_FD		= (1 << 1),
	FUSE_BUF_FD_SEEK	= (1 << 2),
	FUSE_BUF_FD_RETRY	= (1 << 3),
};

struct fuse_buf
{
	size_t size;
	enum fuse_buf_flags flags;
	void *mem;
	int fd;
	off_t pos;
};

struct fuse_bufvec
{
	size_t count;
	size_t idx;
	size_t off;
	struct fuse_buf buf[1];
};

struct fuse;
struct fuse_cmd;
struct fuse_pollhandle;

typedef int (*fuse_fill_dir_t) (void *buf, const char *name, const struct stat *stbuf, off_t off);
typedef struct fuse_dirhandle *fuse_dirh_t;
typedef int (*fuse_dirfil_t) (fuse_dirh_t h, const char *name, int type, ino_t ino);
struct fuse_operations
{
	int (*getattr) (const char *, struct stat *);
	int (*readlink) (const char *, char *, size_t);
	int (*getdir) (const char *, fuse_dirh_t, fuse_dirfil_t);
	int (*mknod) (const char *, mode_t, dev_t);
	int (*mkdir) (const char *, mode_t);
	int (*unlink) (const char *);
	int (*rmdir) (const char *);
	int (*symlink) (const char *, const char *);
	int (*rename) (const char *, const char *);
	int (*link) (const char *, const char *);
	int (*chmod) (const char *, mode_t);
	int (*chown) (const char *, uid_t, gid_t);
	int (*truncate) (const char *, off_t);
	int (*utime) (const char *, struct utimbuf *);
	int (*open) (const char *, struct fuse_file_info *);
	int (*read) (const char *, char *, size_t, off_t, struct fuse_file_info *);
	int (*write) (const char *, const char *, size_t, off_t, struct fuse_file_info *);
	int (*statfs) (const char *, struct statvfs *);
	int (*flush) (const char *, struct fuse_file_info *);
	int (*release) (const char *, struct fuse_file_info *);
	int (*fsync) (const char *, int, struct fuse_file_info *);
	int (*setxattr) (const char *, const char *, const char *, size_t, int);
	int (*getxattr) (const char *, const char *, char *, size_t);
	int (*listxattr) (const char *, char *, size_t);
	int (*removexattr) (const char *, const char *);
	int (*opendir) (const char *, struct fuse_file_info *);
	int (*readdir) (const char *, void *, fuse_fill_dir_t, off_t, struct fuse_file_info *);
	int (*releasedir) (const char *, struct fuse_file_info *);
	int (*fsyncdir) (const char *, int, struct fuse_file_info *);
	void *(*init) (struct fuse_conn_info *conn);
	void (*destroy) (void *);
	int (*access) (const char *, int);
	int (*create) (const char *, mode_t, struct fuse_file_info *);
	int (*ftruncate) (const char *, off_t, struct fuse_file_info *);
	int (*fgetattr) (const char *, struct stat *, struct fuse_file_info *);
	int (*lock) (const char *, struct fuse_file_info *, int cmd, struct flock *);
	int (*utimens) (const char *, const struct timespec tv[2]);
	int (*bmap) (const char *, size_t blocksize, uint64_t *idx);
	unsigned int flag_nullpath_ok:1;
	unsigned int flag_nopath:1;
	unsigned int flag_utime_omit_ok:1;
	unsigned int flag_reserved:29;
	int (*ioctl) (const char *, int cmd, void *arg, struct fuse_file_info *, unsigned int flags, void *data);
	int (*poll) (const char *, struct fuse_file_info *, struct fuse_pollhandle *ph, unsigned *reventsp);
	int (*write_buf) (const char *, struct fuse_bufvec *buf, off_t off, struct fuse_file_info *);
	int (*read_buf) (const char *, struct fuse_bufvec **bufp, size_t size, off_t off, struct fuse_file_info *);
	int (*flock) (const char *, struct fuse_file_info *, int op);
	int (*fallocate) (const char *, int, off_t, off_t, struct fuse_file_info *);
};

int fuse_main_real(int argc, char *argv[], const struct fuse_operations *op, size_t op_size, void *user_data);

typedef unsigned long fuse_ino_t;
typedef struct fuse_req *fuse_req_t;
struct fuse_entry_param
{
	fuse_ino_t ino;
	unsigned long generation;
	struct stat attr;
	double attr_timeout;
	double entry_timeout;
};
struct fuse_forget_data
{
	uint64_t ino;
	uint64_t nlookup;
};

struct fuse_lowlevel_ops
{
	void (*init) (void *userdata, struct fuse_conn_info *conn);
	void (*destroy) (void *userdata);
	void (*lookup) (fuse_req_t req, fuse_ino_t parent, const char *name);
	void (*forget) (fuse_req_t req, fuse_ino_t ino, unsigned long nlookup);
	void (*getattr) (fuse_req_t req, fuse_ino_t ino, struct fuse_file_info *fi);
	void (*setattr) (fuse_req_t req, fuse_ino_t ino, struct stat *attr, int to_set, struct fuse_file_info *fi);
	void (*readlink) (fuse_req_t req, fuse_ino_t ino);
	void (*mknod) (fuse_req_t req, fuse_ino_t parent, const char *name, mode_t mode, dev_t rdev);
	void (*mkdir) (fuse_req_t req, fuse_ino_t parent, const char *name, mode_t mode);
	void (*unlink) (fuse_req_t req, fuse_ino_t parent, const char *name);
	void (*rmdir) (fuse_req_t req, fuse_ino_t parent, const char *name);
	void (*symlink) (fuse_req_t req, const char *link, fuse_ino_t parent, const char *name);
	void (*rename) (fuse_req_t req, fuse_ino_t parent, const char *name, fuse_ino_t newparent, const char *newname);
	void (*link) (fuse_req_t req, fuse_ino_t ino, fuse_ino_t newparent, const char *newname);
	void (*open) (fuse_req_t req, fuse_ino_t ino, struct fuse_file_info *fi);
	void (*read) (fuse_req_t req, fuse_ino_t ino, size_t size, off_t off, struct fuse_file_info *fi);
	void (*write) (fuse_req_t req, fuse_ino_t ino, const char *buf, size_t size, off_t off, struct fuse_file_info *fi);
	void (*flush) (fuse_req_t req, fuse_ino_t ino, struct fuse_file_info *fi);
	void (*release) (fuse_req_t req, fuse_ino_t ino, struct fuse_file_info *fi);
	void (*fsync) (fuse_req_t req, fuse_ino_t ino, int datasync, struct fuse_file_info *fi);
	void (*opendir) (fuse_req_t req, fuse_ino_t ino, struct fuse_file_info *fi);
	void (*readdir) (fuse_req_t req, fuse_ino_t ino, size_t size, off_t off, struct fuse_file_info *fi);
	void (*releasedir) (fuse_req_t req, fuse_ino_t ino, struct fuse_file_info *fi);
	void (*fsyncdir) (fuse_req_t req, fuse_ino_t ino, int datasync, struct fuse_file_info *fi);
	void (*statfs) (fuse_req_t req, fuse_ino_t ino);
	void (*setxattr) (fuse_req_t req, fuse_ino_t ino, const char *name, const char *value, size_t size, int flags);
	void (*getxattr) (fuse_req_t req, fuse_ino_t ino, const char *name, size_t size);
	void (*listxattr) (fuse_req_t req, fuse_ino_t ino, size_t size);
	void (*removexattr) (fuse_req_t req, fuse_ino_t ino, const char *name);
	void (*access) (fuse_req_t req, fuse_ino_t ino, int mask);
	void (*create) (fuse_req_t req, fuse_ino_t parent, const char *name,	mode_t mode, struct fuse_file_info *fi);
	void (*getlk) (fuse_req_t req, fuse_ino_t ino, struct fuse_file_info *fi, struct flock *lock);
	void (*setlk) (fuse_req_t req, fuse_ino_t ino, struct fuse_file_info *fi, struct flock *lock, int sleep);
	void (*bmap) (fuse_req_t req, fuse_ino_t ino, size_t blocksize, uint64_t idx);
	void (*ioctl) (fuse_req_t req, fuse_ino_t ino, int cmd, void *arg, struct fuse_file_info *fi, unsigned flags, const void *in_buf, size_t in_bufsz, size_t out_bufsz);
	void (*poll) (fuse_req_t req, fuse_ino_t ino, struct fuse_file_info *fi, struct fuse_pollhandle *ph);
	void (*write_buf) (fuse_req_t req, fuse_ino_t ino, struct fuse_bufvec *bufv, off_t off, struct fuse_file_info *fi);
	void (*retrieve_reply) (fuse_req_t req, void *cookie, fuse_ino_t ino, off_t offset, struct fuse_bufvec *bufv);
	void (*forget_multi) (fuse_req_t req, size_t count, struct fuse_forget_data *forgets);
	void (*flock) (fuse_req_t req, fuse_ino_t ino, struct fuse_file_info *fi, int op);
	void (*fallocate) (fuse_req_t req, fuse_ino_t ino, int mode, off_t offset, off_t length, struct fuse_file_info *fi);
};

struct fuse_session;
struct fuse_chan;
struct fuse_args
{
	int argc;
	char **argv;
	int allocated;
};
enum fuse_buf_copy_flags
{
	FUSE_BUF_NO_SPLICE	= (1 << 1),
	FUSE_BUF_FORCE_SPLICE	= (1 << 2),
	FUSE_BUF_SPLICE_MOVE	= (1 << 3),
	FUSE_BUF_SPLICE_NONBLOCK= (1 << 4),
};
struct iovec
{
    void  *iov_base;
    size_t iov_len;
};
struct fuse_session *fuse_lowlevel_new(struct fuse_args *args, const struct fuse_lowlevel_ops *op, size_t op_size, void *userdata);

int fuse_reply_err(fuse_req_t req, int err);

void fuse_reply_none(fuse_req_t req);

int fuse_reply_entry(fuse_req_t req, const struct fuse_entry_param *e);

int fuse_reply_create(fuse_req_t req, const struct fuse_entry_param *e,
		      const struct fuse_file_info *fi);

int fuse_reply_attr(fuse_req_t req, const struct stat *attr,
		    double attr_timeout);

int fuse_reply_readlink(fuse_req_t req, const char *link);

int fuse_reply_open(fuse_req_t req, const struct fuse_file_info *fi);

int fuse_reply_write(fuse_req_t req, size_t count);

int fuse_reply_buf(fuse_req_t req, const char *buf, size_t size);

int fuse_reply_data(fuse_req_t req, struct fuse_bufvec *bufv,
		    enum fuse_buf_copy_flags flags);

int fuse_reply_iov(fuse_req_t req, const struct iovec *iov, int count);

int fuse_reply_statfs(fuse_req_t req, const struct statvfs *stbuf);

int fuse_reply_xattr(fuse_req_t req, size_t count);

int fuse_reply_lock(fuse_req_t req, const struct flock *lock);

int fuse_reply_bmap(fuse_req_t req, uint64_t idx);

 size_t fuse_add_direntry(fuse_req_t req, char *buf, size_t bufsize,
 			 const char *name, const struct stat *stbuf,
 			 off_t off);
int fuse_reply_ioctl_retry(fuse_req_t req,
			   const struct iovec *in_iov, size_t in_count,
			   const struct iovec *out_iov, size_t out_count);

int fuse_reply_ioctl(fuse_req_t req, int result, const void *buf, size_t size);

int fuse_reply_ioctl_iov(fuse_req_t req, int result, const struct iovec *iov,
			 int count);

int fuse_reply_poll(fuse_req_t req, unsigned revents);

int fuse_lowlevel_notify_poll(struct fuse_pollhandle *ph);

int fuse_lowlevel_notify_inval_inode(struct fuse_chan *ch, fuse_ino_t ino,
                                     off_t off, off_t len);

int fuse_lowlevel_notify_inval_entry(struct fuse_chan *ch, fuse_ino_t parent,
                                     const char *name, size_t namelen);

int fuse_lowlevel_notify_delete(struct fuse_chan *ch,
				fuse_ino_t parent, fuse_ino_t child,
				const char *name, size_t namelen);

int fuse_lowlevel_notify_store(struct fuse_chan *ch, fuse_ino_t ino,
			       off_t offset, struct fuse_bufvec *bufv,
			       enum fuse_buf_copy_flags flags);
int fuse_lowlevel_notify_retrieve(struct fuse_chan *ch, fuse_ino_t ino,
				  size_t size, off_t offset, void *cookie);

void *fuse_req_userdata(fuse_req_t req);

int fuse_parse_cmdline(struct fuse_args *args, char **mountpoint,
		       int *multithreaded, int *foreground);

struct fuse_session_ops
{
	void (*process) (void *data, const char *buf, size_t len,
			 struct fuse_chan *ch);
	void (*exit) (void *data, int val);
	int (*exited) (void *data);
	void (*destroy) (void *data);
};
struct fuse_session *fuse_session_new(struct fuse_session_ops *op, void *data);
size_t fuse_buf_size(const struct fuse_bufvec *bufv);

ssize_t fuse_buf_copy(struct fuse_bufvec *dst, struct fuse_bufvec *src,
		      enum fuse_buf_copy_flags flags);

int fuse_set_signal_handlers(struct fuse_session *se);

void fuse_remove_signal_handlers(struct fuse_session *se);

int fuse_daemonize(int foreground);

int fuse_version(void);

void fuse_pollhandle_destroy(struct fuse_pollhandle *ph);

void fuse_session_add_chan(struct fuse_session *se, struct fuse_chan *ch);

void fuse_session_remove_chan(struct fuse_chan *ch);

struct fuse_chan *fuse_session_next_chan(struct fuse_session *se,
					 struct fuse_chan *ch);

void fuse_session_process(struct fuse_session *se, const char *buf, size_t len,
			  struct fuse_chan *ch);

void fuse_session_process_buf(struct fuse_session *se,
			      const struct fuse_buf *buf, struct fuse_chan *ch);

int fuse_session_receive_buf(struct fuse_session *se, struct fuse_buf *buf,
			     struct fuse_chan **chp);

void fuse_session_destroy(struct fuse_session *se);

void fuse_session_exit(struct fuse_session *se);

void fuse_session_reset(struct fuse_session *se);

int fuse_session_exited(struct fuse_session *se);

void *fuse_session_data(struct fuse_session *se);
struct fuse_opt
{
	const char *templ;
	unsigned long offset;
	int value;
};
typedef int (*fuse_opt_proc_t)(void *data, const char *arg, int key,
			       struct fuse_args *outargs);

int fuse_opt_parse(struct fuse_args *args, void *data,
		   const struct fuse_opt opts[], fuse_opt_proc_t proc);

int fuse_opt_add_opt(char **opts, const char *opt);

int fuse_opt_add_opt_escaped(char **opts, const char *opt);

int fuse_opt_add_arg(struct fuse_args *args, const char *arg);

int fuse_opt_insert_arg(struct fuse_args *args, int pos, const char *arg);

void fuse_opt_free_args(struct fuse_args *args);

struct fuse_chan *fuse_mount(const char *mountpoint, struct fuse_args *args);

void fuse_unmount(const char *mountpoint, struct fuse_chan *ch);

int fuse_session_loop(struct fuse_session *se);

int fuse_session_loop_mt(struct fuse_session *se);