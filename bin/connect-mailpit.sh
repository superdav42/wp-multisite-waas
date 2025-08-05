#!/usr/bin/env bash

set -euo pipefail
IFS=$'\n\t'

MAILPIT_CONTAINER_NAME="mailpit"
MAILPIT_IMAGE="axllent/mailpit"
MAILPIT_PORT_UI=8025
MAILPIT_PORT_SMTP=1025

# STEP 1: Start Mailpit if not running
if ! docker ps --filter "name=${MAILPIT_CONTAINER_NAME}" --format "{{.Names}}" | grep -q "^${MAILPIT_CONTAINER_NAME}$"; then
  echo "➡️  Starting Mailpit container..."
  docker run -d \
    --name "$MAILPIT_CONTAINER_NAME" \
    -p "${MAILPIT_PORT_UI}:8025" \
    -p "${MAILPIT_PORT_SMTP}:1025" \
    "$MAILPIT_IMAGE"
else
  echo "✅ Mailpit container already running."
fi

# STEP 2: Identify ALL wordpress containers
WP_ENV_CONTAINERS=($(docker ps --filter "name=wordpress" --format "{{.Names}}"))

if [[ ${#WP_ENV_CONTAINERS[@]} -eq 0 ]]; then
  echo "❌ Could not find any wp-env containers. Is wp-env running?" >&2
  exit 1
fi

echo "🔍 Found wp-env containers: ${WP_ENV_CONTAINERS[*]}"

# STEP 3: Loop through each container to get its network
for WP_ENV_CONTAINER in "${WP_ENV_CONTAINERS[@]}"; do
  WP_ENV_NETWORK=$(docker inspect "$WP_ENV_CONTAINER" | \
    grep -A 20 '"Networks": {' | \
    grep '": {' | \
    grep -v '"Networks": {' | \
    head -n1 | \
    sed 's/^[[:space:]]*"\([^"]*\)".*/\1/')

  if [[ -z "$WP_ENV_NETWORK" ]]; then
    echo "❌ Could not determine Docker network for container $WP_ENV_CONTAINER." >&2
    continue
  fi

  echo "📡 Network for $WP_ENV_CONTAINER: $WP_ENV_NETWORK"

  # STEP 4: Connect Mailpit to this network if not already connected
  if docker network inspect "$WP_ENV_NETWORK" | grep -q "\"Name\": \"${MAILPIT_CONTAINER_NAME}\""; then
    echo "🔗 Mailpit already connected to $WP_ENV_NETWORK"
  else
    echo "🔗 Connecting Mailpit to network: $WP_ENV_NETWORK"
    docker network connect "$WP_ENV_NETWORK" "$MAILPIT_CONTAINER_NAME"
  fi
done

echo "✅ Mailpit is ready! Access it at: http://localhost:${MAILPIT_PORT_UI}"
