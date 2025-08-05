#!/usr/bin/env bash
set -euo pipefail
IFS=$'\n\t'

SMTP_HOST="mailpit"

# Determine activation flag (activate vs activate-network)
echo "🔍 Detecting if WordPress is multisite..."
IS_MULTISITE=$(wp-env run cli wp eval 'echo is_multisite() ? "yes" : "no";')

if [[ "$IS_MULTISITE" == "yes" ]]; then
  ACTIVATE_FLAG="--activate-network"
  echo "🌐 Detected multisite. Using network activation."
else
  ACTIVATE_FLAG="--activate"
  echo "📦 Single-site detected. Using normal activation."
fi

# DEV environment
echo "🔌 Installing WP Mail SMTP plugin in DEV..."
wp-env run cli wp plugin install wp-mail-smtp "$ACTIVATE_FLAG"

echo "⚙️ Configuring SMTP in DEV..."
wp-env run cli wp option patch insert wp_mail_smtp mail "{\
  \"from_email\":\"noreply@example.com\",\
  \"from_name\":\"WordPress Test\",\
  \"mailer\":\"smtp\",\
  \"smtp\":{\
    \"host\":\"${SMTP_HOST}\",\
    \"port\":1025,\
    \"encryption\":\"none\",\
    \"auth\":false\
  }\
}"

# TEST environment
echo "🔌 Installing WP Mail SMTP plugin in TESTS..."
wp-env run tests-cli wp plugin install wp-mail-smtp "$ACTIVATE_FLAG"

echo "⚙️ Configuring SMTP in TESTS..."
wp-env run tests-cli wp option patch insert wp_mail_smtp mail "{\
  \"from_email\":\"noreply@example.com\",\
  \"from_name\":\"WordPress Test\",\
  \"mailer\":\"smtp\",\
  \"smtp\":{\
    \"host\":\"${SMTP_HOST}\",\
    \"port\":1025,\
    \"encryption\":\"none\",\
    \"auth\":false\
  }\
}"

echo "✅ WP Mail SMTP plugin installed and configured for both DEV and TEST environments."
