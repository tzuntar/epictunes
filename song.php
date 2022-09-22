***REMOVED***
***REMOVED***
if (!isset($_SESSION['identifier']))
    header('Location: login.php');
if (!isset($_GET['id']))
    header('Location: index.php');
require_once 'utils/queries.php';
require_once 'utils/components.php';
require_once 'utils/mp3.php';
$songData = Song::get($_GET['id']);
if (!$songData)
    header('Location: index.php');
$isSaved = $songData->check_is_saved($_SESSION['id']);

if (isset($_GET['action'])) {
    if ($_GET['action'] == 'save')
        if ($isSaved) $songData->unsave($_SESSION['id']);
        else $songData->save($_SESSION['id']);
    header('Location: song.php?id=' . $_GET['id']);
}

$mainArtist = $songData->album->artists[0];
$mainArtistId = $songData->album->artists[0]->id;
$document_title = $songData->title . ' by ' . $mainArtist->name;
$mp3path = 'userdata/music/' . $songData->file_url;
$albumArt = mp3_get_album_art($mp3path);
$comments = $songData->get_comments();

include_once 'include/header.php';
include_once 'include/sidebar.php' ?>
    <div class="root-container">
        ***REMOVED*** include_once 'include/top-nav.php' ?>
        <link rel="stylesheet" href="./assets/mp3player.css"/>

        <main>
            <div class="margin-top-20">
                <section class="song-player-division grid-container">
                    <div class="grid-col">
                        <button id="playButton" class="play-button">
                            <img id="playIcon" src="./assets/img/icons/play.svg" alt="Play"/>
                        </button>
                        <a href="song.php?id=<?= $_GET['id'] ?>&action=save">
                            <button id="saveButton" class="play-button">
                                <img class="icon-make-smaller" alt="Save"
                                     src="./assets/img/icons/<?= $isSaved ? 'unsave.svg' : 'save.svg' ?>"/>
                            </button>
                        </a>
                    </div>
                    <div class="grid-col">
                        <h1 class="song-name"><?= $songData->title ?></h1>
                        <div class="grid-row">
                            <div id="mp3player-container">
                                <div id="mp3player">
                                    <canvas id="analyzer-render"></canvas>
                                </div>
                                <label>
                                    <input id="seek-slider" type="range" min="0" max="500" value="0" step="1">
                                </label>
                                <div id="time-box">
                                    <span id="current-time">00:00</span> • <span id="duration">00:00</span>
                                </div>
                                <label>
                                    <input id="volume-slider" type="range" min="0" max="100" value="100" step="1">
                                </label>
                            </div>
                        </div>
                    </div>
                    <div class="grid-col flex-column">
                        <p>&nbsp;</p>
                        <img src="<?= $albumArt ?>" class="album-art-big flex-end"
                             id="albumArtBox" alt="Album Art"/>
                    </div>
                </section>
                <section class="artist-division grid-container padding-lr-20">
                    <div class="grid-col">
                        <img class="artist-photo" src="./assets/img/icons/avatar.svg" alt="Artist's Photo"/>
                    </div>
                    <div class="grid-col">
                        <h2><a class="accent" href="artist.php?id=<?= $mainArtistId ?>"><?= $mainArtist->name ?></a>
                        </h2>
                        <a class="action-link fine-print" href="artist.php?id=<?= $mainArtistId ?>">View profile →</a>
                    </div>
                </section>
                <section class="comment-division padding-lr-20">
                    <form method="post" enctype="multipart/form-data"
                          action="post_comment.php?song=<?= $songData->id ?>">
                        <label>
                            <input name="comment" class="comment-entry" placeholder="Write a comment..."
                                   type="text" required/>
                        </label>
                        <input class="neutral-button" type="submit" value="Post"/>
                    </form>
                    ***REMOVED*** if (!$comments || sizeof($comments) < 1) { ?>
                        <p class="fine-print">No comments yet</p>
                    ***REMOVED*** } else {
                        foreach ($comments as $comment) { ?>
                            <div class="grid-container comment-grid">
                                <div class="grid-col">
                                    <a href="artist.php?id=<?= $comment['id_user'] ?>">
                                        <img alt="Profile Photo" class="round-profile-photo"
                                             src="<?= $comment['profile_pic_url'] ?? './assets/img/icons/avatar.svg' ?>"/></a>
                                </div>
                                <div class="grid-col">
                                    <p>
                                        <a href="artist.php?id=<?= $comment['id_user'] ?>">
                                            <strong><?= $comment['user_name'] ?></strong></a>
                                        at <strong><?= $comment['date_time'] ?></strong></p>
                                    <p><?= highlight_comment_timestamps($comment['content']) ?></p>
                                </div>
                            </div>
                        ***REMOVED*** }
                ***REMOVED*** ?>
                </section>
            </div>
        </main>

    </div>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/vibrant.js/1.0.0/Vibrant.min.js"
            integrity="sha512-V6rhYmJy8NZQF8F0bhJiTM0iI6wX/FKJoWvYrCM15UIeb6p38HjvTZWfO0IxJnMZrHWUJZJqLuWK0zslra2FVw=="
            crossorigin="anonymous" referrerpolicy="no-referrer"></script>
    <script src="./utils/mp3player.js"></script>
    <script>
        $(document).ready(() => {
            const vibrantColor = getImgVibrantColorRgb('albumArtBox');
            $('.song-name')[0].style.color = vibrantColor;
            initAudioPlayer('<?= $mp3path ?>', vibrantColor);
    ***REMOVED***);
    </script>
***REMOVED*** include_once 'include/footer.php' ?>