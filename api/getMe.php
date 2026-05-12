<?php

require_once __DIR__ . '/../backend/includes.php';

try {
    validateRequest(false);
    $data = idetifyUser(false);
    if(isset($data['profile_id'])) {
        $data['profile'] = Backend::getProfile($data['profile_id']);
    }
    sendJson(['ok' => true, 'data' => $data]);
}
catch(BackendException $e) {
    sendMessage($e->getMessage(), $e->getCode());
}
catch(Throwable $e){
    require __DIR__ . '/../backend/logger.php';
    Logger::log($e->getMessage());
    sendMessage("internal server error", 500);
}

