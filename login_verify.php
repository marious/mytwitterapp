<?php
require __DIR__ . '/includes/setup.php';

$user_model = new \MyApp\Models\User();
$login_user = $user_model->check_regular_user_login($_POST);

if ($login_user) {
    $_SESSION['logged_user'] = true;
    $_SESSION['regular_user_id'] = $login_user['id'];
    $_SESSION['regular_username'] = $login_user['username'];
    $_SESSION['regular_user_twitter_id'] = $login_user['twitter_id'];
    header('Location: ' . URL_ROOT . '/admin');
    exit;
}

header('Location: login.php?login=failed');
