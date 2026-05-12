<?php

require_once __DIR__ . '/../backend/includes.php';

try {
    validateRequest();
    $profile = idetifyUser();
    $movie_id = json_decode(file_get_contents('php://input'), true);

    if(!$movie_id || !is_int($movie_id)) {
        sendMessage('movie id not specified', 400);
        exit;
    }
    $movie = Backend::getNextPlay($movie_id, $profile['profile_id']);
    sendJson(['ok' => true, 'movie' => $movie]);
}
catch(BackendException $e) {
    sendMessage($e->getMessage(), $e->getCode());
} catch (Throwable $e) {
    require __DIR__ . '/../backend/logger.php';
    Logger::log($e->__toString());
    sendMessage("internal server error", 500);
}
