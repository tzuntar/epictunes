<?php
session_start();
if (!isset($_SESSION['identifier']))
    header('Location: login.php');
require_once 'utils/queries.php';
require_once 'utils/components.php';
$document_title = 'My Albums';

include_once 'include/header.php';
include_once 'include/sidebar.php' ?>
<div class="root-container">
    <?php include_once 'include/top-nav.php' ?>

    <main>
        <h2 class="accent padding-20">My Albums</h2>
        <div class="margin-top-20">

        </div>
    </main>
</div>
<?php include_once 'include/footer.php' ?>
