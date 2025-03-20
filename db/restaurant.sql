-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1:3306
-- Generation Time: Mar 20, 2025 at 02:11 PM
-- Server version: 9.1.0
-- PHP Version: 8.3.14

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `restaurant`
--

-- --------------------------------------------------------

--
-- Table structure for table `accounts`
--

DROP TABLE IF EXISTS `accounts`;
CREATE TABLE IF NOT EXISTS `accounts` (
  `AccountID` int NOT NULL AUTO_INCREMENT,
  `FName` varchar(30) COLLATE utf8mb4_general_ci NOT NULL,
  `LName` varchar(30) COLLATE utf8mb4_general_ci NOT NULL,
  `Username` varchar(30) COLLATE utf8mb4_general_ci NOT NULL,
  `Password` varchar(255) COLLATE utf8mb4_general_ci NOT NULL,
  `Email` varchar(50) COLLATE utf8mb4_general_ci NOT NULL,
  `Role` enum('admin','user') COLLATE utf8mb4_general_ci NOT NULL DEFAULT 'user',
  PRIMARY KEY (`AccountID`),
  UNIQUE KEY `Username` (`Username`),
  UNIQUE KEY `Email` (`Email`)
) ENGINE=MyISAM AUTO_INCREMENT=31 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `accounts`
--

INSERT INTO `accounts` (`AccountID`, `FName`, `LName`, `Username`, `Password`, `Email`, `Role`) VALUES
(1, 'Admin', 'User', 'admin', '$2y$10$ZXbpK8wXTW4/rmf2DQiVhu0.iQHqAxGnBXZPQV839c9gwYw1y3IN2', 'eweay@gmail.com', 'admin'),
(10, 'John', 'Doe', 'johndoe', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEaB3zC6ZGdWNn5FK0mG6X0Jj1C2', 'john.doe@example.com', 'user'),
(11, 'Jane', 'Smith', 'janesmith', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEaB3zC6ZGdWNn5FK0mG6X0Jj1C2', 'jane.smith@example.com', 'user'),
(13, 'Emily', 'Davis', 'emilydavis', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEaB3zC6ZGdWNn5FK0mG6X0Jj1C2', 'emily.davis@example.com', 'user'),
(14, 'Michael', 'Brown', 'michaelbrown1', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEaB3zC6ZGdWNn5FK0mG6X0Jj1C2', 'michael.brown@example.com', 'user'),
(15, 'Sarah', 'Miller', 'sarahmiller', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEaB3zC6ZGdWNn5FK0mG6X0Jj1C2', 'sarah.miller@example.com', 'user'),
(16, 'David', 'Wilson', 'davidwilson', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEaB3zC6ZGdWNn5FK0mG6X0Jj1C2', 'david.wilson@example.com', 'user'),
(17, 'Laura', 'Moore', 'lauramoore', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEaB3zC6ZGdWNn5FK0mG6X0Jj1C2', 'laura.moore@example.com', 'admin'),
(18, 'Chris', 'Taylor', 'christaylor', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEaB3zC6ZGdWNn5FK0mG6X0Jj1C2', 'chris.taylor@example.com', 'user'),
(29, 'Olivia', 'Noob', 'Olivia1', '$2y$10$u8ygVosa5JA4DQ7Hcxgb3u90/PgT1Og55bMip3SQPeUDXfiiRgnce', 'OlivaNoob@gmail.com', 'user');

-- --------------------------------------------------------

--
-- Table structure for table `categories`
--

DROP TABLE IF EXISTS `categories`;
CREATE TABLE IF NOT EXISTS `categories` (
  `CategoryID` int NOT NULL AUTO_INCREMENT,
  `CategoryName` varchar(255) COLLATE utf8mb4_general_ci NOT NULL,
  PRIMARY KEY (`CategoryID`)
) ENGINE=MyISAM AUTO_INCREMENT=9 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `categories`
--

INSERT INTO `categories` (`CategoryID`, `CategoryName`) VALUES
(1, 'Appetizers'),
(2, 'Soups'),
(3, 'Salads'),
(4, 'First Courses'),
(5, 'Main Courses'),
(6, 'Side Dishes'),
(7, 'Desserts'),
(8, 'Beverages');

-- --------------------------------------------------------

--
-- Table structure for table `inqueries`
--

DROP TABLE IF EXISTS `inqueries`;
CREATE TABLE IF NOT EXISTS `inqueries` (
  `MessageID` int NOT NULL AUTO_INCREMENT,
  `Name` varchar(255) COLLATE utf8mb4_general_ci NOT NULL,
  `Email` varchar(255) COLLATE utf8mb4_general_ci NOT NULL,
  `Message` text COLLATE utf8mb4_general_ci NOT NULL,
  `CreatedAt` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `Status` enum('Opened','Unopened','Closed','In Process') COLLATE utf8mb4_general_ci DEFAULT 'Unopened',
  PRIMARY KEY (`MessageID`)
) ENGINE=MyISAM AUTO_INCREMENT=53 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `inqueries`
--

INSERT INTO `inqueries` (`MessageID`, `Name`, `Email`, `Message`, `CreatedAt`, `Status`) VALUES
(52, 'Jane Smith', 'jane.smith@example.com', 'What are your opening hours?', '2025-03-03 12:02:36', 'Closed'),
(51, 'Chris Taylor', 'chris.taylor@example.com', 'Do you offer vegetarian options?', '2025-03-11 12:02:36', 'Unopened'),
(50, 'Laura Moore', 'laura.moore@example.com', 'I have a question about the menu.', '2025-02-18 12:02:36', 'In Process'),
(49, 'Sarah Miller', 'sarah.miller@example.com', 'Can I modify my reservation date?', '2025-03-20 12:02:36', 'Closed'),
(48, 'Laura Moore', 'laura.moore@example.com', 'Can I modify my reservation date?', '2025-03-06 12:02:36', 'Unopened'),
(47, 'Jane Smith', 'jane.smith@example.com', 'What payment methods do you accept?', '2025-02-20 12:02:36', 'Unopened'),
(46, 'Laura Moore', 'laura.moore@example.com', 'Can I modify my reservation date?', '2025-02-28 12:02:36', 'Closed'),
(45, 'Jane Smith', 'jane.smith@example.com', 'I encountered an issue while booking a table.', '2025-03-05 12:02:36', 'In Process'),
(44, 'Emily Davis', 'emily.davis@example.com', 'Is your restaurant wheelchair accessible?', '2025-02-19 12:02:36', 'In Process'),
(43, 'Michael Brown', 'michael.brown@example.com', 'Do you provide catering services?', '2025-03-06 12:02:36', 'In Process');

-- --------------------------------------------------------

--
-- Table structure for table `menu`
--

DROP TABLE IF EXISTS `menu`;
CREATE TABLE IF NOT EXISTS `menu` (
  `MenuItemID` int NOT NULL AUTO_INCREMENT,
  `Name` varchar(255) COLLATE utf8mb4_general_ci NOT NULL,
  `Description` text COLLATE utf8mb4_general_ci,
  `Price` decimal(10,2) NOT NULL,
  `Image` text COLLATE utf8mb4_general_ci,
  `CategoryID` int DEFAULT NULL,
  `SubcategoryID` int DEFAULT NULL,
  PRIMARY KEY (`MenuItemID`),
  KEY `CategoryID` (`CategoryID`),
  KEY `SubcategoryID` (`SubcategoryID`)
) ENGINE=MyISAM AUTO_INCREMENT=22 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `menu`
--

INSERT INTO `menu` (`MenuItemID`, `Name`, `Description`, `Price`, `Image`, `CategoryID`, `SubcategoryID`) VALUES
(1, 'Summer Spaghetti', 'Refreshing summer pasta', 25.00, 'https://i.ibb.co/fY3WxrPZ/Summer-Spaghetti.jpg', 5, 7),
(2, 'Lasagna di San Gimignano', 'Classic Italian Lasagna', 25.00, 'https://i.ibb.co/KpWS093Y/Lasagna-di-San-Gimignano.jpg', 5, 7),
(3, 'Buffalo Mozzarella Pizza', 'Delicious Pizza with Burrata', 9.99, 'https://i.ibb.co/KpBy6w9H/Buffalo-Mozzarella-Burrata-Pizza.jpg', 5, 18),
(4, 'Barolo Red Wine', 'from the Piedmont region of Italy, Barolo is a full-bodied red wine with an alcohol level at the higher end (so be careful).', 5.99, 'https://i.ibb.co/DfW7zPkf/Barolo-Red-Wine.jpg', 8, 17),
(5, 'Holiday Bitter Greens Salad', 'Holiday Bitter Greens Salad is a festive and flavorful salad that combines the crispness of fresh apples with the nutty crunch of almonds and the sweet-tart burst of pomegranate arils. Blue cheese crumbles add a rich, creamy contrast, while a dressing made from red wine vinegar, Dijon vinegar, olive oil, and honey ties the ingredients together with a balanced blend of tangy and sweet flavors. This salad is perfect for the holiday season, offering a refreshing yet hearty dish that pairs well with a variety of main courses.', 7.00, 'https://i.ibb.co/cS7QbbSR/Holiday-Bitter-Greens-Salad-With-Apples-Almonds-Pomegranate-Arils-Blue-Cheese-Crumbles.jpg', 3, 6),
(7, 'Coca Cola 1.5 Liter', 'Coca Cola Bottle.', 8.00, 'https://res.cloudinary.com/duns7qt5s/image/upload/v1741808523/upq4qlif6k5vkiyomco3.jpg', 8, 17),
(8, 'Eggnog Cheesecake', 'eggnog cheesecake.', 10.00, 'https://res.cloudinary.com/duns7qt5s/image/upload/v1741864798/hd2nok3wlxtqgdazjcpl.jpg', 7, 13),
(9, 'Italian Hot Chocolate', 'A glass of delicious Italian hot chocolate.', 5.00, 'https://res.cloudinary.com/duns7qt5s/image/upload/v1741866140/xtz8h45o50vwynp1xrzx.jpg', 8, 16),
(10, 'Margherita Pizza', 'Classic Italian pizza with tomato sauce, fresh mozzarella, and basil.', 15.00, 'https://res.cloudinary.com/duns7qt5s/image/upload/v1741890696/mofjrgflb3rah3cajocp.webp', 5, 9),
(11, 'Spaghetti Carbonara', 'Traditional pasta dish with eggs, Pecorino Romano, pancetta, and black pepper.', 14.99, 'https://res.cloudinary.com/duns7qt5s/image/upload/v1741890658/l60qtpgfhrek4ffdycqy.webp', 4, 7),
(12, 'Fettuccine Alfredo', 'Creamy pasta with Parmesan cheese and butter sauce.', 13.99, 'https://res.cloudinary.com/duns7qt5s/image/upload/v1741890728/vdhaowfkkawzx297g4o7.jpg', 4, 7),
(13, 'Risotto alla Milanese', 'Saffron-infused risotto with a rich and creamy texture.', 16.99, 'https://res.cloudinary.com/duns7qt5s/image/upload/v1741890788/rccsbjwluvj4oeobe0uj.webp', 4, 8),
(14, 'Lasagna Bolognese', 'Layered pasta with rich meat sauce, béchamel, and Parmesan cheese.', 17.99, 'https://res.cloudinary.com/duns7qt5s/image/upload/v1741890823/jh4j195dtcpvqvxzoluh.webp', 5, 9),
(15, 'Tiramisu', 'Coffee-flavored dessert with ladyfingers, mascarpone cream, and cocoa.', 7.99, 'https://res.cloudinary.com/duns7qt5s/image/upload/v1741890855/cpsh3s2qbytonfbsioar.webp', 7, 14),
(16, 'Panna Cotta', 'Creamy, chilled dessert served with a berry compote.', 8.00, 'https://res.cloudinary.com/duns7qt5s/image/upload/v1741890899/dvvqsa9u9xx7zx2eutdx.webp', 7, 13),
(17, 'Bruschetta', 'Grilled bread rubbed with garlic and topped with diced tomatoes and basil.', 5.99, 'https://res.cloudinary.com/duns7qt5s/image/upload/v1741890924/xc1jnl2lem5x22eqizwk.jpg', 1, 1),
(18, 'Caprese Salad', 'Fresh tomatoes, mozzarella, and basil drizzled with olive oil.', 8.99, 'https://res.cloudinary.com/duns7qt5s/image/upload/v1741890948/wkot302oyaikp4yd9hda.jpg', 3, 6),
(19, 'Gelato', 'Traditional Italian ice cream available in various flavors.', 4.99, 'https://res.cloudinary.com/duns7qt5s/image/upload/v1741890979/pjr7lwictrq3aavyhre0.jpg', 7, 15);

-- --------------------------------------------------------

--
-- Table structure for table `orders`
--

DROP TABLE IF EXISTS `orders`;
CREATE TABLE IF NOT EXISTS `orders` (
  `OrderID` int NOT NULL AUTO_INCREMENT,
  `CustomerID` int DEFAULT NULL,
  `OrderDetails` text COLLATE utf8mb4_general_ci NOT NULL,
  `TotalPrice` decimal(10,2) NOT NULL,
  `CreditCardLast4` char(4) COLLATE utf8mb4_general_ci NOT NULL,
  `OrderDate` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`OrderID`)
) ENGINE=MyISAM AUTO_INCREMENT=38 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `orders`
--

INSERT INTO `orders` (`OrderID`, `CustomerID`, `OrderDetails`, `TotalPrice`, `CreditCardLast4`, `OrderDate`) VALUES
(36, 15, 'Barolo Red Wine (x1), Spaghetti Carbonara (x3), Panna Cotta (x1)', 59.97, '7147', '2025-03-03 12:06:49'),
(35, 16, 'Summer Spaghetti (x2), Bruschetta (x2)', 67.97, '5732', '2025-03-04 12:06:49'),
(33, 29, 'Barolo Red Wine (x2)', 11.98, '4423', '2025-03-18 12:06:49'),
(34, 16, 'Panna Cotta (x1), Italian Hot Chocolate (x1), Gelato (x3)', 48.97, '4280', '2025-03-05 12:06:49'),
(32, 18, 'Barolo Red Wine (x2)', 17.97, '1475', '2025-03-04 12:06:49'),
(31, 14, 'Eggnog Cheesecake (x2), Coca Cola 1.5 Liter (x1), Fettuccine Alfredo (x2)', 47.99, '2173', '2025-02-28 12:06:49'),
(30, 10, 'Bruschetta (x2), Buffalo Mozzarella Pizza (x1), Panna Cotta (x1), Eggnog Cheesecake (x3)', 85.95, '8267', '2025-03-11 12:06:49'),
(29, 29, 'Lasagna di San Gimignano (x1)', 25.00, '9788', '2025-02-24 12:06:49'),
(28, 16, 'Barolo Red Wine (x2), Holiday Bitter Greens Salad (x3), Eggnog Cheesecake (x3), Caprese Salad (x2)', 67.97, '4666', '2025-02-24 12:06:49'),
(27, 13, 'Barolo Red Wine (x1), Risotto alla Milanese (x2), Fettuccine Alfredo (x2), Lasagna di San Gimignano (x2)', 98.94, '7264', '2025-02-20 12:06:49'),
(26, 11, 'Risotto alla Milanese (x2)', 50.97, '9813', '2025-03-11 12:06:49'),
(25, 18, 'Italian Hot Chocolate (x2), Coca Cola 1.5 Liter (x3), Margherita Pizza (x3), Caprese Salad (x3)', 76.99, '4439', '2025-02-28 12:06:49'),
(24, 15, 'Spaghetti Carbonara (x1), Eggnog Cheesecake (x2), Lasagna Bolognese (x1), Holiday Bitter Greens Salad (x1)', 99.96, '2737', '2025-03-01 12:06:49'),
(23, 14, 'Lasagna di San Gimignano (x1), Panna Cotta (x3), Risotto alla Milanese (x3)', 57.99, '2695', '2025-03-20 12:06:49'),
(37, 17, 'Italian Hot Chocolate (x3), Bruschetta (x3), Eggnog Cheesecake (x1), Gelato (x2)', 56.95, '8297', '2025-02-22 12:06:49');

-- --------------------------------------------------------

--
-- Table structure for table `reservations`
--

DROP TABLE IF EXISTS `reservations`;
CREATE TABLE IF NOT EXISTS `reservations` (
  `ReservationID` int NOT NULL AUTO_INCREMENT,
  `CustomerName` varchar(255) COLLATE utf8mb4_general_ci NOT NULL,
  `CustomerEmail` varchar(255) COLLATE utf8mb4_general_ci NOT NULL,
  `CustomerPhone` varchar(255) COLLATE utf8mb4_general_ci NOT NULL,
  `ReservationDate` date NOT NULL,
  `ReservationTime` time NOT NULL,
  `NumberOfPeople` int NOT NULL,
  `SpecialRequest` text COLLATE utf8mb4_general_ci,
  `CreatedAt` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`ReservationID`)
) ENGINE=MyISAM AUTO_INCREMENT=47 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `reservations`
--

INSERT INTO `reservations` (`ReservationID`, `CustomerName`, `CustomerEmail`, `CustomerPhone`, `ReservationDate`, `ReservationTime`, `NumberOfPeople`, `SpecialRequest`, `CreatedAt`) VALUES
(36, 'Olivia Noob', 'OlivaNoob@gmail.com', '901-234-5678', '2025-03-29', '21:00:00', 6, 'Near the window, please.', '2025-03-20 12:10:04'),
(35, 'Chris Taylor', 'chris.taylor@example.com', '890-123-4567', '2025-04-10', '21:00:00', 2, 'Vegetarian options only.', '2025-03-20 12:10:04'),
(34, 'William Carter', 'william.carter@example.com', '666-777-8888', '2025-03-22', '18:00:00', 5, 'Large group seating needed.', '2025-03-20 12:10:04'),
(33, 'John Doe', 'john.doe@example.com', '123-456-7890', '2025-03-29', '19:00:00', 4, 'Celebrating a birthday.', '2025-03-20 12:10:04'),
(32, 'Jane Smith', 'jane.smith@example.com', '234-567-8901', '2025-04-17', '19:00:00', 1, 'Quiet table preferred.', '2025-03-20 12:10:04'),
(31, 'Robert White', 'robert.white@example.com', '222-333-4444', '2025-03-25', '18:00:00', 5, 'Near the window, please.', '2025-03-20 12:10:04'),
(30, 'Jane Smith', 'jane.smith@example.com', '234-567-8901', '2025-03-31', '18:00:00', 3, 'Allergic to nuts.', '2025-03-20 12:10:04'),
(29, 'Olivia Noob', 'OlivaNoob@gmail.com', '901-234-5678', '2025-04-07', '19:00:00', 4, 'Need a high chair for a baby.', '2025-03-20 12:10:04'),
(28, 'James Black', 'james.black@example.com', '444-555-6666', '2025-04-14', '18:00:00', 3, 'Gluten-free meal requested.', '2025-03-20 12:10:04'),
(27, 'Robert White', 'robert.white@example.com', '222-333-4444', '2025-03-31', '19:00:00', 6, 'Quiet table preferred.', '2025-03-20 12:10:04'),
(37, 'Alice Johnson', 'alice.johnson@example.com', '111-222-3333', '2025-04-18', '18:00:00', 3, 'Vegetarian options only.', '2025-03-20 12:10:04'),
(38, 'Emma King', 'emma.king@example.com', '555-666-7777', '2025-03-27', '19:00:00', 1, 'Near the window, please.', '2025-03-20 12:10:04'),
(39, 'Emily Davis', 'emily.davis@example.com', '345-678-9012', '2025-04-01', '21:00:00', 6, 'No special requests.', '2025-03-20 12:10:04'),
(40, 'Laura Moore', 'laura.moore@example.com', '789-012-3456', '2025-03-22', '20:00:00', 6, 'No special requests.', '2025-03-20 12:10:04'),
(41, 'Daniel Foster', 'daniel.foster@example.com', '000-111-2222', '2025-04-11', '18:00:00', 2, 'Large group seating needed.', '2025-03-20 12:10:04'),
(42, 'Alice Johnson', 'alice.johnson@example.com', '111-222-3333', '2025-03-24', '21:00:00', 5, 'Large group seating needed.', '2025-03-20 12:10:04'),
(43, 'Olivia Noob', 'OlivaNoob@gmail.com', '901-234-5678', '2025-04-07', '20:00:00', 4, 'Allergic to nuts.', '2025-03-20 12:10:04'),
(44, 'William Carter', 'william.carter@example.com', '666-777-8888', '2025-04-10', '21:00:00', 6, 'Celebrating a birthday.', '2025-03-20 12:10:04'),
(45, 'Emma King', 'emma.king@example.com', '555-666-7777', '2025-04-19', '21:00:00', 1, 'Quiet table preferred.', '2025-03-20 12:10:04'),
(46, 'David Wilson', 'david.wilson@example.com', '678-901-2345', '2025-04-18', '19:00:00', 3, 'Gluten-free meal requested.', '2025-03-20 12:10:04');

-- --------------------------------------------------------

--
-- Table structure for table `subcategories`
--

DROP TABLE IF EXISTS `subcategories`;
CREATE TABLE IF NOT EXISTS `subcategories` (
  `SubcategoryID` int NOT NULL AUTO_INCREMENT,
  `SubcategoryName` varchar(255) COLLATE utf8mb4_general_ci NOT NULL,
  `CategoryID` int DEFAULT NULL,
  PRIMARY KEY (`SubcategoryID`),
  KEY `CategoryID` (`CategoryID`)
) ENGINE=MyISAM AUTO_INCREMENT=19 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `subcategories`
--

INSERT INTO `subcategories` (`SubcategoryID`, `SubcategoryName`, `CategoryID`) VALUES
(1, 'Cold Appetizers', 1),
(2, 'Hot Appetizers', 1),
(3, 'Vegetarian Soups', 2),
(4, 'Meat-Based Soups', 2),
(5, 'Leafy Salads', 3),
(6, 'Mixed Salads', 3),
(7, 'Pasta', 4),
(8, 'Risotto', 4),
(9, 'Meat Dishes', 5),
(10, 'Seafood Dishes', 5),
(11, 'Vegetables', 6),
(12, 'Potatoes', 6),
(13, 'Cakes', 7),
(14, 'Pastries', 7),
(15, 'Gelato', 7),
(16, 'Hot Drinks', 8),
(17, 'Cold Drinks', 8),
(18, 'Pizzas', 5);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
