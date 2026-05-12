<?php

require_once __DIR__ . "/backend.php";

$movie_dir = "/nyetflix/backend/data/movies";
$thumbnails_dir = "/nyetflix/media/thumbnails";
$pictures_dir = "/nyetflix/media/pictures";

function idetifyUser(bool $check_profile = true) : array {
    require_once "../backend/auth.php";

    $headers = getallheaders();
    $header = $headers['Authorization'] ?? '';

    $token = str_replace('Bearer ', '', $header);
    $token = trim($token);

    if(!$token && isset($_COOKIE['auth_token'])) {
        $token = $_COOKIE['auth_token'];
    }

    $user = Auth::validate($token);
    $user['user'] = Backend::getUser($user['user_id']);

    if(isset($user['profile_id'])) {
        $user['profile'] = Backend::getProfile($user['profile_id']);
    }

    if($check_profile && !isset($user['profile'])) {
        throw new BackendException('profile is not set. create or set one before making this request', 400);
    }

    return $user;
}

function validateRequest(bool $validate_body = true) : void {
    if( $_SERVER["REQUEST_METHOD"] !== "POST") {
        throw new BackendException("request method not supported", 400);
    }

    if($validate_body && $_SERVER["CONTENT_TYPE"] !== "application/json") {
        throw new BackendException("expected json body", 400);
    }
}

function sendMessage(string $message, int $statusCode = 400, bool $ok = false) {
    sendJson(["ok" => $ok, "message" => $message], $statusCode);
}

function sendJson(mixed $value, int $statusCode = 200) : void {
    ob_clean();
    header("Content-Type: application/json");
    http_response_code($statusCode);
    echo json_encode($value);
}

function fixThumbnailPaths(array &$movies) {
    global $thumbnails_dir;
    foreach ($movies as &$movie) {
        $movie_id = $movie['movie_id'];
        $ext = $movie['ext'];
        $movie['thumbnail'] = "$thumbnails_dir/$movie_id.$ext";
        unset($movie['ext']);
    }
}
