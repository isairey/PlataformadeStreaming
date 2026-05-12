<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Home - Netflix</title>
    <link href="./assets/images/Icon/Icon.ico" rel="icon" type="image/x-icon" />
    <link href="./styles/index.css" rel="stylesheet" type="text/css" />
    <link href="./styles/main.css" rel="stylesheet" type="text/css" />
    <link href="./styles/nav.css" rel="stylesheet" type="text/css" />
    <link href="./styles/content-row.css" rel="stylesheet" type="text/css" />
    <link href="./styles/top.css" rel="stylesheet" type="text/css" />
    <link href="./styles/footer.css" rel="stylesheet" type="text/css" />

    <link
      rel="stylesheet"
      href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css"
    />
  </head>
    <body>
      <header>
        <nav>
          <div class="left">
            <img src="./assets/images/Icon/logo.png" class="brand" />
            <div class="nav-item small">Browse</div>
            <ul class="nav-items">
              <a href="home.php" class = "nav-items"><li class="nav-item">Home</li></a>
              <a href="#" class = "nav-items"><li class="nav-item">TV Shows</li></a>
              <a href="#" class = "nav-items"><li class="nav-item">Movies</li></a>
              <a href="#" class = "nav-items"><li class="nav-item">Latest</li></a>
              <a href="#" class = "nav-items"><li class="nav-item">My List</li></a>
              <a href="#" class = "nav-items"><li class="nav-item">Browse by Languages</li></a>
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
                <!-- src is handled by javascript -->
                <img
                alt="profile icon"
                />
                <i class="hide" data-feather="chevron-down"></i>
              </div>
            </ul>
          </div>
        </nav>
      </header>
      <div class="genre">
        <select name="genre-type" id="play-btn" class="genre-type">
          <option value="">--Select Genre--</option>
          <option value="Action">Action</option>
          <option value="Comedy">Comedy</option>
          <option value="Drama">Drama</option>
          <option value="Horror">Horror</option>
          <option value="Thriller">Thriller</option>
          <option value="Fantasy">Fantasy</option>
        </select>
      </div>
      <main>
        <div class="content-row column">
          <div class="title">Get In on the Action</div>
          <div class="slider">
            <div class="content">
              <img class = "item"
              src="./assets/images/Main/Body/Row1/Prison-Break.jpg"
              />
            </div>
            <div class="content">
              <img class = "item"
                src="./assets/images/Main/Body/Row1/TOP-BOY.jpg"
              />
            </div>
            <div class="content">
              <img class = "item"
                src="./assets/images/Main/Body/Row1/Money-Heist.jpg"
              />
            </div>
            <div class="content">
              <img class = "item"
              src="./assets/images/Main/Body/Row1/Night-Agent.jpg"
              />
            </div>
            <div class="content">
              <img class = "item"
              src="./assets/images/Main/Body/Row1/The Rookie.jpg"
              />
            </div>
            <div class="content">
              <img class = "item"
              src="./assets/images/Main/Body/Row1/Vikings.jpg"
              />
            </div>
            <div class="content">
              <img class = "item"
              src="./assets/images/Main/Body/Row1/SUPACELL.jpg"
              />
            </div>
            <div class="content">
              <img class = "item"
              src="./assets/images/Main/Body/Row1/The-Union.jpg"
              />
            </div>
          </div>
        </div>

        <div class="content-row column">
          <div class="title">New on Netflix</div>
          <div class="slider">
            <div class="content">
              <img class = "item"
                src="./assets/images/Main/Body/Row2/Squid-Game.jpg"
              />
            </div>
            <div class="content">
              <img class = "item"
              src="./assets/images/Main/Body/Row2/Sakamoto-Days.jpg"
              />
            </div>
            <div class="content">
              <img class = "item"
              src="./assets/images/Main/Body/Row2/Outer Banks.jpg"
              />
            </div>
            <div class="content">
              <img class = "item"
              src="./assets/images/Main/Body/Row2/Bad-Guys.jpg"
              />
            </div>
            <div class="content">
              <img class = "item"
              src="./assets/images/Main/Body/Row2/Carry On.jpg"
              />
            </div>
            <div class="content">
              <img class = "item"
              src="./assets/images/Main/Body/Row2/Ticket to Paradise.jpg"
              />
            </div>
            <div class="content">
              <img class = "item"
              src="./assets/images/Main/Body/Row2/Ad Vitam.jpg"
              />
            </div>
            <div class="content">
              <img class = "item"
              src="./assets/images/Main/Body/Row2/Back in Action.jpg"
              />
            </div>
          </div>
        </div>
        <div class="content-row column">
          <div class="title">Golden Globe Award-winning TV Comedies</div>
          <div class="slider">
            <div class="content">
              <img class = "item"
                src="./assets/images/Main/Body/Row5/Baby Reindeer.jpg"
              />
            </div>
            <div class="content">
              <img class = "item"
              src="./assets/images/Main/Body/Row5/Beef.jpg"
              />
            </div>
            <div class="content">
              <img class = "item"
              src="./assets/images/Main/Body/Row5/The Kominsky Method.jpg"
              />
            </div>
            <div class="content">
              <img class = "item"
              src="./assets/images/Main/Body/Row5/Seinfield.jpg"
              />
            </div>
            <div class="content">
              <img class = "item"
              src="./assets/images/Main/Body/Row5/Friends.jpg"
              />
            </div>
            <div class="content">
              <img class = "item"
              src="./assets/images/Main/Body/Row5/Monk.jpg"
              />
            </div>
            <div class="content">
              <img class = "item"
              src="./assets/images/Main/Body/Row5/Mastor of None.jpg"
              />
            </div>
          </div>
        </div>
        <div class="content-row column">
          <div class="title">Epic Worlds</div>
          <div class="slider">
            <div class="content">
              <img class = "item"
                src="./assets/images/Main/Body/Row6/Love and Monsters.jpg"
              />
            </div>
            <div class="content">
              <img class = "item"
              src="./assets/images/Main/Body/Row6/MIB international.jpg"
              />
            </div>
            <div class="content">
              <img class = "item"
              src="./assets/images/Main/Body/Row6/One Piece.jpg"
              />
            </div>
            <div class="content">
              <img class = "item"
              src="./assets/images/Main/Body/Row6/Dungeons and Dragons.jpg"
              />
            </div>
            <div class="content">
              <img class = "item"
              src="./assets/images/Main/Body/Row6/The last Air-Bender.jpg"
              />
            </div>
            <div class="content">
              <img class = "item"
              src="./assets/images/Main/Body/Row6/Divergent.jpg"
              />
            </div>
            <div class="content">
              <img class = "item"
              src="./assets/images/Main/Body/Row6/The Witcher.jpg"
              />
            </div>
            <div class="content">
              <img class = "item"
              src="./assets/images/Main/Body/Row6/3 Body Problems.jpg"
              />
            </div>
          </div>
        </div>
        <div class="content-row column">
          <div class="title">Top Searches</div>
          <div class="slider">
            <div class="content">
              <img class = "item"
                src="./assets/images/Main/Body/Row7/Anyone but you.jpg"
              />
            </div>
            <div class="content">
              <img class = "item"
              src="./assets/images/Main/Body/Row7/White Chicks.jpg"
              />
            </div>
            <div class="content">
              <img class = "item"
              src="./assets/images/Main/Body/Row7/Hotel transylvania.jpg"
              />
            </div>
            <div class="content">
              <img class = "item"
              src="./assets/images/Main/Body/Row7/The six triple Eight.jpg"
              />
            </div>
            <div class="content">
              <img class = "item"
              src="./assets/images/Main/Body/Row7/Knight and Day.jpg"
              />
            </div>
            <div class="content">
              <img class = "item"
              src="./assets/images/Main/Body/Row7/Little.jpg"
              />
            </div>
            <div class="content">
              <img class = "item"
              src="./assets/images/Main/Body/Row7/LiarLiar.jpg"
              />
            </div>
            <div class="content">
              <img class = "item"
              src="./assets/images/Main/Body/Row7/Lift.jpg"
              />
            </div>
          </div>
        </div>

        <div class="content-row column">
          <div class="title">Your Next Watch</div>
          <div class="slider">
            <div class="content">
              <img class = "item"
                src="./assets/images/Main/Body/Row3/Gifted.jpg"
              />
            </div>
            <div class="content">
              <img class = "item"
              src="./assets/images/Main/Body/Row3/Narcos.jpg"
              />
            </div>
            <div class="content">
              <img class = "item"
              src="./assets/images/Main/Body/Row3/Bodjack Horseman.jpg"
              />
            </div>
            <div class="content">
              <img class = "item"
              src="./assets/images/Main/Body/Row3/Instant Family.webp"
              />
            </div>
            <div class="content">
              <img class = "item"
              src="./assets/images/Main/Body/Row3/Spiderman.jpg"
              />
            </div>
            <div class="content">
              <img class = "item"
              src="./assets/images/Main/Body/Row3/Stranger-Things.jpg"
              />
            </div>
            <div class="content">
              <img class = "item"
              src="./assets/images/Main/Body/Row3/The Social Network.jpg"
              />
            </div>
            <div class="content">
              <img class = "item"
              src="./assets/images/Main/Body/Row3/Space Force.jpg"
              />
            </div>
            <div class="content">
              <img class = "item"
              src="./assets/images/Main/Body/Row3/Sherlock.jpg"
              />
            </div>
          </div>
        </div>
        <div class="content-row column">
          <div class="title">Sci-Fi & Fantasy Movies</div>
          <div class="slider">
            <div class="content">
              <img class = "item"
                src="./assets/images/Main/Body/Row4/Bright.jpg"
              />
            </div>
            <div class="content">
              <img class = "item"
              src="./assets/images/Main/Body/Row4/The School for Good and Evil.jpg"
              />
            </div>
            <div class="content">
              <img class = "item"
              src="./assets/images/Main/Body/Row4/Jurassic-World.jpg"
              />
            </div>
            <div class="content">
              <img class = "item"
              src="./assets/images/Main/Body/Row4/BumbleBee.jpg"
              />
            </div>
            <div class="content">
              <img class = "item"
              src="./assets/images/Main/Body/Row4/The day after Tomorrow.jpg"
              />
            </div>
            <div class="content">
              <img class = "item"
              src="./assets/images/Main/Body/Row4/Predator.jpg"
              />
            </div>
            <div class="content">
              <img class = "item"
              src="./assets/images/Main/Body/Row4/Transformers.jpg"
              />
            </div>
            <div class="content">
              <img class = "item"
              src="./assets/images/Main/Body/Row4/Don't Look UP.jpg"
              />
            </div>

          </div>
        </div>
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
            &copy; 2025 Netflix, Inc.
            </div>
      </footer>

      <script src="./scripts/script.js"></script>
      <script src="https://unpkg.com/feather-icons"></script>
      <script>
        feather.replace();
      </script>
    </body>
</html>
