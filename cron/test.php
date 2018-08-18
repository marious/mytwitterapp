<?php 
$pdo = new PDO("mysql:host=localhost;dbname=mtwitterapp", "root", "2634231");
$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
$stmt = $pdo->prepare("INSERT INTO tasks (task_name, target_twitter_id) VALUES ('test', 'test')");
$result = $stmt->execute();

