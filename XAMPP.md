##XAMPP Version 5.6.28
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
sudo vim /etc/init.d/lampp
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

