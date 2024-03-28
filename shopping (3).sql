-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Mar 28, 2024 at 10:17 AM
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
-- Database: `shopping`
--

-- --------------------------------------------------------

--
-- Table structure for table `account`
--

CREATE TABLE `account` (
  `AccId` int(10) NOT NULL,
  `Email` varchar(255) NOT NULL,
  `Username` varchar(20) NOT NULL,
  `Password` varchar(255) NOT NULL,
  `authority` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `CusID` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `account`
--

INSERT INTO `account` (`AccId`, `Email`, `Username`, `Password`, `authority`, `CusID`) VALUES
(17, 'saiparnchollada@gmail.com', 'dolphin.lowma', '$2y$10$u5eniRBDfANVZVI4XzPB6OhU/6wDdGaAMvNwAVf7PgySK7Pnj5uvO', 'users', 22),
(18, '64050060@kmitl.ac.th', 'dolphin.lowma2', '$2y$10$qdb3MD2iD2P3u68VLP38Y.Chou/4FZCkooONNKo6oixDilJJcZTUK', 'users', 23),
(19, 'admin@gmail.com', 'admin', '$2y$10$jDg68FFZ3XBad85iUkgCMuzcZo0ieIwwjtFcRvjunZ2tg2GoaK6FG', 'admin', 24);

-- --------------------------------------------------------

--
-- Table structure for table `address`
--

CREATE TABLE `address` (
  `AddrId` int(10) NOT NULL,
  `Address` varchar(255) NOT NULL,
  `Province` char(200) NOT NULL,
  `City` char(200) NOT NULL,
  `PostalCode` char(200) NOT NULL,
  `CusId` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `address`
--

INSERT INTO `address` (`AddrId`, `Address`, `Province`, `City`, `PostalCode`, `CusId`) VALUES
(4, 'hel', 'province', 'city', '121587', 22),
(5, 'heladmin', 'provinceadmin', 'cityadmin', '121587', 24),
(6, 'heladmin', 'provinceadmin', 'cityadmin', '121587', 24),
(7, 'heladmin', 'provinceadmin', 'cityadmin', '121587', 24),
(8, 'heladmin', 'provinceadmin', 'cityadmin', '121587', 24),
(9, 'heladmin', 'provinceadmin', 'cityadmin', '121587', 24),
(10, 'heladmin', 'provinceadmin', 'cityadmin', '121587', 24),
(11, 'addressTest', 'Provincetest', 'CityBanckok', '13455', 22),
(12, 'heladmin', 'provinceadmin', 'cityadmin', '121587', 24),
(13, 'heladmin', 'provinceadmin', 'cityadmin', '121587', 24),
(14, 'heladmin', 'provinceadmin', 'cityadmin', '121587', 24),
(15, 'heladmin', 'provinceadmin', 'cityadmin', '121587', 24),
(16, 'heladmin', 'provinceadmin', 'cityadmin', '121587', 24),
(17, 'heladmin', 'provinceadmin', 'cityadmin', '121587', 24);

-- --------------------------------------------------------

--
-- Table structure for table `cart`
--

CREATE TABLE `cart` (
  `cusId` int(10) NOT NULL,
  `ProId` int(10) NOT NULL,
  `Qty` int(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `cart`
--

INSERT INTO `cart` (`cusId`, `ProId`, `Qty`) VALUES
(22, 3, 1);

-- --------------------------------------------------------

--
-- Table structure for table `customer`
--

CREATE TABLE `customer` (
  `CusID` int(11) NOT NULL,
  `fname` varchar(255) NOT NULL,
  `lname` varchar(255) NOT NULL,
  `Sex` char(1) DEFAULT NULL,
  `Tel` char(10) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `customer`
--

INSERT INTO `customer` (`CusID`, `fname`, `lname`, `Sex`, `Tel`) VALUES
(22, 'chollada', 'sae-lim', 'M', '0896980835'),
(23, 'chollada', 'sae-lim', 'M', '0896980835'),
(24, 'admin', 'admin', 'M', '00000');

-- --------------------------------------------------------

--
-- Table structure for table `invoice`
--

CREATE TABLE `invoice` (
  `invId` int(10) NOT NULL,
  `timestamp` datetime NOT NULL DEFAULT current_timestamp(),
  `cusId` int(10) NOT NULL,
  `orderId` int(10) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `orderdelivery`
--

CREATE TABLE `orderdelivery` (
  `DeliId` int(10) NOT NULL,
  `DeliDate` datetime DEFAULT NULL,
  `statusDeli` varchar(255) NOT NULL,
  `addrId` int(10) NOT NULL,
  `Name` varchar(255) NOT NULL,
  `Tel` varchar(25) NOT NULL,
  `TotalPrice` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `orderdelivery`
--

INSERT INTO `orderdelivery` (`DeliId`, `DeliDate`, `statusDeli`, `addrId`, `Name`, `Tel`, `TotalPrice`) VALUES
(8, NULL, 'PREPARE', 14, 'admin  admin', '00000', 535),
(9, NULL, 'PREPARE', 15, 'admin  admin', '00000', 535),
(10, NULL, 'PREPARE', 16, 'admin  admin', '00000', 535),
(11, NULL, 'PREPARE', 17, 'admin  admin', '00000', 214);

-- --------------------------------------------------------

--
-- Table structure for table `orderkey`
--

CREATE TABLE `orderkey` (
  `orderId` int(10) NOT NULL,
  `orderCreate` datetime NOT NULL DEFAULT current_timestamp(),
  `PaymentStatus` varchar(255) NOT NULL,
  `cusId` int(10) NOT NULL,
  `DeliId` int(10) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `orderkey`
--

INSERT INTO `orderkey` (`orderId`, `orderCreate`, `PaymentStatus`, `cusId`, `DeliId`) VALUES
(17, '2024-03-28 00:48:05', 'Pending', 24, 8),
(18, '2024-03-28 00:52:58', 'Pending', 24, 9),
(19, '2024-03-28 01:10:25', 'Pending', 24, 10),
(20, '2024-03-28 01:13:09', 'Pending', 24, 11);

-- --------------------------------------------------------

--
-- Table structure for table `ordervalue`
--

CREATE TABLE `ordervalue` (
  `orderId` int(10) NOT NULL,
  `ProId` int(10) NOT NULL,
  `Qty` int(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `ordervalue`
--

INSERT INTO `ordervalue` (`orderId`, `ProId`, `Qty`) VALUES
(17, 1, 3),
(17, 3, 1),
(18, 1, 3),
(18, 3, 1),
(19, 1, 3),
(19, 3, 1),
(20, 3, 1);

-- --------------------------------------------------------

--
-- Table structure for table `product`
--

CREATE TABLE `product` (
  `proId` int(10) NOT NULL,
  `ProductName` varchar(255) NOT NULL,
  `Description` varchar(255) DEFAULT NULL,
  `cost` int(255) NOT NULL,
  `Price` int(255) NOT NULL,
  `Qty` int(255) NOT NULL,
  `onHand` int(255) NOT NULL,
  `typeId` int(11) DEFAULT NULL,
  `Photo` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `product`
--

INSERT INTO `product` (`proId`, `ProductName`, `Description`, `cost`, `Price`, `Qty`, `onHand`, `typeId`, `Photo`) VALUES
(1, 'เครื่องนวดเท้า', 'นวดวันนี้สบายวันหน้า', 50, 100, 100, 5, 1, '../UploadImg/uploads/423036594_1095115421802957_7894252630182466833_n.jpg'),
(2, 'เครื่องนวดเท้า', 'นวดวันนี้สบายวันหน้า', 50, 100, 100, 32, 1, '../UploadImg/uploads/423036594_1095115421802957_7894252630182466833_n.jpg'),
(3, 'ทดสอบๆ', 'rr', 0, 200, 20, 20, NULL, '../UploadImg/uploads/423036594_1095115421802957_7894252630182466833_n.jpg');

-- --------------------------------------------------------

--
-- Table structure for table `product_type`
--

CREATE TABLE `product_type` (
  `typeId` int(10) NOT NULL,
  `typeName` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `product_type`
--

INSERT INTO `product_type` (`typeId`, `typeName`) VALUES
(1, 'เครื่องใช้ไฟฟ้า');

-- --------------------------------------------------------

--
-- Table structure for table `receipt`
--

CREATE TABLE `receipt` (
  `receiptId` int(10) NOT NULL,
  `timestamp` datetime NOT NULL DEFAULT current_timestamp(),
  `cusId` int(10) NOT NULL,
  `orderId` int(10) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `account`
--
ALTER TABLE `account`
  ADD PRIMARY KEY (`AccId`),
  ADD UNIQUE KEY `Email` (`Email`),
  ADD UNIQUE KEY `Username` (`Username`);

--
-- Indexes for table `address`
--
ALTER TABLE `address`
  ADD PRIMARY KEY (`AddrId`);

--
-- Indexes for table `cart`
--
ALTER TABLE `cart`
  ADD KEY `ProId` (`ProId`),
  ADD KEY `cart_ibfk_1` (`cusId`);

--
-- Indexes for table `customer`
--
ALTER TABLE `customer`
  ADD PRIMARY KEY (`CusID`);

--
-- Indexes for table `invoice`
--
ALTER TABLE `invoice`
  ADD PRIMARY KEY (`invId`),
  ADD KEY `cusId` (`cusId`),
  ADD KEY `orderId` (`orderId`);

--
-- Indexes for table `orderdelivery`
--
ALTER TABLE `orderdelivery`
  ADD PRIMARY KEY (`DeliId`),
  ADD KEY `addrId` (`addrId`);

--
-- Indexes for table `orderkey`
--
ALTER TABLE `orderkey`
  ADD PRIMARY KEY (`orderId`),
  ADD KEY `orderkey_ibfk_1` (`cusId`);

--
-- Indexes for table `ordervalue`
--
ALTER TABLE `ordervalue`
  ADD PRIMARY KEY (`orderId`,`ProId`),
  ADD KEY `orderId` (`orderId`),
  ADD KEY `ProId` (`ProId`);

--
-- Indexes for table `product`
--
ALTER TABLE `product`
  ADD PRIMARY KEY (`proId`);

--
-- Indexes for table `product_type`
--
ALTER TABLE `product_type`
  ADD PRIMARY KEY (`typeId`);

--
-- Indexes for table `receipt`
--
ALTER TABLE `receipt`
  ADD PRIMARY KEY (`receiptId`),
  ADD KEY `cusId` (`cusId`),
  ADD KEY `orderId` (`orderId`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `account`
--
ALTER TABLE `account`
  MODIFY `AccId` int(10) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=20;

--
-- AUTO_INCREMENT for table `address`
--
ALTER TABLE `address`
  MODIFY `AddrId` int(10) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=18;

--
-- AUTO_INCREMENT for table `customer`
--
ALTER TABLE `customer`
  MODIFY `CusID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=25;

--
-- AUTO_INCREMENT for table `invoice`
--
ALTER TABLE `invoice`
  MODIFY `invId` int(10) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `orderdelivery`
--
ALTER TABLE `orderdelivery`
  MODIFY `DeliId` int(10) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- AUTO_INCREMENT for table `orderkey`
--
ALTER TABLE `orderkey`
  MODIFY `orderId` int(10) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=21;

--
-- AUTO_INCREMENT for table `product`
--
ALTER TABLE `product`
  MODIFY `proId` int(10) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `product_type`
--
ALTER TABLE `product_type`
  MODIFY `typeId` int(10) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `receipt`
--
ALTER TABLE `receipt`
  MODIFY `receiptId` int(10) NOT NULL AUTO_INCREMENT;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `cart`
--
ALTER TABLE `cart`
  ADD CONSTRAINT `cart_ibfk_1` FOREIGN KEY (`cusId`) REFERENCES `customer` (`CusID`),
  ADD CONSTRAINT `cart_ibfk_2` FOREIGN KEY (`ProId`) REFERENCES `product` (`proId`);

--
-- Constraints for table `invoice`
--
ALTER TABLE `invoice`
  ADD CONSTRAINT `invoice_ibfk_1` FOREIGN KEY (`cusId`) REFERENCES `customer` (`CusID`),
  ADD CONSTRAINT `invoice_ibfk_2` FOREIGN KEY (`orderId`) REFERENCES `orderkey` (`orderId`);

--
-- Constraints for table `orderdelivery`
--
ALTER TABLE `orderdelivery`
  ADD CONSTRAINT `orderdelivery_ibfk_1` FOREIGN KEY (`addrId`) REFERENCES `address` (`AddrId`);

--
-- Constraints for table `orderkey`
--
ALTER TABLE `orderkey`
  ADD CONSTRAINT `orderkey_ibfk_1` FOREIGN KEY (`cusId`) REFERENCES `customer` (`CusID`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `ordervalue`
--
ALTER TABLE `ordervalue`
  ADD CONSTRAINT `ordervalue_ibfk_1` FOREIGN KEY (`orderId`) REFERENCES `orderkey` (`orderId`),
  ADD CONSTRAINT `ordervalue_ibfk_2` FOREIGN KEY (`ProId`) REFERENCES `product` (`proId`);

--
-- Constraints for table `receipt`
--
ALTER TABLE `receipt`
  ADD CONSTRAINT `receipt_ibfk_1` FOREIGN KEY (`cusId`) REFERENCES `customer` (`CusID`),
  ADD CONSTRAINT `receipt_ibfk_2` FOREIGN KEY (`orderId`) REFERENCES `orderkey` (`orderId`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
