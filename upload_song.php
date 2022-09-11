<?php
session_start();
if (!isset($_SESSION['identifier']))
    header('Location: login.php');

include_once 'include/header.php';
include_once 'include/sidebar.php' ?>
    <div class="root-container">
        <?php include_once 'include/top-nav.php' ?>

        <main class="padding-20">
            <h1 class="step-number">1</h1>
            <h2 class="step-heading accent">Upload Song</h2>
            <form class="margin-top-20" action="upload_song_data.php" method="post"
                  enctype="multipart/form-data">
                <p>
                    <label>
                        Select an MP3 file:
                        <input type="file" name="mp3file" accept=".mp3" required/>
                    </label>
                </p>
                <p class="fine-print">Only upload files you have the rights to.
                    Unauthorized uploading of copyrighted content is illegal and will
                    result in an immediate account termination.</p>
                <p>
                    <input type="submit" value="Continue" class="margin-top-20"/>
                </p>
            </form>
        </main>
    </div>
<?php include_once 'include/footer.php' ?>