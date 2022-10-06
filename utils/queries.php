<?php
require_once $_SERVER['DOCUMENT_ROOT'] . '/include/database.php';

/**
 * Retrieve this user's record
 * @param string $username the username to look up
 * @return false|mixed the record or false if the query fails
 */
function db_get_user(string $username) {
    global $DB;
    $reqUser = $DB->prepare('SELECT u.* FROM users u
        WHERE (u.username = ?)');
    $reqUser->execute([$username]);
    if ($reqUser->rowCount() != 1) return false;   // user not found / invalid
    return $reqUser->fetch();
}

/**
 * Retrieve or create Google OAuth user record
 * @param string $uid OAuth UID
 * @param string $name user's full name
 * @param string $email user's email
 * @return false|mixed the user's record or false on failure
 */
function db_get_user_google_oauth(string $uid, string $name, string $email) {
    global $DB;
    $reqUser = $DB->prepare("SELECT u.* FROM users u
        WHERE (u.oauth_provider = 'google')
          AND (u.oauth_uid = ?)");
    if (!$reqUser->execute([$uid]))
        return false;
    if ($reqUser->rowCount() > 0)   // UID already in the DB
        return $reqUser->fetch();
    // new user, not in the DB yet
    $newUser = $DB->prepare('INSERT INTO users (identifier, username, name, email,
                   oauth_provider, oauth_uid) VALUES (?, ?, ?, ?, ?, ?)');
    try {
        $username = $name . random_int(10, 50);
    } catch (Exception $e) {
        return false;
    }
    if (!$newUser->execute([uniqid(), $username, $name, $email, 'google', $uid]))
        return false;
    return db_get_user_google_oauth($uid, $name, $email);
}

/**
 * Create the user in the database and return their record
 * @param string $name the user's full name
 * @param string $username the user's username
 * @param string $email the user's email
 * @param string $password_hash hash of the user's password
 * @return false|mixed the user record or false if the operation fails
 */
function db_create_user(string $name, string $username, string $email, string $password_hash) {
    global $DB;
    $stmt = $DB->prepare('INSERT INTO users (name, username,
                   email, password, identifier) VALUES (?, ?, ?, ?, ?)');
    $success = $stmt->execute([$name, $username, $email, $password_hash, uniqid()]);
    if ($success) return db_get_user($username);
    return false;
}

class Album {
    public int $id;
    public string $name;
    public array $artists;

    public function __construct() {
        $this->artists = [];
    }

    public static function get_by_searching(string $query) {
        global $DB;
        $stmt = $DB->prepare('SELECT a.id_album,
                a.name
            FROM albums a
            LEFT JOIN albums_artists aa ON a.id_album = aa.id_album
            WHERE UPPER(a.name) LIKE ?');
        if (!$stmt->execute(['%' . strtoupper(trim($query)) . '%'])) return false;

        $albums = [];
        while ($a = $stmt->fetch()) {
            if (!array_key_exists($a['id_album'], $albums)) {
                $album = new Album();
                $album->id = $a['id_album'];
                $album->name = $a['name'];
                $albums[$album->id] = $album;
            }
            if (isset($a['id_artist'])) $albums[$a['id_album']]->artists[] = Artist::get($a['id_artist']);
        }
        return $albums;
    }

    public static function get(int $id) {
        global $DB;
        $stmt = $DB->prepare('SELECT a.id_album,
            a.name AS album_name,
            aa.id_artist AS id_artist
            FROM albums a
            LEFT JOIN albums_artists aa on a.id_album = aa.id_album
            WHERE a.id_album = ?');
        if (!$stmt->execute([$id])) return false;

        $album = new Album();
        while ($a = $stmt->fetch()) {
            $album->id = $a['id_album'];    // Do NOT ever forget to do this again!
            $album->name = $a['album_name'];
            if ($a['id_artist'] != null) $album->artists[] = Artist::get($a['id_artist']);
        }
        return $album;
    }

    public static function get_by_name(string $name) {
        global $DB;
        $stmt = $DB->prepare('SELECT a.name AS album_name,
            aa.id_artist AS id_artist
            FROM albums a
            LEFT JOIN albums_artists aa ON a.id_album = aa.id_album
            WHERE a.name = ?');
        if (!$stmt->execute([$name])) return false;

        $album = new Album();
        while ($a = $stmt->fetch()) {
            $album->name = $a['album_name'];
            $album->artists[] = Artist::get($a['id_artist']);
        }
        return $album;
    }

    public static function get_all() {
        global $DB;
        if ($stmt = $DB->query('SELECT a.id_album, a.name, art.id_artist
            FROM albums a
            LEFT JOIN albums_artists aa ON a.id_album = aa.id_album
            LEFT JOIN artists art ON art.id_artist = aa.id_artist
            ORDER BY a.name ASC')) {
            $albums = [];
            while ($a = $stmt->fetch()) {
                if (!array_key_exists($a['id_album'], $albums)) {
                    $album = new Album();
                    $album->id = $a['id_album'];
                    $album->name = $a['name'];
                    $albums[$album->id] = $album;
                }
                if ($a['id_artist'] != null) $albums[$a['id_album']]->artists[] = Artist::get($a['id_artist']);
            }
        }
        return $albums ?? false;
    }

    public function get_or_create() {   // This one's hideous!
        global $DB;
        $stmt = $DB->prepare('SELECT id_album FROM albums WHERE name = ?');
        if (!$stmt->execute([$this->name])) return false;
        if ($stmt->rowCount() < 1) {
            $stmt = $DB->prepare('INSERT INTO albums (name) VALUES (?)');
            if (!$stmt->execute([$this->name])) return false;
            $this->id = $DB->lastInsertId();
            for ($i = 0; $i < sizeof($this->artists); $i++) {
                $artistResult = $this->artists[$i]->get_or_create();
                if (!$artistResult) return false;
                $this->artists[$i] = $artistResult;
                $stmt = $DB->prepare('INSERT INTO albums_artists (id_album, id_artist) VALUES (?, ?)');
                if (!$stmt->execute([$this->id, $this->artists[$i]->id])) return false;
            }
            return $this->get_or_create();
        } else return Album::get($stmt->fetch()['id_album']);
    }

    public function insert() {
        global $DB;
        $stmt = $DB->prepare('INSERT INTO albums(name) VALUES (?)');
        if (!$stmt->execute([$this->name])) return false;
        $sql = 'INSERT IGNORE INTO artists (name) VALUES ' . str_repeat(' (?), ', sizeof($this->artists));
        $stmt = $DB->prepare(rtrim($sql, ', '));
        foreach ($this->artists as $a) $artistList[] = $a;
        if (!isset($artistList))    // empty artist list safeguard return false;
            if (!$stmt->execute($artistList)) return false;
        return Album::get($DB->lastInsertId());
    }

    public function trigger_dead_check(): bool {
        global $DB;
        $stmt = $DB->prepare('SELECT a.id_album FROM albums a
            INNER JOIN songs s ON s.id_album = a.id_album
            WHERE a.id_album = ?');
        if (!$stmt->execute([$this->id]) || $stmt->rowCount() > 0) return false;
        try {   // if we came so far the album is empty
            return self::delete();
        } catch (Exception $e) {
            return false;
        }
    }

    public function delete(): bool {
        global $DB;
        $stmt = $DB->prepare('DELETE FROM albums WHERE id_album = ?');
        if (!$stmt->execute([$this->id])) return false;
        foreach ($this->artists as $artist) $artist->trigger_dead_check();
        return true;
    }
}

class Genre extends stdClass {
    public int $id;
    public string $name;

    public function get_or_create() {
        global $DB;
        try {
            $stmt = $DB->prepare('INSERT INTO genres (name) VALUES (?)');
            $stmt->execute([$this->name]);
        } catch (Exception $ignored) {
        }
        $stmt = $DB->prepare('SELECT id_genre FROM genres WHERE name = ?');
        if (!$stmt->execute([$this->name]))
            return false;
        return Genre::get($stmt->fetch()['id_genre']);
    }

    public static function get(int $id) {
        global $DB;
        $stmt = $DB->prepare('SELECT * FROM genres WHERE id_genre = ?');
        if (!$stmt->execute([$id])) return false;
        $result = $stmt->fetch();
        $genre = new Genre();
        $genre->id = $result['id_genre'];
        $genre->name = $result['name'];
        return $genre;
    }
}

final class AlertType {
    public const ALERT_INFO = 'info';
    public const ALERT_WARNING = 'warning';
    public const ALERT_SEVERE_WARNING = 'severe_warning';
}

class User extends stdClass {
    public int $id;
    public string $identifier;
    public string $username;
    public string $email;
    public string $name;
    public string $bio;
    public string $date_registered;
    public bool $isAdmin;
    public string $profilePicUrl;
    public string $passwordHash;

    public static function get(int $id) {
        global $DB;
        $stmt = $DB->prepare('SELECT * FROM users WHERE id_user = ?');
        if (!$stmt->execute([$id])) return false;
        if ($stmt->rowCount() < 1) return false;
        $data = $stmt->fetch();
        $user = new User();
        $user->id = $data['id_user'];
        $user->identifier = $data['identifier'];
        $user->username = $data['username'];
        $user->email = $data['email'];
        $user->name = $data['name'];
        $user->bio = $data['bio'] ?: '';
        $user->date_registered = $data['date_registered'];
        $user->isAdmin = $data['is_admin'];
        if (isset($data['profile_pic_url'])) $user->profilePicUrl = $data['profile_pic_url'];
        $user->passwordHash = $data['password'] ?: '';
        return $user;
    }

    public static function get_all() {
        global $DB;
        $stmt = $DB->query('SELECT * FROM users');
        while ($data = $stmt->fetch()) {
            $user = new User();
            $user->id = $data['id_user'];
            $user->identifier = $data['identifier'];
            $user->username = $data['username'];
            $user->email = $data['email'];
            $user->name = $data['name'];
            $user->bio = $data['bio'] ?: '';
            $user->date_registered = $data['date_registered'];
            $user->isAdmin = $data['is_admin'];
            if (isset($data['profile_pic_url'])) $user->profilePicUrl = $data['profile_pic_url'];
            $user->passwordHash = $data['password'] ?: '';
            $users[] = $user;
        }
        return $users ?? false;
    }

    public function update(User $updatedData): bool {
        global $DB;
        $stmt = $DB->prepare('UPDATE users SET name = ?, username = ?, email = ?,
                 password = ?, is_admin = ? WHERE id_user = ?');
        return $stmt->execute([$updatedData->name, $updatedData->username, $updatedData->email, $updatedData->passwordHash, $updatedData->isAdmin, $this->id]);
    }

    public function update_profile_pic(string $newFilePath): bool {
        global $DB;
        $stmt = $DB->prepare('UPDATE users SET profile_pic_url = ? WHERE id_user = ?');
        return $stmt->execute([$newFilePath, $this->id]);
    }

    public function delete(): bool {
        global $DB;
        $postedSongs = Song::get_by_artist($this->id, true);
        foreach ($postedSongs as $song) {
            $postedComments = $song->get_comments_by_user($this->id);
            foreach ($postedComments as $comment) $song->delete_comment($comment['id_comment']);
            $song->delete();
        }
        $songSaves = Song::get_by_user_saves($this->id);
        foreach ($songSaves as $save) $save->unsave($save->id);
        $stmt = $DB->prepare('DELETE FROM artists WHERE id_user = ?');
        $stmt->execute([$this->id]);
        $stmt = $DB->prepare('DELETE FROM users WHERE id_user = ?');
        return $stmt->execute([$this->id]);
    }

    public function notify(string $message, string $type, int $adminUserId): bool {
        global $DB;
        $stmt = $DB->prepare('INSERT INTO users_notifications (id_user, id_admin_user,
                                 content, type) VALUES (?, ?, ?, ?)');
        return $stmt->execute([$this->id, $adminUserId, $message, $type]);
    }

    public static function dismiss_notification(int $notificationId): bool {
        global $DB;
        $stmt = $DB->prepare('DELETE FROM users_notifications WHERE id_notification = ?');
        return $stmt->execute([$notificationId]);
    }

    public static function check_notifications(int $userId, bool $autoExpire = true) {
        global $DB;
        $stmt = $DB->prepare('SELECT * FROM users_notifications WHERE id_user = ?');
        if (!$stmt->execute([$userId]))
            return false;
        $notifications = $stmt->fetchAll();
        if ($autoExpire) {
            $stmt = $DB->prepare('DELETE FROM users_notifications WHERE id_user = ?');
            $stmt->execute([$userId]);
        }
        return $notifications;
    }
}

class Artist extends stdClass {
    public int $id;
    public string $name;
    public User $user;

    public static function get_by_searching(string $query) {
        global $DB;
        $stmt = $DB->prepare('SELECT a.name, a.id_artist
            FROM artists a
            WHERE UPPER(a.name) LIKE ?');
        if (!$stmt->execute(['%' . strtoupper(trim($query)) . '%'])) return false;
        while ($a = $stmt->fetch()) {
            $artist = new Artist();
            $artist->id = $a['id_artist'];
            $artist->name = $a['name'];
            $artists[] = $artist;
        }
        return $artists ?? false;
    }

    public static function get_all() {
        global $DB;
        if ($stmt = $DB->query('SELECT * FROM artists ORDER BY name ASC')) {
            while ($a = $stmt->fetch()) {
                $artist = new Artist();
                $artist->id = $a['id_artist'];
                $artist->name = $a['name'];
                if (isset($a['id_user'])) $artist->user = User::get($a['id_user']);
                $artists[] = $artist;
            }
        }
        return $artists ?? false;
    }

    public function get_or_create() {
        global $DB;
        try {
            if (isset($this->user->id)) {
                $stmt = $DB->prepare('INSERT INTO artists (name, id_user) VALUES (?, ?)');
                $stmt->execute([$this->name, $this->user->id]);
            } else {
                $stmt = $DB->prepare('INSERT INTO artists (name) VALUES (?)');
                $stmt->execute([$this->name]);
            }
        } catch (Exception $ignored) {
        }
        $stmt = $DB->prepare('SELECT id_artist FROM artists WHERE name = ?');
        if (!$stmt->execute([$this->name]))
            return false;
        return Artist::get($stmt->fetch()['id_artist']);
    }

    public static function get(int $id) {
        global $DB;
        $stmt = $DB->prepare('SELECT * FROM artists WHERE id_artist = ?');
        if (!$stmt->execute([$id])) return false;
        $data = $stmt->fetch();
        $artist = new Artist();
        $artist->id = $data['id_artist'];
        $artist->name = $data['name'];
        if (isset($data['id_user'])) $artist->user = User::get($data['id_user']);
        return $artist;
    }

    public function trigger_dead_check(): bool {
        global $DB;
        $stmt = $DB->prepare('SELECT a.id_artist FROM artists a
            INNER JOIN songs_artists s ON s.id_artist = a.id_artist
            WHERE a.id_artist = ?');
        if (!$stmt->execute([$this->id]) || $stmt->rowCount() > 0) return false;
        // if we came so far the artist is not present in any song;
        // if the artist is still present in an album the delete will fail either way
        try {
            return self::delete();
        } catch (Exception $e) {
            return false;
        }
    }

    public function delete(): bool {
        global $DB;
        $stmt = $DB->prepare('DELETE FROM artists WHERE id_artist = ?');
        return $stmt->execute([$this->id]);
    }
}

class SongTag extends stdClass {
    public int $id;
    public string $name;

    public function get_or_create() {
        global $DB;
        $stmt = $DB->prepare('SELECT id_tag FROM tags WHERE name = ?');
        if (!$stmt->execute([$this->name])) return false;
        if ($stmt->rowCount() < 1) {
            $stmt = $DB->prepare('INSERT INTO tags (name) VALUES (?)');
            if (!$stmt->execute([$this->name])) return false;
            return $this->get_or_create();
        } else return SongTag::get($stmt->fetch()['id_tag']);
    }

    public static function get(int $id) {
        global $DB;
        $stmt = $DB->prepare('SELECT * FROM tags WHERE id_tag = ?');
        if (!$stmt->execute([$id])) return false;
        $result = $stmt->fetch();
        $tag = new SongTag();
        $tag->id = $result['id_tag'];
        $tag->name = $result['name'];
        return $tag;
    }
}

class Song extends stdClass {
    public int $id;
    public string $title;
    public array $artists;
    public Album $album;
    public Genre $genre;
    public array $tags;
    public string $file_url;

    public function __construct() {
        $this->artists = [];
        $this->tags = [];
    }

    public static function get_by_album(Album $album) {
        global $DB;
        $stmt = $DB->prepare('SELECT s.id_song,
                   s.name AS song_name,
                   s.song_url,
                   g.id_genre AS id_genre,
                   s.id_album AS id_album,
                   sa.id_artist,
                   g.name AS genre_name
            FROM songs s
            LEFT JOIN genres g ON s.id_genre = g.id_genre
            LEFT JOIN songs_artists sa ON s.id_song = sa.id_song
            LEFT JOIN artists a ON a.id_artist = sa.id_artist
            LEFT JOIN albums alb ON alb.id_album = s.id_album
            LEFT JOIN albums_artists aa ON aa.id_album = alb.id_album
            LEFT JOIN artists a2 ON a2.id_artist = aa.id_artist
            WHERE (s.id_album = ?)');
        if (!$stmt->execute([$album->id])) return false;
        return self::fetch_query_results($stmt);
    }

    /**
     * Processes this statement and extracts all results into an array
     * @param $statement mixed an open, already executed PDO statement
     * @return array the resulting data
     */
    private static function fetch_query_results($statement): array {
        $songs = [];
        while ($s = $statement->fetch()) {
            if (!array_key_exists($s['id_song'], $songs)) {
                $song = new Song();
                $song->id = $s['id_song'];
                $song->file_url = $s['song_url'];
                $song->title = $s['song_name'];
                if ($s['id_genre'] != null) $song->genre = Genre::get($s['id_genre']);
                if ($s['id_album'] != null) $song->album = Album::get($s['id_album']);
                $songs[$song->id] = $song;
            }

            $songs[$s['id_song']]->artists[] = Artist::get($s['id_artist']);
        }
        return $songs;
    }

    public static function get(int $id) {
        global $DB;
        $stmt = $DB->prepare('SELECT s.id_song,
                   s.name AS song_name,
                   s.song_url,
                   g.id_genre AS id_genre,
                   s.id_album,
                   sa.id_artist,
                   g.name AS genre_name
            FROM songs s
            LEFT JOIN genres g ON s.id_genre = g.id_genre
            LEFT JOIN songs_artists sa ON s.id_song = sa.id_song
            WHERE s.id_song = ?');
        if (!$stmt->execute([$id])) return false;

        $song = new Song();
        while ($s = $stmt->fetch()) {
            $song->id = $s['id_song'];
            $song->file_url = $s['song_url'];
            $song->title = $s['song_name'];
            if ($s['id_genre'] != null) $song->genre = Genre::get($s['id_genre']);
            if ($s['id_album'] != null) $song->album = Album::get($s['id_album']);
            if ($s['id_artist'] != null) $song->artists[] = Artist::get($s['id_artist']);
        }
        return $song;
    }

    public static function get_all() {
        global $DB;
        $stmt = $DB->query('SELECT s.id_song,
                   s.name AS song_name,
                   s.song_url,
                   g.id_genre AS id_genre,
                   s.id_album AS id_album,
                   sa.id_artist,
                   g.name AS genre_name
            FROM songs s
            LEFT JOIN genres g ON s.id_genre = g.id_genre
            LEFT JOIN songs_artists sa ON s.id_song = sa.id_song
            LEFT JOIN artists a ON a.id_artist = sa.id_artist
            LEFT JOIN albums alb ON alb.id_album = s.id_album
            LEFT JOIN albums_artists aa ON aa.id_album = alb.id_album
            LEFT JOIN artists a2 ON a2.id_artist = aa.id_artist');
        return self::fetch_query_results($stmt);
    }

    public static function get_by_artist(int $artistId, bool $queryUserId = false) {
        global $DB;
        if ($queryUserId) {
            $stmt = $DB->prepare('SELECT s.id_song,
                   s.name AS song_name,
                   s.song_url,
                   g.id_genre AS id_genre,
                   s.id_album AS id_album,
                   sa.id_artist,
                   g.name AS genre_name
            FROM songs s
            LEFT JOIN genres g ON s.id_genre = g.id_genre
            LEFT JOIN songs_artists sa ON s.id_song = sa.id_song
            LEFT JOIN artists a ON a.id_artist = sa.id_artist
            LEFT JOIN albums alb ON alb.id_album = s.id_album
            LEFT JOIN albums_artists aa ON aa.id_album = alb.id_album
            LEFT JOIN artists a2 ON a2.id_artist = aa.id_artist
            WHERE (a.id_user = ?) OR (a2.id_user = ?)');
        } else {
            $stmt = $DB->prepare('SELECT s.id_song,
                   s.name AS song_name,
                   s.song_url,
                   g.id_genre AS id_genre,
                   s.id_album AS id_album,
                   sa.id_artist,
                   g.name AS genre_name
            FROM songs s
            LEFT JOIN genres g ON s.id_genre = g.id_genre
            LEFT JOIN songs_artists sa ON s.id_song = sa.id_song
            LEFT JOIN albums alb ON alb.id_album = s.id_album
            LEFT JOIN albums_artists aa ON aa.id_album = alb.id_album
            WHERE (sa.id_artist = ?) OR (aa.id_artist = ?)');
        }
        if (!$stmt->execute([$artistId, $artistId])) return false;
        return self::fetch_query_results($stmt);
    }

    public static function get_by_user_saves(int $userId) {
        global $DB;
        $stmt = $DB->prepare('SELECT s.id_song,
                   s.name AS song_name,
                   s.song_url,
                   g.id_genre AS id_genre,
                   s.id_album AS id_album,
                   sa.id_artist,
                   g.name AS genre_name
            FROM songs s
            LEFT JOIN genres g ON s.id_genre = g.id_genre
            LEFT JOIN songs_artists sa ON s.id_song = sa.id_song
            LEFT JOIN songs_saves ss ON ss.id_song = s.id_song
            WHERE ss.id_user = ?');
        if (!$stmt->execute([$userId])) return false;

        return self::fetch_query_results($stmt);
    }

    public static function get_by_searching(string $query) {
        global $DB;
        $stmt = $DB->prepare('SELECT s.id_song,
                   s.name AS song_name,
                   s.song_url,
                   s.id_album,
                   g.id_genre,
                   sa.id_artist
            FROM songs s
            LEFT JOIN genres g ON s.id_genre = g.id_genre
            LEFT JOIN songs_artists sa ON s.id_song = sa.id_song
            WHERE UPPER(s.name) LIKE ?');
        if (!$stmt->execute(['%' . strtoupper(trim($query)) . '%'])) return false;
        return self::fetch_query_results($stmt);
    }

    public function insert() {
        global $DB;
        if (isset($this->genre)) {
            $genreResult = $this->genre->get_or_create();
            if (!$genreResult) return false;
            $this->genre = $genreResult;
        }
        for ($i = 0; $i < sizeof($this->artists); $i++) {
            $artistResult = $this->artists[$i]->get_or_create();
            if (!$artistResult) return false;
            $this->artists[$i] = $artistResult;
        }
        if (isset($this->album)) {
            $albumResult = $this->album->get_or_create();
            if (!$albumResult) return false;
            $this->album = $albumResult;
        }

        $stmt = $DB->prepare('INSERT INTO songs(name, id_album, id_genre, song_url) VALUES (?, ?, ?, ?)');
        if (!$stmt->execute([$this->title, $this->album->id, $this->genre->id, $this->file_url])) return false;

        $this->id = $DB->lastInsertId();
        foreach ($this->artists as $artist) {
            $stmt = $DB->prepare('INSERT INTO songs_artists (id_song, id_artist) VALUES (?, ?)');
            if (!$stmt->execute([$this->id, $artist->id])) return false;
        }
        foreach ($this->tags as $tag) {
            $tagResult = $tag->get_or_create();
            if ($tagResult) {
                $stmt = $DB->prepare('INSERT INTO songs_tags (id_song, id_tag) VALUES (?, ?)');
                $stmt->execute([$this->id, $tagResult->id]);
            }
        }
        return Song::get($this->id);
    }

    public static function get_by_title(string $title) {
        global $DB;
        $stmt = $DB->prepare('SELECT s.id_song,
                   s.name AS song_name,
                   s.id_album,
                   s.song_url,
                   g.id_genre AS id_genre,
                   sa.id_artist
            FROM songs s
            LEFT JOIN genres g ON s.id_genre = g.id_genre
            LEFT JOIN songs_artists sa ON s.id_song = sa.id_song
            WHERE s.name = ?');
        if (!$stmt->execute([$title])) return false;

        $song = new Song();
        while ($s = $stmt->fetch()) {
            $song->id = $s['id_song'];
            $song->file_url = $s['song_url'];
            $song->title = $s['song_name'];
            if ($s['id_genre'] != null) $song->genre = Genre::get($s['id_genre']);
            if ($s['id_album'] != null) $song->album = Album::get($s['id_album']);
            if ($s['id_artist'] != null) $song->artists[] = Artist::get($s['id_artist']);
        }
        return $song;
    }

    public function save(int $userId): bool {
        global $DB;
        $stmt = $DB->prepare('INSERT INTO songs_saves (id_song, id_user) VALUES (?, ?)');
        return $stmt->execute([$this->id, $userId]);
    }

    public function unsave(int $userId): bool {
        global $DB;
        $stmt = $DB->prepare('DELETE FROM songs_saves WHERE id_song = ? AND id_user = ?');
        return $stmt->execute([$this->id, $userId]);
    }

    public function check_is_saved(int $userId): bool {
        global $DB;
        $stmt = $DB->prepare('SELECT id_ss FROM songs_saves WHERE id_song = ? AND id_user = ?');
        if (!$stmt->execute([$this->id, $userId])) return false;
        return $stmt->rowCount() > 0;
    }

    public function delete(): bool {
        global $DB;
        $stmts[] = $DB->prepare('DELETE FROM songs_saves WHERE id_song = ?');
        $stmts[] = $DB->prepare('DELETE FROM comments_songs WHERE id_song = ?');
        $stmts[] = $DB->prepare('DELETE FROM songs_tags WHERE id_song = ?');
        $stmts[] = $DB->prepare('DELETE FROM songs_artists WHERE id_song = ?');
        foreach ($stmts as $stmt) $stmt->execute([$this->id]);
        $stmt = $DB->prepare('DELETE FROM songs WHERE id_song = ?');
        if (!$stmt->execute([$this->id])) return false;
        $this->album->trigger_dead_check();
        foreach ($this->artists as $artist) $artist->trigger_dead_check();
        return true;
    }

    public function check_ownership(int $userId): bool {
        if (!isset($this->artists[0]) || !isset($this->artists[0]->user)) return false;
        return $this->artists[0]->user->id === $userId;
    }

    public function get_comments() {
        global $DB;
        $stmt = $DB->prepare('SELECT c.*, u.name AS user_name,
                u.profile_pic_url
            FROM comments_songs c
            LEFT JOIN users u ON c.id_user = u.id_user
            WHERE c.id_song = ?
            ORDER BY c.date_time DESC');
        $success = $stmt->execute([$this->id]);
        if (!$success) return false;
        return $stmt->fetchAll();
    }

    public function get_comments_by_user(int $userId) {
        global $DB;
        $stmt = $DB->prepare('SELECT c.*, u.name AS user_name
            FROM comments_songs c
            LEFT JOIN users u ON c.id_user = u.id_user
            WHERE (c.id_song = ?)
              AND (c.id_user = ?)
            ORDER BY c.date_time DESC');
        $success = $stmt->execute([$this->id, $userId]);
        if (!$success) return false;
        return $stmt->fetchAll();
    }

    public function insert_comment(int $userId, string $content): bool {
        global $DB;
        $stmt = $DB->prepare('INSERT INTO comments_songs (id_song, id_user, content)
            VALUES (?, ?, ?)');
        return $stmt->execute([$this->id, $userId, $content]);
    }

    public static function delete_comment(int $commentId): bool {
        global $DB;
        $stmt = $DB->prepare('DELETE FROM comments_songs WHERE id_comment = ?');
        return $stmt->execute([$commentId]);
    }
}

function get_upload_stats_per_month(int $numMonths) {
    global $DB;
    $stmt = $DB->prepare('SELECT MONTH(date_added), COUNT(id_song) FROM songs
        WHERE (date_added >= DATE_SUB(now(), INTERVAL ? MONTH))
        GROUP BY MONTH(date_added)');
    if (!$stmt->execute([$numMonths]))
        return false;
    return $stmt->fetchAll();
}
