<?php
require_once __DIR__ . '/../../config/config.php';
require_once __DIR__ . '/../../utils/utils.php';



checkLogin();


if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $_SESSION['username'];
    $password = $_POST['password'];
    $newEmail = $_POST['newEmail'];
    $confirmEmail = $_POST['confirmEmail'];
    
    //make sure the email is valid
    if (!validateEmail($newEmail)) {
        redirectWithMessage("index.php?page=profile", "Invalid email format.", "error");
    }
    //verify that the user entered 2 matching email addresses
    if ($newEmail !== $confirmEmail) {
        redirectWithMessage("index.php?page=profile", "Emails do not match.", "error");
    }

    list($stmt, $result) = executePreparedQuery($mysqli, "SELECT Password FROM accounts WHERE Username=?", "s", [$username]);
    $user = $result->fetch_assoc();

    // password_verify makes sure the user entered the password correctly, basially makes sure the password matches the one in the database
    if ($user && password_verify($password, $user['Password'])) {
        // check if the new email already exists.
        list($stmt, $result) = executePreparedQuery($mysqli, "SELECT * FROM accounts WHERE Email=?", "s", [$newEmail]);
        if ($result->num_rows > 0) {
            redirectWithMessage("index.php?page=profile", "Email is already in use.", "error");
        }
        $stmt = $mysqli->prepare("UPDATE accounts SET Email=? WHERE Username=?");
        $stmt->bind_param("ss", $newEmail, $username);
        if ($stmt->execute()) {
            $_SESSION['Email'] = htmlspecialchars($newEmail, ENT_QUOTES, 'UTF-8');
            redirectWithMessage("index.php?page=profile", "Email Updated Successfully!");
        } else {
            redirectWithMessage("index.php?page=profile", "Error updating your email. Please try again.", "error");
        }
    } else {
        redirectWithMessage("index.php?page=profile", "Incorrect password.", "error");
    }
    $stmt->close();
    $mysqli->close();
}
?>
