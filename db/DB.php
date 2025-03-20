<?php
/*
    this code should only run once
    this code creates a very small database that can help you set up the project for viewing

*/
function Createdb(){
    //those are the database connection configuration variables - change as needed.
    $servername = "localhost";
    $username = "root";
    $password = "";
    $dbname = "Restaurant";

    // Create connection
    $mysqli = mysqli_connect($servername, $username, $password);

    // Check Connection
    if (!$mysqli){
        die("Connection Failed : " . mysqli_connect_error());
    }

    //check if database exists or not , if not create it.
    $db_check = $mysqli->query("SHOW DATABASES LIKE '$dbname'");
    if ($db_check->num_rows == 0) {
        $sql = "CREATE DATABASE $dbname";
        if ($mysqli->query($sql) === TRUE) {
            echo "Database created successfully.<br>";
        } else {
            die("Error creating database: " . $mysqli->error);
        }
    }


    $mysqli->select_db($dbname);
   

    // sql codes list for each of the database tables
    $tables = [
        "Accounts"=>
            "CREATE TABLE IF NOT EXISTS Accounts (
            AccountID INT(11) AUTO_INCREMENT PRIMARY KEY,
            FName VARCHAR(30) NOT NULL,
            LName VARCHAR(30) NOT NULL,
            Username VARCHAR(30) NOT NULL UNIQUE,
            Password VARCHAR(255) NOT NULL, 
            Email VARCHAR(50) NOT NULL UNIQUE,
            Role ENUM('admin', 'user') NOT NULL DEFAULT 'user'
        )",

        "Categories"=>
            "CREATE TABLE IF NOT EXISTS Categories(
            CategoryID INT AUTO_INCREMENT PRIMARY KEY,
            CategoryName VARCHAR(255) NOT NULL
        )",

        "SubCategories"=> 
            "CREATE TABLE IF NOT EXISTS Subcategories (
            SubcategoryID INT AUTO_INCREMENT PRIMARY KEY,
            SubcategoryName VARCHAR(255) NOT NULL,
            CategoryID INT,
            FOREIGN KEY (CategoryID) REFERENCES Categories(CategoryID)
        )",
            
        "Menu" => 
            "CREATE TABLE IF NOT EXISTS Menu (
            MenuItemID INT AUTO_INCREMENT PRIMARY KEY,
            Name VARCHAR(255) NOT NULL,
            Description TEXT,
            Price DECIMAL(10,2) NOT NULL,
            Image Text,
            CategoryID INT,
            SubcategoryID INT,
            FOREIGN KEY (CategoryID) REFERENCES Categories(CategoryID),
            FOREIGN KEY (SubcategoryID) REFERENCES Subcategories(SubcategoryID)
        )",

        "Inqueries" => 
            "CREATE TABLE IF NOT EXISTS Inqueries (
            MessageID INT AUTO_INCREMENT PRIMARY KEY,
            Name VARCHAR(255) NOT NULL,
            Email VARCHAR(255) NOT NULL,
            Message TEXT NOT NULL,
            CreatedAt TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
            Status ENUM('Opened', 'Unopened', 'Closed', 'In Process') DEFAULT 'Unopened'
        )",

        "Reservations" => 
                "CREATE TABLE IF NOT EXISTS Reservations(
                ReservationID INT AUTO_INCREMENT PRIMARY KEY,
                CustomerName VARCHAR(255) NOT NULL,
                CustomerEmail VARCHAR(255) NOT NULL,
                CustomerPhone VARCHAR(255) NOT NULL,
                ReservationDate DATE NOT NULL,
                ReservationTime TIME NOT NULL,
                NumberOfPeople INT NOT NULL,
                SpecialRequest TEXT,
                CreatedAt TIMESTAMP DEFAULT CURRENT_TIMESTAMP
        )",

        "Orders" => "CREATE TABLE IF NOT EXISTS Orders (
            OrderID INT AUTO_INCREMENT PRIMARY KEY,
            CustomerID INT, 
            OrderDetails TEXT NOT NULL, 
            TotalPrice DECIMAL(10, 2) NOT NULL, 
            CreditCardLast4 CHAR(4) NOT NULL, 
            OrderDate TIMESTAMP DEFAULT CURRENT_TIMESTAMP
        )"
    ];

    // iterate over the sql codes in the tables list
    // and execute each sql code one by one
    foreach($tables as $table => $sql){
        if($mysqli -> query($sql) === TRUE){
            echo "$table table has been created successfully. <br>";
        }
        else{
            echo "Error creating $table table: ".$mysqli->error."<br>";
        }
    }
        
      

    // this will make sure that an admin account exists apon database creation
    // this is to allow the project admin to manage the system
    //every system must have at least 1 admin !
    $check_admin = "SELECT COUNT(*) AS count FROM Accounts WHERE Role = 'admin'";
    $result = $mysqli->query($check_admin);
    $row = $result->fetch_assoc();


    // if there is no admin account create one !
    if($row['count'] == 0) {
        // if there is no admin account create one, username is admin, and passowrd is hashed for security
        $admin_password = password_hash("admin",PASSWORD_BCRYPT); 
        $sql_insert_admin = "
            INSERT INTO Accounts (FName, LName, Username, Password, Email, Role) VALUES
            ('Admin', 'User', 'admin', '$admin_password', 'admin@gmail.com', 'admin');
        ";
        $mysqli->query($sql_insert_admin);
        echo "Admin account created successfully. <br>";
    }

    //this checks if the categories table is empty or not
    $check_categories = "SELECT COUNT(*) AS count FROM Categories";
    $result = $mysqli->query($check_categories);
    $row = $result->fetch_assoc();

    //if the table is empty , create the following categories and add them to the table !
    if($row['count'] == 0) {
        // only if table is empty insert the categories to the table
        $sql_insert_categories = "INSERT INTO Categories (CategoryName) VALUES 
        ('Appetizers'), ('Soups'), ('Salads'), ('First Courses'),
        ('Main Courses'), ('Side Dishes'), ('Desserts'), ('Beverages')";

        $mysqli->query($sql_insert_categories);
        echo "Categories inserted successfully. <br>";
    }


    // check for the subcategories as well, if the table is empty, add the following subcategories accoringly
    $check_subcategories = "SELECT COUNT(*) AS count FROM SubCategories";
    $result = $mysqli->query($check_subcategories);
    $row = $result->fetch_assoc();
    if($row['count'] == 0) {
        // Insert subcategories only if Categories table was empty
        $sql_insert_subcategories = "
            INSERT INTO Subcategories (SubcategoryName, CategoryID) VALUES
            ('Cold Appetizers', (SELECT CategoryID FROM Categories WHERE CategoryName = 'Appetizers')),
            ('Hot Appetizers', (SELECT CategoryID FROM Categories WHERE CategoryName = 'Appetizers')),
            ('Vegetarian Soups', (SELECT CategoryID FROM Categories WHERE CategoryName = 'Soups')),
            ('Meat-Based Soups', (SELECT CategoryID FROM Categories WHERE CategoryName = 'Soups')),
            ('Leafy Salads', (SELECT CategoryID FROM Categories WHERE CategoryName = 'Salads')),
            ('Mixed Salads', (SELECT CategoryID FROM Categories WHERE CategoryName = 'Salads')),
            ('Pasta', (SELECT CategoryID FROM Categories WHERE CategoryName = 'First Courses')),
            ('Risotto', (SELECT CategoryID FROM Categories WHERE CategoryName = 'First Courses')),
            ('Meat Dishes', (SELECT CategoryID FROM Categories WHERE CategoryName = 'Main Courses')),
            ('Seafood Dishes', (SELECT CategoryID FROM Categories WHERE CategoryName = 'Main Courses')),
            ('Vegetables', (SELECT CategoryID FROM Categories WHERE CategoryName = 'Side Dishes')),
            ('Potatoes', (SELECT CategoryID FROM Categories WHERE CategoryName = 'Side Dishes')),
            ('Cakes', (SELECT CategoryID FROM Categories WHERE CategoryName = 'Desserts')),
            ('Pastries', (SELECT CategoryID FROM Categories WHERE CategoryName = 'Desserts')),
            ('Gelato', (SELECT CategoryID FROM Categories WHERE CategoryName = 'Desserts')),
            ('Hot Drinks', (SELECT CategoryID FROM Categories WHERE CategoryName = 'Beverages')),
            ('Cold Drinks', (SELECT CategoryID FROM Categories WHERE CategoryName = 'Beverages'));
        ";

        $mysqli->query($sql_insert_subcategories);
        echo "SubCategories inserted successfully. <br>";
    }
        

    //this will check if the menu is empty or not .
    $check_menu="SELECT COUNT(*) AS CNT FROM Menu";
    $result=$mysqli->query($check_menu);
    $row= $result->fetch_assoc();
    
    //if the menu is indeed empty, it will insert a few menu items so that the user can see something in the menu page.
    if($row['CNT']==0){
        $menu_items = [
            ["Summer Spaghetti", "Refreshing summer pasta", 20.0, "https://i.ibb.co/fY3WxrPZ/Summer-Spaghetti.jpg", 5, 7],
            ["Lasagna di San Gimignano", "Classic Italian Lasagna", 25.0, "https://i.ibb.co/KpWS093Y/Lasagna-di-San-Gimignano.jpg", 5, 7],
            ["Buffalo Mozzarella Pizza", "Delicious Pizza with Burrata", 9.99, "https://i.ibb.co/KpBy6w9H/Buffalo-Mozzarella-Burrata-Pizza.jpg", 5, 18],
            [ 'Barolo Red Wine','from the Piedmont region of Italy, Barolo is a full-bodied red wine with an alcohol level at the higher end (so be careful).', 5.99,'https://i.ibb.co/DfW7zPkf/Barolo-Red-Wine.jpg', 8, 19],
            ['Holiday Bitter Greens Salad', 'Holiday Bitter Greens Salad is a festive and flavorful salad that combines the crispness of fresh apples with the nutty crunch of almonds and the sweet-tart burst of pomegranate arils. Blue cheese crumbles add a rich, creamy contrast, while a dressing made from red wine vinegar, Dijon vinegar, olive oil, and honey ties the ingredients together with a balanced blend of tangy and sweet flavors. This salad is perfect for the holiday season, offering a refreshing yet hearty dish that pairs well with a variety of main courses.', 4.99,'https://i.ibb.co/cS7QbbSR/Holiday-Bitter-Greens-Salad-With-Apples-Almonds-Pomegranate-Arils-Blue-Cheese-Crumbles.jpg',3, 6]
        ];

        //iterate over each menu item and add it to the database
        //please note that the images must be a URL !
        foreach ($menu_items as $item){
                
            $sql_insert_menu = "INSERT INTO Menu (Name, Description, Price, Image, CategoryID, SubcategoryID) VALUES 
            ('$item[0]', '$item[1]', '$item[2]', '$item[3]', '$item[4]', '$item[5]')";
                
            $mysqli->query($sql_insert_menu);
        }
        echo "Menu items inserted successfully.<br>";
    }

    echo "Database setup complete.";
    $mysqli->close();
    
}
// this will trigger the database creation function.
Createdb();
?>
