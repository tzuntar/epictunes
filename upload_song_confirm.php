<?php
session_start();
if (!isset($_SESSION['identifier']))
    header('Location: login.php');

include_once 'include/header.php';
include_once 'include/sidebar.php' ?>
<div class="root-container">
    <?php include_once 'include/top-nav.php' ?>

    <main class="padding-20">
        <h1 class="step-number">3</h1>
        <h2 class="step-heading accent">Confirm & Publish</h2>
        <form class="margin-top-20" action="upload_song_confirm.php?c=1"
              method="post" enctype="multipart/form-data">
            <p>
                <label>
                    <input type="checkbox" name="confirmation" required/>
                    I have the rights to upload this audio file. I agree to bear any
                    responsibility for the content I publish on this website.
                </label>
            </p>
            <p>
                <input type="submit" value="Publish" class="margin-top-20"/>
            </p>
        </form>
    </main>
</div>
<?php include_once 'include/footer.php' ?>
