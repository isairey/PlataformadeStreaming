<?php

require_once __DIR__ . '/../backend/helpers.php';

try {
    $user = idetifyUser(false);
    if(isset($user['profile'])) {
        header("Location: home.php");
    }
}
catch(Exception $e) {
    header("Location: signin.php");
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Netflix - Who's Watching?</title>
    <link rel="stylesheet" href="styles/profile.css">
    <link rel="icon" href="assets/images/Icon/Icon.ico" type="image/icon type">
</head>
<body>
    <div class="content-wrapper">
        <div class="profile-list">
            <h1 class="profile-gate-label">Who's watching?</h1>
            <div id="profile-list-container" class="profile-list-container">
                <!-- Profiles will be loaded here -->
            </div>
            <div class="profile-actions">
                <a href="account.php" class="manage-profiles-btn">
                    <i class="fa fa-cog"></i> Manage Profiles
                </a>
            </div>
        </div>
    </div>
    <script type="module" src="scripts/profile.js"></script>
</body>
</html>
