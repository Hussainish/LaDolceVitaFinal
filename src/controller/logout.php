<?php
//this handles log out. destroys session and redirects back to home page
session_destroy();
header("Location: index.php?page=homepage");
exit;
?>