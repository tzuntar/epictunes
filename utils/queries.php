<?php
require_once $_SERVER['DOCUMENT_ROOT'] . '/include/database.php';

function db_get_user(string $username) {
    global $DB;
    $reqUser = $DB->prepare('SELECT u.* FROM users u
        WHERE (u.username = ?)');
    $reqUser->execute([$username]);
    if ($reqUser->rowCount() != 1)
        return false;   // user not found / invalid
    return $reqUser->fetch();
}
