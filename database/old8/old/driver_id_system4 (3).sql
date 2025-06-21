-- phpMyAdmin SQL Dump
-- version 4.8.5
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Nov 10, 2024 at 07:55 AM
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
  `email` varchar(255) NOT NULL,
  `username` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL,
  `attempt` varchar(255) NOT NULL,
  `relog_time` datetime NOT NULL,
  `login_time` datetime NOT NULL,
  `logout_time` datetime NOT NULL,
  `account_type` int(1) NOT NULL,
  `date_registered` datetime NOT NULL,
  `img` varchar(255) NOT NULL,
  `status` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Table structure for table `tbl_appointment`
--

CREATE TABLE `tbl_appointment` (
  `sched_id` int(20) NOT NULL,
  `fk_driver_id` int(11) DEFAULT NULL,
  `appointment_date` date NOT NULL,
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

-- --------------------------------------------------------

--
-- Table structure for table `tbl_comment`
--

CREATE TABLE `tbl_comment` (
  `comment_id` int(11) NOT NULL,
  `fk_driver_id` int(11) DEFAULT NULL,
  `comment_details` longtext,
  `comment_date` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

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
  `pic_2x2` varchar(255) NOT NULL,
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
-- Table structure for table `tbl_log`
--

CREATE TABLE `tbl_log` (
  `log_id` int(11) NOT NULL,
  `fk_admin_id` int(11) DEFAULT NULL,
  `action_details` varchar(255) DEFAULT NULL,
  `action_date` datetime NOT NULL,
  `fk_driver_id` int(11) DEFAULT NULL
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
  `vehicle_registered` datetime DEFAULT NULL,
  `vehicle_img_front` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Table structure for table `tbl_violation`
--

CREATE TABLE `tbl_violation` (
  `violation_id` int(11) NOT NULL,
  `fk_driver_id` int(11) DEFAULT NULL,
  `fk_admin_id` int(11) DEFAULT NULL,
  `violation_category` enum('Improper Garbage Disposal','Driving Under the Influence (DUI)','Parking Violations','Reckless Driving','Violence or Theft','Unauthorized Transport Operations','Noise Violations','Illegal Parking of Tricycles','Other...(Please Specify in Description)') DEFAULT NULL,
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
-- Indexes for table `tbl_comment`
--
ALTER TABLE `tbl_comment`
  ADD PRIMARY KEY (`comment_id`),
  ADD KEY `fk_driver_id_comment` (`fk_driver_id`);

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
-- Indexes for table `tbl_log`
--
ALTER TABLE `tbl_log`
  ADD PRIMARY KEY (`log_id`),
  ADD KEY `fk_admin_id` (`fk_admin_id`),
  ADD KEY `fk_driver_id` (`fk_driver_id`);

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
  MODIFY `admin_id` int(11) NOT NULL AUTO_INCREMENT;

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
  MODIFY `calendar_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `tbl_comment`
--
ALTER TABLE `tbl_comment`
  MODIFY `comment_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `tbl_driver`
--
ALTER TABLE `tbl_driver`
  MODIFY `driver_id` int(4) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `tbl_log`
--
ALTER TABLE `tbl_log`
  MODIFY `log_id` int(11) NOT NULL AUTO_INCREMENT;

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
-- Constraints for table `tbl_comment`
--
ALTER TABLE `tbl_comment`
  ADD CONSTRAINT `fk_driver_id_comment` FOREIGN KEY (`fk_driver_id`) REFERENCES `tbl_driver` (`driver_id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `tbl_driver`
--
ALTER TABLE `tbl_driver`
  ADD CONSTRAINT `fk_admin_id_driver` FOREIGN KEY (`fk_admin_id`) REFERENCES `tbl_admin` (`admin_id`),
  ADD CONSTRAINT `fk_association_id_driver` FOREIGN KEY (`fk_association_id`) REFERENCES `tbl_association` (`association_id`),
  ADD CONSTRAINT `fk_sched_id_driver` FOREIGN KEY (`fk_sched_id`) REFERENCES `tbl_appointment` (`sched_id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_vehicle_id_driver` FOREIGN KEY (`fk_vehicle_id`) REFERENCES `tbl_vehicle` (`vehicle_id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `tbl_log`
--
ALTER TABLE `tbl_log`
  ADD CONSTRAINT `tbl_log_ibfk_1` FOREIGN KEY (`fk_admin_id`) REFERENCES `tbl_admin` (`admin_id`),
  ADD CONSTRAINT `tbl_log_ibfk_2` FOREIGN KEY (`fk_driver_id`) REFERENCES `tbl_driver` (`driver_id`);

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
