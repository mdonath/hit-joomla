#!/bin/bash

DOCKERID=`docker container ls | grep hit_joomla_1 | awk '{ print $1 }'`

docker exec -it -u root $DOCKERID bash -l

