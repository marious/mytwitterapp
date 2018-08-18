<?php
//date_default_timezone_set('Africa/Cairo');
//echo date('Y-m-d H:i:s', strtotime('Sun Oct 01 08:38:24 +0000 2017'));
//require 'includes/setup.php';
//
//$cron = new \MyApp\Libs\Cron();
//var_dump($cron->get_cron_state('follow'));

$db = new PDO('mysql:host=localhost;dbname=twando', 'root', '2634231');
$query = "SELECT twitter_id FROM user_cache";
$stmt = $db->prepare($query);
$stmt->execute();
while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
    var_dump($row);
}