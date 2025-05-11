-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: localhost
-- Generation Time: May 02, 2025 at 11:09 AM
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
-- Database: `hotel_management`
--

-- --------------------------------------------------------

--
-- Table structure for table `contacts`
--

CREATE TABLE `contacts` (
  `id` int(11) NOT NULL,
  `name` varchar(100) NOT NULL,
  `email` varchar(100) NOT NULL,
  `message` text NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `user_id` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `contacts`
--

INSERT INTO `contacts` (`id`, `name`, `email`, `message`, `created_at`, `user_id`) VALUES
(1, 'Resila', 'resi@gmail.com', 'I\'m having problems booking a room for vacation.', '2025-03-31 23:46:19', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `employees`
--

CREATE TABLE `employees` (
  `id` int(11) NOT NULL,
  `first_name` varchar(50) DEFAULT NULL,
  `last_name` varchar(50) DEFAULT NULL,
  `position` varchar(50) DEFAULT NULL,
  `email` varchar(100) DEFAULT NULL,
  `phone` varchar(20) DEFAULT NULL,
  `created_at` datetime DEFAULT current_timestamp(),
  `user_id` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `employees`
--

INSERT INTO `employees` (`id`, `first_name`, `last_name`, `position`, `email`, `phone`, `created_at`, `user_id`) VALUES
(2, 'John', 'Doe', 'Manager', 'john.doe@example.com', '1234567897', '2025-03-27 16:11:55', NULL),
(3, 'Jane', 'Smith', 'Receptionist', 'jane.smith@example.com', '2345678901', '2025-03-27 16:11:55', NULL),
(4, 'Alice', 'Johnson', 'Cleaner', 'alice.johnson@example.com', '3456789012', '2025-03-27 16:11:55', NULL),
(5, 'Bob', 'Williams', 'Chef', 'bob.williams@example.com', '4567890123', '2025-03-27 16:11:55', NULL),
(6, 'Eve', 'Miller', 'Bartender', 'eve.miller@example.com', '5678901234', '2025-03-27 16:11:55', NULL),
(7, 'Charlie', 'Brown', 'Waiter', 'charlie.brown@example.com', '6789012345', '2025-03-27 16:11:55', NULL),
(8, 'Grace', 'Davis', 'Housekeeper', 'grace.davis@example.com', '7890123456', '2025-03-27 16:11:55', NULL),
(9, 'James', 'Wilson', 'Security', 'james.wilson@example.com', '8901234567', '2025-03-27 16:11:55', NULL),
(10, 'Olivia', 'Moore', 'Cook', 'olivia.moore@example.com', '9012345678', '2025-03-27 16:11:55', NULL),
(11, 'Liam', 'Taylor', 'Manager', 'liam.taylor@example.com', '0123456789', '2025-03-27 16:11:55', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `reservations`
--

CREATE TABLE `reservations` (
  `id` int(11) NOT NULL,
  `user_id` int(11) DEFAULT NULL,
  `room_id` int(11) DEFAULT NULL,
  `check_in` date DEFAULT NULL,
  `check_out` date DEFAULT NULL,
  `total_price` decimal(10,2) DEFAULT NULL,
  `status` enum('pending','confirmed','cancelled') DEFAULT 'pending',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `reservations`
--

INSERT INTO `reservations` (`id`, `user_id`, `room_id`, `check_in`, `check_out`, `total_price`, `status`, `created_at`) VALUES
(43, 3, 39, '2025-03-29', '2025-04-30', 95.00, 'cancelled', '2025-03-28 13:43:17'),
(44, 4, 12, '2025-03-29', '2025-04-17', 140.00, 'confirmed', '2025-03-28 13:58:24'),
(45, 4, 41, '2025-03-31', '2025-04-09', 140.00, 'confirmed', '2025-03-28 14:04:29'),
(46, 4, 48, '2025-03-30', '2025-03-31', 110.00, 'cancelled', '2025-03-28 14:05:58'),
(47, 4, 40, '2025-03-29', '2025-03-31', 120.00, 'confirmed', '2025-03-28 14:06:59'),
(48, 3, 13, '2025-03-30', '2025-04-10', 160.00, 'confirmed', '2025-03-28 14:11:23'),
(49, 1, 9, '2025-03-28', '2025-03-28', 90.00, 'confirmed', '2025-03-28 20:14:47'),
(50, 15, 15, '2025-04-02', '2025-04-03', 210.00, 'cancelled', '2025-03-31 22:28:08'),
(51, 3, 9, '2025-04-01', '2025-04-01', 90.00, 'confirmed', '2025-04-01 17:15:45'),
(53, 17, 9, '2025-04-02', '2025-04-02', 90.00, 'confirmed', '2025-04-02 21:03:45'),
(54, 18, 9, '2025-04-03', '2025-04-03', 90.00, 'confirmed', '2025-04-03 17:56:30');

-- --------------------------------------------------------

--
-- Table structure for table `reviews`
--

CREATE TABLE `reviews` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `comment` text NOT NULL,
  `rating` int(11) NOT NULL CHECK (`rating` between 1 and 5),
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `room_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `rooms`
--

CREATE TABLE `rooms` (
  `id` int(11) NOT NULL,
  `room_number` varchar(10) DEFAULT NULL,
  `room_type_id` int(11) DEFAULT NULL,
  `price` decimal(10,2) DEFAULT NULL,
  `description` text DEFAULT NULL,
  `photo` varchar(255) DEFAULT NULL,
  `status` enum('available','booked') DEFAULT 'available',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `rooms`
--

INSERT INTO `rooms` (`id`, `room_number`, `room_type_id`, `price`, `description`, `photo`, `status`, `created_at`) VALUES
(9, '101', 1, 120.00, 'Single room with garden view', 'https://i.pinimg.com/736x/53/f2/40/53f240b1941a4d5ecfd179c7091d7d1a.jpg', 'booked', '2025-03-27 20:07:06'),
(12, '104', 2, 140.00, 'Double room with city view', 'https://i.pinimg.com/736x/23/ea/3b/23ea3be03555947e77560a9976be0502.jpg', 'available', '2025-03-27 20:07:06'),
(13, '105', 2, 160.00, 'Double room with pishinë', 'https://i.pinimg.com/736x/1f/0d/0e/1f0d0eeff459f654c9985e52bb9f299e.jpg', 'available', '2025-03-27 20:07:06'),
(14, '106', 3, 200.00, 'Suite me kuzhinë', 'https://i.pinimg.com/736x/de/42/d1/de42d12450d717c622e661b8c230d1e7.jpg', 'available', '2025-03-27 20:07:06'),
(15, '107', 3, 210.00, 'Lux suite', 'https://i.pinimg.com/736x/cd/7e/de/cd7ede27b9eb00bf5769c3545850c545.jpg', 'available', '2025-03-27 20:07:06'),
(16, '108', 1, 95.00, 'Single room with workspace', 'https://i.pinimg.com/736x/02/93/d4/0293d44778dc655a6f67ca6a8cc0a845.jpg', 'available', '2025-03-27 20:07:06'),
(17, '109', 2, 170.00, 'Double room with balcony', 'https://i.pinimg.com/736x/fa/93/f8/fa93f8914c5b01251c90eca5da4041a6.jpg', 'available', '2025-03-27 20:07:06'),
(18, '110', 3, 250.00, 'Executive suite', 'https://i.pinimg.com/736x/78/ae/dd/78aedd55dfc6abbccb24ef01120b9428.jpg', 'available', '2025-03-27 20:07:06'),
(39, '111', 1, 95.00, 'Single room with desk and workspace', 'https://i.pinimg.com/736x/27/1a/29/271a29121a824bb479ab474c1fffc28b.jpg', 'available', '2025-03-27 20:23:47'),
(40, '112', 2, 120.00, 'Double room with city view and a balcony', 'https://i.pinimg.com/736x/ab/a9/3c/aba93c9f90d291372de824e95185e211.jpg', 'available', '2025-03-27 20:23:47'),
(41, '113', 2, 140.00, 'Double room with garden and balcony', 'https://i.pinimg.com/736x/6c/c7/de/6cc7de9af8be73312b45f9981f2f1039.jpg', 'available', '2025-03-27 20:23:47'),
(42, '114', 3, 180.00, 'Suite with living room and two bathrooms', 'https://i.pinimg.com/736x/52/02/4f/52024fd531dce400d66fea4fec344bfe.jpg', 'available', '2025-03-27 20:23:47'),
(43, '115', 3, 220.00, 'Luxurious suite with panoramic view', 'https://i.pinimg.com/736x/d1/2d/20/d12d20dcfa879eddc60571a0f6be3088.jpg', 'available', '2025-03-27 20:23:47'),
(44, '116', 1, 100.00, 'Single room with queen bed', 'https://i.pinimg.com/736x/89/7e/4f/897e4fa0b05c376e361c3c2337bdfd9a.jpg', 'available', '2025-03-27 20:23:47'),
(45, '117', 2, 160.00, 'Double room with king bed and lake view', 'https://i.pinimg.com/736x/2a/f4/8a/2af48a195e575956630c0297a6cdc126.jpg', 'available', '2025-03-27 20:23:47'),
(46, '118', 2, 170.00, 'Double room with a cozy atmosphere and a fireplace', 'https://i.pinimg.com/736x/25/4f/9d/254f9def7fa3b2ae792f105363cd3458.jpg', 'available', '2025-03-27 20:23:47'),
(47, '119', 3, 230.00, 'Suite with extra luxury, jacuzzi and balcony', 'https://i.pinimg.com/736x/5f/7b/e9/5f7be9ae2644870e2128fc510983fcc5.jpg', 'available', '2025-03-27 20:23:47'),
(48, '120', 1, 110.00, 'Single room with modern furniture', 'https://i.pinimg.com/736x/89/86/ed/8986ed4f5fefc210a22cb7609ce5ab41.jpg', 'available', '2025-03-27 20:23:47'),
(49, '123', 3, 130.00, '123', 'https://encrypted-tbn0.gstatic.com/images?q=tbn:ANd9GcRiRoy38dlehluBdvhfulRsefqSem7r2Dvzng&s', 'available', '2025-04-03 17:59:33');

-- --------------------------------------------------------

--
-- Table structure for table `room_types`
--

CREATE TABLE `room_types` (
  `id` int(11) NOT NULL,
  `name` varchar(100) DEFAULT NULL,
  `description` text DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `room_types`
--

INSERT INTO `room_types` (`id`, `name`, `description`, `created_at`) VALUES
(1, 'Single Room', 'Room for one person with a single bed', '2025-03-23 19:11:35'),
(2, 'Double Room', 'Room for two persons with a double bed', '2025-03-23 19:11:35'),
(3, 'Suite', 'Luxurious suite with extra facilities', '2025-03-23 19:11:35'),
(4, 'Single Room', 'One bed, suitable for one guest', '2025-03-27 20:09:24'),
(5, 'Double Room', 'Double bed, suitable for two guests', '2025-03-27 20:09:24'),
(6, 'Suite', 'Spacious suite with luxury amenities', '2025-03-27 20:09:24'),
(7, 'Family Room', 'Large room for families with children', '2025-03-27 20:09:24'),
(8, 'Deluxe Room', 'Premium room with extra comfort', '2025-03-27 20:09:24'),
(9, 'Economy Room', 'Budget-friendly accommodation', '2025-03-27 20:09:24'),
(10, 'Twin Room', 'Two single beds for two guests', '2025-03-27 20:09:24'),
(11, 'Studio Room', 'Room with a small kitchenette', '2025-03-27 20:09:24'),
(12, 'Business Room', 'Designed for business travelers', '2025-03-27 20:09:24'),
(13, 'Panoramic Room', 'Room with panoramic city or sea view', '2025-03-27 20:09:24');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `first_name` varchar(50) DEFAULT NULL,
  `last_name` varchar(50) DEFAULT NULL,
  `email` varchar(100) DEFAULT NULL,
  `password` varchar(255) DEFAULT NULL,
  `role` enum('admin','user') DEFAULT 'user',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `first_name`, `last_name`, `email`, `password`, `role`, `created_at`) VALUES
(1, 'Albina', 'Demaj', 'demajalbina3@gmail.com', '$2y$10$4/Lv/Ccn01cZD.ghBtc/HOYWj2ZbTB.ASDpZTH45w0.8loJ81ur1y', 'user', '2025-03-23 18:52:57'),
(2, 'Alisa', 'DEMAJ', 'alisa@live.com', '$2y$10$U.0EXuk30OQDr/367HXl6Od.aXo36pQM9LZTiHW49uTsV3VzvCVNC', 'user', '2025-03-25 10:17:08'),
(3, 'Rejan', 'DEMAJ', 'jani@gmail.com', '$2y$10$QRfWip/YI1EP0ljcbrr7x.qkJ4.Io1IaatBC9ctj3vH7LspNmLKk2', 'user', '2025-03-26 19:07:11'),
(4, 'Admin', 'admin', 'admin@example.com', '$2y$10$72Gc7SGcTZ2J575eXN8paOdGlAlLFEJuBhcvgTqiQ3DaWxdHfJAtm', 'admin', '2025-03-27 12:37:18'),
(5, 'Arta', 'Dema', 'arta1@example.com', '123456', 'user', '2025-03-27 20:06:43'),
(6, 'Blerina', 'Krasniqi', 'blerina2@example.com', '123456', 'user', '2025-03-27 20:06:43'),
(7, 'Elira', 'Shala', 'elira3@example.com', '123456', 'user', '2025-03-27 20:06:43'),
(8, 'Klea', 'Gashi', 'klea4@example.com', '123456', 'user', '2025-03-27 20:06:43'),
(9, 'Noel', 'Rama', 'noel5@example.com', '123456', 'user', '2025-03-27 20:06:43'),
(10, 'Leon', 'Hasani', 'leon6@example.com', '123456', 'user', '2025-03-27 20:06:43'),
(11, 'Sara', 'Hoti', 'sara7@example.com', '123456', 'user', '2025-03-27 20:06:43'),
(12, 'Drita', 'Aliu', 'drita8@example.com', '123456', 'user', '2025-03-27 20:06:43'),
(13, 'Altin', 'Demaj', 'altin9@example.com', '123456', 'user', '2025-03-27 20:06:43'),
(14, 'Aulon', 'Morina', 'aulon10@example.com', '123456', 'admin', '2025-03-27 20:06:43'),
(15, 'Resi', 'Demaj', 'resi@gmail.com', '$2y$10$BHXb5a1idbxvrVi8fpJ2qeqIsC/d.X0YorD0Rm5mn7Z2J.9OCS80q', 'user', '2025-03-31 22:27:19'),
(16, 'Indrit', 'Dyrmishi', 'idi@gmail.com', '$2y$10$2ICU6YrNptTy1zSPtBf1c.28wTCjpN7Jc7I9DPxotsWd7tgV5mmY2', 'user', '2025-04-01 20:49:45'),
(17, 'luljeta', 'shala', 'lule1@gmail.com', '$2y$10$pxVanizAEfTBUKWnjC7OcOf9DaPAwj/wqB8IA6sVEpRwWrc4IEXyy', 'user', '2025-04-02 21:02:21'),
(18, 'Ylber', 'Veliu', 'ylber@gmail.com', '$2y$10$0gdrdjPGHrpJ.RWgSMbWNeqeF9vIJFfLu96z/jY.Y379lVvyI16OW', 'user', '2025-04-03 17:55:36');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `contacts`
--
ALTER TABLE `contacts`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_contact_user` (`user_id`);

--
-- Indexes for table `employees`
--
ALTER TABLE `employees`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_employee_user` (`user_id`);

--
-- Indexes for table `reservations`
--
ALTER TABLE `reservations`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_res_user` (`user_id`),
  ADD KEY `fk_res_room` (`room_id`);

--
-- Indexes for table `reviews`
--
ALTER TABLE `reviews`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `rooms`
--
ALTER TABLE `rooms`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `room_number` (`room_number`),
  ADD KEY `room_type_id` (`room_type_id`);

--
-- Indexes for table `room_types`
--
ALTER TABLE `room_types`
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
-- AUTO_INCREMENT for table `contacts`
--
ALTER TABLE `contacts`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `employees`
--
ALTER TABLE `employees`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- AUTO_INCREMENT for table `reservations`
--
ALTER TABLE `reservations`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=55;

--
-- AUTO_INCREMENT for table `reviews`
--
ALTER TABLE `reviews`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `rooms`
--
ALTER TABLE `rooms`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=50;

--
-- AUTO_INCREMENT for table `room_types`
--
ALTER TABLE `room_types`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=14;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=19;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `contacts`
--
ALTER TABLE `contacts`
  ADD CONSTRAINT `fk_contact_user` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `employees`
--
ALTER TABLE `employees`
  ADD CONSTRAINT `fk_employee_user` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `reservations`
--
ALTER TABLE `reservations`
  ADD CONSTRAINT `fk_res_room` FOREIGN KEY (`room_id`) REFERENCES `rooms` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `fk_res_user` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `reservations_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `reservations_ibfk_2` FOREIGN KEY (`room_id`) REFERENCES `rooms` (`id`) ON DELETE SET NULL;

--
-- Constraints for table `reviews`
--
ALTER TABLE `reviews`
  ADD CONSTRAINT `reviews_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`);

--
-- Constraints for table `rooms`
--
ALTER TABLE `rooms`
  ADD CONSTRAINT `rooms_ibfk_1` FOREIGN KEY (`room_type_id`) REFERENCES `room_types` (`id`) ON DELETE SET NULL;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
