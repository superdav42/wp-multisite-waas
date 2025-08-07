<?php
// phpcs:disable
// encrypt-secrets.php
$filename = 'inc/class-addon-repository.php';
$key      = hash_file('sha256', $filename);

$client_id     = '4xYlZXujMatEwrZ6t2dz6O15vyKT7X28xb39ZUQW';
$client_secret = 'b1k4yI4TG00IUDNXXNrTg1ycu2kOvM1kJS3saKFh';

function encryptValue($plaintext, $key) {
    $iv = openssl_random_pseudo_bytes(openssl_cipher_iv_length('aes-256-cbc'));
    $encrypted = openssl_encrypt($plaintext, 'aes-256-cbc', $key, 0, $iv);
    return base64_encode($iv . $encrypted);
}

file_put_contents('inc/stuff.php', "<?php\nreturn ".var_export([
	encryptValue($client_id, $key),
	encryptValue($client_secret, $key),
], true).';');