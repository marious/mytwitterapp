<?php
require __DIR__ . '/includes/setup.php';
use Abraham\TwitterOAuth\TwitterOAuth;

$settings = new \MyApp\Models\Setting();
$twitterKeys = $settings->get('my_twitter_app');

//var_dump($_SESSION);
//exit;

if (isset($_REQUEST['oauth_verifier'], $_REQUEST['oauth_token'])) {
    $request_token = [];
    $request_token['oauth_token'] = $_SESSION['oauth_token'];
    $request_token['oauth_token_secret'] = $_SESSION['oauth_token_secret'];
    $connection = new TwitterOAuth($twitterKeys['consumer_key'], $twitterKeys['consumer_secret'], $request_token['oauth_token'], $request_token['oauth_token_secret']);
    $access_token = $connection->oauth("oauth/access_token", array("oauth_verifier" =>      $_REQUEST['oauth_verifier']));
    $_SESSION['access_token'] = $access_token;

    $access_token = $_SESSION['access_token'];
    $connection = new TwitterOAuth($twitterKeys['consumer_key'], $twitterKeys['consumer_secret'], $access_token['oauth_token'], $access_token['oauth_token_secret']);
    $user = $connection->get("account/verify_credentials");

    // check if user already exist
    $tweets = new \MyApp\Controllers\Tweets();
    if ($tweets->check_user_exists($user->id_str) == true) {
        $_SESSION['error'] = 'This user already exists';
        header('Location: ' . URL_ROOT . 'admin/index.php');
        exit;
    }



    $data = [];
    $data['id']                     = $user->id_str;
    $data['screen_name']            = $user->screen_name;
    $data['profile_image_url']      = $user->profile_image_url;
    $data['friends_count']          = $user->friends_count;
    $data['followers_count']        = $user->followers_count;
    $data['statuses_count']         = $user->statuses_count;
    $data['favourites_count']       = $user->favourites_count;
    $data['oauth_token']            = $_SESSION['access_token']['oauth_token'];
    $data['oauth_token_secret']     = $_SESSION['access_token']['oauth_token_secret'];
    $data['oauth_verifier']         = $_REQUEST['oauth_verifier'];
    $data['created_at']             = date('Y-m-d H:i:s', strtotime($user->created_at));
    $data['name']                   = $user->name;

    $userModel = new \MyApp\Models\User();
    $user = $userModel->getById($user->id_str);
    if ($user) {
        $userModel->update($data);
        $userModel->update_regular_user_twitter_id($_SESSION['regular_user_id'], $data['id']);
        $_SESSION['user_id'] = $data['id'];
        $_SESSION['twitter_logged'] = true;
        $_SESSION['regular_user_twitter_id'] = $data['id'];
    } else {
        $userModel->create($data);
        $userModel->update_regular_user_twitter_id($_SESSION['regular_user_id'], $data['id']);
        $crone = new \MyApp\Libs\Cron();
        $crone->create_cron_tables($data['id']);
        $_SESSION['user_id'] = $data['id'];
        $_SESSION['twitter_logged'] = true;
        $_SESSION['regular_user_twitter_id'] = $data['id'];
    }

    // redirect user back to index page
    $_SESSION['success'] = 'New User added Successfully';
    header('Location: ' . URL_ROOT . 'admin/index.php');
    exit;

}