-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Feb 10, 2025 at 09:41 AM
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
-- Database: `dormitory_management`
--

-- --------------------------------------------------------

--
-- Table structure for table `bills`
--

CREATE TABLE `bills` (
  `id` int(11) NOT NULL,
  `tenant_id` int(11) NOT NULL,
  `room_id` int(11) NOT NULL,
  `month` int(11) NOT NULL,
  `year` int(11) NOT NULL,
  `rent_fee` decimal(10,2) NOT NULL,
  `water_bill` decimal(10,2) NOT NULL,
  `electricity_bill` decimal(10,2) NOT NULL,
  `total` decimal(10,2) GENERATED ALWAYS AS (`rent_fee` + `water_bill` + `electricity_bill`) STORED,
  `paid_status` enum('unpaid','paid') DEFAULT 'unpaid',
  `payment_slip` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `finance`
--

CREATE TABLE `finance` (
  `id` int(11) NOT NULL,
  `date` date NOT NULL,
  `income` decimal(10,2) NOT NULL,
  `expense` decimal(10,2) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `finance`
--

INSERT INTO `finance` (`id`, `date`, `income`, `expense`) VALUES
(1, '2025-02-10', 3000.00, 9000.00),
(2, '2025-01-07', 12312.00, 123444.00);

-- --------------------------------------------------------

--
-- Table structure for table `maintenance_requests`
--

CREATE TABLE `maintenance_requests` (
  `id` int(11) NOT NULL,
  `tenant_id` int(11) NOT NULL,
  `room_id` int(11) NOT NULL,
  `issue` text NOT NULL,
  `request_date` timestamp NOT NULL DEFAULT current_timestamp(),
  `assigned_staff_id` int(11) DEFAULT NULL,
  `status` enum('pending','in_progress','completed') DEFAULT 'pending'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `rooms`
--

CREATE TABLE `rooms` (
  `id` int(11) NOT NULL,
  `room_number` varchar(10) NOT NULL,
  `type` enum('standard','deluxe','vip') NOT NULL,
  `price` decimal(10,2) NOT NULL,
  `status` enum('available','reserved','occupied') DEFAULT 'available',
  `tenant_id` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `rooms`
--

INSERT INTO `rooms` (`id`, `room_number`, `type`, `price`, `status`, `tenant_id`) VALUES
(1, '201', 'standard', 3500.00, 'occupied', 3),
(2, '202', 'standard', 3500.00, 'occupied', 4),
(3, '203', 'standard', 3500.00, 'occupied', 5),
(4, '204', 'standard', 3500.00, 'occupied', 6),
(5, '205', 'standard', 3500.00, 'available', NULL),
(6, '206', 'standard', 3500.00, 'available', NULL),
(7, '207', 'standard', 3500.00, 'reserved', NULL),
(8, '208', 'standard', 3500.00, 'available', NULL),
(9, '301', 'standard', 3500.00, 'available', 3),
(10, '302', 'standard', 3500.00, 'available', NULL),
(11, '303', 'standard', 3500.00, 'available', NULL),
(12, '304', 'standard', 3500.00, 'available', NULL),
(13, '305', 'standard', 3500.00, 'available', NULL),
(14, '306', 'standard', 3500.00, 'available', NULL),
(15, '307', 'standard', 3500.00, 'available', NULL),
(16, '308', 'standard', 3500.00, 'available', NULL),
(17, '401', 'standard', 3500.00, 'available', 3),
(18, '402', 'standard', 3500.00, 'available', NULL),
(19, '403', 'standard', 3500.00, 'available', NULL),
(20, '404', 'standard', 3500.00, 'available', NULL),
(21, '405', 'standard', 3500.00, 'available', NULL),
(22, '406', 'standard', 3500.00, 'available', NULL),
(23, '407', 'standard', 3500.00, 'available', NULL),
(24, '408', 'standard', 3500.00, 'available', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `username` varchar(50) NOT NULL,
  `password` varchar(255) NOT NULL,
  `role` enum('admin','owner','staff','tenant') NOT NULL,
  `full_name` varchar(100) NOT NULL,
  `phone` varchar(20) DEFAULT NULL,
  `email` varchar(100) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `username`, `password`, `role`, `full_name`, `phone`, `email`, `created_at`) VALUES
(1, 'admin', '$2y$10$4QL0DA6uVMmJo8ThCnaQ3u.hPeGL.8.FO27RKUoxYXUVZMcJmxxBa', 'admin', 'admin', '0987439887', 'admin@admin', '2025-02-09 04:49:03'),
(2, 'Saff', 'Sraff', 'staff', 'Saff Saff', '0000000000', 'Sraff@Sraff', '2025-02-10 08:23:14'),
(3, '201', '$2y$10$R0DhBgfCIYHFu/gFKcwWQ.F2V21GwSXkGKBDF75483pgEhJFMRkB6', 'tenant', 'Pepo', '0987439887', '201@mail.com', '2025-02-09 04:58:58'),
(4, '202', '$2y$10$R0DhBgfCIYHFu/gFKcwWQ.F2V21GwSXkGKBDF75483pgEhJFMRkB6', 'tenant', '202 202', '0987439887', '202@mail.com', '2025-02-09 04:58:58'),
(5, '203', '$2y$10$R0DhBgfCIYHFu/gFKcwWQ.F2V21GwSXkGKBDF75483pgEhJFMRkB6', 'tenant', '203 203', '0987439887', '203@mail.com', '2025-02-09 04:58:58'),
(6, '204', '$2y$10$j66rslDNxPL6gWrNSbSgHOkGUp6hqQV59EHTVJyrlbQ.U6sw0q7OO', 'tenant', '204 204', '0987439887', '204@mail.com', '2025-02-10 08:21:59'),
(7, '205', '$2y$10$GLJV.WK/VBfJwC7.qERdROAvVtcSxLretAji0EDX01KSIS6qxztkS', 'tenant', '205 205', '0648423074', '205@mail.com', '2025-02-10 08:23:27'),
(8, '206', '$2y$10$5TCAGNL3iNF2UV1CrOgNfewp6DnRJrbqYBYUYKymQXf3lmy10r3pS', 'tenant', '206 206', '0945984558', '206@mail.com', '2025-02-10 08:23:35'),
(9, '207', '$2y$10$X2QsFKl1Qs8Na96rY4Sm2u0AOHmQZ4ZzUHo3zruNP49TwnRS55UrO', 'tenant', '207 207', '0648423074', '207@mail.com', '2025-02-10 08:24:47'),
(10, '208', '$2y$10$Q/mGyHn/mu6n69Dgg6c4b.VkWiX4OcQ0Z5tsJR/0RfaatUz9hw.rq', 'tenant', '208 208', '0626623874', '208@mail.com', '2025-02-10 08:24:52'),
(12, '301', '$2y$10$Q/mGyHn/mu6n69Dgg6c4b.VkWiX4OcQ0Z5tsJR/0RfaatUz9hw.rq', 'tenant', '301 301', '0626623874', '301@mail.com', '2025-02-10 08:24:52'),
(13, '302', '$2y$10$0yWX47LAX0hOwZUv0BnUnOeGuOg8SzCw.oHkuYz0vUKrdCTdEE4bi', 'tenant', '302 302', '0945984557', '302@mail.com', '2025-02-10 08:27:32'),
(14, '303', '$2y$10$LWBousYouevzp3l2sEzH3eDHVl4Z7h8W83Xz3lzRvb4zfCYOzkkiW', 'tenant', '303 303', '0987439887', '303@mail.com', '2025-02-10 08:28:08'),
(15, '304', '$2y$10$3TfoWhdXUk9SZ988iD91kO/6jaoZKdwE45G0iHWW9a4fyp7MLgdt2', 'tenant', '304 304', '0888658147', '304@mail.com', '2025-02-10 08:28:26'),
(16, '305', '$2y$10$CFuCXOuf3WKkoP0uPV0kjen8J2x.81QaJWJk0mvCtKOF2A9UY9H8q', 'tenant', '305 305', '0648423074', '305@mail.com', '2025-02-10 08:28:36'),
(17, '306', '$2y$10$xROJXXGs/5E/OGqO5AVsueRwV.DpMVCVdY9hkfOH4oKQ2fSqK2u.e', 'tenant', '306 306', '0985555556', '306@mail.com', '2025-02-10 08:28:51'),
(18, '307', '$2y$10$8aWZVr6Xc8DlCo6IZaHYu.K7/TDdFo2oAMWmkedBxmHpnPDgsjmve', 'tenant', '307 307', '0648423074', '307@mail.com', '2025-02-10 08:28:56'),
(19, '308', '$2y$10$6soyCDYc..bptu0ziPdBruB1Z6fvDMsmJXrAoPRVZZ82ZyFHF6nzW', 'tenant', '308 308', '0985555556', '308@mail.com', '2025-02-10 08:29:17'),
(20, '401', '$2y$10$Q/mGyHn/mu6n69Dgg6c4b.VkWiX4OcQ0Z5tsJR/0RfaatUz9hw.rq', 'tenant', '401 401', '0626623874', '401@mail.com', '2025-02-10 08:24:52'),
(21, '402', '$2y$10$0yWX47LAX0hOwZUv0BnUnOeGuOg8SzCw.oHkuYz0vUKrdCTdEE4bi', 'tenant', '402 402', '0945984557', '402@mail.com', '2025-02-10 08:27:32'),
(22, '403', '$2y$10$LWBousYouevzp3l2sEzH3eDHVl4Z7h8W83Xz3lzRvb4zfCYOzkkiW', 'tenant', '403 403', '0987439887', '403@mail.com', '2025-02-10 08:28:08'),
(23, '404', '$2y$10$3TfoWhdXUk9SZ988iD91kO/6jaoZKdwE45G0iHWW9a4fyp7MLgdt2', 'tenant', '404 404', '0888658147', '404@mail.com', '2025-02-10 08:28:26'),
(24, '405', '$2y$10$CFuCXOuf3WKkoP0uPV0kjen8J2x.81QaJWJk0mvCtKOF2A9UY9H8q', 'tenant', '405 405', '0648423074', '405@mail.com', '2025-02-10 08:28:36'),
(25, '406', '$2y$10$xROJXXGs/5E/OGqO5AVsueRwV.DpMVCVdY9hkfOH4oKQ2fSqK2u.e', 'tenant', '406 406', '0985555556', '406@mail.com', '2025-02-10 08:28:51'),
(26, '407', '$2y$10$8aWZVr6Xc8DlCo6IZaHYu.K7/TDdFo2oAMWmkedBxmHpnPDgsjmve', 'tenant', '407 307', '0648423074', '407@mail.com', '2025-02-10 08:28:56'),
(27, '408', '$2y$10$6soyCDYc..bptu0ziPdBruB1Z6fvDMsmJXrAoPRVZZ82ZyFHF6nzW', 'tenant', '408 408', '0985555556', '408@mail.com', '2025-02-10 08:29:17');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `bills`
--
ALTER TABLE `bills`
  ADD PRIMARY KEY (`id`),
  ADD KEY `tenant_id` (`tenant_id`),
  ADD KEY `room_id` (`room_id`);

--
-- Indexes for table `finance`
--
ALTER TABLE `finance`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `maintenance_requests`
--
ALTER TABLE `maintenance_requests`
  ADD PRIMARY KEY (`id`),
  ADD KEY `tenant_id` (`tenant_id`),
  ADD KEY `assigned_staff_id` (`assigned_staff_id`);

--
-- Indexes for table `rooms`
--
ALTER TABLE `rooms`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `room_number` (`room_number`),
  ADD KEY `tenant_id` (`tenant_id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `username` (`username`),
  ADD UNIQUE KEY `email` (`email`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `bills`
--
ALTER TABLE `bills`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `finance`
--
ALTER TABLE `finance`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `maintenance_requests`
--
ALTER TABLE `maintenance_requests`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `rooms`
--
ALTER TABLE `rooms`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=25;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=28;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `bills`
--
ALTER TABLE `bills`
  ADD CONSTRAINT `bills_ibfk_1` FOREIGN KEY (`tenant_id`) REFERENCES `users` (`id`),
  ADD CONSTRAINT `bills_ibfk_2` FOREIGN KEY (`room_id`) REFERENCES `rooms` (`id`);

--
-- Constraints for table `maintenance_requests`
--
ALTER TABLE `maintenance_requests`
  ADD CONSTRAINT `maintenance_requests_ibfk_1` FOREIGN KEY (`tenant_id`) REFERENCES `users` (`id`),
  ADD CONSTRAINT `maintenance_requests_ibfk_2` FOREIGN KEY (`assigned_staff_id`) REFERENCES `users` (`id`);

--
-- Constraints for table `rooms`
--
ALTER TABLE `rooms`
  ADD CONSTRAINT `rooms_ibfk_1` FOREIGN KEY (`tenant_id`) REFERENCES `users` (`id`) ON DELETE SET NULL;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
