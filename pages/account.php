<?php

require_once __DIR__ . '/../backend/helpers.php';

try {
    $user = idetifyUser(false);
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
    <title>Account Settings - Netflix</title>
    <link href="assets/images/Icon/Icon.ico" rel="icon" type="image/x-icon">
    <link href="styles/index.css" rel="stylesheet" type="text/css">
    <link href="styles/main.css" rel="stylesheet" type="text/css">
    <link href="styles/nav.css" rel="stylesheet" type="text/css">
    <link href="styles/account.css" rel="stylesheet" type="text/css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
</head>
<body>
    <header>
        <nav>
            <div class="left">
                <a href="home.php">
                    <img src="assets/images/Icon/logo.png" class="brand" alt="Netflix">
                </a>
            </div>
        </nav>
    </header>

    <main class="account-container">
        <h1>Account Settings</h1>
        
        <!-- Profiles Section -->
        <section class="profiles-section active">
            <h2>Profiles</h2>
            <div class="profiles-grid" id="profiles-grid">
                <!-- Profiles will be loaded here -->
            </div>
            <button class="add-profile-btn" id="add-profile-btn">
                <i class="fa fa-plus"></i> Add Profile
            </button>
        </section>

        <!-- Account Section -->
        <section class="account-section">
            <h2>Account</h2>
            <div class="account-actions">
                <button class="delete-account-btn" id="delete-account-btn">
                    <i class="fa fa-trash"></i> Delete Account
                </button>
            </div>
        </section>

        <!-- Add Profile Modal -->
        <div class="modal" id="add-profile-modal">
            <div class="modal-content">
                <h3>Add Profile</h3>
                <form id="add-profile-form">
                    <input type="text" name="name" placeholder="Profile Name" required>
                    <div class="modal-actions">
                        <button type="button" class="cancel-btn">Cancel</button>
                        <button type="submit" class="submit-btn">Add Profile</button>
                    </div>
                </form>
            </div>
        </div>

        <!-- Edit Profile Modal -->
        <div class="modal" id="edit-profile-modal">
            <div class="modal-content">
                <h3>Edit Profile</h3>
                <form id="edit-profile-form">
                    <input type="hidden" name="profile_id">
                    <input type="text" name="name" placeholder="Profile Name" required>
                    <div class="modal-actions">
                        <button type="button" class="cancel-btn">Cancel</button>
                        <button type="submit" class="submit-btn">Save Changes</button>
                    </div>
                </form>
            </div>
        </div>

        <!-- Delete Confirmation Modal -->
        <div class="modal" id="delete-confirmation-modal">
            <div class="modal-content">
                <h3>Confirm Deletion</h3>
                <p>Are you sure you want to delete this profile? This action cannot be undone.</p>
                <div class="modal-actions">
                    <button type="button" class="cancel-btn">Cancel</button>
                    <button type="button" class="delete-btn">Delete</button>
                </div>
            </div>
        </div>
    </main>

    <script type="module" src="scripts/account.js"></script>
</body>
</html>

