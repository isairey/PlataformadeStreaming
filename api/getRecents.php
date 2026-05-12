<?php

require_once __DIR__ . '/../backend/includes.php';

try {
    validateRequest(false);
    $profile = idetifyUser();

    $recent_movies = Backend::getRecents();
    fixThumbnailPaths($recent_movies);
    sendJson(['ok' => true, 'movies' => $recent_movies]);
}
catch(BackendException $e) {
    sendMessage($e->getMessage(), $e->getCode());
}
catch(Throwable $e){
    require __DIR__ . '/../backend/logger.php';
    Logger::log($e->getMessage());
    sendMessage("internal server error", 500);
}
