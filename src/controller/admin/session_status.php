<?php

// this will check whether a session is started or not, if not it will start a session.
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

// it checks whether a user is logged in, if a user is logged in his/her username should
// be saved in session along with their role - admin or regular user
if (isset($_SESSION['username'])){
    $isLoggedIn = true;
    $userRole =$_SESSION['role'];
}
else{
    //if a user isnt logged in set isloggedin variable to false. and reset user role.
    $isLoggedIn=false;
    $userRole='';
}
?>