<?php
session_start();
$document_title = 'Administration • Notify User';
if (!isset($_SESSION['identifier']))
    header('Location: login.php');
if (!$_SESSION['is_admin'])
    header('Location: index.php');
if (!isset($_GET['id']))
    header('Location: admin_users.php');
require_once 'utils/queries.php';
require_once 'utils/components.php';
$user = User::get($_SESSION['id']);

if (isset($_POST['userid'])) {
    $message = filter_input(INPUT_POST, 'message', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
    $targetUser = User::get($_POST['userid']);
    $targetUser->notify($message, $_POST['alert_type'], $_SESSION['id']);
    header('Location: admin_users.php');
}

$targetUser = User::get($_GET['id']);
if (!$targetUser) header('Location: admin_users.php');
include_once 'include/header.php';
include_once 'include/sidebar.php' ?>
    <div class="root-container">
        <?php include_once 'include/top-nav.php' ?>

        <main>
            <h2 class="accent padding-20"><?= $document_title ?></h2>
            <form class="margin-top-20" method="post" enctype="multipart/form-data">
                <div class="grid-container user-edit-grid">
                    <div class="grid-col">
                        <div class="grid-row">
                            <p class="field-label">Username:</p>
                        </div>
                        <div class="grid-row">
                            <p class="field-label">Alert Type:</p>
                        </div>
                        <div class="grid-row">
                            <p class="field-label">Message:</p>
                        </div>
                    </div>
                    <div class="grid-col">
                        <label>
                            <input type="text" name="userid" required value="<?= $targetUser->username ?>" readonly
                                   disabled/>
                        </label>
                        <label>
                            <select name="alert_type" required>
                                <option name="info" selected>Information</option>
                                <option name="warning">Warning</option>
                                <option name="severe_warning">Severe Warning</option>
                            </select>
                        </label>
                        <label>
                            <textarea name="message" required placeholder="Enter your message..."></textarea>
                        </label>
                    </div>
                </div>
                <p>
                    <input type="hidden" name="userid" value="<?= $targetUser->id ?>" readonly/>
                </p>
                <p class="header-center">
                    <input type="submit" value="Notify" class="margin-top-20"/>
                </p>
            </form>
        </main>

    </div>
<?php include_once 'include/footer.php' ?>