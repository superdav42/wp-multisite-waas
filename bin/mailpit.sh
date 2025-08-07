#!/usr/bin/env bash

set -euo pipefail
IFS=$'\n\t'

HOST_MAILPIT_WEB_PORT=8025
HOST_MAILPIT_SMTP_PORT=1025
CONTAINER_NAME="mailpit"
WP_ENV_NETWORK="${1:-}"

# Determine platform
PLATFORM="$(uname -s)"

check_port_in_use() {
  local port=$1

  if [[ "$PLATFORM" == "Linux" || "$PLATFORM" == "Darwin" ]]; then
    ss -ltn 2>/dev/null | grep -q ":$port " || \
    netstat -ltn 2>/dev/null | grep -q ":$port "
  elif [[ "$PLATFORM" == MINGW* || "$PLATFORM" == CYGWIN* ]]; then
    powershell.exe -Command "& {Get-NetTCPConnection -State Listen -LocalPort $port}" | grep -q "$port"
  fi
}

if check_port_in_use "$HOST_MAILPIT_WEB_PORT"; then
  echo "ERROR: Port $HOST_MAILPIT_WEB_PORT is already in use." >&2
  exit 1
fi

if check_port_in_use "$HOST_MAILPIT_SMTP_PORT"; then
  echo "ERROR: Port $HOST_MAILPIT_SMTP_PORT is already in use." >&2
  exit 1
fi

# Detect wp-env Docker network if not passed
if [[ -z "$WP_ENV_NETWORK" ]]; then
  MYSQL_CONTAINER=$(docker ps --filter "name=mysql" --format "{{.Names}}" | grep -v "tests-mysql" | head -n1 || true)

  if [[ -z "$MYSQL_CONTAINER" ]]; then
    echo "ERROR: Could not detect wp-env mysql container to determine network." >&2
    exit 1
  fi

  WP_ENV_NETWORK=$(docker inspect "$MYSQL_CONTAINER" --format '{{range $k, $v := .NetworkSettings.Networks}}{{$k}}{{end}}')
  if [[ -z "$WP_ENV_NETWORK" ]]; then
    echo "ERROR: Could not extract network name from container $MYSQL_CONTAINER." >&2
    exit 1
  fi
fi

# Stop existing Mailpit
if docker ps --filter "name=$CONTAINER_NAME" --format "{{.Names}}" | grep -q "^$CONTAINER_NAME$"; then
  echo "Stopping existing $CONTAINER_NAME container..."
  docker stop "$CONTAINER_NAME" &> /dev/null
  echo "$CONTAINER_NAME stopped."
fi

# Start new Mailpit container on correct network
echo "Starting $CONTAINER_NAME container on network $WP_ENV_NETWORK..."

docker run -d --rm \
  --name "$CONTAINER_NAME" \
  -p "$HOST_MAILPIT_WEB_PORT:8025" \
  -p "$HOST_MAILPIT_SMTP_PORT:1025" \
  --network "$WP_ENV_NETWORK" \
  axllent/mailpit:latest

echo
echo "$CONTAINER_NAME container started in detached mode."
echo "Web UI available at http://localhost:$HOST_MAILPIT_WEB_PORT"
echo "SMTP listening on port $HOST_MAILPIT_SMTP_PORT"
echo
echo "To stop the container, run:"
echo "  docker stop $CONTAINER_NAME"
