#!/usr/bin/env bash

set -euo pipefail
IFS=$'\n\t'

MAILPIT_CONTAINER_NAME="mailpit"
MAILPIT_IMAGE="axllent/mailpit"
MAILPIT_PORT_UI=8025
MAILPIT_PORT_SMTP=1025

# STEP 1: Check if mailpit container is running, if not, start it
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

# STEP 2: Identify one of the wp-env containers (e.g., cli or wordpress)
WP_ENV_CONTAINER=$(docker ps --filter "name=_wordpress" --format "{{.Names}}" | head -n1)

if [[ -z "$WP_ENV_CONTAINER" ]]; then
  echo "❌ Could not find a wp-env container. Is wp-env running?" >&2
  exit 1
fi

# STEP 3: Get the Docker network from that container
WP_ENV_NETWORK=$(docker inspect "$WP_ENV_CONTAINER" | \
  grep -A 20 '"Networks": {' | \
  grep '": {' | \
  grep -v '"Networks": {' | \
  head -n1 | \
  sed 's/^[[:space:]]*"\([^"]*\)".*/\1/')

if [[ -z "$WP_ENV_NETWORK" ]]; then
  echo "❌ Could not determine wp-env Docker network." >&2
  exit 1
fi

echo "📡 wp-env network identified: $WP_ENV_NETWORK"

# STEP 4: Connect mailpit to the wp-env network
if docker network inspect "$WP_ENV_NETWORK" | grep -q "\"Name\": \"${MAILPIT_CONTAINER_NAME}\""; then
  echo "🔗 Mailpit already connected to $WP_ENV_NETWORK"
else
  echo "🔗 Connecting Mailpit to network: $WP_ENV_NETWORK"
  docker network connect "$WP_ENV_NETWORK" "$MAILPIT_CONTAINER_NAME"
fi

echo "✅ Mailpit is ready! Access it at: http://localhost:${MAILPIT_PORT_UI}"
