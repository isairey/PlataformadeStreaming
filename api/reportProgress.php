<?php

require_once __DIR__ . '/../backend/includes.php';

try {
    validateRequest();
    $profile = idetifyUser();
    $report = json_decode(file_get_contents('php://input'), true);

    if(!isset($report['movie_id'], $report['position'])) {
        sendMessage('movie_id or current position not specified', 400);
        exit;
    }

    Backend::updateHistory($profile['profile_id'], $report['movie_id'], $report['position']);
    sendJson(['ok' => true]);
}
catch(BackendException $e) {
    sendMessage($e->getMessage(), $e->getCode());
} catch (Throwable $e) {
    require __DIR__ . '/../backend/logger.php';
    Logger::log($e->getMessage() . PHP_EOL);
    sendMessage("internal server error", 500);
}
