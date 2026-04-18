-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Apr 16, 2026 at 11:09 AM
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
-- Database: `food_donation_db`
--

-- --------------------------------------------------------

--
-- Table structure for table `donations`
--

CREATE TABLE `donations` (
  `id` int(11) NOT NULL,
  `donor_id` int(11) DEFAULT NULL,
  `food_type` varchar(100) DEFAULT NULL,
  `quantity` int(11) DEFAULT NULL,
  `status` enum('available','requested','assigned','delivered') NOT NULL DEFAULT 'available',
  `pickup_location` varchar(255) DEFAULT NULL,
  `expiry_time` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `donations`
--

INSERT INTO `donations` (`id`, `donor_id`, `food_type`, `quantity`, `status`, `pickup_location`, `expiry_time`) VALUES
(10, 13, 'Biriyani', 30, 'delivered', 'Mohammadpur', '2026-02-20 23:33:00'),
(54, 13, 'Meat', 11, 'requested', 'Agargaon', '2026-03-12 00:00:00'),
(59, 13, 'Fish', 20, 'requested', 'Savar', '2026-03-21 00:00:00'),
(60, 13, 'Pasta', 30, 'requested', 'Taltola', '2026-03-28 00:00:00'),
(61, 13, 'Snacks', 55, 'requested', 'Adabor', '2026-03-19 00:00:00'),
(72, 13, 'Fruits', 50, 'available', 'Mohammadpur', '2026-04-14 00:00:00'),
(74, 32, 'Fresh Vegetables', 100, 'available', 'Mohammadpur, Dhaka', '2026-04-16 00:00:00');

-- --------------------------------------------------------

--
-- Table structure for table `requests`
--

CREATE TABLE `requests` (
  `id` int(11) NOT NULL,
  `ngo_id` int(11) DEFAULT NULL,
  `donation_id` int(11) NOT NULL,
  `food_type` varchar(100) DEFAULT NULL,
  `quantity` int(11) DEFAULT NULL,
  `status` enum('pending','approved','rejected') DEFAULT 'pending'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `requests`
--

INSERT INTO `requests` (`id`, `ngo_id`, `donation_id`, `food_type`, `quantity`, `status`) VALUES
(65, 14, 54, NULL, NULL, 'approved'),
(66, 14, 55, NULL, NULL, 'approved'),
(67, 14, 56, NULL, NULL, 'approved'),
(68, 14, 57, NULL, NULL, 'approved'),
(69, 14, 58, NULL, NULL, 'rejected'),
(70, 14, 59, NULL, NULL, 'rejected'),
(71, 14, 60, NULL, NULL, 'approved'),
(72, 14, 61, NULL, NULL, 'approved'),
(73, 33, 73, NULL, NULL, 'approved');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `name` varchar(100) DEFAULT NULL,
  `email` varchar(100) DEFAULT NULL,
  `password` varchar(255) DEFAULT NULL,
  `role` enum('admin','donor','ngo','volunteer') NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `name`, `email`, `password`, `role`) VALUES
(12, 'Admin', 'admin@example.com', '$2y$10$tt/Vsx3JFXa/5Ws3MJEFKOd.IEWMr/028PVsDICrFm3M.cGfpOsG6', 'admin'),
(32, 'Aysha Siddika', 'donor@gmail.com', '$2y$10$NpjEhKSuE4.VGzjvT7lRleJ0Xiob1hI/UwgPySJPNWsWCOUZjUzC6', 'donor'),
(33, 'Sumaiya Anjum', 'ngo@gmail.com', '$2y$10$j9ZrpMinTYQxOgugcepJN.mJWmcCnKklueQ2jhHKSzn9KsEfmrgAq', 'ngo');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `donations`
--
ALTER TABLE `donations`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `requests`
--
ALTER TABLE `requests`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `email` (`email`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `donations`
--
ALTER TABLE `donations`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=75;

--
-- AUTO_INCREMENT for table `requests`
--
ALTER TABLE `requests`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=74;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=34;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
