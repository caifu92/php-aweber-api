<?php

    require 'init.php';

    echo '<a href="form.php" class="btn btn-primary">Go to back</a><br><br>';

    const BASE_URL  = 'https://api.aweber.com/1.0/';

    $credentials = parse_ini_file('credentials.ini', true);
    if(sizeof($credentials) == 0 ||
        !array_key_exists('accessToken', $credentials) ||
        !array_key_exists('refreshToken', $credentials)) {
        header("Location: login.php");
        exit();
    }

    $firstname  = isset($_POST['firstname']) ? $_POST['firstname'] : '';
    $lastname   = isset($_POST['lastname']) ? $_POST['lastname'] : '';
    $email      = isset($_POST['email']) ? $_POST['email'] : '';
    $customID   = isset($_POST['customID']) ? $_POST['customID'] : '';

    if (!$firstname || !$lastname || !$email || !$customID) {
        print_r('Input values wrong!');
        exit();
    }

    $client = new GuzzleHttp\Client();

    $accountId = '1642792';
    $listId = '5838782';
    $subsUrl = 'https://api.aweber.com/1.0/accounts/' . $accountId . '/lists/' . $listId . '/subscribers';

    // add the subscriber if they are not already on the first list
    $data = array(
        'email' => $email,
        'name'  => $firstname . ' ' . $lastname,
        'custom_fields' => array('customID' => $customID),
        'tags' => array('prospect')
    );

    try {
        $body = $client->post($subsUrl, [
            'json' => $data, 
            'headers' => ['Authorization' => 'Bearer ' . $credentials['accessToken']]
        ]);

        // get the subscriber entry using the Location header from the post request
        $subscriberUrl = $body->getHeader('Location')[0];
        $subscriberResponse = $client->get($subscriberUrl,
            ['headers' => ['Authorization' => 'Bearer ' . $credentials['accessToken']]])->getBody();
        $subscriber = json_decode($subscriberResponse, true);
        echo 'Created Subscriber: ';
        print_r($subscriber);
    } catch (\Exception $e) {
        print_r($e->getMessage());
    }


    // // get all the accounts entries
    // $accounts = getCollection($client, $credentials['accessToken'], BASE_URL . 'accounts');
    // $accountUrl = $accounts[0]['self_link'];

    // // get all the list entries for the first account
    // $listsUrl = $accounts[0]['lists_collection_link'];
    // $lists = getCollection($client, $credentials['accessToken'], $listsUrl);
    
    // $subsUrl = $lists[0]['subscribers_collection_link'];

    // // add the subscriber if they are not already on the first list
    // $data = array(
    //     'email' => $email,
    //     'name'  => $firstname . ' ' . $lastname,
    //     'custom_fields' => array('custom_id' => $customID),
    //     'tags' => array('prospect')
    // );
    // $body = $client->post($subsUrl, [
    //     'json' => $data, 
    //     'headers' => ['Authorization' => 'Bearer ' . $credentials['accessToken']]
    // ]);

    // // get the subscriber entry using the Location header from the post request
    // $subscriberUrl = $body->getHeader('Location')[0];
    // $subscriberResponse = $client->get($subscriberUrl,
    //     ['headers' => ['Authorization' => 'Bearer ' . $credentials['accessToken']]])->getBody();
    // $subscriber = json_decode($subscriberResponse, true);
    // echo 'Created Subscriber: ';
    // print_r($subscriber);

    // function getCollection($client, $accessToken, $url) {
    //     $collection = array();
    //     while (isset($url)) {
    //         $request = $client->get($url,
    //             ['headers' => ['Authorization' => 'Bearer ' . $accessToken]]
    //         );
    //         $body = $request->getBody();
    //         $page = json_decode($body, true);
    //         $collection = array_merge($page['entries'], $collection);
    //         $url = isset($page['next_collection_link']) ? $page['next_collection_link'] : null;
    //     }
    //     return $collection;
    // }
