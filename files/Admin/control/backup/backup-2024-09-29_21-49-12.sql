-- MySQL dump 10.16  Distrib 10.1.38-MariaDB, for Win64 (AMD64)
--
-- Host: localhost    Database: driver_id_system2
-- ------------------------------------------------------
-- Server version	10.1.38-MariaDB

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;
/*!40103 SET @OLD_TIME_ZONE=@@TIME_ZONE */;
/*!40103 SET TIME_ZONE='+00:00' */;
/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;

--
-- Table structure for table `tbl_admin`
--

DROP TABLE IF EXISTS `tbl_admin`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `tbl_admin` (
  `admin_id` int(11) NOT NULL AUTO_INCREMENT,
  `first_name` varchar(255) NOT NULL,
  `middle_name` varchar(255) DEFAULT NULL,
  `last_name` varchar(255) NOT NULL,
  `sex` varchar(255) NOT NULL,
  `mobile_number` varchar(11) NOT NULL,
  `username` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL,
  `attempt` varchar(255) NOT NULL,
  `relog_time` datetime NOT NULL,
  `login_time` datetime NOT NULL,
  `logout_time` datetime NOT NULL,
  `account_type` int(1) NOT NULL,
  `date_registered` datetime NOT NULL,
  `img` varchar(255) NOT NULL,
  `status` varchar(255) NOT NULL,
  PRIMARY KEY (`admin_id`)
) ENGINE=InnoDB AUTO_INCREMENT=8 DEFAULT CHARSET=utf8mb4;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `tbl_admin`
--

LOCK TABLES `tbl_admin` WRITE;
/*!40000 ALTER TABLE `tbl_admin` DISABLE KEYS */;
INSERT INTO `tbl_admin` VALUES (1,'Wakamole','Medav','Plamor','Male','09090909090','admin','123','0','0000-00-00 00:00:00','2024-09-30 02:03:28','2024-09-30 02:02:35',1,'0000-00-00 00:00:00','7_UPLOD_9 Ma_Lodi_2024-07-25_18-43-58_43444.jpg',''),(2,'asdsad','adasda','asdasdas','asdsad','09090909090','admin2','123','0','0000-00-00 00:00:00','2024-08-11 18:06:23','2024-07-26 20:46:10',2,'0000-00-00 00:00:00','7_UPLOD_9 Ma_Lodi_2024-07-25_18-43-58_43444.jpg',''),(3,'asdada','asdada','dsadas','dasdasd','09090909090','admin3','123','','0000-00-00 00:00:00','0000-00-00 00:00:00','0000-00-00 00:00:00',3,'0000-00-00 00:00:00','7_UPLOD_9 Ma_Lodi_2024-07-25_18-43-58_43444.jpg',''),(4,'Dino','Me','Saur','Male','09090090909','Dino','dino','0','0000-00-00 00:00:00','2024-07-25 21:40:09','2024-07-25 21:40:14',2,'2024-07-25 18:08:52','1_Dino_Saur_43444.jpg','Approved'),(5,'Dino','Me','Saur','Male','09090090909','DinoMe','123','0','0000-00-00 00:00:00','0000-00-00 00:00:00','0000-00-00 00:00:00',2,'2024-07-25 18:11:26','1_Dino_Saur_43444.jpg','Approved'),(6,'UPLOD','8 Ma','Lodi','Male','09090909090','upload8','123','0','0000-00-00 00:00:00','2024-07-25 21:13:18','2024-07-25 21:39:48',2,'2024-07-25 18:39:49','upload8 - 2024.07.25 - 03.16.31pm.jpg','Approved'),(7,'UPLOD 1million tawsan','9 Ma','Lodi','Male','09090909090','upload9','123','0','0000-00-00 00:00:00','0000-00-00 00:00:00','0000-00-00 00:00:00',2,'2024-07-25 18:43:58','7_UPLOD_9 Ma_Lodi_2024-07-26_43444.jpg','Approved');
/*!40000 ALTER TABLE `tbl_admin` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `tbl_appointment`
--

DROP TABLE IF EXISTS `tbl_appointment`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `tbl_appointment` (
  `sched_id` int(20) NOT NULL AUTO_INCREMENT,
  `fk_driver_id` int(11) DEFAULT NULL,
  `DATE` date NOT NULL,
  `booking_date` datetime NOT NULL,
  PRIMARY KEY (`sched_id`),
  KEY `fk_driver_id_appointment` (`fk_driver_id`),
  CONSTRAINT `fk_driver_id_appointment` FOREIGN KEY (`fk_driver_id`) REFERENCES `tbl_driver` (`driver_id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=23 DEFAULT CHARSET=utf8mb4;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `tbl_appointment`
--

LOCK TABLES `tbl_appointment` WRITE;
/*!40000 ALTER TABLE `tbl_appointment` DISABLE KEYS */;
INSERT INTO `tbl_appointment` VALUES (1,2,'2024-07-01','0000-00-00 00:00:00'),(2,3,'2024-07-01','0000-00-00 00:00:00'),(6,8,'2024-07-01','2024-06-28 18:47:50'),(7,9,'2024-07-01','2024-06-28 19:09:13'),(8,10,'2024-07-01','2024-06-28 19:12:39'),(9,11,'2024-07-01','2024-06-28 19:21:59'),(10,12,'2024-07-01','2024-06-28 19:24:55'),(11,13,'2024-07-01','2024-06-28 19:34:57'),(12,16,'2024-07-01','2024-06-30 17:47:31'),(13,17,'2024-07-01','2024-06-30 17:52:33'),(14,18,'2024-07-01','2024-06-30 17:56:41'),(15,19,'2024-07-01','2024-06-30 18:07:22'),(16,20,'2024-07-29','2024-07-26 17:34:41'),(17,21,'2024-07-29','2024-07-26 17:35:41'),(18,22,'2024-07-30','2024-07-26 17:47:14'),(19,23,'2024-07-31','2024-07-26 17:54:30'),(20,24,'2024-07-31','2024-07-27 19:56:12'),(21,25,'2024-07-31','2024-07-27 19:56:53'),(22,26,'2024-07-29','2024-07-29 18:39:26');
/*!40000 ALTER TABLE `tbl_appointment` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `tbl_association`
--

DROP TABLE IF EXISTS `tbl_association`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `tbl_association` (
  `association_id` int(5) NOT NULL AUTO_INCREMENT,
  `association_category` enum('E-Bike','Tricycle','Trisikad') NOT NULL,
  `association_name` varchar(255) NOT NULL,
  `association_area` varchar(255) NOT NULL,
  `association_president` varchar(255) NOT NULL,
  `association_color` varchar(255) NOT NULL,
  `association_color_name` varchar(255) NOT NULL,
  PRIMARY KEY (`association_id`)
) ENGINE=InnoDB AUTO_INCREMENT=11 DEFAULT CHARSET=utf8mb4;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `tbl_association`
--

LOCK TABLES `tbl_association` WRITE;
/*!40000 ALTER TABLE `tbl_association` DISABLE KEYS */;
INSERT INTO `tbl_association` VALUES (1,'E-Bike','sa balay','dasdasdasd','dsadaasdasdaa','#FF0000','Red'),(6,'E-Bike','Happy','Dinosaur','Siya Eh','#FF0A0A','Red'),(8,'Trisikad','Sabi','asdasd','adsad','#FFFF00','Yellow'),(9,'Trisikad','CEHOTODA','Celine Homes','Si Libay','#FFFF00','Yellow'),(10,'Tricycle','Tagok','Sa kilid','tAG@k','#0000FF','Blue');
/*!40000 ALTER TABLE `tbl_association` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `tbl_calendar`
--

DROP TABLE IF EXISTS `tbl_calendar`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `tbl_calendar` (
  `calendar_id` int(11) NOT NULL AUTO_INCREMENT,
  `calendar_date` date NOT NULL,
  `calendar_description` varchar(255) DEFAULT NULL,
  `slots` int(11) DEFAULT NULL,
  `end_time` time DEFAULT NULL,
  `calendar_control` enum('Enable','Disable') NOT NULL,
  PRIMARY KEY (`calendar_id`)
) ENGINE=InnoDB AUTO_INCREMENT=18 DEFAULT CHARSET=utf8mb4;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `tbl_calendar`
--

LOCK TABLES `tbl_calendar` WRITE;
/*!40000 ALTER TABLE `tbl_calendar` DISABLE KEYS */;
INSERT INTO `tbl_calendar` VALUES (3,'2024-08-04','',20,'15:00:00','Enable'),(4,'2024-08-21','Ninoy Aqquino Day',40,'15:00:00','Disable'),(6,'2024-07-29','',25,'20:28:00','Enable'),(7,'2024-07-30','Britdi ni Adszkieesz',30,'15:28:00','Disable'),(8,'2024-07-31','',10,'00:00:00','Enable'),(10,'2024-08-19','',2,'15:00:00','Enable'),(11,'2024-08-14','',30,'15:00:00','Enable'),(12,'2024-08-11','',23,'15:00:00','Enable'),(13,'2024-08-19','',2,'15:00:00','Enable'),(15,'2024-08-13','',2,'15:00:00','Enable'),(17,'2024-07-13','',20,'15:00:00','Enable');
/*!40000 ALTER TABLE `tbl_calendar` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `tbl_driver`
--

DROP TABLE IF EXISTS `tbl_driver`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `tbl_driver` (
  `driver_id` int(4) NOT NULL AUTO_INCREMENT,
  `driver_category` enum('E-Bike','Tricycle','Trisikad') NOT NULL,
  `formatted_id` varchar(9) DEFAULT NULL,
  `first_name` varchar(255) NOT NULL,
  `middle_name` varchar(255) DEFAULT NULL,
  `last_name` varchar(255) NOT NULL,
  `suffix_name` varchar(255) DEFAULT NULL,
  `nickname` varchar(255) DEFAULT NULL,
  `age` int(3) NOT NULL,
  `birth_date` date NOT NULL,
  `birth_place` varchar(100) NOT NULL,
  `sex` enum('Male','Female') NOT NULL,
  `address` varchar(255) NOT NULL,
  `mobile_number` varchar(11) NOT NULL,
  `civil_status` enum('Single','Married','Live-In','Widowed','Separated','Divorced') NOT NULL,
  `religion` varchar(100) DEFAULT NULL,
  `citizenship` varchar(50) NOT NULL,
  `height` int(10) NOT NULL,
  `weight` int(10) NOT NULL,
  `pic_2x2` varchar(255) NOT NULL,
  `doc_proof` varchar(255) NOT NULL,
  `name_to_notify` varchar(255) NOT NULL,
  `relationship` varchar(255) NOT NULL,
  `num_to_notify` varchar(11) NOT NULL,
  `vehicle_ownership` enum('Owned','Rented') NOT NULL,
  `verification_stat` enum('Registered','Pending','Denied') NOT NULL,
  `fk_association_id` int(11) DEFAULT NULL,
  `driver_registered` datetime DEFAULT NULL,
  `renew_stat` enum('Active','For Renewal','Revoked due to Violations') DEFAULT NULL,
  `fk_sched_id` int(11) DEFAULT NULL,
  `fk_vehicle_id` int(11) DEFAULT NULL,
  `fk_admin_id` int(11) DEFAULT NULL,
  PRIMARY KEY (`driver_id`),
  KEY `fk_sched_id_driver` (`fk_sched_id`),
  KEY `fk_vehicle_id_driver` (`fk_vehicle_id`),
  KEY `fk_association_id_driver` (`fk_association_id`),
  KEY `fk_admin_id_driver` (`fk_admin_id`),
  CONSTRAINT `fk_admin_id_driver` FOREIGN KEY (`fk_admin_id`) REFERENCES `tbl_admin` (`admin_id`),
  CONSTRAINT `fk_association_id_driver` FOREIGN KEY (`fk_association_id`) REFERENCES `tbl_association` (`association_id`),
  CONSTRAINT `fk_sched_id_driver` FOREIGN KEY (`fk_sched_id`) REFERENCES `tbl_appointment` (`sched_id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `fk_vehicle_id_driver` FOREIGN KEY (`fk_vehicle_id`) REFERENCES `tbl_vehicle` (`vehicle_id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=27 DEFAULT CHARSET=utf8mb4;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `tbl_driver`
--

LOCK TABLES `tbl_driver` WRITE;
/*!40000 ALTER TABLE `tbl_driver` DISABLE KEYS */;
INSERT INTO `tbl_driver` VALUES (2,'E-Bike','ETRK-0002','Wabafeeet','Ma','Lodi','Jr','asdsad',0,'2024-05-26','sadsad','Male','asdasd','09090909090','Single','sadsad','sadasd',12,12,'uploads/drivers/66a745a94c923_ETRK-0002_.jpg','uploads/documents/66a745a94ced5_ETRK-0002_.jpg','sadasd','sadas','09909099900','Owned','Registered',1,'2024-07-03 00:00:00','Active',1,1,NULL),(3,'E-Bike','ETRK-0003','Dabaret','Ma','Lodi','Jr','asdsad',0,'2024-05-26','sadsad','Male','asdasd','09090909090','Single','sadsad','sadasd',56,56,'uploads/drivers/66a73f7e54e70_ETRK-0003_.jpg','uploads/documents/66a73f7e550c5_ETRK-0003_.jpg','sadasd','sadas','09909099900','Owned','Registered',1,'2024-07-03 18:52:30','Active',2,2,1),(8,'Tricycle','TRCL-0008','Aduken','Ma','Lodi','Jr','asdsad',0,'2024-05-27','sadsad','Male','asdasd','09090909090','Single','sadsad','sadasd',12,12,'uploads/drivers/66ac9a35ada78_ETRK-0008_.jpg','uploads/documents/66ac9a35ae05c_ETRK-0008_.jpg','sadasd','sadas','09909099900','Owned','Registered',1,'2024-07-26 03:27:41','Active',6,6,1),(9,'E-Bike','ETRK-0009','Masdad','Ma','Lodi','Jr','asdsad',3,'2020-12-27','sadsad','Male','asdasd','09090909090','Single','sadsad','sadasd',12,12,'Cost Benefit Analysis 2.png','Cost Benefit Analysis 2.png','sadasd','sadas','09909099900','Owned','Registered',1,'2024-07-27 20:09:05','Active',7,7,1),(10,'E-Bike','ETRK-0010','Bushzxada','Ma','Lodi','Jr','asdsad',3,'2020-12-27','sadsad','Male','asdasd','09090909090','Single','sadsad','sadasd',12,12,'uploads/drivers/66a74c626fcb1_ETRK-0010_.jpg','uploads/documents/66a74c6270235_ETRK-0010_.jpg','sadasd','sadas','09909099900','Owned','Registered',1,'2024-07-29 16:31:11','Active',8,8,1),(11,'E-Bike','ETRK-0011','SADASD','Ma','Lodi','Jr','asdsad',6,'2018-01-07','sadsad','Male','asdasd','09090909090','Single','sadsad','sadasd',12,12,'Cost Benefit Analysis 1.png','Cost Benefit Analysis 1.png','sadasd','sadas','09909099900','Owned','Denied',1,'2024-07-29 17:19:38','',9,9,1),(12,'E-Bike','ETRK-0012','asdasdas','Ma','Lodi','Jr','asdsad',0,'2024-04-15','sadsad','Male','asdasd','09090909090','Single','sadsad','sadasd',12,12,'Cost Benefit Analysis 2.png','Cost Benefit Analysis 2.png','sadasd','sadas','09909099900','Owned','Denied',1,NULL,NULL,10,10,1),(13,'Trisikad','TSKD-0013','Modaves','Ma','Lodi','Jr','asdsad',8,'2015-12-27','sadsad','Male','asdasd','09090909090','Single','sadsad','sadasd',12,12,'Cost Benefit Analysis 2.png','Cost Benefit Analysis 2.png','sadasd','sadas','09909099900','Owned','Denied',1,NULL,NULL,11,11,1),(16,'E-Bike','ETRK-0016','UPLOD 2','Ma','Lodi','Jr','asdsad',0,'2024-06-30','sadsad','Male','asdasd','09090909090','Single','sadsad','sadasd',12,12,'uploads/drivers/668129b387e33_Cat.jpg','uploads/documents/668129b388155_Cat.jpg','sadasd','sadas','09909099900','Owned','Registered',1,'2024-07-29 18:29:32','Active',12,12,1),(17,'E-Bike','ETRK-0017','UPLOD 3','Ma','Lodi','Jr','asdsad',0,'2024-06-30','sadsad','','asdasd','09090909090','','sadsad','sadasd',12,12,'','','sadasd','sadas','09909099900','','Registered',1,'2024-07-28 18:00:15','Active',13,13,1),(18,'E-Bike','ETRK-0018','UPLOD 4','Ma','Lodi','Jr','asdsad',0,'2024-06-30','sadsad','','asdasd','09090909090','','sadsad','sadasd',12,12,'uploads/drivers/66812bd90d2f0_ETRK-0018_Cat.jpg','uploads/documents/66812bd90d6f6_ETRK-0018_Cat.jpg','sadasd','sadas','09909099900','','Registered',1,'2024-07-29 17:30:02','Active',14,14,1),(19,'E-Bike','ETRK-0019','UPLOD 7','Ma','Lodi','Jr','asdsad',0,'2024-06-30','sadsad','','asdasd','09090909090','','sadsad','sadasd',12,12,'uploads/drivers/66812e5ad1a53_ETRK-0019_Cost Benefit Analysis 2.png','uploads/documents/66812e5ad1c7f_ETRK-0019_Cost Benefit Analysis 2.png','sadasd','sadas','09909099900','','Registered',1,'2024-07-27 20:24:27','Active',15,15,1),(20,'Trisikad','TSKD-0020','Happy','Happy','Happy','Happy','Happy',0,'2024-06-30','Happy','Male','Happy','23121231231','Single','Happy','Happy',0,0,'','','','','21321321321','Owned','Registered',1,'2024-07-27 20:02:13','Active',16,16,1),(21,'E-Bike','ETRK-0021','Happy','Happy','Happy','Happy','Happy',0,'2024-06-30','Happy','Male','Happy','12312321321','Single','Happy','Happy',0,0,'uploads/drivers/66a36ded84eb4_ETRK-0021_43444.jpg','uploads/documents/66a36ded8513f_ETRK-0021_43444.jpg','Happy','Happy','12312321323','Owned','Registered',1,'2024-07-27 20:18:19','Active',17,17,1),(22,'E-Bike','ETRK-0022','UPLOD 11','Ma','Lodi','Jr','asdsad',0,'2024-06-30','sadsad','Male','asdasd','09090909090','Single','sadsad','sadasd',12,12,'uploads/drivers/66a370a2d6a40_ETRK-0022_43444.jpg','uploads/documents/66a370a2d6d5a_ETRK-0022_43444.jpg','sadasd','sadas','09909099900','Owned','Registered',6,'2024-07-27 20:14:32','Active',18,18,1),(23,'E-Bike','ETRK-0023','Ha','Ha','Ha','Ha','Ha',0,'2024-06-30','Ha','Male','Ha','13123123131','Married','Ha','Ha',0,0,'uploads/drivers/66a372564399d_ETRK-0023_43444.jpg','uploads/documents/66a3725643c33_ETRK-0023_43444.jpg','Ha','Ha','12312312312','Owned','Registered',1,'2024-07-27 19:07:58','Active',19,19,1),(24,'Trisikad','TSKD-0024','UPLOD 19','Ma','Lodi','Jr','asdsad',0,'2024-06-30','sadsad','Female','asdasd','09090909090','Single','sadsad','sadasd',12,12,'','','sadasd','sadas','09909099900','Owned','Registered',6,'2024-07-27 20:11:42','Active',20,20,1),(25,'E-Bike','ETRK-0025','UPLOD 19','Ma','Lodi','Jr','asdsad',0,'2024-07-04','sadsad','Female','asdasd','09090909090','Single','sadsad','sadasd',12,12,'../../uploads/drivers/66a73e0e7bfbf_ETRK-0025_.jpg','../../uploads/documents/66a73e0e7c298_ETRK-0025_.jpg','sadasd','sadas','09909099900','Owned','Registered',8,'2024-07-27 20:09:51','Active',21,21,1),(26,'E-Bike','ETRK-0026','UPLOD 20','Ma','Lodi','Jr','asdsad',0,'2024-06-30','sadsad','Male','asdasd','09090909090','Single','sadsad','sadasd',12,12,'uploads/drivers/66a7715e59c76_ETRK-0026_43444.jpg','uploads/documents/66a7715e59f8b_ETRK-0026_43444.jpg','sadasd','sadas','09909099900','Owned','Registered',6,'2024-07-29 18:39:50','Active',22,22,1);
/*!40000 ALTER TABLE `tbl_driver` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `tbl_vehicle`
--

DROP TABLE IF EXISTS `tbl_vehicle`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `tbl_vehicle` (
  `vehicle_id` int(5) NOT NULL AUTO_INCREMENT,
  `fk_driver_id` int(11) DEFAULT NULL,
  `vehicle_category` varchar(255) NOT NULL,
  `name_of_owner` varchar(255) NOT NULL,
  `addr_of_owner` varchar(255) NOT NULL,
  `owner_phone_num` varchar(11) NOT NULL,
  `vehicle_color` varchar(255) NOT NULL,
  `brand` varchar(255) NOT NULL,
  `plate_num` varchar(20) DEFAULT NULL,
  `vehicle_registered` datetime DEFAULT NULL,
  `vehicle_img_front` varchar(255) NOT NULL,
  `vehicle_img_back` varchar(255) NOT NULL,
  PRIMARY KEY (`vehicle_id`),
  KEY `fk_driver_id_vehicle` (`fk_driver_id`),
  CONSTRAINT `fk_driver_id_vehicle` FOREIGN KEY (`fk_driver_id`) REFERENCES `tbl_driver` (`driver_id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=23 DEFAULT CHARSET=utf8mb4;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `tbl_vehicle`
--

LOCK TABLES `tbl_vehicle` WRITE;
/*!40000 ALTER TABLE `tbl_vehicle` DISABLE KEYS */;
INSERT INTO `tbl_vehicle` VALUES (1,2,'E-Bike','Wabafet Ma Lodi','asdasd','09090909090','sdasad','asdsad','asdsa','0000-00-00 00:00:00','uploads/vehicles/66a745a94d39a_ETRK-0002_front.jpg','uploads/vehicles/66a745a94d8fc_ETRK-0002_back.jpg'),(2,3,'E-Bike','Dabaret Ma Lodi','asdasd','09090909090','sdasad','asdsad','asdsa','0000-00-00 00:00:00','uploads/vehicles/66a73f7e552b0_ETRK-0003_front.jpg','uploads/vehicles/66a73f7e55532_ETRK-0003_back.jpg'),(6,8,'Tricycle','Aduken Ma Lodi','asdasd','09090909090','sdasad','asdsad','asdsa','0000-00-00 00:00:00','uploads/vehicles/66ac9a35ae50e_ETRK-0008_front.jpg','uploads/vehicles/66ac9a35aea65_ETRK-0008_back.jpg'),(7,9,'E-Bike','Masdad Ma Lodi','asdasd','09090909090','sdasad','asdsad','asdsa','0000-00-00 00:00:00','',''),(8,10,'E-Bike','Bushzxada Ma Lodi','asdasd','09090909090','sdasad','asdsad','asdsa','2024-07-29 16:31:11','uploads/vehicles/66a74c62707c9_ETRK-0010_front.jpg','uploads/vehicles/66a74c6270cf5_ETRK-0010_back.jpg'),(9,11,'E-Bike','SADASD Ma Lodi','asdasd','09090909090','sdasad','asdsad','asdsa','0000-00-00 00:00:00','Cost Benefit Analysis 1.png','Cost Benefit Analysis 1.png'),(10,12,'E-Bike','asdasdas Ma Lodi','asdasd','09090909090','sdasad','asdsad','asdsa','0000-00-00 00:00:00','Cost Benefit Analysis 2.png','Cost Benefit Analysis 2.png'),(11,13,'Trisikad','Modaves Ma Lodi','asdasd','09090909090','sdasad','asdsad','asdsa',NULL,'Cost Benefit Analysis 2.png','Cost Benefit Analysis 2.png'),(12,16,'E-Bike','Modaves Ma Lodi','asdasd','09090909090','sdasad','asdsad','asdsa','2024-07-29 18:29:32','uploads/vehicles/668129b3883d4_Cat.jpg','uploads/vehicles/668129b388652_Cat.jpg'),(13,17,'E-Bike','Modaves Ma Lodi','asdasd','09090909090','sdasad','asdsad','asdsa','2024-07-28 18:00:15','uploads/vehicles/66812ae159a55_ETRK-0017_Cat.jpg','uploads/vehicles/66812ae159d05_ETRK-0017_Cat.jpg'),(14,18,'E-Bike','Modaves Ma Lodi','asdasd','09090909090','sdasad','asdsad','asdsa','2024-07-29 17:30:02','uploads/vehicles/66812bd90da17_ETRK-0018_Cat.jpg','uploads/vehicles/66812bd90dc1f_ETRK-0018_Cat.jpg'),(15,19,'E-Bike','Modaves Ma Lodi','asdasd','09090909090','sdasad','asdsad','asdsa','2024-07-27 20:24:27','uploads/vehicles/66812e5ad1ec6_ETRK-0019_Cost Benefit Analysis 2.png','uploads/vehicles/66812e5ad216d_ETRK-0019_Cost Benefit Analysis 2.png'),(16,20,'Trisikad','Happy Happy Happy','Happy','23121231231','Happy','Happy','Happy','0000-00-00 00:00:00','',''),(17,21,'E-Bike','Happy Happy Happy','Happy','12312321321','Happy','Happy','Happy','0000-00-00 00:00:00','uploads/vehicles/66a36ded8538b_ETRK-0021_43444.jpg','uploads/vehicles/66a36ded8566d_ETRK-0021_43444.jpg'),(18,22,'E-Bike','UPLOD 11 Ma Lodi','asdasd','09090909090','sdasad','asdsad','asdsa','0000-00-00 00:00:00','uploads/vehicles/66a370a2d7159_ETRK-0022_43444.jpg','uploads/vehicles/66a370a2d7527_ETRK-0022_43444.jpg'),(19,23,'E-Bike','Ha Ha Ha','Ha','13123123131','Ha','Ha','Ha','0000-00-00 00:00:00','uploads/vehicles/66a3725643f2b_ETRK-0023_43444.jpg','uploads/vehicles/66a372564429c_ETRK-0023_43444.jpg'),(20,24,'Trisikad','UPLOD 19 Ma Lodi','asdasd','09090909090','sdasad','asdsad','asdsa','0000-00-00 00:00:00','',''),(21,25,'E-Bike','UPLOD 19 Ma Lodi','asdasd','09090909090','sdasad','asdsad','asdsa','0000-00-00 00:00:00','../../uploads/vehicles/66a73e0e7c4f2_ETRK-0025_front.jpg','../../uploads/vehicles/66a73e0e7cf5f_ETRK-0025_back.jpg'),(22,26,'E-Bike','UPLOD 20 Ma Lodi','asdasd','09090909090','sdasad','asdsad','asdsa','2024-07-29 18:39:50','uploads/vehicles/66a7715e5a42a_ETRK-0026_43444.jpg','uploads/vehicles/66a7715e5a86f_ETRK-0026_43444.jpg');
/*!40000 ALTER TABLE `tbl_vehicle` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `tbl_violation`
--

DROP TABLE IF EXISTS `tbl_violation`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `tbl_violation` (
  `violation_id` int(11) NOT NULL AUTO_INCREMENT,
  `fk_driver_id` int(11) DEFAULT NULL,
  `fk_admin_id` int(11) DEFAULT NULL,
  `violation_category` enum('Improper Garbage Disposal','Driving Under the Influence (DUI)','Parking Violations','Reckless Driving','Violence or Theft','Unauthorized Transport Operations','Noise Violations','Illegal Parking of Tricycles','Other...(Please Specify in Description)') NOT NULL,
  `violation_description` varchar(255) DEFAULT NULL,
  `violation_date` datetime NOT NULL,
  `renewed_date` datetime DEFAULT NULL,
  PRIMARY KEY (`violation_id`),
  KEY `fk_driver_id_violation` (`fk_driver_id`),
  KEY `fk_admin_id_violation` (`fk_admin_id`),
  CONSTRAINT `fk_admin_id_violation` FOREIGN KEY (`fk_admin_id`) REFERENCES `tbl_admin` (`admin_id`),
  CONSTRAINT `fk_driver_id_violation` FOREIGN KEY (`fk_driver_id`) REFERENCES `tbl_driver` (`driver_id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=42 DEFAULT CHARSET=utf8mb4;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `tbl_violation`
--

LOCK TABLES `tbl_violation` WRITE;
/*!40000 ALTER TABLE `tbl_violation` DISABLE KEYS */;
INSERT INTO `tbl_violation` VALUES (1,2,NULL,'Driving Under the Influence (DUI)','Apuchu','2024-06-30 00:00:00','2024-07-11 20:22:15'),(2,3,1,'Parking Violations','ckvv','2024-06-30 00:00:00','2024-07-11 20:26:46'),(3,2,1,'Improper Garbage Disposal','dasdasdadas','2024-07-01 20:05:00','2024-07-11 20:22:15'),(4,2,1,'Improper Garbage Disposal','sdafsadfasdf','2024-06-30 01:21:00','2024-07-11 20:22:15'),(5,3,1,'Improper Garbage Disposal','asdasdasdsa','2024-06-30 02:15:00','2024-07-11 20:26:46'),(6,3,1,'Improper Garbage Disposal','asdasdas','2024-07-09 02:26:00','2024-07-11 20:26:46'),(7,2,1,'Parking Violations','asdasdasd','2024-05-26 02:27:00','2024-07-11 20:42:13'),(8,2,1,'Improper Garbage Disposal','asdasdas','2024-06-30 02:28:00','2024-07-11 20:42:13'),(9,3,1,'Parking Violations','asdasdasd','2024-06-30 02:28:00','2024-07-11 20:42:00'),(10,2,1,'Violence or Theft','asdasdasd','2024-06-30 02:29:00','2024-07-11 20:42:13'),(11,3,1,'Illegal Parking of Tricycles','fafdasdas','2024-06-30 02:30:00','2024-07-11 20:42:00'),(14,3,1,'Improper Garbage Disposal','sadasdasd','2024-06-30 02:33:00','2024-07-11 20:42:00'),(16,2,1,'Violence or Theft','oijp;kl','2024-06-30 02:47:00','2024-07-11 21:01:45'),(17,2,1,'Reckless Driving','sadasdas','2024-06-30 02:47:00','2024-07-11 21:01:45'),(18,2,1,'Reckless Driving','asdasdas','2024-06-30 02:47:00','2024-07-11 21:01:45'),(19,2,1,'Parking Violations','adas','2024-06-30 03:02:00','2024-07-12 03:06:58'),(20,2,1,'Parking Violations','asdasd','2024-06-30 03:02:00','2024-07-12 03:06:58'),(21,2,1,'Parking Violations','sadasd','2024-06-30 03:02:00','2024-07-12 03:06:58'),(22,2,1,'Parking Violations','asdasd','2024-06-30 03:08:00','2024-07-12 03:17:45'),(23,2,1,'Parking Violations','asdasd','2024-06-30 03:09:00','2024-07-12 03:17:45'),(24,2,1,'Reckless Driving','asdasd','2024-06-30 03:09:00','2024-07-12 03:17:45'),(25,3,1,'Reckless Driving','asdasd','2024-06-30 03:16:00','2024-07-12 03:17:48'),(26,3,1,'Reckless Driving','sadas','2024-06-30 03:16:00','2024-07-12 03:17:48'),(27,3,1,'Parking Violations','asdasd','2024-06-30 03:16:00','2024-07-12 03:17:48'),(30,2,1,'Parking Violations','asdasdasd','2024-06-30 02:08:00','2024-07-15 02:09:53'),(31,2,1,'Noise Violations','wsdasdasd','2024-06-30 02:08:00','2024-07-15 02:09:53'),(32,2,1,'Driving Under the Influence (DUI)','wsdasdasd','2024-06-30 02:08:00','2024-07-15 02:09:53'),(33,2,1,'Parking Violations','12213123','2024-06-30 02:10:00','2024-07-15 02:10:50'),(34,2,1,'Driving Under the Influence (DUI)','21312312','2024-06-30 02:10:00','2024-07-15 02:10:50'),(35,2,1,'Parking Violations','12312312','2024-06-30 02:10:00','2024-07-15 02:10:50'),(36,2,1,'Parking Violations','123123','2024-06-30 02:10:00','2024-07-26 14:56:12'),(37,2,1,'Reckless Driving','21321321','2024-06-30 02:11:00','2024-07-26 14:56:12'),(38,2,1,'Reckless Driving','213123','2024-06-30 02:11:00','2024-07-26 14:56:12'),(39,2,1,'Driving Under the Influence (DUI)','asdas','2024-09-08 01:57:00','2024-09-30 01:58:12'),(40,2,1,'Driving Under the Influence (DUI)','asdasd','2024-09-10 01:57:00','2024-09-30 01:58:12'),(41,2,1,'Reckless Driving','asdas','2024-09-02 01:58:00','2024-09-30 01:58:12');
/*!40000 ALTER TABLE `tbl_violation` ENABLE KEYS */;
UNLOCK TABLES;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2024-09-30  3:49:13
