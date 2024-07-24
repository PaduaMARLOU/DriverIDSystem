-- phpMyAdmin SQL Dump
-- version 4.8.5
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Jul 04, 2024 at 10:51 AM
-- Server version: 10.1.38-MariaDB
-- PHP Version: 7.3.2

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `driver_id_system2`
--

-- --------------------------------------------------------

--
-- Table structure for table `tbl_admin`
--

CREATE TABLE `tbl_admin` (
  `admin_id` int(11) NOT NULL,
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
  `img` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `tbl_admin`
--

INSERT INTO `tbl_admin` (`admin_id`, `first_name`, `middle_name`, `last_name`, `sex`, `mobile_number`, `username`, `password`, `attempt`, `relog_time`, `login_time`, `logout_time`, `account_type`, `date_registered`, `img`) VALUES
(1, 'Wakamole', 'Medav', 'Plamor', 'Male', '09090909090', 'admin', '123', '0', '0000-00-00 00:00:00', '2024-07-03 17:54:51', '2024-07-03 17:52:12', 1, '0000-00-00 00:00:00', ''),
(2, 'asdsad', 'adasda', 'asdasdas', 'asdsad', '09090909090', 'admin2', '123', '0', '0000-00-00 00:00:00', '2024-07-02 19:21:38', '2024-07-02 19:22:00', 2, '0000-00-00 00:00:00', ''),
(3, 'asdada', 'asdada', 'dsadas', 'dasdasd', '09090909090', 'admin3', '123', '', '0000-00-00 00:00:00', '0000-00-00 00:00:00', '0000-00-00 00:00:00', 3, '0000-00-00 00:00:00', '');

-- --------------------------------------------------------

--
-- Table structure for table `tbl_appointment`
--

CREATE TABLE `tbl_appointment` (
  `sched_id` int(20) NOT NULL,
  `fk_driver_id` int(11) DEFAULT NULL,
  `DATE` date NOT NULL,
  `booking_date` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `tbl_appointment`
--

INSERT INTO `tbl_appointment` (`sched_id`, `fk_driver_id`, `DATE`, `booking_date`) VALUES
(1, 2, '2024-07-01', '0000-00-00 00:00:00'),
(2, 3, '2024-07-01', '0000-00-00 00:00:00'),
(3, 4, '2024-07-01', '0000-00-00 00:00:00'),
(4, 5, '2024-07-01', '0000-00-00 00:00:00'),
(5, 7, '2024-07-01', '2024-06-28 12:45:37'),
(6, 8, '2024-07-01', '2024-06-28 18:47:50'),
(7, 9, '2024-07-01', '2024-06-28 19:09:13'),
(8, 10, '2024-07-01', '2024-06-28 19:12:39'),
(9, 11, '2024-07-01', '2024-06-28 19:21:59'),
(10, 12, '2024-07-01', '2024-06-28 19:24:55'),
(11, 13, '2024-07-01', '2024-06-28 19:34:57'),
(12, 16, '2024-07-01', '2024-06-30 17:47:31'),
(13, 17, '2024-07-01', '2024-06-30 17:52:33'),
(14, 18, '2024-07-01', '2024-06-30 17:56:41'),
(15, 19, '2024-07-01', '2024-06-30 18:07:22');

-- --------------------------------------------------------

--
-- Table structure for table `tbl_association`
--

CREATE TABLE `tbl_association` (
  `association_id` int(5) NOT NULL,
  `association_category` enum('E-Bike','Tricycle','Trisikad') NOT NULL,
  `association_name` varchar(255) NOT NULL,
  `association_area` varchar(255) NOT NULL,
  `association_president` varchar(255) NOT NULL,
  `association_color` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `tbl_association`
--

INSERT INTO `tbl_association` (`association_id`, `association_category`, `association_name`, `association_area`, `association_president`, `association_color`) VALUES
(1, 'E-Bike', 'sa balay', 'dasdasdasd', 'dsadaasdasdaa', 'sadasdasdasd');

-- --------------------------------------------------------

--
-- Table structure for table `tbl_driver`
--

CREATE TABLE `tbl_driver` (
  `driver_id` int(4) NOT NULL,
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
  `fk_admin_id` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `tbl_driver`
--

INSERT INTO `tbl_driver` (`driver_id`, `driver_category`, `formatted_id`, `first_name`, `middle_name`, `last_name`, `suffix_name`, `nickname`, `age`, `birth_date`, `birth_place`, `sex`, `address`, `mobile_number`, `civil_status`, `religion`, `citizenship`, `height`, `weight`, `pic_2x2`, `doc_proof`, `name_to_notify`, `relationship`, `num_to_notify`, `vehicle_ownership`, `verification_stat`, `fk_association_id`, `driver_registered`, `renew_stat`, `fk_sched_id`, `fk_vehicle_id`, `fk_admin_id`) VALUES
(2, 'E-Bike', 'ETRK-0002', 'Wabafet', 'Ma', 'Lodi', 'Jr', 'asdsad', 0, '2024-05-26', 'sadsad', 'Male', 'asdasd', '09090909090', 'Single', 'sadsad', 'sadasd', 0, 0, '', '', 'sadasd', 'sadas', '09909099900', 'Owned', 'Registered', 1, '2024-07-03 00:00:00', 'Active', 1, 1, NULL),
(3, 'E-Bike', 'ETRK-0003', 'Dabaret', 'Ma', 'Lodi', 'Jr', 'asdsad', 0, '2024-05-26', 'sadsad', 'Male', 'asdasd', '09090909090', 'Single', 'sadsad', 'sadasd', 0, 0, '', '', 'sadasd', 'sadas', '09909099900', 'Owned', 'Registered', 1, '2024-07-03 18:52:30', 'Active', 2, 2, 1),
(4, 'E-Bike', 'ETRK-0004', 'Dorder', 'Ma', 'Lodi', 'Jr', 'asdsad', 22, '2002-05-01', 'sadsad', 'Male', 'asdasd', '09090909090', 'Single', 'sadsad', 'sadasd', 12, 12, '', '', 'sadasd', 'sadas', '09909099900', 'Owned', 'Pending', 1, NULL, NULL, 3, 3, NULL),
(5, 'E-Bike', 'ETRK-0005', 'malo', 'Ma', 'Lodi', 'Jr', 'asdsad', 20, '2003-08-17', 'sadsad', 'Male', 'asdasd', '09090909090', 'Single', 'sadsad', 'sadasd', 12, 12, '', '', 'sadasd', 'sadas', '09909099900', 'Owned', 'Pending', 1, NULL, NULL, 4, 4, NULL),
(7, 'E-Bike', 'ETRK-0007', 'malo', 'Ma', 'Lodi', 'Jr', 'asdsad', 0, '2024-06-28', 'sadsad', '', 'asdasd', '09090909090', '', 'sadsad', 'sadasd', 12, 12, '', '', 'sadasd', 'sadas', '09909099900', '', 'Pending', 1, NULL, NULL, 5, 5, NULL),
(8, 'E-Bike', 'ETRK-0008', 'Aduken', 'Ma', 'Lodi', 'Jr', 'asdsad', 0, '2024-05-27', 'sadsad', 'Male', 'asdasd', '09090909090', 'Single', 'sadsad', 'sadasd', 12, 12, '', '', 'sadasd', 'sadas', '09909099900', 'Owned', 'Pending', 1, NULL, NULL, 6, 6, NULL),
(9, 'E-Bike', 'ETRK-0009', 'Masdad', 'Ma', 'Lodi', 'Jr', 'asdsad', 3, '2020-12-27', 'sadsad', 'Male', 'asdasd', '09090909090', 'Single', 'sadsad', 'sadasd', 12, 12, 'Cost Benefit Analysis 2.png', 'Cost Benefit Analysis 2.png', 'sadasd', 'sadas', '09909099900', 'Owned', 'Pending', 1, NULL, NULL, 7, 7, NULL),
(10, 'E-Bike', 'ETRK-0010', 'Bushzxada', 'Ma', 'Lodi', 'Jr', 'asdsad', 3, '2020-12-27', 'sadsad', 'Male', 'asdasd', '09090909090', 'Single', 'sadsad', 'sadasd', 12, 12, 'Cost Benefit Analysis 2.png', 'Cost Benefit Analysis 2.png', 'sadasd', 'sadas', '09909099900', 'Owned', 'Pending', 1, NULL, NULL, 8, 8, NULL),
(11, 'E-Bike', 'ETRK-0011', 'SADASD', 'Ma', 'Lodi', 'Jr', 'asdsad', 6, '2018-01-07', 'sadsad', 'Male', 'asdasd', '09090909090', 'Single', 'sadsad', 'sadasd', 12, 12, 'Cost Benefit Analysis 1.png', 'Cost Benefit Analysis 1.png', 'sadasd', 'sadas', '09909099900', 'Owned', 'Pending', 1, NULL, NULL, 9, 9, NULL),
(12, 'E-Bike', 'ETRK-0012', 'asdasdas', 'Ma', 'Lodi', 'Jr', 'asdsad', 0, '2024-04-15', 'sadsad', 'Male', 'asdasd', '09090909090', 'Single', 'sadsad', 'sadasd', 12, 12, 'Cost Benefit Analysis 2.png', 'Cost Benefit Analysis 2.png', 'sadasd', 'sadas', '09909099900', 'Owned', 'Pending', 1, NULL, NULL, 10, 10, NULL),
(13, 'Trisikad', 'TSKD-0013', 'Modaves', 'Ma', 'Lodi', 'Jr', 'asdsad', 8, '2015-12-27', 'sadsad', 'Male', 'asdasd', '09090909090', 'Single', 'sadsad', 'sadasd', 12, 12, 'Cost Benefit Analysis 2.png', 'Cost Benefit Analysis 2.png', 'sadasd', 'sadas', '09909099900', 'Owned', 'Pending', 1, NULL, NULL, 11, 11, NULL),
(16, 'E-Bike', 'ETRK-0016', 'UPLOD 2', 'Ma', 'Lodi', 'Jr', 'asdsad', 0, '2024-06-30', 'sadsad', '', 'asdasd', '09090909090', '', 'sadsad', 'sadasd', 12, 12, 'uploads/drivers/668129b387e33_Cat.jpg', 'uploads/documents/668129b388155_Cat.jpg', 'sadasd', 'sadas', '09909099900', '', 'Pending', 1, NULL, NULL, 12, 12, NULL),
(17, 'E-Bike', 'ETRK-0017', 'UPLOD 3', 'Ma', 'Lodi', 'Jr', 'asdsad', 0, '2024-06-30', 'sadsad', '', 'asdasd', '09090909090', '', 'sadsad', 'sadasd', 12, 12, '', '', 'sadasd', 'sadas', '09909099900', '', 'Pending', 1, NULL, NULL, 13, 13, NULL),
(18, 'E-Bike', 'ETRK-0018', 'UPLOD 4', 'Ma', 'Lodi', 'Jr', 'asdsad', 0, '2024-06-30', 'sadsad', '', 'asdasd', '09090909090', '', 'sadsad', 'sadasd', 12, 12, 'uploads/drivers/66812bd90d2f0_ETRK-0018_Cat.jpg', 'uploads/documents/66812bd90d6f6_ETRK-0018_Cat.jpg', 'sadasd', 'sadas', '09909099900', '', 'Pending', 1, NULL, NULL, 14, 14, NULL),
(19, 'E-Bike', 'ETRK-0019', 'UPLOD 7', 'Ma', 'Lodi', 'Jr', 'asdsad', 0, '2024-06-30', 'sadsad', '', 'asdasd', '09090909090', '', 'sadsad', 'sadasd', 12, 12, 'uploads/drivers/66812e5ad1a53_ETRK-0019_Cost Benefit Analysis 2.png', 'uploads/documents/66812e5ad1c7f_ETRK-0019_Cost Benefit Analysis 2.png', 'sadasd', 'sadas', '09909099900', '', 'Pending', 1, NULL, NULL, 15, 15, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `tbl_vehicle`
--

CREATE TABLE `tbl_vehicle` (
  `vehicle_id` int(5) NOT NULL,
  `fk_driver_id` int(11) DEFAULT NULL,
  `vehicle_category` varchar(255) NOT NULL,
  `name_of_owner` varchar(255) NOT NULL,
  `addr_of_owner` varchar(255) NOT NULL,
  `owner_phone_num` varchar(11) NOT NULL,
  `vehicle_color` varchar(255) NOT NULL,
  `brand` varchar(255) NOT NULL,
  `plate_num` varchar(20) DEFAULT NULL,
  `vehicle_registered` datetime NOT NULL,
  `vehicle_img_front` varchar(255) NOT NULL,
  `vehicle_img_back` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `tbl_vehicle`
--

INSERT INTO `tbl_vehicle` (`vehicle_id`, `fk_driver_id`, `vehicle_category`, `name_of_owner`, `addr_of_owner`, `owner_phone_num`, `vehicle_color`, `brand`, `plate_num`, `vehicle_registered`, `vehicle_img_front`, `vehicle_img_back`) VALUES
(1, 2, 'E-Bike', 'Wabafet Ma Lodi', 'asdasd', '09090909090', 'sdasad', 'asdsad', 'asdsa', '0000-00-00 00:00:00', '', ''),
(2, 3, 'E-Bike', 'Dabaret Ma Lodi', 'asdasd', '09090909090', 'sdasad', 'asdsad', 'asdsa', '0000-00-00 00:00:00', '', ''),
(3, 4, 'E-Bike', 'Dabaret Ma Lodi', 'asdasd', '09090909090', 'sdasad', 'asdsad', 'asdsa', '0000-00-00 00:00:00', '', ''),
(4, 5, 'E-Bike', 'malo Ma Lodi', 'asdasd', '09090909090', 'sdasad', 'asdsad', 'asdsa', '0000-00-00 00:00:00', '', ''),
(5, 7, 'E-Bike', 'malo Ma Lodi', 'asdasd', '09090909090', 'sdasad', 'asdsad', 'asdsa', '0000-00-00 00:00:00', '', ''),
(6, 8, 'E-Bike', 'Aduken Ma Lodi', 'asdasd', '09090909090', 'sdasad', 'asdsad', 'asdsa', '0000-00-00 00:00:00', '', ''),
(7, 9, 'E-Bike', 'Masdad Ma Lodi', 'asdasd', '09090909090', 'sdasad', 'asdsad', 'asdsa', '0000-00-00 00:00:00', '', ''),
(8, 10, 'E-Bike', 'Bushzxada Ma Lodi', 'asdasd', '09090909090', 'sdasad', 'asdsad', 'asdsa', '0000-00-00 00:00:00', 'Cost Benefit Analysis 2.png', 'Cost Benefit Analysis 2.png'),
(9, 11, 'E-Bike', 'SADASD Ma Lodi', 'asdasd', '09090909090', 'sdasad', 'asdsad', 'asdsa', '0000-00-00 00:00:00', 'Cost Benefit Analysis 1.png', 'Cost Benefit Analysis 1.png'),
(10, 12, 'E-Bike', 'asdasdas Ma Lodi', 'asdasd', '09090909090', 'sdasad', 'asdsad', 'asdsa', '0000-00-00 00:00:00', 'Cost Benefit Analysis 2.png', 'Cost Benefit Analysis 2.png'),
(11, 13, 'Trisikad', 'Modaves Ma Lodi', 'asdasd', '09090909090', 'sdasad', 'asdsad', 'asdsa', '0000-00-00 00:00:00', 'Cost Benefit Analysis 2.png', 'Cost Benefit Analysis 2.png'),
(12, 16, 'E-Bike', 'Modaves Ma Lodi', 'asdasd', '09090909090', 'sdasad', 'asdsad', 'asdsa', '0000-00-00 00:00:00', 'uploads/vehicles/668129b3883d4_Cat.jpg', 'uploads/vehicles/668129b388652_Cat.jpg'),
(13, 17, 'E-Bike', 'Modaves Ma Lodi', 'asdasd', '09090909090', 'sdasad', 'asdsad', 'asdsa', '0000-00-00 00:00:00', 'uploads/vehicles/66812ae159a55_ETRK-0017_Cat.jpg', 'uploads/vehicles/66812ae159d05_ETRK-0017_Cat.jpg'),
(14, 18, 'E-Bike', 'Modaves Ma Lodi', 'asdasd', '09090909090', 'sdasad', 'asdsad', 'asdsa', '0000-00-00 00:00:00', 'uploads/vehicles/66812bd90da17_ETRK-0018_Cat.jpg', 'uploads/vehicles/66812bd90dc1f_ETRK-0018_Cat.jpg'),
(15, 19, 'E-Bike', 'Modaves Ma Lodi', 'asdasd', '09090909090', 'sdasad', 'asdsad', 'asdsa', '0000-00-00 00:00:00', 'uploads/vehicles/66812e5ad1ec6_ETRK-0019_Cost Benefit Analysis 2.png', 'uploads/vehicles/66812e5ad216d_ETRK-0019_Cost Benefit Analysis 2.png');

-- --------------------------------------------------------

--
-- Table structure for table `tbl_violation`
--

CREATE TABLE `tbl_violation` (
  `violation_id` int(11) NOT NULL,
  `fk_driver_id` int(11) DEFAULT NULL,
  `fk_admin_id` int(11) DEFAULT NULL,
  `violation_category` enum('Improper Garbage Disposal','Driving Under the Influence (DUI)','Parking Violations','Reckless Driving','Violence or Theft','Unauthorized Transport Operations','Noise Violations','Illegal Parking of Tricycles','Other...(Please Specify in Description)') NOT NULL,
  `violation_description` varchar(255) DEFAULT NULL,
  `violation_date` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `tbl_violation`
--

INSERT INTO `tbl_violation` (`violation_id`, `fk_driver_id`, `fk_admin_id`, `violation_category`, `violation_description`, `violation_date`) VALUES
(1, 2, NULL, 'Driving Under the Influence (DUI)', 'Apuchu', '2024-06-30 00:00:00'),
(2, 3, 1, 'Parking Violations', 'ckvv', '2024-06-30 00:00:00'),
(3, 2, 1, 'Improper Garbage Disposal', 'dasdasdadas', '2024-07-01 20:05:00');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `tbl_admin`
--
ALTER TABLE `tbl_admin`
  ADD PRIMARY KEY (`admin_id`);

--
-- Indexes for table `tbl_appointment`
--
ALTER TABLE `tbl_appointment`
  ADD PRIMARY KEY (`sched_id`),
  ADD KEY `fk_driver_id_appointment` (`fk_driver_id`);

--
-- Indexes for table `tbl_association`
--
ALTER TABLE `tbl_association`
  ADD PRIMARY KEY (`association_id`);

--
-- Indexes for table `tbl_driver`
--
ALTER TABLE `tbl_driver`
  ADD PRIMARY KEY (`driver_id`),
  ADD KEY `fk_sched_id_driver` (`fk_sched_id`),
  ADD KEY `fk_vehicle_id_driver` (`fk_vehicle_id`),
  ADD KEY `fk_association_id_driver` (`fk_association_id`),
  ADD KEY `fk_admin_id_driver` (`fk_admin_id`);

--
-- Indexes for table `tbl_vehicle`
--
ALTER TABLE `tbl_vehicle`
  ADD PRIMARY KEY (`vehicle_id`),
  ADD KEY `fk_driver_id_vehicle` (`fk_driver_id`);

--
-- Indexes for table `tbl_violation`
--
ALTER TABLE `tbl_violation`
  ADD PRIMARY KEY (`violation_id`),
  ADD KEY `fk_driver_id_violation` (`fk_driver_id`),
  ADD KEY `fk_admin_id_violation` (`fk_admin_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `tbl_admin`
--
ALTER TABLE `tbl_admin`
  MODIFY `admin_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `tbl_appointment`
--
ALTER TABLE `tbl_appointment`
  MODIFY `sched_id` int(20) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=16;

--
-- AUTO_INCREMENT for table `tbl_association`
--
ALTER TABLE `tbl_association`
  MODIFY `association_id` int(5) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `tbl_driver`
--
ALTER TABLE `tbl_driver`
  MODIFY `driver_id` int(4) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=20;

--
-- AUTO_INCREMENT for table `tbl_vehicle`
--
ALTER TABLE `tbl_vehicle`
  MODIFY `vehicle_id` int(5) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=16;

--
-- AUTO_INCREMENT for table `tbl_violation`
--
ALTER TABLE `tbl_violation`
  MODIFY `violation_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `tbl_appointment`
--
ALTER TABLE `tbl_appointment`
  ADD CONSTRAINT `fk_driver_id_appointment` FOREIGN KEY (`fk_driver_id`) REFERENCES `tbl_driver` (`driver_id`);

--
-- Constraints for table `tbl_driver`
--
ALTER TABLE `tbl_driver`
  ADD CONSTRAINT `fk_admin_id_driver` FOREIGN KEY (`fk_admin_id`) REFERENCES `tbl_admin` (`admin_id`),
  ADD CONSTRAINT `fk_association_id_driver` FOREIGN KEY (`fk_association_id`) REFERENCES `tbl_association` (`association_id`),
  ADD CONSTRAINT `fk_sched_id_driver` FOREIGN KEY (`fk_sched_id`) REFERENCES `tbl_appointment` (`sched_id`),
  ADD CONSTRAINT `fk_vehicle_id_driver` FOREIGN KEY (`fk_vehicle_id`) REFERENCES `tbl_vehicle` (`vehicle_id`);

--
-- Constraints for table `tbl_vehicle`
--
ALTER TABLE `tbl_vehicle`
  ADD CONSTRAINT `fk_driver_id_vehicle` FOREIGN KEY (`fk_driver_id`) REFERENCES `tbl_driver` (`driver_id`);

--
-- Constraints for table `tbl_violation`
--
ALTER TABLE `tbl_violation`
  ADD CONSTRAINT `fk_admin_id_violation` FOREIGN KEY (`fk_admin_id`) REFERENCES `tbl_admin` (`admin_id`),
  ADD CONSTRAINT `fk_driver_id_violation` FOREIGN KEY (`fk_driver_id`) REFERENCES `tbl_driver` (`driver_id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
