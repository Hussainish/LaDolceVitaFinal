<?php 

include 'session_status.php'; 

require_once __DIR__ . '/../../config/config.php';
require_once __DIR__ . '/../../utils/utils.php';

checkLogin();

$username = $_SESSION['username'];


// reservations pagination

$rpage = isset($_GET['rpage']) ? (int)$_GET['rpage'] : 1;
$res_limit = 5;
$res_offset = ($rpage - 1) * $res_limit;


// count total reservations - needed for pagination
$sql_count_res = "SELECT COUNT(*) AS total FROM reservations WHERE CustomerEmail = (SELECT Email FROM accounts WHERE Username = ?)";
$stmt_count_res = mysqli_prepare($mysqli, $sql_count_res);
mysqli_stmt_bind_param($stmt_count_res, "s", $username);
mysqli_stmt_execute($stmt_count_res);
$result_count_res = mysqli_stmt_get_result($stmt_count_res);
$count_row = mysqli_fetch_assoc($result_count_res);
$total_reservations = $count_row['total'];
$total_res_pages = ceil($total_reservations / $res_limit);

// load reservations with prepared statement
$sql_reservations = "SELECT * FROM reservations WHERE CustomerEmail = (SELECT Email FROM accounts WHERE Username = ?) LIMIT ? OFFSET ?";
$stmt_res = mysqli_prepare($mysqli, $sql_reservations);
mysqli_stmt_bind_param($stmt_res, "sii", $username, $res_limit, $res_offset);
mysqli_stmt_execute($stmt_res);
$result_reservations = mysqli_stmt_get_result($stmt_res);


// orders Pagination
$opage = isset($_GET['opage']) ? (int)$_GET['opage'] : 1;
$order_limit = 5;
$order_offset = ($opage - 1) * $order_limit;

// count total orders - needed for pagination.
$sql_count_orders = "SELECT COUNT(*) AS total FROM orders WHERE CustomerID = (SELECT AccountID FROM accounts WHERE Username = ?)";
$stmt_count_orders = mysqli_prepare($mysqli, $sql_count_orders);
mysqli_stmt_bind_param($stmt_count_orders, "s", $username);
mysqli_stmt_execute($stmt_count_orders);
$result_count_orders = mysqli_stmt_get_result($stmt_count_orders);
$count_row_orders = mysqli_fetch_assoc($result_count_orders);
$total_orders = $count_row_orders['total'];
$total_orders_pages = ceil($total_orders / $order_limit);

// load orders with prepared statement
$sql_orders = "SELECT * FROM orders WHERE CustomerID = (SELECT AccountID FROM accounts WHERE Username = ?) LIMIT ? OFFSET ?";
$stmt_orders = mysqli_prepare($mysqli, $sql_orders);
mysqli_stmt_bind_param($stmt_orders, "sii", $username, $order_limit, $order_offset);
mysqli_stmt_execute($stmt_orders);
$result_orders = mysqli_stmt_get_result($stmt_orders);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>My Reservations and Orders</title>
    <link rel="icon" type="image/x-icon" href="favicon.ico">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="/assets/css/styles.css">
    <style>
        .tab-content {
            padding: 20px;
            background-color: #fff;
            border: 1px solid #dee2e6;
            border-top: none;
            border-radius: 0 0 10px 10px;
        }
    </style>
</head>
<body>
    <!-- navigation Bar -->
    <?php require_once realpath(__DIR__ . '/../views/header.php'); ?>

    <div class="container mt-5 ">
        <h1 class="text-center">Your Reservations and Orders</h1>

        <!-- tab navigation - includes 2 tabs , 1 for orders and 1 for reservations -->
        <ul class="nav nav-tabs" id="myTab" role="tablist">
            <li class="nav-item">
                <a class="nav-link active" id="reservations-tab" data-toggle="tab" href="#reservations" role="tab" aria-controls="reservations" aria-selected="true">Reservations</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" id="orders-tab" data-toggle="tab" href="#orders" role="tab" aria-controls="orders" aria-selected="false">Orders</a>
            </li>
        </ul>

        <!-- tab content -->
        <div class="tab-content" id="myTabContent">
            <!-- reservations tab -->
            <div class="tab-pane fade show active table-responsive" id="reservations" role="tabpanel" aria-labelledby="reservations-tab">
                <h2>Your Reservations</h2>
                <table class="table table-hover table-bordered">
                    <thead class="thead-dark">
                        <tr>
                            <th>Reservation ID</th>
                            <th>Date</th>
                            <th>Time</th>
                            <th>Number of People</th>
                            <th>Special Request</th>
                            <th>Reservation Created</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (mysqli_num_rows($result_reservations) > 0): ?>
                            <?php while ($row = mysqli_fetch_assoc($result_reservations)): ?>
                                <tr>
                                    <td><?= htmlspecialchars($row['ReservationID']) ?></td>
                                    <td><?= htmlspecialchars($row['ReservationDate']) ?></td>
                                    <td><?= htmlspecialchars($row['ReservationTime']) ?></td>
                                    <td><?= htmlspecialchars($row['NumberOfPeople']) ?></td>
                                    <td><?= htmlspecialchars($row['SpecialRequest']) ?></td>
                                    <td><?= htmlspecialchars($row['CreatedAt']) ?></td>
                                </tr>
                            <?php endwhile; ?>
                        <?php else: ?>
                            <tr>
                                <td colspan="6" class="text-center">No reservations found.</td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
                <!-- reservations pagination -->
                <nav aria-label="Reservations Pagination">
                    <ul class="pagination justify-content-center">
                        <?php for($i = 1; $i <= $total_res_pages; $i++): ?>
                            <li class="page-item <?= ($i == $rpage) ? 'active' : '' ?>">
                                <a class="page-link" href="?rpage=<?= $i ?><?= isset($_GET['opage']) ? '&opage=' . $_GET['opage'] : '' ?>#reservations"><?= $i ?></a>
                            </li>
                        <?php endfor; ?>
                    </ul>
                </nav>
            </div>

            <!-- orders tab -->
            <div class="tab-pane fade table-responsive" id="orders" role="tabpanel" aria-labelledby="orders-tab">
                <h2>Your Orders</h2>
                <table class="table table-hover table-bordered">
                    <thead class="thead-dark">
                        <tr>
                            <th>Order ID</th>
                            <th>Order Details</th>
                            <th>Total Price</th>
                            <th>Credit Card Last 4 Digits</th>
                            <th>Order Date</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (mysqli_num_rows($result_orders) > 0): ?>
                            <?php while ($row = mysqli_fetch_assoc($result_orders)): ?>
                                <tr>
                                    <td><?= htmlspecialchars($row['OrderID']) ?></td>
                                    <td><?= htmlspecialchars($row['OrderDetails']) ?></td>
                                    <td>$<?= number_format($row['TotalPrice'], 2) ?></td>
                                    <td><?= htmlspecialchars($row['CreditCardLast4']) ?></td>
                                    <td><?= htmlspecialchars($row['OrderDate']) ?></td>
                                </tr>
                            <?php endwhile; ?>
                        <?php else: ?>
                            <tr>
                                <td colspan="5" class="text-center">No orders found.</td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
                <!-- orders pagination -->
                <nav aria-label="Orders Pagination">
                    <ul class="pagination justify-content-center">
                        <?php for($i = 1; $i <= $total_orders_pages; $i++): ?>
                            <li class="page-item <?= ($i == $opage) ? 'active' : '' ?>">
                                <a class="page-link" href="?opage=<?= $i ?><?= isset($_GET['rpage']) ? '&rpage=' . $_GET['rpage'] : '' ?>#orders"><?= $i ?></a>
                            </li>
                        <?php endfor; ?>
                    </ul>
                </nav>
            </div>
        </div>
    </div>
    <footer class="footer">
        <div class="container text-center">
            <p>&copy; 2024 La Dolce Vita. All rights Reserved. </p>
        </div>
    </footer>

    
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.1/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
    <script src="https://kit.fontawesome.com/a076d05399.js"></script>
</body>
</html>
