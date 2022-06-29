<?php
unset($code);
unset($data);
unset($logged_in);
unset($logged_out);
session_destroy();
header('Location: index.php?logged_out=1');
