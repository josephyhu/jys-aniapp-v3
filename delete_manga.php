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

if (delete_userManga($_SESSION['accessToken'], $id)) {
    echo "<p class='success'>Manga successfully deleted.</p>";
} else {
    echo "<p class='warning'>Deleting manga failed.</p>";
}
echo "<a href='mangalist.php'>Go back to your mangalist.</a>";

require_once 'inc/footer.php';
