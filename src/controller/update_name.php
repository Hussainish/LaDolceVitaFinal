<?php

require_once __DIR__ . '/../../config/config.php';
require_once __DIR__ . '/../../utils/utils.php';

checkLogin();



if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $_SESSION['username'];
    $currentPssword = $_POST['currentPassword'];
    $Fname = $_POST['Fname'];
    $Lname = $_POST['Lname'];

    
    list($stmt, $result) = executePreparedQuery($mysqli, "SELECT * FROM accounts WHERE Username=?", "s", [$username]);
    $user = $result->fetch_assoc();
    // verify current password - make sure the user entered a valid password 
    if ($user && password_verify($currentPssword,$user['Password'])) {
        // update first name and last name in case of successful password verification
        $updateSql = "UPDATE accounts SET Fname=? , Lname=? WHERE Username=?";
        $stmt = $mysqli->prepare($updateSql);
        $stmt ->bind_param("sss",$Fname,$Lname,$username);
       
        if ( $stmt ->execute()) {
            // update session variables
            $_SESSION['Fname'] = $Fname;
            $_SESSION['Lname'] = $Lname;
            redirectWithMessage("index.php?page=profile", "Name Updated Successfully!");
        } else {
            redirectWithMessage("index.php?page=profile", "Error updating your name. Please try again.", "error");
        }
    } else {
        redirectWithMessage("index.php?page=profile", "Incorrect current password.", "error");
    }

    $stmt ->close();
    
    $mysqli ->close();
}
?>