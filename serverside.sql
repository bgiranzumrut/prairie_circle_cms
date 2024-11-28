-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Nov 28, 2024 at 07:35 AM
-- Server version: 10.4.32-MariaDB
-- PHP Version: 8.0.30

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `serverside`
--

-- --------------------------------------------------------

--
-- Table structure for table `categories`
--

CREATE TABLE `categories` (
  `id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `description` text DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `categories`
--

INSERT INTO `categories` (`id`, `name`, `description`, `created_at`) VALUES
(3, 'Workshops', 'Online seminars and discussions and', '2024-11-12 05:03:40'),
(16, 'Cultural Events', 'Events that celebrate cultural heritage, traditions, or artistic performances.', '2024-11-22 21:52:54'),
(17, 'Community Gatherings', ' Social events aimed at fostering connections among community members', '2024-11-22 21:53:12'),
(18, 'Educational Seminars', 'Informative presentations or lectures on various educational topics.', '2024-11-22 21:53:24'),
(19, 'Volunteer Activities', 'Opportunities for individuals to contribute to community projects and causes.', '2024-11-22 21:53:33');

-- --------------------------------------------------------

--
-- Table structure for table `events`
--

CREATE TABLE `events` (
  `id` int(11) NOT NULL,
  `title` varchar(255) NOT NULL,
  `description` text NOT NULL,
  `category_id` int(11) NOT NULL,
  `event_date` date NOT NULL,
  `status` enum('upcoming','ongoing','completed') DEFAULT 'upcoming',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `image_path` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `events`
--

INSERT INTO `events` (`id`, `title`, `description`, `category_id`, `event_date`, `status`, `created_at`, `updated_at`, `image_path`) VALUES
(8, 'Intro to Coding Workshop', 'Learn the basics of coding in this beginner-friendly workshop. No prior experience needed.', 3, '2025-12-22', 'upcoming', '2024-11-21 03:00:46', '2024-11-22 21:55:36', 'uploads/6740fdd8076db-chris-ried-ieic5Tq8YMk-unsplash.jpg'),
(20, 'Multicultural Food Festival', ' Enjoy delicious dishes from around the world and celebrate cultural diversity.', 16, '2024-12-25', 'upcoming', '2024-11-21 22:17:35', '2024-11-22 21:58:33', 'uploads/6740fe892753e-ali-inay-y3aP9oo9Pjc-unsplash.jpg'),
(28, 'Community Meet and Greet', 'A casual event to meet and network with fellow community members.', 17, '2024-11-26', 'upcoming', '2024-11-22 22:00:26', '2024-11-22 22:00:26', 'uploads/events/1732312826_brooke-cagle--uHVRvDr7pg-unsplash.jpg'),
(29, 'Financial Planning 101', 'An educational seminar to help you manage your finances effectively.', 18, '2024-12-05', 'upcoming', '2024-11-22 22:01:13', '2024-11-22 22:01:13', 'uploads/events/1732312873_ibrahim-rifath-OApHds2yEGQ-unsplash.jpg'),
(30, 'Park Clean-Up Drive', 'Join us in cleaning up the local park to make it a more enjoyable space for everyone.', 19, '2024-12-07', 'upcoming', '2024-11-22 22:02:15', '2024-11-22 22:02:15', 'uploads/events/1732312935_kelly-sikkema-eSQNlt0QmXI-unsplash.jpg'),
(31, 'Traditional Music Night', 'Experience a night of traditional music performances from various cultures around the world.', 16, '2024-12-01', 'upcoming', '2024-11-22 22:03:29', '2024-11-22 22:03:43', 'uploads/6740ffbf510ab-derek-truninger-uLitVttkC7o-unsplash.jpg'),
(32, 'Stress Management Seminar', 'Learn techniques to reduce stress and maintain a balanced lifestyle in this informative session.', 18, '2024-12-04', 'upcoming', '2024-11-22 22:04:59', '2024-11-22 22:04:59', 'uploads/events/1732313099_kelly-sikkema-1TLgfpPfDZA-unsplash.jpg');

-- --------------------------------------------------------

--
-- Table structure for table `event_comments`
--

CREATE TABLE `event_comments` (
  `id` int(11) NOT NULL,
  `event_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `comment` text NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `event_comments`
--

INSERT INTO `event_comments` (`id`, `event_id`, `user_id`, `comment`, `created_at`) VALUES
(1, 28, 14, 'xdcc', '2024-11-28 00:09:31'),
(2, 28, 14, 'cdd', '2024-11-28 00:09:53'),
(3, 28, 14, 'sdc ', '2024-11-28 00:14:26'),
(4, 28, 4, 'CS', '2024-11-28 00:15:32'),
(5, 28, 4, 'xsac', '2024-11-28 00:37:24'),
(6, 28, 4, 'CDS', '2024-11-28 02:08:18'),
(7, 28, 4, 'Merhaba', '2024-11-28 02:08:48'),
(9, 28, 4, 'MQGKB', '2024-11-28 04:35:05'),
(10, 28, 4, 'hey', '2024-11-28 04:35:30'),
(11, 30, 14, 'I will be there!', '2024-11-28 06:34:16');

-- --------------------------------------------------------

--
-- Table structure for table `registrations`
--

CREATE TABLE `registrations` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `event_id` int(11) NOT NULL,
  `registered_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `registrations`
--

INSERT INTO `registrations` (`id`, `user_id`, `event_id`, `registered_at`) VALUES
(4, 14, 28, '2024-11-27 23:28:45'),
(19, 14, 31, '2024-11-28 04:27:13'),
(21, 4, 8, '2024-11-28 04:36:46'),
(22, 4, 31, '2024-11-28 05:41:14');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL,
  `role` enum('admin','event_coordinator','registered_user') DEFAULT 'registered_user',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `name`, `email`, `password`, `role`, `created_at`) VALUES
(4, 'Alice', 'alice@example.com', '$2y$10$v9xnxYRmC7kDknWeLVHlM.BTzsSHgQPrQRckd7lMIBgo8hWRfxTqm', 'admin', '2024-11-12 05:23:21'),
(6, 'John Doe', 'john.doe@example.com', '$2y$10$E9yTICkB9aDZPS1fKD.aIuEd/CrjWhniWYZQGpBsAlHOfekNjHvY2', 'admin', '2024-11-12 05:48:38'),
(14, 'Busra', 'busragiran@gmail.com', '$2y$10$K.wa8jawS2T2Yf6iODoYuevMoTceFIKHY.mAq8mdcBIaN6A6.se86', 'registered_user', '2024-11-15 20:30:58'),
(20, 'eren', 'eren@gmail.com', '$2y$10$N6xTWTGovgAo5ahs.X9nzeSGhbwPdLu/718moODbG5tVNhonEQynW', 'registered_user', '2024-11-16 21:05:01'),
(23, 'badem', 'badem@gmail.com', '$2y$10$NkTBKl4llqBFkRup8JyJF.hURXXUpjfLxUnmIDq64eQIEGYPftKe6', 'registered_user', '2024-11-22 17:53:48'),
(24, 'melek', 'melek@gmail.com', '$2y$10$mfe4rPBf0LYtyIRPrw7nGeJ.j6B9Q4IiALKnSFTE4upEYFrc5Umiq', 'registered_user', '2024-11-28 01:33:32'),
(25, 'Bados', 'bados@gmail.com', '$2y$10$AMPkoxP6Lkkc0ibqRNk9teBy3CQ/a62uCZ/oaDpiaXVrUW1ZFNilO', 'registered_user', '2024-11-28 06:27:18');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `categories`
--
ALTER TABLE `categories`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `events`
--
ALTER TABLE `events`
  ADD PRIMARY KEY (`id`),
  ADD KEY `category_id` (`category_id`);

--
-- Indexes for table `event_comments`
--
ALTER TABLE `event_comments`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`),
  ADD KEY `event_comments_ibfk_1` (`event_id`);

--
-- Indexes for table `registrations`
--
ALTER TABLE `registrations`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `unique_user_event` (`user_id`,`event_id`),
  ADD KEY `user_id` (`user_id`),
  ADD KEY `event_id` (`event_id`);

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
-- AUTO_INCREMENT for table `categories`
--
ALTER TABLE `categories`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=27;

--
-- AUTO_INCREMENT for table `events`
--
ALTER TABLE `events`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=42;

--
-- AUTO_INCREMENT for table `event_comments`
--
ALTER TABLE `event_comments`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- AUTO_INCREMENT for table `registrations`
--
ALTER TABLE `registrations`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=24;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=26;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `events`
--
ALTER TABLE `events`
  ADD CONSTRAINT `events_ibfk_1` FOREIGN KEY (`category_id`) REFERENCES `categories` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `event_comments`
--
ALTER TABLE `event_comments`
  ADD CONSTRAINT `event_comments_ibfk_1` FOREIGN KEY (`event_id`) REFERENCES `events` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `event_comments_ibfk_2` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`);

--
-- Constraints for table `registrations`
--
ALTER TABLE `registrations`
  ADD CONSTRAINT `registrations_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `registrations_ibfk_2` FOREIGN KEY (`event_id`) REFERENCES `events` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
