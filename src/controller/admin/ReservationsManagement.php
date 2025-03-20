<?php

require_once realpath(__DIR__ . '/../../../config/config.php');
require_once realpath(__DIR__ . '/../../../utils/utils.php');

checkLogin();


//  pagination setup with limit and offset
$p = isset($_GET['p']) ? (int)$_GET['p'] : 1;
if ($p < 1) { $p = 1; }
$limit = 10;
$offset = ($p - 1) * $limit;

//  handle Adding a New Reservation 
if (isset($_POST['add_reservation'])) {
    $name = $_POST['name'];
    $email = $_POST['email'];
    $phone = $_POST['phone'];
    $date = $_POST['date'];
    $time = $_POST['time'];
    $people = (int)$_POST['people'];
    $request = $_POST['request'];

    if(!validateEmail($email)){
        redirectWithMessage("index.php?page=admin_manage_reservations", "Invalid email format.", "error");
    }

    if (!validatePhone($phone)) {
        redirectWithMessage("index.php?page=admin_manage_reservations", "Invalid phone number.", "error");
    }

    $stmt = $mysqli->prepare("INSERT INTO reservations (CustomerName, CustomerEmail, CustomerPhone, ReservationDate, ReservationTime, NumberOfPeople, SpecialRequest) VALUES (?, ?, ?, ?, ?, ?, ?)");
    $stmt->bind_param("sssssis", $name, $email, $phone, $date, $time, $people, $request);

    if ($stmt->execute()) {
        echo "<script>alert('Reservation added successfully.'); window.location.href='index.php?page=admin_manage_reservations'</script>";
    } else {
        echo "<script>alert('Error adding reservation.'); window.location.href='index.php?page=admin_manage_reservations'</script>";
    }
    exit;
}

//  handle updating a reservation 
if (isset($_POST['update_reservation'])) {
    $reservationID = $_POST['reservation_id'];
    $name = $_POST['name'];
    $email = $_POST['email'];
    $phone = $_POST['phone'];
    $date = $_POST['date'];
    $time = $_POST['time'];
    $people = (int)$_POST['people'];
    $request = $_POST['request'];

    if(!validateEmail($email)){
        redirectWithMessage("index.php?page=admin_manage_reservations", "Invalid email format.", "error");
    }

    if (!validatePhone($phone)) {
        redirectWithMessage("index.php?page=admin_manage_reservations", "Invalid phone number.", "error");
    }

    

    $update_query = "UPDATE reservations 
                     SET CustomerName=?, CustomerEmail=?, CustomerPhone=?, ReservationDate=?, ReservationTime=?, NumberOfPeople=?, SpecialRequest=?
                     WHERE ReservationID=?";
    $stmt = $mysqli->prepare($update_query);
    $stmt->bind_param("sssssisi", $name, $email, $phone, $date, $time, $people, $request, $reservationID);

    if ($stmt->execute()) {
        redirectWithMessage("index.php?page=admin_manage_reservations", "Reservation updated successfully.");
    } else {
        redirectWithMessage("index.php?page=admin_manage_reservations", "Error updating reservation.", "error");
    }
    exit;
}

//  handle canceling (deleting) the reservation 
if (isset($_POST['cancel_reservation'])) {
    $reservationID = $_POST['reservation_id'];
    $delete_query = "DELETE FROM reservations WHERE ReservationID=?";
    $stmt = $mysqli->prepare($delete_query);
    $stmt->bind_param("i", $reservationID);

    if ($stmt->execute()) {
        redirectWithMessage("index.php?page=admin_manage_reservations", "Reservation canceled successfully.");
    } else {
        redirectWithMessage("index.php?page=admin_manage_reservations", "Error canceling reservation.", "error");
    }
    exit;
}

//  filter query for fetching reservations with pagination 
$search_query = "";
$date_filter = "";
$whereClause = " WHERE 1=1 ";
$paramTypes = "";
$params = [];

// if a search term is provided add it to the query
if (isset($_GET['search']) && !empty($_GET['search'])) {
    $search_query = $_GET['search'];
    $whereClause .= " AND (CustomerName LIKE ? OR CustomerEmail LIKE ? OR CustomerPhone LIKE ?)";
    $likeSearch = "%" . $search_query . "%";
    $paramTypes .= "sss";
    $params = array_merge($params, [$likeSearch, $likeSearch, $likeSearch]);
}

// if a filter date is provided add it to the query
if (isset($_GET['filter_date']) && !empty($_GET['filter_date'])) {
    $date_filter = $_GET['filter_date'];
    $whereClause .= " AND ReservationDate = ?";
    $paramTypes .= "s";
    $params[] = $date_filter;
}

//  count total reservations found  for pagination 
$count_query = "SELECT COUNT(*) as total FROM reservations" . $whereClause;
$stmt_count = $mysqli->prepare($count_query);
if (!empty($paramTypes)) {
    $stmt_count->bind_param($paramTypes, ...$params);
}
$stmt_count->execute();
$result_count = $stmt_count->get_result();
$row_count = $result_count->fetch_assoc();
$total = $row_count['total'];
$totalPages = ceil($total / $limit);
$stmt_count->close();

//  this is the main query: fetch reservations with pagination
// basically needed to show the list or reservations 
$filter_query = "SELECT * FROM reservations" . $whereClause . " ORDER BY ReservationDate ASC, ReservationTime ASC LIMIT ? OFFSET ?";
$paramTypes .= "ii";
$params[] = $limit;
$params[] = $offset;

$stmt = $mysqli->prepare($filter_query);
$stmt->bind_param($paramTypes, ...$params);
$stmt->execute();
$result = $stmt->get_result();


$baseUrl = "index.php?page=admin_manage_reservations&";
if (!empty($search_query)) {
    $baseUrl .= "search=" . urlencode($search_query) . "&";
}
if (!empty($date_filter)) {
    $baseUrl .= "filter_date=" . urlencode($date_filter) . "&";
}

$successMessage = isset($_GET['success']) ? htmlspecialchars($_GET['success']) : '';
$errorMessage   = isset($_GET['error'])   ? htmlspecialchars($_GET['error'])   : '';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="base-url" content="/project/">
    <title>Manage Reservations - La Dolce Vita</title>
    <link rel="icon" type="image/x-icon" href="favicon.ico">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <link rel="stylesheet" href="/assets/css/styles.css">
    <style>
        body {
            background-color: #f8f9fa;
        }
        .card-header {
            background-color: #343a40;
            color: #fff;
        }
        .card {
            margin-bottom: 30px;
        }
        .table-container {
            margin-top: 30px;
        }
        .table-responsive {
            box-shadow: 0 0 15px rgba(0,0,0,0.1);
            border-radius: 8px;
        }
        .pagination {
            justify-content: center;
        }
    </style>
</head>
<body>
    <!-- navigation bar -->
    <header id="navbar">
        <?php require_once realpath(__DIR__ . '/../../views/header.php'); ?>
    </header>

    <div class="container mt-4">
        <h2 class="text-center mb-4">Manage Reservations</h2>
        <div class="text-center mb-3">
            <a href="index.php?page=admin_dashboard" class="btn btn-secondary">Back to Dashboard</a>
        </div>

        <!-- add reservation form card -->
        <div class="card">
            <div class="card-header">
                <h4 class="mb-0">Add New Reservation</h4>
            </div>
            <div class="card-body">
                <form action="index.php?page=admin_manage_reservations" method="POST">
                    <div class="form-row">
                        <div class="form-group col-md-6">
                            <input type="text" name="name" class="form-control" placeholder="Customer Name" required>
                        </div>
                        <div class="form-group col-md-6">
                            <input type="email" name="email" class="form-control" placeholder="Customer Email" required>
                        </div>
                    </div>
                    <div class="form-row">
                        <div class="form-group col-md-4">
                            <input type="tel" name="phone" class="form-control" placeholder="Customer Phone (10 digits)" required>
                        </div>
                        <div class="form-group col-md-4">
                            <input type="date" name="date" class="form-control" required>
                        </div>
                        <div class="form-group col-md-4">
                            <input type="time" name="time" class="form-control" required>
                        </div>
                    </div>
                    <div class="form-row">
                        <div class="form-group col-md-6">
                            <input type="number" name="people" class="form-control" placeholder="Number of People" required>
                        </div>
                        <div class="form-group col-md-6">
                            <input type="text" name="request" class="form-control" placeholder="Special Request (Optional)">
                        </div>
                    </div>
                    <button type="submit" name="add_reservation" class="btn btn-primary btn-block">Add Reservation</button>
                </form>
            </div>
        </div>

        <!-- search and date filter card -->
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0">Search Reservations</h5>
            </div>
            <div class="card-body">
                <form action="index.php" method="GET" class="form-inline justify-content-center">
                <input type="hidden" name="page" value="admin_manage_reservations">
                    <div class="form-group mx-sm-2 mb-2">
                        <input type="text" name="search" class="form-control" placeholder="Search by Name, Email, or Phone" value="<?php echo htmlspecialchars($search_query); ?>">
                    </div>
                    <div class="form-group mx-sm-2 mb-2">
                        <input type="date" name="filter_date" class="form-control" value="<?php echo htmlspecialchars($date_filter); ?>">
                    </div>
                    <button type="submit" class="btn btn-primary mb-2">Search</button>
                </form>
            </div>
        </div>

        <!-- reservation table - here all the reservations are gonna show-->
        <div class="table-container">
            <div class="table-responsive">
                <table class="table table-hover table-bordered text-center">
                    <thead class="thead-dark">
                        <tr>
                            <th scope="col">Reservation ID</th>
                            <th scope="col">Customer Name</th>
                            <th scope="col">Customer Email</th>
                            <th scope="col">Customer Phone</th>
                            <th scope="col">Date</th>
                            <th scope="col">Time</th>
                            <th scope="col">No. of People</th>
                            <th scope="col">Special Request</th>
                            <th scope="col">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        if ($result && mysqli_num_rows($result) > 0) {
                            while ($row = $result->fetch_assoc()) {
                                echo "<tr>";
                                echo "<td>" . htmlspecialchars($row['ReservationID']) . "</td>";
                                echo "<td>" . htmlspecialchars($row['CustomerName']) . "</td>";
                                echo "<td>" . htmlspecialchars($row['CustomerEmail']) . "</td>";
                                echo "<td>" . htmlspecialchars($row['CustomerPhone']) . "</td>";
                                echo "<td>" . htmlspecialchars($row['ReservationDate']) . "</td>";
                                echo "<td>" . htmlspecialchars($row['ReservationTime']) . "</td>";
                                echo "<td>" . htmlspecialchars($row['NumberOfPeople']) . "</td>";
                                echo "<td>" . htmlspecialchars($row['SpecialRequest']) . "</td>";
                                echo "<td>
                                        <form action='index.php?page=admin_manage_reservations' method='POST' class='d-inline' onsubmit=\"return confirm('Are you sure you want to cancel this reservation?');\">
                                            <input type='hidden' name='reservation_id' value='" . htmlspecialchars($row['ReservationID']) . "'>
                                            <button type='submit' name='cancel_reservation' class='btn btn-danger btn-sm'>Cancel</button>
                                        </form>
                                        <button class='btn btn-warning btn-sm' data-toggle='modal' data-target='#editReservationModal" . htmlspecialchars($row['ReservationID']) . "'>Update</button>
                                        
                                        <!-- Update Modal -->
                                        <div class='modal fade' id='editReservationModal" . htmlspecialchars($row['ReservationID']) . "' tabindex='-1' role='dialog'>
                                            <div class='modal-dialog modal-dialog-centered' role='document'>
                                                <div class='modal-content'>
                                                    <div class='modal-header'>
                                                        <h5 class='modal-title'>Update Reservation</h5>
                                                        <button type='button' class='close' data-dismiss='modal' aria-label='Close'>
                                                            <span aria-hidden='true'>&times;</span>
                                                        </button>
                                                    </div>
                                                    <div class='modal-body'>
                                                        <form action='index.php?page=admin_manage_reservations' method='POST'>
                                                            <input type='hidden' name='reservation_id' value='" . htmlspecialchars($row['ReservationID']) . "'>
                                                            <div class='form-group'>
                                                                <input type='text' name='name' class='form-control' value='" . htmlspecialchars($row['CustomerName']) . "' required>
                                                            </div>
                                                            <div class='form-group'>
                                                                <input type='email' name='email' class='form-control' value='" . htmlspecialchars($row['CustomerEmail']) . "' required>
                                                            </div>
                                                            <div class='form-group'>
                                                                <input type='tel' name='phone' class='form-control' value='" . htmlspecialchars($row['CustomerPhone']) . "' required>
                                                            </div>
                                                            <div class='form-group'>
                                                                <input type='date' name='date' class='form-control' value='" . htmlspecialchars($row['ReservationDate']) . "' required>
                                                            </div>
                                                            <div class='form-group'>
                                                                <input type='time' name='time' class='form-control' value='" . htmlspecialchars($row['ReservationTime']) . "' required>
                                                            </div>
                                                            <div class='form-group'>
                                                                <input type='number' name='people' class='form-control' value='" . htmlspecialchars($row['NumberOfPeople']) . "' required>
                                                            </div>
                                                            <div class='form-group'>
                                                                <input type='text' name='request' class='form-control' value='" . htmlspecialchars($row['SpecialRequest']) . "'>
                                                            </div>
                                                            <button type='submit' name='update_reservation' class='btn btn-primary btn-block'>Update</button>
                                                        </form>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                      </td>";
                                echo "</tr>";
                            }
                        } else {
                            echo "<tr><td colspan='9'>No reservations found.</td></tr>";
                        }
                        ?>
                    </tbody>
                </table>
            </div>
        </div>
        
        <!-- pagination - used generatePaginationLinks method from utils.php -->
        <?php echo generatePaginationLinks($p, $totalPages, $baseUrl); ?>
    </div>

    
    <footer class="footer">
        <div class="container text-center">
            <p>&copy; 2024 La Dolce Vita. All rights Reserved. </p>
        </div>
    </footer>

    <!-- error and success message hadnling -->
    <?php if ($successMessage): ?>
        <script>
        alert("Success: <?php echo $successMessage; ?>");
    </script>
    <?php endif; ?>

    <?php if ($errorMessage): ?>
        <script>
        alert("Error: <?php echo $errorMessage; ?>");
        </script>
    <?php endif;
    ?>
    <script src="/assets/js/scripts.js"></script>
    <script src="https://kit.fontawesome.com/a076d05399.js"></script>
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.0/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>
