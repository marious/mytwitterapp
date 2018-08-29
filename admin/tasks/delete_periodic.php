<?php
include __DIR__ . '/../../includes/setup.php';
$db = DB::connect();
$task_id = $_GET['id'];
$query = "DELETE FROM periodic_task WHERE id = :task_id";
$stmt = $db->prepare($query);
$stmt->bindValue(':task_id', $task_id);
$stmt->execute();
$_SESSION['success'] = 'Task Delete Successfully';
header('Location: ' . URL_ROOT . 'admin/periodic_tweets.php');