-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Mar 01, 2025 at 05:14 AM
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
-- Database: `phpadminpanel`
--

-- --------------------------------------------------------

--
-- Table structure for table `cart`
--

CREATE TABLE `cart` (
  `id` int(11) NOT NULL,
  `username` varchar(255) DEFAULT NULL,
  `course_id` int(11) DEFAULT NULL,
  `quantity` int(11) DEFAULT 1,
  `total_price` decimal(10,2) DEFAULT NULL,
  `payment_status` bigint(20) DEFAULT 0,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `cart`
--

INSERT INTO `cart` (`id`, `username`, `course_id`, `quantity`, `total_price`, `payment_status`, `created_at`, `updated_at`) VALUES
(1, 'b', 3, 1, 100.00, 1, '2025-02-27 10:58:15', '2025-02-27 10:58:47'),
(2, 'neha', 3, 1, 100.00, 1, '2025-02-27 11:08:55', '2025-02-27 11:48:19'),
(4, 'aa', 3, 1, 100.00, 1, '2025-02-27 11:51:55', '2025-02-27 11:52:00'),
(5, 'aa', 4, 1, 69.99, 1, '2025-02-28 05:39:01', '2025-02-28 05:42:07'),
(14, 'aa', 5, 1, 29.99, 1, '2025-02-28 07:30:53', '2025-02-28 09:45:26'),
(15, 'aa', 11, 1, 34.09, 1, '2025-02-28 09:50:11', '2025-02-28 10:30:30'),
(16, 'aa', 12, 1, 100.00, 1, '2025-02-28 10:30:49', '2025-02-28 11:09:57'),
(17, 'aa', 7, 1, 79.99, 1, '2025-02-28 11:10:41', '2025-02-28 11:13:55'),
(18, 'aa', 10, 1, 24.99, 1, '2025-02-28 11:14:12', '2025-02-28 11:14:40'),
(19, 'aa', 8, 1, 44.99, 1, '2025-02-28 11:20:04', '2025-02-28 11:22:17');

-- --------------------------------------------------------

--
-- Table structure for table `courses`
--

CREATE TABLE `courses` (
  `id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `description` text NOT NULL,
  `price` decimal(10,2) NOT NULL,
  `image` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `courses`
--

INSERT INTO `courses` (`id`, `name`, `description`, `price`, `image`) VALUES
(3, 'Course 3: Math', 'This is Math course description.', 100.00, 'https://blog.careerlauncher.com/wp-content/uploads/2021/06/maths-chalkboard_23-2148178220.jpg'),
(4, 'Course 4: Science course', 'This is Science course description.', 69.99, 'https://www.eurokidsindia.com/blog/wp-content/uploads/2024/02/science-exhibitions-870x570.jpg'),
(5, 'Course 5: History', 'This is History course description.', 29.99, 'https://imgs.search.brave.com/CqG7uTcoD8cfCMQJkXQI44VskeOkL6d5iAosi7YnTHE/rs:fit:500:0:0:0/g:ce/aHR0cHM6Ly9tLm1l/ZGlhLWFtYXpvbi5j/b20vaW1hZ2VzL0kv/NTFMTDArcGxWMFMu/anBn'),
(6, 'Course 6: Geography', 'This is Geography course description.', 34.99, 'https://study.com/cimages/videopreview/p3c2j8y73a.jpg'),
(7, 'Course 7: Computer Science', 'This is Computer Science course description.', 79.99, 'https://thumbs.dreamstime.com/b/computer-science-word-cloud-concept-grey-background-90729606.jpg'),
(8, 'Course 8: Art', 'This is Art course description.', 44.99, 'https://t3.ftcdn.net/jpg/02/73/22/74/360_F_273227473_N0WRQuX3uZCJJxlHKYZF44uaJAkh2xLG.jpg'),
(9, 'Course 9: Music', 'This is Music course description.', 54.99, 'https://media.istockphoto.com/id/1431567498/vector/vector-illustration-of-musical-notes-on-white-background.jpg?s=612x612&w=0&k=20&c=E4Qx8E7OJm-itMPylpaZhNIU8mkJQt5XctWlKLLa1I8='),
(10, 'Course 10: Physical Education', 'This is Physical Education course description.', 24.99, 'https://img.freepik.com/free-vector/hand-drawn-physical-education-day-lettering_23-2149034048.jpg?semt=ais_hybrid'),
(11, 'Course 1: English', 'Eng Desc', 34.09, 'https://imgv3.fotor.com/images/blog-richtext-image/what-is-png-file-cover-with-blue-background.jpg'),
(12, 'Commerce', 'This is a commerce course', 100.00, 'https://cdn1.byjus.com/wp-content/uploads/2022/09/Commerce.webp');

-- --------------------------------------------------------

--
-- Table structure for table `fees`
--

CREATE TABLE `fees` (
  `id` int(11) NOT NULL,
  `username` varchar(55) NOT NULL,
  `amount` double(8,2) NOT NULL,
  `description` text NOT NULL,
  `status` bigint(20) NOT NULL,
  `date` datetime NOT NULL DEFAULT current_timestamp(),
  `lastdate` datetime NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `students`
--

CREATE TABLE `students` (
  `id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `username` varchar(50) NOT NULL,
  `password` varchar(255) NOT NULL,
  `age` int(11) NOT NULL,
  `phone` varchar(15) NOT NULL,
  `referral_name` varchar(255) DEFAULT NULL,
  `referral_phone` varchar(15) DEFAULT NULL,
  `role_as` tinyint(1) NOT NULL DEFAULT 0 COMMENT '0 = Student, 1 = Admin',
  `balance` decimal(10,2) DEFAULT 0.00,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `status` varchar(10) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `students`
--

INSERT INTO `students` (`id`, `name`, `email`, `username`, `password`, `age`, `phone`, `referral_name`, `referral_phone`, `role_as`, `balance`, `created_at`, `updated_at`, `status`) VALUES
(1, 'Anuj Kumar Gupta', 'anujkumargupta0903@gmail.com', 'anuj', '$2y$10$RmJSbCPpY2zU4vCFL1K2ge/9yS2.9PdxwLD1FiYQ6nNWyQqEiQwvG', 21, '9953230726', '0', '0', 1, 108.12, '2025-01-22 08:55:41', '2025-03-01 04:01:01', 'Active'),
(2, 'Neha', 'neha@gmail.com', 'neha', '$2y$10$RXvjIzvgkTIaVOU1lR88auJOvVFwpVWd4fpLBc9k5rt.i5IgrEF3e', 21, '9090909090', 'Anuj Kumar Gupta', '9953230726', 0, 1867.00, '2025-01-22 09:17:00', '2025-03-01 04:01:01', 'Active'),
(4, 'Dummy Singh', 'dummy@mailinator.com', 'dummy', '$2y$10$7AyN/smh4o35y16xFpSij.er0rCk192sA4kVUUD0cgyD.MYyy6euC', 21, '9898989898', 'Neha', '9090909090', 0, 1000.00, '2025-01-23 04:53:32', '2025-02-13 10:53:50', 'Inactive'),
(6, 'd', 'd@gmail.com', 'd', '$2y$10$HSXXDNlj/bOMPkIXpCm36./zkh2HfISSIBmlGRsyzQTR/e2xfkzAe', 31, '1111111111', 'Dummy Singh', '9898989898', 0, 0.00, '2025-01-23 06:57:23', '2025-02-22 06:08:45', 'Inactive'),
(7, 'a', 'a@gmail.com', 'a', '$2y$10$K5GKEnjMXaSIaRGi82SrgebqhSOzuPUQbOnZ5C5Cax1c0uY3TJ2AG', 31, '2222222222', 'Neha', '9090909090', 0, 1002.00, '2025-01-23 07:04:38', '2025-02-13 10:56:08', 'Inactive'),
(8, 'q', 'q@gmail.com', 'q', '$2y$10$uyWBCE2z2zMr80pMQU8SJelIawGsHvEvQtTJ26xGswB4WtPfGEeaG', 21, '8998989988', 'Neha', '9090909090', 0, 0.00, '2025-01-23 08:07:18', '2025-02-22 06:08:49', 'Active'),
(9, 'Putra', 'p@gmail.com', 'p', '$2y$10$8Xf0CBjVHYwOBW6lPb3ZwuFcY8UGxudCsK/fR7jRw6idodSHNC9P.', 21, '9898989877', 'Anuj Kumar Gupta', '9953230726', 0, 289.99, '2025-01-27 12:36:33', '2025-02-28 11:22:17', 'Inactive'),
(10, 'b', 'b@gmail.com', 'b', '$2y$10$jWZMZixpel1pC3vybn/dq.W/DYvfXXYiV2ltCUE6pOpfn8DvSZy5i', 32, '9999999999', 'Putra', '9898989877', 0, 1994.41, '2025-02-13 11:19:42', '2025-02-28 11:22:17', 'Inactive'),
(11, 'c', 'c@gmail.com', 'c', '$2y$10$fl1O4j.GnDDaB3vZJKq4n.hP7zs2RA3AiwQ/xfei7OakfdlN0iW16', 21, '8888888888', 'b', '9999999999', 0, 374.69, '2025-02-13 11:35:23', '2025-02-28 11:22:17', 'Inactive'),
(13, 'f', 'f@gmail.com', 'f', '$2y$10$rU7BrSAJr4GiygqpOeVrFO8DNSkgVtsgkP2EM4h.D7Z9cjxaIqFG2', 21, '6666666666', 'Neha', '9090909090', 0, 149051.05, '2025-02-13 12:21:49', '2025-02-25 09:22:27', 'Active'),
(14, 'g', 'g@gmail.com', 'g', '$2y$10$vwHZZmeSxmA5L1B7nfDwweLsX6nCUsO1HxUsriWALPS1NADRoQVTq', 21, '5555555555', 'f', '6666666666', 0, 99897.01, '2025-02-13 12:24:49', '2025-02-25 09:22:27', 'Active'),
(15, 'h', 'h@gmail.com', 'h', '$2y$10$c4jqJHAmKNKKULhxcbM21e61IpamyFnxkeiKnxNg2J..CufGk6I8O', 31, '4444444444', 'g', '5555555555', 0, 99977.00, '2025-02-13 12:33:45', '2025-02-25 09:22:27', 'Active'),
(16, 'i', 'i@gmail.com', 'i', '$2y$10$6Y3GPp8LbK3x3OwVX4NAwuBheEvzpx2WWyHq..LQ9Be/M0aqtbr6i', 21, '1222222222', 'h', '4444444444', 0, 999600.00, '2025-02-13 12:41:52', '2025-02-25 09:20:59', 'Active'),
(17, 'j', 'j@gmail.com', 'j', '$2y$10$nBh1Fu8vaU2Yy6roo4r6F.Z.KMdkT/2sPHr9hZCbtExO22VrMbg5i', 21, '1111111111', 'i', '1222222222', 0, 999790.00, '2025-02-13 12:46:46', '2025-02-24 10:23:14', 'Active'),
(18, 'z', 'z@gmail.com', 'z', '$2y$10$wnXyiJv5SgqxIa4TrFT2yeP33Y3NhlIfWjw/nmZTHJGI1eyaF3Nae', 21, '1234567899', 'Anuj Kumar Gupta', '9953230726', 0, 0.00, '2025-02-14 04:52:52', '2025-02-24 06:31:03', 'Inactive'),
(19, 'x', 'x@gmail.com', 'x', '$2y$10$pLaAZc5SxJHrBEqXhbiF9O/dAqLoNITXyBqmNzA7tC/cKirGYZIWS', 21, '0987654321', 'z', '1234567899', 0, 0.00, '2025-02-14 05:00:05', '2025-02-14 05:04:33', 'Inactive'),
(20, 'y', 'y@gmail.com', 'y', '$2y$10$oCMigR0CCHj2mAPSKvL56eo0HOZ5mVggmP28IgA6poK0SdnpAWxHO', 21, '1231231234', 'x', '0987654321', 0, 100000.00, '2025-02-14 05:05:18', '2025-02-21 15:26:24', 'Inactive'),
(21, 'aa', 'aaa@gmail.com', 'aa', '$2y$10$tgFMxWZN8w3yOz1tsjWJBukV4FFdBMAVnMtEXl4dZ491lm/rPmwH2', 21, '1234512345', 'c', '8888888888', 0, 100023.15, '2025-02-14 06:16:03', '2025-02-28 11:22:17', 'Active');

-- --------------------------------------------------------

--
-- Table structure for table `wallet_history`
--

CREATE TABLE `wallet_history` (
  `id` int(11) NOT NULL,
  `username` varchar(30) NOT NULL,
  `amount` decimal(8,2) NOT NULL DEFAULT 0.00,
  `description` varchar(100) NOT NULL,
  `status` bigint(20) NOT NULL,
  `date` datetime NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `wallet_history`
--

INSERT INTO `wallet_history` (`id`, `username`, `amount`, `description`, `status`, `date`) VALUES
(1, 'anuj', 100.00, 'Library Fees', 1, '2025-01-22 16:58:00'),
(2, 'neha', 20.00, 'Library Books', 1, '2025-01-23 11:24:25'),
(3, 'neha', 20.00, 'Scholarship Price', 1, '2025-01-23 11:28:32'),
(4, 'anuj', 0.22, 'College fees', 0, '2025-01-23 11:38:43'),
(5, 'anuj', 50.00, 'Salary', 1, '2025-01-23 11:48:25'),
(6, 'anuj', 50.99, 'Loan', 0, '2025-01-23 11:49:45'),
(7, 'anuj', 2.00, 'salary', 1, '2025-01-23 12:11:23'),
(8, 'a', 2.00, 'Award', 1, '2025-01-29 15:42:07'),
(9, 'anuj', 9.00, 'loan', 1, '2025-02-07 18:01:26'),
(10, 'neha', 500.00, 'Default Amount', 1, '2025-02-10 17:17:57'),
(11, 'anuj', 1000.00, 'Default', 1, '2025-02-10 17:18:24'),
(12, 'dummy', 1000.00, 'Default', 1, '2025-02-10 17:18:51'),
(13, 'd', 1000.00, 'Default', 1, '2025-02-10 17:19:08'),
(14, 'a', 1000.00, 'Default', 1, '2025-02-10 17:19:33'),
(15, 'q', 1000.00, 'Default', 1, '2025-02-10 17:19:55'),
(16, 'p', 1000.00, 'Default', 1, '2025-02-10 17:20:16'),
(17, 'p', 21.00, 'Added Successfully.', 1, '2025-02-11 13:56:57'),
(18, 'c', 10.00, 'Level 1 Income', 1, '2025-02-14 12:43:33'),
(19, 'b', 5.00, 'Level 2 Income', 1, '2025-02-14 12:43:33'),
(20, 'p', 4.00, 'Level 3 Income', 1, '2025-02-14 12:43:33'),
(21, 'anuj', 3.00, 'Level 4 Income', 1, '2025-02-14 12:43:33'),
(22, 'c', 10.00, 'Level 1 Income', 1, '2025-02-14 12:47:40'),
(23, 'b', 5.00, 'Level 2 Income', 1, '2025-02-14 12:47:41'),
(24, 'p', 4.00, 'Level 3 Income', 1, '2025-02-14 12:47:41'),
(25, 'anuj', 3.00, 'Level 4 Income', 1, '2025-02-14 12:47:41'),
(26, 'c', 10.00, 'Level 1 Income', 1, '2025-02-14 12:47:49'),
(27, 'b', 5.00, 'Level 2 Income', 1, '2025-02-14 12:47:49'),
(28, 'p', 4.00, 'Level 3 Income', 1, '2025-02-14 12:47:49'),
(29, 'anuj', 3.00, 'Level 4 Income', 1, '2025-02-14 12:47:49'),
(30, 'c', 10.00, 'Level 1 Income', 1, '2025-02-14 12:50:40'),
(31, 'b', 5.00, 'Level 2 Income', 1, '2025-02-14 12:50:40'),
(32, 'p', 4.00, 'Level 3 Income', 1, '2025-02-14 12:50:40'),
(33, 'anuj', 3.00, 'Level 4 Income', 1, '2025-02-14 12:50:40'),
(34, 'c', 10.00, 'Level 1 Income', 1, '2025-02-14 12:52:48'),
(35, 'b', 5.00, 'Level 2 Income', 1, '2025-02-14 12:52:48'),
(36, 'p', 4.00, 'Level 3 Income', 1, '2025-02-14 12:52:48'),
(37, 'anuj', 3.00, 'Level 4 Income', 1, '2025-02-14 12:52:48'),
(38, 'c', 10.00, 'Level 1 Income', 1, '2025-02-14 12:54:57'),
(39, 'b', 5.00, 'Level 2 Income', 1, '2025-02-14 12:54:57'),
(40, 'p', 4.00, 'Level 3 Income', 1, '2025-02-14 12:54:57'),
(41, 'anuj', 3.00, 'Level 4 Income', 1, '2025-02-14 12:54:57'),
(42, 'c', 10.00, 'Level 1 Income', 1, '2025-02-14 12:56:15'),
(43, 'b', 5.00, 'Level 2 Income', 1, '2025-02-14 12:56:15'),
(44, 'p', 4.00, 'Level 3 Income', 1, '2025-02-14 12:56:15'),
(45, 'anuj', 3.00, 'Level 4 Income', 1, '2025-02-14 12:56:15'),
(46, 'c', 10.00, 'Level 1 Income', 1, '2025-02-14 12:56:15'),
(47, 'b', 5.00, 'Level 2 Income', 1, '2025-02-14 12:56:15'),
(48, 'p', 4.00, 'Level 3 Income', 1, '2025-02-14 12:56:15'),
(49, 'anuj', 3.00, 'Level 4 Income', 1, '2025-02-14 12:56:15'),
(50, 'c', 10.00, 'Level 1 Income', 1, '2025-02-14 12:57:28'),
(51, 'b', 5.00, 'Level 2 Income', 1, '2025-02-14 12:57:28'),
(52, 'p', 4.00, 'Level 3 Income', 1, '2025-02-14 12:57:28'),
(53, 'anuj', 3.00, 'Level 4 Income', 1, '2025-02-14 12:57:28'),
(54, 'c', 15.00, 'Level 1 Income', 1, '2025-02-14 12:59:49'),
(55, 'b', 10.00, 'Level 2 Income', 1, '2025-02-14 12:59:49'),
(56, 'p', 5.00, 'Level 3 Income', 1, '2025-02-14 12:59:49'),
(57, 'anuj', 4.00, 'Level 4 Income', 1, '2025-02-14 12:59:49'),
(58, 'c', 15.00, 'Level 1 Income', 1, '2025-02-14 13:01:55'),
(59, 'b', 10.00, 'Level 2 Income', 1, '2025-02-14 13:01:55'),
(60, 'p', 5.00, 'Level 3 Income', 1, '2025-02-14 13:01:55'),
(61, 'anuj', 4.00, 'Level 4 Income', 1, '2025-02-14 13:01:55'),
(62, 'c', 15.00, 'Level 1 Income', 1, '2025-02-14 13:02:34'),
(63, 'b', 10.00, 'Level 2 Income', 1, '2025-02-14 13:02:34'),
(64, 'p', 5.00, 'Level 3 Income', 1, '2025-02-14 13:02:34'),
(65, 'anuj', 4.00, 'Level 4 Income', 1, '2025-02-14 13:02:34'),
(66, 'c', 15.00, 'Level 1 Income', 1, '2025-02-14 14:01:35'),
(67, 'b', 10.00, 'Level 2 Income', 1, '2025-02-14 14:01:35'),
(68, 'p', 5.00, 'Level 3 Income', 1, '2025-02-14 14:01:35'),
(69, 'anuj', 4.00, 'Level 4 Income', 1, '2025-02-14 14:01:35'),
(70, 'c', 15.00, 'Level 1 Income', 1, '2025-02-14 14:01:38'),
(71, 'b', 10.00, 'Level 2 Income', 1, '2025-02-14 14:01:38'),
(72, 'p', 5.00, 'Level 3 Income', 1, '2025-02-14 14:01:38'),
(73, 'anuj', 4.00, 'Level 4 Income', 1, '2025-02-14 14:01:38'),
(74, 'c', 15.00, 'Level 1 Income', 1, '2025-02-14 14:01:45'),
(75, 'b', 10.00, 'Level 2 Income', 1, '2025-02-14 14:01:45'),
(76, 'p', 5.00, 'Level 3 Income', 1, '2025-02-14 14:01:45'),
(77, 'anuj', 4.00, 'Level 4 Income', 1, '2025-02-14 14:01:45'),
(78, 'c', 15.00, 'Level 1 Income', 1, '2025-02-14 14:07:14'),
(79, 'b', 10.00, 'Level 2 Income', 1, '2025-02-14 14:07:14'),
(80, 'p', 5.00, 'Level 3 Income', 1, '2025-02-14 14:07:14'),
(81, 'anuj', 4.00, 'Level 4 Income', 1, '2025-02-14 14:07:14'),
(82, 'c', 15.00, 'Level 1 Income', 1, '2025-02-14 14:07:16'),
(83, 'b', 10.00, 'Level 2 Income', 1, '2025-02-14 14:07:16'),
(84, 'p', 5.00, 'Level 3 Income', 1, '2025-02-14 14:07:16'),
(85, 'anuj', 4.00, 'Level 4 Income', 1, '2025-02-14 14:07:16'),
(86, 'c', 15.00, 'Level 1 Income', 1, '2025-02-14 14:07:24'),
(87, 'b', 10.00, 'Level 2 Income', 1, '2025-02-14 14:07:24'),
(88, 'p', 5.00, 'Level 3 Income', 1, '2025-02-14 14:07:24'),
(89, 'anuj', 4.00, 'Level 4 Income', 1, '2025-02-14 14:07:24'),
(90, 'c', 15.00, 'Level 1 Income', 1, '2025-02-14 14:07:49'),
(91, 'b', 10.00, 'Level 2 Income', 1, '2025-02-14 14:07:49'),
(92, 'p', 5.00, 'Level 3 Income', 1, '2025-02-14 14:07:49'),
(93, 'anuj', 4.00, 'Level 4 Income', 1, '2025-02-14 14:07:49'),
(94, 'c', 15.00, 'Level 1 Income', 1, '2025-02-14 14:08:10'),
(95, 'b', 10.00, 'Level 2 Income', 1, '2025-02-14 14:08:10'),
(96, 'p', 5.00, 'Level 3 Income', 1, '2025-02-14 14:08:10'),
(97, 'anuj', 4.00, 'Level 4 Income', 1, '2025-02-14 14:08:10'),
(98, 'c', 15.00, 'Level 1 Income', 1, '2025-02-14 14:20:25'),
(99, 'b', 10.00, 'Level 2 Income', 1, '2025-02-14 14:20:25'),
(100, 'p', 5.00, 'Level 3 Income', 1, '2025-02-14 14:20:25'),
(101, 'anuj', 4.00, 'Level 4 Income', 1, '2025-02-14 14:20:25'),
(102, 'c', 15.00, 'Level 1 Income', 1, '2025-02-14 14:20:26'),
(103, 'b', 10.00, 'Level 2 Income', 1, '2025-02-14 14:20:26'),
(104, 'p', 5.00, 'Level 3 Income', 1, '2025-02-14 14:20:26'),
(105, 'anuj', 4.00, 'Level 4 Income', 1, '2025-02-14 14:20:26'),
(106, 'c', 15.00, 'Level 1 Income', 1, '2025-02-14 14:20:42'),
(107, 'b', 10.00, 'Level 2 Income', 1, '2025-02-14 14:20:42'),
(108, 'p', 5.00, 'Level 3 Income', 1, '2025-02-14 14:20:42'),
(109, 'anuj', 4.00, 'Level 4 Income', 1, '2025-02-14 14:20:42'),
(110, 'c', 15.00, 'Level 1 Income', 1, '2025-02-14 14:20:45'),
(111, 'b', 10.00, 'Level 2 Income', 1, '2025-02-14 14:20:45'),
(112, 'p', 5.00, 'Level 3 Income', 1, '2025-02-14 14:20:45'),
(113, 'anuj', 4.00, 'Level 4 Income', 1, '2025-02-14 14:20:45'),
(114, 'c', 15.00, 'Level 1 Income', 1, '2025-02-14 14:20:52'),
(115, 'b', 10.00, 'Level 2 Income', 1, '2025-02-14 14:20:52'),
(116, 'p', 5.00, 'Level 3 Income', 1, '2025-02-14 14:20:52'),
(117, 'anuj', 4.00, 'Level 4 Income', 1, '2025-02-14 14:20:52'),
(118, 'c', 15.00, 'Level 1 Income', 1, '2025-02-14 14:27:33'),
(119, 'b', 10.00, 'Level 2 Income', 1, '2025-02-14 14:27:33'),
(120, 'p', 5.00, 'Level 3 Income', 1, '2025-02-14 14:27:33'),
(121, 'anuj', 4.00, 'Level 4 Income', 1, '2025-02-14 14:27:33'),
(122, 'c', 15.00, 'Level 1 Income', 1, '2025-02-14 14:58:23'),
(123, 'b', 10.00, 'Level 2 Income', 1, '2025-02-14 14:58:23'),
(124, 'p', 5.00, 'Level 3 Income', 1, '2025-02-14 14:58:23'),
(125, 'anuj', 4.00, 'Level 4 Income', 1, '2025-02-14 14:58:23'),
(126, 'c', 15.00, 'Level 1 Income', 1, '2025-02-14 14:59:49'),
(127, 'b', 10.00, 'Level 2 Income', 1, '2025-02-14 14:59:49'),
(128, 'p', 5.00, 'Level 3 Income', 1, '2025-02-14 14:59:49'),
(129, 'anuj', 4.00, 'Level 4 Income', 1, '2025-02-14 14:59:49'),
(130, 'c', 15.00, 'Level 1 Income', 1, '2025-02-14 14:59:53'),
(131, 'b', 10.00, 'Level 2 Income', 1, '2025-02-14 14:59:53'),
(132, 'p', 5.00, 'Level 3 Income', 1, '2025-02-14 14:59:53'),
(133, 'anuj', 4.00, 'Level 4 Income', 1, '2025-02-14 14:59:53'),
(134, 'c', 15.00, 'Level 1 Income', 1, '2025-02-14 15:00:00'),
(135, 'b', 10.00, 'Level 2 Income', 1, '2025-02-14 15:00:00'),
(136, 'p', 5.00, 'Level 3 Income', 1, '2025-02-14 15:00:00'),
(137, 'anuj', 4.00, 'Level 4 Income', 1, '2025-02-14 15:00:00'),
(138, 'c', 15.00, 'Level 1 Income', 1, '2025-02-14 15:01:05'),
(139, 'b', 10.00, 'Level 2 Income', 1, '2025-02-14 15:01:05'),
(140, 'p', 5.00, 'Level 3 Income', 1, '2025-02-14 15:01:05'),
(141, 'anuj', 4.00, 'Level 4 Income', 1, '2025-02-14 15:01:05'),
(142, 'c', 15.00, 'Level 1 Income', 1, '2025-02-14 15:01:08'),
(143, 'b', 10.00, 'Level 2 Income', 1, '2025-02-14 15:01:08'),
(144, 'p', 5.00, 'Level 3 Income', 1, '2025-02-14 15:01:08'),
(145, 'anuj', 4.00, 'Level 4 Income', 1, '2025-02-14 15:01:09'),
(146, 'c', 15.00, 'Level 1 Income', 1, '2025-02-14 15:01:32'),
(147, 'b', 10.00, 'Level 2 Income', 1, '2025-02-14 15:01:32'),
(148, 'p', 5.00, 'Level 3 Income', 1, '2025-02-14 15:01:32'),
(149, 'anuj', 4.00, 'Level 4 Income', 1, '2025-02-14 15:01:32'),
(150, 'c', 15.00, 'Level 1 Income', 1, '2025-02-14 15:01:38'),
(151, 'b', 10.00, 'Level 2 Income', 1, '2025-02-14 15:01:38'),
(152, 'p', 5.00, 'Level 3 Income', 1, '2025-02-14 15:01:38'),
(153, 'anuj', 4.00, 'Level 4 Income', 1, '2025-02-14 15:01:38'),
(154, 'c', 15.00, 'Level 1 Income', 1, '2025-02-14 15:01:43'),
(155, 'b', 10.00, 'Level 2 Income', 1, '2025-02-14 15:01:43'),
(156, 'p', 5.00, 'Level 3 Income', 1, '2025-02-14 15:01:43'),
(157, 'anuj', 4.00, 'Level 4 Income', 1, '2025-02-14 15:01:43'),
(158, 'c', 15.00, 'Level 1 Income', 1, '2025-02-14 15:04:37'),
(159, 'b', 10.00, 'Level 2 Income', 1, '2025-02-14 15:04:37'),
(160, 'p', 5.00, 'Level 3 Income', 1, '2025-02-14 15:04:37'),
(161, 'anuj', 4.00, 'Level 4 Income', 1, '2025-02-14 15:04:37'),
(162, 'c', 15.00, 'Level 1 Income', 1, '2025-02-14 15:05:07'),
(163, 'b', 10.00, 'Level 2 Income', 1, '2025-02-14 15:05:07'),
(164, 'p', 5.00, 'Level 3 Income', 1, '2025-02-14 15:05:07'),
(165, 'anuj', 4.00, 'Level 4 Income', 1, '2025-02-14 15:05:07'),
(166, 'c', 15.00, 'Level 1 Income', 1, '2025-02-14 15:05:12'),
(167, 'b', 10.00, 'Level 2 Income', 1, '2025-02-14 15:05:12'),
(168, 'p', 5.00, 'Level 3 Income', 1, '2025-02-14 15:05:12'),
(169, 'anuj', 4.00, 'Level 4 Income', 1, '2025-02-14 15:05:12'),
(170, 'c', 15.00, 'Level 1 Income', 1, '2025-02-14 15:08:38'),
(171, 'b', 10.00, 'Level 2 Income', 1, '2025-02-14 15:08:38'),
(172, 'p', 5.00, 'Level 3 Income', 1, '2025-02-14 15:08:38'),
(173, 'anuj', 4.00, 'Level 4 Income', 1, '2025-02-14 15:08:38'),
(174, 'c', 15.00, 'Level 1 Income', 1, '2025-02-14 15:10:19'),
(175, 'b', 10.00, 'Level 2 Income', 1, '2025-02-14 15:10:20'),
(176, 'p', 5.00, 'Level 3 Income', 1, '2025-02-14 15:10:20'),
(177, 'anuj', 4.00, 'Level 4 Income', 1, '2025-02-14 15:10:20'),
(178, 'c', 15.00, 'Level 1 Income', 1, '2025-02-14 15:12:17'),
(179, 'b', 10.00, 'Level 2 Income', 1, '2025-02-14 15:12:17'),
(180, 'p', 5.00, 'Level 3 Income', 1, '2025-02-14 15:12:17'),
(181, 'anuj', 4.00, 'Level 4 Income', 1, '2025-02-14 15:12:17'),
(182, 'c', 15.00, 'Level 1 Income', 1, '2025-02-14 15:13:54'),
(183, 'b', 10.00, 'Level 2 Income', 1, '2025-02-14 15:13:54'),
(184, 'p', 5.00, 'Level 3 Income', 1, '2025-02-14 15:13:54'),
(185, 'anuj', 4.00, 'Level 4 Income', 1, '2025-02-14 15:13:54'),
(186, 'c', 15.00, 'Level 1 Income', 1, '2025-02-14 15:17:07'),
(187, 'b', 10.00, 'Level 2 Income', 1, '2025-02-14 15:17:07'),
(188, 'p', 5.00, 'Level 3 Income', 1, '2025-02-14 15:17:07'),
(189, 'anuj', 4.00, 'Level 4 Income', 1, '2025-02-14 15:17:07'),
(190, 'c', 15.00, 'Level 1 Income', 1, '2025-02-14 15:20:10'),
(191, 'b', 10.00, 'Level 2 Income', 1, '2025-02-14 15:20:10'),
(192, 'p', 5.00, 'Level 3 Income', 1, '2025-02-14 15:20:10'),
(193, 'anuj', 4.00, 'Level 4 Income', 1, '2025-02-14 15:20:10'),
(194, 'anuj', 1000.00, 'Add', 1, '2025-02-14 16:52:29'),
(195, 'd', 1.00, 'Added sucessfully', 1, '2025-02-15 14:20:50'),
(196, 'q', 1.00, 'Added', 1, '2025-02-15 14:21:34'),
(197, 'neha', 90.00, 'Payment for courses', 1, '2025-02-21 17:07:03'),
(198, 'neha', 0.00, 'Payment for courses', 1, '2025-02-21 17:08:20'),
(199, 'neha', 214.97, 'Payment for courses', 1, '2025-02-21 17:58:41'),
(200, 'neha', 69.99, 'Payment for courses', 1, '2025-02-21 20:51:47'),
(201, 'neha', 69.99, 'Payment for courses', 1, '2025-02-21 20:54:55'),
(202, 'f', 15.00, 'Level 1 Income', 1, '2025-02-21 20:58:18'),
(203, 'g', 69.99, 'Payment for courses', 1, '2025-02-21 20:58:43'),
(205, 'anuj', 159.99, 'Admin earnings from course purchase', 1, '2025-02-21 22:06:57'),
(206, 'f', 159.99, 'Payment for courses', 1, '2025-02-21 22:06:57'),
(208, 'f', 0.00, 'Payment for courses', 1, '2025-02-21 22:17:16'),
(209, 'anuj', 44.09, 'Admin earnings from course purchase', 1, '2025-02-21 22:25:21'),
(210, 'f', 69.99, 'Payment for courses', 1, '2025-02-21 22:25:21'),
(211, 'anuj', 56.70, 'Admin earnings from course purchase', 1, '2025-02-21 22:28:36'),
(212, 'f', 90.00, 'Payment for courses', 1, '2025-02-21 22:28:36'),
(213, 'f', 90.00, 'Payment for courses', 1, '2025-02-21 22:28:36'),
(214, 'anuj', 56.70, 'Admin earnings from course purchase', 1, '2025-02-21 22:34:56'),
(215, 'anuj', 0.00, 'Admin earnings from course purchase', 1, '2025-02-21 22:37:48'),
(216, 'anuj', 56.70, 'Admin earnings from course purchase', 1, '2025-02-21 22:45:07'),
(218, 'anuj', 56.70, 'Admin earnings from course purchase', 1, '2025-02-21 22:51:32'),
(219, 'anuj', 100.79, 'Admin earnings from course purchase', 1, '2025-02-22 11:39:33'),
(220, 'c', 19.20, 'Referral commission', 1, '2025-02-22 11:39:33'),
(221, 'b', 16.00, 'Referral commission', 1, '2025-02-22 11:39:33'),
(222, 'p', 11.20, 'Referral commission', 1, '2025-02-22 11:39:33'),
(223, 'anuj', 8.00, 'Referral commission', 1, '2025-02-22 11:39:33'),
(224, 'anuj', 56.70, 'Admin earnings from course purchase', 1, '2025-02-24 10:21:07'),
(225, 'c', 10.80, 'Referral commission', 1, '2025-02-24 10:21:07'),
(226, 'b', 9.00, 'Referral commission', 1, '2025-02-24 10:21:07'),
(227, 'p', 6.30, 'Referral commission', 1, '2025-02-24 10:21:07'),
(228, 'anuj', 4.50, 'Referral commission', 1, '2025-02-24 10:21:07'),
(229, 'anuj', 63.00, 'Admin earnings from course purchase', 1, '2025-02-24 10:28:01'),
(230, 'c', 12.00, 'Referral commission', 1, '2025-02-24 10:28:01'),
(231, 'b', 10.00, 'Referral commission', 1, '2025-02-24 10:28:01'),
(232, 'p', 7.00, 'Referral commission', 1, '2025-02-24 10:28:01'),
(233, 'anuj', 5.00, 'Referral commission', 1, '2025-02-24 10:28:01'),
(234, 'anuj', 63.00, 'Admin earnings from course purchase', 1, '2025-02-24 10:31:56'),
(235, 'c', 12.00, 'Referral commission', 1, '2025-02-24 10:31:56'),
(236, 'b', 10.00, 'Referral commission', 1, '2025-02-24 10:31:56'),
(237, 'p', 7.00, 'Referral commission', 1, '2025-02-24 10:31:56'),
(238, 'anuj', 5.00, 'Referral commission', 1, '2025-02-24 10:31:56'),
(239, 'i', 15.00, 'Level 1 Income', 1, '2025-02-24 15:52:34'),
(240, 'h', 10.00, 'Level 2 Income', 1, '2025-02-24 15:52:34'),
(241, 'g', 5.00, 'Level 3 Income', 1, '2025-02-24 15:52:34'),
(242, 'f', 4.00, 'Level 4 Income', 1, '2025-02-24 15:52:34'),
(243, 'neha', 3.00, 'Level 5 Income', 1, '2025-02-24 15:52:34'),
(244, 'anuj', 63.00, 'Admin earnings from course purchase', 1, '2025-02-24 15:53:14'),
(245, 'i', 12.00, 'Referral commission', 1, '2025-02-24 15:53:14'),
(246, 'h', 10.00, 'Referral commission', 1, '2025-02-24 15:53:14'),
(247, 'g', 7.00, 'Referral commission', 1, '2025-02-24 15:53:14'),
(248, 'f', 5.00, 'Referral commission', 1, '2025-02-24 15:53:14'),
(249, 'neha', 3.00, 'Referral commission', 1, '2025-02-24 15:53:14'),
(250, 'h', 15.00, 'Level 1 Income', 1, '2025-02-25 10:27:49'),
(251, 'g', 10.00, 'Level 2 Income', 1, '2025-02-25 10:27:49'),
(252, 'f', 5.00, 'Level 3 Income', 1, '2025-02-25 10:27:49'),
(253, 'neha', 4.00, 'Level 4 Income', 1, '2025-02-25 10:27:49'),
(254, 'anuj', 3.00, 'Level 5 Income', 1, '2025-02-25 10:27:49'),
(255, 'anuj', 0.00, 'Admin earnings from course purchase', 1, '2025-02-25 10:50:52'),
(256, 'h', 0.00, 'Referral commission', 1, '2025-02-25 10:50:52'),
(257, 'g', 0.00, 'Referral commission', 1, '2025-02-25 10:50:52'),
(258, 'f', 0.00, 'Referral commission', 1, '2025-02-25 10:50:52'),
(259, 'neha', 0.00, 'Referral commission', 1, '2025-02-25 10:50:52'),
(260, 'anuj', 0.00, 'Referral commission', 1, '2025-02-25 10:50:52'),
(261, 'anuj', 0.00, 'Admin earnings from course purchase', 1, '2025-02-25 10:53:26'),
(262, 'h', 0.00, 'Referral commission', 1, '2025-02-25 10:53:26'),
(263, 'g', 0.00, 'Referral commission', 1, '2025-02-25 10:53:26'),
(264, 'f', 0.00, 'Referral commission', 1, '2025-02-25 10:53:26'),
(265, 'neha', 0.00, 'Referral commission', 1, '2025-02-25 10:53:26'),
(266, 'anuj', 0.00, 'Referral commission', 1, '2025-02-25 10:53:26'),
(267, 'anuj', 0.00, 'Admin earnings from course purchase', 1, '2025-02-25 10:53:34'),
(268, 'h', 0.00, 'Referral commission', 1, '2025-02-25 10:53:34'),
(269, 'g', 0.00, 'Referral commission', 1, '2025-02-25 10:53:34'),
(270, 'f', 0.00, 'Referral commission', 1, '2025-02-25 10:53:34'),
(271, 'neha', 0.00, 'Referral commission', 1, '2025-02-25 10:53:34'),
(272, 'anuj', 0.00, 'Referral commission', 1, '2025-02-25 10:53:34'),
(273, 'anuj', 0.00, 'Admin earnings from course purchase', 1, '2025-02-25 10:54:54'),
(274, 'h', 0.00, 'Referral commission', 1, '2025-02-25 10:54:54'),
(275, 'g', 0.00, 'Referral commission', 1, '2025-02-25 10:54:54'),
(276, 'f', 0.00, 'Referral commission', 1, '2025-02-25 10:54:54'),
(277, 'neha', 0.00, 'Referral commission', 1, '2025-02-25 10:54:54'),
(278, 'anuj', 0.00, 'Referral commission', 1, '2025-02-25 10:54:54'),
(279, 'anuj', 63.00, 'Admin earnings from course purchase', 1, '2025-02-25 10:59:35'),
(280, 'h', 12.00, 'Referral commission', 1, '2025-02-25 10:59:35'),
(281, 'g', 10.00, 'Referral commission', 1, '2025-02-25 10:59:35'),
(282, 'f', 7.00, 'Referral commission', 1, '2025-02-25 10:59:35'),
(283, 'neha', 5.00, 'Referral commission', 1, '2025-02-25 10:59:35'),
(284, 'anuj', 3.00, 'Referral commission', 1, '2025-02-25 10:59:35'),
(285, 'h', 15.00, 'Level 1 Income', 1, '2025-02-25 14:48:15'),
(286, 'g', 10.00, 'Level 2 Income', 1, '2025-02-25 14:48:15'),
(287, 'f', 5.00, 'Level 3 Income', 1, '2025-02-25 14:48:15'),
(288, 'neha', 4.00, 'Level 4 Income', 1, '2025-02-25 14:48:15'),
(289, 'anuj', 3.00, 'Level 5 Income', 1, '2025-02-25 14:48:15'),
(290, 'h', 15.00, 'Level 1 Income', 1, '2025-02-25 14:50:59'),
(291, 'g', 10.00, 'Level 2 Income', 1, '2025-02-25 14:50:59'),
(292, 'f', 5.00, 'Level 3 Income', 1, '2025-02-25 14:50:59'),
(293, 'neha', 4.00, 'Level 4 Income', 1, '2025-02-25 14:50:59'),
(294, 'anuj', 3.00, 'Level 5 Income', 1, '2025-02-25 14:50:59'),
(295, 'g', 15.00, 'Level 1 Income', 1, '2025-02-25 14:52:27'),
(296, 'f', 10.00, 'Level 2 Income', 1, '2025-02-25 14:52:27'),
(297, 'neha', 5.00, 'Level 3 Income', 1, '2025-02-25 14:52:27'),
(298, 'anuj', 4.00, 'Level 4 Income', 1, '2025-02-25 14:52:27'),
(299, 'c', 1.00, 'Add', 1, '2025-02-27 14:26:58'),
(300, 'anuj', 63.00, 'Admin earnings from course purchase', 1, '2025-02-27 15:35:55'),
(301, 'c', 12.00, 'Referral commission', 1, '2025-02-27 15:35:55'),
(302, 'b', 10.00, 'Referral commission', 1, '2025-02-27 15:35:55'),
(303, 'p', 7.00, 'Referral commission', 1, '2025-02-27 15:35:55'),
(304, 'anuj', 5.00, 'Referral commission', 1, '2025-02-27 15:35:55'),
(305, 'anuj', 63.00, 'Admin earnings from course purchase', 1, '2025-02-27 15:41:06'),
(306, 'c', 12.00, 'Referral commission 1', 1, '2025-02-27 15:41:06'),
(307, 'b', 10.00, 'Referral commission 2', 1, '2025-02-27 15:41:06'),
(308, 'p', 7.00, 'Referral commission 3', 1, '2025-02-27 15:41:06'),
(309, 'anuj', 5.00, 'Referral commission 4', 1, '2025-02-27 15:41:06'),
(310, 'anuj', 100.00, 'Admin earnings from course purchase', 1, '2025-02-27 15:43:32'),
(311, 'c', 12.00, 'Referral commission 1', 1, '2025-02-27 15:43:32'),
(312, 'b', 10.00, 'Referral commission 2', 1, '2025-02-27 15:43:32'),
(313, 'p', 7.00, 'Referral commission 3', 1, '2025-02-27 15:43:32'),
(314, 'anuj', 5.00, 'Referral commission 4', 1, '2025-02-27 15:43:32'),
(315, 'anuj', 100.00, 'Admin earnings from course purchase', 1, '2025-02-27 15:45:27'),
(316, 'c', 12.00, 'Referral commission level 1', 1, '2025-02-27 15:45:27'),
(317, 'b', 10.00, 'Referral commission level 2', 1, '2025-02-27 15:45:27'),
(318, 'p', 7.00, 'Referral commission level 3', 1, '2025-02-27 15:45:27'),
(319, 'anuj', 5.00, 'Referral commission level 4', 1, '2025-02-27 15:45:27'),
(320, 'anuj', 100.00, 'Admin earnings from course purchase', 1, '2025-02-27 15:57:07'),
(321, 'c', 12.00, 'Referral commission level 1', 1, '2025-02-27 15:57:07'),
(322, 'b', 10.00, 'Referral commission level 2', 1, '2025-02-27 15:57:07'),
(323, 'p', 7.00, 'Referral commission level 3', 1, '2025-02-27 15:57:07'),
(324, 'anuj', 5.00, 'Referral commission level 4', 1, '2025-02-27 15:57:07'),
(325, 'anuj', 100.00, 'Admin earnings from course purchase', 1, '2025-02-27 15:57:53'),
(326, 'c', 12.00, 'Referral commission level 1', 1, '2025-02-27 15:57:53'),
(327, 'b', 10.00, 'Referral commission level 2', 1, '2025-02-27 15:57:53'),
(328, 'p', 7.00, 'Referral commission level 3', 1, '2025-02-27 15:57:53'),
(329, 'anuj', 5.00, 'Referral commission level 4', 1, '2025-02-27 15:57:53'),
(330, 'anuj', 5.00, 'Unclaimed commission from level 4', 1, '2025-02-27 15:57:53'),
(331, 'anuj', 100.00, 'Admin earnings from course purchase', 1, '2025-02-27 15:59:10'),
(332, 'c', 12.00, 'Referral commission level 1', 1, '2025-02-27 15:59:10'),
(333, 'b', 10.00, 'Referral commission level 2', 1, '2025-02-27 15:59:10'),
(334, 'p', 7.00, 'Referral commission level 3', 1, '2025-02-27 15:59:10'),
(335, 'anuj', 5.00, 'Referral commission level 4', 1, '2025-02-27 15:59:10'),
(336, 'anuj', 5.00, 'Referral commission level 4', 1, '2025-02-27 15:59:10'),
(337, 'anuj', 100.00, 'Admin earnings from course purchase', 1, '2025-02-27 16:00:12'),
(338, 'c', 12.00, 'Referral commission level 1', 1, '2025-02-27 16:00:12'),
(339, 'b', 10.00, 'Referral commission level 2', 1, '2025-02-27 16:00:12'),
(340, 'p', 7.00, 'Referral commission level 3', 1, '2025-02-27 16:00:12'),
(341, 'anuj', 5.00, 'Referral commission level 4', 1, '2025-02-27 16:00:12'),
(342, 'anuj', 5.00, 'Referral commission level 5', 1, '2025-02-27 16:00:12'),
(343, 'anuj', 100.00, 'Admin earnings from course purchase', 1, '2025-02-27 16:01:39'),
(344, 'c', 12.00, 'Referral commission level 1', 1, '2025-02-27 16:01:39'),
(345, 'b', 10.00, 'Referral commission level 2', 1, '2025-02-27 16:01:39'),
(346, 'p', 7.00, 'Referral commission level 3', 1, '2025-02-27 16:01:39'),
(347, 'anuj', 5.00, 'Referral commission level 4', 1, '2025-02-27 16:01:39'),
(348, 'anuj', 5.00, 'Referral commission level 5', 1, '2025-02-27 16:01:39'),
(349, 'anuj', 100.00, 'Admin earnings from course purchase', 1, '2025-02-27 16:03:37'),
(350, 'c', 12.00, 'Referral commission level 1', 1, '2025-02-27 16:03:37'),
(351, 'b', 10.00, 'Referral commission level 2', 1, '2025-02-27 16:03:37'),
(352, 'p', 7.00, 'Referral commission level 3', 1, '2025-02-27 16:03:37'),
(353, 'anuj', 5.00, 'Referral commission level 4', 1, '2025-02-27 16:03:37'),
(354, 'anuj', 100.00, 'Admin earnings from course purchase', 1, '2025-02-27 16:04:42'),
(355, 'c', 12.00, 'Referral commission level 1', 1, '2025-02-27 16:04:42'),
(356, 'b', 10.00, 'Referral commission level 2', 1, '2025-02-27 16:04:42'),
(357, 'p', 7.00, 'Referral commission level 3', 1, '2025-02-27 16:04:43'),
(358, 'anuj', 5.00, 'Referral commission level 4', 1, '2025-02-27 16:04:43'),
(359, 'anuj', 100.00, 'Admin earnings from course purchase', 1, '2025-02-27 16:06:59'),
(360, 'c', 12.00, 'Referral commission level 1', 1, '2025-02-27 16:06:59'),
(361, 'b', 10.00, 'Referral commission level 2', 1, '2025-02-27 16:06:59'),
(362, 'p', 7.00, 'Referral commission level 3', 1, '2025-02-27 16:06:59'),
(363, 'anuj', 5.00, 'Referral commission level 4', 1, '2025-02-27 16:06:59'),
(364, 'anuj', 100.00, 'Admin earnings from course purchase', 1, '2025-02-27 16:08:27'),
(365, 'c', 12.00, 'Referral commission level 1', 1, '2025-02-27 16:08:27'),
(366, 'b', 10.00, 'Referral commission level 2', 1, '2025-02-27 16:08:27'),
(367, 'p', 7.00, 'Referral commission level 3', 1, '2025-02-27 16:08:27'),
(368, 'anuj', 5.00, 'Referral commission level 4', 1, '2025-02-27 16:08:27'),
(369, 'anuj', 100.00, 'Admin earnings from course purchase', 1, '2025-02-27 16:09:34'),
(370, 'c', 12.00, 'Referral commission level 1', 1, '2025-02-27 16:09:34'),
(371, 'b', 10.00, 'Referral commission level 2', 1, '2025-02-27 16:09:34'),
(372, 'p', 7.00, 'Referral commission level 3', 1, '2025-02-27 16:09:34'),
(373, 'anuj', 5.00, 'Referral commission level 4', 1, '2025-02-27 16:09:34'),
(374, 'anuj', 5.00, 'Referral commission level 5', 1, '2025-02-27 16:09:34'),
(375, 'anuj', 100.00, 'Admin earnings from course purchase', 1, '2025-02-27 16:11:02'),
(376, 'p', 12.00, 'Referral commission level 1', 1, '2025-02-27 16:11:02'),
(377, 'anuj', 10.00, 'Referral commission level 2', 1, '2025-02-27 16:11:02'),
(378, 'anuj', 10.00, 'Referral commission level 3', 1, '2025-02-27 16:11:02'),
(379, 'anuj', 10.00, 'Referral commission level 4', 1, '2025-02-27 16:11:02'),
(380, 'anuj', 10.00, 'Referral commission level 5', 1, '2025-02-27 16:11:02'),
(381, 'anuj', 100.00, 'Admin earnings from course purchase', 1, '2025-02-27 16:13:59'),
(382, 'p', 12.00, 'Referral commission level 1', 1, '2025-02-27 16:13:59'),
(383, 'anuj', 10.00, 'Referral commission level 2', 1, '2025-02-27 16:13:59'),
(384, 'anuj', 7.00, 'Referral commission level 3', 1, '2025-02-27 16:13:59'),
(385, 'anuj', 5.00, 'Referral commission level 4', 1, '2025-02-27 16:13:59'),
(386, 'anuj', 3.00, 'Referral commission level 5', 1, '2025-02-27 16:13:59'),
(387, 'b', 100.00, ' course purchase', 1, '2025-02-27 16:18:35'),
(388, 'p', 12.00, 'Referral commission level 1', 1, '2025-02-27 16:18:35'),
(389, 'anuj', 10.00, 'Referral commission level 2', 1, '2025-02-27 16:18:35'),
(390, 'anuj', 7.00, 'Referral commission level 3', 1, '2025-02-27 16:18:35'),
(391, 'anuj', 5.00, 'Referral commission level 4', 1, '2025-02-27 16:18:35'),
(392, 'anuj', 3.00, 'Referral commission level 5', 1, '2025-02-27 16:18:35'),
(393, 'b', 100.00, ' course purchase', 1, '2025-02-27 16:19:13'),
(394, 'p', 12.00, 'Referral commission level 1', 1, '2025-02-27 16:19:13'),
(395, 'anuj', 10.00, 'Referral commission level 2', 1, '2025-02-27 16:19:13'),
(396, 'anuj', 7.00, 'Referral commission level 3', 1, '2025-02-27 16:19:13'),
(397, 'anuj', 5.00, 'Referral commission level 4', 1, '2025-02-27 16:19:13'),
(398, 'anuj', 3.00, 'Referral commission level 5', 1, '2025-02-27 16:19:13'),
(399, 'b', 100.00, ' course purchase', 1, '2025-02-27 16:26:12'),
(400, 'p', 12.00, 'Referral commission level 1', 1, '2025-02-27 16:26:12'),
(401, 'anuj', 10.00, 'Referral commission level 2', 1, '2025-02-27 16:26:12'),
(402, 'anuj', 7.00, 'Referral commission level 3', 1, '2025-02-27 16:26:12'),
(403, 'anuj', 5.00, 'Referral commission level 4', 1, '2025-02-27 16:26:12'),
(404, 'anuj', 3.00, 'Referral commission level 5', 1, '2025-02-27 16:26:12'),
(405, 'b', 100.00, ' course purchase', 1, '2025-02-27 16:28:47'),
(406, 'p', 12.00, 'Referral commission level 1', 1, '2025-02-27 16:28:47'),
(407, 'anuj', 10.00, 'Referral commission level 2', 1, '2025-02-27 16:28:47'),
(408, 'anuj', 7.00, 'Referral commission level 3', 1, '2025-02-27 16:28:47'),
(409, 'anuj', 5.00, 'Referral commission level 4', 1, '2025-02-27 16:28:47'),
(410, 'anuj', 3.00, 'Referral commission level 5', 1, '2025-02-27 16:28:47'),
(411, 'neha', 100.00, ' course purchase', 1, '2025-02-27 17:18:19'),
(412, 'anuj', 12.00, 'Referral commission level 1', 1, '2025-02-27 17:18:19'),
(413, 'anuj', 10.00, 'Referral commission level 2', 1, '2025-02-27 17:18:19'),
(414, 'anuj', 7.00, 'Referral commission level 3', 1, '2025-02-27 17:18:19'),
(415, 'anuj', 5.00, 'Referral commission level 4', 1, '2025-02-27 17:18:19'),
(416, 'anuj', 3.00, 'Referral commission level 5', 1, '2025-02-27 17:18:19'),
(417, 'neha', 100.00, ' course purchase', 1, '2025-02-27 17:20:06'),
(418, 'anuj', 12.00, 'Referral commission level 1', 1, '2025-02-27 17:20:06'),
(419, 'anuj', 10.00, 'Referral commission level 2', 1, '2025-02-27 17:20:06'),
(420, 'anuj', 7.00, 'Referral commission level 3', 1, '2025-02-27 17:20:06'),
(421, 'anuj', 5.00, 'Referral commission level 4', 1, '2025-02-27 17:20:06'),
(422, 'anuj', 3.00, 'Referral commission level 5', 1, '2025-02-27 17:20:06'),
(423, 'neha', 100.00, ' course purchase', 1, '2025-02-27 17:20:36'),
(424, 'anuj', 12.00, 'Referral commission level 1', 1, '2025-02-27 17:20:36'),
(425, 'anuj', 10.00, 'Referral commission level 2', 1, '2025-02-27 17:20:36'),
(426, 'anuj', 7.00, 'Referral commission level 3', 1, '2025-02-27 17:20:36'),
(427, 'anuj', 5.00, 'Referral commission level 4', 1, '2025-02-27 17:20:36'),
(428, 'anuj', 3.00, 'Referral commission level 5', 1, '2025-02-27 17:20:36'),
(429, 'aa', 100.00, ' course purchase', 1, '2025-02-27 17:22:00'),
(430, 'c', 12.00, 'Referral commission level 1', 1, '2025-02-27 17:22:00'),
(431, 'b', 10.00, 'Referral commission level 2', 1, '2025-02-27 17:22:00'),
(432, 'p', 7.00, 'Referral commission level 3', 1, '2025-02-27 17:22:00'),
(433, 'anuj', 5.00, 'Referral commission level 4', 1, '2025-02-27 17:22:00'),
(434, 'anuj', 3.00, 'Referral commission level 5', 1, '2025-02-27 17:22:00'),
(435, 'aa', 169.99, ' course purchase', 1, '2025-02-28 11:12:07'),
(436, 'c', 20.40, 'Referral commission level 1', 1, '2025-02-28 11:12:07'),
(437, 'b', 17.00, 'Referral commission level 2', 1, '2025-02-28 11:12:07'),
(438, 'p', 11.90, 'Referral commission level 3', 1, '2025-02-28 11:12:07'),
(439, 'anuj', 8.50, 'Referral commission level 4', 1, '2025-02-28 11:12:07'),
(440, 'anuj', 5.10, 'Referral commission level 5', 1, '2025-02-28 11:12:07'),
(441, 'aa', 169.99, ' course purchase', 1, '2025-02-28 11:55:55'),
(442, 'c', 20.40, 'Referral commission level 1', 1, '2025-02-28 11:55:55'),
(443, 'b', 17.00, 'Referral commission level 2', 1, '2025-02-28 11:55:55'),
(444, 'p', 11.90, 'Referral commission level 3', 1, '2025-02-28 11:55:55'),
(445, 'anuj', 8.50, 'Referral commission level 4', 1, '2025-02-28 11:55:55'),
(446, 'anuj', 5.10, 'Referral commission level 5', 1, '2025-02-28 11:55:55'),
(447, 'aa', 169.99, ' course purchase', 1, '2025-02-28 11:56:38'),
(448, 'c', 20.40, 'Referral commission level 1', 1, '2025-02-28 11:56:38'),
(449, 'b', 17.00, 'Referral commission level 2', 1, '2025-02-28 11:56:38'),
(450, 'p', 11.90, 'Referral commission level 3', 1, '2025-02-28 11:56:38'),
(451, 'anuj', 8.50, 'Referral commission level 4', 1, '2025-02-28 11:56:38'),
(452, 'anuj', 5.10, 'Referral commission level 5', 1, '2025-02-28 11:56:38'),
(453, 'aa', 169.99, ' course purchase', 1, '2025-02-28 11:59:33'),
(454, 'c', 20.40, 'Referral commission level 1', 1, '2025-02-28 11:59:33'),
(455, 'b', 17.00, 'Referral commission level 2', 1, '2025-02-28 11:59:33'),
(456, 'p', 11.90, 'Referral commission level 3', 1, '2025-02-28 11:59:33'),
(457, 'anuj', 8.50, 'Referral commission level 4', 1, '2025-02-28 11:59:33'),
(458, 'anuj', 5.10, 'Referral commission level 5', 1, '2025-02-28 11:59:33'),
(459, 'aa', 169.99, ' course purchase', 1, '2025-02-28 11:59:44'),
(460, 'c', 20.40, 'Referral commission level 1', 1, '2025-02-28 11:59:44'),
(461, 'b', 17.00, 'Referral commission level 2', 1, '2025-02-28 11:59:44'),
(462, 'p', 11.90, 'Referral commission level 3', 1, '2025-02-28 11:59:44'),
(463, 'anuj', 8.50, 'Referral commission level 4', 1, '2025-02-28 11:59:44'),
(464, 'anuj', 5.10, 'Referral commission level 5', 1, '2025-02-28 11:59:44'),
(465, 'aa', 29.99, ' course purchase', 1, '2025-02-28 15:15:26'),
(466, 'c', 3.60, 'Referral commission level 1', 1, '2025-02-28 15:15:26'),
(467, 'b', 3.00, 'Referral commission level 2', 1, '2025-02-28 15:15:26'),
(468, 'p', 2.10, 'Referral commission level 3', 1, '2025-02-28 15:15:26'),
(469, 'anuj', 1.50, 'Referral commission level 4', 1, '2025-02-28 15:15:26'),
(470, 'anuj', 0.90, 'Referral commission level 5', 1, '2025-02-28 15:15:26'),
(471, 'aa', 34.09, ' course purchase', 1, '2025-02-28 16:00:30'),
(472, 'c', 4.09, 'Referral commission level 1', 1, '2025-02-28 16:00:30'),
(473, 'b', 3.41, 'Referral commission level 2', 1, '2025-02-28 16:00:30'),
(474, 'p', 2.39, 'Referral commission level 3', 1, '2025-02-28 16:00:30'),
(475, 'anuj', 1.70, 'Referral commission level 4', 1, '2025-02-28 16:00:30'),
(476, 'anuj', 1.02, 'Referral commission level 5', 1, '2025-02-28 16:00:30'),
(477, 'aa', 100.00, ' course purchase', 1, '2025-02-28 16:39:57'),
(478, 'c', 12.00, 'Referral commission level 1', 1, '2025-02-28 16:39:57'),
(479, 'b', 10.00, 'Referral commission level 2', 1, '2025-02-28 16:39:57'),
(480, 'p', 7.00, 'Referral commission level 3', 1, '2025-02-28 16:39:57'),
(481, 'anuj', 5.00, 'Referral commission level 4', 1, '2025-02-28 16:39:57'),
(482, 'anuj', 3.00, 'Referral commission level 5', 1, '2025-02-28 16:39:57'),
(483, 'aa', 79.99, ' course purchase', 1, '2025-02-28 16:43:55'),
(484, 'c', 9.60, 'Referral commission level 1', 1, '2025-02-28 16:43:55'),
(485, 'b', 8.00, 'Referral commission level 2', 1, '2025-02-28 16:43:55'),
(486, 'p', 5.60, 'Referral commission level 3', 1, '2025-02-28 16:43:55'),
(487, 'anuj', 4.00, 'Referral commission level 4', 1, '2025-02-28 16:43:55'),
(488, 'anuj', 2.40, 'Referral commission level 5', 1, '2025-02-28 16:43:55'),
(489, 'aa', 24.99, ' course purchase', 1, '2025-02-28 16:44:40'),
(490, 'c', 3.00, 'Referral commission level 1', 1, '2025-02-28 16:44:40'),
(491, 'b', 2.50, 'Referral commission level 2', 1, '2025-02-28 16:44:40'),
(492, 'p', 1.75, 'Referral commission level 3', 1, '2025-02-28 16:44:40'),
(493, 'anuj', 1.25, 'Referral commission level 4', 1, '2025-02-28 16:44:40'),
(494, 'anuj', 0.75, 'Referral commission level 5', 1, '2025-02-28 16:44:40'),
(495, 'aa', 44.99, ' course purchase', 1, '2025-02-28 16:52:17'),
(496, 'c', 5.40, 'Referral commission level 1', 1, '2025-02-28 16:52:17'),
(497, 'b', 4.50, 'Referral commission level 2', 1, '2025-02-28 16:52:17'),
(498, 'p', 3.15, 'Referral commission level 3', 1, '2025-02-28 16:52:17'),
(499, 'anuj', 2.25, 'Referral commission level 4', 1, '2025-02-28 16:52:17'),
(500, 'anuj', 1.35, 'Referral commission level 5', 1, '2025-02-28 16:52:17'),
(501, 'anuj', 15.00, 'Level 1 Income', 1, '2025-03-01 09:31:01');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `cart`
--
ALTER TABLE `cart`
  ADD PRIMARY KEY (`id`),
  ADD KEY `username` (`username`),
  ADD KEY `course_id` (`course_id`);

--
-- Indexes for table `courses`
--
ALTER TABLE `courses`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `fees`
--
ALTER TABLE `fees`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `students`
--
ALTER TABLE `students`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `email` (`email`),
  ADD UNIQUE KEY `username` (`username`),
  ADD UNIQUE KEY `username_2` (`username`),
  ADD UNIQUE KEY `username_3` (`username`);

--
-- Indexes for table `wallet_history`
--
ALTER TABLE `wallet_history`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_wallet_username` (`username`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `cart`
--
ALTER TABLE `cart`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=20;

--
-- AUTO_INCREMENT for table `courses`
--
ALTER TABLE `courses`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- AUTO_INCREMENT for table `fees`
--
ALTER TABLE `fees`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `students`
--
ALTER TABLE `students`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=22;

--
-- AUTO_INCREMENT for table `wallet_history`
--
ALTER TABLE `wallet_history`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=502;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `cart`
--
ALTER TABLE `cart`
  ADD CONSTRAINT `cart_ibfk_1` FOREIGN KEY (`username`) REFERENCES `students` (`username`) ON DELETE CASCADE,
  ADD CONSTRAINT `cart_ibfk_2` FOREIGN KEY (`course_id`) REFERENCES `courses` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `wallet_history`
--
ALTER TABLE `wallet_history`
  ADD CONSTRAINT `fk_wallet_username` FOREIGN KEY (`username`) REFERENCES `students` (`username`) ON DELETE CASCADE ON UPDATE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
