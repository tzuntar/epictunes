<?php
session_start();
if (!isset($_SESSION['username']))
    header('Location: login.php');
require_once 'include/database.php';
