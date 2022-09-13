<?php
session_start();
if (!isset($_SESSION['identifier']))
    header('Location: login.php');
if ($_FILES['mp3file']['name'] == 0)
    header('Location: upload_song.php');
require_once 'utils/mp3.php';

$mp3file = 'userdata/music/' . $_FILES['mp3file']['name'] . '-' . uniqid();
move_uploaded_file($_FILES['mp3file']['tmp_name'], $mp3file);
$mp3tags = mp3_get_song_data($mp3file);

include_once 'include/header.php';
include_once 'include/sidebar.php' ?>
<div class="root-container">
    <?php include_once 'include/top-nav.php' ?>

    <main class="padding-20">
        <h1 class="step-number">2</h1>
        <h2 class="step-heading accent">Edit Data</h2>
        <form class="margin-top-20" action="upload_song_complete.php" method="post"
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
                               value="<?= $mp3tags['title'] ?>"/>
                    </label>
                    <label>
                        <input type="text" name="artist" required placeholder="Artist names (separated by commas)"
                               value="<?= $mp3tags['artist'] ?>"/>
                    </label>
                    <label>
                        <input type="text" name="album" required placeholder="Album name"
                               value="<?= $mp3tags['album'] ?>"/>
                    </label>
                    <label>
                        <input type="text" name="album_artist" required
                               placeholder="Album artist names (separated by commas)"
                               value="<?= $mp3tags['album_artist'] ?>"/>
                    </label>
                    <label>
                        <input type="text" name="genre" required placeholder="Song genre"
                               value="<?= $mp3tags['genre'] ?>"/>
                    </label>
                    <label>
                        <input type="text" name="tags" placeholder="Song tags (separated by commas)"/>
                    </label>
                </div>
                <div class="grid-col">
                    <img src="<?= $mp3tags['album_art'] ?>" alt="Album Art" class="album-art-big"/>
                </div>
            </div>
            <p>
                <label>
                    <input type="checkbox" name="confirmation" required/>
                    I have the rights to upload this audio file. I agree to bear any
                    responsibility for the content I publish on this website.
                </label>
            </p>
            <p>
                <input type="hidden" name="filename" value="<?= $mp3file ?>" readonly/>
            </p>
            <p class="header-center">
                <input type="submit" value="Publish" class="margin-top-20"/>
            </p>
        </form>
    </main>
</div>
<?php include_once 'include/footer.php' ?>
