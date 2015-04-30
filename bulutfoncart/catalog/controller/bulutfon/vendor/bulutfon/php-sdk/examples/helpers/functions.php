<?php
    function getAccessTokenFromSession() {
        $token = new \League\OAuth2\Client\Token\AccessToken([
            'access_token' => $_SESSION["accessToken"],
            'refresh_token' => $_SESSION["refreshToken"],
            'expires' => $_SESSION["expires"],
            'uid' => $_SESSION["uid"],
        ]);

        return $token;
    }