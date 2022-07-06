<?php
session_start();
require_once 'inc/functions.php';

if (!isset($_SESSION['userId'])) {
    header('Location: index.php?logged_in=0');
}

$id = $_GET['entryId'];
$userId = $_GET['userId'];

require_once 'inc/header.php';

if (delete_userManga($_SESSION['accessToken'], $id)) {
    echo "<p class='success'>Manga successfully deleted.</p>";
} else {
    echo "<p class='warning'>Deleting manga failed.</p>";
}

