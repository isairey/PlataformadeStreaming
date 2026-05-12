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
catch(Exception $e) {}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Netflix - Watch TV Shows Online, Watch Movies Online</title>
    <link href="assets/images/Icon/Icon.ico" rel="icon" type="image/x-icon" />
    <link rel="stylesheet" href="styles/signup.css">
</head>
<body>
    <div class="dark-overlay">
        <header class="header">
            <img src="assets/images/Icon/logo.png" alt="Netflix" class="logo">
        </header>

        <div class="signup-container">
            <h1>Sign Up</h1>
            <form id="signup-form">
                <div class="form-group">
                    <input type="text" id="name-input" placeholder="Name" required>
                </div>
                <div class="form-group">
                    <input type="email" id="email-input" placeholder="Email" required>
                </div>
                <div class="form-group">
                    <input type="password" id="password-input" placeholder="Password" required>
                </div>
                <button type="submit">Sign Up</button>
            </form>
            <div class="help-text">
                <p>Already have an account? <a href="signin.php">Sign in now</a>.</p>
                <p style="margin-top: 10px;">
                    This page is protected by Google reCAPTCHA to ensure you're not a bot. 
                    <a href="#">Learn more</a>.
                </p>
            </div>
        </div>

        <div class="signup-footer">
            <div class="footer-links" style="padding: 20px 50px;">
                <div style="margin-top: 20px; display: grid; grid-template-columns: repeat(4, 1fr); gap: 12px;">
                    <a href="#">FAQ</a>
                    <a href="#">Help Center</a>
                    <a href="#">Terms of Use</a>
                    <a href="#">Privacy</a>
                    <a href="#">Cookie Preferences</a>
                    <a href="#">Corporate Information</a>
                    <p>Questions? Call 1-844-505-2993</p>
                </div>
            </div>
        </div>
    </div>
    <script type="module" src="scripts/signup.js"></script>
</body>
</html>
