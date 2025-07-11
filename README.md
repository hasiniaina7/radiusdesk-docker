# RADIUSdesk Docker

Dockerized version of the [RADIUSdesk Project](https://www.radiusdesk.com/wiki24/start). Find the source code
[here](https://github.com/RADIUSdesk/rdcore).

## Quickstart

Ensure you have docker and docker compose installed and simply run: `docker compose up -d`. This will pull MariaDB and
the RADIUSdesk docker container and then build the containers for you with the necessary config.

## Accessing the Services

Once you have run the docker-compose.yml file and the service is up (check its status using `docker logs radiusdesk`)
you can log in with the default credentials at http://127.0.0.1:80/.

**The default username and password for RADIUSdesk are root and admin, respectively.**

## Important Considerations

The docker service name for the database and the username and passwords in the database config need to match those in
the RADIUSdesk config in the original repo, which defaults to the passwords listed above. Changing these will not work
in version 4.\*.

The [app_local.php](src/app_local.php) file specifies the details in the original repo.

## Dockerhub

The container can be found on Dockerhub [here](https://hub.docker.com/repository/docker/keegan337/radiusdesk/general).

## Publishing

To see what commit the the Dockerhub image relates to check [this file](./publishing.md)
