<?php
include __DIR__ . '/../../includes/setup.php';
$db = DB::connect();
$tweet = trim($_POST['periodic_tweet']);
$action = $_POST['action'];
if ($action == 'update') {
    $id = $_POST['tweet_id'];
    $query = 'UPDATE periodic_tweets SET tweet = :tweet WHERE id = :id';
} else {
    $query = "INSERT INTO periodic_tweets (tweet) VALUES (:tweet)";
}
$stmt = $db->prepare($query);
if ($action == 'update') {
    $stmt->bindValue(':id', $id);
}
$stmt->bindValue(':tweet', $tweet);
return $stmt->execute();
