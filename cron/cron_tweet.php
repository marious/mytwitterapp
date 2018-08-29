<?php
use Abraham\TwitterOAuth\TwitterOAuth;

require __DIR__ . '/../includes/setup.php';

$db = DB::connect();
$cron = new \MyApp\Libs\Cron();

$settingsModel = new \MyApp\Models\Setting();
$ap_creds = $settingsModel->get('my_twitter_app');

$twitterApp = new \MyApp\Controllers\Tweets();
$user_model = new \MyApp\Models\User();


$query = "SELECT * FROM scheduled_tweets 
              WHERE time_to_post != '0000-00-00 00:00:00' ORDER BY time_to_post ASC LIMIT 5";
$stmt = $db->prepare($query);
$stmt->execute();
$tweets = $stmt->fetchAll(PDO::FETCH_ASSOC);
if ($tweets && count($tweets))
{
    $ct = date('Y-m-d H:i:s');

    foreach ($tweets as $tweet)
    {
        $users = unserialize($tweet['owner_id']);
        foreach ($users as $user)
        {
            $u_data = $user_model->getById($user);
            if ($u_data) {
                $connection = new TwitterOAuth($ap_creds['consumer_key'], $ap_creds['consumer_secret'], $u_data['oauth_token'], $u_data['oauth_token_secret']);
                $connection->setTimeouts(30, 45);
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