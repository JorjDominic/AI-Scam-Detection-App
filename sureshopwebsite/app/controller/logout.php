<?php
session_start();
$_SESSION = [];
session_destroy();
header("Location: /php/sureshopwebsite/index.php");
exit;
