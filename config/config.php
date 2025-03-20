<?php

// Define the absolute path to the project root.
define('PROJECT_ROOT', str_replace('\\', '/', realpath(__DIR__ . '/../')) . '/');

// Database configuration
/*
 Please if you're planning on testing the project on your localhost 
 change the database configuration credintails to the ones that suits your localhost
 please note that if you need to build a database that suits the project you can either run DB.php from the db folder once
 or you can use the restaurant.sql file that i have included in the project from the db folder 
 to import a small working database  that suits the project
 */
define('DB_HOST', 'localhost'); // mysql server host - can be localhost
define('DB_USER', 'root'); // mysql username - in case of localhost its mostly root
define('DB_PASS', ''); // mysql password 
define('DB_NAME', 'restaurant'); // database name


// Create a database connection using mysqli
$mysqli = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME);

// Check connection
if ($mysqli->connect_error) {
    die("Database Connection Error: " . $mysqli->connect_error);
}
?>
