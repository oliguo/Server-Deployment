#Server Deployment

Hi All,just a guide to setup Software**(LAMPP)** on ***Ubuntu*** for my memo.

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

##Apache/MySql/PHP/PHPMyAdmin
**reference** [1](https://gregrickaby.com/2013/05/how-to-install-lamp-on-ubuntu/)
[2](https://www.digitalocean.com/community/tutorials/how-to-install-linux-apache-mysql-php-lamp-stack-on-ubuntu-14-04)

<pre>
sudo apt-get update && sudo apt-get upgrade && sudo apt-get dist-upgrade
</pre>

**Apache**

<pre>
sudo apt-get install apache2 apache2-utils libapache2-mod-php5
which apache2
apache2 -v
*Uninstall:
sudo apt-get remove --purge apache2 apache2-utils
sudo apt-get remove apache2*
sudo apt-get autoremove
</pre>

**MySql**
<pre>
sudo apt-get install mysql-server-5.6 mysql-client-5.6
which mysql
mysql -V
sudo apt-get autoremove --purge mysql-server-5.6
sudo apt-get autoremove --purge mysql-client-5.6
</pre>

**PHP**
<pre>
sudo apt-get install php5 php5-gd php5-mysql php5-curl php5-cli php5-cgi php5-dev php5-mcrypt
which php
php -v
</pre>

**PhpMyAdmin**
<pre>
sudo add-apt-repository -y ppa:ondrej/php
sudo apt-get update
sudo apt-get install phpmyadmin
</pre>

##PHP Setting
**reference** [1](https://www.digitalocean.com/community/tutorials/how-to-install-linux-apache-mysql-php-lamp-stack-on-ubuntu-14-04)

Make first look for a file called **index.php**. 

<pre>
sudo nano /etc/apache2/mods-enabled/dir.conf
</pre>

Modify like below:
<pre>
\<IfModule mod_dir.c>
    DirectoryIndex **index.php** index.html index.cgi index.pl index.xhtml index.htm
\</IfModule>
</pre>

Then restart
<pre>
sudo service apache2 restart
</pre>

##PHPMyAdmin Setting

<pre>
sudo nano /etc/apache2/apache2.conf
</pre>

Add line below:
<pre>
Include /etc/phpmyadmin/apache.conf
</pre>

Then restart
<pre>
sudo service apache2 restart
</pre>

Notice:
If show Error below:
<pre>
[alias:warn] [pid 22355] AH00671: The Alias directive in /etc/phpmyadmin/apache.conf at line 3 will probably never match because it overlaps an earlier Alias.
</pre>

Then remove above line below:
<pre>
Include /etc/phpmyadmin/apache.conf
</pre>

And restart:
<pre>
sudo service apache2 restart
</pre>

---
If phpmyadmin show error on UI Panel like below:
<pre>
The mcrypt extension is missing. Please check your PHP configuration.
</pre>

So,do below:
<pre>
sudo updatedb 
locate mcrypt.ini
</pre>
Should show it located at /etc/php5/mods-available
<pre>
locate mcrypt.so
</pre>
Edit mcrypt.ini and change extension to match the path to mcrypt.so, example:
<pre>
extension=/usr/lib/php5/20121212/mcrypt.so
</pre>
Then exec and restart:
<pre>
sudo php5enmod mcrypt
sudo service apache2 restart 
</pre>











