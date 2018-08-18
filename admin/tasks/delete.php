<?php
include __DIR__ . '/../../includes/setup.php';
$task_id = (int) $_GET['task_id'];

if ($task_id) {
    $db = DB::connect();
    // delete from tasks table
    $query = "DELETE FROM tasks WHERE id =:id";
    $stmt = $db->prepare($query);
    $stmt->bindValue(':id', $task_id);
    $stmt->execute();

    // delete from replay users table
    $query = 'DELETE FROM replay_users WHERE task_id =:id';
    $stmt = $db->prepare($query);
    $stmt->bindValue(':id', $task_id);
    $stmt->execute();

    // delete from retweet users
    $query = 'DELETE FROM retweets_users WHERE task_id = :id';
    $stmt = $db->prepare($query);
    $stmt->bindValue(':id', $task_id);
    $stmt->execute();

    $_SESSION['task_deleted'] = true;
    header('Location: ' . URL_ROOT . 'admin/tasks/all_tasks.php');
    exit;
}