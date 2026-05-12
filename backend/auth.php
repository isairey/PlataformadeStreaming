<?php

require_once __DIR__ . '/../vendor/autoload.php';

use Firebase\JWT\JWT;
use Firebase\JWT\Key;

class Auth {
    private static string $private_key_file = __DIR__ . '/keys/private_key.pem';
    private static string $public_key_file = __DIR__ . '/keys/public_key.pem';

    public static function newToken(array $user) : string {
        static $private_key = file_get_contents(self::$private_key_file);
        $payload = [
            'iat' => time(),
            'exp' => time() + 2592000,
            'user' => $user['user_id']
        ];

        if(isset($user['profile_id'])) {
            $payload['profile'] = $user['profile_id'];
        }

        return JWT::encode($payload, $private_key, 'RS256');
    }

    public static function validate(string $token) : array {
        static $pub_key = new Key(file_get_contents(self::$public_key_file), 'RS256');
        try {
            $payload = (array)JWT::decode($token, $pub_key);
            $user = ['user_id' => $payload['user']];

            if(isset($payload['profile'])) {
                $user['profile_id'] = $payload['profile'];
            }

            return $user;
        }
        catch(UnexpectedValueException | DomainException $e) {
            throw new BackendException('invalid authorization token', 401);
        }
    }
}
