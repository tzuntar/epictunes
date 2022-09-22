<?php
session_start();
$document_title = 'Login';
$noflex = true;

require_once $_SERVER['DOCUMENT_ROOT'] . '/utils/queries.php';

if (isset($_POST['username'])) {
    $username = filter_input(INPUT_POST, 'username',
        FILTER_SANITIZE_FULL_SPECIAL_CHARS);
    $password = $_POST['password'];
    if (empty($username) or empty($password))
        $loginMessage = 'Please enter both your username and your password';
    else {
        $user = db_get_user($username);
        if ($user && password_verify($password, $user['password'])) {
            $_SESSION['id'] = $user['id_user'];
            $_SESSION['username'] = $user['username'];
            $_SESSION['identifier'] = $user['identifier'];
            $_SESSION['name'] = $user['name'];
            $_SESSION['is_admin'] = $user['is_admin'];
            if (isset($user['profile_pic_url']))
                $_SESSION['profile_pic'] = $user['profile_pic_url'];
            header('Location: index.php');
        } else $loginMessage = 'Incorrect username or password';
    }
}


include_once './include/header.php' ?>
    <div class="header-center top-padding">
        <a href="index.php" class="logo">
            <img src="./assets/img/logo.svg" alt="Logo"/>
            <p>EpicTunes</p>
        </a>
    </div>
    <div class="flex-container">
        <div class="flex-child-half">
            <h1>World's Best Music Streaming Platform</h1>
            <p>Listen to millions of songs online on <strong>EpicTunes</strong></p>
        </div>
        <div class="flex-child-half">
            <h1>Sign In</h1>
            <form method="post" class="login-form">
                <?php if (isset($loginMessage)) { ?>
                    <strong class="login-error">
                        <?= $loginMessage ?>
                    </strong>
                <?php } ?>
                <p>Existing user? Please enter your login details to continue</p>
                <p>
                    <label>
                        <input type="text" name="username" placeholder="Username" required/>
                    </label>
                </p>
                <p>
                    <label>
                        <input type="password" name="password" placeholder="Password" required/>
                    </label>
                </p>
                <input class="margin-top-20" type="submit" value="Sign In"/>
            </form>
            <p class="padding-top-40">Don't have an account yet? Create a free account today</p>
            <a class="action-link" href="register.php">Create Account →</a>
        </div>
    </div>
<?php include_once './include/footer.php' ?>