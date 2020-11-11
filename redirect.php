<?php
    require 'init.php';

    $code = $_GET['code'];
    $token = $provider->getAccessToken('authorization_code', [
        'code' => $code
    ]);

    $accessToken = $token->getToken();
    $refreshToken = $token->getRefreshToken();
    
    $fp = fopen('credentials.ini', 'wt');
    fwrite($fp,
        "accessToken = {$accessToken}
        refreshToken = {$refreshToken}");
    fclose($fp);
    chmod('credentials.ini', 0600);

    header("Location: form.php");
    exit();
?>