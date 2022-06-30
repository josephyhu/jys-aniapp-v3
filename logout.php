<?php
unset($_SESSION['accessToken']);
unset($_SESSION['userId']);
unset($_SESSION['username']);
session_destroy();
header('Location: index.php?logged_out=1');
