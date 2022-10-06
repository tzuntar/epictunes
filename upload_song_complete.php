<?php
session_start();
if (!isset($_SESSION['identifier']))
    header('Location: login.php');
if (empty($_POST['filename']) || empty($_POST['title']))
    header('Location: upload_song.php');
require_once 'utils/queries.php';
$song = new Song();
$song->file_url = $_POST['filename'];
$song->title = filter_input(INPUT_POST, 'title', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
$artistsEntry = filter_input(INPUT_POST, 'artist', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
$userArtist = new Artist(); // set current user as main artist
$userArtist->name = $_SESSION['name'];
$userArtist->user = User::get($_SESSION['id']);
$song->artists[] = $userArtist;
if (!trim($artistsEntry) == '') {
    $artistsEntry = str_replace(', ', ',', $artistsEntry);
    foreach (explode(',', $artistsEntry) as $a) {
        $artist = new Artist();
        $artist->name = $a;
        $song->artists[] = $artist;
    }
}
$song->album = new Album();
$song->album->name = filter_input(INPUT_POST, 'album', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
$albumArtists = filter_input(INPUT_POST, 'album_artist', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
if (!trim($albumArtists) == '') {
    $albumArtists = str_replace(', ', ',', $albumArtists);
    foreach (explode(',', $albumArtists) as $a) {
        $artist = new Artist();
        $artist->name = $a;
        $song->album->artists[] = $artist;
    }
}
$song->genre = new Genre();
$song->genre->name = filter_input(INPUT_POST, 'genre', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
$tags = filter_input(INPUT_POST, 'tags', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
if (!trim($tags) == '') {
    $tags = str_replace(', ', ',', $tags);
    foreach (explode(',', $tags) as $t) {
        $tag = new SongTag();
        $tag->name = $t;
        $song->tags[] = $tag;
    }
}
$result = $song->insert();
if (isset($result->id)) {
    $result->save($_SESSION['id']);
    header('Location: song.php?id=' . $result->id);
}
