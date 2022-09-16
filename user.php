<?php
session_start();
if (!isset($_SESSION['identifier']))
    header('Location: login.php');
if (!isset($_GET['id']))
    header('Location: ' . $_SERVER['HTTP_REFERER']);
require_once 'utils/queries.php';
require_once 'utils/components.php';
$user = User::get($_GET['id']);
if (!$user) {
    $user = Artist::get($_GET['id']);
    $postedSongs = Song::get_by_artist($_GET['id']);
} else $postedSongs = Song::get_by_artist($_GET['id'], true);
if (!$user) header('Location: ' . $_SERVER['HTTP_REFERER']);
$document_title = $user->name . "'s Profile";

include_once 'include/header.php';
include_once 'include/sidebar.php' ?>
<div class="root-container">
    <?php include_once 'include/top-nav.php' ?>

    <main>
        <h2 class="accent padding-20">User Profile</h2>
        <div class="margin-top-20">
            <section class="user-summary-division grid-container">
                <div class="grid-col centered-flex-container">
                    <img class="user-profile-pic" src="/assets/img/icons/avatar.svg" alt="Profile Picture"/>
                </div>
                <div class="grid-col">
                    <h1 class="user-name"><?= $user->name ?></h1>
                </div>
            </section>
            <section class="user-song-list">
                <?php if (isset($postedSongs)) {
                    render_song_list($postedSongs);
                } ?>
            </section>
        </div>
    </main>
</div>
<?php include_once 'include/footer.php' ?>
