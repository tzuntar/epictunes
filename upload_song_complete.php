***REMOVED***
***REMOVED***
if (!isset($_SESSION['identifier']))
    header('Location: login.php');
if (!isset($_POST['filename']) || !isset($_POST['title']))
    header('Location: ' . $_SERVER['HTTP_REFERER']);
require_once 'utils/queries.php';
$song = new Song();
$song->file_url = $_POST['filename'];
$song->title = filter_input(INPUT_POST, 'title', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
$artistsEntry = filter_input(INPUT_POST, 'artist', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
if (!trim($artistsEntry)) {
    $artistsEntry = str_replace(', ', ',', $artistsEntry);
    foreach (explode(',', $artistsEntry) as $a) {
        $artist = new Artist();
        $artist->name = $a;
        $song->artists[] = $artist;
***REMOVED***
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
***REMOVED***
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
***REMOVED***
}
$result = $song->insert();
if ($result) header('Location: song.php?id=' . $result->id);
