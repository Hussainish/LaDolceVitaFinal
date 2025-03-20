<?php

//require config file to connect to database
// require utils to be able to use common functions from the utils file
require_once realpath(__DIR__ . '/../../../config/config.php');
require_once realpath(__DIR__ . '/../../../utils/utils.php');

//check whether a user is logged in or not
// if there is no user logged in 
// it will redirect the user back to the login page and show an error "admin only".
checkLogin();


// this variable is used for pagination 
//if the variable isnt set, set it.
$p = isset($_GET['p']) ? (int)$_GET['p'] : 1;
if ($p < 1) { $p = 1; }
//limit to 10 items per page - for the accounts list show only 10 accounts at a time.
$limit = 10;
$offset = ($p - 1) * $limit;

//this is search term, used to handle the searching logic
$searchTerm = '';
$whereClause = '';
$params = [];
$paramTypes = '';


//checks whether the user is searching for something in the accoutns list.
// if the user is searching for something it will update the searchterm variable and the whereclause variable
// and will set the parapm types to sssss so that they can be used during stmt bind_param function
if (isset($_GET['search']) && !empty($_GET['search'])) {
    $searchTerm = mysqli_real_escape_string($mysqli, $_GET['search']);
    $likeSearch = "%$searchTerm%";
    $whereClause = "WHERE FName LIKE ? OR LName LIKE ? OR Username LIKE ? OR Email LIKE ? OR Role LIKE ?";
    $params = [$likeSearch, $likeSearch, $likeSearch, $likeSearch, $likeSearch];
    $paramTypes = "sssss";
}

//if there is a where clause - basically if there is a search term
if ($whereClause) {
    // check how many similar items are there in the database
    $countSql = "SELECT COUNT(*) as total FROM accounts $whereClause";
    $stmt = $mysqli->prepare($countSql);
    $stmt->bind_param($paramTypes, ...$params);
    $stmt->execute();
    $resultCount = $stmt->get_result();
    $rowCount = $resultCount->fetch_assoc();
    //that way we got the total items found by the search term
    $total = $rowCount['total'];
    $stmt->close();
} else {
    //if there is no search term - simply select all items in the accounts list and count them
    $countSql = "SELECT COUNT(*) as total FROM accounts";
    $resultCount = mysqli_query($mysqli, $countSql);
    $rowCount = mysqli_fetch_assoc($resultCount);
    $total = $rowCount['total'];
}

// now we divide the total items we found by the limit of 10 accounts that we set to get
// how many pages we have.
$totalPages = ceil($total / $limit);


//now after that we select the itmes based on the search term and limit them
if ($whereClause) {
    $sql = "SELECT * FROM accounts $whereClause LIMIT $limit OFFSET $offset";
    $stmt = $mysqli->prepare($sql);
    $stmt->bind_param($paramTypes, ...$params);
    $stmt->execute();
    $result = $stmt->get_result();
} else {
    //again if there is no search term basically show everything, but limit them to 10 accounts per page
    $sql = "SELECT * FROM accounts LIMIT $limit OFFSET $offset";
    $result = mysqli_query($mysqli, $sql);
}

// this will handle the form submission logic for adding or updating  accounts
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // add a new account.
    if (isset($_POST['add'])) {
        // make sure all input fields are filled !
        if (isset($_POST['fname'], $_POST['lname'], $_POST['username'], $_POST['email'], $_POST['role'], $_POST['password'])) {
            $fname = $_POST['fname'];
            $lname = $_POST['lname'];
            $username = $_POST['username'];
            $email = $_POST['email'];
            $role = $_POST['role'];
            

            // in case of adding a new account, make sure a password is provided !
            if (empty($_POST['password'])) {
                redirectWithMessage("index.php?page=admin_manage_accounts&p=". $p, "Password is required for new accounts.", "error");
            }
            //hash password for security , then insert the account to the database
            $pwd = password_hash($_POST['password'], PASSWORD_BCRYPT);
            $sql_insert = "INSERT INTO accounts (FName, LName, Username, Password, Email, Role) VALUES (?, ?, ?, ?, ?, ?)";
            $stmt = $mysqli->prepare($sql_insert);
            $stmt->bind_param("ssssss", $fname, $lname, $username, $pwd, $email, $role);
            //if the sql code execution works redirect back to the manage accounts page and refresh the accounts list 
            //while showing a message of success that the account is added.
            //if the sql code execution failed , it will redirect back to the manage accounts page while showing the user an error message.
            if ($stmt->execute()) {
                redirectWithMessage("index.php?page=admin_manage_accounts&p=". $p, "Account added successfully!");
            } else {
                redirectWithMessage("index.php?page=admin_manage_accounts&p=" . $p, "Error adding account.", "error");
            }
        }
    }
    // this handles the update account logic apon update form submission
    if (isset($_POST['update'])) {
        //make sure all form inputs are filled !
        if (isset($_POST['account_id'], $_POST['fname'], $_POST['lname'], $_POST['username'], $_POST['email'], $_POST['role'])) {
            $account_id = $_POST['account_id'];
            $fname = $_POST['fname'];
            $lname = $_POST['lname'];
            $username = $_POST['username'];
            $email = $_POST['email'];
            $role = $_POST['role'];
            // user passwords shouldnt be accessibly by admins, so passowrds cannot be changed by the admin.
            $update_query = "UPDATE accounts SET FName = ?, LName = ?, Username = ?, Email = ?, Role = ? WHERE AccountID = ?";
            $stmt = $mysqli->prepare($update_query);
            // bind params as required in order to execute the query, make sure the param types is 5 times s and 1 time i for the account id as its a number.
            $stmt->bind_param("sssssi", $fname, $lname, $username, $email, $role, $account_id);
            if ($stmt->execute()) {
                // if the update is successfull redirect back to the account management page while showing success message.
                redirectWithMessage("index.php?page=admin_manage_accounts&p=". $p, "Account updated successfully!");
                exit;
            } else {
                //if the update failed redirect back to the manage accounts page with an error message
                redirectWithMessage("index.php?page=admin_manage_accounts&p=". $p, "Error updating account.");
                exit;
            }
        }
        exit;
    }
}

// handle account deletion request.

if (isset($_GET['delete_id'])) {
    //it will grab the account id from the delete button of where its clicked.
    $delete_id = $_GET['delete_id'];
    //execute the account deletion query.
    mysqli_query($mysqli, "DELETE FROM accounts WHERE AccountID = $delete_id");
    //redirect back to the manage accounts page showing success message.
    redirectWithMessage("index.php?page=admin_manage_accounts&p=" . $p, "Account deleted successfully!");
}

//those are used to determine whether there is a success or an error and show a message accordingly.
$successMessage = isset($_GET['success']) ? htmlspecialchars($_GET['success']) : '';
$errorMessage   = isset($_GET['error'])   ? htmlspecialchars($_GET['error'])   : '';
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="base-url" content="/project/">
    <title>Account Management - La Dolce Vita</title>
    <link rel="icon" type="image/x-icon" href="favicon.ico">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <link rel="stylesheet" href="/assets/css/styles.css">
</head>
<body>
    <div id="navbar">
        <!-- this fetches the navigation barr here -->
        <?php require_once realpath(__DIR__ . '/../../views/header.php'); ?>
    </div>
    
    <!-- this is the main container -->
    <div class="container mt-5 ">
        <h1>Manage Accounts</h1>

        <!-- this button will redirect back to the admin dashboard-->
        <a href="index.php?page=admin_dashboard" class="btn btn-secondary mb-3">Back to Dashboard</a>

        <!-- this is the search bar -->
        <form method="get" action="index.php" class="mb-3">
            <input type="hidden" name="page" value="admin_manage_accounts">
            <div class="input-group">
                <input type="text" class="form-control" name="search" placeholder="Search by name, username, email, or role" value="<?php echo htmlspecialchars($searchTerm); ?>">
                <div class="input-group-append">
                    <button class="btn btn-outline-secondary" type="submit">Search</button>
                </div>
            </div>
        </form>

        <!-- this form is used to either add a new account, or update an existing account-->
        <form action="index.php?page=admin_manage_accounts" method="post">
            <div class="form-row">
                <div class="form-group col-md-6">
                    <label for="fname">First Name</label>
                    <input type="text" class="form-control" id="fname" name="fname" required>
                </div>
                <div class="form-group col-md-6">
                    <label for="lname">Last Name</label>
                    <input type="text" class="form-control" id="lname" name="lname" required>
                </div>
            </div>
            <div class="form-row">
                <div class="form-group col-md-6">
                    <label for="username">Username</label>
                    <input type="text" class="form-control" id="username" name="username" required>
                </div>
                <div class="form-group col-md-6">
                    <label for="email">Email</label>
                    <input type="email" class="form-control" id="email" name="email" required>
                </div>
            </div>
            <div class="form-row">
                <div class="form-group col-md-6">
                    <label for="role">Role</label>
                    <select class="form-control" id="role" name="role" required>
                        <option value="admin">Admin</option>
                        <option value="user">User</option>
                    </select>
                </div>
                <!-- this password field is only shown if a user is being added, if the user is being updated it wont show ! -->
                <div class="form-group col-md-6" id="passwordField">
                    <label for="password">Password</label>
                    <input type="password" class="form-control" id="password" name="password">
                </div>
            </div>
            <input type="hidden" name="account_id" id="account_id">
            <button type="submit" name="add" class="btn btn-success">Add</button>
            <button type="submit" name="update" class="btn btn-primary">Update</button>
        </form>

        <hr>
        <div class="table-responsive">
            <!-- this table will fetch the accounts data from the database -->
            <table class="table table-dark table-hover">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>First Name</th>
                        <th>Last Name</th>
                        <th>Username</th>
                        <th>Email</th>
                        <th>Role</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <!-- iterate over the accounts table in the database and fetch all the accounts accordingly -->
                    <?php while($row = mysqli_fetch_assoc($result)): ?>
                        <tr>
                            <td><?php echo $row['AccountID']; ?></td>
                            <td><?php echo $row['FName']; ?></td>
                            <td><?php echo $row['LName']; ?></td>
                            <td><?php echo $row['Username']; ?></td>
                            <td><?php echo $row['Email']; ?></td>
                            <td><?php echo $row['Role']; ?></td>
                            <td>
                                <!-- this is a button that will trigger the editAccount function in the javascript below, function recieves account data-->
                                <a href="javascript:void(0);" class="btn btn-warning btn-sm" onclick="editAccount(<?php echo $row['AccountID']; ?>, '<?php echo $row['FName']; ?>', '<?php echo $row['LName']; ?>', '<?php echo $row['Username']; ?>', '<?php echo $row['Email']; ?>', '<?php echo $row['Role']; ?>')">Edit</a>
                                <!-- this here handles account deletion, once clicked delete_id is triggered and the 
                                account id  is stored in it allowing the php code above to delete the account from the database
                                please note that once the delete button is clicked a confirmation pop up will show asking 
                                to confirm deletion ! -->
                                <a href="index.php?page=admin_manage_accounts&delete_id=<?php echo $row['AccountID']; ?>" 
                                class="btn btn-danger btn-sm" 
                                onclick="return confirm('Are you sure you want to delete this account?');">
                                Delete
                                </a>                       
                            </td>
                        </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>

            <!-- pagination section -->
            <?php
                // this is base url for the pagination to work.
                //pagination will also work with a search term
                // note that $p variable is the pagination page
                $baseUrl = "index.php?page=admin_manage_accounts&";
                if ($searchTerm) {
                    $baseUrl .= "search=" . urlencode($searchTerm) . "&";
                }
                echo generatePaginationLinks($p, $totalPages, $baseUrl);
            ?>
        </div>
    </div>

    <script>
        //this script is needed for updating an existing account
        //it works basically by loading the user data into the form input fields
        //allowing the admin to update these fields and then update the data in the databse.
        function editAccount(id, fname, lname, username, email, role) {
            document.getElementById('account_id').value = id;
            document.getElementById('fname').value = fname;
            document.getElementById('lname').value = lname;
            document.getElementById('username').value = username;
            document.getElementById('email').value = email;
            document.getElementById('role').value = role;
            // this line below hides the password field when an admin is trying to update an
            //existing user.
            //please note that even if this input field wasnt hiddem, no password will be shown here
            // as the passwords arent being grabbed form the database
            document.getElementById('passwordField').style.display = 'none';
        }
    </script>

    <!-- those scripts are basically to show alert messages of success and errors in case if there is any -->
    <?php if ($successMessage): ?>
    <script>
        alert("Success: <?php echo $successMessage; ?>");
    </script>
    <?php endif; ?>

    <?php if ($errorMessage):
        echo '<script>
            alert("Error: <?php echo $errorMessage; ?>");</script>';
    endif;
    ?>

    <footer class="footer">
        <div class="container text-center">
            <p>&copy; 2024 La Dolce Vita. All rights Reserved. </p>
        </div>
    </footer>
    
    <script src="/assets/js/scripts.js"></script>
    <script src="https://kit.fontawesome.com/a076d05399.js"></script>
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.0/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>
