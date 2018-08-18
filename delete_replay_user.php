<?php
require __DIR__ . '/includes/setup.php';
$db = DB::connect();

$id = (int) $_GET['id'];
if ($id) {
    $query = "DELETE FROM replay_users WHERE id = :id";
    $stmt = $db->prepare($query);
    $stmt->bindValue(':id', $id);
    if ($stmt->execute()) {
        header('Location: ' . URL_ROOT . '/tweets/replay/index.php');
    }
    header('Location: ' . URL_ROOT . '/tweets/replay/index.php');
}
header('Location: ' . URL_ROOT . '/tweets/replay/index.php');