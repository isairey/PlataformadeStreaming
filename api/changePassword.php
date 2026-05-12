<?php

require_once __DIR__ . '/../backend/includes.php';

try {
    validateRequest();
    $user = idetifyUser(false);

    $password = json_decode(file_get_contents("php://input"), true);
    if(!$password || !is_string($password)) {
        sendMessage('no password specified', 400);
        exit;
    }

    Backend::changePassword($user['user_id'], $password);
    sendJson(['ok' => true]);
}
catch(BackendException $e) {
    sendMessage($e->getMessage(), $e->getCode());
}
catch(Throwable $e){
    require __DIR__ . '/../backend/logger.php';
    Logger::log($e->getMessage());
    sendMessage("internal server error", 500);
}
