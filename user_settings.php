<?php
session_start();
$document_title = 'My Music';
if (!isset($_SESSION['identifier']))
    header('Location: login.php');
require_once 'utils/queries.php';
$user = User::get($_SESSION['id']);

if (isset($_POST['password']) && !empty($_POST['password'])) {
    $newHash = password_hash($_POST['password'], PASSWORD_DEFAULT);
    if (!password_verify($_POST['confirm_password'], $newHash))
        header('edit_user.php?id=' . $_GET['id']);
    $updated = clone $user;
    $updated->passwordHash = $newHash;
    $user->update($updated);
}

if (!empty($_FILES['profile_photo']['name'])) {
//    global $top_level;
    $path = pathinfo($_FILES['profile_photo']['name']);
    $imageFile = $path['filename'] . '-' . uniqid() . '.' . $path['extension'];   // to avoid filename collisions
    $uploadTarget = /*$top_level . */
        './userdata/profile_pics/' . $imageFile;
    if (move_uploaded_file($_FILES['profile_photo']['tmp_name'], $uploadTarget)
        && $user->update_profile_pic($uploadTarget))
        $_SESSION['profile_pic'] = $uploadTarget;
}

include_once 'include/header.php';
include_once 'include/sidebar.php' ?>
<div class="root-container">
    <?php include_once 'include/top-nav.php' ?>
    <main>
        <h2 class="accent padding-20">User Preferences</h2>
        <form class="margin-top-20" method="post" enctype="multipart/form-data">
            <h2 class="accent margin-lr-20 light-underline">Change Password</h2>
            <div class="grid-container user-edit-grid margin-top-20">
                <table class="edit-table">
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
                </table>
            </div>

            <h2 class="accent margin-lr-20 light-underline">Change Profile Photo</h2>
            <div class="grid-container user-edit-grid margin-top-20">
                <div class="grid-col">
                    <div class="grid-row">
                        <p class="field-label">Upload an image:</p>
                    </div>
                </div>
                <div class="grid-col">
                    <label>
                        <input type="file" name="profile_photo" accept=".jpg,.jpeg,.png,.gif" class="margin-top-20"/>
                    </label>
                </div>
            </div>
            <p class="header-center">
                <input type="submit" value="Save" class="margin-top-20"/>
            </p>
        </form>
    </main>
</div>
<?php include_once 'include/footer.php' ?>
