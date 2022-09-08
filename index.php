<?php
session_start();
$document_title = 'My Music';
if (!isset($_SESSION['identifier']))
    header('Location: login.php');
require_once 'utils/queries.php';
$userSongs = db_get_saved_songs_user($_SESSION['id']);

include_once 'include/header.php';
include_once 'include/sidebar.php' ?>
    <div class="root-container">
        <?php include_once 'include/top-nav.php' ?>

        <main>
            <h2 class="accent padding-20">My Music</h2>
            <div class="margin-top-20">
                <?php foreach ($userSongs as $song) { ?>
                    <div class="song-list-card">
                        <?= $song['name'] ?>
                    </div>
                <?php } ?>
            </div>
        </main>

        <div class="player"></div>
    </div>
<?php include_once 'include/footer.php' ?>