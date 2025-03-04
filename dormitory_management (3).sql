-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Mar 04, 2025 at 04:46 AM
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
-- Table structure for table `events`
--

CREATE TABLE `events` (
  `id` int(11) NOT NULL,
  `event_date` date NOT NULL,
  `title` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `events`
--

INSERT INTO `events` (`id`, `event_date`, `title`) VALUES
(2, '2025-03-11', 'asdasdasdadsd'),
(4, '2025-03-10', 'asdasd'),
(5, '2025-03-29', 'ฟฟฟฟฟฟฟฟฟฟฟฟฟฟฟฟฟฟฟฟฟฟฟฟฟฟฟฟฟฟฟฟฟฟฟฟฟฟฟฟฟฟฟฟฟฟฟฟฟฟฟฟฟฟฟฟฟฟฟฟฟฟฟฟฟฟฟฟฟฟฟฟฟฟฟฟฟฟฟฟฟฟฟฟฟฟฟฟฟฟฟฟฟฟฟฟฟฟฟฟฟฟฟฟฟฟฟฟฟฟฟฟฟฟฟฟฟฟฟฟฟฟฟฟฟฟฟฟฟฟฟฟฟฟฟฟฟฟฟฟฟฟฟฟฟฟฟฟฟฟฟฟฟฟฟฟฟฟฟฟฟฟฟฟฟฟฟฟฟฟฟฟฟฟฟฟฟฟฟฟฟฟฟฟฟฟฟฟฟฟฟฟฟฟฟฟฟฟฟฟฟฟฟฟฟฟฟฟฟฟฟฟฟฟฟฟฟฟฟฟฟฟฟฟฟฟฟฟฟฟฟฟฟฟฟฟฟฟฟฟฟฟฟฟฟฟฟฟฟฟฟฟฟฟฟ');

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
(2, '2025-01-07', 12312.00, 123444.00),
(3, '2025-03-31', 200000.00, 70000.00),
(4, '2026-01-31', 200000.00, 70000.00),
(5, '2025-04-10', 200000.00, 70000.00),
(6, '2025-05-01', 3000.00, 9000.00),
(7, '2025-06-12', 12312.00, 123444.00),
(8, '2025-07-16', 200000.00, 70000.00),
(9, '2026-08-12', 200000.00, 70000.00),
(10, '2025-09-11', 200000.00, 70000.00),
(11, '2025-10-09', 12312.00, 123444.00),
(12, '2025-11-20', 200000.00, 70000.00),
(13, '2026-12-24', 200000.00, 70000.00);

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

--
-- Dumping data for table `maintenance_requests`
--

INSERT INTO `maintenance_requests` (`id`, `tenant_id`, `room_id`, `issue`, `request_date`, `assigned_staff_id`, `status`) VALUES
(14, 3, 1, 'ไฟไหม้มมมมมมมมมมมมมมมมมมมมมมมมมมมมมมมมมม\r\n', '2025-02-17 13:06:30', 1, 'in_progress'),
(15, 3, 1, 'asdasdasdasd', '2025-02-17 13:09:17', 1, 'in_progress');

-- --------------------------------------------------------

--
-- Table structure for table `meter`
--

CREATE TABLE `meter` (
  `id` int(11) NOT NULL,
  `water` int(20) DEFAULT NULL,
  `electricity` int(20) DEFAULT NULL,
  `Date` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `meter`
--

INSERT INTO `meter` (`id`, `water`, `electricity`, `Date`) VALUES
(1, 100, 7, '2025-02-18 03:07:55');

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
  `tenant_id` int(11) DEFAULT NULL,
  `Date of Stay` int(11) DEFAULT NULL,
  `Expiration Date` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `rooms`
--

INSERT INTO `rooms` (`id`, `room_number`, `type`, `price`, `status`, `tenant_id`, `Date of Stay`, `Expiration Date`) VALUES
(1, '201', 'standard', 3500.00, 'occupied', 3, 2025, 2026),
(2, '202', 'standard', 3500.00, 'occupied', 4, 2025, 2026),
(3, '203', 'standard', 3500.00, 'occupied', 5, 2025, 2026),
(4, '204', 'standard', 3500.00, 'occupied', 6, 2025, 2026),
(5, '205', 'standard', 3500.00, 'occupied', 7, 2025, 2026),
(6, '206', 'standard', 3500.00, 'occupied', 8, 2025, 2026),
(7, '207', 'standard', 3500.00, 'reserved', NULL, NULL, NULL),
(8, '208', 'standard', 3500.00, 'available', NULL, NULL, NULL),
(9, '301', 'standard', 3500.00, 'available', NULL, NULL, NULL),
(10, '302', 'standard', 3500.00, 'available', NULL, NULL, NULL),
(11, '303', 'standard', 3500.00, 'available', NULL, NULL, NULL),
(12, '304', 'standard', 3500.00, 'available', NULL, NULL, NULL),
(13, '305', 'standard', 3500.00, 'available', NULL, NULL, NULL),
(14, '306', 'standard', 3500.00, 'available', NULL, NULL, NULL),
(15, '307', 'standard', 3500.00, 'available', NULL, NULL, NULL),
(16, '308', 'standard', 3500.00, 'available', NULL, NULL, NULL),
(17, '401', 'standard', 3500.00, 'available', NULL, NULL, NULL),
(18, '402', 'standard', 3500.00, 'available', NULL, NULL, NULL),
(19, '403', 'standard', 3500.00, 'available', NULL, NULL, NULL),
(20, '404', 'standard', 3500.00, 'available', NULL, NULL, NULL),
(21, '405', 'standard', 3500.00, 'available', NULL, NULL, NULL),
(22, '406', 'standard', 3500.00, 'available', NULL, NULL, NULL),
(23, '407', 'standard', 3500.00, 'available', NULL, NULL, NULL),
(24, '408', 'standard', 3500.00, 'available', NULL, NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `username` varchar(50) NOT NULL,
  `password` varchar(255) NOT NULL,
  `role` enum('admin','staff','tenant') NOT NULL,
  `full_name` varchar(100) NOT NULL,
  `phone` varchar(20) DEFAULT NULL,
  `email` varchar(100) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `IDCard` int(200) DEFAULT NULL,
  `Img` varchar(200) DEFAULT NULL,
  `idcard_img` varchar(200) DEFAULT NULL,
  `charter` varchar(200) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `username`, `password`, `role`, `full_name`, `phone`, `email`, `created_at`, `IDCard`, `Img`, `idcard_img`, `charter`) VALUES
(1, 'admin', '$2y$10$4QL0DA6uVMmJo8ThCnaQ3u.hPeGL.8.FO27RKUoxYXUVZMcJmxxBa', 'admin', 'admin admin', '0000000000', 'admin@admin', '2025-02-09 04:49:03', 2147483647, 'LINE_ALBUM_ไบเทค_250209_1.jpg', '', '65-ch8-การเลือกตัวอย่าง.pdf'),
(2, 'Saff', '$2y$10$odcEG7BEKRMGc38hGgLB4.BQQnm/FcykSDUFBhbKGSMWtBjExzwVO', 'staff', 'Saff Saff', '1111111111', 'Saff@Saff', '2025-02-10 08:23:14', 2147483647, '', '', 'บทที่ 1 ระบบจัดการหอพัก กรณีศึกษาหอพัก บ้าน.pdf'),
(3, '201', '$2y$10$R0DhBgfCIYHFu/gFKcwWQ.F2V21GwSXkGKBDF75483pgEhJFMRkB6', 'tenant', 'Pepo', '0987439887', '201@mail.com', '2025-02-09 04:58:58', 2147483647, '67b3459726876.jpg', '', '67b3459726f4a.p67b3459726f4a.pdf'),
(4, '202', '$2y$10$R0DhBgfCIYHFu/gFKcwWQ.F2V21GwSXkGKBDF75483pgEhJFMRkB6', 'tenant', '202 202', '0987439887', '202@mail.com', '2025-02-09 04:58:58', 2147483647, 'IMG_3576.JPG', '', '65-4X 654230XXX-ผลการประเมินความพึงพอใจ.xlsx'),
(5, '203', '$2y$10$R0DhBgfCIYHFu/gFKcwWQ.F2V21GwSXkGKBDF75483pgEhJFMRkB6', 'tenant', '203 203', '0987439887', '203@mail.com', '2025-02-09 04:58:58', 2147483647, '1730994810_21d9baec7bc4ed46b70a.png', '', 'ร้านดรีมวันไอที.pdf'),
(6, '204', '$2y$10$j66rslDNxPL6gWrNSbSgHOkGUp6hqQV59EHTVJyrlbQ.U6sw0q7OO', 'tenant', '204 204', '1111111111', '204@mail.com', '2025-02-10 08:21:59', 2147483647, '', '', '65-41 654230018-คำถามทบทวนบทที่ 8-2.pdf'),
(7, '205', '$2y$10$GLJV.WK/VBfJwC7.qERdROAvVtcSxLretAji0EDX01KSIS6qxztkS', 'tenant', '205 205', '0648423074', '205@mail.com', '2025-02-10 08:23:27', NULL, '', '', NULL),
(8, '206', '$2y$10$5TCAGNL3iNF2UV1CrOgNfewp6DnRJrbqYBYUYKymQXf3lmy10r3pS', 'tenant', '206 206', '0945984558', '206@mail.com', '2025-02-10 08:23:35', NULL, '', '', NULL),
(9, '207', '$2y$10$X2QsFKl1Qs8Na96rY4Sm2u0AOHmQZ4ZzUHo3zruNP49TwnRS55UrO', 'tenant', '207 207', '0648423074', '207@mail.com', '2025-02-10 08:24:47', NULL, '', '', NULL),
(10, '208', '$2y$10$Q/mGyHn/mu6n69Dgg6c4b.VkWiX4OcQ0Z5tsJR/0RfaatUz9hw.rq', 'tenant', '208 208', '0626623874', '208@mail.com', '2025-02-10 08:24:52', NULL, '', '', NULL),
(12, '301', '$2y$10$Q/mGyHn/mu6n69Dgg6c4b.VkWiX4OcQ0Z5tsJR/0RfaatUz9hw.rq', 'tenant', '301 301', '0626623874', '301@mail.com', '2025-02-10 08:24:52', NULL, '', '', NULL),
(13, '302', '$2y$10$0yWX47LAX0hOwZUv0BnUnOeGuOg8SzCw.oHkuYz0vUKrdCTdEE4bi', 'tenant', '302 302', '0945984557', '302@mail.com', '2025-02-10 08:27:32', NULL, '', '', NULL),
(14, '303', '$2y$10$LWBousYouevzp3l2sEzH3eDHVl4Z7h8W83Xz3lzRvb4zfCYOzkkiW', 'tenant', '303 303', '0987439887', '303@mail.com', '2025-02-10 08:28:08', NULL, '', '', NULL),
(15, '304', '$2y$10$3TfoWhdXUk9SZ988iD91kO/6jaoZKdwE45G0iHWW9a4fyp7MLgdt2', 'tenant', '304 304', '0888658147', '304@mail.com', '2025-02-10 08:28:26', NULL, '', '', NULL),
(16, '305', '$2y$10$CFuCXOuf3WKkoP0uPV0kjen8J2x.81QaJWJk0mvCtKOF2A9UY9H8q', 'tenant', '305 305', '0648423074', '305@mail.com', '2025-02-10 08:28:36', NULL, '', '', NULL),
(17, '306', '$2y$10$xROJXXGs/5E/OGqO5AVsueRwV.DpMVCVdY9hkfOH4oKQ2fSqK2u.e', 'tenant', '306 306', '0985555556', '306@mail.com', '2025-02-10 08:28:51', NULL, '', '', NULL),
(18, '307', '$2y$10$8aWZVr6Xc8DlCo6IZaHYu.K7/TDdFo2oAMWmkedBxmHpnPDgsjmve', 'tenant', '307 307', '0648423074', '307@mail.com', '2025-02-10 08:28:56', NULL, '', '', NULL),
(19, '308', '$2y$10$6soyCDYc..bptu0ziPdBruB1Z6fvDMsmJXrAoPRVZZ82ZyFHF6nzW', 'tenant', '308 308', '0985555556', '308@mail.com', '2025-02-10 08:29:17', NULL, '', '', NULL),
(20, '401', '$2y$10$Q/mGyHn/mu6n69Dgg6c4b.VkWiX4OcQ0Z5tsJR/0RfaatUz9hw.rq', 'tenant', '401 401', '0626623874', '401@mail.com', '2025-02-10 08:24:52', NULL, '', '', NULL),
(21, '402', '$2y$10$0yWX47LAX0hOwZUv0BnUnOeGuOg8SzCw.oHkuYz0vUKrdCTdEE4bi', 'tenant', '402 402', '0945984557', '402@mail.com', '2025-02-10 08:27:32', NULL, '', '', NULL),
(22, '403', '$2y$10$LWBousYouevzp3l2sEzH3eDHVl4Z7h8W83Xz3lzRvb4zfCYOzkkiW', 'tenant', '403 403', '0987439887', '403@mail.com', '2025-02-10 08:28:08', NULL, '', '', NULL),
(23, '404', '$2y$10$3TfoWhdXUk9SZ988iD91kO/6jaoZKdwE45G0iHWW9a4fyp7MLgdt2', 'tenant', '404 404', '0888658147', '404@mail.com', '2025-02-10 08:28:26', NULL, '', '', NULL),
(24, '405', '$2y$10$CFuCXOuf3WKkoP0uPV0kjen8J2x.81QaJWJk0mvCtKOF2A9UY9H8q', 'tenant', '405 405', '0648423074', '405@mail.com', '2025-02-10 08:28:36', NULL, '', '', NULL),
(25, '406', '$2y$10$xROJXXGs/5E/OGqO5AVsueRwV.DpMVCVdY9hkfOH4oKQ2fSqK2u.e', 'tenant', '406 406', '0985555556', '406@mail.com', '2025-02-10 08:28:51', NULL, '', '', NULL),
(26, '407', '$2y$10$8aWZVr6Xc8DlCo6IZaHYu.K7/TDdFo2oAMWmkedBxmHpnPDgsjmve', 'tenant', '407 307', '0648423074', '407@mail.com', '2025-02-10 08:28:56', NULL, '', '', NULL),
(27, '408', '$2y$10$6soyCDYc..bptu0ziPdBruB1Z6fvDMsmJXrAoPRVZZ82ZyFHF6nzW', 'tenant', '408 408', '0985555556', '408@mail.com', '2025-02-10 08:29:17', NULL, '', '', NULL);

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
-- Indexes for table `events`
--
ALTER TABLE `events`
  ADD PRIMARY KEY (`id`);

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
-- Indexes for table `meter`
--
ALTER TABLE `meter`
  ADD PRIMARY KEY (`id`);

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
-- AUTO_INCREMENT for table `events`
--
ALTER TABLE `events`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `finance`
--
ALTER TABLE `finance`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=14;

--
-- AUTO_INCREMENT for table `maintenance_requests`
--
ALTER TABLE `maintenance_requests`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=16;

--
-- AUTO_INCREMENT for table `meter`
--
ALTER TABLE `meter`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

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
