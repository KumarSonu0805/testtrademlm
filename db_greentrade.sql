-- phpMyAdmin SQL Dump
-- version 5.1.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Oct 21, 2025 at 06:39 AM
-- Server version: 10.4.19-MariaDB
-- PHP Version: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `db_bitscore`
--

-- --------------------------------------------------------

--
-- Table structure for table `bs_errors`
--

CREATE TABLE `bs_errors` (
  `id` int(11) NOT NULL,
  `req_id` int(11) NOT NULL,
  `response` text NOT NULL,
  `type` varchar(20) NOT NULL,
  `added_on` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Table structure for table `bs_income`
--

CREATE TABLE `bs_income` (
  `id` int(11) NOT NULL,
  `date` date NOT NULL,
  `regid` int(11) NOT NULL,
  `type` varchar(20) NOT NULL,
  `inv_id` int(11) NOT NULL,
  `member_id` int(11) NOT NULL,
  `rank_id` int(11) NOT NULL,
  `royalty_id` int(11) NOT NULL,
  `level` int(11) NOT NULL,
  `rate` decimal(30,20) NOT NULL,
  `hr` int(11) NOT NULL,
  `amount` decimal(20,10) NOT NULL,
  `status` tinyint(1) NOT NULL DEFAULT 1,
  `added_on` datetime NOT NULL,
  `updated_on` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Table structure for table `bs_investments`
--

CREATE TABLE `bs_investments` (
  `id` int(11) NOT NULL,
  `regid` int(11) NOT NULL,
  `date` date NOT NULL,
  `rate` decimal(40,20) NOT NULL,
  `amount` decimal(30,20) NOT NULL,
  `reward` decimal(40,20) NOT NULL,
  `total` decimal(40,20) NOT NULL,
  `old` tinyint(1) NOT NULL DEFAULT 0,
  `unstaked` tinyint(1) NOT NULL DEFAULT 0,
  `status` tinyint(1) NOT NULL DEFAULT 1,
  `added_on` datetime NOT NULL,
  `updated_on` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Table structure for table `bs_level_members`
--

CREATE TABLE `bs_level_members` (
  `id` int(11) NOT NULL,
  `regid` int(11) NOT NULL,
  `level_id` int(11) NOT NULL,
  `member_id` int(11) NOT NULL,
  `added_on` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `bs_members`
--

CREATE TABLE `bs_members` (
  `id` int(11) NOT NULL,
  `name` varchar(100) NOT NULL,
  `gender` varchar(6) NOT NULL,
  `wallet_address` varchar(100) DEFAULT NULL,
  `aadhar` varchar(12) NOT NULL,
  `pan` varchar(10) NOT NULL,
  `address` varchar(255) NOT NULL,
  `district` varchar(30) NOT NULL,
  `state` varchar(30) NOT NULL,
  `country` varchar(50) NOT NULL,
  `pincode` varchar(6) NOT NULL,
  `photo` varchar(200) NOT NULL,
  `regid` int(11) NOT NULL,
  `refid` int(11) NOT NULL,
  `date` date NOT NULL,
  `time` time NOT NULL,
  `package` decimal(30,20) NOT NULL,
  `activation_date` date DEFAULT NULL,
  `activation_time` time DEFAULT NULL,
  `contact_id` varchar(50) DEFAULT NULL,
  `old` int(11) NOT NULL DEFAULT 0,
  `status` int(11) NOT NULL,
  `added_on` datetime NOT NULL,
  `updated_on` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `bs_member_ranks`
--

CREATE TABLE `bs_member_ranks` (
  `id` int(11) NOT NULL,
  `date` date NOT NULL,
  `regid` int(11) NOT NULL,
  `rank_id` int(11) NOT NULL,
  `rank` varchar(10) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Table structure for table `bs_ranks`
--

CREATE TABLE `bs_ranks` (
  `id` int(11) NOT NULL,
  `rank` varchar(10) NOT NULL,
  `leg_1` decimal(16,2) NOT NULL,
  `leg_2` decimal(16,2) NOT NULL,
  `reward` decimal(16,2) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Table structure for table `bs_request_log`
--

CREATE TABLE `bs_request_log` (
  `id` int(11) NOT NULL,
  `url` varchar(255) NOT NULL,
  `ip_address` varchar(20) NOT NULL,
  `post` longtext DEFAULT NULL,
  `server` longtext DEFAULT NULL,
  `cookie` longtext DEFAULT NULL,
  `headers` longtext DEFAULT NULL,
  `added_on` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `bs_settings`
--

CREATE TABLE `bs_settings` (
  `id` int(11) NOT NULL,
  `name` varchar(100) NOT NULL,
  `title` varchar(100) NOT NULL,
  `type` varchar(30) NOT NULL,
  `value` varchar(100) NOT NULL,
  `status` tinyint(1) NOT NULL DEFAULT 1,
  `added_on` datetime NOT NULL,
  `updated_on` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `bs_settings`
--

INSERT INTO `bs_settings` (`id`, `name`, `title`, `type`, `value`, `status`, `added_on`, `updated_on`) VALUES
(1, 'coin_rate', 'Coin Rate', 'Text', '0.000001062638366620783', 1, '2025-07-06 02:42:35', '2025-10-21 10:08:48');

-- --------------------------------------------------------

--
-- Table structure for table `bs_unstake`
--

CREATE TABLE `bs_unstake` (
  `id` int(11) NOT NULL,
  `regid` int(11) NOT NULL,
  `inv_id` int(11) NOT NULL,
  `date` date NOT NULL,
  `amount` decimal(30,20) NOT NULL,
  `rate` decimal(40,20) NOT NULL,
  `reward` decimal(40,20) NOT NULL,
  `total` decimal(40,20) NOT NULL,
  `approve_date` date DEFAULT NULL,
  `response` text DEFAULT NULL,
  `remarks` text DEFAULT NULL,
  `approved_by` int(11) DEFAULT NULL,
  `status` tinyint(1) NOT NULL DEFAULT 0,
  `added_on` datetime NOT NULL,
  `updated_on` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Table structure for table `bs_users`
--

CREATE TABLE `bs_users` (
  `id` int(11) NOT NULL,
  `username` varchar(20) NOT NULL,
  `mobile` varchar(10) NOT NULL,
  `name` varchar(50) NOT NULL,
  `email` varchar(50) NOT NULL,
  `password` varchar(100) NOT NULL,
  `vp` varchar(50) NOT NULL,
  `role` varchar(20) NOT NULL,
  `salt` varchar(20) NOT NULL,
  `otp` varchar(100) NOT NULL,
  `token` varchar(50) NOT NULL,
  `photo` varchar(100) NOT NULL,
  `language_id` int(11) DEFAULT NULL,
  `status` tinyint(1) NOT NULL DEFAULT 0,
  `created_on` datetime NOT NULL,
  `updated_on` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `bs_users`
--

INSERT INTO `bs_users` (`id`, `username`, `mobile`, `name`, `email`, `password`, `vp`, `role`, `salt`, `otp`, `token`, `photo`, `language_id`, `status`, `created_on`, `updated_on`) VALUES
(1, 'admin', '0987654321', 'Admin', 'admin@gmail.com', '$2y$10$o1sA2Dop80YLPtM85hWXau61T.Pog9hpbIwE7WVXP3s2FBizHzCLu', '12345', 'admin', 'Xh5AZKQFUtH6EwNk', '', '', '', NULL, 1, '2024-09-19 12:52:30', '2025-10-21 10:08:30');

-- --------------------------------------------------------

--
-- Table structure for table `bs_withdrawals`
--

CREATE TABLE `bs_withdrawals` (
  `id` int(11) NOT NULL,
  `regid` int(11) NOT NULL,
  `date` date NOT NULL,
  `amount` float(40,20) NOT NULL,
  `deduction` decimal(5,2) NOT NULL,
  `deduction_amount` decimal(40,20) NOT NULL,
  `payable_amount` decimal(40,20) NOT NULL,
  `rate` decimal(40,20) NOT NULL,
  `amount_usdt` decimal(40,20) NOT NULL,
  `approve_date` date DEFAULT NULL,
  `response` varchar(500) DEFAULT NULL,
  `remarks` text NOT NULL,
  `approved_by` int(11) NOT NULL,
  `status` tinyint(1) NOT NULL DEFAULT 0,
  `added_on` datetime NOT NULL,
  `updated_on` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `bs_errors`
--
ALTER TABLE `bs_errors`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `bs_income`
--
ALTER TABLE `bs_income`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `bs_investments`
--
ALTER TABLE `bs_investments`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `bs_level_members`
--
ALTER TABLE `bs_level_members`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_lmregid` (`regid`),
  ADD KEY `fk_lmmid` (`member_id`);

--
-- Indexes for table `bs_members`
--
ALTER TABLE `bs_members`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `regid` (`regid`),
  ADD UNIQUE KEY `wallet_address` (`wallet_address`),
  ADD KEY `FK_ref` (`refid`);

--
-- Indexes for table `bs_member_ranks`
--
ALTER TABLE `bs_member_ranks`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `bs_ranks`
--
ALTER TABLE `bs_ranks`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `bs_request_log`
--
ALTER TABLE `bs_request_log`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `bs_settings`
--
ALTER TABLE `bs_settings`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `bs_unstake`
--
ALTER TABLE `bs_unstake`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `bs_users`
--
ALTER TABLE `bs_users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `username` (`username`);

--
-- Indexes for table `bs_withdrawals`
--
ALTER TABLE `bs_withdrawals`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `bs_errors`
--
ALTER TABLE `bs_errors`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `bs_income`
--
ALTER TABLE `bs_income`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `bs_investments`
--
ALTER TABLE `bs_investments`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `bs_level_members`
--
ALTER TABLE `bs_level_members`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `bs_members`
--
ALTER TABLE `bs_members`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `bs_member_ranks`
--
ALTER TABLE `bs_member_ranks`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `bs_ranks`
--
ALTER TABLE `bs_ranks`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `bs_request_log`
--
ALTER TABLE `bs_request_log`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `bs_settings`
--
ALTER TABLE `bs_settings`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `bs_unstake`
--
ALTER TABLE `bs_unstake`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `bs_users`
--
ALTER TABLE `bs_users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `bs_withdrawals`
--
ALTER TABLE `bs_withdrawals`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `bs_level_members`
--
ALTER TABLE `bs_level_members`
  ADD CONSTRAINT `fk_lmmid` FOREIGN KEY (`member_id`) REFERENCES `bs_users` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_lmregid` FOREIGN KEY (`regid`) REFERENCES `bs_users` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `bs_members`
--
ALTER TABLE `bs_members`
  ADD CONSTRAINT `FK_ref` FOREIGN KEY (`refid`) REFERENCES `bs_users` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `FK_reg` FOREIGN KEY (`regid`) REFERENCES `bs_users` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
