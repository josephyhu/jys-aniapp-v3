<?php
session_start();
require_once 'inc/functions.php';

$id = $_GET['entryId'];
$userId = $_GET['userId'];

require_once 'inc/header.php';

if (delete_userManga($_SESSION['accessToken'], $id)) {
    echo "<p class='success'>Manga successfully deleted.</p>";
    sleep(3);
    header('Location: mangalist.php');
} else {
    echo "<p class='warning'>Deleting manga failed.</p>";
    sleep(3);
    header('Location: mangalist.php');
}
