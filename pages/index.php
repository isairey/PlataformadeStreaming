<?php

require_once __DIR__ . '/../backend/helpers.php';

try {
    $user = idetifyUser(false);
    if(isset($user['profile'])) {
        header("Location: home.php");
    }
    else {
        header("Location: profile.php");
    }
}
catch(Exception $e) {
    header("Location: signin.php");
}
?>
