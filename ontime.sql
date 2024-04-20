-- phpMyAdmin SQL Dump
-- version 5.1.3
-- https://www.phpmyadmin.net/
--
-- Host: mysql
-- Generation Time: Apr 20, 2024 at 09:47 PM
-- Server version: 10.6.12-MariaDB-1:10.6.12+maria~ubu2004-log
-- PHP Version: 8.1.14

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `ontime`
--

-- --------------------------------------------------------

--
-- Table structure for table `profiles`
--

CREATE TABLE `profiles` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `firstname` varchar(255) NOT NULL,
  `middlename` varchar(255) DEFAULT NULL,
  `lastname` varchar(255) NOT NULL,
  `picture` varchar(255) DEFAULT NULL,
  `designation` varchar(255) DEFAULT NULL,
  `created_at` datetime DEFAULT current_timestamp(),
  `updated_at` datetime DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `profiles`
--

INSERT INTO `profiles` (`id`, `user_id`, `firstname`, `middlename`, `lastname`, `picture`, `designation`, `created_at`, `updated_at`) VALUES
(1, 1, 'Shishir', '', 'Kumar', '6621acc5e7562_profile-image.jpg', 'Software Engineer', '2024-04-18 11:24:37', '2024-04-18 23:29:09');

-- --------------------------------------------------------

--
-- Table structure for table `tasks`
--

CREATE TABLE `tasks` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `title` varchar(255) NOT NULL,
  `description` text NOT NULL,
  `deadline` datetime DEFAULT current_timestamp(),
  `priority` enum('very-low','low','medium','high','very-high') DEFAULT 'very-low',
  `status` enum('not-started','in-progress','stopped','completed') DEFAULT 'not-started',
  `created_at` datetime DEFAULT current_timestamp(),
  `updated_at` datetime DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `tasks`
--

INSERT INTO `tasks` (`id`, `user_id`, `title`, `description`, `deadline`, `priority`, `status`, `created_at`, `updated_at`) VALUES
(1, 1, 'Create a new task', 'Creating a new task', '2024-04-19 06:45:00', 'high', 'not-started', '2024-04-16 23:15:56', '2024-04-17 19:32:08'),
(2, 1, 'Just another ', 'Task Do it faster ', '2024-04-20 20:14:00', 'very-low', 'in-progress', '2024-04-16 23:44:11', '2024-04-19 18:26:50'),
(3, 1, 'One more task', 'One more task for us', '2024-04-26 06:37:00', 'medium', 'in-progress', '2024-04-17 22:07:59', '2024-04-20 21:43:57'),
(4, 1, 'Let\'s create another task', 'This is our new task', '2024-04-29 10:00:00', 'low', 'completed', '2024-04-18 08:36:57', '2024-04-19 18:27:16'),
(5, 1, 'Testing new task', 'testing new task', '2024-04-19 03:00:00', 'very-high', 'stopped', '2024-04-18 09:47:56', '2024-04-19 18:27:00'),
(6, 1, 'Create a new task ', 'Added new features ', '2024-04-25 12:00:00', 'low', 'not-started', '2024-04-20 21:40:27', '2024-04-20 21:40:27'),
(7, 1, 'Create a new task  attempt 2', 'Added new features attempt 2', '2024-04-27 12:00:00', 'high', 'not-started', '2024-04-20 21:41:31', '2024-04-20 21:41:31');

-- --------------------------------------------------------

--
-- Table structure for table `task_assets`
--

CREATE TABLE `task_assets` (
  `id` int(11) NOT NULL,
  `task_id` int(11) NOT NULL,
  `location` varchar(255) NOT NULL,
  `caption` varchar(255) NOT NULL,
  `description` varchar(255) DEFAULT NULL,
  `type` varchar(20) DEFAULT NULL,
  `size` int(11) NOT NULL,
  `created_at` datetime DEFAULT current_timestamp(),
  `updated_at` datetime DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `task_assets`
--

INSERT INTO `task_assets` (`id`, `task_id`, `location`, `caption`, `description`, `type`, `size`, `created_at`, `updated_at`) VALUES
(1, 1, 'pexels-pixabay-415829.jpg', 'Testing again and again', 'Testing again and again', 'image/jpeg', 27504, '2024-04-20 20:46:03', '2024-04-20 20:46:03'),
(2, 1, 'nature-light-plant-photography-sunlight-leaf-1393281-pxhere.com.jpg', 'Check this oout', 'Here is error', 'image/jpeg', 6522536, '2024-04-20 20:46:03', '2024-04-20 20:46:03'),
(3, 7, 'wallpaperflare.com_wallpaper.jpg', 'This is new ', 'For testing purpose only ', 'image/jpeg', 173444, '2024-04-20 21:42:37', '2024-04-20 21:42:37'),
(4, 7, 'picture.png', 'Let\'s see if it works', 'HA ga ga', 'image/png', 60325, '2024-04-20 21:42:37', '2024-04-20 21:42:37');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `username` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `phone` varchar(20) NOT NULL,
  `password` varchar(255) NOT NULL,
  `email_verified_at` datetime DEFAULT NULL,
  `phone_verified_at` datetime DEFAULT NULL,
  `status` enum('active','inactive') DEFAULT 'active',
  `created_at` datetime DEFAULT current_timestamp(),
  `updated_at` datetime DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `username`, `email`, `phone`, `password`, `email_verified_at`, `phone_verified_at`, `status`, `created_at`, `updated_at`) VALUES
(1, 'user1', 'user1@email.com', '8798787878', '$2y$10$vaeKNEl/BPwf/L8V5nMXeu/xZqrdfUNSCryYRPk6V8IxM.bFiVewW', NULL, NULL, 'active', '2024-04-17 00:50:30', '2024-04-17 02:09:05');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `profiles`
--
ALTER TABLE `profiles`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `tasks`
--
ALTER TABLE `tasks`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_user_id` (`user_id`);

--
-- Indexes for table `task_assets`
--
ALTER TABLE `task_assets`
  ADD PRIMARY KEY (`id`),
  ADD KEY `task_id` (`task_id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `profiles`
--
ALTER TABLE `profiles`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `tasks`
--
ALTER TABLE `tasks`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT for table `task_assets`
--
ALTER TABLE `task_assets`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `profiles`
--
ALTER TABLE `profiles`
  ADD CONSTRAINT `profiles_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`);

--
-- Constraints for table `tasks`
--
ALTER TABLE `tasks`
  ADD CONSTRAINT `fk_user_id` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`);

--
-- Constraints for table `task_assets`
--
ALTER TABLE `task_assets`
  ADD CONSTRAINT `task_assets_ibfk_1` FOREIGN KEY (`task_id`) REFERENCES `tasks` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
