<?php

require_once __DIR__ . '/../backend/includes.php';

try {
    validateRequest();
    $profile = idetifyUser();

    $request = json_decode(file_get_contents('php://input'), true);

    if (!isset($request['movie_id'], $request['request_type'])) {
        throw new BackendException('movie id is not specified in the request', 400);
    }

    // Sanitize video ID to prevent directory traversal
    $movie_id = preg_replace('/[^a-zA-Z0-9_-]/', '', $request["movie_id"]);
    $movie_path = __DIR__ . "/../backend/data/movies/$movie_id";

    if ($request['request_type'] === 'manifest') {
        servePlaylist($movie_path);
        Backend::insertHistory($profile['profile_id'], $movie_id);
    } elseif ($request['request_type'] === 'segment') {
        if (!isset($request['segment_info']['sn'], $request['segment_info']['start'])) {
            throw new BackendException('missing segment information', 400);
        }
        serveSegment($movie_path, $request['segment_info']);
    } else {
        throw new BackendException('invalid request type', 400);
    }
}
catch(BackendException $e) {
    sendMessage($e->getMessage(), $e->getCode());
} catch (Throwable $e) {
    require __DIR__ . '/../backend/logger.php';
    Logger::log($e->getMessage() . PHP_EOL);
    sendMessage("internal server error", 500);
}

function servePlaylist($movie_path) {
    $playlist = "$movie_path/playlist.m3u8";
    if (!file_exists($playlist)) {
        throw new BackendException('Playlist not found', 404);
    }

    header('Content-Type: application/vnd.apple.mpegurl');
    readfile($playlist);
}

function serveSegment($movie_path, $segmentInfo) {
    $segmentNum = $segmentInfo['sn'];
    $segmentFile = "$movie_path/$segmentNum.ts";

    if (!file_exists($segmentFile)) {
        throw new BackendException('video segment not found', 404);
    }
    header('Content-Type: video/MP2T');
    readfile($segmentFile);
}
