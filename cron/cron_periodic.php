<?php
use Abraham\TwitterOAuth\TwitterOAuth;
include '../includes/setup.php';
$db = DB::connect();

$settingsModel = new \MyApp\Models\Setting();
$settings = $settingsModel->get('my_twitter_app');

$q1 = 'SELECT * from periodic_tweets';
$stmt = $db->query($q1);
$tweets = $stmt->fetchAll(PDO::FETCH_ASSOC);

$q2 = 'SELECT * FROM periodic_task';
$stmt = $db->query($q2);
$tasks = $stmt->fetchAll(PDO::FETCH_ASSOC);
//var_dump($tasks);exit;


if ($tasks && count($tasks))
{
    foreach ($tasks as $task)
    {
        $task_run_time = unserialize($task['task_time']);
        $current_time = strtotime(date("g:i A"));


        if (strtotime($task_run_time['start_time_1']) <= $current_time && strtotime($task_run_time['end_time_1']) > $current_time) {
            $users = unserialize($task['owner_id']);
            make_periodic_tweet($task);
        }
    }
}


function make_periodic_tweet($task)
{
    $users = unserialize($task['owner_id']);
    $settingsModel = new \MyApp\Models\Setting();
    $ap_creds = $settingsModel->get('my_twitter_app');
    $db = DB::connect();
    // get the message to send
    $tweet = get_tweet_message($task);
    $userModel = new \MyApp\Models\User();

    $interval = $task['periodic_time'] * 60;
    $last_send = strtotime($task['last_send']);

//    var_dump($interval + $last_send);
//    var_dump(time());

    if ($interval + $last_send <= time()) {
        foreach ($users as $user) {
        $user_cred = $userModel->getById($user);
        $connection = new TwitterOAuth($ap_creds['consumer_key'], $ap_creds['consumer_secret'], $user_cred['oauth_token'], $user_cred['oauth_token_secret']);
        $connection->setTimeouts(30, 45);
        $content = $connection->post('statuses/update', ['status' => $tweet['tweet']]);
        }
        // update task task_time and last send
        update_task_tweet_last_send($task['id'], $tweet['id']);
    }

}


function get_tweet_message($task)
{
    $db = DB::connect();
    if ($task['tweet_id'] == 0)
    {
        $query = "SELECT tweet, id FROM periodic_tweets LIMIT 1";
        $stmt = $db->query($query);
        $tweet = $stmt->fetch(PDO::FETCH_ASSOC);
    }
    else
    {
        $query = "SELECT tweet, id FROM periodic_tweets WHERE id <> {$task['tweet_id']} LIMIT 1";
        $stmt = $db->query($query);
        $tweet = $stmt->fetch(PDO::FETCH_ASSOC);
        if (empty($tweet))
        {
            $query = 'SELECT tweet, id FROM periodic_tweets LIMIT 1';
            $stmt = $db->query($query);
            $tweet = $stmt->fetch(PDO::FETCH_ASSOC);
        }
    }

    return $tweet;

}

function update_task_tweet_last_send($task_id, $tweet_id)
{
    $db = DB::connect();
    $query = "UPDATE periodic_task SET tweet_id = :tweet_id, last_send = :last_send WHERE id = :task_id";
    $stmt = $db->prepare($query);
    $stmt->bindValue(':tweet_id', $tweet_id);
    $stmt->bindValue(':last_send', date('Y-m-d H:i:s', time()));
    $stmt->bindValue(':task_id', $task_id);
    return $stmt->execute();
}