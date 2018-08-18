<?php
require __DIR__ . '/../includes/setup.php';
$db = DB::connect();

if (isset($_POST['tweet_id'])) {
    $output = [];
    $stmt = $db->prepare('SELECT * FROM scheduled_tweets WHERE id = :tweet_id');
    $stmt->bindValue(':tweet_id', $_POST['tweet_id']);
    $stmt->execute();
    $row = $stmt->fetch(PDO::FETCH_ASSOC);
    $output['time_to_post'] = $row['time_to_post'];
    $output['tweet_content'] = $row['tweet_content'];
    $output['tweet_media'] = $row['tweet_media'];
    $output['owner_id'] = $row['owner_id'];
    $output['id'] = $row['id'];
    echo json_encode($output);
}