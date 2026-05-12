<?php

require_once __DIR__ . '/../backend/includes.php';

try {
    validateRequest();
    $user = idetifyUser(false);

    $profile = json_decode(file_get_contents("php://input"), true);
    if(!$profile || !is_array($profile)) {
        sendMessage('invalid profile data: empty or incompelete data', 400);
        exit;
    }

    $profile['user_id'] = $user['user_id'];
    $profile = Backend::addProfile($profile);
    sendJson(['ok' => true, 'profile' => $profile]);
}
catch(BackendException $e) {
    sendMessage($e->getMessage(), $e->getCode());
}
catch(Throwable $e){
    require __DIR__ . '/../backend/logger.php';
    Logger::log($e->getMessage());
    sendMessage("internal server error", 500);
}
