<?php
require 'config.php';
require 'vendor/autoload.php';

use League\Oauth2\Client\Provider\GenericProvider;

$scopes = array(
    'account.read',
    'list.read',
    'list.write',
    'subscriber.read',
    'subscriber.write',
    'email.read',
    'email.write',
    'subscriber.read-extended',
    'landing-page.read'
);

// Create a OAuth2 client configured to use OAuth for authentication
$provider = new \League\OAuth2\Client\Provider\GenericProvider([
    'clientId' => $clientId,
    'clientSecret' => $clientSecret,
    'redirectUri' => $redirectUri,
    'scopes' => $scopes,
    'scopeSeparator' => ' ',
    'urlAuthorize' => OAUTH_URL . 'authorize',
    'urlAccessToken' => OAUTH_URL . 'token',
    'urlResourceOwnerDetails' => 'https://api.aweber.com/1.0/accounts'
]);
