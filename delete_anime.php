<?php
session_start();
require_once 'inc/functions.php';

$id = $_GET['entryId'];
$userId = $_GET['userId'];

require_once 'inc/header.php';

try {
    delete_userAnime($_SESSION['accessToken'], $id);
    echo "<p class='success'>Anime successfully deleted.</p>";
    sleep(3);
    header('Location: animelist.php');
} catch (Exception $e) {
    echo $e->getMessage();
}