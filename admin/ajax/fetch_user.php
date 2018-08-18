<?php
include __DIR__ . '/../../includes/setup.php';

$user_id = $_POST['user_id'];
$db = DB::connect();
$query = "SELECT users.proxy FROM users WHERE id = :id";
$stmt = $db->prepare($query);
$stmt->bindValue(':id', $user_id);
$stmt->execute();
$row = $stmt->fetch(PDO::FETCH_ASSOC);
echo json_encode($row);