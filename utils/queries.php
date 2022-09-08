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
 * Retrieve the card summary data of songs saved by this user
 * @param string $userId the user's ID
 * @return array|false the data or false if the query fails
 */
function db_get_saved_songs_user(string $userId) {
    global $DB;
    $stmt = $DB->prepare('SELECT s.id_song,
               s.name AS song_name,
               g.id_genre AS id_genre,
               g.name AS genre_name,
               a.id_album AS id_album,
               a.name AS album_name,
               a.art_url,
               a2.id_artist AS id_artist,
               a2.name AS artist_name
        FROM songs s
        INNER JOIN albums a ON s.id_album = a.id_album
        INNER JOIN genres g ON s.id_genre = g.id_genre
        INNER JOIN songs_artists sa ON s.id_song = sa.id_song
        INNER JOIN artists a2 ON sa.id_artist = a2.id_artist
        INNER JOIN songs_saves ss on s.id_song = ss.id_song
        WHERE ss.id_user = ?');
    $success = $stmt->execute([$userId]);
    if (!$success)
        return false;

    $songs = [];
    while ($song = $stmt->fetch()) {
        $songId = $song['id_song'];
        if (!isset($songs[$songId])) {
            $songs[] = [
                'id_song' => $songId,
                'name' => $song['song_name'],
                'id_genre' => $song['id_genre'],
                'genre' => $song['genre_name'],
                'id_album' => $song['id_album'],
                'album' => $song['album_name'],
                'album_art' => $song['art_url'],
            ];
        }

        $songs[$songId]['artists'][] = [
            'id_artist' => $song['id_artist'],
            'artist' => $song['artist_name']
        ];
    }
    return $songs;
}
