<?php
$privateKeyFile = 'private_key.pem';
$publicKeyFile = 'public_key.pem';
$keyBits = 2048;

$config = array(
    "digest_alg" => "sha256",
    "private_key_bits" => $keyBits,
    "private_key_type" => OPENSSL_KEYTYPE_RSA,
);

$res = openssl_pkey_new($config);

openssl_pkey_export($res, $privateKey);

$publicKey = openssl_pkey_get_details($res);
$publicKey = $publicKey["key"];

file_put_contents($privateKeyFile, $privateKey);
file_put_contents($publicKeyFile, $publicKey);

echo "keys generated successfully";
?>

