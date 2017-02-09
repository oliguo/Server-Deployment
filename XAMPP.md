##XAMPP Version 5.6.28
***Last Edit:2017-02-09***

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
/opt/lampp/lampp restart
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
sudo nano /opt/lampp/etc/proftpd.conf
*and comment below:
#DefaultRoot /opt/lampp/htdocs
*and add user specify folder
DefaultRoot /opt/lampp/htdocs/ABC  ABCftp
*restart lampp
*and set user permission
sudo chown -R ABCuser:ABCftp /var/www/test/public_html
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

##SSL Setup with Xampp（Authorized with godaddy）

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
        Options All
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
        Options All
    	AllowOverride All
    	Require all granted
    </Directory>
    ErrorLog "/opt/lampp/htdocs/youdomain_B/domain_ssl_error_log"
    ErrorDocument 404 https://youdomain_B 
</VirtualHost>
```


##Config Overall Rating to 'A' from SSLLab Report Test
1.make sure your xampp included newest openssl,if not,please install newest xampp to upgrade.

2.
<pre>
sudo nano /opt/lampp/etc/extra/httpd-ssl.conf
</pre>

add below in file

<pre>
SSLHonorCipherOrder on 
SSLCipherSuite "EECDH+ECDSA+AESGCM EECDH+aRSA+AESGCM EECDH+ECDSA+SHA384 EECDH+ECDSA+SHA256 EECDH+aRSA+SHA384 EECDH+aRSA+SHA256 EECDH+aRSA+RC4 EECDH EDH+aRSA RC4 !aNULL !eNULL !LOW !3DES !MD5 !EXP !PSK !SRP !DSS !RC4"
</pre>

Remember to restart xampp

<pre>
/opt/lampp/lampp restart
</pre>

