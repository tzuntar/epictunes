<?php
session_start();
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
        <?php include_once 'include/top-nav.php' ?>
        <link rel="stylesheet" href="./assets/mp3player.css"/>

        <main>
            <div class="margin-top-20">
                <section class="song-player-division grid-container">
                    <div class="grid-col">
                        <button id="playButton" class="play-button">
                            <img id="playIcon" src="./assets/img/icons/play.svg" alt="Play"/>
                        </button>
                    </div>
                    <div class="grid-col">
                        <h1 class="song-name"><?= $songData['name'] ?></h1>
                        <div id="mp3player-container">
                            <div id="mp3player">
                                <canvas id="analyzer-render"></canvas>
                            </div>
                            <label>
                                <input id="seek-slider" type="range" min="0" max="500" value="0" step="1">
                            </label>
                            <div id="time-box">
                                <span id="current-time">00:00</span>| <span id="duration">00:00</span>
                            </div>
                            <button id="mute-button"></button>
                            <label>
                                <input id="volume-slider" type="range" min="0" max="100" value="100" step="1">
                            </label>
                        </div>
                    </div>
                    <div class="grid-col">
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
    <script src="./utils/mp3player.js"></script>
    <script>
        $(document).ready(() => {
            // applyBgColorCss($('.song-player-division')[0],
            // getImgMutedColorRgb('albumArtBox'));
            $('.song-name')[0].style.color = getImgVibrantColorRgb('albumArtBox');
            initAudioPlayer('<?= $mp3path ?>');
        });
    </script>
<?php include_once 'include/footer.php' ?>