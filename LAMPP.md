##LAMPP

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











