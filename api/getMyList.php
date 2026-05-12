<?php

require_once __DIR__ . '/../backend/includes.php';

try {
    validateRequest(false);
    $profile = idetifyUser();

    $items = Backend::listItems($profile['profile_id']);
    fixThumbnailPaths($items);
    sendJson(['ok' => true, 'items' => $items]);
}
catch(BackendException $e) {
    sendMessage($e->getMessage(), $e->getCode());
}
catch(Throwable $e) {
    require __DIR__ . '/../backend/logger.php';
    Logger::log($e->getMessage());
    sendMessage("internal server error", 500);
}
