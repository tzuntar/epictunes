<?php
session_start();
if (!isset($_SESSION['identifier']))
    header('Location: login.php');
if (!isset($_GET['id']))
    header('Location: ' . $_SERVER['HTTP_REFERER']);
require_once 'utils/queries.php';
require_once 'utils/components.php';
$user = User::get($_GET['id']);
if (!$user) $user = Artist::get($_GET['id']);
if (!$user) header('Location: ' . $_SERVER['HTTP_REFERER']);
$document_title = $user->name . "'s Profile";

include_once 'include/header.php';
include_once 'include/sidebar.php' ?>
<div class="root-container">
    <?php include_once 'include/top-nav.php' ?>

    <main>
        <h2 class="accent padding-20">User Profile</h2>
        <div class="margin-top-20">
            <?= $user->name ?>
        </div>
    </main>
</div>
<?php include_once 'include/footer.php' ?>
