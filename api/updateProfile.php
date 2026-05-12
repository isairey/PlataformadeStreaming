<?php

require_once __DIR__ . '/../backend/includes.php';

try {
    validateRequest();
    $user = idetifyUser(false);

    $profile = json_decode(file_get_contents("php://input"), true);

    if(!$profile || !is_array($profile)) {
        sendMessage("no profile data found", 400);
        exit;
    }

    Backend::updateProfile($user['user_id'], $profile);
    sendJson(["ok" => true]);
}
catch(BackendException $e) {
    sendMessage($e->getMessage(), $e->getCode());
}
catch(Throwable $e){
    require __DIR__ . '/../backend/logger.php';
    Logger::log($e->getMessage());
    sendMessage("internal server error", 500);
}
