<?php

require_once __DIR__ . '/../backend/helpers.php';

try {
    $user = idetifyUser(false);
    if(!isset($user['profile'])) {
        header("Location: profile.php");
    }
}
catch(Exception $e) {
    header("Location: signin.php");
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Netflix</title>
    <link href="./assets/images/Icon/Icon.ico" rel="icon" type="image/x-icon" />
    <link href="styles/index.css" rel="stylesheet" type="text/css" />
    <link href="styles/main.css" rel="stylesheet" type="text/css" />
    <link href="styles/nav.css" rel="stylesheet" type="text/css" />
    <link href="styles/content-row.css" rel="stylesheet" type="text/css" />
    <link href="styles/top.css" rel="stylesheet" type="text/css" />
    <link href="styles/footer.css" rel="stylesheet" type="text/css" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css" />
    <link href="styles/home.css" rel="stylesheet" type="text/css" />
</head>
<body>
    <div id="loading-overlay" class="loading-overlay">
        <div class="loading-spinner"></div>
    </div>

    <div id="video-player" class="video-player">
        <button class="close-btn">&times;</button>
        <video id="video" controls></video>
        <div class="video-controls">
            <button class="play-pause">
                <i class="fa fa-play"></i>
            </button>
            <div class="progress-bar">
                <div class="progress"></div>
            </div>
            <div class="time">0:00 / 0:00</div>
            <button class="fullscreen">
                <i class="fa fa-expand"></i>
            </button>
        </div>
    </div>

    <header>
        <nav>
            <div class="left">
                <img src="assets/images/Icon/logo.png" class="brand" />
                <div class="nav-item small">Browse</div>
                <ul class="nav-items">
                    <a href="#" class="nav-items" data-content="home"><li class="nav-item">Home</li></a>
                    <a href="#" class="nav-items" data-content="series"><li class="nav-item">TV Shows</li></a>
                    <a href="#" class="nav-items" data-content="films"><li class="nav-item">Movies</li></a>
                    <a href="#" class="nav-items" data-content="latest"><li class="nav-item">Latest</li></a>
                    <a href="#" class="nav-items" data-content="mylist"><li class="nav-item">My List</li></a>
                    <a href="#" class="nav-items" data-content="languages"><li class="nav-item">Browse by Languages</li></a>
                </ul>
            </div>
            <div class="right">
                <ul class="nav-icons">
                    <div class="nav-item icon">
                        <i data-feather="search"></i>
                    </div>
                    <div class="nav-item icon">
                        <i data-feather="bell"></i>
                    </div>
                    <div class="nav-item icon">
                        <div class="profile-dropdown">
                            <div class="profile-icon">
                                <img src="assets/images/Profile/Profile1.png" alt="Profile">
                            </div>
                            <div class="dropdown-menu">
                                <div class="profiles-list">
                                    <!-- Profiles will be loaded here -->
                                </div>
                                <div class="dropdown-divider"></div>
                                <a href="account.php" class="dropdown-item">
                                    <i class="fa fa-cog"></i> Manage Profiles
                                </a>
                                <a href="#" class="dropdown-item" id="logout-btn">
                                    <i class="fa fa-sign-out"></i> Log out
                                </a>
                            </div>
                        </div>
                    </div>
                </ul>
            </div>
        </nav>
    </header>

    <main>
        <!-- Hero banner section -->
        <div id="hero-banner" class="top">
            <!-- Will be populated by JavaScript -->
        </div>

        <!-- Content rows will be added here by JavaScript -->
        <div id="content-rows"></div>
    </main>

    <footer class="column">
        <div class="social-icons row">
          <i class="fa fa-facebook social-icon"></i>
          <i class="fa fa-instagram social-icon"></i>
          <i class="fa fa-youtube social-icon"></i>
        </div>

        <div class="footer-links">
            <a href="#" class="footer-link">Audio Description</a>
            <a href="#" class="footer-link">Help Center</a>
            <a href="#" class="footer-link">Gift Cards</a>
            <a href="#" class="footer-link">Media Center</a>
            <a href="#" class="footer-link">Investor Relations</a>
            <a href="#" class="footer-link">Jobs</a>
            <a href="#" class="footer-link">Terms of use</a>
            <a href="#" class="footer-link">Privacy</a>
            <a href="#" class="footer-link">Legal Notices</a>
            <a href="#" class="footer-link">Cookie Preferences</a>
            <a href="#" class="footer-link">Coorperate Information</a>
            <a href="#" class="footer-link">Contact us</a>
        </div>

        <div class="footer-service">
            <button class="service-code">Service Code</button>
        </div>

            <div class="footer-copyright">
            &copy; 2025 Nyetflix, Inc.
            </div>
      </footer>


    <script src="https://unpkg.com/feather-icons"></script>
    <script src="scripts/hls.min.js"></script>
    <script type="module" src="scripts/home.js"></script>
</body>
</html>
