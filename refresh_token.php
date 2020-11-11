<?php
    require 'init.php';
    
    echo '<a href="form.php" class="btn btn-primary">Go to back</a><br><br>';

    $credentials = parse_ini_file('credentials.ini', true);
    if(sizeof($credentials) == 0 ||
        !array_key_exists('accessToken', $credentials) ||
        !array_key_exists('refreshToken', $credentials)) {
        header("Location: login.php");
        exit();
    }

    $client = new GuzzleHttp\Client();
    $response = $client->post(
        TOKEN_URL, [
            'auth' => [
                $clientId, $clientSecret
            ],
            'json' => [
                'grant_type' => 'refresh_token',
                'refresh_token' => $credentials['refreshToken']
            ]
        ]
    );
    $body = $response->getBody();
    $newCreds = json_decode($body, true);
    $accessToken = $newCreds['access_token'];
    $refreshToken = $newCreds['refresh_token'];

    $fp = fopen('credentials.ini', 'wt');
    fwrite($fp,
        "accessToken = {$accessToken}
        refreshToken = {$refreshToken}");
    fclose($fp);
    chmod('credentials.ini', 0600);

    print_r('Token refreshed');
?>
