<?php
session_start();
if (!isset($_SESSION['identifier']) || !isset($_GET['id']))
    exit;
require_once 'queries.php';
User::dismiss_notification($_GET['id']);