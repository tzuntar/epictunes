<?php
session_start();
if (!isset($_SESSION['id']) || !$_SESSION['is_admin'])
    header('Location: index.php');
if (!isset($_GET['id']))
    header('Location: admin_users.php');
require_once 'utils/queries.php';
Song::delete_comment($_GET['id']);
header('Location: ' . $_SERVER['HTTP_REFERER']);