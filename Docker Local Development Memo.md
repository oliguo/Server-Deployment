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
docker pull ubuntu
docker pull mysql:5.7
docker pull phpmyadmin/phpmyadmin:4.7
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
 -v /var/run/docker.sock:/var/run/docker.sock 
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
 gitlab/gitlab-ce:latest
```
