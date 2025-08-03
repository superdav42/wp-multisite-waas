#!/usr/bin/env bash

set -euo pipefail
IFS=$'\n\t'

# ---------------------------------------------------------------------------------------------------------------------

# script runs/stops phpmyadmin docker image as a container that's configured to connect to a running wp-env database

# author: Kevin Firko (@firxworx)
# date: 2023-10-24
# version: 1.0.0
# license: MIT (https://opensource.org/license/mit/)

# @see https://www.npmjs.com/package/@wordpress/env
# @see https://developer.wordpress.org/block-editor/reference-guides/packages/packages-env/

# ---------------------------------------------------------------------------------------------------------------------

# description:
# - the script will launch a container named 'phpmyadmin' at: http://localhost:8080

# dependencies:
# - wp-env must be installed and configured for your project (@wordpress/env)
# - docker must be installed and running
# - ensure the script is executable (e.g. chmod +x pma-docker.sh)

# assumptions:
# wp-env must be running
# wp-env must be using 'mariadb' image for its mysql-compatible database
# wp-env must be using 2x database containers that respectively include 'mysql' and 'tests-mysql' in their name
# wp-env is configured to bridge the database container to the host network port 3306 (mysql default port)
# your local dev workstation has no port conflicts (e.g. 3306, 8080, etc)

# usage:
# ./scripts/phpmyadmin/pma-docker.sh

# notes:
# - the docker command is run with `--rm` option to remove the container when stopped

# ---------------------------------------------------------------------------------------------------------------------

# local phpmyadmin port
HOST_PMA_PORT=8080
HOST_PMA_CONTAINER_NAME="phpmyadmin"

# docker image name filter
IMAGE_NAME_FILTER="mariadb:lts"
CONTAINER_NAME_FILTER="mysql"

# used to exclude 'tests-mysql' container from the results
EXCLUDE_GREP_FILTER_PATTERN="tests-mysql"

# Check if port is available
if netstat -ano | grep ":$HOST_PMA_PORT " | grep LISTEN > /dev/null; then
  echo "ERROR: Port $HOST_PMA_PORT is already in use." >&2
  exit 1
fi

if docker ps --filter "name=$HOST_PMA_CONTAINER_NAME" --format "{{.Names}}" | grep -q "phpmyadmin"; then
  echo "$HOST_PMA_CONTAINER_NAME container is running... stopping..."
  docker stop "$HOST_PMA_CONTAINER_NAME" &> /dev/null
  echo "$HOST_PMA_CONTAINER_NAME container stopped"
  exit
else
  echo "preparing to start $HOST_PMA_CONTAINER_NAME container..."
  echo
  echo "- checking for running wp-env containers..."
fi

echo "DEBUG: Matching DB containers:"
docker ps --filter "name=$CONTAINER_NAME_FILTER" --filter "ancestor=$IMAGE_NAME_FILTER" --format "{{.Names}}"

# Get the name of the container excluding the tests container
DB_CONTAINER_NAME=$(docker ps \
  --filter "name=$CONTAINER_NAME_FILTER" \
  --filter "ancestor=$IMAGE_NAME_FILTER" \
  --format "{{.Names}}" | grep -v "$EXCLUDE_GREP_FILTER_PATTERN"
)

echo "DEBUG: DB_CONTAINER_NAME=$DB_CONTAINER_NAME"

if [[ -z "$DB_CONTAINER_NAME" ]]; then
  echo "ERROR: no matching container found. is wp-env running?" >&2
  exit 1
fi

echo "DEBUG: docker inspect output:"
docker inspect "$DB_CONTAINER_NAME"

WP_ENV_NETWORK=$(docker inspect "$DB_CONTAINER_NAME" | grep -A 20 '"Networks": {' | grep '": {' | grep -v '"Networks": {' | head -n1 | sed 's/^[[:space:]]*"\([^"]*\)".*/\1/')
if [[ -z "$WP_ENV_NETWORK" ]]; then
  echo "ERROR: failed to resolve the network for container $DB_CONTAINER_NAME." >&2
  exit 1
fi

echo "- found wp-env database container: $DB_CONTAINER_NAME"
echo "- found wp-env network name: $WP_ENV_NETWORK"

# Use PMA_HOSTS to allow connecting to both main and test DBs
PMA_CONTAINER_ID=$(docker run \
  -d \
  --rm \
  --name "$HOST_PMA_CONTAINER_NAME" \
  -e PMA_ARBITRARY=1 \
  -e PMA_HOSTS="mysql,tests-mysql" \
  -e PMA_USER=root \
  -e PMA_PASSWORD=password \
  -p "$HOST_PMA_PORT:80" \
  --network "$WP_ENV_NETWORK" \
  phpmyadmin/phpmyadmin:latest
)

echo "- $HOST_PMA_CONTAINER_NAME container started in detached mode: $PMA_CONTAINER_ID"
echo
echo "$HOST_PMA_CONTAINER_NAME is now running at: http://localhost:$HOST_PMA_PORT"
echo
echo "You can select 'mysql' or 'mysql-test' as the server in the phpMyAdmin login screen."
echo
echo "stop the container by executing the following command"
echo "docker stop $HOST_PMA_CONTAINER_NAME"
echo
echo "happy coding!"

while docker ps --filter "name=$HOST_PMA_CONTAINER_NAME" --format "{{.Names}}" | grep -q "$HOST_PMA_CONTAINER_NAME"; do
  sleep 2
done