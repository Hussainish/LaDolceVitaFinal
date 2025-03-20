<?php

//require common functions from utils.php
// require database connection from config.php
require_once __DIR__ . '/../../config/config.php';
require_once __DIR__ . '/../../utils/utils.php';

// handle signup logic
if($_SERVER["REQUEST_METHOD"]=="POST"){
    //makes sure the user agreed to the terms and conditions
    if (isset($_POST['terms'])){
        //get the form input fields data
        $Fname=$_POST['Fname'];
        $Lname=$_POST['Lname'];
        $username = $_POST['username'];
        $email = $_POST['email'];
        $password = $_POST['password'];

        //validates that the email address entered is valid
        if(!validateEmail($email)){  
            $error = "Invalid email format.";
            header("Location: index.php?page=auth&signup_error=" . urlencode($error));
            exit;
        }

        // validates that the password is valid and follows the pattern - 
        // the pattern is 1 upper case 1 digit and 6 characters long minimum
        if(!preg_match("/^(?=.*[A-Z])(?=.*\d).{6,}$/", $password)){
            $error = "Password must be at least 6 characters long, include 1 uppercase letter, and 1 number.";
            header("Location: index.php?page=auth&signup_error=" . urlencode($error));
            exit;
        }
        list($stmt, $result) = executePreparedQuery($mysqli, "SELECT * FROM accounts WHERE Username=? OR Email=?", "ss", [$username,$email]);

        //makes sure the user is a new user and the email and username dont exist 
        if($result->num_rows>0){
            $error = "Username or email already exists. Try Logging In instead.";
            header("Location: index.php?page=auth&signup_error=" . urlencode($error));
            exit;
        }
        else{
            //hash the password for security
            $hashed_pwd = password_hash($password, PASSWORD_BCRYPT);

            //after a successful signup add the user data to the database
            $sql="INSERT INTO accounts (Fname, Lname, Username, Email, Password, Role) VALUES (?, ?, ?, ?, ?, 'user')";
            $stmt = $mysqli->prepare($sql);
            $stmt->bind_param("sssss",$Fname,$Lname,$username,$email,$hashed_pwd);
            
            
            if ($stmt->execute()) {
                //and finally start session and store user data in the session then redirect to home page
                session_start();
                $_SESSION['username'] = htmlspecialchars($username, ENT_QUOTES, 'UTF-8');
                $_SESSION['role']='user';
                $_SESSION['isLoggedIn'] = true;
                $_SESSION['Fname']=htmlspecialchars($Fname, ENT_QUOTES, 'UTF-8');
                $_SESSION['Lname']=htmlspecialchars($Lname, ENT_QUOTES, 'UTF-8');
                $_SESSION['Email']=htmlspecialchars($email, ENT_QUOTES, 'UTF-8');
                header("Location: index.php?page=homepage");
            } else {
                $error = "Error: " .mysqli_error($mysqli);
                header("Location: index.php?page=auth&signup_error=" . urlencode($error));
            }
        }
    }
    $stmt->close();
    $mysqli->close();
}
?>