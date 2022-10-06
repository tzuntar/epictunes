<?php
session_start();
if (!isset($_SESSION['identifier']))
    header('Location: login.php');
if ($_FILES['mp3file']['name'] == 0)
    header('Location: upload_song.php');
require_once 'utils/mp3.php';

$path = pathinfo($_FILES['mp3file']['name']);
$mp3file = $path['filename'] . '-' . uniqid() . '.' . $path['extension'];   // to avoid filename collisions
$uploadTarget = 'userdata/music/' . $mp3file;
move_uploaded_file($_FILES['mp3file']['tmp_name'], $uploadTarget);
$mp3tags = mp3_get_song_data($uploadTarget);

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
                    <table class="edit-table">
                        <tr>
                            <td><p class="field-label">Title:</p></td>
                            <td><label>
                                    <input type="text" name="title" required placeholder="Song title"
                                           value="<?= $mp3tags['title'] ?>"/>
                                </label></td>
                        </tr>
                        <tr>
                            <td><p class="field-label">Collaborators:</p></td>
                            <td><label>
                                    <input type="text" name="artist" required placeholder="Artist names (separated by commas)"
                                           value="<?= $mp3tags['artist'] ?>"/>
                                </label></td>
                        </tr>
                        <tr>
                            <td><p class="field-label">Album:</p></td>
                            <td><label>
                                    <input type="text" name="album" required placeholder="Album name"
                                           value="<?= $mp3tags['album'] ?>"/>
                                </label></td>
                        </tr>
                        <tr>
                            <td><p class="field-label">...by:</p></td>
                            <td><label>
                                    <input type="text" name="album_artist" required
                                           placeholder="Album artist names (separated by commas)"
                                           value="<?= $mp3tags['album_artist'] ?>"/>
                                </label></td>
                        </tr>
                        <tr>
                            <td><p class="field-label">Genre:</p></td>
                            <td><label>
                                    <input type="text" name="genre" required placeholder="Song genre"
                                           value="<?= $mp3tags['genre'] ?>"/>
                                </label></td>
                        </tr>
                        <tr>
                            <td><p class="field-label">Tags:</p></td>
                            <td><label>
                                    <input type="text" name="tags" placeholder="Song tags (separated by commas)"/>
                                </label></td>
                        </tr>
                    </table>
                </div>
                <?php if (isset($mp3tags['album_art'])) { ?>
                <div class="grid-col">
                    <img src="<?= $mp3tags['album_art'] ?: '/assets/img/icons/avatar.svg' ?>" alt="Album Art" class="album-art-big"/>
                </div>
                <?php } ?>
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
