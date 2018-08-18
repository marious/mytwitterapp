<?php
use Abraham\TwitterOAuth\TwitterOAuth;

include '../includes/setup.php';
//var_dump($_SESSION);exit;

$db = DB::connect();

$settingsModel = new \MyApp\Models\Setting();
$settings = $settingsModel->get('my_twitter_app');
//$twitter = \MyApp\Libs\Helper::getTwInstance();
//$tweets = $twitter->get('statuses/user_timeline', [
//    'screen_name' => 'vskarich',
//    'since_id' => '909808281081847808',
//    'count' => 10,
//]);

$query = "SELECT * FROM replay_users LIMIT 1";
$stmt = $db->prepare($query);
$stmt->execute();
$replay_users = $stmt->fetchAll(PDO::FETCH_ASSOC);
$replay_users = $replay_users[0];
$tweetApp = new \MyApp\Controllers\Tweets();
$task = $tweetApp->get_task($replay_users['task_id']);

$twApi = \MyApp\Libs\Helper::getTwInstance();
$user_info = $twApi->get('users/show', array('screen_name' => $replay_users['screen_name']));
$last_status_id = $user_info->status->id_str;

if ($last_status_id == $replay_users['last_status_id']) {
    $query = "SELECT * FROM replay_users WHERE last_status_id <> :last_status_id AND task_id = :task_id";
    $stmt = $db->prepare($query);
    $stmt->bindValue(':last_status_id', $last_status_id);
    $stmt->bindValue(':task_id', $replay_users['task_id']);
    $stmt->execute();
    $replay_users = $stmt->fetch(PDO::FETCH_ASSOC);
    if (empty($replay_users)) {
        exit;
    }
}


// check last status id

$task_run_time = unserialize($task['task_time']);
$current_time = strtotime(date("g:i A"));




function make_replay($replay_users)
{
    if ($replay_users && count($replay_users)) {

        $owners_id = $replay_users["owner_id"];
        $tweetApp = new \MyApp\Controllers\Tweets();
        $settingsModel = new \MyApp\Models\Setting();
        $settings = $settingsModel->get('my_twitter_app');
        $db = DB::connect();

//        foreach ($owners_id as $id) {
            $query = "SELECT oauth_token, oauth_token_secret,proxy FROM users WHERE id = :id";
            $stmt = $db->prepare($query);
            $stmt->bindValue(":id", $owners_id);
            $stmt->execute();
            $user_cred = $stmt->fetch(PDO::FETCH_ASSOC);

            if ($user_cred && count($user_cred)) {
//                $twitter = \MyApp\Libs\Helper::getTwInstance();
//                var_dump($twitter);exit;

                $twitter  = new TwitterOAuth($settings['consumer_key'], $settings['consumer_secret'], $user_cred['oauth_token'], $user_cred['oauth_token_secret']);

                if (isset($user_cred['proxy']) && $user_cred['proxy'] != '') {
                    $twitter->setProxy([
                        'CURLOPT_PROXY' => $user_cred['proxy'],
                        'CURLOPT_PROXYUSERPWD' => 'anazas0b:epsJyhTr',
                        'CURLOPT_PROXYPORT' => 4444,
                    ]);
                }




                $get_tweets = $twitter->get('statuses/user_timeline', [
                    'screen_name' => $replay_users['screen_name'],
                    'since_id'    => $replay_users['last_status_id'],
                    'count' => 5
                ]);





                // update user last_status_id in retweets_users in database for the next time to count
                if (count($get_tweets))
                {

                    $user_info  = $twitter->get('users/show', array('screen_name' => $replay_users['screen_name'])
                    );
//                    $twitter_api  = new TwitterOAuth($settings['consumer_key'], $settings['consumer_secret'], $user_cred['oauth_token'], $user_cred['oauth_token_secret']);



                    foreach ($get_tweets as $tweet_info) {

                        $retweet = $twitter->post('statuses/update', array(
                            'in_reply_to_status_id' => $tweet_info->id_str,
                            'status' => "@{$replay_users['screen_name']} {$replay_users['replay_message']}"
                        ));
                        sleep(5);
                    }

                    $tweetApp->makeRetweetReplayUser($user_info, 'replay_users', 'update', $replay_users["owner_id"], $replay_users['task_id']);

                }
            }

//        }

    }
}


if (strtotime($task_run_time['start_time_1']) <= $current_time && strtotime($task_run_time['end_time_1']) > $current_time) {
    make_replay($replay_users);
}

if ($task_run_time['start_time_2'] != '0')
{
    if (strtotime($task_run_time['start_time_2']) <= $current_time && strtotime($task_run_time['end_time_2']) > $current_time) {
        make_replay($replay_users);
    }
}

if ($task_run_time['start_time_3'] != '0') {
   if ( strtotime($task_run_time['start_time_3']) <= $current_time && strtotime($task_run_time['end_time_3']) > $current_time) {
       make_replay($replay_users);
   }
}





