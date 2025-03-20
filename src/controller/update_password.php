<?php
require_once __DIR__ . '/../../config/config.php';
require_once __DIR__ . '/../../utils/utils.php';

checkLogin();


if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $_SESSION['username'];
    $oldPassword = $_POST['oldPassword'];
    $newPassword = $_POST['newPassword'];
    $confirmPassword = $_POST['confirmPassword'];

    
    list($stmt, $result) = executePreparedQuery($mysqli, "SELECT * FROM accounts WHERE Username=?", "s", [$username]);
    $user = $result->fetch_assoc();

    // verify old password
    if ($user && password_verify($oldPassword,$user['Password'])) {
        if ($newPassword === $confirmPassword) {
            
            if (!preg_match("/^(?=.*[A-Z])(?=.*\d).{6,}$/", $newPassword)) {
                redirectWithMessage('../views/profile.php','Password must be at least 6 characters long and contain at least 1 uppercase letter and 1 number.','error');
            }
            // update password
            $updateSql = "UPDATE accounts SET Password=? WHERE Username=?";
            $stmt = $mysqli->prepare($updateSql);
            $stmt ->bind_param("ss",password_hash($newPassword, PASSWORD_BCRYPT) ,$username);
            
            if ($stmt ->execute()) {
                redirectWithMessage('index.php?page=profile','Password Updated Successfully!.');
            } else {
                redirectWithMessage('index.php?page=profile','Error updating your password. Please try again.','error');
            }
        } else {
            redirectWithMessage('index.php?page=profile','New passwords do not match.','error');
        }
    } else {
        redirectWithMessage('index.php?page=profile','Incorrect old password.','error');
    }

    $stmt ->close();
    $mysqli ->close();
}
?>
