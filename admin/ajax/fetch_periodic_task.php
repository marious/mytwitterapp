<?php
include __DIR__ . '/../../includes/setup.php';
$db = DB::connect();
$task_id = $_POST['task_id'];
$query = "SELECT * FROM periodic_task WHERE id = :task_id";
$stmt = $db->prepare($query);
$stmt->bindValue(':task_id', $task_id);
$stmt->execute();
$task = $stmt->fetch(PDO::FETCH_ASSOC);
$output = $task;
$output['owner_id'] = unserialize($task['owner_id']);
$output['task_time'] = unserialize($task['task_time']);
echo json_encode($output);