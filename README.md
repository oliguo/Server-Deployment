#Server Deployment

Hi All,just a guide to setup Software on ***Ubuntu*** for my memo.

**LAMPP** [here](https://github.com/oliguo/Server_Deployment/blob/master/LAMPP.md)

**XAMPP** 

##Check File/Folder Size

<pre>
df -h /path/xxx
du -sh xxxx.file
</pre>


##Server BandWidth Speed Test

**reference** [here](http://askubuntu.com/questions/104755/how-to-check-internet-speed-via-terminal)

<pre>
*speedtest
wget -O speedtest-cli-newest https://raw.githubusercontent.com/sivel/speedtest-cli/master/speedtest.py
</pre>

##Locale Language Setting
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
LC\_ALL=en_US.UTF-8 ssh \<name>@\<host>
</pre>

##External Package

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
sudo ppa-purge ppa\_name
</pre>
Note that this will uninstall packages provided by the PPA, but not those provided by the official repositories. If you want to remove them, you should tell it to apt:
<pre>
sudo apt-get purge package\_name
</pre>
You can also remove PPAs by deleting the .list files from /etc/apt/sources.list.d directory.


##Check Services or Ports,then Stop and Remove

<pre>
*service
service --status-all
sudo service xxxx stop
sudo apt-get remove xxx
sudo apt-get --purge remove
*port
sudo netstat -tunap | grep LISTEN
sudo kill xxxx
</pre>