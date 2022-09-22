<?php
session_start();
require_once 'vendor/autoload.php';

$clientId = "243495466457-lh99s3dsp2m8givn1uhd32r8i6qf4t64.apps.googleusercontent.com";
$clientSecret = "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";
$redirectUri = "https://epictunes.tobija-zuntar.eu/redirect.php";

$client = new Google_Client();
$client->setClientId($clientId);
$client->setClientSecret($clientSecret);
$client->setRedirectUri($redirectUri);
$client->addScope('email');
$client->addScope('profile');

if (isset($_GET['code'])) { // code from Google OAuth Flow
    $token = $client->fetchAccessTokenWithAuthCode($_GET['code']);
    $client->setAccessToken($token['access_token']);

    $google_oauth = new Google_Service_Oauth2($client);
    $google_account_info = $google_oauth->userinfo->get();
    $email = $google_account_info->email;
    $name = $google_account_info->name;
    $uid = $google_account_info->id;
    $provider = 'google';

    require_once 'utils/queries.php';
    $user = db_get_user_google_oauth($uid, $name, $email);
    if ($user) {
        $_SESSION['id'] = $user['id_user'];
        $_SESSION['username'] = $user['username'];
        $_SESSION['identifier'] = $user['identifier'];
        $_SESSION['name'] = $user['name'];
        $_SESSION['is_admin'] = $user['is_admin'];
        if (isset($user['profile_pic_url']))
            $_SESSION['profile_pic'] = $user['profile_pic_url'];
        header('Location: index.php');
    }
} else header('Location: ' . $client->createAuthUrl());