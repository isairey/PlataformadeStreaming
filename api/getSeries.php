<?php

require_once __DIR__ . '/../backend/includes.php';

try {
    validateRequest(false);
    $profile = idetifyUser();

    $series = Backend::getFilmsSeries(2);
    fixThumbnailPaths($series);
    sendJson(['ok' => true, 'series' => $series]);
}
catch(BackendException $e) {
    sendMessage($e->getMessage(), $e->getCode());
}
catch(Throwable $e){
    require __DIR__ . '/../backend/logger.php';
    Logger::log($e->getMessage());
    sendMessage("internal server error", 500);
}

