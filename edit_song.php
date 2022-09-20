***REMOVED***
***REMOVED***
$document_title = 'Edit Song';
if (!isset($_SESSION['identifier']))
    header('Location: login.php');
if (!isset($_GET['id']))
    header('Location: ' . $_SERVER['HTTP_REFERER']);
require_once 'utils/queries.php';
require_once 'utils/mp3.php';
$song = Song::get($_GET['id']);
if (!$_SESSION['is_admin'] && !$song->check_ownership($_SESSION['id']))
    header('Location: ' . $_SERVER['HTTP_REFERER']);

if (isset($_POST['title'])) {
    $newSong = new Song();
    $newSong->file_url = $_POST['filename'];
    $newSong->title = filter_input(INPUT_POST, 'title', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
    $artistsEntry = filter_input(INPUT_POST, 'artist', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
    if (!trim($artistsEntry) == '') {
        $artistsEntry = str_replace(', ', ',', $artistsEntry);
        foreach (explode(',', $artistsEntry) as $a) {
            $artist = new Artist();
            $artist->name = $a;
            $newSong->artists[] = $artist;
    ***REMOVED***
***REMOVED***
    $newSong->album = new Album();
    $newSong->album->name = filter_input(INPUT_POST, 'album', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
    $albumArtists = filter_input(INPUT_POST, 'album_artist', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
    if (!trim($albumArtists) == '') {
        $albumArtists = str_replace(', ', ',', $albumArtists);
        foreach (explode(',', $albumArtists) as $a) {
            $artist = new Artist();
            $artist->name = $a;
            $newSong->album->artists[] = $artist;
    ***REMOVED***
***REMOVED***
    $newSong->genre = new Genre();
    $newSong->genre->name = filter_input(INPUT_POST, 'genre', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
    $tags = filter_input(INPUT_POST, 'tags', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
    if (!trim($tags) == '') {
        $tags = str_replace(', ', ',', $tags);
        foreach (explode(',', $tags) as $t) {
            $tag = new SongTag();
            $tag->name = $t;
            $newSong->tags[] = $tag;
    ***REMOVED***
***REMOVED***
    $result = $newSong->insert();
    $song->delete();
    if ($result) header('Location: song.php?id=' . $result->id);
}

$songArtists = '';
$albumArtists = '';
$songTags = '';
foreach ($song->artists as $artist)
    $songArtists .= $artist->name . ', ';
foreach ($song->album->artists as $artist)
    $albumArtists .= $artist->name . ', ';
foreach ($song->tags as $tag)
    $songTags .= $tag->name . ', ';

include_once 'include/header.php';
include_once 'include/sidebar.php' ?>
<div class="root-container">
    ***REMOVED*** include_once 'include/top-nav.php' ?>
    <main>
        <h2 class="accent padding-20">Edit Song Data</h2>
        <form class="margin-top-20" method="post" enctype="multipart/form-data">
            <div class="grid-container meta-edit-grid">
                <div class="grid-col">
                    <div class="grid-row">
                        <p>Title:</p>
                    </div>
                    <div class="grid-row">
                        <p>Artists:</p>
                    </div>
                    <div class="grid-row">
                        <p>Album:</p>
                    </div>
                    <div class="grid-row">
                        <p>...by:</p>
                    </div>
                    <div class="grid-row">
                        <p>Genre:</p>
                    </div>
                    <div class="grid-row">
                        <p>Tags:</p>
                    </div>
                </div>
                <div class="grid-col">
                    <label>
                        <input type="text" name="title" required placeholder="Song title"
                               value="<?= $song->title ?>"/>
                    </label>
                    <label>
                        <input type="text" name="artist" required placeholder="Artist names (separated by commas)"
                               value="<?= isset($songArtists) ? rtrim($songArtists, ', ') : '' ?>"/>
                    </label>
                    <label>
                        <input type="text" name="album" required placeholder="Album name"
                               value="<?= $song->album->name ?? '' ?>"/>
                    </label>
                    <label>
                        <input type="text" name="album_artist" required
                               placeholder="Album artist names (separated by commas)"
                               value="<?= isset($albumArtists) ? rtrim($albumArtists, ', ') : '' ?>"/>
                    </label>
                    <label>
                        <input type="text" name="genre" required placeholder="Song genre"
                               value="<?= $song->genre->name ?? '' ?>"/>
                    </label>
                    <label>
                        <input type="text" name="tags" placeholder="Song tags (separated by commas)"
                               value="<?= isset($songTags) ? rtrim($songTags, ', ') : '' ?>"/>
                    </label>
                </div>
                <div class="grid-col">
                    <img src="<?= mp3_get_album_art('userdata/music/' . $song->file_url) ?>" alt="Album Art"
                         class="album-art-big"/>
                </div>
            </div>
            <p>
                <input type="hidden" name="filename" value="<?= $song->file_url ?>" readonly/>
            </p>
            <p class="header-center">
                <input type="submit" value="Save" class="margin-top-20"/>
            </p>
        </form>
    </main>
</div>
***REMOVED*** include_once 'include/footer.php' ?>
