#!/bin/bash
docker run -d \
  --name "${3}-phpmyadmin" \
  -p 8080:80 \
  -e PMA_HOSTS="mysql,tests-mysql" \
  -e PMA_VERBOSES="Development,Test" \
  -e PMA_USER=root \
  -e PMA_PASSWORD=password \
  --network "$1" \
  -e PMA_HOST="$2" \
  -e PMA_PORT=3306 \
  phpmyadmin/phpmyadmin