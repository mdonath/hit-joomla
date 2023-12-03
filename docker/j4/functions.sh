#!/bin/bash

###########################################

setup_network() {
  docker network create hit-dev-network
}

teardown_network() {
  docker network rm hit-dev-network
}

###########################################

setup_database() {
  docker pull mariadb:lts
  docker volume create hit-dev-db
  docker run \
    --detach \
    --name hitdevdb \
    --volume hit-dev-db \
    --network hit-dev-network \
    --publish 3306:3306 \
    --env "MARIADB_USER=joomla" \
    --env "MARIADB_PASSWORD=testtest" \
    --env "MARIADB_DATABASE=hitjoomla4" \
    --env "MARIADB_ROOT_PASSWORD=rootroot" \
    mariadb:lts

    echo Waiting 5 seconds...
    sleep 5
}

start_database() {
  docker start hitdevdb
}

stop_database() {
  docker stop hitdevdb
}

teardown_database() {
  docker stop hitdevdb
  docker container rm hitdevdb
  docker volume rm hit-dev-db
}

###########################################

setup_joomla() {
  docker pull joomla:4.3
  docker volume create hit-dev-data
  docker run \
    --name hitdevjoomla \
    --publish 80:80 \
    --volume hit-dev-data:/var/www/html \
    --volume $(pwd)/work_directory:/var/www/work_directory \
    --network hit-dev-network \
    --env "JOOMLA_DB_HOST=hitdevdb" \
    --env "JOOMLA_DB_USER=joomla" \
    --env "JOOMLA_DB_PASSWORD=testtest" \
    --env "JOOMLA_DB_NAME=hitjoomla4" \
    --env "MARIADB_ROOT_PASSWORD=rootroot" \
    hit_dev_docker
}

start_joomla() {
  docker start -i hitdevjoomla
}

stop_joomla() {
  docker stop hitdevjoomla
}

teardown_joomla() {
  docker stop hitdevjoomla
  docker container rm hitdevjoomla
  docker volume rm hit-dev-data
}

###########################################

setup_all() {
  echo Setting up

  setup_network
  setup_database
  setup_joomla
}

teardown_all() {
  echo Tearing down

  teardown_joomla
  teardown_database
  teardown_network
}

###########################################

start_all() {
  start_database
  start_joomla
}

###########################################
