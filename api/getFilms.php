<?php

require_once __DIR__ . '/../backend/includes.php';

try {
    validateRequest(false);
    $profile = idetifyUser();

    $films = Backend::getFilmsSeries(1);
    fixThumbnailPaths($films);
    sendJson(['ok' => true, 'films' => $films]);
}
catch(BackendException $e) {
    sendMessage($e->getMessage(), $e->getCode());
}
catch(Throwable $e){
    require __DIR__ . '/../backend/logger.php';
    Logger::log($e->getMessage());
    sendMessage("internal server error", 500);
}
