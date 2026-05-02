-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: May 03, 2026 at 12:36 AM
-- Server version: 10.4.32-MariaDB
-- PHP Version: 8.0.30

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `pastimes_db`
--

-- --------------------------------------------------------

--
-- Table structure for table `admin`
--

CREATE TABLE `admin` (
  `adminId` int(11) NOT NULL,
  `adminName` varchar(100) DEFAULT NULL,
  `adminEmail` varchar(100) DEFAULT NULL,
  `password` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `cart`
--

CREATE TABLE `cart` (
  `cartId` int(11) NOT NULL,
  `userId` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `cartitem`
--

CREATE TABLE `cartitem` (
  `cartItemId` int(11) NOT NULL,
  `cartId` int(11) DEFAULT NULL,
  `productId` int(11) DEFAULT NULL,
  `quantity` int(11) DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `category`
--

CREATE TABLE `category` (
  `categoryId` int(11) NOT NULL,
  `categoryName` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `message`
--

CREATE TABLE `message` (
  `messageId` int(11) NOT NULL,
  `productId` int(11) DEFAULT NULL,
  `senderId` int(11) DEFAULT NULL,
  `receiverId` int(11) DEFAULT NULL,
  `content` text DEFAULT NULL,
  `timestamp` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `order`
--

CREATE TABLE `order` (
  `orderId` int(11) NOT NULL,
  `userId` int(11) DEFAULT NULL,
  `totalAmount` decimal(10,2) DEFAULT NULL,
  `orderStatus` varchar(20) DEFAULT 'Pending',
  `orderDate` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `orderitem`
--

CREATE TABLE `orderitem` (
  `orderItemId` int(11) NOT NULL,
  `orderId` int(11) DEFAULT NULL,
  `productId` int(11) DEFAULT NULL,
  `priceAtPurchase` decimal(10,2) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `product`
--

CREATE TABLE `product` (
  `productId` int(11) NOT NULL,
  `userId` int(11) DEFAULT NULL,
  `brand` varchar(100) NOT NULL,
  `price` decimal(10,2) NOT NULL,
  `condition_score` int(11) DEFAULT NULL CHECK (`condition_score` between 1 and 5),
  `description` text DEFAULT NULL,
  `size` varchar(50) DEFAULT NULL,
  `material` varchar(50) DEFAULT NULL,
  `color` varchar(50) DEFAULT NULL,
  `status` enum('Available','Sold') DEFAULT 'Available',
  `datePosted` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `user`
--

CREATE TABLE `user` (
  `userId` int(11) NOT NULL,
  `userName` varchar(100) NOT NULL,
  `userEmail` varchar(100) NOT NULL,
  `password` varchar(255) NOT NULL,
  `userAdress` text NOT NULL,
  `role` enum('Seller','Buyer','','') NOT NULL,
  `isVerrified` tinyint(1) NOT NULL DEFAULT 0,
  `theme` enum('Light','Dark','') NOT NULL DEFAULT 'Light'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci COMMENT='//the user table';

--
-- Dumping data for table `user`
--

INSERT INTO `user` (`userId`, `userName`, `userEmail`, `password`, `userAdress`, `role`, `isVerrified`, `theme`) VALUES
(1, '<br /><b>Warning</b>:  Undefined array key ', 'sivsav@gmail.com', 'BEATmen34567-', '', 'Seller', 0, 'Light'),
(2, 'Kylah Irvin', 'kyrvin@outlook.com', 'Fashionista1!', '', 'Seller', 0, 'Light'),
(3, 'Mpilo Mzimela', 'mpilomzee23@pastimes.co.za', 'MzeeWord123!', '', 'Buyer', 0, 'Light'),
(4, 'Zizi Mvumbi', 'zmvumbi@gmail.com', 'Clothingpastimes99#', '', 'Buyer', 0, 'Light'),
(5, 'Admin User', 'admin@pastimes.com', 'AdminSecret01*', '', '', 0, 'Light'),
(6, 'Sivikelwe Nxumalo', 'sivsav@gmail.com', '$2y$10$LzCezjl5rBQ8n/LnrRks6.v8xfoTifZBwGYdrJvkwnaBwVY0TtUNy', '', 'Seller', 0, 'Light'),
(7, 'Kylah Irvin', 'kyrvin@outlook.com', '$2y$10$DuI4P/DWNUMtByPXWP3hvOz2ajp3nHlmtzHjGdfEZQ05/TG4tL3Qa', '', 'Seller', 0, 'Light'),
(8, 'Mpilo Mzimela', 'mpilomzee23@pastimes.co.za', '$2y$10$kD6kQEPFGL9kJ/Cm6FH21OUvODElsMZuVIHgC3RujaGq9y5lh2Nn.', '', 'Buyer', 0, 'Light'),
(9, 'Zizi Mvumbi', 'zmvumbi@gmail.com', '$2y$10$mt7Acmr9jeOQhymY2YA8qeuG8jegKl9dOJ0q4Cmu6p/O5zYrcXa7a', '', 'Buyer', 0, 'Light'),
(10, 'Admin User', 'admin@pastimes.com', '$2y$10$zK7QiFiiX0O5eMHZn.AwaOI.q9ajeOZwwLJ1vcuMif9DUEbxp35Si', '', '', 0, 'Light'),
(11, 'Sivikelwe Nxumalo', 'sivsav@gmail.com', '$2y$10$tHnTGW7imnfE7BE0TyR1CepbsELz9m7tRdfitMWcmonyS8Eoe9/KW', '', 'Seller', 0, 'Light'),
(12, 'Kylah Irvin', 'kyrvin@outlook.com', '$2y$10$kAywmzaOT7LOLAkEDi.h3eEMr8bTp7ECfA/mA23U2RK4296DQqrna', '', 'Seller', 0, 'Light'),
(13, 'Mpilo Mzimela', 'mpilomzee23@pastimes.co.za', '$2y$10$x7GoMG1/GQbvzeGg.zotC.3SmkFTfIbSAF3npOUvlTxq7ucRUo9Oi', '', 'Buyer', 0, 'Light'),
(14, 'Zizi Mvumbi', 'zmvumbi@gmail.com', '$2y$10$j7GHWNM8HeC.08AkAYdNeeLD/d7xasNSKCNUivgQdvHCXzSizS/R.', '', 'Buyer', 0, 'Light'),
(15, 'Admin User', 'admin@pastimes.com', '$2y$10$d4HtdLd4Q0z9qDDBM5x0oexIG4z0764LEWW0cpJiqfmOxvyEAfLty', '', '', 0, 'Light'),
(16, 'Sivikelwe Nxumalo', 'sivsav@gmail.com', '$2y$10$R3L5PBFozjlc1ch7RNuYL.9dg1815O2yyOj2C/EIPqQphe.hm2sCe', '', 'Seller', 0, 'Light'),
(17, 'Kylah Irvin', 'kyrvin@outlook.com', '$2y$10$IgzpAUBkkT3XvrEjmwj2ou.o1TC9uszsqekDGz9Ksb9Cm/bhbjxkK', '', 'Seller', 0, 'Light'),
(18, 'Mpilo Mzimela', 'mpilomzee23@pastimes.co.za', '$2y$10$nZjrHAsCvTVA.VubULq7KeniwseM3YHvNMerVi2f9J1royBCKp5lq', '', 'Buyer', 0, 'Light'),
(19, 'Zizi Mvumbi', 'zmvumbi@gmail.com', '$2y$10$mdWAhRFEwPfjuUj6iZWYJ.BStlGf8Yh52TxbG5C94mdfRKR76KvR2', '', 'Buyer', 0, 'Light'),
(20, 'Admin User', 'admin@pastimes.com', '$2y$10$kERA0nGDrHPswjmFdR2dx.7T5QScT4B95u9H7Il6SHzBkr8pqCai6', '', '', 0, 'Light'),
(21, 'Sivikelwe Nxumalo', 'sivsav@gmail.com', '$2y$10$4avIWsSBvfWwZ5zZwl497.ojsIlunKVmi52ezIC2vxhw7/60dLK6S', '', 'Seller', 0, 'Light'),
(22, 'Kylah Irvin', 'kyrvin@outlook.com', '$2y$10$fZgMYCGteyBP9B2/JvRhiOvDDt3ak/g4GfuAYwhUr7gX02DADGsqq', '', 'Seller', 0, 'Light'),
(23, 'Mpilo Mzimela', 'mpilomzee23@pastimes.co.za', '$2y$10$UjlvlvZb3ggaaXj7l9nRfeHo2jeiNV5HCnXucINgLNkZHXacO3cNi', '', 'Buyer', 0, 'Light'),
(24, 'Zizi Mvumbi', 'zmvumbi@gmail.com', '$2y$10$FxHSIW0SkQ7bDF5fmb9StO/iDOt2.zJ0J73NYFzJLoPYqmDlUy9K2', '', 'Buyer', 0, 'Light'),
(25, 'Admin User', 'admin@pastimes.com', '$2y$10$pi0YUrxR5RvoEhQcbPAngeS8TorUP2JHfni2b1HNS2IswmHC6fYD2', '', '', 0, 'Light'),
(26, 'Sivikelwe Nxumalo', 'sivsav@gmail.com', '$2y$10$iwuZXaUVxlcoU7iMDLD05.5o/DKyiO3PxaZeoMxthe8J91rb23sam', '', 'Seller', 0, 'Light'),
(27, 'Kylah Irvin', 'kyrvin@outlook.com', '$2y$10$mtHjlzhUjIuBTLJqHW5GGeUlysS2spEk/N5Qa9LhJgVGfTcFQw2TO', '', 'Seller', 0, 'Light'),
(28, 'Mpilo Mzimela', 'mpilomzee23@pastimes.co.za', '$2y$10$sCaWAv0TwCllr09P0w3qrOjuf1wLyq570wn59r8Rc5Ug.Q23gdUX6', '', 'Buyer', 0, 'Light'),
(29, 'Zizi Mvumbi', 'zmvumbi@gmail.com', '$2y$10$DUqxSgvgEz1PSZDLvkDGYeLXfsY/t4z9hd7d2Z8Ht0FlajjP5TA/G', '', 'Buyer', 0, 'Light'),
(30, 'Admin User', 'admin@pastimes.com', '$2y$10$x0ligOdOveeWkZ0kUpDaHuUNnUxiPLtVrAwkiXFoK3d2gcp5NLnMi', '', '', 0, 'Light'),
(31, 'Sivikelwe Nxumalo', 'sivsav@gmail.com', '$2y$10$fUCHKG2SjuoxE9nCDgIi4eNVZCifsd0UXx/HggCy6TKWnkf1c8.Bm', '', 'Seller', 0, 'Light'),
(32, 'Kylah Irvin', 'kyrvin@outlook.com', '$2y$10$81xxot3odBq7htIAs2WIUukRQDQ4SR2v.wQ9oz/221Ao14cK6bD4q', '', 'Seller', 0, 'Light'),
(33, 'Mpilo Mzimela', 'mpilomzee23@pastimes.co.za', '$2y$10$7lqJZbW2OlhkOo1urg9iBOT8jRw0gMBAOPmokegJ/7R7J.wEq51oG', '', 'Buyer', 0, 'Light'),
(34, 'Zizi Mvumbi', 'zmvumbi@gmail.com', '$2y$10$ZMlXDPKO6E/F1HeVWtbd8e9lI4dAoGCHhpQvLUcwxszMuiyYXsV3O', '', 'Buyer', 0, 'Light'),
(35, 'Admin User', 'admin@pastimes.com', '$2y$10$z0zB2NZYxtIrpszGCnL.8eN2jl13fBoKDpee1k/NR0/9ALAX5aSGm', '', '', 0, 'Light');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `admin`
--
ALTER TABLE `admin`
  ADD PRIMARY KEY (`adminId`),
  ADD UNIQUE KEY `adminEmail` (`adminEmail`);

--
-- Indexes for table `cart`
--
ALTER TABLE `cart`
  ADD PRIMARY KEY (`cartId`),
  ADD UNIQUE KEY `userId` (`userId`);

--
-- Indexes for table `cartitem`
--
ALTER TABLE `cartitem`
  ADD PRIMARY KEY (`cartItemId`),
  ADD KEY `cartId` (`cartId`),
  ADD KEY `productId` (`productId`);

--
-- Indexes for table `category`
--
ALTER TABLE `category`
  ADD PRIMARY KEY (`categoryId`);

--
-- Indexes for table `message`
--
ALTER TABLE `message`
  ADD PRIMARY KEY (`messageId`),
  ADD KEY `productId` (`productId`),
  ADD KEY `senderId` (`senderId`),
  ADD KEY `receiverId` (`receiverId`);

--
-- Indexes for table `order`
--
ALTER TABLE `order`
  ADD PRIMARY KEY (`orderId`),
  ADD KEY `userId` (`userId`);

--
-- Indexes for table `orderitem`
--
ALTER TABLE `orderitem`
  ADD PRIMARY KEY (`orderItemId`),
  ADD KEY `orderId` (`orderId`),
  ADD KEY `productId` (`productId`);

--
-- Indexes for table `product`
--
ALTER TABLE `product`
  ADD PRIMARY KEY (`productId`),
  ADD KEY `userId` (`userId`);

--
-- Indexes for table `user`
--
ALTER TABLE `user`
  ADD PRIMARY KEY (`userId`,`userEmail`),
  ADD UNIQUE KEY `userId` (`userId`,`userEmail`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `admin`
--
ALTER TABLE `admin`
  MODIFY `adminId` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `cart`
--
ALTER TABLE `cart`
  MODIFY `cartId` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `cartitem`
--
ALTER TABLE `cartitem`
  MODIFY `cartItemId` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `category`
--
ALTER TABLE `category`
  MODIFY `categoryId` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `message`
--
ALTER TABLE `message`
  MODIFY `messageId` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `order`
--
ALTER TABLE `order`
  MODIFY `orderId` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `orderitem`
--
ALTER TABLE `orderitem`
  MODIFY `orderItemId` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `product`
--
ALTER TABLE `product`
  MODIFY `productId` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `user`
--
ALTER TABLE `user`
  MODIFY `userId` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=36;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `cart`
--
ALTER TABLE `cart`
  ADD CONSTRAINT `cart_ibfk_1` FOREIGN KEY (`userId`) REFERENCES `user` (`userId`);

--
-- Constraints for table `cartitem`
--
ALTER TABLE `cartitem`
  ADD CONSTRAINT `cartitem_ibfk_1` FOREIGN KEY (`cartId`) REFERENCES `cart` (`cartId`),
  ADD CONSTRAINT `cartitem_ibfk_2` FOREIGN KEY (`productId`) REFERENCES `product` (`productId`);

--
-- Constraints for table `message`
--
ALTER TABLE `message`
  ADD CONSTRAINT `message_ibfk_1` FOREIGN KEY (`productId`) REFERENCES `product` (`productId`),
  ADD CONSTRAINT `message_ibfk_2` FOREIGN KEY (`senderId`) REFERENCES `user` (`userId`),
  ADD CONSTRAINT `message_ibfk_3` FOREIGN KEY (`receiverId`) REFERENCES `user` (`userId`);

--
-- Constraints for table `order`
--
ALTER TABLE `order`
  ADD CONSTRAINT `order_ibfk_1` FOREIGN KEY (`userId`) REFERENCES `user` (`userId`);

--
-- Constraints for table `orderitem`
--
ALTER TABLE `orderitem`
  ADD CONSTRAINT `orderitem_ibfk_1` FOREIGN KEY (`orderId`) REFERENCES `order` (`orderId`),
  ADD CONSTRAINT `orderitem_ibfk_2` FOREIGN KEY (`productId`) REFERENCES `product` (`productId`);

--
-- Constraints for table `product`
--
ALTER TABLE `product`
  ADD CONSTRAINT `product_ibfk_1` FOREIGN KEY (`userId`) REFERENCES `user` (`userId`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
