<?php
/*
Plugin Name: SMTP Config for Mailpit
Description: Route WordPress emails through local Mailpit SMTP server in wp-env multisite environment
Version: 1.0
Author: Dev
*/

add_action('phpmailer_init', function($phpmailer) {
    error_log('📬 PHPMailer init hook triggered');

    $phpmailer->isSMTP();
    $phpmailer->Host       = '0.0.0.0';     // Mailpit SMTP host
    $phpmailer->Port       = 1025;          // Mailpit SMTP port
    $phpmailer->SMTPAuth   = false;         // No auth for Mailpit by default
    $phpmailer->SMTPSecure = false;         // No encryption for local SMTP
    $phpmailer->Username   = '';            // Leave empty
    $phpmailer->Password   = '';            // Leave empty

    // Optional: set the default From address and name for all outgoing emails
    $phpmailer->From       = 'dev@example.local';
    $phpmailer->FromName   = 'Dev Site';

    // Uncomment to enable SMTP debug output (helpful for troubleshooting)
    $phpmailer->SMTPDebug = 2;
});
