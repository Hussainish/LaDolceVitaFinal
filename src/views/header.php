<?php

include realpath(__DIR__ . '/../../src/controller/session_status.php');

//this code makes sure to use the correct base url

/*$protocol = (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on') ? "https" : "http";
$host = $_SERVER['HTTP_HOST'];
$project_folder = '/project'; // Adjust if your project folder name changes.
$base_url = $protocol . '://' . $host . $project_folder;*/
?>

<nav class="navbar navbar-expand-lg navbar-dark bg-dark">
    <a class="navbar-brand" href="index.php?=homepage">La Dolce Vita</a>
    <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNav" 
            aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
        <span class="navbar-toggler-icon"></span>
    </button>
    <div class="collapse navbar-collapse" id="navbarNav">
        <ul class="navbar-nav ml-auto">
            <!-- public links -->
            <li class="nav-item">
                <a class="nav-link" href="index.php?page=homepage">Home Page</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href= "index.php?page=menu">Menu</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="index.php?page=reservation">Reservation</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="index.php?page=about">About Us</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="index.php?page=contactus">Contact us</a>
            </li>
            <!-- shopping cart -->
            <li class="nav-item">
                <a class="nav-link" href="index.php?page=cart">
                    <i class="fas fa-shopping-cart"></i>
                </a>
            </li>
            <!-- user-specific Links , like profile, orders and reservations and logout button-->
            <?php if ($isLoggedIn): ?>
                <!-- in case the user is an admin, show admin dashboard -->
                <?php if ($userRole == 'admin'): ?>
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" href="#" id="userDropdown" role="button" 
                           data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                            <i class="fas fa-user"></i>
                        </a>
                        <div class="dropdown-menu dropdown-menu-right" aria-labelledby="userDropdown">
                            <a class="dropdown-item" href="index.php?page=admin_dashboard">Management</a>
                            <a class="dropdown-item" href="index.php?page=profile">Profile</a>
                            <div class="dropdown-divider"></div>
                            <a class="dropdown-item" href="index.php?page=logout">Logout</a>
                        </div>
                    </li>
                    <!-- in case user isnt an admin, hide admin dashboard -->
                <?php else: ?>
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" href="#" id="userDropdown" role="button" 
                           data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                            <i class="fas fa-user"></i>
                        </a>
                        <div class="dropdown-menu dropdown-menu-right" aria-labelledby="userDropdown">
                            <a class="dropdown-item" href="index.php?page=profile">Profile</a>
                            <a class="dropdown-item" href="index.php?page=orders_and_reservations">Orders & Reservations</a>
                            <div class="dropdown-divider"></div>
                            <a class="dropdown-item" href="index.php?page=logout">Logout</a>
                        </div>
                    </li>
                <?php endif; ?>
            <?php else: ?>
                <!-- in case no user is logged in, show the fa-user icon and redirect to the login/signup page -->
                <li class="nav-item">
                    <a class="nav-link" href="index.php?page=auth">
                        <i class="fas fa-user"></i>
                    </a>
                </li>
            <?php endif; ?>
        </ul>
    </div>
</nav>

<style>
    .navbar-dark .dropdown-menu {
        background-color: #343a40;
        border: none;
    }
    .navbar-dark .dropdown-menu .dropdown-item {
        color: #fff;
    }
    .navbar-dark .dropdown-menu .dropdown-item:hover,
    .navbar-dark .dropdown-menu .dropdown-item:focus {
        background-color: #495057;
        color: #fff;
    }
</style>
