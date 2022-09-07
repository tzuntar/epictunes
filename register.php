<?php
session_start();
$document_title = 'Create Account';

require_once $_SERVER['DOCUMENT_ROOT'] . '/utils/queries.php';

if (isset($_POST['username'])) {
    $username = filter_input(INPUT_POST, 'username',
        FILTER_SANITIZE_FULL_SPECIAL_CHARS);
    $password = $_POST['password'];
    if (empty($username) or empty($password))
        $registrationMessage = 'Please enter both your username and your password';
    $user = db_get_user($username);
    if (!$user) $registrationMessage = 'Incorrect username or password';

    if (password_verify($password, $user['password'])) {
        $_SESSION['id'] = $user['id_user'];
        $_SESSION['username'] = $user['username'];
        $_SESSION['identifier'] = $user['identifier'];
        $_SESSION['name'] = $user['name'];
        $_SESSION['is_admin'] = $user['is_admin'];
        header('Location: index.php');
    } else $registrationMessage = 'Incorrect username or password';
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
                    <input type="checkbox" name="dont_register">
                    I agree that I haven't read this text and<br/>therefore do not want to register.
                </label>
            </p>
            <input class="margin-top-20" type="submit" value="Create Account"/>
        </form>
        <p class="padding-top-40">Already have an account? Sign in instead</p>
        <a class="action-link" href="login.php">Sign In →</a>
    </div>
<?php include_once './include/footer.php' ?>