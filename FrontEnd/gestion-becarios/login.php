<?php
require_once 'vendor/autoload.php';

$clientID = '271064182383-jiqq967o31578qu7op4v3o1hp8ippjf6.apps.googleusercontent.com';
$clientSecret = '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';
$redirectUrl = 'http://localhost/gestion-becarios/login.php';

$client = new Google_Client();
$client -> setClientId($clientID);
$client -> setClientSecret($clientSecret);
$client -> setRedirectUri($redirectUrl);
$client -> addScope('profile');
$client ->addScope('email');

if(isset($_GET['code'])){
    $token = $client-> fetchAccessTokenWithAuthCode($_GET['code']);
    $client -> setAccessToken($token);

    //Getting User Profile
    $gauth = new Google_Service_Oauth2($client);
    $google_info = $gauth -> userinfo -> get();
    $email = $google_info -> email;
    $name = $google_info -> name;
    echo "Welcome " . $name . " You are registered with email " . $email;
}
else
{
    echo"<a href='" . $client -> createAuthUrl() . "'>Login with Google</a>";
}

