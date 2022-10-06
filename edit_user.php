<?php
session_start();
$document_title = 'Edit User';
if (!isset($_SESSION['id']) || !$_SESSION['is_admin'])
    header('Location: index.php');
if (!isset($_GET['id']))
    header('Location: admin_users.php');
require_once 'utils/queries.php';
$user = User::get($_GET['id']);
if (!$user) header('Location: admin_users.php');

if (isset($_POST['name'])) {
    if (!empty($_POST['password'])) {
        $newHash = password_hash($_POST['password'], PASSWORD_DEFAULT);
        if (!password_verify($_POST['confirm_password'], $newHash))
            header('edit_user.php?id=' . $_GET['id']);
    }
    $updatedUser = clone $user;
    $updatedUser->name = filter_input(INPUT_POST, 'name', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
    $updatedUser->username = filter_input(INPUT_POST, 'username', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
    $updatedUser->email = filter_input(INPUT_POST, 'email', FILTER_SANITIZE_EMAIL);
    if (isset($newHash))
        $updatedUser->passwordHash = $newHash;
    $updatedUser->isAdmin = $_POST['is_admin'];
    if ($user->update($updatedUser))
        header('Location: admin_users.php');
}

include_once 'include/header.php';
include_once 'include/sidebar.php' ?>
<div class="root-container">
    <?php include_once 'include/top-nav.php' ?>
    <main>
        <h2 class="accent padding-20">Edit User Data</h2>
        <form class="margin-top-20" method="post" enctype="multipart/form-data">
            <div class="grid-container user-edit-grid">
                <table class="edit-table">
                    <tr>
                        <td><p class="field-label">Name:</p></td>
                        <td><label>
                                <input type="text" name="name" required placeholder="Full name"
                                       value="<?= $user->name ?>"/>
                            </label></td>
                    </tr>
                    <tr>
                        <td><p class="field-label">Username:</p></td>
                        <td><label>
                                <input type="text" name="username" required placeholder="Username (without spaces)"
                                       value="<?= $user->username ?>"/>
                            </label></td>
                    </tr>
                    <tr>
                        <td><p class="field-label">E-mail:</p></td>
                        <td><label>
                                <input type="email" name="email" required placeholder="E-mail"
                                       value="<?= $user->email ?? '' ?>"/>
                            </label></td>
                    </tr>
                    <tr>
                        <td><p class="field-label">Password:</p></td>
                        <td><label>
                                <input type="password" name="password" placeholder="New password"/>
                            </label></td>
                    </tr>
                    <tr>
                        <td><p class="field-label">Confirm:</p></td>
                        <td><label>
                                <input type="password" name="confirm_password" placeholder="Re-enter the password"/>
                            </label></td>
                    </tr>
                    <tr>
                        <td><p class="field-label">Administrator:</p></td>
                        <td><label>
                                <input type="checkbox" name="is_admin" <?= $user->isAdmin ? ' checked ' : '' ?>/>
                            </label></td>
                    </tr>
                </table>
            </div>
            <p class="header-center">
                <input type="submit" value="Save" class="margin-top-20"/>
            </p>
        </form>
    </main>
</div>
<?php include_once 'include/footer.php' ?>
