***REMOVED***
***REMOVED***
if (!isset($_SESSION['identifier']))
    header('Location: login.php');
if (!isset($_GET['id']))
    header('Location: index.php');
require_once 'utils/queries.php';
require_once 'utils/mp3.php';
$songData = db_get_song_data($_GET['id']);
if (!$songData)
    header('Location: index.php');

$mainArtist = $songData['artists'][0]['artist'];
$document_title = $songData['name'] . ' by ' . $mainArtist;
$mp3path = 'userdata/music/' . $songData['mp3_url'];
$albumArt = mp3_get_album_art($mp3path);

include_once 'include/header.php';
include_once 'include/sidebar.php' ?>
    <div class="root-container">
        ***REMOVED*** include_once 'include/top-nav.php' ?>

        <main>
            <div class="margin-top-20">
                <section class="song-player-division flex-container">
                    <div class="flex-child-half">
                        <button id="playButton" class="play-button">&gt;</button>
                        <?= $songData['name'] ?>
                    </div>
                    <div class="flex-child-half">
                        <img src="<?= $albumArt ?>" class="album-art-big"
                             id="albumArtBox" alt="Album Art"/>
                    </div>
                </section>
                <section class="artist-division">
                    <!-- artist image -->
                    <h2 class="accent"><?= $mainArtist ?></h2>
                </section>
                <section class="comment-division">

                </section>
            </div>
        </main>

        <div class="player"></div>
    </div>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/vibrant.js/1.0.0/Vibrant.min.js"
            integrity="sha512-V6rhYmJy8NZQF8F0bhJiTM0iI6wX/FKJoWvYrCM15UIeb6p38HjvTZWfO0IxJnMZrHWUJZJqLuWK0zslra2FVw=="
            crossorigin="anonymous" referrerpolicy="no-referrer"></script>
    <script>
        $(document).ready(function () {
            applyBgColorCss($('.song-player-division')[0],
                getImgSignificantColorRgb('albumArtBox'))
    ***REMOVED***);
    </script>
***REMOVED*** include_once 'include/footer.php' ?>