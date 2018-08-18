<?php
use Abraham\TwitterOAuth\TwitterOAuth;

include '../includes/setup.php';
//var_dump($_SESSION);exit;

$db = DB::connect();

$settingsModel = new \MyApp\Models\Setting();
$settings = $settingsModel->get('my_twitter_app');
$twitter = \MyApp\Libs\Helper::getTwInstance();
//$tweets = $twitter->get('statuses/user_timeline', [
//    'screen_name' => 'vskarich',
//    'since_id' => '909808281081847808',
//    'count' => 10,
//]);


$query = "SELECT * FROM favorites_users ORDER BY rand() LIMIT 2";
$stmt = $db->prepare($query);
$stmt->execute();
$retweet_users = $stmt->fetchAll(PDO::FETCH_ASSOC);

if ($retweet_users && count($retweet_users)) {

    foreach ($retweet_users as $user) {
        $query = "SELECT oauth_token, oauth_token_secret FROM users WHERE id = :id";
        $stmt = $db->prepare($query);
        $stmt->bindValue(":id", $user['owner_id']);
        $stmt->execute();
        $user_cred = $stmt->fetch(PDO::FETCH_ASSOC);
        if ($user_cred && count($user_cred)) {

            $get_tweets = $twitter->get('statuses/user_timeline', [
                'screen_name' => $user['screen_name'],
                'since_id'    => $user['last_status_id'],
                'count' => 5
            ]);

            // update user last_status_id in retweets_users in database for the next time to count
            if (count($get_tweets))
            {

                $user_info  = $twitter->get('users/show', array('screen_name' => $user['screen_name'])
                );

                $tweetApp = new \MyApp\Controllers\Tweets();
                $tweetApp->makeRetweetFavuser($user_info, 'favorites_users', 'update');

                $twitter_api  = new TwitterOAuth($settings['consumer_key'], $settings['consumer_secret'], $user_cred['oauth_token'], $user_cred['oauth_token_secret']);

                foreach ($get_tweets as $tweet_info) {
                    $retweet = $twitter_api->post('favorites/create', array(
                        'id' => $tweet_info->id_str,
                    ));
                    sleep(5);
                }
            }
        }

    }

}