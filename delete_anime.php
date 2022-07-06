<?php
session_start();
require_once 'inc/functions.php';

$id = $_GET['entryId'];
$userId = $_GET['userId'];

require_once 'inc/header.php';

try {
    $data = delete_userAnime($_SESSION['accessToken'], $id);
    echo $data['deleted'];
} catch (Exception $e) {
    echo $e->getMessage();
}