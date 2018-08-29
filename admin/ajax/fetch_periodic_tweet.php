<?php
include __DIR__ . '/../../includes/setup.php';
$db = DB::connect();
$tweet_id = $_POST['tweet_id'];
$query = "SELECT * FROM periodic_tweets WHERE id = :tweet_id";
$stmt = $db->prepare($query);
$stmt->bindValue(':tweet_id', $tweet_id);
$stmt->execute();
$tweet = $stmt->fetch(PDO::FETCH_ASSOC);

echo json_encode($tweet);