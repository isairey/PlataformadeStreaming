<?php

require_once __DIR__ . '/../backend/auth.php';
require_once __DIR__ . '/../backend/includes.php';

try {
    validateRequest();
    $user= json_decode(file_get_contents("php://input"), true);

    if(!$user || !is_array($user)) {
        sendMessage("no login data found in the request body", 400);
        exit;
    }

    $stored_user = Backend::subscribe($user);
    $token = Auth::newToken($stored_user);
    sendJson(["ok" => true, "token" => $token, "user" => $stored_user]);
}
catch(BackendException $e) {
    sendMessage($e->getMessage(), $e->getCode());
}
catch(Throwable $e){
    require __DIR__ . '/../backend/logger.php';
    Logger::log($e->getMessage());
    sendMessage("internal server error", 500);
}
