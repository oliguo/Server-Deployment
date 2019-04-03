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
 -v /Users/oliguo/Work-Dev/Docker/registry/data:/var/lib/registry \
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
 -v /Users/oliguo/Work-Dev/Docker/portainer/data:/data \
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
 --volume /Users/oliguo/Work-Dev/Docker/gitlab/config:/etc/gitlab \
 --volume /Users/oliguo/Work-Dev/Docker/gitlab/logs:/var/log/gitlab \
 --volume /Users/oliguo/Work-Dev/Docker/gitlab/data:/var/opt/gitlab \
 gitlab/gitlab-ce
```

## MySQL
```
docker run -itd -p 3306:3306 \
 --name mysql-5.7 \
 --restart always \
 -v /Users/oliguo/Work-Dev/Docker/mysql/data:/var/lib/mysql \
 -v /Users/oliguo/Work-Dev/Docker/mysql/log:/var/log/mysql \
 -v /Users/oliguo/Work-Dev/Docker/mysql/config/mysql.conf.d:/etc/mysql/mysql.conf.d \
 -v /Users/oliguo/Work-Dev/Docker/mysql/config/conf.d:/etc/mysql/conf.d \
 -e MYSQL_ROOT_PASSWORD=root \
 mysql:5.7

docker run -d -p 8080:80 \
 --name phpmyadmin-4.7 \
 --restart always \
 -e PMA_ARBITRARY=1 \
 -v /Users/oliguo/Work-Dev/Docker/phpmyadmin/config.user.inc.php:/etc/phpmyadmin/config.user.inc.php \
 phpmyadmin/phpmyadmin:4.7
```


