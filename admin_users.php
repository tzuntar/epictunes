<?php
session_start();
$document_title = 'Administration • Users';
if (!isset($_SESSION['identifier']))
    header('Location: login.php');
if (!$_SESSION['is_admin'])
    header('Location: index.php');
require_once 'utils/queries.php';
require_once 'utils/components.php';
$allUsers = User::get_all();

include_once 'include/header.php';
include_once 'include/sidebar.php' ?>
    <div class="root-container">
        <?php include_once 'include/top-nav.php' ?>

        <main>
            <h2 class="accent padding-20"><?= $document_title ?></h2>
            <div class="margin-top-20">
                <?php if (isset($allUsers)) {
                    render_user_list($allUsers);
                } ?>
            </div>
        </main>

    </div>
<?php include_once 'include/footer.php' ?>