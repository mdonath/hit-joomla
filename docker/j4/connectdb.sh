#!/bin/bash

docker run -it --network hit-dev-network --rm mariadb mariadb -hhitdevdb -ujoomla --password=testtest --database=hitjoomla4
