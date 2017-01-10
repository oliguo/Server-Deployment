##XAMPP Version 5.6.28
***Last Edit:2016-12-26***

reference [here](https://www.apachefriends.org/faq_linux.html)

###Download And Install
<pre>
wget http://jaist.dl.sourceforge.net/project/xampp/XAMPP%20Linux/5.6.28/xampp-linux-x64-5.6.28-0-installer.run
sudo chmod 777 xampp-linux-*-installer.run
sudo ./xampp-linux-*-installer.run
</pre>

###Restart and Secure Xampp
<pre>
sudo /opt/lampp/lampp restart
sudo /opt/lampp/lampp security
</pre>

###Access Forbidden Setting
```
sudo /opt/lampp/lampp stop
sudo nano /opt/lampp/etc/extra/httpd-xampp.conf
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

###XAMPP Auto Start
reference [here](http://computernetworkingnotes.com/ubuntu-12-04-tips-and-tricks/how-to-start-xampp-automatically-in-ubuntu.html)

<pre>
sudo nano /etc/init.d/lampp
</pre>

add below:
```
#!/bin/bash
/opt/lampp/lampp start
```
then:
<pre>
sudo chmod +x /etc/init.d/lampp
sudo update-rc.d lampp defaults
sudo reboot
</pre>

##Disable Directory Index
<pre>
sudo nano /opt/lampp/etc/httpd.conf
</pre>
edit like below without **Indexes**:
<pre>
Options Includes FollowSymLinks MultiViews
</pre>

##Create FTP User (Proftp)

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
sudo /opt/lampp/etc/proftpd.conf
*and comment below:
#DefaultRoot /opt/lampp/htdocs
*and add user specify folder
DefaultRoot /opt/lampp/htdocs/ABC  ABCftp
*restart lampp
</pre>

##Virtual Host Setup
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
