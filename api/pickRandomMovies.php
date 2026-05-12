<?php

require_once __DIR__ . '/../backend/includes.php';

try {
    validateRequest();
    $profile = idetifyUser();

    $count = json_decode(file_get_contents("php://input"), true);
    if(!$count || !is_int($count)) {
        sendMessage('no movie id specified', 400);
        exit;
    }
    $movies = Backend::pickRandomMovies($count);
    sendJson(['ok' => true, 'movies' => $movies]);
}
catch(BackendException $e) {
    sendMessage($e->getMessage(), $e->getCode());
}
catch(Throwable $e) {
    require __DIR__ . '/../backend/logger.php';
    Logger::log($e->getMessage());
    sendMessage("internal server error", 500);
}
