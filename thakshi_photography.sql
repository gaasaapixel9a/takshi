-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Jun 29, 2026 at 06:56 AM
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
-- Database: `thakshi_photography`
--

-- --------------------------------------------------------

--
-- Table structure for table `access_requests`
--

CREATE TABLE `access_requests` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `service_id` int(11) NOT NULL,
  `status` enum('pending','approved','rejected','expired') DEFAULT 'pending',
  `request_count` int(11) DEFAULT 1,
  `approved_at` timestamp NULL DEFAULT NULL,
  `expires_at` timestamp NULL DEFAULT NULL,
  `admin_note` varchar(255) DEFAULT NULL,
  `ip_address` varchar(45) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `access_requests`
--

INSERT INTO `access_requests` (`id`, `user_id`, `service_id`, `status`, `request_count`, `approved_at`, `expires_at`, `admin_note`, `ip_address`, `created_at`, `updated_at`) VALUES
(1, 1, 1, 'approved', 2, '2026-06-13 07:35:42', '2026-06-23 07:35:42', NULL, NULL, '2026-06-05 10:05:10', '2026-06-13 07:35:42'),
(2, 1, 2, 'approved', 1, '2026-06-13 07:35:42', '2026-06-23 07:35:42', NULL, NULL, '2026-06-05 10:13:05', '2026-06-13 07:35:42'),
(3, 1, 4, 'approved', 1, '2026-06-13 07:35:42', '2026-06-23 07:35:42', NULL, NULL, '2026-06-06 05:20:27', '2026-06-13 07:35:42'),
(4, 2, 5, 'rejected', 1, '2026-06-06 06:04:53', '2026-06-16 06:04:53', NULL, NULL, '2026-06-06 06:04:39', '2026-06-11 05:28:15'),
(5, 3, 1, 'rejected', 1, '2026-06-11 05:15:20', '2026-06-21 05:15:20', NULL, NULL, '2026-06-11 05:03:25', '2026-06-11 05:28:02'),
(6, 3, 2, 'rejected', 0, '2026-06-11 05:15:20', '2026-06-21 05:15:20', NULL, NULL, '2026-06-11 05:15:20', '2026-06-11 05:28:05'),
(7, 3, 3, 'rejected', 0, '2026-06-11 05:15:20', '2026-06-21 05:15:20', NULL, NULL, '2026-06-11 05:15:20', '2026-06-11 05:28:06'),
(8, 3, 4, 'rejected', 0, '2026-06-11 05:15:20', '2026-06-21 05:15:20', NULL, NULL, '2026-06-11 05:15:20', '2026-06-11 05:28:08'),
(9, 3, 5, 'rejected', 0, '2026-06-11 05:15:20', '2026-06-21 05:15:20', NULL, NULL, '2026-06-11 05:15:20', '2026-06-11 05:28:11'),
(10, 3, 6, 'rejected', 0, '2026-06-11 05:15:20', '2026-06-21 05:15:20', NULL, NULL, '2026-06-11 05:15:20', '2026-06-11 05:28:12'),
(11, 4, 1, 'approved', 1, '2026-06-11 05:29:43', '2026-06-21 05:29:43', NULL, NULL, '2026-06-11 05:29:37', '2026-06-11 05:29:43'),
(12, 1, 3, 'approved', 0, '2026-06-13 07:35:42', '2026-06-23 07:35:42', NULL, NULL, '2026-06-13 07:35:42', '2026-06-13 07:35:42'),
(13, 1, 5, 'approved', 0, '2026-06-13 07:35:42', '2026-06-23 07:35:42', NULL, NULL, '2026-06-13 07:35:42', '2026-06-13 07:35:42'),
(14, 1, 6, 'approved', 0, '2026-06-13 07:35:42', '2026-06-23 07:35:42', NULL, NULL, '2026-06-13 07:35:42', '2026-06-13 07:35:42');

-- --------------------------------------------------------

--
-- Table structure for table `admin_notifications`
--

CREATE TABLE `admin_notifications` (
  `id` int(11) NOT NULL,
  `type` enum('new_request','re_request') DEFAULT 'new_request',
  `user_id` int(11) DEFAULT NULL,
  `request_id` int(11) DEFAULT NULL,
  `message` varchar(255) DEFAULT NULL,
  `is_read` tinyint(1) DEFAULT 0,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `admin_notifications`
--

INSERT INTO `admin_notifications` (`id`, `type`, `user_id`, `request_id`, `message`, `is_read`, `created_at`) VALUES
(1, 'new_request', 1, 1, 'manya is requesting access to Wedding', 1, '2026-06-05 10:05:10'),
(2, 'new_request', 1, 2, 'Bronze is requesting access to New Born', 1, '2026-06-05 10:13:05'),
(3, 'new_request', 1, 3, 'Gaasaa Pixel 9a is requesting access to Maternity', 0, '2026-06-06 05:20:27'),
(4, 'new_request', 2, 4, 'Gaasaa Pixel 9a c is requesting access to Corporate', 0, '2026-06-06 06:04:39'),
(5, 'new_request', 3, 5, 'Gaasaa Pixel 9a is requesting access to Wedding', 0, '2026-06-11 05:03:25'),
(6, 'new_request', 4, 11, 'manya is requesting access to Wedding', 0, '2026-06-11 05:29:37'),
(7, 'new_request', 1, 1, 'manya is requesting access to Wedding', 0, '2026-06-13 07:34:24');

-- --------------------------------------------------------

--
-- Table structure for table `admin_users`
--

CREATE TABLE `admin_users` (
  `id` int(11) NOT NULL,
  `username` varchar(50) NOT NULL,
  `password_hash` varchar(255) NOT NULL,
  `push_subscription` text DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `admin_users`
--

INSERT INTO `admin_users` (`id`, `username`, `password_hash`, `push_subscription`, `created_at`) VALUES
(2, 'admin', '$2y$10$/PFQVMgnAkeR7NssqUl.ye9z3AC92/svK8JKyQsnlSLCAAFg88cS6', NULL, '2026-06-05 10:10:34');

-- --------------------------------------------------------

--
-- Table structure for table `gallery_images`
--

CREATE TABLE `gallery_images` (
  `id` int(11) NOT NULL,
  `service_id` int(11) NOT NULL,
  `subcategory_id` int(11) DEFAULT NULL,
  `filename` varchar(255) NOT NULL,
  `filepath` varchar(500) NOT NULL,
  `filesize` int(11) DEFAULT NULL,
  `width` int(11) DEFAULT NULL,
  `height` int(11) DEFAULT NULL,
  `display_order` int(11) DEFAULT 0,
  `is_active` tinyint(1) DEFAULT 1,
  `uploaded_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `gallery_images`
--

INSERT INTO `gallery_images` (`id`, `service_id`, `subcategory_id`, `filename`, `filepath`, `filesize`, `width`, `height`, `display_order`, `is_active`, `uploaded_at`) VALUES
(1, 1, 1, 'img_6a22a26abb69d7.88393528.png', 'wedding/img_6a22a26abb69d7.88393528.png', 1980644, 1672, 941, 1, 0, '2026-06-05 10:18:18'),
(2, 1, 3, 'img_6a2a47e8481728.72351722.png', 'wedding/img_6a2a47e8481728.72351722.png', 1980644, 1672, 941, 2, 1, '2026-06-11 05:30:16');

-- --------------------------------------------------------

--
-- Table structure for table `services`
--

CREATE TABLE `services` (
  `id` int(11) NOT NULL,
  `slug` varchar(100) NOT NULL,
  `name` varchar(100) NOT NULL,
  `hero_image` varchar(255) DEFAULT NULL,
  `display_order` int(11) DEFAULT 0,
  `is_active` tinyint(1) DEFAULT 1,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `services`
--

INSERT INTO `services` (`id`, `slug`, `name`, `hero_image`, `display_order`, `is_active`, `created_at`) VALUES
(1, 'wedding', 'Wedding', NULL, 1, 1, '2026-06-02 07:43:15'),
(2, 'newborn', 'New Born', NULL, 2, 1, '2026-06-02 07:43:15'),
(3, 'model-shoot', 'Model Shoot', NULL, 3, 1, '2026-06-02 07:43:15'),
(4, 'maternity', 'Maternity', NULL, 4, 1, '2026-06-02 07:43:15'),
(5, 'corporate', 'Corporate', NULL, 5, 1, '2026-06-02 07:43:15'),
(6, 'couple-portraits', 'Couple Portraits', NULL, 6, 1, '2026-06-02 07:43:15');

-- --------------------------------------------------------

--
-- Table structure for table `subcategories`
--

CREATE TABLE `subcategories` (
  `id` int(11) NOT NULL,
  `service_id` int(11) NOT NULL,
  `name` varchar(100) NOT NULL,
  `display_order` int(11) DEFAULT 0,
  `is_active` tinyint(1) DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `subcategories`
--

INSERT INTO `subcategories` (`id`, `service_id`, `name`, `display_order`, `is_active`) VALUES
(1, 1, 'Baby Photoshoot', 1, 0),
(2, 2, 'Side angles', 1, 1),
(3, 1, 'new', 2, 1);

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `name` varchar(150) NOT NULL,
  `phone` varchar(20) NOT NULL,
  `total_visits` int(11) DEFAULT 0,
  `total_request_count` int(11) DEFAULT 0,
  `first_seen` timestamp NOT NULL DEFAULT current_timestamp(),
  `last_seen` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `name`, `phone`, `total_visits`, `total_request_count`, `first_seen`, `last_seen`) VALUES
(1, 'Gaasaa Pixel 9a', '8550833186', 5, 4, '2026-06-05 10:05:10', '2026-06-13 07:43:28'),
(2, 'Gaasaa Pixel 9a c', '9353672514', 1, 1, '2026-06-06 06:04:39', '2026-06-06 06:04:53'),
(3, 'Gaasaa Pixel 9a', '7483573810', 1, 1, '2026-06-11 05:03:25', '2026-06-11 05:15:20'),
(4, 'manya', '8516545623', 1, 1, '2026-06-11 05:29:37', '2026-06-11 05:29:43');

-- --------------------------------------------------------

--
-- Table structure for table `visit_logs`
--

CREATE TABLE `visit_logs` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `service_id` int(11) DEFAULT NULL,
  `page` varchar(255) DEFAULT NULL,
  `ip_address` varchar(45) DEFAULT NULL,
  `user_agent` text DEFAULT NULL,
  `time_spent_seconds` int(11) DEFAULT 0,
  `visited_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `visit_logs`
--

INSERT INTO `visit_logs` (`id`, `user_id`, `service_id`, `page`, `ip_address`, `user_agent`, `time_spent_seconds`, `visited_at`) VALUES
(1, 1, 1, 'access_request', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36', 27, '2026-06-05 10:05:10'),
(2, 1, 1, 'gallery_view', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36', 820, '2026-06-05 10:11:21'),
(3, 1, 2, 'access_request', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36', 842, '2026-06-05 10:13:05'),
(4, 1, 4, 'access_request', '192.168.0.105', 'Mozilla/5.0 (Linux; Android 10; K) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Mobile Safari/537.36', 0, '2026-06-06 05:20:27'),
(5, 2, 5, 'access_request', '192.168.0.105', 'Mozilla/5.0 (Linux; Android 10; K) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Mobile Safari/537.36', 122, '2026-06-06 06:04:39'),
(6, 3, 1, 'access_request', '10.101.8.229', 'Mozilla/5.0 (Linux; Android 10; K) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/149.0.0.0 Mobile Safari/537.36', 0, '2026-06-11 05:03:25'),
(7, 4, 1, 'access_request', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36', 168, '2026-06-11 05:29:37'),
(8, 1, 1, 'access_request', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36', 266, '2026-06-13 07:34:24');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `access_requests`
--
ALTER TABLE `access_requests`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_requests_user` (`user_id`),
  ADD KEY `idx_requests_service` (`service_id`),
  ADD KEY `idx_requests_status` (`status`),
  ADD KEY `idx_requests_uid_sid` (`user_id`,`service_id`);

--
-- Indexes for table `admin_notifications`
--
ALTER TABLE `admin_notifications`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`),
  ADD KEY `request_id` (`request_id`),
  ADD KEY `idx_notif_read` (`is_read`),
  ADD KEY `idx_notif_created` (`created_at`);

--
-- Indexes for table `admin_users`
--
ALTER TABLE `admin_users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `username` (`username`);

--
-- Indexes for table `gallery_images`
--
ALTER TABLE `gallery_images`
  ADD PRIMARY KEY (`id`),
  ADD KEY `subcategory_id` (`subcategory_id`),
  ADD KEY `idx_gallery_service` (`service_id`);

--
-- Indexes for table `services`
--
ALTER TABLE `services`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `slug` (`slug`);

--
-- Indexes for table `subcategories`
--
ALTER TABLE `subcategories`
  ADD PRIMARY KEY (`id`),
  ADD KEY `service_id` (`service_id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `phone` (`phone`);

--
-- Indexes for table `visit_logs`
--
ALTER TABLE `visit_logs`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_visits_user` (`user_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `access_requests`
--
ALTER TABLE `access_requests`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=15;

--
-- AUTO_INCREMENT for table `admin_notifications`
--
ALTER TABLE `admin_notifications`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT for table `admin_users`
--
ALTER TABLE `admin_users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `gallery_images`
--
ALTER TABLE `gallery_images`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `services`
--
ALTER TABLE `services`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `subcategories`
--
ALTER TABLE `subcategories`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `visit_logs`
--
ALTER TABLE `visit_logs`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `access_requests`
--
ALTER TABLE `access_requests`
  ADD CONSTRAINT `access_requests_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `access_requests_ibfk_2` FOREIGN KEY (`service_id`) REFERENCES `services` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `admin_notifications`
--
ALTER TABLE `admin_notifications`
  ADD CONSTRAINT `admin_notifications_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `admin_notifications_ibfk_2` FOREIGN KEY (`request_id`) REFERENCES `access_requests` (`id`) ON DELETE SET NULL;

--
-- Constraints for table `gallery_images`
--
ALTER TABLE `gallery_images`
  ADD CONSTRAINT `gallery_images_ibfk_1` FOREIGN KEY (`service_id`) REFERENCES `services` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `gallery_images_ibfk_2` FOREIGN KEY (`subcategory_id`) REFERENCES `subcategories` (`id`) ON DELETE SET NULL;

--
-- Constraints for table `subcategories`
--
ALTER TABLE `subcategories`
  ADD CONSTRAINT `subcategories_ibfk_1` FOREIGN KEY (`service_id`) REFERENCES `services` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `visit_logs`
--
ALTER TABLE `visit_logs`
  ADD CONSTRAINT `visit_logs_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
