-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: May 16, 2025 at 06:47 AM
-- Server version: 10.4.32-MariaDB
-- PHP Version: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `smart_farm`
--
CREATE DATABASE IF NOT EXISTS `smart_farm` DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci;
USE `smart_farm`;

-- --------------------------------------------------------

--
-- Table structure for table `auto_modes`
--

CREATE TABLE `auto_modes` (
  `device` varchar(50) NOT NULL,
  `enabled` tinyint(1) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `auto_modes`
--

INSERT INTO `auto_modes` (`device`, `enabled`) VALUES
('light', 0),
('water', 0);

-- --------------------------------------------------------

--
-- Table structure for table `device_logs`
--

CREATE TABLE `device_logs` (
  `id` int(11) NOT NULL,
  `device` varchar(50) NOT NULL,
  `action` varchar(10) NOT NULL,
  `timestamp` datetime NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `device_logs`
--

INSERT INTO `device_logs` (`id`, `device`, `action`, `timestamp`) VALUES
(1, 'water', 'ON', '2025-05-08 20:38:59'),
(2, 'water', 'ON', '2025-05-08 20:39:06'),
(3, 'water', '1', '2025-05-08 21:13:59'),
(4, 'water', '0', '2025-05-08 21:14:03'),
(5, 'water', '1', '2025-05-08 21:14:19'),
(6, 'light', '1', '2025-05-08 21:14:29'),
(7, 'light', '0', '2025-05-08 21:14:31'),
(8, 'water', '0', '2025-05-08 21:14:32'),
(9, 'water', '1', '2025-05-08 21:36:16'),
(10, 'water', '0', '2025-05-08 21:37:03'),
(11, 'water', '1', '2025-05-09 15:08:09'),
(12, 'water', '0', '2025-05-09 15:08:27'),
(13, 'light', '1', '2025-05-09 15:08:32'),
(14, 'light', '0', '2025-05-09 15:08:48'),
(15, 'light', '1', '2025-05-09 15:08:51'),
(16, 'light', '0', '2025-05-09 15:09:01'),
(17, 'light', '1', '2025-05-09 15:11:54'),
(18, 'light', '0', '2025-05-09 15:11:55'),
(19, 'light', '1', '2025-05-09 15:12:10'),
(20, 'light', '0', '2025-05-09 15:12:20'),
(21, 'water', '1', '2025-05-09 15:19:28'),
(22, 'water', '0', '2025-05-09 15:19:33'),
(23, 'water', '1', '2025-05-09 15:19:42'),
(24, 'water', '0', '2025-05-09 15:19:45'),
(25, 'water', '1', '2025-05-09 15:19:56'),
(26, 'water', '0', '2025-05-09 15:19:59'),
(27, 'water', '1', '2025-05-09 15:22:30'),
(28, 'water', '0', '2025-05-09 15:22:33'),
(29, 'water', '1', '2025-05-09 15:23:08'),
(30, 'water', '0', '2025-05-09 15:23:34'),
(31, 'water', '1', '2025-05-09 15:23:36'),
(32, 'water', '0', '2025-05-09 15:23:37'),
(33, 'water', '1', '2025-05-09 15:23:38'),
(34, 'water', '0', '2025-05-09 15:23:39'),
(35, 'water', '1', '2025-05-09 15:23:43'),
(36, 'water', '0', '2025-05-09 15:23:46'),
(37, 'water', '1', '2025-05-09 15:25:38'),
(38, 'water', '0', '2025-05-09 15:25:41'),
(39, 'water', '1', '2025-05-09 15:26:53'),
(40, 'water', '0', '2025-05-09 15:26:56'),
(41, 'light', '1', '2025-05-09 15:27:25'),
(42, 'light', '0', '2025-05-09 15:27:35'),
(43, 'water', '1', '2025-05-09 15:28:36'),
(44, 'water', '0', '2025-05-09 15:28:39'),
(45, 'water', '1', '2025-05-09 15:30:11'),
(46, 'water', '0', '2025-05-09 15:30:13'),
(47, 'water', '1', '2025-05-09 15:36:44'),
(48, 'water', '0', '2025-05-09 15:36:47'),
(49, 'water', '1', '2025-05-09 15:37:02'),
(50, 'water', '0', '2025-05-09 15:37:04'),
(51, 'water', '1', '2025-05-09 15:37:18'),
(52, 'water', '0', '2025-05-09 15:37:21'),
(53, 'water', '1', '2025-05-09 15:47:38'),
(54, 'water', '0', '2025-05-09 15:47:40'),
(55, 'water', '1', '2025-05-09 15:49:44'),
(56, 'water', '0', '2025-05-09 15:49:48'),
(57, 'water', '1', '2025-05-09 15:49:53'),
(58, 'water', '0', '2025-05-09 15:49:58'),
(59, 'water', '1', '2025-05-16 09:40:03'),
(60, 'water', '0', '2025-05-16 09:40:11'),
(61, 'light', '1', '2025-05-16 09:40:14'),
(62, 'light', '0', '2025-05-16 09:40:16'),
(63, 'light', '1', '2025-05-16 09:40:51'),
(64, 'light', '0', '2025-05-16 09:40:53'),
(65, 'light', '1', '2025-05-16 09:41:36'),
(66, 'light', '0', '2025-05-16 09:41:39'),
(67, 'water', '1', '2025-05-16 09:41:41'),
(68, 'water', '0', '2025-05-16 09:41:43'),
(69, 'water', '1', '2025-05-16 09:41:47'),
(70, 'water', '0', '2025-05-16 09:41:48'),
(71, 'light', '1', '2025-05-16 09:41:51'),
(72, 'light', '0', '2025-05-16 09:41:52'),
(73, 'water', '1', '2025-05-16 09:41:53'),
(74, 'water', '0', '2025-05-16 09:41:54'),
(75, 'light', '1', '2025-05-16 09:42:03'),
(76, 'light', '0', '2025-05-16 09:42:13'),
(77, 'water', '1', '2025-05-16 09:43:45'),
(78, 'water', '0', '2025-05-16 09:43:46'),
(79, 'light', '1', '2025-05-16 09:44:05'),
(80, 'light', '0', '2025-05-16 09:44:07'),
(81, 'water', '1', '2025-05-16 09:44:50'),
(82, 'water', '0', '2025-05-16 09:44:53'),
(83, 'light', '1', '2025-05-16 09:45:23'),
(84, 'light', '0', '2025-05-16 09:45:33'),
(85, 'light', '1', '2025-05-16 09:45:43'),
(86, 'light', '0', '2025-05-16 09:45:53'),
(87, 'light', '1', '2025-05-16 09:46:03'),
(88, 'light', '0', '2025-05-16 09:46:13'),
(89, 'light', '1', '2025-05-16 09:46:23'),
(90, 'light', '0', '2025-05-16 09:46:33'),
(91, 'light', '1', '2025-05-16 09:46:43'),
(92, 'light', '0', '2025-05-16 09:46:53'),
(93, 'light', '1', '2025-05-16 09:47:03'),
(94, 'light', '0', '2025-05-16 09:47:13'),
(95, 'light', '1', '2025-05-16 09:47:23'),
(96, 'light', '0', '2025-05-16 09:47:33'),
(97, 'light', '1', '2025-05-16 09:47:43'),
(98, 'light', '0', '2025-05-16 09:48:39'),
(99, 'light', '1', '2025-05-16 09:49:39'),
(100, 'light', '0', '2025-05-16 09:50:39'),
(101, 'light', '1', '2025-05-16 09:51:39'),
(102, 'light', '0', '2025-05-16 09:52:19'),
(103, 'light', '1', '2025-05-16 09:52:23'),
(104, 'light', '0', '2025-05-16 09:52:33'),
(105, 'light', '1', '2025-05-16 09:54:18'),
(106, 'light', '0', '2025-05-16 09:54:19'),
(107, 'light', '1', '2025-05-16 09:54:23'),
(108, 'light', '0', '2025-05-16 09:54:33'),
(109, 'light', '1', '2025-05-16 09:56:13'),
(110, 'light', '0', '2025-05-16 09:56:23');

-- --------------------------------------------------------

--
-- Table structure for table `device_status`
--

CREATE TABLE `device_status` (
  `ID` tinyint(4) NOT NULL,
  `Name` varchar(10) NOT NULL,
  `Status` enum('on','off') NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `device_status`
--

INSERT INTO `device_status` (`ID`, `Name`, `Status`) VALUES
(1, 'water', 'off'),
(2, 'light', 'off');

-- --------------------------------------------------------

--
-- Table structure for table `sensor_data`
--

CREATE TABLE `sensor_data` (
  `id` int(11) NOT NULL,
  `temperature` float NOT NULL,
  `humidity` float NOT NULL,
  `light` float NOT NULL,
  `timestamp` datetime NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `sensor_data`
--

INSERT INTO `sensor_data` (`id`, `temperature`, `humidity`, `light`, `timestamp`) VALUES
(1, 28.2, 54, 135, '2025-05-16 10:49:50');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `auto_modes`
--
ALTER TABLE `auto_modes`
  ADD PRIMARY KEY (`device`);

--
-- Indexes for table `device_logs`
--
ALTER TABLE `device_logs`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `device_status`
--
ALTER TABLE `device_status`
  ADD PRIMARY KEY (`ID`);

--
-- Indexes for table `sensor_data`
--
ALTER TABLE `sensor_data`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `device_logs`
--
ALTER TABLE `device_logs`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=111;

--
-- AUTO_INCREMENT for table `device_status`
--
ALTER TABLE `device_status`
  MODIFY `ID` tinyint(4) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `sensor_data`
--
ALTER TABLE `sensor_data`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
