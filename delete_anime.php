<?php
session_start();
require_once 'inc/functions.php';

// Check if the user has logged in, and redirect the user to the homepage if he/she hasn't.
if (!isset($_SESSION['userId'])) {
    header('Location: index.php?logged_in=0');
}

// Get current list entry id.
$id = $_GET['entryId'];

require_once 'inc/header.php';

if (delete_userAnime($_SESSION['accessToken'], $id)) {
    echo "<p class='success'>Anime successfully deleted.</p>";
} else {
    echo "<p class='warning'>Deleting anime failed.</p>";
}
echo "<a href='animelist.php'>Go back to your animelist.</a>";

require_once 'inc/footer.php';
