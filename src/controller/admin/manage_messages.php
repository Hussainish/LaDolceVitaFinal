
<?php

	require_once realpath(__DIR__ . '/../../../config/config.php');
	require_once realpath(__DIR__ . '/../../../utils/utils.php');

    checkLogin();

    // handle updating the status
    if (isset($_POST['update_status'])) {
        $messageID = filter_var($_POST['message_id']);
        $new_status =htmlspecialchars($_POST['status']);


        // update query using mysqli_query
        $update_query = "UPDATE inqueries SET Status = ? WHERE MessageID = ?";
        $stmt = $mysqli->prepare($update_query);
        $stmt->bind_param("si",$new_status,$messageID);

        if ($stmt->execute()) {
            redirectWithMessage("index.php?page=admin_inqueries", "Message status updated successfully!");
        } else {
            redirectWithMessage("index.php?page=admin_inqueries", "Error updating message status!", "error");
        }
    }

    $limit = 10; //limit number of messages per page
    $p = isset($_GET['p']) ? (int)$_GET['p'] : 1;
    $offset = ($p - 1) * $limit;
    $sql = "SELECT * FROM inqueries LIMIT $limit OFFSET $offset";
    $total_query = "SELECT COUNT(*) AS total FROM inqueries";
    $total_result = mysqli_query($mysqli, $total_query);
    $total_row = mysqli_fetch_assoc($total_result);
    $total_messages = $total_row['total'];

    $totalPages  = ceil($total_messages / $limit);

    // search functionality
    $searchTerm = isset($_GET['search']) ? $_GET['search'] : ""; 
    $search_query = "%" . $searchTerm . "%"; 

    if (!empty($searchTerm)) {
        $sql = "SELECT * FROM inqueries WHERE Name LIKE ? OR Email LIKE ? OR Message LIKE ? LIMIT ? OFFSET ?";
        $stmt = $mysqli->prepare($sql);
        $stmt->bind_param("sssii", $search_query, $search_query, $search_query, $limit, $offset);
    } else {
        $sql = "SELECT * FROM inqueries LIMIT ? OFFSET ?";
        $stmt = $mysqli->prepare($sql);
        $stmt->bind_param("ii", $limit, $offset);
    }
    $stmt->execute();
    $result = $stmt->get_result();
    $successMessage = isset($_GET['success']) ? htmlspecialchars($_GET['success']) : '';
    $errorMessage   = isset($_GET['error'])   ? htmlspecialchars($_GET['error'])   : '';
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="base-url" content="/project/">
    <title>Manage Customer Messages - La Dolce Vita</title>
    <link rel="icon" type="image/x-icon" href="favicon.ico">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <link rel="stylesheet" href="/assets/css/styles.css">
    <style>
        
        .search-bar .form-control {
            border-radius: 25px;
            padding: 10px;
        }

        .pagination .page-item.active .page-link {
            background-color: #007bff;
            border-color: #007bff;
        }

        .status-dropdown {
            background-color: #f8f9fa;
            border-radius: 5px;
        }

        .badge {
            font-size: 14px;
            padding: 5px 10px;
        }
    </style>
</head>
<body>
    <header id="navbar">
        <?php require_once realpath(__DIR__ . '/../../views/header.php'); ?>
    </header>
    <div class="container mt-5">
    <h2 class="text-center mb-4">Manage Customer Messages</h2>

    <div class="d-flex justify-content-between align-items-center mb-3">
        <a href="index.php?page=admin_dashboard" class="btn btn-secondary">
            <i class="fas fa-arrow-left"></i> Back to Dashboard
        </a>
    </div>

    <!-- search bar -->
    <div class="row justify-content-center">
        <div class="col-md-6">
            <form action="index.php" method="GET" class="input-group">
            <input type="hidden" name="page" value="admin_inqueries">
            <input type="text" name="search" class="form-control rounded-left" 
                placeholder="Search by Name, Email, or Message" 
                value="<?= htmlspecialchars($searchTerm, ENT_QUOTES); ?>">
                <div class="input-group-append">
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-search"></i> Search
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- messages table - fetches the menu items data fom the databse-->
    <div class="table-responsive mt-4">
        <table class="table table-hover table-striped table-bordered text-center">
            <thead class="thead-dark">
                <tr>
                    <th scope="col">Message ID</th>
                    <th scope="col">Name</th>
                    <th scope="col">Email</th>
                    <th scope="col">Message</th>
                    <th scope="col">Created At</th>
                    <th scope="col">Status</th>
                    <th scope="col">Action</th>
                </tr>
            </thead>
            <tbody>
                <?php while ($row = mysqli_fetch_assoc($result)) : ?>
                    <tr>
                        <td><?= htmlspecialchars($row['MessageID']); ?></td>
                        <td><?= htmlspecialchars($row['Name']); ?></td>
                        <td><?= htmlspecialchars($row['Email']); ?></td>
                        <td><?= htmlspecialchars($row['Message']); ?></td>
                        <td><?= htmlspecialchars($row['CreatedAt']); ?></td>
                        <td>
                            <span class="badge badge-<?php 
                                echo ($row['Status'] == 'Opened') ? 'success' :
                                     (($row['Status'] == 'Unopened') ? 'warning' :
                                     (($row['Status'] == 'Closed') ? 'secondary' : 'info'));
                            ?>">
                                <?= htmlspecialchars($row['Status']); ?>
                            </span>
                        </td>
                        <td>
                            <form action="" method="POST">
                                <input type="hidden" name="message_id" value="<?= $row['MessageID']; ?>">
                                <select class="form-control status-dropdown" name="status">
                                    <option value="Opened" <?= $row['Status'] == 'Opened' ? 'selected' : ''; ?>>Opened</option>
                                    <option value="Unopened" <?= $row['Status'] == 'Unopened' ? 'selected' : ''; ?>>Unopened</option>
                                    <option value="Closed" <?= $row['Status'] == 'Closed' ? 'selected' : ''; ?>>Closed</option>
                                    <option value="In Process" <?= $row['Status'] == 'In Process' ? 'selected' : ''; ?>>In Process</option>
                                </select>
                                <input class="btn btn-sm btn-primary mt-2" type="submit" name="update_status" value="Update">
                            </form>
                        </td>
                    </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
    </div>

    <!-- pagination -->
    <?php
        $baseUrl = "index.php?page=admin_inqueries&";
        if ($searchTerm) {
            $baseUrl .= "searchTerm=" . urlencode($searchTerm) . "&";
        }
        echo generatePaginationLinks($p, $totalPages, $baseUrl);
    ?>
    


    <footer class="footer">
        <div class="container text-center">
            <p>&copy; 2024 La Dolce Vita. All rights Reserved. </p>
        </div>
    </footer>
    
    <!-- error and success messages handling -->
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
