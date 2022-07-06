<?php
session_start();
require_once 'inc/functions.php';

$id = $_GET['id'];
$userId = $_GET['userId'];

require_once 'inc/header.php';

$data = delete_userAnime($_SESSION['accessToken'], $id);

var_dump($data);

if (delete_userAnime($_SESSION['accessToken'], $id)) {
            echo "<p class='success'>Anime successfully deleted.</p>";
            sleep(3);
            header('Location: animelist.php');
        } else {
            echo "<p class='warning'>Deleting anime failed.</p>";
            sleep(3);
            header('Location: anime.php?userId="' . $userId . '&id=' . $id .  '"');
        }