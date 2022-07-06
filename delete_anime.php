<?php
session_start();
require_once 'inc/functions.php';

if (!isset($_SESSION['userId'])) {
    header('Location: index.php?logged_in=0');
}

$id = $_GET['entryId'];
$userId = $_GET['userId'];

require_once 'inc/header.php';

if (delete_userAnime($_SESSION['accessToken'], $id)) {
    echo "<p class='success'>Anime successfully deleted.</p>";
} else {
    echo "<p class='warning'>Deleting anime failed.</p>";
}
echo "<a href='animelist.php'>Go back to your animelist.</a>";
