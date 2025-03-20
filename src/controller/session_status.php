<?php
// checks if a session is started, if not start a new session
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

//if a user is logged in , their username is saved in session, in case it isnt that means that
// there is no user logged in, thus a login is required.
if (isset($_SESSION['username'])){
    $isLoggedIn = true;
    $userRole =$_SESSION['role'];
}
else{
    $isLoggedIn=false;
    $userRole='';
}
?>