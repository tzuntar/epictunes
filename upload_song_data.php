***REMOVED***
***REMOVED***
if (!isset($_SESSION['identifier']))
    header('Location: login.php');
if ($_FILES['mp3file']['name'] == 0)
    header('Location: upload_song.php');

$mp3file = 'userdata/music/' . $_FILES['mp3file']['name'];
move_uploaded_file($_FILES['mp3file']['tmp_name'], $mp3file);

include_once 'include/header.php';
include_once 'include/sidebar.php' ?>
<div class="root-container">
    ***REMOVED*** include_once 'include/top-nav.php' ?>

    <main class="padding-20">
        <h1 class="step-number">2</h1>
        <h2 class="step-heading accent">Edit Data</h2>
        <form class="margin-top-20" action="upload_song_confirm.php" method="post"
              enctype="multipart/form-data">
            <div class="grid-container meta-edit-grid">
                <!-- ToDo: find out why the grid placement doesn't work correctly here -->
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
                    <input type="text" name="title" required placeholder="Song title"/>
                    <input type="text" name="artist" required placeholder="Artist names (separated by commas)"/>
                    <input type="text" name="album" required placeholder="Album name"/>
                    <input type="text" name="album_artist" required
                           placeholder="Album artist names (separated by commas)"/>
                    <input type="text" name="genre" required placeholder="Song genre"/>
                    <input type="text" name="tags" placeholder="Song tags (separated by commas)"/>
                </div>
                <div class="grid-col">
                    <img src="" alt="Album Art" class="album-art-big"/>
                </div>
            </div>
        </form>
    </main>
</div>
***REMOVED*** include_once 'include/footer.php' ?>
