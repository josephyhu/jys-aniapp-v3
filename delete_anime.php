<?php
session_start();
require_once 'inc/functions.php';

$id = $_GET['entryId'];
$userId = $_GET['userId'];

require_once 'inc/header.php';

if (delete_userAnime($_SESSION['accessToken'], $id)) {
    echo "<p class='success'>Anime successfully deleted.</p>";
    sleep(3);
    header('Location: animelist.php');
} else {
    echo "<p class='warning'>Deleting anime failed.</p>";
    sleep(3);
    header('Location: animelist.php');
}
