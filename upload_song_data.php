<?php
session_start();
if (!isset($_SESSION['identifier']))
    header('Location: login.php');
if ($_FILES['mp3file']['name'] == 0)
    header('Location: upload_song.php');

$mp3file = 'userdata/music/' . $_FILES['mp3file']['name'];
move_uploaded_file($_FILES['mp3file']['tmp_name'], $mp3file);

include_once 'include/header.php';
include_once 'include/sidebar.php' ?>
<div class="root-container">
    <?php include_once 'include/top-nav.php' ?>

    <main class="padding-20">
        <h1 class="step-number">2</h1>
        <h2 class="step-heading accent">Edit Data</h2>
        <form class="margin-top-20" action="upload_song_confirm.php" method="post"
              enctype="multipart/form-data">
            <div class="grid-container">

            </div>
        </form>
    </main>
</div>
<?php include_once 'include/footer.php' ?>
