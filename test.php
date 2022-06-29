<?php
session_start();
require_once 'inc/functions.php';
$data = test($_SESSION['accessToken'], 9253, 9);
var_dump($data);