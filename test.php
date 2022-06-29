<?php
session_start();
require_once 'inc/functions.php';
try {
    $data = test($_SESSION['accessToken'], 9253, 9);
} catch (Exception $e) {
    $e->getMessage();
}
var_dump($data);