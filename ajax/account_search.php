<?php
include '../includes/setup.php';

if (\MyApp\Libs\Helper::isAjax()) {
    if (isset($_GET['query'])) {
        $q = $_GET['query'];
    }
    $connection = \MyApp\Libs\Helper::getCodeBirdInstance();
    $result =$connection->users_search(['q' => $q, 'count' => 5]);
    echo \MyApp\Libs\Helper::jsonEncode($result);
}
header('Location: ' . URL_ROOT . 'index.php');