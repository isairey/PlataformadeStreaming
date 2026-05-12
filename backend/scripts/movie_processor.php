<?php

if ($argc < 4) {
    die("Usage: php hls_processor.php <input_directory> <output_directory> [thumbnail_dir]\n");
}

$inputDir = rtrim($argv[1], '/');
$outputDir = rtrim($argv[2], '/');
$optional_dir = $argv[3] ? rtrim($argv[3], '/') : null;

new DirectoryProcessor()->process($inputDir, $outputDir, $optional_dir);

class DirectoryProcessor {
    private PDO $db;
    private $videoExtensions = ['mp4', 'mov', 'mkv', 'avi', 'webm', 'flv'];
    private $thumbnailExtensions = ['jpg', 'jpeg', 'png', 'gif'];

    private string $thumbnail_dir = '';
    private string $movie_dir = '';

    public function __construct() {
       $this->db = new PDO('mysql:host=localhost;dbname=nyetflix', 'nyetflix', 'nyetflix');
       $this->db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    }


    private function beginTransaction() : bool {
        try {
            $this->db->beginTransaction();
            return true;
        }
        catch(PDOException $e) {
            echo 'Failed to begin transaction in the database: ' . $e->getMessage() . PHP_EOL;
            return false;
        }
    }

    private function rollback() : void {
        try {
            $this->db->rollBack();
        }
        catch(PDOException $e) {
            echo 'Failed to rollBack the database state: ' . $e->getMessage() . PHP_EOL;
        }
    }

    private function commit() : void {
        try {
            $this->db->commit();
        }
        catch(PDOException $e) {
            echo 'Failed to commit the database state: ' . $e->getMessage() . PHP_EOL;
        }
    }

    private function setSavePoint(string $savepoint) : void {
        try {
            $this->db->exec("SAVEPOINT $savepoint");
        }
        catch(PDOException $e) {
            echo 'Failed to set savepoint. Database may end up in incosistent state: ' . $e->getMessage .PHP_EOL;
        }
    }


    private function releaseSavepoint(string $savepoint) : void {
        try {
            $this->db->exec("RELEASE SAVEPOINT $savepoint");
        }
        catch(PDOException $e) {
            echo 'Failed to set savepoint. Database may end up in incosistent state: ' . $e->getMessage .PHP_EOL;
        }
    }

    private function rollBackToSavepoint(string $savepoint) : void {
        try {
            $this->db->exec("ROLLBACK TO SAVEPOINT $savepoint");
        }
        catch(PDOException $e) {
            echo 'Failed to rollBack savepoint. Database may end up in incosistent state: ' . $e->getMessage .PHP_EOL;
        }
    }

    public function process(string $input_dir, string $output_dir, ?string $thumbnail_out_dir = null) : bool {
        if (!is_dir($input_dir)) {
            echo "Error: Input directory does not exist\n";
            return false;
        }

        if (!is_dir($output_dir)) {
            echo "Error: Output directory does not exist\n";
            return false;
        }

        $movie_dir= $thumbnail_out_dir ? $output_dir : $output_dir . '/movies';
        $thumbnail_dir = $thumbnail_out_dir ?? $output_dir . '/thumbnails';

        if(!$thumbnail_out_dir && !file_exists($movie_dir) && !@mkdir($movie_dir)) {
            echo 'Failed to create output directory\n';
            return false;
        }

        if(!$thumbnail_out_dir && !file_exists($thumbnail_dir) && !@mkdir($thumbnail_dir)) {
            echo 'Failed to create output directory\n';
            return false;
        }

        $this->movie_dir = $movie_dir;
        $this->thumbnail_dir = $thumbnail_dir;

        if(!$this->beginTransaction()) {
            return false;
        }

        $success = false;
        $dirs = scandir($input_dir);
        foreach ($dirs as $dir) {
            if ($dir === '.' || $dir === '..') {
                continue;
            }

            $path = $input_dir . '/' . $dir;
            if (!is_dir($path)) {
                continue;
            }

            $files = $this->getInterestingFiles($path);
            $this->setSavePoint("movie");

            $local_success = false;
            if (isset($files['video']) && isset($files['thumbnail'])) {
                $local_success = $this->processMovie($path, false) || $success;
            }
            else if (isset($files['thumbnail']) && !empty($files['dirs'])) {
                $local_success = $this->processSeries($path) || $success;
            }

            $local_success ? $this->releaseSavepoint("movie") : $this->rollBackToSavepoint("movie");
            $success = $success || $local_success;
        }

        if(!$success) {
            echo 'No valid movie or series found in input dir\n';
            $this->cleanupDirectory($thumbnail_dir);
            $this->cleanupDirectory($output_dir);
            $this->rollback();
        }
        else {
            $this->commit();
        }

        return $success;
    }

    private function processSeries(string $input_path) : bool {
        echo "Processing series: $input_path\n";

        $title = basename($input_path);
        $description = "Default description";

        $thumbnail = "$input_path" . '/' . $this->getInterestingFiles($input_path)['thumbnail'];
        $ext = strtolower(pathinfo($thumbnail, PATHINFO_EXTENSION));

        try {
            $stmnt = $this->db->prepare("INSERT INTO movies (title, description, ext, type) values (?, ?, ?, 2)");
            $stmnt->execute([$title, $description, $ext]);
            $movie_id = $this->db->lastInsertId();
        } catch (PDOException $e) {
            return $this->handleError("Database error: " . $e->getMessage());
        }

        $dirs = scandir($input_path);
        $season_no = 1;
        foreach ($dirs as $dir) {
            if ($dir === '.' || $dir === '..') {
                continue;
            }

            $path = $input_path . '/' . $dir;
            if (!is_dir($path)) {
                continue;
            }

            $success = $this->processSeason($movie_id, $path, $season_no);
            if(!$success) return false;
            ++$season_no;
        }


        $thumbnail_out = "$this->thumbnail_dir/$movie_id.$ext";
        if (!copy($thumbnail, $thumbnail_out)) {
            return $this->handleError("Failed to copy thumbnail", $input_path);
        }

        return true;
    }

    private function processSeason(int $movie_id, string $input_path, int $season_no) : bool {
        echo "Processing season: $input_path\n";

        $season_no = preg_replace('/[^0-9]/', '', basename($input_path));
        if (empty($season_no)) {
            echo "Could not determine season number from folder name: " . basename($input_path) . "\n";
            $season_no = $this->getInput("Enter season number");
            if (!is_numeric($season_no)) {
                return $this->handleError("Invalid season number");
            }
        }

        try {
            $stmnt = $this->db->prepare('INSERT INTO seasons (movie_id, season_no) values (?, ?)');
            $stmnt->execute([$movie_id, $season_no]);
            $season_id = $this->db->lastInsertId();
        } catch (PDOException $e) {
            return $this->handleError("Database error: " . $e->getMessage());
        }

        $dirs = scandir($input_path);
        $episode_no = 1;
        foreach ($dirs as $dir) {
            if ($dir === '.' || $dir === '..') {
                continue;
            }

            $path = $input_path . '/' . $dir;
            if (!is_dir($path)) {
                continue;
            }

            $success = $this->processEpisode($season_id, $path, $episode_no);
            if(!$success) return false;
            ++$episode_no;
        }

        return true;
    }

    private function processEpisode(int $season_id, string $input_path, int $episode_no) : bool {
        echo "Processing episode: $input_path\n";

        if (empty($episode_no)) {
            echo "Could not determine episode number from folder name: " . basename($input_path) . "\n";
            $episode_no = $this->getInput("Enter episode number");
            if (!is_numeric($episode_no)) {
                return $this->handleError("Invalid episode number");
            }
        }

        $movie_id = $this->processMovie($input_path, true);
        if(!$movie_id) return false;

        try {
            $stmnt = $this->db->prepare('INSERT INTO episodes (season_id, episode_no, movie_id) values (?, ?, ?)');
            $stmnt->execute([$season_id, $episode_no, $movie_id]);
            return true;
        } catch (PDOException $e) {
            return $this->handleError("Database error: " . $e->getMessage());
        }
    }

    private function processMovie(string $input_path, bool $episode) : int|false {
        echo "Processing movie: $input_path\n";

        $title = basename($input_path);
        $description = "Default movie description";

        $thumbnail = "$input_path" . '/' . $this->getInterestingFiles($input_path)['thumbnail'];
        $ext = strtolower(pathinfo($thumbnail, PATHINFO_EXTENSION));

        try {
            $stmnt = $this->db->prepare('INSERT INTO movies (title, description, ext, type) values (?, ?, ?, ?)');
            $stmnt->execute([$title, $description, $ext, $episode ? 3 : 1]);
            $movie_id = $this->db->lastInsertId();
        } catch (PDOException $e) {
            return $this->handleError("Database error: " . $e->getMessage());
        }

        $out_path = $this->movie_dir . '/' . $movie_id;
        if (!is_dir($out_path) && !@mkdir($out_path, 0755, true)) {
            $error = error_get_last();
            echo "Current working directory: " . getcwd() . "\n";
            echo "Directory permissions: " . substr(sprintf('%o', fileperms(dirname($out_path))), -4) . "\n";
            return $this->handleError("Failed to create movie directory: " . ($error['message'] ?? 'Unknown error'));
        }

        $success = $this->processVideo($input_path . '/' . $this->getInterestingFiles($input_path)['video'], $out_path);
        if(!$success) {
            $this->cleanupDirectory($out_path);
            return false;
        }

        $thumbnail_out = "$this->thumbnail_dir/$movie_id.$ext";
        if (!@copy($thumbnail, $thumbnail_out)) {
            $error = error_get_last();
            $this->cleanupDirectory($out_path);
            return $this->handleError("Failed to copy thumbnail: " . ($error['message'] ?? 'Unknown error'));
        }

        return $movie_id;
    }

    private function processVideo(string $videoPath, string $outputDir) : bool {
        $ffmpegPath = getenv('FFMPEG_PATH') ?: 'ffmpeg';

        if (!file_exists($videoPath)) {
            return $this->handleError("Video file not found: $videoPath");
        }

        $cmd = sprintf(
            '%s -i "%s" ' .
            '-c:v libx264 -crf 23 -preset veryfast -profile:v main -level 3.1 -vf "scale=-2:720" ' .
            '-c:a aac -b:a 128k ' .
            '-g 60 -keyint_min 60 -sc_threshold 0 ' .
            '-f hls -hls_time 6 -hls_list_size 0 -hls_playlist_type vod ' .
            '-hls_segment_filename "%s/%%d.ts" "%s/playlist.m3u8" 2>&1',
            $ffmpegPath,
            $videoPath,
            $outputDir,
            $outputDir
        );

        exec($cmd, $output, $returnCode);

        if ($returnCode !== 0) {
            return $this->handleError("FFmpeg error: " . implode("\n", $output));
        }

        if (!file_exists("$outputDir/playlist.m3u8")) {
            return $this->handleError("Failed to create HLS playlist");
        }

        return true;
    }

    private function getInterestingFiles(string $path) : array {
        $items = scandir($path);
        $output = ['dirs' => [], 'video' => null, 'thumbnail' => null];

        foreach($items as $item) {
            $full_path = $path . '/' . $item;
            if($item === '.' || $item === '..') continue;
            if(is_dir($full_path)) $output['dirs'][] = $item;
            if(is_null($output['video']) || is_null($output['thumbnail'])) {
                // process files as well
                $ext = strtolower(pathinfo($full_path, PATHINFO_EXTENSION));
                if(in_array($ext, $this->videoExtensions)) {
                    $output['video'] = $item;
                }
                else if(in_array($ext, $this->thumbnailExtensions)) {
                    $output['thumbnail'] = $item;
                }
            }
        }

        return $output;
    }

    private function cleanupDirectory(string $path) : void {
        if (is_dir($path)) {
            $files = array_diff(scandir($path), ['.', '..']);
            foreach ($files as $file) {
                $filePath = $path . '/' . $file;
                if (is_dir($filePath)) {
                    $this->cleanupDirectory($filePath);
                } else {
                    unlink($filePath);
                }
            }
            rmdir($path);
        }
    }

    private function handleError(string $message, ?string $cleanupPath = null) : bool {
        echo "Error: $message\n";
        if ($cleanupPath) {
            $this->cleanupDirectory($cleanupPath);
        }
        return false;
    }

    private function getInput(string $prompt) : string {
        echo $prompt . ": ";
        return trim(fgets(STDIN));
    }
}

