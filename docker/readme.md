# Docker Local Development Memo

## Change Local Host
```
sudo nano /etc/hosts
```
```
127.0.0.1       localhost dev.oli
255.255.255.255 broadcasthost
::1             localhost dev.oli
```

## Pull Images from Docker Hub
```
docker pull gitlab/gitlab-ce
docker pull portainer/portainer
docker pull mysql
docker pull phpmyadmin/phpmyadmin
```

## Create Own Registry and Pull Images[Optional]
[Ref.](https://docs.docker.com/registry/deploying/)
```
docker run -d -p 15000:5000 \
 --restart=always \
 --name dev-registry \
 -v /local-path/Docker/registry/data:/var/lib/registry \
 registry:2

docker image tag ubuntu dev.oli:15000/ubuntu
docker push dev.oli:15000/ubuntu
```

## Portainer
[Ref.](https://www.portainer.io/installation/)
```
docker run -d -p 9000:9000 \
 --name portainer \ 
 --restart always \
 -v /var/run/docker.sock:/var/run/docker.sock \
 -v /local-path/Docker/portainer/data:/data \
 portainer/portainer
```

## Gitlab
[Ref.](https://docs.gitlab.com/omnibus/docker/README.html#after-starting-a-container)
```
docker run --detach \
 --hostname dev.oli \
 --publish 10443:443 --publish 10080:80 --publish 10022:22 \
 --name gitlab \
 --restart always \
 --volume /local-path/Docker/gitlab/config:/etc/gitlab \
 --volume /local-path/Docker/gitlab/logs:/var/log/gitlab \
 --volume /local-path/Docker/gitlab/data:/var/opt/gitlab \
 gitlab/gitlab-ce
```

## MySQL and PhpMyAdmin
```
docker run -itd -p 3306:3306 \
 --name mysql-5.7 \
 --restart always \
 -v /local-path/Docker/mysql/data:/var/lib/mysql \
 -v /local-path/Docker/mysql/log:/var/log/mysql \
 -v /local-path/Docker/mysql/config/mysql.conf.d:/etc/mysql/mysql.conf.d \
 -v /local-path/Docker/mysql/config/conf.d:/etc/mysql/conf.d \
 -e MYSQL_ROOT_PASSWORD=root \
 mysql:5.7

docker run -d -p 8080:80 \
 --name phpmyadmin-4.7 \
 --restart always \
 -e PMA_ARBITRARY=1 \
 -v /local-path/Docker/phpmyadmin/config.user.inc.php:/etc/phpmyadmin/config.user.inc.php \
 phpmyadmin/phpmyadmin:4.7
```

## Apache and PHP
```
docker run -itd \
 -p 90:80 \
 -p 99:443 \
 --name alpine-apache-php7 \
 --restart always \
 -v /local-path/Docker/alpine-apache-php7/htdocs:/var/www/localhost/htdocs \
 -v /local-path/Docker/alpine-apache-php7/config/apache2/httpd.conf:/etc/apache2/httpd.conf \
 -v /local-path/Docker/alpine-apache-php7/config/apache2/conf.d:/etc/apache2/conf.d \
 -v /local-path/Docker/alpine-apache-php7/config/php7/php.ini:/etc/php7/php.ini  \
 -v /local-path/Docker/alpine-apache-php7/log/apache2:/var/log/apache2  \
 -v /local-path/Docker/alpine-apache-php7/log/php7:/var/log/php7  \
 oliguo/alpine-apache-php7
```

