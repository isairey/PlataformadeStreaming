<?php

require_once __DIR__ . '/../backend/includes.php';

try {
    validateRequest(false);
    $user = idetifyUser(false);

    Backend::deleteUser($user["user_id"]);
    sendJson(["ok" => true]);
}
catch(BackendException $e) {
    sendMessage($e->getMessage(), $e->getCode());
}
catch(Throwable $e){
    require __DIR__ . '/../backend/logger.php';
    Logger::log($e->__toString());
    sendMessage("internal server error", 500);
}
