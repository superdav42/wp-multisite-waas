#!/usr/bin/env bash

set -euo pipefail
IFS=$'\n\t'

# ---------------------------------------------------------------------------------------------------------------------
# This script runs/stops a phpMyAdmin docker container configured to connect to a running wp-env database.
# ---------------------------------------------------------------------------------------------------------------------

HOST_PMA_PORT=8080
HOST_PMA_CONTAINER_NAME="phpmyadmin"

IMAGE_NAME_FILTER="mariadb:lts"
CONTAINER_NAME_FILTER="mysql"
EXCLUDE_GREP_FILTER_PATTERN="tests-mysql"

# Check if port is already in use (using netstat instead of netstat)
if netstat -ltn | grep -q ":$HOST_PMA_PORT "; then
  echo "ERROR: Port $HOST_PMA_PORT is already in use." >&2
  exit 1
fi

# Check if phpMyAdmin container is already running
if docker ps --filter "name=$HOST_PMA_CONTAINER_NAME" --format "{{.Names}}" | grep -q "^$HOST_PMA_CONTAINER_NAME$"; then
  echo "$HOST_PMA_CONTAINER_NAME container is running... stopping..."
  docker stop "$HOST_PMA_CONTAINER_NAME" &> /dev/null
  echo "$HOST_PMA_CONTAINER_NAME container stopped."
  exit 0
fi

echo "Preparing to start $HOST_PMA_CONTAINER_NAME container..."
echo "- Checking for running wp-env database containers..."

# Find database container excluding the test container
DB_CONTAINER_NAME=$(docker ps \
  --filter "name=$CONTAINER_NAME_FILTER" \
  --filter "ancestor=$IMAGE_NAME_FILTER" \
  --format "{{.Names}}" | grep -v "$EXCLUDE_GREP_FILTER_PATTERN" || true)

if [[ -z "$DB_CONTAINER_NAME" ]]; then
  echo "ERROR: No matching database container found. Is wp-env running?" >&2
  exit 1
fi

echo "- Found database container: $DB_CONTAINER_NAME"

# Determine network name for the DB container
WP_ENV_NETWORK=$(docker inspect "$DB_CONTAINER_NAME" \
  --format '{{range $k, $v := .NetworkSettings.Networks}}{{$k}}{{end}}')

if [[ -z "$WP_ENV_NETWORK" ]]; then
  echo "ERROR: Failed to resolve the network for container $DB_CONTAINER_NAME." >&2
  exit 1
fi

echo "- Found network name: $WP_ENV_NETWORK"

# Run phpMyAdmin container with connection to the wp-env network
docker run -d --rm \
  --name "$HOST_PMA_CONTAINER_NAME" \
  -e PMA_ARBITRARY=1 \
  -e PMA_HOSTS="mysql,tests-mysql" \
  -e PMA_VERBOSES="Development,Test" \
  -e PMA_USER=root \
  -e PMA_PASSWORD=password \
  -p "$HOST_PMA_PORT:80" \
  --network "$WP_ENV_NETWORK" \
  phpmyadmin/phpmyadmin:latest

echo
echo "$HOST_PMA_CONTAINER_NAME container started."
echo "Access phpMyAdmin at: http://localhost:$HOST_PMA_PORT"
echo
echo "You can select 'Development (root)' or 'Test (root)' server in the login screen."
echo
echo "To stop the container, run:"
echo "  docker stop $HOST_PMA_CONTAINER_NAME"
echo
echo "Happy coding!"