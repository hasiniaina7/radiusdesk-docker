#!bin/sh

docker build ./ -t enock/radiusdesk3-php7-ubuntu16-supervisord-v3
docker run --restart unless-stopped --net macvlan0 --ip 10.2.1.18  --name  radiusdeskv3.0 -it enock/radiusdesk3-php7-ubuntu16-supervisord-v3
