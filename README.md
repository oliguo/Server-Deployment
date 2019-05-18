# Server Deployment

**LAMPP** [here](https://github.com/oliguo/Server_Deployment/blob/master/LAMPP.md)

**XAMPP** [here](https://github.com/oliguo/Server_Deployment/blob/master/XAMPP.md)

**Docker** [here](https://github.com/oliguo/Server_Deployment/tree/master/docker)

## Change Server Time

<pre>
sudo apt-get install ntp
</pre>
then,edit file
<pre>
sudo nano /etc/ntp.conf
</pre>
add below:
<pre>
server ntp.ubuntu.com
server pool.ntp.org
</pre>
restart
<pre>
sudo service ntp stop
sudo service ntp start
</pre>
Check server time and set timezone:
<pre>
sudo dpkg-reconfigure tzdata
</pre>
will show:
<pre>
Current default time zone: 'Asia/Hong_Kong'
Local time is now:      Wed Feb 22 20:16:02 HKT 2017.
Universal Time is now:  Wed Feb 22 12:16:02 UTC 2017.
</pre>

## Check File/Folder Size

<pre>
df -h /path/xxx
du -sh xxxx.file
</pre>

## Rename,Copy file/folder

<pre>
*rename
mv old.file new file

*if move file to some directory
mv /path/a.file /target/a.file

*copy
cp -r /path/folder/xxx /target/folder/xxx

*Recursive, Non-Overwriting File Copy
sudo cp -vnpr /xxx/* /yyy

xxx = source

yyy = destination

v = verbose

n = no clobber (no overwrite)

p = preserve permissions

r = recursive
</pre>

## Server BandWidth Speed Test

**reference** [here](http://askubuntu.com/questions/104755/how-to-check-internet-speed-via-terminal)

<pre>
*speedtest
wget -O speedtest-cli-newest https://raw.githubusercontent.com/sivel/speedtest-cli/master/speedtest.py
sudo chmod +x speedtest-cli-newest
sudo ./speedtest-cli-newest
</pre>

## Locale Language Setting
<pre>
perl: warning: Setting locale failed.
perl: warning: Please check that your locale settings:
    LANGUAGE = (unset),
    LC_ALL = (unset),
    LC_MESSAGES = "zh_CN.UTF-8",
    LANG = "zh_CN.UTF-8"
    are supported and installed on your system.
perl: warning: Falling back to the standard locale ("C").
</pre>

Then exec:
<pre>
sudo apt-get install localepurge
</pre>

Choose **en_US.UTF-8**,if want change again and print it to check:
<pre>
sudo dpkg-reconfigure localepurge
sudo locale-gen en_US.UTF-8
</pre>

Or mapping local locate to server like below:
<pre>
LC_ALL=en_US.UTF-8 ssh name@host
</pre>

## External Package

**reference** [here](http://askubuntu.com/questions/307/how-can-ppas-be-removed)

Use the **--remove** flag, similar to how the PPA was added:
<pre>
sudo add-apt-repository --remove ppa:whatever/ppa
</pre>
As a safer alternative, you can install ppa-purge:
<pre>
sudo apt-get install ppa-purge
</pre>
And then remove the PPA, downgrading gracefully packages it provided to packages provided by official repositories:
<pre>
sudo ppa-purge ppa_name
</pre>
Note that this will uninstall packages provided by the PPA, but not those provided by the official repositories. If you want to remove them, you should tell it to apt:
<pre>
sudo apt-get purge package_name
</pre>
You can also remove PPAs by deleting the .list files from /etc/apt/sources.list.d directory.


## Check Services or Ports,then Stop and Remove

<pre>
*service
service --status-all
sudo service xxxx stop
sudo apt-get remove xxx
sudo apt-get --purge remove xxx
*port
sudo netstat -tunap | grep LISTEN
sudo kill xxxx
</pre>

## How To Add Swap on Ubuntu 14.04
[reference](https://www.digitalocean.com/community/tutorials/how-to-add-swap-on-ubuntu-14-04)

<pre>

1.check swap:

sudo swapon -s

and:

free -m

2.create what you want memory for swap

sudo fallocate -l 4G /swapfile  =>4g

3.check swapfile

ls -lh /swapfile

4.enable swapfile

sudo chmod 600 /swapfile

5.check again

ls -lh /swapfile

6.next setup

sudo mkswap /swapfile

sudo swapon /swapfile

7.check it

sudo swapon -s

free -m

8.make it permanent

sudo nano /etc/fstab

add bottom:

/swapfile   none    swap    sw    0   0

</pre>

## Helpful visual to check traffic
```
sudo apt-get install slurm

slurm -s -i eth0
```

## Mount the drive
[Reference 1](https://yq.aliyun.com/articles/225380)

[Reference 2](https://www.cyberciti.biz/faq/mount-drive-from-command-line-ubuntu-linux/)
### list the drives
```
$ fdisk -l
```
### example result
```
root@abcd:~# fdisk -l
Disk /dev/vda: 40 GiB, 42949672960 bytes, 83886080 sectors
Units: sectors of 1 * 512 = 512 bytes
Sector size (logical/physical): 512 bytes / 512 bytes
I/O size (minimum/optimal): 512 bytes / 512 bytes
Disklabel type: dos
Disk identifier: 0x6c740fc2

Device     Boot Start      End  Sectors Size Id Type
/dev/vda1  *     2048 83886046 83883999  40G 83 Linux


Disk /dev/vdb: 40 GiB, 42949672960 bytes, 83886080 sectors
Units: sectors of 1 * 512 = 512 bytes
Sector size (logical/physical): 512 bytes / 512 bytes
I/O size (minimum/optimal): 512 bytes / 512 bytes
```
### To mount the drive:/dev/vdb
```
$ fdisk /dev/vdb
```
### following the steps
```
root@abcd:~# fdisk /dev/vdb

Welcome to fdisk (util-linux 2.31.1).
Changes will remain in memory only, until you decide to write them.
Be careful before using the write command.

Device does not contain a recognized partition table.
Created a new DOS disklabel with disk identifier 0xaa3faf38.

Command (m for help): n ->Here
Partition type
   p   primary (0 primary, 0 extended, 4 free)
   e   extended (container for logical partitions)
Select (default p): p ->Here
Partition number (1-4, default 1): 1
First sector (2048-xxx, default 2048): ->click 'enter;
Last sector, +sectors or +size{K,M,G,T,P} (2048-xxx, default xxx): ->click 'enter;

Created a new partition 1 of type 'Linux' and of size 40 GiB.

Command (m for help): wq ->Here
The partition table has been altered.
Calling ioctl() to re-read partition table.
Syncing disks.
```
### list the drive
```
$ fdisk -l
```
### format the drive
```
$ mkfs.ext3 /dev/vdb1  
```
### create mount folder
```
$ mkdir /data_drive_0
```
### automatic mount at boot time
```
$ echo '/dev/vdb1 /data_drive_0 ext3 defaults 0 0' >> /etc/fstab 
```
### mount all drive
```
$ mount -a
```
### view the mounted list
```
$ df -h
-> /dev/vdb1      39G   73M  39G   1% /data_drive_0
```
