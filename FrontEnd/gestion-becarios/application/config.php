<?php
require_once 'google-api/vendor/autoload.php';

$gClient = new Google_Client();
$gClient->setClientId("271064182383-uuhn8nhq71p91hmgpa5p8pgh7isjm46o.apps.googleusercontent.com");
$gClient->setClientSecret("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");
$gClient->setApplicationName("Gestor de Becarios UPB");
$gClient->setRedirectUri("http://localhost/gestion-becarios2/controller.php");
$gClient->addScope("https://www.googleapis.com/auth/plus.login https://www.googleapis.com/auth/userinfo.email");

// login URL
$login_url = $gClient->createAuthUrl();
