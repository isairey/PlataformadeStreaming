<?php

class BackendException extends Exception {}

class Backend {
    private static $db_host = "localhost";
    private static $db_name = "nyetflix";
    private static $db_username = "nyetflix";
    private static $db_password = "nyetflix";

    private static function connection() : PDO {
        $db = new PDO("mysql:host=" . self::$db_host . ";dbname=" . self::$db_name, self::$db_username, self::$db_password);
        $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $db->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
        return $db;
    }

    private static function getEpisodeInfo(int $movie_id, PDO $db) : array {
        $stmnt = $db->prepare("SELECT seasons.movie_id AS series_id, seasons.season_id, seasons.season_no " . 
            " FROM episodes JOIN seasons ON episodes.season_id = seasons.season_id WHERE episodes.movie_id = ?");
        $stmnt->execute([$movie_id]);
        return $stmnt->fetch();
    }

    private static function getNextEpisode(int $movie_id, PDO $db) : array|false {
        $query = "
            SELECT
                COALESCE(next_ep.movie_id, first_next_season_ep.movie_id) AS episode_id,
                COALESCE(next_ep.episode_no, first_next_season_ep.episode_no) AS episode_no,
                COALESCE(next_season.season_no, next_series_season.season_no) AS season_no,
                current_season.movie_id AS series_id
            FROM episodes current_ep
            JOIN seasons current_season ON current_ep.season_id = current_season.season_id
            LEFT JOIN episodes next_ep ON current_ep.season_id = next_ep.season_id
                AND next_ep.episode_no = current_ep.episode_no + 1
            LEFT JOIN seasons next_season ON next_ep.season_id = next_season.season_id
            LEFT JOIN seasons next_series_season ON current_season.movie_id = next_series_season.movie_id
                AND current_season.season_no + 1 = next_series_season.season_no
            LEFT JOIN episodes first_next_season_ep ON next_series_season.season_id = first_next_season_ep.season_id
                AND first_next_season_ep.episode_no = 1
            WHERE current_ep.movie_id = ?
            LIMIT 1
        ";
        $stmnt = $db->prepare($query);
        $stmnt->execute([$movie_id]);
        return $stmnt->fetch();
    }

    private static function getMovieType(int $movie_id, PDO $db) : int {
        $stmnt = $db->prepare("SELECT type FROM movies WHERE movie_id = ?");
        $stmnt->execute([$movie_id]);
        return $stmnt->fetch()['type'] ?? 0;
    }

    private static function getMovie(int $movie_id, PDO $db) : array|null {
        $stmnt = $db->prepare("SELECT movie_id, title, description, type, ext FROM movies WHERE movie_id = ?");
        $stmnt->execute([$movie_id]);
        return $stmnt->fetch();
    }

    private static function getRandomMovies(int $count, PDO $db) : array {
        $query = "
            SELECT m.movie_id, m.type, m.ext
            FROM movies m
            JOIN (
                SELECT movie_id
                FROM movies
                WHERE type IN (1, 2)
                ORDER BY RAND()
                LIMIT :count
            ) AS random_movies ON m.movie_id = random_movies.movie_id
        ";
        $stmnt = $db->prepare($query);
        $stmnt->bindValue(':count', $count, PDO::PARAM_INT);
        $stmnt->execute();
        return $stmnt->fetchAll();
    }

    public static function pickRandomMovies(int $count) : array {
        if($count < 1) {
            throw new BackendException("invalid count given", 400);
        }

        $db = self::connection();
        return self::getRandomMovies($count, $db);
    }

    public static function getMovieDetail(int $movie_id) : array {
        $db = self::connection();
        $movie = self::getMovie($movie_id, $db);

        if(!$movie) {
            throw new BackendException("movie not found", 404);
        }
        return $movie;
    }

    /**
     * @param array $user: new user information. Must contain 'email' and password
     * @return array: user information after addition of user_id to it
     */
    public static function subscribe(array $user) : array {
        if(!isset($user["email"], $user["password"], $user['name'])) {
            throw new BackendException("missing password, email or name field", 400);
        }

        if(!is_string($user['email']) || !is_string($user['password']) || !is_string($user['name'])) {
            throw new BackendException('invalid user data types', 400);
        }

        if(strlen($user['password']) < 4) {
            throw new BackendException('password too short', 400);
        }

        try {
            $db = self::connection();
            $password_hash = password_hash($user['password'], PASSWORD_DEFAULT);
            $stmnt = $db->prepare('INSERT INTO users (email, password, name) VALUES (?, ?, ?)');
            $stmnt->execute([$user['email'], $password_hash, $user['name']]);
            unset($user['password']);

            // get user_id
            $stmnt = $db->prepare("SELECT LAST_INSERT_ID() AS id");
            $stmnt->execute();
            $user["user_id"] = $stmnt->fetch()["id"];


            $stmnt = $db->prepare("INSERT INTO profiles (user_id, name) VALUES (?, ?)");
            $stmnt->execute([$user['user_id'], $user['name']]);

            return $user;
        }
        catch(PDOException $e) {
            if($e->errorInfo[1] === 1062) {
                throw new BackendException("email already registered with another user", 400);
            }
            else {
                throw $e;
            }
        }
    }

    public static function getUser(int $user_id) : array {
        $db = self::connection();
        $user = self::getUserData($user_id, $db);
        if(!$user) {
            throw new BackendException("user not found", 404);
        }

        return (array)$user;
    }

    /**
     * @param array $user: new user information. Must contain 'email' and password
     */
    public static function authenticate(array $user) : array {
        if(!isset($user['email'], $user['password'])) {
            throw new BackendException("invalid user data", 400);
        }

        $password_hash = password_hash($user["password"], PASSWORD_DEFAULT);

        $db = self::connection();
        $stmnt = $db->prepare("SELECT user_id, name, email, password FROM users WHERE email = ?");
        $stmnt->execute([$user["email"]]);
        $stored_user = $stmnt->fetch();

        if(!$stored_user || !password_verify($user['password'], $stored_user['password'])) {
            throw new BackendException("incorrect email or password", 400);
        }

        unset($stored_user['password']);
        return $stored_user;
    }

    /**
     * Check whether associated with a user_id is valid
     * @param int $user_id: user id to be checked
     * @return bool: true if user exists, false otherwise
     */
    private static function getUserData(int $user_id, PDO $db) : array|bool {
        $db = self::connection();
        $stmnt = $db->prepare("SELECT user_id, name, email FROM users WHERE user_id = ?");
        $stmnt->execute([$user_id]);
        return $stmnt->fetch();
    }

    public static function addProfile(array $profile) : array {
        if(!isset($profile['name'], $profile['user_id']) || strlen($profile['name']) === 0) {
            throw new BackendException('invalid profile data. required fields missing or empty', 400);
        }

        try {
            $db = self::connection();
            $stmnt = $db->prepare("INSERT INTO profiles (user_id, name, picture) VALUES (?, ?, ?)");
            $stmnt->execute([$profile['user_id'], $profile['name'], $profile['picture'] ?? null]);

            $profile['profile_id'] = $db->lastInsertId();
            return $profile;
        }
        catch(PDOException $e) {
            if($e->errorInfo[1] === 1062) {
                throw new BackendException("profile already exists", 400);
            }
            else {
                throw $e;
            }
        }
    }

    public static function getProfile(int $profile_id) : array {
        $db = self::connection();
        $stmnt = $db->prepare("SELECT user_id, profile_id, name, picture FROM profiles WHERE profile_id = ?");
        $stmnt->execute([$profile_id]);
        $profile = $stmnt->fetch();
        if(!$profile) {
            throw new BackendException('profile not found', 404);
        }

        return $profile;
    }

    public static function getUserProfiles(int $user_id) : array {
        $db = self::connection();
        $stmnt = $db->prepare("SELECT user_id, profile_id, name, picture FROM profiles WHERE user_id = ?");
        $stmnt->execute([$user_id]);
        return $stmnt->fetchAll();
    }

    /**
     *  Update user information.
     *
     * @param array $profile data to replace existing user information
     * @param int $profile_id whose information is to be updated
     */
    public static function updateProfile(int $user_id, array $profile) : void {
        $updates = [];
        $values = [];

        $db = self::connection();

        if(isset($profile['name'])) {
            $updates[] = ' name = ? ';
            $values[] = $profile['name'];
        }

        if(isset($profile['picture'])) {
            $updates[] = ' picture = ? ';
            $values[] = $profile['picture'];
        }

        if(empty($updates) || !isset($profile['profile_id'])) {
            throw new BackendException("required fields missing or update is empty", 400);
        }

        $values[] = $profile['profile_id'];
        $values[] = $user_id;

        $stmnt = $db->prepare( "UPDATE profiles SET " . implode(", ", $updates) . " WHERE profile_id = ? AND user_id = ?");
        $stmnt->execute($values);

        if(0 === $stmnt->rowCount()) {
            throw new BackendException('profile not found', 404);
        }
    }

    public static function changePassword(int $user_id, string $password) : void {
        if(strlen($password) < 4) {
            throw new BackendException('password too short', 400);
        }
        $password_hash = password_hash($password, PASSWORD_DEFAULT);
        $db = self::connection();
        $stmnt = $db->prepare('UPDATE users SET password = ? WHERE user_id = ?');
        $stmnt->execute([$password_hash, $user_id]);
    }

    public static function deleteUser(int $user_id) : void {
        $db = self::connection();
        $stmnt = $db->prepare("DELETE FROM users WHERE user_id = ?");
        $stmnt->execute([$user_id]);
    }

    public static function deleteProfile(int $user_id, int $profile_id) : void {
        $db = self::connection();
        $stmnt = $db->prepare("DELETE FROM profiles WHERE user_id = ? AND profile_id = ?");
        $stmnt->execute([$user_id, $profile_id]);
        if(0 === $stmnt->rowCount()) {
            throw new BackendException('profile not found', 404);
        }
    }

    public static function listItems(int $profile_id) : array {
        $db = self::connection();
        $stmnt = $db->prepare("SELECT movies.movie_id, type, ext FROM lists JOIN "
            . " movies ON lists.movie_id = movies.movie_id WHERE profile_id = ?");
        $stmnt->execute([$profile_id]);
        return $stmnt->fetchAll();
    }

    public static function addToList(int $profile_id, int $movie_id) : void {
        $db = self::connection();
        $stmnt = $db->prepare("INSERT INTO lists (profile_id, movie_id) VALUES (?, ?)");
        $stmnt->execute([$profile_id, $movie_id]);
    }

    public static function removeFromList(int $profile_id, int $movie_id) : void {
        $db = self::connection();
        $stmnt = $db->prepare("DELETE FROM lists WHERE profile_id = ? AND movie_id = ?");
        $stmnt->execute([$profile_id, $movie_id]);
        if(0 === $stmnt->rowCount()) {
            throw new BackendException('movie not found in the list', 404);
        }
    }

    public static function insertHistory(int $profile_id, int $movie_id) : void {
        $db = self::connection();
        $type = self::getMovieType($movie_id, $db);
        if($type === 3) {
            // this is episode
            $series = self::getEpisodeInfo($movie_id, $db);
            $stmnt = $db->prepare("INSERT INTO history (profile_id, movie_id, series_id) VALUES (?, ?, ?) " .
                " ON DUPLICATE KEY UPDATE position = 0, movie_id = ?");
            $stmnt->execute([$profile_id, $movie_id, $series['series_id'], $movie_id]);
        }
        else {
            // this is a film
            $stmnt = $db->prepare("INSERT INTO history (profile_id, movie_id) VALUES (?, ?) " .
                " ON DUPLICATE KEY UPDATE position = 0");
            $stmnt->execute([$profile_id, $movie_id]);
        }
    }

    public static function updateHistory(int $profile_id, int $movie_id, float $position) : void {
        // user_id, movie_id, position
        $db = self::connection();
        $stmnt = $db->prepare("UPDATE history SET position = ? WHERE profile_id = ? AND movie_id = ?");
        $stmnt->execute([$position, $profile_id, $movie_id]);
    }


    /**
     *   Fist clearns current movie history and return chosen next movie info
     */
    public static function getNextPlay(int $movie_id, int $profile_id) : array {
        $db = self::connection();

        $next_episode = self::getNextEpisode($movie_id, $db);
        if($next_episode['episode_id']) {
            $stmnt = $db->prepare("UPDATE history SET movie_id = ?, position = 0 WHERE profile_id = ? AND movie_id = ?");
            $stmnt->execute([$next_episode['episode_id'], $profile_id, $movie_id]);
            return self::getMovie($next_episode['episode_id'], $db);
        }
        else {
            $stmnt = $db->prepare("DELETE FROM history WHERE profile_id = ? AND movie_id = ?");
            $stmnt->execute([$profile_id, $movie_id]);
            $movies = self::getRandomMovies(2, $db);
            $next_movie = $movies[0]['movie_id'] === $movie_id ? $movies[1] : $movies[0];
            return self::getMovie($next_movie['movie_id'], $db);
        }
    }

    public static function getFilmsSeries(int $type) : array {
        $db = self::connection();
        $stmnt = $db->prepare("SELECT movie_id, type, ext FROM movies WHERE type = ?");
        $stmnt->execute([$type]);
        return $stmnt->fetchAll();
    }

    public static function getSeasonByNumber(int $movie_id, int $season_no) : array {
        $db = self::connection();
        $full_seasons = [];
        $stmnt = $db->prepare("SELECT season_id FROM seasons WHERE movie_id = ? AND season_no = ?");
        $stmnt->execute([$movie_id, $season_no]);
        $season = $stmnt->fetch();

        if(!$season) {
            throw new BackendException('season not found', 404);
        }

        return self::getSeasonById($season['season_id']);
    }

    public static function getSeasonById(int $season_id) : array {
        $db = self::connection();
        $stmnt = $db->prepare("SELECT episode_no, movies.movie_id, title, description, type, ext " .
            " FROM episodes JOIN movies ON episodes.movie_id = movies.movie_id WHERE season_id = ?");
        $stmnt->execute([$season_id]);
        return $stmnt->fetchAll();
    }

    public static function getGenreMovies(string $genre) : array {
        $db = self::connection();
        $stmnt = $db->prepare("SELECT movies.movie_id, type, ext FROM movies JOIN movie_genres " .
            " ON movies.movie_id = movie_genres.movie_id WHERE genre = ?");
        $stmnt->execute([$genre]);
        return $stmnt->fetchAll();
    }

    public static function getHistory(int $profile_id) : array {
        $db = self::connection();
        $stmnt = $db->prepare("SELECT movies.movie_id, type, ext FROM movies JOIN history " .
                " ON movies.movie_id = history.movie_id WHERE profile_id = ?");
        $stmnt->execute([$profile_id]);
        return $stmnt->fetchAll();
    }

    public static function getRecents() : array {
        $db = self::connection();
        $stmnt = $db->prepare("SELECT movie_id, type, ext FROM movies WHERE type IN (1, 2)" . 
            "ORDER BY added DESC LIMIT 100");
        $stmnt->execute([]);
        return $stmnt->fetchAll();
    }

    public static function getPlayPosition(int $movie_id, int $profile_id) : array {
        $db = self::connection();
        $type = self::getMovieType($movie_id, $db);
        if($type === 0) {
            throw new BackendException("movie not found", 404);
        }

        $playPosition = ['movie_id' => $movie_id, 'position' => -1];
        if($type !== 2) {
            $stmnt = $db->prepare("SELECT position FROM history WHERE profile_id = ? AND movie_id = ?");
            $stmnt->execute([$profile_id, $movie_id]);
            $history = $stmnt->fetch();
            $playPosition['position'] = $history['position'] ?? -1;
        }
        else {
            $stmnt = $db->prepare("SELECT movie_id, position FROM history WHERE profile_id = ? AND series_id = ?");
            $stmnt->execute([$profile_id, $movie_id]);
            $history = $stmnt->fetch();
            if($history) {
                $playPosition['movie_id'] = $history['movie_id'];
                $playPosition['position'] = $history['position'];
            }
            else {
                $stmnt = $db->prepare("SELECT episodes.movie_id FROM seasons JOIN episodes ON seasons.season_id = episodes.season_id " .
                    " WHERE episode_no = 1 AND season_no = 1 AND seasons.movie_id = ?");
                $stmnt->execute([$movie_id]);
                $episode = $stmnt->fetch();
                $playPosition['movie_id'] = $episode['movie_id'];
            }
        }
        return $playPosition;
    }
}
