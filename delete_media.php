<?php
if (isset($_GET['fname'])) {
    $file_name =  $_GET['fname'];
    if (file_exists(__DIR__ . '/media/' . $file_name)) {
        unlink(__DIR__ . '/media/' . $file_name);
        header('Location: ./media.php');
    }
}
