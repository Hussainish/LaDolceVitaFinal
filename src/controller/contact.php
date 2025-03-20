<?php

require_once __DIR__ . '/../../config/config.php';
require_once __DIR__ . '/../../utils/utils.php';

// this handles form submission for inqueries
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = $_POST['name'];
    $email = $_POST['email'];
    $message = $_POST['message'];

    list($stmt, $result) = executePreparedQuery($mysqli, 
    "INSERT INTO inqueries (Name, Email, Message, Status) VALUES (?, ?, ?, 'Unopened')",
    "sss", [$name, $email, $message]);


    if ($stmt->execute()) {
        header("Location: index.php?page=contactus&success=1");
        
        
    } else {
        redirectWithMessage("index.php?page=contactus&", "An error occurred while sending your message. Please try again later.", "error");
    }

    $stmt->close();
    $mysqli->close();
}
?>
