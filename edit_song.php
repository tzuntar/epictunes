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
        <form class="margin-top-20" action="edit_song.php?id=<?= $song->id ?>" method="post"
              enctype="multipart/form-data">
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
                               value="<?= $song->album ?? '' ?>"/>
                    </label>
                    <label>
                        <input type="text" name="album_artist" required
                               placeholder="Album artist names (separated by commas)"
                               value="<?= isset($albumArtists) ? rtrim($albumArtists, ', ') : '' ?>"/>
                    </label>
                    <label>
                        <input type="text" name="genre" required placeholder="Song genre"
                               value="<?= $song->genre ?? '' ?>"/>
                    </label>
                    <label>
                        <input type="text" name="tags" placeholder="Song tags (separated by commas)"
                               value="<?= isset($songTags) ? rtrim($songTags, ', ') : '' ?>"/>
                    </label>
                </div>
                <div class="grid-col">
                    <img src="<?= mp3_get_album_art('/userdata/uploads/' . $song->file_url) ?>" alt="Album Art"
                         class="album-art-big"/>
                </div>
            </div>
            <p class="header-center">
                <input type="submit" value="Save" class="margin-top-20"/>
            </p>
        </form>
    </main>
</div>
***REMOVED*** include_once 'include/footer.php' ?>
