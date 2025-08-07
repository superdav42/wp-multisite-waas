#!/bin/bash

# Define the directory containing PHP files
PHP_DIR="path/to/your/php/files"

# Define the Ollama API endpoint and prompt
OLLAMA_API_URL="http://127.0.0.1:11434/api/chat "
PROMPT="Fix any security problems in the following PHP code:"

# Loop through all PHP files in the directory
#for PHP_FILE in "$PHP_DIR"/*.php; do
PHP_FILE="/home/dave/wp-multisite-waas/views/admin-notices.php"
  # Read the contents of the PHP file
  PHP_CONTENT=$(cat "$PHP_FILE")

  # Make the API   call and store the response
  RESPONSE=$(curl -s -X POST "$OLLAMA_API_URL" \
    -H "Content-Type: application/json" \
    -d "{\"model\": \"codellama\", \"stream\":false, \"messages\":[{\"role\":\"user\",\"content\":\"$PROMPT $PHP_CONTENT\"}")

  # Extract the fixed code from the response
  FIXED_CODE=$(echo "$RESPONSE" | jq -r '.fixed_code')

  # Update the PHP file with the fixed code
  #echo "$FIXED_CODE" > "$PHP_FILE"

  echo "The file $PHP_FILE has been updated with the fixed code."
#done