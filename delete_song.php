<?php
session_start();
if (!isset($_SESSION['identifier']))
    header('Location: login.php');
if (!isset($_GET['id']))
    header('Location: ' . $_SERVER['HTTP_REFERER']);
require_once 'utils/queries.php';
$song = Song::get($_GET['id']);
if ($_SESSION['is_admin'] || $song->check_ownership($_SESSION['id']))
    $song->delete();
header('Location: ' . $_SERVER['HTTP_REFERER']);