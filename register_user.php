<?php
require __DIR__ . '/includes/setup.php';

$user_model = new \MyApp\Models\User();

$user_data = [];
$user_data['username'] = trim($_POST['username']);
$user_data['password'] = trim(password_hash($_POST['password'], PASSWORD_DEFAULT));
$user_data['email'] = trim($_POST['email']);

if ($user_model->create_regular_user($user_data)) {
    header('Location: login.php');
}