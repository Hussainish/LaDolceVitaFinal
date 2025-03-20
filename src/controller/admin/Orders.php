<?php include 'session_status.php';?>

<?php

require_once realpath(__DIR__ . '/../../../config/config.php');

$search_query = "";
$date_filter = "";
$limit = 10;
$p = isset($_GET['p']) ? (int)$_GET['p'] : 1;
$offset = ($p - 1) * $limit;

// handle search and date filter
if (isset($_GET['search'])) {
    $search_query = $_GET['search'];
}
if (isset($_GET['date'])) {
    $date_filter = $_GET['date'];
}

// SQL query for fetching orders with filters
$sql = "SELECT O.OrderID, A.FName, A.LName, O.OrderDetails, O.TotalPrice, O.CreditCardLast4, O.OrderDate 
        FROM orders O
        JOIN accounts A ON O.CustomerID = A.AccountID
        WHERE 1";

if ($search_query) {
    $sql .= " AND (A.FName LIKE ? OR A.LName LIKE ? OR O.OrderDetails LIKE ? OR O.CreditCardLast4 LIKE ?)";
}
if ($date_filter) {
    $sql .= " AND DATE(O.OrderDate) = ?";
}

$sql .= " LIMIT ? OFFSET ?";

$stmt = $mysqli->prepare($sql);

if ($search_query && $date_filter) {
    $search_param = "%$search_query%";
    // 4 string params for search, 1 for date, then 2 integers
    // s for text - aka search - i is used for date and for the limit and offset as integers
    $stmt->bind_param("ssssiii", $search_param, $search_param, $search_param, $search_param, $date_filter, $limit, $offset);
} elseif ($search_query) {
    $search_param = "%$search_query%";
    $stmt->bind_param("ssssii", $search_param, $search_param, $search_param, $search_param, $limit, $offset);
} elseif ($date_filter) {
    $stmt->bind_param("sii", $date_filter, $limit, $offset);
} else {
    $stmt->bind_param("ii", $limit, $offset);
}

$stmt->execute();
$result = $stmt->get_result();

// calculate how many orders are found with the search term
// in case there is no search term, cound total orders in the database
//this is needed for pagination
$count_sql = "SELECT COUNT(*) AS total 
              FROM orders O 
              JOIN accounts A ON O.CustomerID = A.AccountID
              WHERE 1";
if ($search_query) {
    $count_sql .= " AND (A.FName LIKE ? OR A.LName LIKE ? OR O.OrderDetails LIKE ? OR O.CreditCardLast4 LIKE ?)";
}
if ($date_filter) {
    $count_sql .= " AND DATE(O.OrderDate) = ?";
}

$stmt_count = $mysqli->prepare($count_sql);

if ($search_query && $date_filter) {
    $search_param = "%$search_query%";
    $stmt_count->bind_param("sssss", $search_param, $search_param, $search_param, $search_param, $date_filter);
} elseif ($search_query) {
    $search_param = "%$search_query%";
    $stmt_count->bind_param("ssss", $search_param, $search_param, $search_param, $search_param);
} elseif ($date_filter) {
    $stmt_count->bind_param("s", $date_filter);
}
$stmt_count->execute();
$count_result = $stmt_count->get_result();
$total_row = $count_result->fetch_assoc();
$total_records = $total_row['total'];
$total_pages = ceil($total_records / $limit);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="base-url" content="/project/">
    <title>Orders List - Management Dashboard</title>
    <link rel="icon" type="image/x-icon" href="favicon.ico">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="/assets/css/styles.css">
    <style>
        body {
            background-color: #f8f9fa;
        }
        h1 {
            margin-top: 30px;
            color: #333;
        }
        .table-hover tbody tr:hover {
            background-color: #f1f1f1;
        }
        .search-container {
            display: flex;
            justify-content: center;
            margin-bottom: 20px;
        }
        .search-bar-card {
            background-color: #ffffff;
            box-shadow: 0px 4px 10px rgba(0, 0, 0, 0.1);
            padding: 20px;
            border-radius: 10px;
            width: 100%;
            max-width: 800px;
        }
        .search-bar {
            display: flex;
            flex-wrap: wrap;
            gap: 10px;
        }
        .search-bar input {
            flex: 1;
            min-width: 150px;
        }
        .btn-search {
            background-color: #007bff;
            border: none;
            padding: 10px 20px;
            border-radius: 5px;
        }
        .btn-search:hover {
            background-color: #0056b3;
        }
        .table-wrapper {
            box-shadow: 0px 4px 6px rgba(0, 0, 0, 0.1);
            background-color: #fff;
            border-radius: 10px;
            padding: 20px;
            margin-top: 20px;
        }
        .pagination {
            margin-top: 20px;
        }
    </style>
</head>
<body>
    <!-- navigation bar -->
    <header id="navbar">
        <?php require_once realpath(__DIR__ . '/../../views/header.php'); ?>
    </header>

    <div class="container mt-5">
        <h1 class="text-center">Order List</h1>
        <a href="index.php?page=admin_dashboard" class="btn btn-secondary mb-3">Back to Dashboard</a>

        <!-- search and date filter form in a card -->
        <div class="search-container">
            <div class="search-bar-card">
                <form method="GET" class="search-bar" action="index.php">
                <input type="hidden" name="page" value="admin_orders_list">
                    <input type="text" name="search" class="form-control" placeholder="Search by customer or details" value="<?= htmlspecialchars($search_query) ?>" style="flex: 1;">
                    <input type="date" name="date" class="form-control" value="<?= htmlspecialchars($date_filter) ?>">
                    <button type="submit" class="btn btn-search">Search</button>
                </form>
            </div>
        </div>

        <!-- orders table -->
        <div class="table-wrapper table-responsive">
            <table class="table table-hover table-bordered">
                <thead class="table-dark">
                    <tr>
                        <th>Order ID</th>
                        <th>Customer Name</th>
                        <th>Order Details</th>
                        <th>Total Price</th>
                        <th>Credit Card Last 4</th>
                        <th>Order Date</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (mysqli_num_rows($result) > 0): ?>
                        <?php while ($row = $result->fetch_assoc()): ?>
                            <tr>
                                <td><?= htmlspecialchars($row['OrderID']) ?></td>
                                <td><?= htmlspecialchars($row['FName'] . " " . $row['LName']) ?></td>
                                <td><?= htmlspecialchars($row['OrderDetails']) ?></td>
                                <td>$<?= number_format($row['TotalPrice'], 2) ?></td>
                                <td><?php echo "**** **** **** " . htmlspecialchars($row['CreditCardLast4']); ?></td>
                                <td><?= date('Y-m-d H:i:s', strtotime($row['OrderDate'])) ?></td>
                            </tr>
                        <?php endwhile; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="6" class="text-center">No orders found.</td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>

            <!-- pagination -->
            <?php if ($total_pages > 1): ?>
            <nav aria-label="Page navigation">
                <ul class="pagination justify-content-center">
                    <?php for ($i = 1; $i <= $total_pages; $i++): ?>
                        <li class="page-item <?= ($i == $p) ? 'active' : '' ?>">
                            <a class="page-link" href="index.php?page=admin_orders_list&p=<?= $i ?><?php if($search_query) echo '&search=' . urlencode($search_query); ?><?php if($date_filter) echo '&date=' . urlencode($date_filter); ?>">
                                <?= $i ?>
                            </a>
                        </li>
                    <?php endfor; ?>
                </ul>
            </nav>
            <?php endif; ?>
        </div>
    </div>

    
    <footer class="footer">
        <div class="container text-center">
            <p>&copy; 2024 La Dolce Vita. All rights Reserved. </p>
        </div>
    </footer>
    

    <script src="/assets/js/scripts.js"></script>
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://kit.fontawesome.com/a076d05399.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>
