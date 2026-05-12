<?php

require_once __DIR__ . '/../backend/includes.php';

try {
    validateRequest(false);
    $user = idetifyUser(false);

    $profiles = Backend::getUserProfiles($user["user_id"]);
    sendJson(['ok' => true, 'profiles' => $profiles]);
}
catch(BackendException $e) {
    sendMessage($e->getMessage(), $e->getCode());
}
catch(Throwable $e) {
    require __DIR__ . '/../backend/logger.php';
    Logger::log($e->getMessage());
    sendMessage("internal server error", 500);
}
