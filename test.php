<?php
session_start();
$data = test($_SESSION['accessToken'], 9253, 9);
var_dump($data);