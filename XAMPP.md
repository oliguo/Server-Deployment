## XAMPP Version 5.6.28

reference [here](https://www.apachefriends.org/faq_linux.html)

### Download And Install
```
$ wget http://jaist.dl.sourceforge.net/project/xampp/XAMPP%20Linux/5.6.30/xampp-linux-x64-5.6.30-1-installer.run
$ sudo chmod 777 xampp-linux-*-installer.run
$ sudo ./xampp-linux-*-installer.run
```

### Restart and Secure Xampp
```
$ sudo /opt/lampp/lampp restart
$ sudo /opt/lampp/lampp security
```

### Access Forbidden Setting
```
$ sudo /opt/lampp/lampp stop
$ sudo nano /opt/lampp/etc/extra/httpd-xampp.conf
```
edit like below
```
# since XAMPP 1.4.3
<Directory "/opt/lampp/phpmyadmin">
    AllowOverride AuthConfig Limit
     Require all granted  #add this
#    Require local
    ErrorDocument 403 /error/XAMPP_FORBIDDEN.html.var
</Directory>

<Directory "/opt/lampp/phpsqliteadmin">
    AllowOverride AuthConfig Limit
Require all granted #add this
#    Require local
    ErrorDocument 403 /error/XAMPP_FORBIDDEN.html.var
</Directory>
```

### Enable rotatelog and access_log
```
$ sudo nano /opt/lampp/etc/httpd.conf

check need to enable mod_logio.c, and find codes as below 
<IfModule log_config_module>
    #
    # The following directives define some format nicknames for use with
    # a CustomLog directive (see below).
    #
    LogFormat "%h %l %u %t \"%r\" %>s %b \"%{Referer}i\" \"%{User-Agent}i\"" combined
    LogFormat "%h %l %u %t \"%r\" %>s %b" common

    <IfModule logio_module>
      # You need to enable mod_logio.c to use %I and %O
      LogFormat "%h %l %u %t \"%r\" %>s %b \"%{Referer}i\" \"%{User-Agent}i\" %I %O" combinedio
    </IfModule>

    #
    # The location and format of the access logfile (Common Logfile Format).
    # If you do not define any access logfiles within a <VirtualHost>
    # container, they will be logged here.  Contrariwise, if you *do*
    # define per-<VirtualHost> access logfiles, transactions will be
    # logged therein and *not* in this file.
    #
    #CustomLog "logs/access_log" common

    #
    # If you prefer a logfile with access, agent, and referer information
    # (Combined Logfile Format) you can use the following directive.
    #
    #CustomLog "logs/access_log" combined

    #add this line to enable
    LogFormat "%v %h %l %u %t \"%r\" %>s %D \"%{Referer}i\" \"%{User-Agent}i\" %I %O" combinedio-more
    CustomLog "|/opt/lampp/bin/rotatelogs /opt/lampp/logs/access_log.%Y-%m-%d 86400" combinedio-more
</IfModule>
```

### Goaccess monitoring access_log
```
https://goaccess.io/

To install
$ echo "deb http://deb.goaccess.io/ $(lsb_release -cs) main" | sudo tee -a /etc/apt/sources.list.d/goaccess.list
$ wget -O - https://deb.goaccess.io/gnugpg.key | sudo apt-key add -
$ sudo apt-get update
$ sudo apt-get install goaccess

Xampp 'combinedio-more' log-format 
$ goaccess access_log --time-format='%H:%M:%S' --date-format='%d/%b/%Y' --log-format='%v %h %^[%d:%t +0800%^] "%m %U %H" %s %D %R %u %^ %b' -o report.html

sed -n '/10\/Apr\/2019/,/11\/Apr\/2019/ p' access_log > access_log_filter_date
```

### XAMPP Auto Start
reference [here](http://computernetworkingnotes.com/ubuntu-12-04-tips-and-tricks/how-to-start-xampp-automatically-in-ubuntu.html)

<pre>
sudo nano /etc/init.d/lampp
</pre>

add below:
```
#!/bin/bash
### BEGIN INIT INFO
# Provides: lampp
# Required-Start:    $local_fs $syslog $remote_fs dbus
# Required-Stop:     $local_fs $syslog $remote_fs
# Default-Start:     2 3 4 5
# Default-Stop:      0 1 6
# Short-Description: Start lampp
### END INIT INFO
/opt/lampp/lampp start

```
then:
<pre>
sudo chmod +x /etc/init.d/lampp
sudo update-rc.d lampp defaults
sudo reboot
</pre>


## Disable Directory Index
<pre>
sudo nano /opt/lampp/etc/httpd.conf
</pre>
edit like below without **Indexes**:
<pre>
Options Includes FollowSymLinks MultiViews
</pre>

## Create FTP User (Proftp)

<pre>
*add Group
groupadd ABCftp
*if remove
groupdel ABCftp

*add user with home directory
useradd -m -d /opt/lampp/htdocs/ABC -g ABCftp -s /sbin/nologin ABCuser

    *if show error below:
    useradd: warning: the home directory already exists.
    Not copying any file from skel directory into it.
    *please remove the folder and re-create new one(recommend)

*set user password
passwd ABCuser

*set only folder which user access
*1.edit proftpd.conf
sudo nano /opt/lampp/etc/proftpd.conf
*and comment below:
#DefaultRoot /opt/lampp/htdocs
*and add user specify folder
DefaultRoot /opt/lampp/htdocs/ABC  ABCftp
*restart lampp
*and set user permission
sudo chown -R ABCuser:ABCftp /var/www/test/public_html

#check folder user and group
ls -l /path/your folder

#change user group
#To assign a primary group to an user:
usermod -g primarygroupname username

#To assign secondary groups to a user (-a keeps already existing secondary groups intact otherwise they'll be removed):
usermod -a -G secondarygroupname username

</pre>

## Virtual Host Setup
reference:
[1](http://serverfault.com/questions/246445/how-do-i-create-virtual-hosts-for-different-ports-on-apache/246474)
[2](http://www.9streets.cn/art-php-535.html)

<pre>
sudo nano /opt/lampp/etc/httpd.conf
</pre>
remove comment '#' below
<pre>
#Include etc/extra/httpd-vhosts.conf
*add listen ports you want like to listen

#like website
listen 80
#like another website
listen 81
....
</pre>
then
<pre>
sudo nano /opt/lampp/etc/extra/httpd-vhosts.conf
</pre>
add below sample
```  
  # Listen for virtual host requests on all IP addresses
  NameVirtualHost *:80

  <VirtualHost *:80>
    DocumentRoot /opt/lampp/htdocs/website_a
    ServerName www.website_a.com
    ErrorLog "/opt/lampp/htdocs/website_a/error_log"
  </VirtualHost>

  NameVirtualHost *:81
  <VirtualHost *:81>
    DocumentRoot /opt/lampp/htdocs/website_b
    ServerName www.website_b.com
    ErrorLog "/opt/lampp/htdocs/website_b/error_log"
  </VirtualHost>
```
<pre>
/opt/lampp/lampp restart
</pre>

## Blocking Direct IP Access in Apache
```
<VirtualHost *:80>
    ServerName 123.123.123.123
    Redirect 403 /
    ErrorDocument 403 "No"
    #ErrorDocument 403 https://github.com
    UseCanonicalName Off
    UserDir disabled
    ErrorLog "/opt/lampp/htdocs/IP_error_log"
</VirtualHost>
```

## SSL Setup with Xampp（Authorized with godaddy）

reference

[Ubuntu Linux服务器搭建SSL/TLS(https)](http://www.awaimai.com/126.html)

[Enabling Perfect Forward Secrecy](https://www.digicert.com/ssl-support/ssl-enabling-perfect-forward-secrecy.htm)

[How To Set Up Multiple SSL Host With A Single Apache Server](http://www.tutorialspoint.com/articles/how-to-set-up-multiple-ssl-host-with-a-single-apache-server)

1.
<pre>
cd /opt/lampp/etc/ssl.key
</pre>

2.
<pre>
openssl genrsa -out yourdomain.key 2048
</pre>

3.
<pre>
openssl req -new -key yourdomain.key -out yourdomain.csr
</pre>

then will enter info like detail below,and no to enter password:

<pre>
Country Name (2 letter code) [AU]:xxxx
State or Province Name (full name) [Some-State]:xxxx
Locality Name (eg, city) []:xxx
Organization Name (eg, company) [Internet Widgits Pty Ltd]:xxxx
Organizational Unit Name (eg, section) []:xxxx
Common Name (eg, YOUR name) []:yourdomain
Email Address []:your email
</pre>

4.open csr and copy cert string to godaddy to auth
<pre>
nano yourdomain.csr
</pre>

5.change virtual host to 443 port for multi-ssl

<pre>
sudo nano /opt/lampp/etc/extra/httpd-vhosts.conf
</pre>

then add below

```
NameVirtualHost *:443

<VirtualHost *:443>
    DocumentRoot "/opt/lampp/htdocs/youdomain_A"
    ServerName youdomain_A
    SSLProtocol all -SSLv2 -SSLv3
    SSLCertificateFile "/opt/lampp/etc/ssl.crt/youdomain_A/youdomain_A.crt" ->from godady
    SSLCertificateKeyFile "/opt/lampp/etc/ssl.key/youdomain_A.key"
    SSLCertificateChainFile "/opt/lampp/etc/ssl.crt/youdomain_A/gd_bundle-g2-g1.crt" ->from godaddy
    <Directory "/opt/lampp/htdocs/youdomain_A/">
        Options Includes FollowSymLinks MultiViews
    	AllowOverride All
    	Require all granted
    </Directory>
    ErrorLog "/opt/lampp/htdocs/youdomain_A/domain_ssl_error_log"
    ErrorDocument 404 https://youdomain_A 
</VirtualHost>

<VirtualHost *:443>
    DocumentRoot "/opt/lampp/htdocs/youdomain_B"
    ServerName youdomain_B
    SSLProtocol all -SSLv2 -SSLv3 ->improve Overall Rating to 'A' from SSLLab Report Test
    SSLCertificateFile "/opt/lampp/etc/ssl.crt/youdomain_B/youdomain_B.crt" ->from godady
    SSLCertificateKeyFile "/opt/lampp/etc/ssl.key/youdomain_B.key"
    SSLCertificateChainFile "/opt/lampp/etc/ssl.crt/youdomain_B/gd_bundle-g2-g1.crt" ->from godaddy
    <Directory "/opt/lampp/htdocs/youdomain_B/">
        Options Includes FollowSymLinks MultiViews
    	AllowOverride All
    	Require all granted
    </Directory>
    ErrorLog "/opt/lampp/htdocs/youdomain_B/domain_ssl_error_log"
    ErrorDocument 404 https://youdomain_B 
</VirtualHost>
```


## Config Overall Rating to 'A' from SSLLab Report Test
1.make sure your xampp included newest openssl,if not,please install newest xampp to upgrade.

2.
<pre>
sudo nano /opt/lampp/etc/extra/httpd-ssl.conf
</pre>

add below in file

reference
[Cipherli](https://cipherli.st/)

<pre>
SSLHonorCipherOrder on 
SSLCipherSuite "EECDH+ECDSA+AESGCM EECDH+aRSA+AESGCM EECDH+ECDSA+SHA384 EECDH+ECDSA+SHA256 EECDH+aRSA+SHA384 EECDH+aRSA+SHA256 EECDH+aRSA+RC4 EECDH EDH+aRSA RC4 !aNULL !eNULL !LOW !3DES !MD5 !EXP !PSK !SRP !DSS !RC4"
</pre>

Remember to restart xampp

<pre>
/opt/lampp/lampp restart
</pre>

Ather that you can go to SSLLab to test like

<pre>
https://www.ssllabs.com/ssltest/analyze.html?d=yourdomain&latest=yes
</pre>

like below

<img src="https://github.com/oliguo/Server_Deployment/blob/master/SSL_setup_SSLLab_overall_rating.png" height="500"/>

## Fixed issues:

### mysql_connect()“No such file or directory”
```
>locate mysql.sock
>>/opt/lampp/var/mysql/mysql.sock
>sudo nano /opt/lampp/etc/php.ini
find and change as below
pdo_mysql.default_socket=/opt/lampp/var/mysql/mysql.sock
mysql.default_socket=/opt/lampp/var/mysql/mysql.sock
mysqli.default_socket=/opt/lampp/var/mysql/mysql.sock
```

### Apache/2.2.22 (Unix) configured -- resuming normal operations
```
>sudo nano /opt/lampp/etc/httpd.conf
>find and comment all coding as below
#DocumentRoot "/opt/lampp/htdocs"
#<Directory "/opt/lampp/htdocs">
    #.....comment everything
#</Directory>
```



