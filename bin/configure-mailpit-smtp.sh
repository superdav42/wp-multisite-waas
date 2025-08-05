#!/usr/bin/env bash
set -euo pipefail
IFS=$'\n\t'

SMTP_HOST="mailpit"

echo "🔌 Installing WP Mail SMTP plugin in DEV..."
wp-env run cli wp plugin install wp-mail-smtp --activate

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

echo "🔌 Installing WP Mail SMTP plugin in TESTS..."
wp-env run tests-cli wp plugin install wp-mail-smtp --activate

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

echo "✅ WP Mail SMTP plugin configured for both DEV and TEST environments."
