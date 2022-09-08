***REMOVED***
***REMOVED***
$document_title = 'My Music';
if (!isset($_SESSION['identifier']))
    header('Location: login.php');
require_once 'utils/queries.php';
require_once 'utils/mp3.php';
$userSongs = db_get_saved_songs_user($_SESSION['id']);

include_once 'include/header.php';
include_once 'include/sidebar.php' ?>
    <div class="root-container">
        ***REMOVED*** include_once 'include/top-nav.php' ?>

        <main>
            <h2 class="accent padding-20">My Music</h2>
            <div class="margin-top-20">
                ***REMOVED*** foreach ($userSongs as $song) {
                    $mp3path = 'userdata/music/' . $song['mp3_url'];
                    $albumArt = mp3_get_album_art($mp3path) ?>
                    <div class="song-list-card">
                        <div class="flex-container">
                            <div>
                                <img class="album-art-list" src="<?= $albumArt ?>" alt="Album Art"/>
                            </div>
                            <div>
                                <p><?= $song['name'] ?></p>
                                <p class="sub-label">
                                    ***REMOVED*** foreach ($song['artists'] as $artist) { ?>
                                        <?= $artist['artist'] ?> /
                                    ***REMOVED*** } ?></p>
                            </div>
                        </div>
                    </div>
                ***REMOVED*** } ?>
            </div>
        </main>

        <div class="player"></div>
    </div>
***REMOVED*** include_once 'include/footer.php' ?>