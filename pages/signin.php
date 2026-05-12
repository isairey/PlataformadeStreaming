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
    <title>Login Page</title>
    <link href="assets/images/Icon/Icon.ico" rel="icon" type="image/x-icon" />
    <link rel="stylesheet" href="styles/login.css">
</head>
<body>
    <nav>
        <a href="#"><img src="assets/images/Icon/logo.png" alt="logo"></a>
    </nav>
    <div class="form-wrapper">
        <h2>Sign In</h2>
        <form id="signin-form">
            <div class="form-control">
                <input id="email-input" type="text" required>
                <label>Email or phone number</label>
            </div>
            <div class="form-control">
                <input id="password-input" type="password" required>
                <label>Password</label>
            </div>
            <button type="submit">Sign In</button>
            <div class="form-help"> 
                <div class="remember-me">
                    <input type="checkbox" id="remember-me">
                    <label for="remember-me">Remember me</label>
                </div>
                <a href="#">Need help?</a>
            </div>
        </form>
        <p>New to Netflix? <a href="signup.php">Sign up now</a></p>
        <small>
            This page is protected by Google reCAPTCHA to ensure you're not a bot. 
            <a href="#">Learn more.</a>
        </small>
    </div>
    <script type="module" src="scripts/signin.js"></script>
</body>
</html>
