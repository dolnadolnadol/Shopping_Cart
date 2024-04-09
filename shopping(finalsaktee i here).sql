-- MySQL dump 10.13  Distrib 8.0.19, for Win64 (x86_64)
--
-- Host: localhost    Database: shopping
-- ------------------------------------------------------
-- Server version	8.3.0

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!50503 SET NAMES utf8mb4 */;
/*!40103 SET @OLD_TIME_ZONE=@@TIME_ZONE */;
/*!40103 SET TIME_ZONE='+00:00' */;
/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;

--
-- Table structure for table `address`
--

DROP TABLE IF EXISTS `address`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `address` (
  `AddrId` int NOT NULL AUTO_INCREMENT,
  `Address` varchar(255) COLLATE utf8mb4_general_ci NOT NULL,
  `Province` char(200) COLLATE utf8mb4_general_ci NOT NULL,
  `City` char(200) COLLATE utf8mb4_general_ci NOT NULL,
  `PostalCode` char(200) COLLATE utf8mb4_general_ci NOT NULL,
  `CusId` int NOT NULL,
  `fname` varchar(255) COLLATE utf8mb4_general_ci NOT NULL,
  `lname` varchar(255) COLLATE utf8mb4_general_ci NOT NULL,
  `tel` char(10) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `deleteStatus` int DEFAULT NULL,
  PRIMARY KEY (`AddrId`)
) ENGINE=InnoDB AUTO_INCREMENT=18 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `address`
--

LOCK TABLES `address` WRITE;
/*!40000 ALTER TABLE `address` DISABLE KEYS */;
INSERT INTO `address` VALUES (4,'hel','province','city','121587',22,'','','',NULL),(5,'heladmin','provinceadmin','cityadmin','121587',24,'','','',NULL),(6,'heladmin','provinceadmin','cityadmin','121587',24,'','','',NULL),(7,'heladmin','provinceadmin','cityadmin','121587',24,'','','',NULL),(8,'heladmin','provinceadmin','cityadmin','121587',24,'','','',NULL),(9,'heladmin','provinceadmin','cityadmin','121587',24,'','','',NULL),(10,'heladmin','provinceadmin','cityadmin','121587',24,'','','',NULL),(11,'addressTest','Provincetest','CityBanckok','13455',22,'','','',NULL),(12,'heladmin','provinceadmin','cityadmin','121587',24,'','','',NULL),(13,'heladmin','provinceadmin','cityadmin','121587',24,'','','',NULL),(14,'heladmin','provinceadmin','cityadmin','121587',24,'','','',NULL),(15,'heladmin','provinceadmin','cityadmin','121587',24,'','','',NULL),(16,'heladmin','provinceadmin','cityadmin','121587',24,'','','',NULL),(17,'heladmin','provinceadmin','cityadmin','121587',24,'','','',NULL);
/*!40000 ALTER TABLE `address` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `cart`
--

DROP TABLE IF EXISTS `cart`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `cart` (
  `cusId` int NOT NULL,
  `ProId` int NOT NULL,
  `Qty` int NOT NULL,
  KEY `ProId` (`ProId`),
  KEY `cart_ibfk_1` (`cusId`),
  CONSTRAINT `cart_ibfk_1` FOREIGN KEY (`cusId`) REFERENCES `customer` (`CusID`),
  CONSTRAINT `cart_ibfk_2` FOREIGN KEY (`ProId`) REFERENCES `product` (`proId`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `cart`
--

LOCK TABLES `cart` WRITE;
/*!40000 ALTER TABLE `cart` DISABLE KEYS */;
INSERT INTO `cart` VALUES (22,3,1);
/*!40000 ALTER TABLE `cart` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `customer`
--

DROP TABLE IF EXISTS `customer`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `customer` (
  `CusID` int NOT NULL AUTO_INCREMENT,
  `fname` varchar(255) COLLATE utf8mb4_general_ci NOT NULL,
  `lname` varchar(255) COLLATE utf8mb4_general_ci NOT NULL,
  `Sex` char(1) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `Tel` char(10) COLLATE utf8mb4_general_ci NOT NULL,
  `Email` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `Username` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `Password` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `authority` varchar(255) CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci NOT NULL,
  PRIMARY KEY (`CusID`)
) ENGINE=InnoDB AUTO_INCREMENT=25 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `customer`
--

LOCK TABLES `customer` WRITE;
/*!40000 ALTER TABLE `customer` DISABLE KEYS */;
INSERT INTO `customer` VALUES (22,'chollada','sae-lim','M','0896980835','','','',''),(23,'chollada','sae-lim','M','0896980835','','','',''),(24,'admin','admin','M','00000','','','','');
/*!40000 ALTER TABLE `customer` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `invoice`
--

DROP TABLE IF EXISTS `invoice`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `invoice` (
  `invId` int NOT NULL AUTO_INCREMENT,
  `timestamp` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `cusId` int NOT NULL,
  `orderId` int NOT NULL,
  `DeliId` int NOT NULL,
  PRIMARY KEY (`invId`),
  KEY `cusId` (`cusId`),
  KEY `orderId` (`orderId`),
  CONSTRAINT `invoice_ibfk_1` FOREIGN KEY (`cusId`) REFERENCES `customer` (`CusID`),
  CONSTRAINT `invoice_ibfk_2` FOREIGN KEY (`orderId`) REFERENCES `orderkey` (`orderId`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `invoice`
--

LOCK TABLES `invoice` WRITE;
/*!40000 ALTER TABLE `invoice` DISABLE KEYS */;
/*!40000 ALTER TABLE `invoice` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `orderdelivery`
--

DROP TABLE IF EXISTS `orderdelivery`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `orderdelivery` (
  `DeliId` int NOT NULL AUTO_INCREMENT,
  `DeliDate` datetime DEFAULT NULL,
  `statusDeli` varchar(255) COLLATE utf8mb4_general_ci NOT NULL,
  `addrId` int NOT NULL,
  `fname` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `Tel` varchar(25) COLLATE utf8mb4_general_ci NOT NULL,
  `TotalPrice` int NOT NULL,
  `lname` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  PRIMARY KEY (`DeliId`),
  KEY `addrId` (`addrId`),
  CONSTRAINT `orderdelivery_ibfk_1` FOREIGN KEY (`addrId`) REFERENCES `address` (`AddrId`)
) ENGINE=InnoDB AUTO_INCREMENT=12 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `orderdelivery`
--

LOCK TABLES `orderdelivery` WRITE;
/*!40000 ALTER TABLE `orderdelivery` DISABLE KEYS */;
INSERT INTO `orderdelivery` VALUES (8,NULL,'PREPARE',14,'admin  admin','00000',535,''),(9,NULL,'PREPARE',15,'admin  admin','00000',535,''),(10,NULL,'PREPARE',16,'admin  admin','00000',535,''),(11,NULL,'PREPARE',17,'admin  admin','00000',214,'');
/*!40000 ALTER TABLE `orderdelivery` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `orderkey`
--

DROP TABLE IF EXISTS `orderkey`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `orderkey` (
  `orderId` int NOT NULL AUTO_INCREMENT,
  `orderCreate` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `PaymentStatus` varchar(255) COLLATE utf8mb4_general_ci NOT NULL,
  `cusId` int NOT NULL,
  `DeliId` int DEFAULT NULL,
  PRIMARY KEY (`orderId`),
  KEY `orderkey_ibfk_1` (`cusId`),
  CONSTRAINT `orderkey_ibfk_1` FOREIGN KEY (`cusId`) REFERENCES `customer` (`CusID`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=21 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `orderkey`
--

LOCK TABLES `orderkey` WRITE;
/*!40000 ALTER TABLE `orderkey` DISABLE KEYS */;
INSERT INTO `orderkey` VALUES (17,'2024-03-28 00:48:05','Pending',24,8),(18,'2024-03-28 00:52:58','Pending',24,9),(19,'2024-03-28 01:10:25','Pending',24,10),(20,'2024-03-28 01:13:09','Pending',24,11);
/*!40000 ALTER TABLE `orderkey` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `ordervalue`
--

DROP TABLE IF EXISTS `ordervalue`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `ordervalue` (
  `orderId` int NOT NULL,
  `ProId` int NOT NULL,
  `Qty` int NOT NULL,
  PRIMARY KEY (`orderId`,`ProId`),
  KEY `orderId` (`orderId`),
  KEY `ProId` (`ProId`),
  CONSTRAINT `ordervalue_ibfk_1` FOREIGN KEY (`orderId`) REFERENCES `orderkey` (`orderId`),
  CONSTRAINT `ordervalue_ibfk_2` FOREIGN KEY (`ProId`) REFERENCES `product` (`proId`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `ordervalue`
--

LOCK TABLES `ordervalue` WRITE;
/*!40000 ALTER TABLE `ordervalue` DISABLE KEYS */;
INSERT INTO `ordervalue` VALUES (17,1,3),(17,3,1),(18,1,3),(18,3,1),(19,1,3),(19,3,1),(20,3,1);
/*!40000 ALTER TABLE `ordervalue` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `product`
--

DROP TABLE IF EXISTS `product`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `product` (
  `proId` int NOT NULL AUTO_INCREMENT,
  `ProductName` varchar(255) COLLATE utf8mb4_general_ci NOT NULL,
  `Description` varchar(255) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `cost` int NOT NULL,
  `Price` int NOT NULL,
  `Qty` int NOT NULL,
  `onHand` int NOT NULL,
  `typeId` int DEFAULT NULL,
  `Photo` varchar(255) COLLATE utf8mb4_general_ci NOT NULL,
  `deleteStatus` int DEFAULT NULL,
  PRIMARY KEY (`proId`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `product`
--

LOCK TABLES `product` WRITE;
/*!40000 ALTER TABLE `product` DISABLE KEYS */;
INSERT INTO `product` VALUES (1,'เครื่องนวดเท้า','นวดวันนี้สบายวันหน้า',50,100,100,5,1,'../UploadImg/uploads/423036594_1095115421802957_7894252630182466833_n.jpg',NULL),(2,'เครื่องนวดเท้า','นวดวันนี้สบายวันหน้า',50,100,100,32,1,'../UploadImg/uploads/423036594_1095115421802957_7894252630182466833_n.jpg',NULL),(3,'ทดสอบๆ','rr',0,200,20,20,NULL,'../UploadImg/uploads/423036594_1095115421802957_7894252630182466833_n.jpg',NULL);
/*!40000 ALTER TABLE `product` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `product_type`
--

DROP TABLE IF EXISTS `product_type`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `product_type` (
  `typeId` int NOT NULL AUTO_INCREMENT,
  `typeName` varchar(255) COLLATE utf8mb4_general_ci NOT NULL,
  PRIMARY KEY (`typeId`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `product_type`
--

LOCK TABLES `product_type` WRITE;
/*!40000 ALTER TABLE `product_type` DISABLE KEYS */;
INSERT INTO `product_type` VALUES (1,'เครื่องใช้ไฟฟ้า');
/*!40000 ALTER TABLE `product_type` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `receipt`
--

DROP TABLE IF EXISTS `receipt`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `receipt` (
  `receiptId` int NOT NULL AUTO_INCREMENT,
  `timestamp` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `cusId` int NOT NULL,
  `orderId` int NOT NULL,
  PRIMARY KEY (`receiptId`),
  KEY `cusId` (`cusId`),
  KEY `orderId` (`orderId`),
  CONSTRAINT `receipt_ibfk_1` FOREIGN KEY (`cusId`) REFERENCES `customer` (`CusID`),
  CONSTRAINT `receipt_ibfk_2` FOREIGN KEY (`orderId`) REFERENCES `orderkey` (`orderId`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `receipt`
--

LOCK TABLES `receipt` WRITE;
/*!40000 ALTER TABLE `receipt` DISABLE KEYS */;
/*!40000 ALTER TABLE `receipt` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Dumping routines for database 'shopping'
--
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2024-04-04 20:40:08
