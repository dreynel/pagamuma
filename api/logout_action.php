<?php
// c:\xampp\htdocs\pagamuma2\api\logout_action.php
session_start();
session_unset();
session_destroy();
header("Location: ../login.php");
exit;
?>
