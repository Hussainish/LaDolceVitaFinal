<?php
require_once __DIR__ . '/../../config/config.php';
require_once __DIR__ . '/../../utils/utils.php';


if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // get the form input  data
    $name = mysqli_real_escape_string($mysqli, $_POST['name']);
    $email = mysqli_real_escape_string($mysqli, $_POST['email']);
    $phone = mysqli_real_escape_string($mysqli, $_POST['phone']);
    $date = mysqli_real_escape_string($mysqli, $_POST['date']);
    $time = mysqli_real_escape_string($mysqli, $_POST['time']);
    $people = (int)$_POST['people'];
    $special_requests =  $_POST['special_requests'];
    

    if (!validatePhone($phone)) {
        echo "<script>alert('Invalid phone number.'); window.history.back();</script>";
        exit;
    }

    if (!validateEmail($email)){
        echo "<script>alert('Invalid Email address.'); window.history.back();</script>";
        exit;
    }

    $reservationDateTime = DateTime::createFromFormat('Y-m-d H:i', $date . ' ' . $time);
    $currentDateTime     = new DateTime();

    if ($reservationDateTime <= $currentDateTime) {
        echo "<script>alert('Reservation date and time must be in the future.'); window.history.back();</script>";
        exit;
    }


    // insert the data into the database
    $sql = "INSERT INTO reservations (CustomerName, CustomerEmail, CustomerPhone, ReservationDate, ReservationTime, NumberOfPeople, SpecialRequest)
    VALUES (?, ?, ?, ?, ?, ?, ?)";
    $stmt = $mysqli->prepare($sql);
    $stmt->bind_param("sssssis", $name, $email, $phone, $date, $time, $people, $special_requests);
    

    if ($stmt->execute()) {
        echo  " <script> alert( 'Your reservation has been successfully made!');
        window.location.href='index.php?page=homepage';</script>";
    } else {
        echo " <script> alert('Error ! your reservation isnt complete !');
         window.location.href='index.php?page=reservations';</script>";
    }
}
$stmt->close();
$mysqli->close();
?>