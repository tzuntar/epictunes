<?php
session_start();
if (!isset($_SESSION['identifier']))
    header('Location: login.php');
if (!isset($_GET['song']) || !isset($_POST['']))
    header('Location: ' . $_SERVER['HTTP_REFERER']);
require_once 'utils/queries.php';
$comment = filter_input(INPUT_POST, 'comment', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
$songId = filter_input(INPUT_GET, 'song', FILTER_SANITIZE_NUMBER_INT);
db_store_comment_for_song($comment, $_SESSION['id'], $songId);
header('Location: ' . $_SERVER['HTTP_REFERER']);