-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Feb 16, 2026 at 12:07 PM
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
-- Database: `aep1`
--

-- --------------------------------------------------------

--
-- Table structure for table `admins`
--

CREATE TABLE `admins` (
  `id` int(11) NOT NULL,
  `username` varchar(50) NOT NULL,
  `password_hash` varchar(255) NOT NULL,
  `role` enum('superadmin','admin') NOT NULL DEFAULT 'admin',
  `is_active` tinyint(1) NOT NULL DEFAULT 1,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

--
-- Dumping data for table `admins`
--

INSERT INTO `admins` (`id`, `username`, `password_hash`, `role`, `is_active`, `created_at`) VALUES
(1, 'admin', '$2y$10$CIsC3UlpRt.ZyJ24joU0Be508LeakM7l8XshR3VBAx4z5eERURiHm', 'superadmin', 1, '2025-12-22 12:06:39');

-- --------------------------------------------------------

--
-- Table structure for table `devices`
--

CREATE TABLE `devices` (
  `device_id` int(11) NOT NULL,
  `device_name` varchar(100) NOT NULL,
  `device_location` varchar(100) DEFAULT NULL,
  `device_status` enum('available','in_use','maintenance') NOT NULL DEFAULT 'available',
  `created_at` datetime NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `reports`
--

CREATE TABLE `reports` (
  `report_id` int(11) NOT NULL,
  `user_id` int(11) DEFAULT NULL,
  `reporter_name` varchar(100) NOT NULL,
  `reporter_phone` varchar(30) NOT NULL,
  `room` varchar(50) NOT NULL,
  `detail` text NOT NULL,
  `status` enum('pending','working','done','cancel') NOT NULL DEFAULT 'pending',
  `created_at` datetime NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `request_history`
--

CREATE TABLE `request_history` (
  `hist_id` int(11) NOT NULL,
  `req_id` int(11) DEFAULT NULL,
  `user_id` int(11) NOT NULL,
  `user_name` varchar(255) DEFAULT NULL,
  `user_phone` varchar(50) DEFAULT NULL,
  `device_type` varchar(100) NOT NULL,
  `qty` int(11) NOT NULL DEFAULT 1,
  `location` varchar(255) NOT NULL,
  `start_time` datetime DEFAULT NULL,
  `duration_value` int(11) NOT NULL DEFAULT 1,
  `duration_unit` varchar(10) NOT NULL DEFAULT 'hour',
  `note` text DEFAULT NULL,
  `status` varchar(20) NOT NULL DEFAULT 'pending',
  `created_at` datetime NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

--
-- Dumping data for table `request_history`
--

INSERT INTO `request_history` (`hist_id`, `req_id`, `user_id`, `user_name`, `user_phone`, `device_type`, `qty`, `location`, `start_time`, `duration_value`, `duration_unit`, `note`, `status`, `created_at`) VALUES
(2, 15, 45, 'ซูฟี มะดาหะ', '0123456789', 'ATS', 2, '123/1', '2026-01-26 16:02:00', 1, 'hour', 'เอาใว้ส่องคนเสือก', 'pending', '2026-01-24 10:02:33'),
(3, 17, 45, 'ซูฟี มะดาหะ', '0123456789', 'ATS', 1, '123/1', '2026-01-16 14:04:00', 1, 'day', '', 'pending', '2026-01-24 11:01:05'),
(4, 18, 45, 'ซูฟี มะดาหะ', '0123456789', 'UPS', 1, '123/1', '2026-01-03 11:28:00', 1, 'hour', 'fgnfgh', 'pending', '2026-01-24 11:28:20'),
(5, 19, 45, 'ซูฟี มะดาหะ', '0123456789', 'UPS', 1, '123/1', '2026-01-07 15:49:00', 2, 'hour', 'ขอด่วนๆ', 'pending', '2026-01-24 13:47:16'),
(6, 20, 46, 'ซูฟี มะดาหะ', '123456789', 'ATS', 1, '4/5', '2026-01-31 15:00:00', 1, 'hour', 'ขอด่วนๆ', 'pending', '2026-01-24 13:58:24'),
(7, 21, 46, 'ซูฟี มะดาหะ', '123456789', 'ATS', 1, '4/5', '2026-01-29 17:04:00', 1, 'hour', 'กหฟกฟหกฟหก', 'pending', '2026-01-24 14:01:24'),
(8, 22, 47, 'ซูฟี มะดาหะ', '12346789', 'ATS', 1, '4/5', '2026-02-04 17:20:00', 1, 'hour', 'ด่วนๆครับ', 'pending', '2026-02-15 15:18:01');

-- --------------------------------------------------------

--
-- Table structure for table `service_requests`
--

CREATE TABLE `service_requests` (
  `req_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `device_type` varchar(100) NOT NULL,
  `qty` int(11) NOT NULL DEFAULT 1,
  `location` varchar(150) NOT NULL,
  `start_time` datetime DEFAULT NULL,
  `duration_value` int(11) NOT NULL DEFAULT 1,
  `duration_unit` enum('hour','day') NOT NULL DEFAULT 'hour',
  `hours` int(11) DEFAULT NULL,
  `note` text DEFAULT NULL,
  `status` enum('pending','working','done','cancel') NOT NULL DEFAULT 'pending',
  `created_at` datetime NOT NULL DEFAULT current_timestamp(),
  `updated_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

--
-- Dumping data for table `service_requests`
--

INSERT INTO `service_requests` (`req_id`, `user_id`, `device_type`, `qty`, `location`, `start_time`, `duration_value`, `duration_unit`, `hours`, `note`, `status`, `created_at`, `updated_at`) VALUES
(1, 36, 'ATS', 1, 'fiudhxiug', NULL, 1, 'day', NULL, '', 'cancel', '2026-01-16 19:17:42', '2026-01-16 19:20:22'),
(2, 37, 'ATS', 1, '4/5', '2026-01-22 20:00:00', 1, 'day', NULL, 'หฟกฟหกฟหกฟหก', 'pending', '2026-01-16 20:24:41', '2026-01-16 20:51:57'),
(3, 37, 'ATS', 1, '4/5', '2026-01-03 22:43:00', 1, 'hour', NULL, 'ห้ดก้กด้ฟหก', 'pending', '2026-01-16 20:41:27', '2026-01-16 20:51:54'),
(4, 38, 'Generator', 1, '4/5', '2026-01-01 23:00:00', 1, 'hour', NULL, 'adssdasdsd', 'pending', '2026-01-16 21:00:05', NULL),
(5, 39, 'ATS', 1, 'sdgfdhsh', '2026-01-01 02:48:00', 1, 'hour', NULL, '', 'pending', '2026-01-16 23:48:58', NULL),
(6, 39, 'ATS', 1, 'sdgfdhsh', '2026-01-01 02:48:00', 1, 'hour', NULL, '', 'pending', '2026-01-16 23:48:58', NULL),
(7, 40, 'ATS', 1, 'iosdhgosid', '2026-01-15 14:59:00', 1, 'day', NULL, 'adasdas', 'pending', '2026-01-17 11:55:51', NULL),
(8, 40, 'ATS', 1, 'iosdhgosid', '2026-01-15 14:59:00', 1, 'day', NULL, 'adasdas', 'pending', '2026-01-17 11:55:51', NULL),
(9, 41, 'ATS', 1, '4/9', '2026-01-01 16:46:00', 2, 'day', NULL, 'ขอด่วนๆ', 'pending', '2026-01-22 14:44:55', NULL),
(10, 41, 'ATS', 1, '4/9', '2026-01-09 22:57:00', 1, 'day', NULL, '', 'pending', '2026-01-22 19:57:56', NULL),
(11, 42, 'ATS', 1, '4/5', '2026-01-16 23:52:00', 1, 'day', NULL, 'ขอด่วนๆ', 'pending', '2026-01-23 20:48:42', NULL),
(12, 43, 'UPS', 1, 'dasd', '2026-01-14 23:11:00', 1, 'day', NULL, 'asdasd', 'pending', '2026-01-23 21:11:45', NULL),
(13, 44, 'UPS', 1, 'dasdas', '2025-12-31 22:25:00', 3, 'day', NULL, 'sadasd', 'pending', '2026-01-23 21:23:20', NULL),
(14, 44, 'Generator', 1, 'dasdas', NULL, 1, 'hour', NULL, 'asdas', 'pending', '2026-01-23 21:26:20', NULL),
(19, 45, 'UPS', 1, '123/1', '2026-01-07 15:49:00', 2, 'hour', NULL, 'ขอด่วนๆ', 'cancel', '2026-01-24 13:47:16', NULL),
(21, 46, 'ATS', 1, '4/5', '2026-01-29 17:04:00', 1, 'hour', NULL, 'กหฟกฟหกฟหก', 'cancel', '2026-01-24 14:01:24', NULL),
(22, 47, 'ATS', 1, '4/5', '2026-02-04 17:20:00', 1, 'hour', NULL, 'ด่วนๆครับ', 'working', '2026-02-15 15:18:01', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `user_id` int(11) NOT NULL,
  `member_username` varchar(50) DEFAULT NULL,
  `member_password_hash` varchar(255) DEFAULT NULL,
  `user_name` varchar(100) NOT NULL,
  `user_address` text NOT NULL,
  `user_phone` varchar(20) NOT NULL,
  `report_detail` text NOT NULL,
  `service_status` varchar(20) NOT NULL DEFAULT 'none',
  `report_time` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`user_id`, `member_username`, `member_password_hash`, `user_name`, `user_address`, `user_phone`, `report_detail`, `service_status`, `report_time`) VALUES
(47, 'fee', '$2y$10$Oarj6eak6uD2SUyvACGW.OEw3EMDCm5d52fUF.imcrUnMYZ3N1Y5K', 'ซูฟี มะดาหะ', '4/5', '12346789', 'samaeke@gmail.com', 'approved', '2026-01-31 12:37:57'),
(48, 'sufee', '$2y$10$W.nrLoNn1qORL5/EkbzaQurQLBmjMl9cXR7/IyuuE5lwMGdfnUjz6', 'ฟี มะดาหะ', '4/5', '123456789', 'samaeke123@gmail.com', 'none', '2026-02-16 15:11:29');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `admins`
--
ALTER TABLE `admins`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `username` (`username`);

--
-- Indexes for table `devices`
--
ALTER TABLE `devices`
  ADD PRIMARY KEY (`device_id`);

--
-- Indexes for table `reports`
--
ALTER TABLE `reports`
  ADD PRIMARY KEY (`report_id`);

--
-- Indexes for table `request_history`
--
ALTER TABLE `request_history`
  ADD PRIMARY KEY (`hist_id`);

--
-- Indexes for table `service_requests`
--
ALTER TABLE `service_requests`
  ADD PRIMARY KEY (`req_id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`user_id`),
  ADD UNIQUE KEY `member_username` (`member_username`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `admins`
--
ALTER TABLE `admins`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- AUTO_INCREMENT for table `devices`
--
ALTER TABLE `devices`
  MODIFY `device_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `reports`
--
ALTER TABLE `reports`
  MODIFY `report_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `request_history`
--
ALTER TABLE `request_history`
  MODIFY `hist_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT for table `service_requests`
--
ALTER TABLE `service_requests`
  MODIFY `req_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=23;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `user_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=49;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
