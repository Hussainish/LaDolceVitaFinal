<?php

// Load configuration, utilities, and session status.
require_once __DIR__ . '/config/config.php';
require_once __DIR__ . '/utils/utils.php';
require_once __DIR__ . '/src/controller/session_status.php';

//Check which page is clicked to route to that page accordingly.
$page = isset($_GET['page']) ? $_GET['page'] : null;

// Simple routing:
switch ($page) {

  // Public routes :
  
  //Home page route:
  case 'homepage':
    require_once realpath(__DIR__ . '/homepage.html');
    break;
  
  //Menu page route:
  case 'menu':
    require_once realpath(__DIR__ . '/src/controller/menu.php');
    break;

  //Reservation page route.
  case 'reservation':
    require_once realpath(__DIR__ . '/reservations.html');
    break;
	
  //About Us page route.  
  case 'about':
    require_once realpath(__DIR__ . '/about.html');
    break;

  //Contact Us page route.
  case 'contactus':
    require_once realpath(__DIR__ . '/contact.html');
    break;
  
  //Shopping cart route. - contains the shopping cart page and logic.
  case 'cart':
    require_once realpath(__DIR__ . '/src/controller/cart.php');
    break;

  //Login/Signup route.
  case 'auth':
    require_once realpath(__DIR__ . '/auth.html');
    break;
  
  //Profile page route.
  case 'profile':
    require_once realpath(__DIR__ . '/src/views/profile.php');
    break;
  
  //Logout route - runs the logout script to force session destruction.
  case 'logout':
    require_once realpath(__DIR__ . '/src/controller/logout.php');
    break;
  
  
  //Orders And Reservations page route - this page shows the user's orders and reservations.
  case 'orders_and_reservations':
    require_once realpath(__DIR__ . '/src/controller/myordersandreservations.php');
    break;
  
  
  //Login logic route - used to trigger the login proccess.
  case 'login':
    require_once realpath(__DIR__ . '/src/controller/login.php');
    break;
  
  
  //Signup logic route - used to trigger the signup proccess.
  case 'signup':
    require_once realpath(__DIR__ . '/src/controller/signup.php');
    break;
  
  // Admin routes:
  
  //Admin Dashboard page route - redirects to the admin dashboard page.
  case 'admin_dashboard':
    if (!isset($_SESSION['isLoggedIn']) || $_SESSION['isLoggedIn'] !== true || $_SESSION['role'] !== 'admin') {
      header("Location: index.php?page=auth&login_error=" . urlencode("Admin only."));
      exit;
    }
    require_once realpath(__DIR__ . '/src/controller/admin/Management.html');
    break;
  
  
  //Manage Accounts page route - in case an admin wants to view or handle a user account.
  case 'admin_manage_accounts':
    if (!isset($_SESSION['isLoggedIn']) || $_SESSION['isLoggedIn'] !== true || $_SESSION['role'] !== 'admin') {
      header("Location: index.php?page=auth&login_error=" . urlencode("Admin only."));
      exit;
    }
    require_once realpath(__DIR__ . '/src/controller/admin/manage_accounts.php');
    break;
  
  //Menu Management page route - used to add, update or delete menu items.
  case 'admin_manage_menu':
    if (!isset($_SESSION['isLoggedIn']) || $_SESSION['isLoggedIn'] !== true || $_SESSION['role'] !== 'admin') {
      header("Location: index.php?page=auth&login_error=" . urlencode("Admin only."));
      exit;
    }
    require_once realpath(__DIR__ . '/src/controller/admin/manage_menu.php');
    break;
  
  
  //Inqueries page route - page to view user requests and handle them accordingly.
  case 'admin_inqueries':
    if (!isset($_SESSION['isLoggedIn']) || $_SESSION['isLoggedIn'] !== true || $_SESSION['role'] !== 'admin') {
      header("Location: index.php?page=auth&login_error=" . urlencode("Admin only."));
      exit;
    }
    require_once realpath(__DIR__ . '/src/controller/admin/manage_messages.php');
    break;
  
  
  //Orders list page route - for admins to view completed orders in case of a customer requiring assistance.
  case 'admin_orders_list':
    if (!isset($_SESSION['isLoggedIn']) || $_SESSION['isLoggedIn'] !== true || $_SESSION['role'] !== 'admin') {
      header("Location: index.php?page=auth&login_error=" . urlencode("Admin only."));
      exit;
    }
    require_once realpath(__DIR__ . '/src/controller/admin/Orders.php');
    break;
  
  
  //Reservations Management page route - page showing all reservations , allows admins to manually add, update or cancel reservations.
  case 'admin_manage_reservations':
    if (!isset($_SESSION['isLoggedIn']) || $_SESSION['isLoggedIn'] !== true || $_SESSION['role'] !== 'admin') {
      header("Location: index.php?page=auth&login_error=" . urlencode("Admin only."));
      exit;
    }
    require_once realpath(__DIR__ . '/src/controller/admin/ReservationsManagement.php');
    break;

  // Controller routes - used to handle logic and proccesses needed for the other pages :
  
  //Reserve controller route - forces reservation making so customers can reserve a table.
  case 'reserve_table':
    require_once realpath(__DIR__ . '/src/controller/reservation.php');
    break;

  //Inqueries controller route - allows users to send a message to the staff for assistance.
  case 'send_message':
    require_once realpath(__DIR__ . '/src/controller/contact.php');
    break;
  
  
  //Edit Email controller route - allows the users to update their email address from their profile page.
  case 'edit_email':
    require_once realpath(__DIR__ . '/src/controller/update_email.php');
    break;
  
  
  //Edit Name controller route - allows the users to update their First Name and Last Name from their profile page.
  case 'edit_name':
    require_once realpath(__DIR__ . '/src/controller/update_name.php');
    break;
  
  
  //Change Password controller route - allows users to change their password from their profile page.
  case 'change_password':
    require_once realpath(__DIR__ . '/src/controller/update_password.php');
    break;
  
  
  //default route - this route redirects to the homepage of the website once accessed.
  default:
    require_once realpath(__DIR__ . '/homepage.html');
    break;
}
?>
