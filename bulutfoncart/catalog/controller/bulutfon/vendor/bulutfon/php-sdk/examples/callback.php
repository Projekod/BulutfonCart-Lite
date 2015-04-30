<?php
session_start();
require '../vendor/autoload.php';
require_once './helpers/variables.php';
require_once './helpers/functions.php';

if (!isset($_GET['code'])) {

    if(isset($_GET['refresh_token'])) {
        $token = getAccessTokenFromSession();
        $refreshToken =  $token->refreshToken;
        $url = $_GET['back'];
        $token = $provider->getAccessToken('refresh_token', ['refresh_token' => $refreshToken]);

        $_SESSION["accessToken"] = $token->accessToken;
        $_SESSION["refreshToken"] = $token->refreshToken;
        $_SESSION["expires"] = $token->expires;
        $_SESSION["uid"] = $token->uid;
        header('Location: '.$url);
    } else {
        echo "Code Doesn't exist";
        exit;
    }

} else {
    $token = $provider->getAccessToken('authorization_code', [
        'code' => $_GET['code']
    ]);

    $_SESSION["accessToken"] = $token->accessToken;
    $_SESSION["refreshToken"] = $token->refreshToken;
    $_SESSION["expires"] = $token->expires;
    $_SESSION["uid"] = $token->uid;

    header('Location: index.php');

}