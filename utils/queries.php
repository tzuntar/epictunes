***REMOVED***
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
    if ($reqUser->rowCount() != 1)
        return false;   // user not found / invalid
    return $reqUser->fetch();
}

/**
 * Create the user in the database and return their record
 * @param string $name the user's full name
 * @param string $username the user's username
 * @param string $email the user's email
 * @param string $password_hash hash of the user's password
 * @return false|mixed the user record or false if the operation fails
 */
function db_create_user(string $name, string $username, string $email,
                        string $password_hash) {
    global $DB;
    $stmt = $DB->prepare('INSERT INTO users (name, username,
                   email, password, identifier) VALUES (?, ?, ?, ?, ?)');
    $success = $stmt->execute([$name, $username, $email, $password_hash, uniqid()]);
    if ($success)
        return db_get_user($username);
    return false;
}

/**
 * Returns ID of the last inserted object in the database,
 * regardless of the table
 * @return int the ID
 */
function db_last_insert_id(): int {
    global $DB;
    $id = $DB->query('SELECT LAST_INSERT_ID()');
    return $id->fetch()[0];
}

class Album {
    public int $id;
    public string $name;
    public array $artists;

    public static function get_by_name(string $name) {
        global $DB;
        $stmt = $DB->prepare('SELECT a.name AS album_name,
            aa.id_artist AS id_artist
            FROM albums a
            INNER JOIN albums_artists aa on a.id_album = aa.id_album
            WHERE a.name = ?');
        if (!$stmt->execute([$name]))
            return false;

        $album = new Album();
        while ($a = $stmt->fetch()) {
            $album->name = $a['album_name'];
            $album->artists[] = Artist::get($a['id_artist']);
    ***REMOVED***
        return $album;
***REMOVED***

    public static function get(int $id) {
        global $DB;
        $stmt = $DB->prepare('SELECT a.name AS album_name,
            aa.id_artist AS id_artist
            FROM albums a
            INNER JOIN albums_artists aa on a.id_album = aa.id_album
            WHERE a.id_album = ?');
        if (!$stmt->execute([$id]))
            return false;

        $album = new Album();
        while ($a = $stmt->fetch()) {
            $album->name = $a['album_name'];
            $album->artists[] = Artist::get($a['id_artist']);
    ***REMOVED***
        return $album;
***REMOVED***

    public function get_or_create() {
        global $DB;
        $stmt = $DB->prepare('SELECT id_album FROM albums WHERE name = ?');
        if (!$stmt->execute([$this->name]))
            return false;
        if ($stmt->rowCount() < 1) {
            $stmt = $DB->prepare('INSERT INTO albums (name) VALUES (?)');
            if (!$stmt->execute([$this->name]))
                return false;
            $newAlbumId = db_last_insert_id();
            foreach ($this->artists as $artist) {
                $stmt = $DB->prepare('INSERT INTO albums_artists (id_album, id_artist) VALUES (?, ?)');
                if (!$stmt->execute([$newAlbumId, $artist->id]))
                    return false;
        ***REMOVED***
            return $this->get_or_create();
    ***REMOVED***
        return Album::get($stmt->fetch()['id_album']);
***REMOVED***

    public function insert() {
        global $DB;
        $stmt = $DB->prepare('INSERT INTO albums(name) VALUES (?)');
        if (!$stmt->execute([$this->name]))
            return false;
        $sql = 'INSERT IGNORE INTO artists (name) VALUES '
            . str_repeat(' (?), ', sizeof($this->artists));
        $stmt = $DB->prepare(rtrim($sql, ', '));
        foreach ($this->artists as $a)
            $artistList[] = $a;
        if (!isset($artistList))    // empty artist list safeguard
            return false;
        if (!$stmt->execute($artistList))
            return false;
        return Album::get(db_last_insert_id());
***REMOVED***
}

class Genre extends stdClass {
    public int $id;
    public string $name;

    public function get_or_create() {
        global $DB;
        $stmt = $DB->prepare('SELECT id_genre FROM genres WHERE name = ?');
        if (!$stmt->execute([$this->name]))
            return false;
        if ($stmt->rowCount() < 1) {
            $stmt = $DB->prepare('INSERT INTO genres (name) VALUES (?)');
            if (!$stmt->execute([$this->name]))
                return false;
            return $this->get_or_create();
    ***REMOVED***
        return Genre::get($stmt->fetch()['id_genre']);
***REMOVED***

    public static function get(int $id) {
        global $DB;
        $stmt = $DB->prepare('SELECT * FROM genre WHERE id_genre = ?');
        if (!$stmt->execute([$id]))
            return false;
        $result = $stmt->fetch();
        $genre = new Genre();
        $genre->id = $result['id_genre'];
        $genre->name = $result['name'];
        return $genre;
***REMOVED***
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
        if (!$stmt->execute([$id]))
            return false;
        $data = $stmt->fetch();
        $user = new User();
        $user->id = $data['id_user'];
        $user->identifier = $data['identifier'];
        $user->username = $data['username'];
        $user->name = $data['name'];
        $user->bio = $data['bio'];
        $user->date_registered = $data['date_registered'];
        $user->isAdmin = $data['is_admin'];
        $user->profilePicUrl = $data['profile_pic_url'];
        $user->passwordHash = $data['password'];
        return $user;
***REMOVED***
}

class Artist extends stdClass {
    public int $id;
    public string $name;
    public User $user;

    public function get_or_create() {
        global $DB;
        if (isset($this->user)) {
            $stmt = $DB->prepare('SELECT id_artist FROM artists 
                 WHERE name = ? AND id_user = ?');
            if (!$stmt->execute([$this->name, $this->user->id]))
                return false;
    ***REMOVED*** else {
            $stmt = $DB->prepare('SELECT id_artist FROM artists WHERE name = ?');
            if (!$stmt->execute([$this->name]))
                return false;
    ***REMOVED***
        if ($stmt->rowCount() < 1) {
            if (isset($this->user)) {
                $stmt = $DB->prepare('INSERT INTO artists (name, id_user) VALUES (?, ?)');
                if ($stmt->execute([$this->name, $this->user->id]))
                    return false;
        ***REMOVED*** else {
                $stmt = $DB->prepare('INSERT INTO artists (name) VALUES (?)');
                if ($stmt->execute([$this->name]))
                    return false;
        ***REMOVED***
            return $this->get_or_create();
    ***REMOVED***
        return Artist::get($stmt->fetch()['id_artist']);
***REMOVED***

    public static function get(int $id) {
        global $DB;
        $stmt = $DB->prepare('SELECT * FROM artists WHERE id_artist = ?');
        if (!$stmt->execute([$id]))
            return false;
        $data = $stmt->fetch();
        $artist = new Artist();
        $artist->id = $data['id_artist'];
        $artist->name = $data['name'];
        if (isset($data['id_user']))
            $artist->user = User::get($data['id_user']);
        return $artist;
***REMOVED***
}

class SongTag extends stdClass {
    public int $id;
    public string $name;

    public function get_or_create() {
        global $DB;
        $stmt = $DB->prepare('SELECT id_tag FROM tags WHERE name = ?');
        if (!$stmt->execute([$this->name]))
            return false;
        if ($stmt->rowCount() < 1) {
            $stmt = $DB->prepare('INSERT INTO tags (name) VALUES (?)');
            if (!$stmt->execute([$this->name]))
                return false;
            return $this->get_or_create();
    ***REMOVED***
        return SongTag::get($stmt->fetch()['id_tag']);
***REMOVED***

    public static function get(int $id) {
        global $DB;
        $stmt = $DB->prepare('SELECT * FROM tags WHERE id_tag = ?');
        if (!$stmt->execute([$id]))
            return false;
        $result = $stmt->fetch();
        $tag = new SongTag();
        $tag->id = $result['id_tag'];
        $tag->name = $result['name'];
        return $tag;
***REMOVED***
}

class Song extends stdClass {
    public int $id;
    public string $title;
    public array $artists;
    public Album $album;
    public Genre $genre;
    public array $tags;
    public string $file_url;

    public static function get_by_user_saves(string $userId) {
        global $DB;
        $stmt = $DB->prepare('SELECT s.id_song,
                   s.name AS song_name,
                   s.song_url,
                   g.id_genre AS id_genre,
                   g.name AS genre_name,
            FROM songs s
            INNER JOIN genres g ON s.id_genre = g.id_genre
            INNER JOIN songs_artists sa ON s.id_song = sa.id_song
            INNER JOIN songs_saves ss ON ss.id_song = s.id_song
            WHERE ss.id_user = ?');
        if (!$stmt->execute([$userId]))
            return false;

        $songs = [];
        while ($s = $stmt->fetch()) {
            if (!array_key_exists($s['id_song'], $songs)) {
                $song = new Song();
                $song->id = $s['id_song'];
                $song->file_url = $s['song_url'];
                $song->title = $s['song_name'];
                $song->genre = Genre::get($s['id_genre']);
                $song->album = Album::get($s['id_album']);
                $songs[$song->id] = $song;
        ***REMOVED***

            $songs[$s['id_song']]->artists[] = Artist::get($s['id_artist']);
    ***REMOVED***
        return $songs;
***REMOVED***

    public static function get(int $id) {
        global $DB;
        $stmt = $DB->prepare('SELECT s.id_song,
                   s.name AS song_name,
                   s.song_url,
                   g.id_genre AS id_genre,
                   g.name AS genre_name,
            FROM songs s
            INNER JOIN genres g ON s.id_genre = g.id_genre
            INNER JOIN songs_artists sa ON s.id_song = sa.id_song
            WHERE s.id_song = ?');
        if (!$stmt->execute([$id]))
            return false;

        $song = new Song();
        while ($s = $stmt->fetch()) {
            $song->id = $s['id_song'];
            $song->file_url = $s['song_url'];
            $song->title = $s['song_name'];
            $song->genre = Genre::get($s['id_genre']);
            $song->album = Album::get($s['id_album']);
            $song->artists[] = Artist::get($s['id_artist']);
    ***REMOVED***
        return $song;
***REMOVED***

    public static function get_by_searching(string $query) {
        global $DB;
        $stmt = $DB->prepare('SELECT s.id_song,
                   s.name AS song_name,
                   s.song_url,
                   g.id_genre AS id_genre,
                   g.name AS genre_name,
            FROM songs s
            INNER JOIN genres g ON s.id_genre = g.id_genre
            INNER JOIN songs_artists sa ON s.id_song = sa.id_song
            WHERE UPPER(s.name) LIKE ?');
        if (!$stmt->execute(['%' . strtoupper(trim($query)) . '%']))
            return false;

        $songs = [];
        while ($s = $stmt->fetch()) {
            if (!array_key_exists($s['id_song'], $songs)) {
                $song = new Song();
                $song->id = $s['id_song'];
                $song->file_url = $s['song_url'];
                $song->title = $s['song_name'];
                $song->genre = Genre::get($s['id_genre']);
                $song->album = Album::get($s['id_album']);
                $songs[$song->id] = $song;
        ***REMOVED***

            $songs[$s['id_song']]->artists[] = Artist::get($s['id_artist']);
    ***REMOVED***
        return $songs;
***REMOVED***

    public function insert() {
        global $DB;
        $albumId = $this->album->get_or_create();
        $genreId = $this->genre->get_or_create();
        if (!$albumId || !$genreId)
            return false;

        $stmt = $DB->prepare('INSERT INTO songs(name, id_album, id_genre, song_url) VALUES (?, ?, ?, ?)');
        if (!$stmt->execute([$this->title, $albumId, $this->genre->id, $this->url]))
            return false;

        foreach ($this->artists as $artist) {
            $artistId = $artist->get_or_create();
            $stmt = $DB->prepare('INSERT INTO songs_artists (id_song, id_artist) VALUES (?, ?)');
            if (!$stmt->execute([$this->id, $artistId]))
                return false;
    ***REMOVED***
        foreach ($this->tags as $tag) {
            $tagId = $tag->get_or_create();
            $stmt = $DB->prepare('INSERT INTO songs_tags (id_song, id_tag) VALUES (?, ?)');
            !$stmt->execute([$this->id, $tagId]);
    ***REMOVED***
        return Song::get_by_title($this->title);
***REMOVED***

    public static function get_by_title(string $title) {
        global $DB;
        $stmt = $DB->prepare('SELECT s.id_song,
                   s.name AS song_name,
                   s.song_url,
                   g.id_genre AS id_genre,
                   g.name AS genre_name,
            FROM songs s
            INNER JOIN genres g ON s.id_genre = g.id_genre
            INNER JOIN songs_artists sa ON s.id_song = sa.id_song
            WHERE s.song_name = ?');
        if (!$stmt->execute([$title]))
            return false;

        $song = new Song();
        while ($s = $stmt->fetch()) {
            $song->id = $s['id_song'];
            $song->file_url = $s['song_url'];
            $song->title = $s['song_name'];
            $song->genre = Genre::get($s['id_genre']);
            $song->album = Album::get($s['id_album']);
            $song->artists[] = Artist::get($s['id_artist']);
    ***REMOVED***
        return $song;
***REMOVED***

    public function get_comments() {
        global $DB;
        $stmt = $DB->prepare('SELECT c.*, u.name AS user_name
            FROM comments_songs c
            INNER JOIN users u ON c.id_user = u.id_user
            WHERE c.id_song = ?
            ORDER BY c.date_time DESC');
        $success = $stmt->execute([$this->id]);
        if (!$success)
            return false;
        return $stmt->fetchAll();
***REMOVED***

    public function insert_comment(int $userId, string $content): bool {
        global $DB;
        $stmt = $DB->prepare('INSERT INTO comments_songs (id_song, id_user, content)
            VALUES (?, ?, ?)');
        return $stmt->execute([$this->id, $userId, $content]);
***REMOVED***
}
