<?php

require_once 'vendor/autoload.php';

session_start();

// init configuration
$clientID = '328613557058-2vlgubore5g2r1demrrua2o7isvl9n09.apps.googleusercontent.com';
$clientSecret = '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';
$redirectUri = 'http://localhost/YouTube/php-google-login/welcome.php';

// create Client Request to access Google API
$client = new Google_Client();
$client->setClientId($clientID);
$client->setClientSecret($clientSecret);
$client->setRedirectUri($redirectUri);
$client->addScope("email");
$client->addScope("profile");

// Connect to database
$hostname = "localhost";
$username = "root";
$password = "";
$database = "youtube-google-login";

$conn = mysqli_connect($hostname, $username, $password, $database);
