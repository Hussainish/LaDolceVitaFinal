<?php
require_once __DIR__ . '/../../config/config.php';
require_once __DIR__ . '/../../utils/utils.php';

//once a user logs in a session starts
session_start();


// this handles the login form submission
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $_POST['username'];
    $pwd = $_POST['password'];
    $lockout_time = 5*60;

    //in case of too many login attempts it forces a time out and asks the user to try again later
    //timeout time is set as 5 minutes
    if (!isset($_SESSION['login_attempts'])) {
        $_SESSION['login_attempts'] = 0;
        $_SESSION['last_attempt_time'] = time();
    }
    if (time() - $_SESSION['last_attempt_time'] > $lockout_time) {
        $_SESSION['login_attempts'] = 0;
    }
    if ($_SESSION['login_attempts'] >= 5) {
        die("Too many failed login attempts. Please try again later.");
    }
    
    // using the function executepreparedquery function from utility.php
    list($stmt, $result) = executePreparedQuery($mysqli, "SELECT * FROM accounts WHERE Username=? OR Email=?", "ss", [$username, $username]);
    $user = $result->fetch_assoc();

    if (!$user) {
        $_SESSION['login_attempts'] += 1;
        redirectWithMessage("index.php?page=auth", "Invalid Username or Password", "login_error");
    }

    if ($user && password_verify($pwd, $user['Password'])) {
        $_SESSION['isLoggedIn'] = true;
        $_SESSION['username'] = htmlspecialchars($username, ENT_QUOTES, 'UTF-8');
        $_SESSION['role'] = htmlspecialchars($user['Role'], ENT_QUOTES, 'UTF-8');
        $_SESSION['Fname'] = htmlspecialchars($user['FName'], ENT_QUOTES, 'UTF-8');
        $_SESSION['Lname'] = htmlspecialchars($user['LName'], ENT_QUOTES, 'UTF-8');
        $_SESSION['Email'] = htmlspecialchars($user['Email'], ENT_QUOTES, 'UTF-8');
        header("Location: index.php?page=homepage");
        exit;
    } else {
        redirectWithMessage("index.php?page=auth", "Invalid Username or Password", "login_error");
    }
    $stmt->close();
    $mysqli->close();
}
?>
