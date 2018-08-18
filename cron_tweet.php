<?php
use Abraham\TwitterOAuth\TwitterOAuth;

require __DIR__ . '/../includes/setup.php';

$db = DB::connect();
$cron = new \MyApp\Libs\Cron();

$settingsModel = new \MyApp\Models\Setting();
$ap_creds = $settingsModel->get('my_twitter_app');

$query = "SELECT * FROM users ORDER BY (followers_count + friends_count) ASC";
$stmt = $db->prepare($query);
$stmt->execute();
$users = $stmt->fetchAll(PDO::FETCH_ASSOC);

if (count($users)) {
    foreach ($users as $u_data) {
        $cron->set_user_id($u_data['id']);
        $ct = date('Y-m-d H:i:s');      // current time
        $query = "SELECT * FROM scheduled_tweets 
              WHERE owner_id = :owner_id 
              AND time_to_post != '0000-00-00 00:00:00' ORDER BY time_to_post ASC LIMIT 5";
        $stmt = $db->prepare($query);
        $stmt->bindValue(':owner_id', $u_data['id']);
        $stmt->execute();
        $tweets = $stmt->fetchAll(PDO::FETCH_ASSOC);

        if (count($tweets)) {

            $connection = new TwitterOAuth($ap_creds['consumer_key'], $ap_creds['consumer_secret'],                             $u_data['oauth_token'], $u_data['oauth_token_secret']);
            $connection->setTimeouts(30, 45);

            foreach ($tweets as $tweet) {
                if (strtotime($tweet['time_to_post']) <= strtotime($ct)) {
                    // Tweet
                    $schedule_tweet_id = $tweet['id'];
                    include __DIR__ . '/send_cron_tweets.php';
                    sleep(4);
                }
            }
        }
    }
}


//while ($u_data = $stmt->fetch(PDO::FETCH_ASSOC)) {
//    $connection = new TwitterOAuth($ap_creds['consumer_key'], $ap_creds['consumer_secret'],                             $u_data['oauth_token'], $u_data['oauth_token_secret']);
//    $connection->setTimeouts(30, 45);
//    $cron->set_user_id($u_data['id']);
//    $ct = date('Y-m-d H:i:s');      // current time
//    $query = "SELECT * FROM scheduled_tweets
//              WHERE owner_id = :owner_id
//              AND time_to_post != '0000-00-00 00:00:00' ORDER BY time_to_post ASC";
//    $stmt = $db->prepare($query);
//    $stmt->bindValue(':owner_id', $u_data['']);
//    $stmt->execute();
//    $tweets = [];
//    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
//        if (strtotime($row['time_to_post']) <= strtotime($ct)) {
//            $tweets[] = $row;
//            // Tweet
//            $schedule_tweet_id = $row['id'];
//            include __DIR__ . '/send_cron_tweets.php';
//            sleep(5);
//        }
//    }
//
//
//
//}
