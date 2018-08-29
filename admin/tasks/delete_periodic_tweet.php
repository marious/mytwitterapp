<?php
include __DIR__ . '/../../includes/setup.php';
$db = DB::connect();
$tweet_id = $_GET['id'];
$query = "DELETE FROM periodic_tweets WHERE id = :tweet_id";
$stmt = $db->prepare($query);
$stmt->bindValue(':tweet_id', $tweet_id);
$stmt->execute();
$_SESSION['success'] = 'Tweet Delete Successfully';
header('Location: ' . URL_ROOT . 'admin/periodic_tweets.php');