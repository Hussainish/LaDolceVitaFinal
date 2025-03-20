-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1:3306
-- Generation Time: Mar 17, 2025 at 11:06 AM
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
  `FName` varchar(30) NOT NULL,
  `LName` varchar(30) NOT NULL,
  `Username` varchar(30) NOT NULL,
  `Password` varchar(255) NOT NULL,
  `Email` varchar(50) NOT NULL,
  `Role` enum('admin','user') NOT NULL DEFAULT 'user',
  PRIMARY KEY (`AccountID`),
  UNIQUE KEY `Username` (`Username`),
  UNIQUE KEY `Email` (`Email`)
) ENGINE=MyISAM AUTO_INCREMENT=31 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;;

--
-- Dumping data for table `accounts`
--

INSERT INTO `accounts` (`AccountID`, `FName`, `LName`, `Username`, `Password`, `Email`, `Role`) VALUES
(1, 'Admin', 'User', 'admin', '$2y$10$ZXbpK8wXTW4/rmf2DQiVhu0.iQHqAxGnBXZPQV839c9gwYw1y3IN2', 'eweay@gmail.com', 'admin'),
(4, 'samer', 'abuatta', 'samerabuatta', '$2y$10$B4VCAFeAQ6Y4M6ph/oxjT.e4wkkNSe/x5FaGNCF3OVJGVjVfZ.Ofi', 'samer@gmail.com', 'user'),
(8, 'Hussain1', 'Ishan1', 'hussish', '$2y$10$nUj3jfyqXyMbOvhUTooehueNe/CWcZA.waAOT9QhWJmnSHvQxwNxK', 'hussish929@gmail.com', 'admin'),
(9, 'Hussain', 'Ishan', 'HighKickz', '$2y$10$iTenlt2q8KTqtm8wzALtp.DpNWLKtPOW6xfa44bsuU1Ntlujo0Vbi', 'asdasda@gmail.com', 'admin'),
(26, 'normal', 'user', 'normal', '$2y$10$bsM/US0WZ1XGMncPv.B9Ye2trqGpH0usegCu6Oi9VN1k6OcDu.yWq', 'normal@gmail.com', 'user'),
(10, 'John', 'Doe', 'johndoe', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEaB3zC6ZGdWNn5FK0mG6X0Jj1C2', 'john.doe@example.com', 'user'),
(11, 'Jane', 'Smith', 'janesmith', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEaB3zC6ZGdWNn5FK0mG6X0Jj1C2', 'jane.smith@example.com', 'user'),
(20, 'Hala', 'Hala', 'Hala', '$2y$10$X4vKMgqoAF2MS1GGr/GlvOAdLzCtW1w30DWh5kXE2l1hjYuJg1iOu', 'Hala@gmail.com', 'user'),
(13, 'Emily', 'Davis', 'emilydavis', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEaB3zC6ZGdWNn5FK0mG6X0Jj1C2', 'emily.davis@example.com', 'user'),
(14, 'Michael', 'Brown', 'michaelbrown1', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEaB3zC6ZGdWNn5FK0mG6X0Jj1C2', 'michael.brown@example.com', 'user'),
(15, 'Sarah', 'Miller', 'sarahmiller', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEaB3zC6ZGdWNn5FK0mG6X0Jj1C2', 'sarah.miller@example.com', 'user'),
(16, 'David', 'Wilson', 'davidwilson', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEaB3zC6ZGdWNn5FK0mG6X0Jj1C2', 'david.wilson@example.com', 'user'),
(17, 'Laura', 'Moore', 'lauramoore', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEaB3zC6ZGdWNn5FK0mG6X0Jj1C2', 'laura.moore@example.com', 'admin'),
(18, 'Chris', 'Taylor', 'christaylor', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEaB3zC6ZGdWNn5FK0mG6X0Jj1C2', 'chris.taylor@example.com', 'user'),
(29, 'Olivia', 'Noob', 'Olivia1', '$2y$10$u8ygVosa5JA4DQ7Hcxgb3u90/PgT1Og55bMip3SQPeUDXfiiRgnce', 'OlivaNoob@gmail.com', 'user'),
(21, 'Hussain', 'Ishan', 'Hussish66', '$2y$10$FKjDmiHg5XPMg8s2yT6.5u/GjSGFkamiYch53kDz1EeniuVu4c736', 'Hussish66@gmail.com', 'user'),
(22, 'Hussain', 'Ishan', 'newacct2', '$2y$10$6csumfy1Em9svWKdXggflOOX0V6.jkgr5IJEZyfSRKIFchp9z0WIS', 'newacct2@gmail.com', 'user'),
(24, 'Hussain', 'Ishan', 'darkmaster4623', '$2y$10$4O4Zu1SdelYCV0JW/vX3Gud5iYe3i2cRiRuE3O9yTEpwx/lkvVadu', 'jb19990801@gmail.com', 'admin'),
(30, 'normal', 'normal', 'normal23', '$2y$10$eozRvoUdiVq6Ovuw7RvHb.W4Ylnzz0jzejzaAltsst90yl0dArB4C', 'admin22323@gmail.com', 'user');

-- --------------------------------------------------------

--
-- Table structure for table `categories`
--

DROP TABLE IF EXISTS `categories`;
CREATE TABLE IF NOT EXISTS `categories` (
  `CategoryID` int NOT NULL AUTO_INCREMENT,
  `CategoryName` varchar(255) NOT NULL,
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
  `Name` varchar(255) NOT NULL,
  `Email` varchar(255) NOT NULL,
  `Message` text NOT NULL,
  `CreatedAt` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `Status` enum('Opened','Unopened','Closed','In Process') DEFAULT 'Unopened',
  PRIMARY KEY (`MessageID`)
) ENGINE=MyISAM AUTO_INCREMENT=43 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `inqueries`
--

INSERT INTO `inqueries` (`MessageID`, `Name`, `Email`, `Message`, `CreatedAt`, `Status`) VALUES
(1, 'Hello', 'Hello@gmail.com', 'Hello', '2025-03-13 12:50:25', 'Closed'),
(2, 'asdasd', 'asdasd@gmail.com', 'asdasdw', '2025-03-13 12:53:05', 'Closed'),
(3, 'asdasd@gmail.com', 'asdasd@gmail.com', 'asdasd@gmail.com', '2025-03-13 12:53:10', 'Unopened'),
(4, 'asdasd@gmail.com', 'asdasd@gmail.com', 'asdasd@gmail.com', '2025-03-13 12:53:15', 'Unopened'),
(5, 'asdasd@gmail.com', 'asdasd@gmail.com', 'asdasd@gmail.com', '2025-03-13 12:53:18', 'Unopened'),
(6, 'asdasd@gmail.com', 'asdasd@gmail.com', 'asdasd@gmail.com', '2025-03-13 12:53:21', 'Unopened'),
(7, 'asdasd@gmail.com', 'asdasd@gmail.com', 'asdasd@gmail.com', '2025-03-13 12:53:26', 'Unopened'),
(8, 'asdasd@gmail.com', 'asdasd@gmail.com', 'asdasd@gmail.com', '2025-03-13 12:53:30', 'Unopened'),
(9, 'asdasd@gmail.com', 'asdasd@gmail.com', 'asdasd@gmail.com', '2025-03-13 12:53:34', 'Unopened'),
(10, 'asdasd@gmail.com', 'asdasd@gmail.com', 'asdasd@gmail.com', '2025-03-13 12:53:45', 'Unopened'),
(11, 'asdasd@gmail.com', 'asdasd@gmail.com', 'asdasd@gmail.com', '2025-03-13 12:53:49', 'Unopened'),
(12, 'Hussain Ishan', 'asdasd@gmail.com', 'asdasd', '2025-03-14 12:04:53', 'Unopened'),
(13, 'Hussain Ishan', 'husish99@gmail.com', 'asdasda', '2025-03-14 12:46:32', 'Unopened'),
(14, 'Hussain Ishan', 'husish99@gmail.com', 'asdasda', '2025-03-14 12:46:32', 'Closed'),
(15, 'Hussain Ishan', 'husish99@gmail.com', 'asdasda', '2025-03-14 18:53:10', 'Unopened'),
(16, 'Hussain Ishan', 'husish99@gmail.com', 'asdasda', '2025-03-14 18:53:10', 'Unopened'),
(17, 'Hussain Ishan', 'asdasd@gmail.com', 'test', '2025-03-15 00:51:41', 'Unopened'),
(18, 'Hussain Ishan', 'asdasd@gmail.com', 'test', '2025-03-15 00:51:41', 'Unopened'),
(19, 'Hussain Ishan', 'asdasd@gmail.com', 'test', '2025-03-15 00:51:46', 'Closed'),
(20, 'Hussain Ishan', 'asdasd@gmail.com', 'test', '2025-03-15 00:51:46', 'Unopened'),
(21, 'Hussain Ishan', 'asdasda@gmail.com', 'test2', '2025-03-15 00:53:29', 'Unopened'),
(22, 'Hussain Ishan', 'asdasda@gmail.com', 'test2', '2025-03-15 00:53:29', 'Unopened'),
(23, 'Hussain Ishan', 'asdasd@gmail.com', 'asdasd', '2025-03-15 00:53:44', 'Unopened'),
(24, 'Hussain Ishan', 'asdasd@gmail.com', 'asdasd', '2025-03-15 00:53:44', 'Unopened'),
(25, 'Hussain Ishan', 'jb19990801@gmail.com', 'asdasd', '2025-03-15 00:54:05', 'Unopened'),
(26, 'Hussain Ishan', 'jb19990801@gmail.com', 'asdasd', '2025-03-15 00:54:05', 'Unopened'),
(27, 'Hussain Ishan', 'husish99@gmail.com', 'asdasd', '2025-03-15 00:54:35', 'Unopened'),
(28, 'Hussain Ishan', 'husish99@gmail.com', 'asdasd', '2025-03-15 00:54:35', 'Unopened'),
(29, 'Hussain Ishan', 'asdasd@gmail.com', 'asdasd', '2025-03-15 00:55:08', 'Unopened'),
(30, 'Hussain Ishan', 'asdasd@gmail.com', 'asdasd', '2025-03-15 00:55:08', 'Unopened'),
(31, 'Hussain Ishan', 'husish99@gmail.com', 'asdasda', '2025-03-15 00:55:59', 'Unopened'),
(32, 'Hussain Ishan', 'husish99@gmail.com', 'asdasda', '2025-03-15 00:55:59', 'Unopened'),
(33, 'Hussain Ishan', 'husish99@gmail.com', 'asdasda', '2025-03-15 00:56:06', 'Unopened'),
(34, 'Hussain Ishan', 'husish99@gmail.com', 'asdasda', '2025-03-15 00:56:06', 'Unopened'),
(35, 'Hussain Ishan', 'jb19990801@gmail.com', 'asdasd', '2025-03-15 00:56:25', 'Unopened'),
(36, 'Hussain Ishan', 'jb19990801@gmail.com', 'asdasd', '2025-03-15 00:56:25', 'Unopened'),
(37, 'Hussain Ishan', 'asdasd@gmail.com', 'asdasda', '2025-03-15 00:57:15', 'Unopened'),
(38, 'Hussain Ishan', 'asdasd@gmail.com', 'asdasda', '2025-03-15 00:57:15', 'Unopened'),
(39, 'Hussain Ishan', 'asdasda@gmail.com', 'asdasdasd', '2025-03-15 00:57:30', 'Unopened'),
(40, 'Hussain Ishan', 'asdasda@gmail.com', 'asdasdasd', '2025-03-15 00:57:30', 'Unopened'),
(41, 'Hussain Ishan', 'husish99@gmail.com', 'asdasd', '2025-03-15 00:57:52', 'Unopened'),
(42, 'Hussain Ishan', 'husish99@gmail.com', 'asdasd', '2025-03-15 00:57:52', 'Unopened');

-- --------------------------------------------------------

--
-- Table structure for table `menu`
--

DROP TABLE IF EXISTS `menu`;
CREATE TABLE IF NOT EXISTS `menu` (
  `MenuItemID` int NOT NULL AUTO_INCREMENT,
  `Name` varchar(255) NOT NULL,
  `Description` text,
  `Price` decimal(10,2) NOT NULL,
  `Image` text,
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
  `OrderDetails` text NOT NULL,
  `TotalPrice` decimal(10,2) NOT NULL,
  `CreditCardLast4` char(4) NOT NULL,
  `OrderDate` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`OrderID`)
) ENGINE=MyISAM AUTO_INCREMENT=23 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `orders`
--

INSERT INTO `orders` (`OrderID`, `CustomerID`, `OrderDetails`, `TotalPrice`, `CreditCardLast4`, `OrderDate`) VALUES
(1, 7, 'Lasagna di San Gimignano x 1, Summer Spaghetti x 1, Buffalo Mozzarella Pizza x 1', 54.99, '1254', '2025-03-12 13:59:19'),
(2, 7, 'Summer Spaghetti x 1, Lasagna di San Gimignano x 1, Buffalo Mozzarella Pizza x 1', 54.99, '3652', '2025-03-12 13:59:59'),
(3, 21, 'Summer Spaghetti x 1, Lasagna di San Gimignano x 1, Buffalo Mozzarella Pizza x 1', 59.99, '7458', '2025-03-13 18:02:22'),
(4, 13, 'Lasagna di San Gimignano x 1, Tiramisu x 2', 31.37, '8187', '2024-05-12 21:00:00'),
(5, 13, 'Buffalo Mozzarella Pizza x 1, Gelato x 1', 32.04, '1397', '2024-10-24 21:00:00'),
(6, 17, 'Summer Spaghetti x 1, Tiramisu x 2', 11.85, '6308', '2024-08-17 21:00:00'),
(7, 4, 'Buffalo Mozzarella Pizza x 1, Tiramisu x 2', 22.70, '3630', '2024-11-24 22:00:00'),
(8, 4, 'Buffalo Mozzarella Pizza x 1, Tiramisu x 2', 32.48, '6881', '2025-02-09 22:00:00'),
(9, 20, 'Lasagna di San Gimignano x 1, Gelato x 1', 29.47, '5895', '2024-05-06 21:00:00'),
(10, 16, 'Buffalo Mozzarella Pizza x 1, Tiramisu x 2', 23.97, '5861', '2024-11-21 22:00:00'),
(11, 15, 'Lasagna di San Gimignano x 1, Gelato x 1', 24.97, '3099', '2024-05-11 21:00:00'),
(12, 17, 'Barolo Red Wine x 1, Tiramisu x 2', 9.88, '5215', '2025-01-02 22:00:00'),
(13, 20, 'Buffalo Mozzarella Pizza x 1, Tiramisu x 2', 9.94, '7650', '2024-10-17 21:00:00'),
(14, 1, 'Eggnog Cheesecake x 1, Lasagna di San Gimignano x 1, Buffalo Mozzarella Pizza x 1', 44.99, '2563', '2025-03-14 16:18:50'),
(15, 1, 'Summer Spaghetti x 1, Lasagna di San Gimignano x 1', 50.00, '4585', '2025-03-14 18:47:22'),
(16, 27, 'Summer Spaghetti x 1, Lasagna di San Gimignano x 1', 50.00, '1212', '2025-03-15 00:42:28'),
(17, 27, 'Lasagna di San Gimignano x 1', 25.00, '1212', '2025-03-15 00:44:51'),
(18, 27, 'Lasagna di San Gimignano x 2', 50.00, '1212', '2025-03-15 00:45:16'),
(19, 1, 'Buffalo Mozzarella Pizza x 1', 9.99, '1236', '2025-03-15 14:23:20'),
(20, 1, 'Lasagna di San Gimignano x 1', 25.00, '1236', '2025-03-15 14:23:37'),
(21, 1, 'Buffalo Mozzarella Pizza x 2', 19.98, '1236', '2025-03-15 14:23:54'),
(22, 1, 'Lasagna di San Gimignano x 1', 25.00, '1236', '2025-03-15 14:24:12');

-- --------------------------------------------------------

--
-- Table structure for table `reservations`
--

DROP TABLE IF EXISTS `reservations`;
CREATE TABLE IF NOT EXISTS `reservations` (
  `ReservationID` int NOT NULL AUTO_INCREMENT,
  `CustomerName` varchar(255) NOT NULL,
  `CustomerEmail` varchar(255) NOT NULL,
  `CustomerPhone` varchar(255) NOT NULL,
  `ReservationDate` date NOT NULL,
  `ReservationTime` time NOT NULL,
  `NumberOfPeople` int NOT NULL,
  `SpecialRequest` text,
  `CreatedAt` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`ReservationID`)
) ENGINE=MyISAM AUTO_INCREMENT=27 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `reservations`
--

INSERT INTO `reservations` (`ReservationID`, `CustomerName`, `CustomerEmail`, `CustomerPhone`, `ReservationDate`, `ReservationTime`, `NumberOfPeople`, `SpecialRequest`, `CreatedAt`) VALUES
(5, 'Hussain Ishan', 'husish9292@gmail.com', '0549307362', '2025-03-26', '21:27:00', 85, '', '2025-03-12 14:24:31'),
(7, 'Hussain Ishan', 'Hussish66@gmail.com', '0549307362', '2025-03-27', '12:07:00', 2, '', '2025-03-13 18:02:54'),
(8, 'Bob Smith', 'bob.smith@example.com', '555-8467', '2025-04-10', '05:07:01', 3, 'Window seat requested', '2025-03-13 18:21:03'),
(9, 'Bob Smith12', 'bob.smith@example.com', '0526987854', '2025-03-26', '23:33:16', 6, 'No special request', '2025-03-13 18:21:03'),
(10, 'Bob Smith', 'bob.smith@example.com', '555-9594', '2025-03-26', '08:18:26', 4, 'No special request', '2025-03-13 18:21:03'),
(11, 'Charlie Brown', 'bob.smith@example.com', '555-9966', '2025-04-04', '18:22:35', 6, 'No special request', '2025-03-13 18:21:03'),
(12, 'Alice Johnson', 'alice.johnson@example.com', '555-9731', '2025-03-30', '23:58:15', 3, 'Window seat requested', '2025-03-13 18:21:03'),
(13, 'Bob Smith', 'bob.smith@example.com', '555-2936', '2025-03-19', '03:42:15', 2, 'Window seat requested', '2025-03-13 18:21:03'),
(14, 'Bob Smith', 'charlie.brown@example.com', '555-8170', '2025-04-08', '22:20:43', 1, 'Window seat requested', '2025-03-13 18:21:03'),
(19, 'Hussain Ishan', 'jb19990801@gmail.com', '0549307362', '2025-03-19', '19:39:00', 2, '', '2025-03-14 13:36:41'),
(16, 'Alice Johnson', 'charlie.brown@example.com', '555-6762', '2025-03-24', '22:01:05', 5, 'Window seat requested', '2025-03-13 18:21:03'),
(17, 'Bob Smith', 'alice.johnson@example.com', '555-5538', '2025-03-28', '20:12:47', 8, 'Window seat requested', '2025-03-13 18:21:03'),
(20, 'dawood', 'dawood@gmail.com', '0549396362', '2025-03-19', '20:48:00', 10, '', '2025-03-14 13:43:21'),
(21, 'Hussain Ishan', 'asdasd@gmail.com', '0549307362', '2025-03-25', '23:51:00', 2, '', '2025-03-14 18:51:27'),
(22, 'Hussain Ishan', 'asdasd@gmail.com', '0549307362', '2025-03-25', '23:51:00', 2, '', '2025-03-14 18:52:07'),
(24, 'Hussain Ishan', 'asdasda@gmail.com', '0549307362', '2025-03-20', '05:51:00', 4, '', '2025-03-15 00:48:58');

-- --------------------------------------------------------

--
-- Table structure for table `subcategories`
--

DROP TABLE IF EXISTS `subcategories`;
CREATE TABLE IF NOT EXISTS `subcategories` (
  `SubcategoryID` int NOT NULL AUTO_INCREMENT,
  `SubcategoryName` varchar(255) NOT NULL,
  `CategoryID` int DEFAULT NULL,
  PRIMARY KEY (`SubcategoryID`),
  KEY `CategoryID` (`CategoryID`)
) ENGINE=MyISAM AUTO_INCREMENT=19 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;;

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
