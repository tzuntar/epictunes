<?php
session_start();
$document_title = 'Create Account';
$noflex = true;

require_once $_SERVER['DOCUMENT_ROOT'] . '/utils/queries.php';

if (isset($_POST['username'])) {
    if (empty($_POST['name'])
        or empty($_POST['email']
            or empty($_POST['password'])
            or empty($_POST['confirm_password']))) {
        $registerMessage = 'Please fill in all required fields';
    } else {
        $password_hash = password_hash($_POST['password'], PASSWORD_DEFAULT);
        if (!password_verify($_POST['confirm_password'], $password_hash)) {
            $registerMessage = "The passwords don't match";
        } else {
            $name = filter_input(INPUT_POST, 'name',
                FILTER_SANITIZE_FULL_SPECIAL_CHARS);
            $username = filter_input(INPUT_POST, 'username',
                FILTER_SANITIZE_FULL_SPECIAL_CHARS);
            $email = filter_input(INPUT_POST, 'email',
                FILTER_SANITIZE_EMAIL);

            $user = db_create_user($name, $username, $email, $password_hash);
            if (!$user) {
                $registerMessage = 'Creating the user failed';
                return;
            } else {
                $_SESSION['id'] = $user['id_user'];
                $_SESSION['username'] = $user['username'];
                $_SESSION['identifier'] = $user['identifier'];
                $_SESSION['name'] = $user['name'];
                $_SESSION['is_admin'] = $user['is_admin'];
                header('Location: index.php');
            }
        }
    }
}

include_once './include/header.php' ?>
    <div class="header-center top-padding">
        <a href="index.php" class="logo">
            <img src="./assets/img/logo.svg" alt="Logo"/>
            <p>EpicTunes</p>
        </a>
    </div>
    <div class="div-center">
        <h1>Create Account</h1>
        <form method="post" class="login-form">
            <?php if (isset($registrationMessage)) { ?>
                <strong class="login-error">
                    <?= $registrationMessage ?>
                </strong>
            <?php } ?>
            <p>Please enter your data to create an account</p>
            <p>
                <label>
                    <input onkeyup="autoFillUsername('name-field', 'username-field')" id="name-field" type="text"
                           name="name" placeholder="Name"
                           required/>
                </label>
            </p>
            <p>
                <label>
                    <input type="text" name="username" id="username-field" placeholder="Pick a username" required/>
                </label>
            </p>
            <p>
                <label>
                    <input type="email" name="email" placeholder="E-mail" required/>
                </label>
            </p>
            <p>
                <label>
                    <input type="password" name="password" placeholder="Password" required/>
                </label>
            </p>
            <p>
                <label>
                    <input type="password" name="confirm_password" placeholder="Re-enter the password" required/>
                </label>
            </p>
            <p>
                <label class="fine-print w-40">
                    <input type="checkbox" name="privacy_confirm" required>
                    I have read and agree to the terms and conditions.
                </label>
            </p>
            <input class="margin-top-20" type="submit" value="Create Account"/>
        </form>
        <p class="padding-top-40">Already have an account? Sign in instead</p>
        <a class="action-link" href="login.php">Sign In →</a>
    </div>
<?php include_once './include/footer.php' ?>