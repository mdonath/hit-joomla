#!/bin/bash

DOCKERID=`docker container ls | grep joomla | awk '{ print $1 }'`

docker exec -it $DOCKERID bash -l

