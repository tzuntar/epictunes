<?php
session_start();
if (!isset($_SESSION['identifier']))
    header('Location: login.php');
require_once 'utils/queries.php';
require_once 'utils/components.php';
$document_title = 'My Albums';
$albums = Album::get_all();

include_once 'include/header.php';
include_once 'include/sidebar.php' ?>
<div class="root-container">
    <?php include_once 'include/top-nav.php' ?>

    <main>
        <h2 class="accent padding-20">My Albums</h2>
        <div class="margin-top-20">
            <?php if ($albums) {
                render_album_list($albums);
            } ?>
        </div>
    </main>
</div>
<?php include_once 'include/footer.php' ?>
