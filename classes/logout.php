<?php
session_start();
session_destroy();
header('Location: ../login.php'); // Adjusted to navigate up one directory level to reach login.php
exit();
?>
