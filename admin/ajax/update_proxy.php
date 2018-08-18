<?php
include __DIR__ . '/../../includes/setup.php';
$user_id = $_POST['user_id'];
$proxy = $_POST['proxy'];

$db = DB::connect();

$query = "UPDATE users set proxy = :proxy where id = :id";
$stmt = $db->prepare($query);
$stmt->bindValue(':proxy', $proxy);
$stmt->bindValue(':id', $user_id);
return $stmt->execute();