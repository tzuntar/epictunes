<?php
session_start();
$document_title = 'My Music';
if (!isset($_SESSION['identifier']))
    header('Location: login.php');
require_once 'utils/queries.php';
require_once 'utils/mp3.php';
$userSongs = db_get_saved_songs_summary_user($_SESSION['id']);

include_once 'include/header.php';
include_once 'include/sidebar.php' ?>
    <div class="root-container">
        <?php include_once 'include/top-nav.php' ?>

        <main>
            <h2 class="accent padding-20">My Music</h2>
            <div class="margin-top-20">
                <?php foreach ($userSongs as $song) {
                    $mp3path = 'userdata/music/' . $song['mp3_url'];
                    $albumArt = mp3_get_album_art($mp3path) ?>
                    <div class="song-list-card">
                        <div class="flex-container">
                            <div>
                                <img class="album-art-list" src="<?= $albumArt ?>" alt="Album Art"/>
                            </div>
                            <div>
                                <p><a href="song.php?id=<?= $song['id_song'] ?>"><?= $song['name'] ?></a></p>
                                <p class="sub-label">
                                    <?php $artists = '';
                                    foreach ($song['artists'] as $artist)
                                        $artists .= $artist['artist'] . ' / ';
                                    echo rtrim($artists, ' /') ?></p>
                            </div>
                        </div>
                    </div>
                <?php } ?>
            </div>
        </main>

        <div class="player"></div>
    </div>
<?php include_once 'include/footer.php' ?>