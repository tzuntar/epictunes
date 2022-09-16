<?php
session_start();
if (!isset($_SESSION['identifier']))
    header('Location: login.php');
if (!isset($_GET['id']))
    header('Location: ' . $_SERVER['HTTP_REFERER']);
require_once 'utils/queries.php';
require_once 'utils/components.php';
require_once 'utils/mp3.php';
$album = Album::get($_GET['id']);
$containedSongs = Song::get_by_album($album);
if (sizeof($containedSongs) > 0)
    $albumArt = mp3_get_album_art('userdata/music/' . reset($containedSongs)->file_url);
if (!$album) header('Location: ' . $_SERVER['HTTP_REFERER']);
$document_title = $album->name;

include_once 'include/header.php';
include_once 'include/sidebar.php' ?>
<div class="root-container">
    <?php include_once 'include/top-nav.php' ?>

    <main>
        <div class="margin-top-20">
            <section class="user-summary-division grid-container">
                <div class="grid-col centered-flex-container">
                    <img class="album-art-big margin-lr-20" src="<?= $albumArt ?? '/assets/img/icons/avatar.svg' ?>"
                         alt="Album Art"/>
                </div>
                <div class="grid-col">
                    <h1 class="user-name"><?= $album->name ?></h1>
                </div>
            </section>
            <section class="user-song-list">
                <?php if (isset($containedSongs)) {
                    render_song_list($containedSongs);
                } ?>
            </section>
        </div>
    </main>
</div>
<?php include_once 'include/footer.php' ?>
