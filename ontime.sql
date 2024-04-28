-- phpMyAdmin SQL Dump
-- version 5.1.3
-- https://www.phpmyadmin.net/
--
-- Host: mysql
-- Generation Time: Apr 28, 2024 at 10:37 PM
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
-- Table structure for table `categories`
--

CREATE TABLE `categories` (
  `id` int(11) NOT NULL,
  `category_name` varchar(255) NOT NULL,
  `category_slug` varchar(255) NOT NULL,
  `status` enum('active','inactive') DEFAULT 'active',
  `created_at` datetime DEFAULT current_timestamp(),
  `updated_at` datetime DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `categories`
--

INSERT INTO `categories` (`id`, `category_name`, `category_slug`, `status`, `created_at`, `updated_at`) VALUES
(1, 'Marketing', 'marketing', 'active', '2024-04-27 19:47:48', '2024-04-27 19:47:48'),
(2, 'Technology', 'technology', 'active', '2024-04-27 20:00:20', '2024-04-27 20:00:20'),
(3, 'Medical', 'medical', 'active', '2024-04-27 20:11:55', '2024-04-27 20:11:55'),
(4, 'Government', 'government', 'active', '2024-04-27 20:15:47', '2024-04-27 20:15:47'),
(5, 'Travel', 'travel', 'active', '2024-04-27 21:03:50', '2024-04-27 21:03:50');

-- --------------------------------------------------------

--
-- Table structure for table `clients`
--

CREATE TABLE `clients` (
  `id` int(11) NOT NULL,
  `client_name` varchar(255) NOT NULL,
  `status` enum('active','inactive') DEFAULT 'active',
  `created_at` datetime DEFAULT current_timestamp(),
  `updated_at` datetime DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `clients`
--

INSERT INTO `clients` (`id`, `client_name`, `status`, `created_at`, `updated_at`) VALUES
(1, 'Jana Match Industries', 'active', '2024-04-27 21:35:54', '2024-04-27 21:35:54'),
(2, 'CIPLA LTD.', 'active', '2024-04-27 21:39:46', '2024-04-27 21:39:46'),
(3, 'Domino`s', 'active', '2024-04-27 21:41:41', '2024-04-27 21:41:41'),
(4, 'BOROSIL', 'active', '2024-04-27 21:42:44', '2024-04-27 21:42:44');

-- --------------------------------------------------------

--
-- Table structure for table `permissions`
--

CREATE TABLE `permissions` (
  `id` int(11) NOT NULL,
  `permission_name` varchar(255) NOT NULL,
  `description` text DEFAULT NULL,
  `status` enum('active','inactive') DEFAULT 'active',
  `created_at` datetime DEFAULT current_timestamp(),
  `updated_at` datetime DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

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
(1, 1, 'Shishir', '', 'Kumar', '6621acc5e7562_profile-image.jpg', 'Software Engineer', '2024-04-18 11:24:37', '2024-04-18 23:29:09'),
(16, 2, 'Ashwani', '', 'Kumar', '662afb76d06bc_profile-image.jpg', 'Software Engineer', '2024-04-26 00:21:55', '2024-04-27 17:28:23');

-- --------------------------------------------------------

--
-- Table structure for table `projects`
--

CREATE TABLE `projects` (
  `id` int(11) NOT NULL,
  `project_name` varchar(255) NOT NULL,
  `project_description` text DEFAULT NULL,
  `status` enum('not-started','in-progress','stopped','completed') DEFAULT 'not-started',
  `project_manager_id` int(11) NOT NULL,
  `priority` enum('very-low','low','medium','high','very-high') DEFAULT 'very-low',
  `budget` varchar(255) DEFAULT NULL,
  `client_id` int(11) NOT NULL,
  `category_id` int(11) NOT NULL,
  `created_at` datetime DEFAULT current_timestamp(),
  `updated_at` datetime DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `projects`
--

INSERT INTO `projects` (`id`, `project_name`, `project_description`, `status`, `project_manager_id`, `priority`, `budget`, `client_id`, `category_id`, `created_at`, `updated_at`) VALUES
(1, 'Blinkit Official Application', 'Blinkit Official Application Design and Development. ', 'in-progress', 2, 'medium', '222', 1, 3, '2024-04-28 17:45:18', '2024-04-28 17:45:18'),
(2, 'Test Official Application', 'Test Official Application Design and Development. ', 'in-progress', 2, 'medium', '22222', 1, 3, '2024-04-28 17:45:18', '2024-04-28 17:45:18');

-- --------------------------------------------------------

--
-- Table structure for table `roles`
--

CREATE TABLE `roles` (
  `id` int(11) NOT NULL,
  `role_name` varchar(255) NOT NULL,
  `description` text DEFAULT NULL,
  `status` enum('active','inactive') DEFAULT 'active',
  `created_at` datetime DEFAULT current_timestamp(),
  `updated_at` datetime DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `role_permissions`
--

CREATE TABLE `role_permissions` (
  `role_id` int(11) NOT NULL,
  `permission_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `tasks`
--

CREATE TABLE `tasks` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `project_id` int(11) DEFAULT NULL,
  `title` varchar(255) NOT NULL,
  `description` text NOT NULL,
  `deadline` datetime DEFAULT current_timestamp(),
  `priority` enum('very-low','low','medium','high','very-high') DEFAULT 'very-low',
  `public` tinyint(1) DEFAULT 1,
  `status` enum('not-started','in-progress','stopped','completed') DEFAULT 'not-started',
  `created_at` datetime DEFAULT current_timestamp(),
  `updated_at` datetime DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `tasks`
--

INSERT INTO `tasks` (`id`, `user_id`, `project_id`, `title`, `description`, `deadline`, `priority`, `public`, `status`, `created_at`, `updated_at`) VALUES
(11, 1, 1, 'Finding a needle in a haystack isn\'t hard when every straw is computerized.', 'I feel like a jigsaw puzzle missing a piece. And I\'m not even sure what the picture should be. I love Halloween. The one time of year when everyone wears a mask … not just me. I\'ve lived in darkness a long time. Over the years my eyes adjusted until the dark became my world and I could see.\n\n', '2024-04-26 12:00:00', 'high', 1, 'completed', '2024-04-21 16:37:34', '2024-04-28 19:54:37'),
(12, 1, 2, 'Finding a needle in a haystack isn\'t hard when every straw is computerized.', 'I feel like a jigsaw puzzle missing a piece. And I\'m not even sure what the picture should be. I love Halloween. The one time of year when everyone wears a mask … not just me. I\'ve lived in darkness a long time. Over the years my eyes adjusted until the dark became my world and I could see.\r\n\r\n', '2024-04-26 12:00:00', 'high', 1, 'completed', '2024-04-21 16:37:34', '2024-04-28 19:54:40'),
(13, 2, 1, 'New Task For test Application', 'Create a new advertisement asap. ', '2024-04-30 12:00:00', 'medium', 1, 'in-progress', '2024-04-28 19:16:29', '2024-04-28 19:54:46'),
(14, 2, 2, 'Create a database design', 'Create a database design', '2024-04-30 03:00:00', 'medium', 1, 'not-started', '2024-04-28 19:27:33', '2024-04-28 19:54:44');

-- --------------------------------------------------------

--
-- Table structure for table `task_assets`
--

CREATE TABLE `task_assets` (
  `id` int(11) NOT NULL,
  `task_id` int(11) NOT NULL,
  `location` varchar(255) NOT NULL,
  `caption` varchar(255) NOT NULL,
  `description` text DEFAULT NULL,
  `type` varchar(20) DEFAULT NULL,
  `size` int(11) NOT NULL,
  `created_at` datetime DEFAULT current_timestamp(),
  `updated_at` datetime DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `task_assets`
--

INSERT INTO `task_assets` (`id`, `task_id`, `location`, `caption`, `description`, `type`, `size`, `created_at`, `updated_at`) VALUES
(7, 11, '66254111af0ca_10-profile-picture-ideas-to-make-you-stand-out.jpg', 'Leela, are you alright?', 'You know the worst thing about being a slave? They make you work, but they don\'t pay you or let you go. Oh, all right, I am. But if anything happens to me, tell them I died robbing some old man. Hey! I\'m a porno-dealing monster, what do I care what you think?', 'image/jpeg', 36844, '2024-04-21 16:38:41', '2024-04-21 20:52:35'),
(8, 11, '66254111bd31d_nature-light-plant-photography-sunlight-leaf-1393281-pxhere.com.jpg', 'You got wanged on the head', 'Guards! Bring me the forms I need to fill out to have her taken away! Say it in Russian! Bender, being God isn\'t easy. If you do too much, people get dependent on you, and if you do nothing, they lose hope. You have to use a light touch. Like a safecracker, or a pickpocket.', 'image/jpeg', 6522536, '2024-04-21 16:38:41', '2024-04-21 20:52:52'),
(9, 11, '662ad77ca243e_nature-light-plant-photography-sunlight-leaf-1393281-pxhere.com.jpg', 'Testing Adding Caption', '', 'image/jpeg', 6522536, '2024-04-25 22:21:48', '2024-04-25 22:21:48'),
(10, 11, '662ad77d1d6b5_pexels-pixabay-415829.jpg', 'Common ', 'dsa', 'image/jpeg', 27504, '2024-04-25 22:21:49', '2024-04-25 22:21:49'),
(11, 13, '662ea123b912a_persuasive-ads-coca-cola-1.jpg', 'Example Advertisement', 'Something like these ', 'image/jpeg', 59885, '2024-04-28 19:18:59', '2024-04-28 19:18:59'),
(12, 13, '662ea123d21b5_cocacola-ads-example-with-orange-background-slogan.webp', 'Banner with picture', 'Orange background', 'image/webp', 631550, '2024-04-28 19:18:59', '2024-04-28 19:18:59'),
(13, 14, '662ea372b16f8_nature-light-plant-photography-sunlight-leaf-1393281-pxhere.com.jpg', 'Testing Blink it', 'sadas', 'image/jpeg', 6522536, '2024-04-28 19:28:50', '2024-04-28 19:28:50'),
(14, 14, '662ea372c0fa5_pexels-pixabay-415829.jpg', 'Testing again', 'dasdas', 'image/jpeg', 27504, '2024-04-28 19:28:50', '2024-04-28 19:28:50');

-- --------------------------------------------------------

--
-- Table structure for table `task_comments`
--

CREATE TABLE `task_comments` (
  `id` int(11) NOT NULL,
  `task_id` int(11) NOT NULL,
  `parent_comment_id` int(11) DEFAULT NULL,
  `user_id` int(11) NOT NULL,
  `comment_text` text DEFAULT NULL,
  `created_at` datetime DEFAULT current_timestamp(),
  `updated_at` datetime DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `task_comments`
--

INSERT INTO `task_comments` (`id`, `task_id`, `parent_comment_id`, `user_id`, `comment_text`, `created_at`, `updated_at`) VALUES
(1, 11, NULL, 1, 'Adding another comment to testing ', '2024-04-22 12:25:58', '2024-04-22 12:25:58'),
(2, 11, 1, 1, 'New Comment to Adding another comment to testing\r\n', '2024-04-22 12:29:25', '2024-04-22 12:29:25'),
(3, 11, NULL, 1, 'Totally new Comment ', '2024-04-22 12:29:54', '2024-04-22 12:29:54'),
(4, 11, 3, 1, 'Adding new comment to Totally new Comment', '2024-04-22 12:31:26', '2024-04-22 12:31:26'),
(6, 11, 1, 1, 'Testing With another comment !', '2024-04-22 12:33:51', '2024-04-22 12:33:51'),
(7, 11, 3, 2, 'Hi Have done research upon this or not?', '2024-04-26 00:51:48', '2024-04-26 00:51:48'),
(8, 11, 4, 2, 'Why So many comment?', '2024-04-26 00:53:00', '2024-04-26 00:53:00');

-- --------------------------------------------------------

--
-- Table structure for table `teams`
--

CREATE TABLE `teams` (
  `id` int(11) NOT NULL,
  `team_name` varchar(50) NOT NULL,
  `description` text DEFAULT NULL,
  `status` enum('active','inactive') DEFAULT 'active',
  `created_at` datetime DEFAULT current_timestamp(),
  `updated_at` datetime DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `team_members`
--

CREATE TABLE `team_members` (
  `team_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

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
(1, 'user1', 'user1@email.com', '8798787878', '$2y$10$vaeKNEl/BPwf/L8V5nMXeu/xZqrdfUNSCryYRPk6V8IxM.bFiVewW', NULL, NULL, 'active', '2024-04-17 00:50:30', '2024-04-17 02:09:05'),
(2, 'user2', 'user2@email.com', '8989898989', '$2y$10$vaeKNEl/BPwf/L8V5nMXeu/xZqrdfUNSCryYRPk6V8IxM.bFiVewW', NULL, NULL, 'active', '2024-04-22 21:00:08', '2024-04-22 21:00:08');

-- --------------------------------------------------------

--
-- Table structure for table `user_roles`
--

CREATE TABLE `user_roles` (
  `user_id` int(11) NOT NULL,
  `role_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `categories`
--
ALTER TABLE `categories`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `clients`
--
ALTER TABLE `clients`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `permissions`
--
ALTER TABLE `permissions`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `profiles`
--
ALTER TABLE `profiles`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `projects`
--
ALTER TABLE `projects`
  ADD PRIMARY KEY (`id`),
  ADD KEY `client_id` (`client_id`),
  ADD KEY `category_id` (`category_id`),
  ADD KEY `project_manager_id` (`project_manager_id`);

--
-- Indexes for table `roles`
--
ALTER TABLE `roles`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `role_permissions`
--
ALTER TABLE `role_permissions`
  ADD PRIMARY KEY (`role_id`,`permission_id`),
  ADD KEY `permission_id` (`permission_id`);

--
-- Indexes for table `tasks`
--
ALTER TABLE `tasks`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_user_id` (`user_id`),
  ADD KEY `project_id_key` (`project_id`);

--
-- Indexes for table `task_assets`
--
ALTER TABLE `task_assets`
  ADD PRIMARY KEY (`id`),
  ADD KEY `task_id` (`task_id`);

--
-- Indexes for table `task_comments`
--
ALTER TABLE `task_comments`
  ADD PRIMARY KEY (`id`),
  ADD KEY `task_id` (`task_id`),
  ADD KEY `parent_comment_id` (`parent_comment_id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `teams`
--
ALTER TABLE `teams`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `team_name` (`team_name`);

--
-- Indexes for table `team_members`
--
ALTER TABLE `team_members`
  ADD PRIMARY KEY (`team_id`,`user_id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `user_roles`
--
ALTER TABLE `user_roles`
  ADD PRIMARY KEY (`user_id`,`role_id`),
  ADD KEY `role_id` (`role_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `categories`
--
ALTER TABLE `categories`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `clients`
--
ALTER TABLE `clients`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `permissions`
--
ALTER TABLE `permissions`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `profiles`
--
ALTER TABLE `profiles`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=17;

--
-- AUTO_INCREMENT for table `projects`
--
ALTER TABLE `projects`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `roles`
--
ALTER TABLE `roles`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `tasks`
--
ALTER TABLE `tasks`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=15;

--
-- AUTO_INCREMENT for table `task_assets`
--
ALTER TABLE `task_assets`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=15;

--
-- AUTO_INCREMENT for table `task_comments`
--
ALTER TABLE `task_comments`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT for table `teams`
--
ALTER TABLE `teams`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `profiles`
--
ALTER TABLE `profiles`
  ADD CONSTRAINT `profiles_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`);

--
-- Constraints for table `projects`
--
ALTER TABLE `projects`
  ADD CONSTRAINT `projects_ibfk_1` FOREIGN KEY (`client_id`) REFERENCES `clients` (`id`),
  ADD CONSTRAINT `projects_ibfk_2` FOREIGN KEY (`category_id`) REFERENCES `categories` (`id`),
  ADD CONSTRAINT `projects_ibfk_3` FOREIGN KEY (`project_manager_id`) REFERENCES `users` (`id`);

--
-- Constraints for table `role_permissions`
--
ALTER TABLE `role_permissions`
  ADD CONSTRAINT `role_permissions_ibfk_1` FOREIGN KEY (`role_id`) REFERENCES `roles` (`id`),
  ADD CONSTRAINT `role_permissions_ibfk_2` FOREIGN KEY (`permission_id`) REFERENCES `permissions` (`id`);

--
-- Constraints for table `tasks`
--
ALTER TABLE `tasks`
  ADD CONSTRAINT `fk_user_id` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`),
  ADD CONSTRAINT `project_id_key` FOREIGN KEY (`project_id`) REFERENCES `projects` (`id`);

--
-- Constraints for table `task_assets`
--
ALTER TABLE `task_assets`
  ADD CONSTRAINT `task_assets_ibfk_1` FOREIGN KEY (`task_id`) REFERENCES `tasks` (`id`);

--
-- Constraints for table `task_comments`
--
ALTER TABLE `task_comments`
  ADD CONSTRAINT `task_comments_ibfk_1` FOREIGN KEY (`task_id`) REFERENCES `tasks` (`id`),
  ADD CONSTRAINT `task_comments_ibfk_2` FOREIGN KEY (`parent_comment_id`) REFERENCES `task_comments` (`id`),
  ADD CONSTRAINT `task_comments_ibfk_3` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`);

--
-- Constraints for table `team_members`
--
ALTER TABLE `team_members`
  ADD CONSTRAINT `team_members_ibfk_1` FOREIGN KEY (`team_id`) REFERENCES `teams` (`id`),
  ADD CONSTRAINT `team_members_ibfk_2` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`);

--
-- Constraints for table `user_roles`
--
ALTER TABLE `user_roles`
  ADD CONSTRAINT `user_roles_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`),
  ADD CONSTRAINT `user_roles_ibfk_2` FOREIGN KEY (`role_id`) REFERENCES `roles` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
