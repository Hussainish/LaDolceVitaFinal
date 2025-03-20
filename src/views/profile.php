<?php 
// require the functions we need from utils.php file  
require_once __DIR__ . '/../../utils/utils.php';

// check if a user is signed in, you must be signed in to see your profile page.
// in case a user isnt sign in it redirects the user to the loin page.
if (!isset($_SESSION['username'])) {
    header("Location: index.php?page=auth");
    exit;
}


// grab the user info from session in case they exist.
$Fname = isset($_SESSION['Fname']) ? $_SESSION['Fname'] : 'First Name';
$Lname = isset($_SESSION['Lname']) ? $_SESSION['Lname'] : 'Last Name';
$email = isset($_SESSION['Email']) ? $_SESSION['Email'] : 'Email';


// the following variables are required to handle success and error messages while
// updating user info.
$successMessage = isset($_GET['success']) ? htmlspecialchars($_GET['success']) : '';
$errorMessage   = isset($_GET['error'])   ? htmlspecialchars($_GET['error'])   : '';
$modal          = isset($_GET['modal'])    ? $_GET['modal'] : '';
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta name="base-url" content="/project/">
  <title>Profile - La Dolce Vita</title>
  <link rel="icon" type="image/x-icon" href="favicon.ico">
  <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
  <link rel="stylesheet" href="/public/assets/css/styles.css">
  <style>
    body {
      background-color: #f8f9fa;
      font-family: 'Helvetica Neue', Helvetica, Arial, sans-serif;
      color: #333;
    }
    main .container {
      margin-top: 30px;
      margin-bottom: 30px;
    }
    h1 {
      text-align: center;
      margin: 30px 0;
      font-weight: bold;
      color: #333;
    }
    .card {
      border-radius: 10px;
      box-shadow: 0 4px 12px rgba(0,0,0,0.1);
      margin-bottom: 20px;
    }
    .card-body {
      padding: 30px;
    }
    .card-title {
      font-size: 1.5rem;
      margin-bottom: 15px;
    }
    .card-text {
      font-size: 1.1rem;
      margin-bottom: 10px;
    }
    .btn {
      border-radius: 20px;
    }
    /* Modal styling */
    .modal-content {
      border-radius: 10px;
    }
    .modal-header, .modal-footer {
      border: none;
    }
    .modal-header {
      background-color: #f1f1f1;
    }
  </style>
</head>
<body>
  <header id="navbar">
    <?php require_once 'header.php'; ?>
  </header>

  <main>
    <div class="container">
      <h1>User Profile</h1>
      <div class="card">
        <div class="card-body">
          <h5 class="card-title">Welcome, <?php echo htmlspecialchars($_SESSION['username'], ENT_QUOTES, 'UTF-8'); ?></h5>
          <p class="card-text"><strong>First Name:</strong> <?php echo htmlspecialchars($Fname, ENT_QUOTES, 'UTF-8'); ?></p>
          <p class="card-text"><strong>Last Name:</strong> <?php echo htmlspecialchars($Lname, ENT_QUOTES, 'UTF-8'); ?></p>
          <p class="card-text"><strong>Email:</strong> <?php echo htmlspecialchars($email, ENT_QUOTES, 'UTF-8'); ?></p>
          <p class="card-text"><strong>Password:</strong> ************</p>
          <div class="mb-3">
            <button type="button" class="btn btn-primary mr-2" data-toggle="modal" data-target="#editNameModal">
              Edit Name
            </button>
            <button type="button" class="btn btn-primary mr-2" data-toggle="modal" data-target="#editEmailModal">
              Edit Email
            </button>
            <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#editPasswordModal">
              Change Password
            </button>
          </div>
          <a href="index.php?page=logout" class="btn btn-danger">Logout</a>
        </div>
      </div>
    </div>
  </main>

  <!-- Modal for Editing Name -->
  <div class="modal fade" id="editNameModal" tabindex="-1" aria-labelledby="editNameModalLabel" aria-hidden="true">
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="editNameModalLabel">Edit Name</h5>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <form action="index.php?page=edit_name" method="post">
          <div class="modal-body">
            <div class="form-group">
              <label for="Fname">First Name</label>
              <input type="text" class="form-control" id="Fname" name="Fname" value="<?php echo $Fname; ?>" required>
            </div>
            <div class="form-group">
              <label for="Lname">Last Name</label>
              <input type="text" class="form-control" id="Lname" name="Lname" value="<?php echo $Lname; ?>" required>
            </div>
            <div class="form-group">
              <label for="currentPassword">Current Password</label>
              <input type="password" class="form-control" id="currentPassword" name="currentPassword" required>
            </div>
          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
            <button type="submit" class="btn btn-primary">Save changes</button>
          </div>
        </form>
      </div>
    </div>
  </div>

  <!-- Modal for Editing Email -->
  <div class="modal fade" id="editEmailModal" tabindex="-1" aria-labelledby="editEmailModalLabel" aria-hidden="true">
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="editEmailModalLabel">Edit Email</h5>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <form action="index.php?page=edit_email" method="post">
          <div class="modal-body">
            <div class="form-group">
              <label for="newEmail">New Email</label>
              <input type="email" class="form-control" id="newEmail" name="newEmail" required>
            </div>
            <div class="form-group">
              <label for="confirmEmail">Confirm New Email</label>
              <input type="email" class="form-control" id="confirmEmail" name="confirmEmail" required>
            </div>
            <div class="form-group">
              <label for="password">Password</label>
              <input type="password" class="form-control" id="password" name="password" required>
            </div>
          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
            <button type="submit" class="btn btn-primary">Save changes</button>
          </div>
        </form>
      </div>
    </div>
  </div>

  <!-- Modal for Changing Password -->
  <div class="modal fade" id="editPasswordModal" tabindex="-1" aria-labelledby="editPasswordModalLabel" aria-hidden="true">
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="editPasswordModalLabel">Change Password</h5>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <form action="index.php?page=change_password" method="post">
          <div class="modal-body">
            <div class="form-group">
              <label for="oldPassword">Old Password</label>
              <input type="password" class="form-control" id="oldPassword" name="oldPassword" required>
            </div>
            <div class="form-group">
              <label for="newPassword">New Password</label>
              <input type="password" class="form-control" id="newPassword" name="newPassword" required>
            </div>
            <div class="form-group">
              <label for="confirmPassword">Confirm New Password</label>
              <input type="password" class="form-control" id="confirmPassword" name="confirmPassword" required>
            </div>
          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
            <button type="submit" class="btn btn-primary">Save changes</button>
          </div>
        </form>
      </div>
    </div>
  </div>

  <!-- Success and Error Handling -->
  <?php if ($successMessage): ?>
  <script>
      alert("Success: <?php echo $successMessage; ?>");
  </script>
  <?php endif; ?>

  <?php if ($errorMessage): ?>
  <script>
      alert("Error: <?php echo $errorMessage; ?>");
      <?php if ($modal === 'name'): ?>
          $(document).ready(function(){
              $('#editNameModal').modal('show');
          });
      <?php elseif ($modal === 'email'): ?>
          $(document).ready(function(){
              $('#editEmailModal').modal('show');
          });
      <?php elseif ($modal === 'password'): ?>
          $(document).ready(function(){
              $('#editPasswordModal').modal('show');
          });
      <?php endif; ?>
  </script>
  <?php endif; ?>

  <footer class="footer">
    <div class="container text-center">
        <p>&copy; 2024 La Dolce Vita. All rights Reserved. </p>
    </div>
  </footer>
  
  <script src="../../public/assets/js/script.js"></script>
  <script src="https://kit.fontawesome.com/a076d05399.js"></script>
  <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.0/dist/umd/popper.min.js"></script>
  <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>
