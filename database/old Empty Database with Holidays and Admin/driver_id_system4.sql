-- phpMyAdmin SQL Dump
-- version 4.8.5
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Aug 01, 2024 at 11:29 PM
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
-- Database: `driver_id_system4`
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
  `relog_time` datetime DEFAULT NULL,
  `login_time` datetime DEFAULT NULL,
  `logout_time` datetime DEFAULT NULL,
  `account_type` int(1) NOT NULL,
  `date_registered` datetime NOT NULL,
  `img` varchar(255) DEFAULT NULL,
  `status` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `tbl_admin`
--

INSERT INTO `tbl_admin` (`admin_id`, `first_name`, `middle_name`, `last_name`, `sex`, `mobile_number`, `username`, `password`, `attempt`, `relog_time`, `login_time`, `logout_time`, `account_type`, `date_registered`, `img`, `status`) VALUES
(1, 'Super Admin', 'E', 'Siya', 'Male', '09090909090', 'admin', '123', '', NULL, NULL, NULL, 1, '2024-08-02 05:30:00', '7_UPLOD_9 Ma_Lodi_2024-07-25_18-43-58_43444.jpg', 'Default Approved');

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
  `association_color` varchar(255) NOT NULL,
  `association_color_name` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Table structure for table `tbl_calendar`
--

CREATE TABLE `tbl_calendar` (
  `calendar_id` int(11) NOT NULL,
  `calendar_date` date NOT NULL,
  `calendar_description` varchar(255) DEFAULT NULL,
  `slots` int(11) DEFAULT NULL,
  `end_time` time DEFAULT NULL,
  `calendar_control` enum('Enable','Disable') NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `tbl_calendar`
--

INSERT INTO `tbl_calendar` (`calendar_id`, `calendar_date`, `calendar_description`, `slots`, `end_time`, `calendar_control`) VALUES
(1, '2024-01-01', 'New Year\'s Day - Regular Holiday', NULL, NULL, 'Disable'),
(2, '2024-03-28', 'Maundy Thursday - Regular Holiday', NULL, NULL, 'Disable'),
(3, '2024-03-29', 'Good Friday - Regular Holiday', NULL, NULL, 'Disable'),
(4, '2024-04-09', 'Araw ng Kagitingan - Regular Holiday', NULL, NULL, 'Disable'),
(5, '2024-05-01', 'Labor Day - Regular Holiday', NULL, NULL, 'Disable'),
(6, '2024-06-12', 'Independence Day - Regular Holiday', NULL, NULL, 'Disable'),
(7, '2024-08-26', 'National Heroes Day - Regular Holiday', NULL, NULL, 'Disable'),
(8, '2024-11-30', 'Bonifacio Day - Regular Holiday', NULL, NULL, 'Disable'),
(9, '2024-12-25', 'Christmas Day - Regular Holiday', NULL, NULL, 'Disable'),
(10, '2024-12-30', 'Rizal Day - Regular Holiday', NULL, NULL, 'Disable'),
(11, '2024-02-10', 'Chinese New Year - Special (Non-Working) Day', NULL, NULL, 'Disable'),
(12, '2024-02-25', 'EDSA People Power Revolution Anniversary - Special (Non-Working) Day', NULL, NULL, 'Disable'),
(13, '2024-03-30', 'Black Saturday - Special (Non-Working) Day', NULL, NULL, 'Disable'),
(14, '2024-08-21', 'Ninoy Aquino Day - Special (Non-Working) Day', NULL, NULL, 'Disable'),
(15, '2024-11-01', 'All Saints\' Day - Special (Non-Working) Day', NULL, NULL, 'Disable'),
(16, '2024-11-02', 'All Souls\' Day - Special (Non-Working) Day', NULL, NULL, 'Disable'),
(17, '2024-12-08', 'Feast of the Immaculate Conception of the Blessed Virgin Mary - Special (Non-Working) Day', NULL, NULL, 'Disable'),
(18, '2024-12-24', 'Christmas Eve - Special (Non-Working) Day', NULL, NULL, 'Disable'),
(19, '2024-12-31', 'New Year\'s Eve - Special (Non-Working) Day', NULL, NULL, 'Disable');

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
  `vehicle_registered` datetime DEFAULT NULL,
  `vehicle_img_front` varchar(255) NOT NULL,
  `vehicle_img_back` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

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
  `violation_date` datetime NOT NULL,
  `renewed_date` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

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
-- Indexes for table `tbl_calendar`
--
ALTER TABLE `tbl_calendar`
  ADD PRIMARY KEY (`calendar_id`);

--
-- Indexes for table `tbl_driver`
--
ALTER TABLE `tbl_driver`
  ADD PRIMARY KEY (`driver_id`),
  ADD KEY `fk_association_id_driver` (`fk_association_id`),
  ADD KEY `fk_admin_id_driver` (`fk_admin_id`),
  ADD KEY `fk_sched_id_driver` (`fk_sched_id`),
  ADD KEY `fk_vehicle_id_driver` (`fk_vehicle_id`);

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
  ADD KEY `fk_admin_id_violation` (`fk_admin_id`),
  ADD KEY `fk_driver_id_violation` (`fk_driver_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `tbl_admin`
--
ALTER TABLE `tbl_admin`
  MODIFY `admin_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `tbl_appointment`
--
ALTER TABLE `tbl_appointment`
  MODIFY `sched_id` int(20) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `tbl_association`
--
ALTER TABLE `tbl_association`
  MODIFY `association_id` int(5) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `tbl_calendar`
--
ALTER TABLE `tbl_calendar`
  MODIFY `calendar_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=20;

--
-- AUTO_INCREMENT for table `tbl_driver`
--
ALTER TABLE `tbl_driver`
  MODIFY `driver_id` int(4) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `tbl_vehicle`
--
ALTER TABLE `tbl_vehicle`
  MODIFY `vehicle_id` int(5) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `tbl_violation`
--
ALTER TABLE `tbl_violation`
  MODIFY `violation_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `tbl_appointment`
--
ALTER TABLE `tbl_appointment`
  ADD CONSTRAINT `fk_driver_id_appointment` FOREIGN KEY (`fk_driver_id`) REFERENCES `tbl_driver` (`driver_id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `tbl_driver`
--
ALTER TABLE `tbl_driver`
  ADD CONSTRAINT `fk_admin_id_driver` FOREIGN KEY (`fk_admin_id`) REFERENCES `tbl_admin` (`admin_id`),
  ADD CONSTRAINT `fk_association_id_driver` FOREIGN KEY (`fk_association_id`) REFERENCES `tbl_association` (`association_id`),
  ADD CONSTRAINT `fk_sched_id_driver` FOREIGN KEY (`fk_sched_id`) REFERENCES `tbl_appointment` (`sched_id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_vehicle_id_driver` FOREIGN KEY (`fk_vehicle_id`) REFERENCES `tbl_vehicle` (`vehicle_id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `tbl_vehicle`
--
ALTER TABLE `tbl_vehicle`
  ADD CONSTRAINT `fk_driver_id_vehicle` FOREIGN KEY (`fk_driver_id`) REFERENCES `tbl_driver` (`driver_id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `tbl_violation`
--
ALTER TABLE `tbl_violation`
  ADD CONSTRAINT `fk_admin_id_violation` FOREIGN KEY (`fk_admin_id`) REFERENCES `tbl_admin` (`admin_id`),
  ADD CONSTRAINT `fk_driver_id_violation` FOREIGN KEY (`fk_driver_id`) REFERENCES `tbl_driver` (`driver_id`) ON DELETE CASCADE ON UPDATE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
