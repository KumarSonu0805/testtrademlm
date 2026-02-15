-- phpMyAdmin SQL Dump
-- version 5.1.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Feb 15, 2026 at 02:51 PM
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
-- Database: `db_greentrade`
--

DELIMITER $$
--
-- Functions
--
CREATE DEFINER=`root`@`localhost` FUNCTION `GetAncestry` (`GivenID` INT) RETURNS VARCHAR(1024) CHARSET utf8mb4 BEGIN
    DECLARE rv VARCHAR(1024);
    DECLARE cm CHAR(1);
    DECLARE ch INT;

    SET rv = '';
    SET cm = '';
    SET ch = GivenID;
    WHILE ch > 1 DO
        SELECT IFNULL(refid,-1) INTO ch FROM
        (SELECT refid FROM dc_members WHERE regid = ch) A;
        IF ch > 1 THEN
            SET rv = CONCAT(rv,cm,ch);
            SET cm = ',';
        END IF;
    END WHILE;
    RETURN rv;
END$$

DELIMITER ;

-- --------------------------------------------------------

--
-- Table structure for table `dc_acc_details`
--

CREATE TABLE `dc_acc_details` (
  `id` int(11) NOT NULL,
  `regid` int(11) NOT NULL,
  `bank` varchar(50) NOT NULL,
  `branch` varchar(50) NOT NULL,
  `city` varchar(50) NOT NULL,
  `account_no` varchar(50) NOT NULL,
  `account_name` varchar(100) NOT NULL,
  `ifsc` varchar(11) NOT NULL,
  `micr` varchar(30) NOT NULL,
  `upi` varchar(20) NOT NULL,
  `aadhar1` varchar(100) NOT NULL,
  `aadhar2` varchar(100) NOT NULL,
  `address` varchar(500) NOT NULL,
  `pan` varchar(100) NOT NULL,
  `fund_id` varchar(30) DEFAULT NULL,
  `reason` varchar(200) DEFAULT NULL,
  `remarks` text NOT NULL,
  `cheque` varchar(100) NOT NULL,
  `kyc` tinyint(1) NOT NULL DEFAULT 0,
  `added_on` datetime DEFAULT NULL,
  `updated_on` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `dc_area`
--

CREATE TABLE `dc_area` (
  `id` int(11) NOT NULL,
  `name` varchar(300) NOT NULL,
  `type` varchar(200) NOT NULL,
  `parent_id` int(11) NOT NULL,
  `status` int(11) NOT NULL DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `dc_area`
--

INSERT INTO `dc_area` (`id`, `name`, `type`, `parent_id`, `status`) VALUES
(1, 'Andaman & Nicobar Islands', 'State', 0, 1),
(2, 'Andhra Pradesh', 'State', 0, 1),
(3, 'Arunachal Pradesh', 'State', 0, 1),
(4, 'Assam', 'State', 0, 1),
(5, 'Bihar', 'State', 0, 1),
(6, 'Chandigarh', 'State', 0, 1),
(7, 'Chhattisgarh', 'State', 0, 1),
(8, 'Dadra and Nagar Haveli', 'State', 0, 1),
(9, 'Daman and Diu', 'State', 0, 1),
(10, 'Delhi', 'State', 0, 1),
(11, 'Goa', 'State', 0, 1),
(12, 'Gujarat', 'State', 0, 1),
(13, 'Haryana', 'State', 0, 1),
(14, 'Himachal Pradesh', 'State', 0, 1),
(15, 'Jammu and Kashmir', 'State', 0, 1),
(16, 'Jharkhand', 'State', 0, 1),
(17, 'Karnataka', 'State', 0, 1),
(18, 'Kerala', 'State', 0, 1),
(19, 'Lakshadweep', 'State', 0, 1),
(20, 'Madhya Pradesh', 'State', 0, 1),
(21, 'Maharashtra', 'State', 0, 1),
(22, 'Manipur', 'State', 0, 1),
(23, 'Meghalaya', 'State', 0, 1),
(24, 'Mizoram', 'State', 0, 1),
(25, 'Nagaland', 'State', 0, 1),
(26, 'Odisha', 'State', 0, 1),
(27, 'Puducherry', 'State', 0, 1),
(28, 'Punjab', 'State', 0, 1),
(29, 'Rajasthan', 'State', 0, 1),
(30, 'Sikkim', 'State', 0, 1),
(31, 'Tamil Nadu', 'State', 0, 1),
(32, 'Telengana', 'State', 0, 1),
(33, 'Tripura', 'State', 0, 1),
(34, 'Uttrakhand', 'State', 0, 1),
(35, 'Uttar Pradesh', 'State', 0, 1),
(36, 'West Bengal', 'State', 0, 1),
(37, 'Nicobar', 'District', 1, 1),
(38, 'North and Middle Andaman', 'District', 1, 1),
(39, 'South Andaman', 'District', 1, 1),
(40, 'Anantapur', 'District', 2, 1),
(41, 'Chittoor', 'District', 2, 1),
(42, 'East Godavari', 'District', 2, 1),
(43, 'Guntur', 'District', 2, 1),
(44, 'Krishna', 'District', 2, 1),
(45, 'Kurnool', 'District', 2, 1),
(46, 'Prakasam', 'District', 2, 1),
(47, 'Srikakulam', 'District', 2, 1),
(48, 'Sri Potti Sriramulu Nellore', 'District', 2, 1),
(49, 'Visakhapatnam', 'District', 2, 1),
(50, 'Vizianagaram', 'District', 2, 1),
(51, 'West Godavari', 'District', 2, 1),
(52, 'YSR District, Kadapa (Cuddapah)', 'District', 2, 1),
(53, 'Anjaw', 'District', 3, 1),
(54, 'Changlang', 'District', 3, 1),
(55, 'Dibang Valley', 'District', 3, 1),
(56, 'East Kameng', 'District', 3, 1),
(57, 'East Siang', 'District', 3, 1),
(58, 'Kamle', 'District', 3, 1),
(59, 'Kra Daadi', 'District', 3, 1),
(60, 'Kurung Kumey', 'District', 3, 1),
(61, 'Lepa Rada', 'District', 3, 1),
(62, 'Lohit', 'District', 3, 1),
(63, 'Longding', 'District', 3, 1),
(64, 'Lower Dibang Valley', 'District', 3, 1),
(65, 'Lower Siang', 'District', 3, 1),
(66, 'Lower Subansiri', 'District', 3, 1),
(67, 'Namsai', 'District', 3, 1),
(68, 'Pakke Kessang', 'District', 3, 1),
(69, 'Papum Pare', 'District', 3, 1),
(70, 'Shi Yomi', 'District', 3, 1),
(71, 'Siang', 'District', 3, 1),
(72, 'Tawang', 'District', 3, 1),
(73, 'Tirap', 'District', 3, 1),
(74, 'Upper Siang', 'District', 3, 1),
(75, 'Upper Subansiri', 'District', 3, 1),
(76, 'West Kameng', 'District', 3, 1),
(77, 'West Siang', 'District', 3, 1),
(78, 'Baksa', 'District', 4, 1),
(79, 'Barpeta', 'District', 4, 1),
(80, 'Biswanath', 'District', 4, 1),
(81, 'Bongaigaon', 'District', 4, 1),
(82, 'Cachar', 'District', 4, 1),
(83, 'Charaideo', 'District', 4, 1),
(84, 'Chirang', 'District', 4, 1),
(85, 'Darrang', 'District', 4, 1),
(86, 'Dhemaji', 'District', 4, 1),
(87, 'Dhubri', 'District', 4, 1),
(88, 'Dibrugarh', 'District', 4, 1),
(89, 'Dima Hasao (North Cachar Hills)', 'District', 4, 1),
(90, 'Goalpara', 'District', 4, 1),
(91, 'Golaghat', 'District', 4, 1),
(92, 'Hailakandi', 'District', 4, 1),
(93, 'Hojai', 'District', 4, 1),
(94, 'Jorhat', 'District', 4, 1),
(95, 'Kamrup', 'District', 4, 1),
(96, 'Kamrup Metropolitan', 'District', 4, 1),
(97, 'Karbi Anglong', 'District', 4, 1),
(98, 'Karimganj', 'District', 4, 1),
(99, 'Kokrajhar', 'District', 4, 1),
(100, 'Lakhimpur', 'District', 4, 1),
(101, 'Majuli', 'District', 4, 1),
(102, 'Morigaon', 'District', 4, 1),
(103, 'Nagaon', 'District', 4, 1),
(104, 'Nalbari', 'District', 4, 1),
(105, 'Sivasagar', 'District', 4, 1),
(106, 'Sonitpur', 'District', 4, 1),
(107, 'South Salamara-Mankachar', 'District', 4, 1),
(108, 'Tinsukia', 'District', 4, 1),
(109, 'Udalguri', 'District', 4, 1),
(110, 'West Karbi Anglong', 'District', 4, 1),
(111, 'Araria', 'District', 5, 1),
(112, 'Arwal', 'District', 5, 1),
(113, 'Aurangabad', 'District', 5, 1),
(114, 'Banka', 'District', 5, 1),
(115, 'Begusarai', 'District', 5, 1),
(116, 'Bhagalpur', 'District', 5, 1),
(117, 'Bhojpur', 'District', 5, 1),
(118, 'Buxar', 'District', 5, 1),
(119, 'Darbhanga', 'District', 5, 1),
(120, 'East Champaran (Motihari)', 'District', 5, 1),
(121, 'Gaya', 'District', 5, 1),
(122, 'Gopalganj', 'District', 5, 1),
(123, 'Jamui', 'District', 5, 1),
(124, 'Jehanabad', 'District', 5, 1),
(125, 'Kaimur (Bhabua)', 'District', 5, 1),
(126, 'Katihar', 'District', 5, 1),
(127, 'Khagaria', 'District', 5, 1),
(128, 'Kishanganj', 'District', 5, 1),
(129, 'Lakhisarai', 'District', 5, 1),
(130, 'Madhepura', 'District', 5, 1),
(131, 'Madhubani', 'District', 5, 1),
(132, 'Munger (Monghyr)', 'District', 5, 1),
(133, 'Muzaffarpur', 'District', 5, 1),
(134, 'Nalanda', 'District', 5, 1),
(135, 'Nawada', 'District', 5, 1),
(136, 'Patna', 'District', 5, 1),
(137, 'Purnia (Purnea)', 'District', 5, 1),
(138, 'Rohtas', 'District', 5, 1),
(139, 'Saharsa', 'District', 5, 1),
(140, 'Samastipur', 'District', 5, 1),
(141, 'Saran', 'District', 5, 1),
(142, 'Sheikhpura', 'District', 5, 1),
(143, 'Sheohar', 'District', 5, 1),
(144, 'Sitamarhi', 'District', 5, 1),
(145, 'Siwan', 'District', 5, 1),
(146, 'Supaul', 'District', 5, 1),
(147, 'Vaishali', 'District', 5, 1),
(148, 'West Champaran', 'District', 5, 1),
(149, 'Chandigarh', 'District', 6, 1),
(150, 'Balod', 'District', 7, 1),
(151, 'Baloda Bazar', 'District', 7, 1),
(152, 'Balrampur', 'District', 7, 1),
(153, 'Bastar', 'District', 7, 1),
(154, 'Bemetara', 'District', 7, 1),
(155, 'Bijapur', 'District', 7, 1),
(156, 'Bilaspur', 'District', 7, 1),
(157, 'Dantewada (South Bastar)', 'District', 7, 1),
(158, 'Dhamtari', 'District', 7, 1),
(159, 'Durg', 'District', 7, 1),
(160, 'Gariyaband', 'District', 7, 1),
(161, 'Janjgir-Champa', 'District', 7, 1),
(162, 'Jashpur', 'District', 7, 1),
(163, 'Kabirdham (Kawardha)', 'District', 7, 1),
(164, 'Kanker (North Bastar)', 'District', 7, 1),
(165, 'Kondagaon', 'District', 7, 1),
(166, 'Korba', 'District', 7, 1),
(167, 'Korea (Koriya)', 'District', 7, 1),
(168, 'Mahasamund', 'District', 7, 1),
(169, 'Mungeli', 'District', 7, 1),
(170, 'Narayanpur', 'District', 7, 1),
(171, 'Raigarh', 'District', 7, 1),
(172, 'Raipur', 'District', 7, 1),
(173, 'Rajnandgaon', 'District', 7, 1),
(174, 'Sukma', 'District', 7, 1),
(175, 'Surajpur', 'District', 7, 1),
(176, 'Surguja', 'District', 7, 1),
(177, 'Dadra &amp; Nagar Haveli', 'District', 8, 1),
(178, 'Daman', 'District', 9, 1),
(179, 'Diu', 'District', 9, 1),
(180, 'Central Delhi', 'District', 10, 1),
(181, 'East Delhi', 'District', 10, 1),
(182, 'New Delhi', 'District', 10, 1),
(183, 'North Delhi', 'District', 10, 1),
(184, 'North East  Delhi', 'District', 10, 1),
(185, 'North West  Delhi', 'District', 10, 1),
(186, 'Shahdara', 'District', 10, 1),
(187, 'South Delhi', 'District', 10, 1),
(188, 'South East Delhi', 'District', 10, 1),
(189, 'South West  Delhi', 'District', 10, 1),
(190, 'West Delhi', 'District', 10, 1),
(191, 'North Goa', 'District', 11, 1),
(192, 'South Goa', 'District', 11, 1),
(193, 'Ahmedabad', 'District', 12, 1),
(194, 'Amreli', 'District', 12, 1),
(195, 'Anand', 'District', 12, 1),
(196, 'Aravalli', 'District', 12, 1),
(197, 'Banaskantha (Palanpur)', 'District', 12, 1),
(198, 'Bharuch', 'District', 12, 1),
(199, 'Bhavnagar', 'District', 12, 1),
(200, 'Botad', 'District', 12, 1),
(201, 'Chhota Udepur', 'District', 12, 1),
(202, 'Dahod', 'District', 12, 1),
(203, 'Dangs (Ahwa)', 'District', 12, 1),
(204, 'Devbhoomi Dwarka', 'District', 12, 1),
(205, 'Gandhinagar', 'District', 12, 1),
(206, 'Gir Somnath', 'District', 12, 1),
(207, 'Jamnagar', 'District', 12, 1),
(208, 'Junagadh', 'District', 12, 1),
(209, 'Kachchh', 'District', 12, 1),
(210, 'Kheda (Nadiad)', 'District', 12, 1),
(211, 'Mahisagar', 'District', 12, 1),
(212, 'Mehsana', 'District', 12, 1),
(213, 'Morbi', 'District', 12, 1),
(214, 'Narmada (Rajpipla)', 'District', 12, 1),
(215, 'Navsari', 'District', 12, 1),
(216, 'Panchmahal (Godhra)', 'District', 12, 1),
(217, 'Patan', 'District', 12, 1),
(218, 'Porbandar', 'District', 12, 1),
(219, 'Rajkot', 'District', 12, 1),
(220, 'Sabarkantha (Himmatnagar)', 'District', 12, 1),
(221, 'Surat', 'District', 12, 1),
(222, 'Surendranagar', 'District', 12, 1),
(223, 'Tapi (Vyara)', 'District', 12, 1),
(224, 'Vadodara', 'District', 12, 1),
(225, 'Valsad', 'District', 12, 1),
(226, 'Ambala', 'District', 13, 1),
(227, 'Bhiwani', 'District', 13, 1),
(228, 'Charkhi Dadri', 'District', 13, 1),
(229, 'Faridabad', 'District', 13, 1),
(230, 'Fatehabad', 'District', 13, 1),
(231, 'Gurgaon', 'District', 13, 1),
(232, 'Hisar', 'District', 13, 1),
(233, 'Jhajjar', 'District', 13, 1),
(234, 'Jind', 'District', 13, 1),
(235, 'Kaithal', 'District', 13, 1),
(236, 'Karnal', 'District', 13, 1),
(237, 'Kurukshetra', 'District', 13, 1),
(238, 'Mahendragarh', 'District', 13, 1),
(239, 'Mewat', 'District', 13, 1),
(240, 'Palwal', 'District', 13, 1),
(241, 'Panchkula', 'District', 13, 1),
(242, 'Panipat', 'District', 13, 1),
(243, 'Rewari', 'District', 13, 1),
(244, 'Rohtak', 'District', 13, 1),
(245, 'Sirsa', 'District', 13, 1),
(246, 'Sonipat', 'District', 13, 1),
(247, 'Yamunanagar', 'District', 13, 1),
(248, 'Bilaspur', 'District', 14, 1),
(249, 'Chamba', 'District', 14, 1),
(250, 'Hamirpur', 'District', 14, 1),
(251, 'Kangra', 'District', 14, 1),
(252, 'Kinnaur', 'District', 14, 1),
(253, 'Kullu', 'District', 14, 1),
(254, 'Lahaul &amp; Spiti', 'District', 14, 1),
(255, 'Mandi', 'District', 14, 1),
(256, 'Shimla', 'District', 14, 1),
(257, 'Sirmaur (Sirmour)', 'District', 14, 1),
(258, 'Solan', 'District', 14, 1),
(259, 'Una', 'District', 14, 1),
(260, 'Anantnag', 'District', 15, 1),
(261, 'Bandipore', 'District', 15, 1),
(262, 'Baramulla', 'District', 15, 1),
(263, 'Budgam', 'District', 15, 1),
(264, 'Doda', 'District', 15, 1),
(265, 'Ganderbal', 'District', 15, 1),
(266, 'Jammu', 'District', 15, 1),
(267, 'Kargil', 'District', 15, 1),
(268, 'Kathua', 'District', 15, 1),
(269, 'Kishtwar', 'District', 15, 1),
(270, 'Kulgam', 'District', 15, 1),
(271, 'Kupwara', 'District', 15, 1),
(272, 'Leh', 'District', 15, 1),
(273, 'Poonch', 'District', 15, 1),
(274, 'Pulwama', 'District', 15, 1),
(275, 'Rajouri', 'District', 15, 1),
(276, 'Ramban', 'District', 15, 1),
(277, 'Reasi', 'District', 15, 1),
(278, 'Samba', 'District', 15, 1),
(279, 'Shopian', 'District', 15, 1),
(280, 'Srinagar', 'District', 15, 1),
(281, 'Udhampur', 'District', 15, 1),
(282, 'Bokaro', 'District', 16, 1),
(283, 'Chatra', 'District', 16, 1),
(284, 'Deoghar', 'District', 16, 1),
(285, 'Dhanbad', 'District', 16, 1),
(286, 'Dumka', 'District', 16, 1),
(287, 'East Singhbhum', 'District', 16, 1),
(288, 'Garhwa', 'District', 16, 1),
(289, 'Giridih', 'District', 16, 1),
(290, 'Godda', 'District', 16, 1),
(291, 'Gumla', 'District', 16, 1),
(292, 'Hazaribag', 'District', 16, 1),
(293, 'Jamtara', 'District', 16, 1),
(294, 'Khunti', 'District', 16, 1),
(295, 'Koderma', 'District', 16, 1),
(296, 'Latehar', 'District', 16, 1),
(297, 'Lohardaga', 'District', 16, 1),
(298, 'Pakur', 'District', 16, 1),
(299, 'Palamu', 'District', 16, 1),
(300, 'Ramgarh', 'District', 16, 1),
(301, 'Ranchi', 'District', 16, 1),
(302, 'Sahibganj', 'District', 16, 1),
(303, 'Seraikela-Kharsawan', 'District', 16, 1),
(304, 'Simdega', 'District', 16, 1),
(305, 'West Singhbhum', 'District', 16, 1),
(306, 'Bagalkot', 'District', 17, 1),
(307, 'Ballari (Bellary)', 'District', 17, 1),
(308, 'Belagavi (Belgaum)', 'District', 17, 1),
(309, 'Bengaluru (Bangalore) Rural', 'District', 17, 1),
(310, 'Bengaluru (Bangalore) Urban', 'District', 17, 1),
(311, 'Bidar', 'District', 17, 1),
(312, 'Chamarajanagar', 'District', 17, 1),
(313, 'Chikballapur', 'District', 17, 1),
(314, 'Chikkamagaluru (Chikmagalur)', 'District', 17, 1),
(315, 'Chitradurga', 'District', 17, 1),
(316, 'Dakshina Kannada', 'District', 17, 1),
(317, 'Davangere', 'District', 17, 1),
(318, 'Dharwad', 'District', 17, 1),
(319, 'Gadag', 'District', 17, 1),
(320, 'Hassan', 'District', 17, 1),
(321, 'Haveri', 'District', 17, 1),
(322, 'Kalaburagi (Gulbarga)', 'District', 17, 1),
(323, 'Kodagu', 'District', 17, 1),
(324, 'Kolar', 'District', 17, 1),
(325, 'Koppal', 'District', 17, 1),
(326, 'Mandya', 'District', 17, 1),
(327, 'Mysuru (Mysore)', 'District', 17, 1),
(328, 'Raichur', 'District', 17, 1),
(329, 'Ramanagara', 'District', 17, 1),
(330, 'Shivamogga (Shimoga)', 'District', 17, 1),
(331, 'Tumakuru (Tumkur)', 'District', 17, 1),
(332, 'Udupi', 'District', 17, 1),
(333, 'Uttara Kannada (Karwar)', 'District', 17, 1),
(334, 'Vijayapura (Bijapur)', 'District', 17, 1),
(335, 'Yadgir', 'District', 17, 1),
(336, 'Alappuzha', 'District', 18, 1),
(337, 'Ernakulam', 'District', 18, 1),
(338, 'Idukki', 'District', 18, 1),
(339, 'Kannur', 'District', 18, 1),
(340, 'Kasaragod', 'District', 18, 1),
(341, 'Kollam', 'District', 18, 1),
(342, 'Kottayam', 'District', 18, 1),
(343, 'Kozhikode', 'District', 18, 1),
(344, 'Malappuram', 'District', 18, 1),
(345, 'Palakkad', 'District', 18, 1),
(346, 'Pathanamthitta', 'District', 18, 1),
(347, 'Thiruvananthapuram', 'District', 18, 1),
(348, 'Thrissur', 'District', 18, 1),
(349, 'Wayanad', 'District', 18, 1),
(350, 'Lakshadweep', 'District', 19, 1),
(351, 'Agar Malwa', 'District', 20, 1),
(352, 'Alirajpur', 'District', 20, 1),
(353, 'Anuppur', 'District', 20, 1),
(354, 'Ashoknagar', 'District', 20, 1),
(355, 'Balaghat', 'District', 20, 1),
(356, 'Barwani', 'District', 20, 1),
(357, 'Betul', 'District', 20, 1),
(358, 'Bhind', 'District', 20, 1),
(359, 'Bhopal', 'District', 20, 1),
(360, 'Burhanpur', 'District', 20, 1),
(361, 'Chhatarpur', 'District', 20, 1),
(362, 'Chhindwara', 'District', 20, 1),
(363, 'Damoh', 'District', 20, 1),
(364, 'Datia', 'District', 20, 1),
(365, 'Dewas', 'District', 20, 1),
(366, 'Dhar', 'District', 20, 1),
(367, 'Dindori', 'District', 20, 1),
(368, 'Guna', 'District', 20, 1),
(369, 'Gwalior', 'District', 20, 1),
(370, 'Harda', 'District', 20, 1),
(371, 'Hoshangabad', 'District', 20, 1),
(372, 'Indore', 'District', 20, 1),
(373, 'Jabalpur', 'District', 20, 1),
(374, 'Jhabua', 'District', 20, 1),
(375, 'Katni', 'District', 20, 1),
(376, 'Khandwa', 'District', 20, 1),
(377, 'Khargone', 'District', 20, 1),
(378, 'Mandla', 'District', 20, 1),
(379, 'Mandsaur', 'District', 20, 1),
(380, 'Morena', 'District', 20, 1),
(381, 'Narsinghpur', 'District', 20, 1),
(382, 'Neemuch', 'District', 20, 1),
(383, 'Panna', 'District', 20, 1),
(384, 'Raisen', 'District', 20, 1),
(385, 'Rajgarh', 'District', 20, 1),
(386, 'Ratlam', 'District', 20, 1),
(387, 'Rewa', 'District', 20, 1),
(388, 'Sagar', 'District', 20, 1),
(389, 'Satna', 'District', 20, 1),
(390, 'Sehore', 'District', 20, 1),
(391, 'Seoni', 'District', 20, 1),
(392, 'Shahdol', 'District', 20, 1),
(393, 'Shajapur', 'District', 20, 1),
(394, 'Sheopur', 'District', 20, 1),
(395, 'Shivpuri', 'District', 20, 1),
(396, 'Sidhi', 'District', 20, 1),
(397, 'Singrauli', 'District', 20, 1),
(398, 'Tikamgarh', 'District', 20, 1),
(399, 'Ujjain', 'District', 20, 1),
(400, 'Umaria', 'District', 20, 1),
(401, 'Vidisha', 'District', 20, 1),
(402, 'Ahmednagar', 'District', 21, 1),
(403, 'Akola', 'District', 21, 1),
(404, 'Amravati', 'District', 21, 1),
(405, 'Aurangabad', 'District', 21, 1),
(406, 'Beed', 'District', 21, 1),
(407, 'Bhandara', 'District', 21, 1),
(408, 'Buldhana', 'District', 21, 1),
(409, 'Chandrapur', 'District', 21, 1),
(410, 'Dhule', 'District', 21, 1),
(411, 'Gadchiroli', 'District', 21, 1),
(412, 'Gondia', 'District', 21, 1),
(413, 'Hingoli', 'District', 21, 1),
(414, 'Jalgaon', 'District', 21, 1),
(415, 'Jalna', 'District', 21, 1),
(416, 'Kolhapur', 'District', 21, 1),
(417, 'Latur', 'District', 21, 1),
(418, 'Mumbai City', 'District', 21, 1),
(419, 'Mumbai Suburban', 'District', 21, 1),
(420, 'Nagpur', 'District', 21, 1),
(421, 'Nanded', 'District', 21, 1),
(422, 'Nandurbar', 'District', 21, 1),
(423, 'Nashik', 'District', 21, 1),
(424, 'Osmanabad', 'District', 21, 1),
(425, 'Palghar', 'District', 21, 1),
(426, 'Parbhani', 'District', 21, 1),
(427, 'Pune', 'District', 21, 1),
(428, 'Raigad', 'District', 21, 1),
(429, 'Ratnagiri', 'District', 21, 1),
(430, 'Sangli', 'District', 21, 1),
(431, 'Satara', 'District', 21, 1),
(432, 'Sindhudurg', 'District', 21, 1),
(433, 'Solapur', 'District', 21, 1),
(434, 'Thane', 'District', 21, 1),
(435, 'Wardha', 'District', 21, 1),
(436, 'Washim', 'District', 21, 1),
(437, 'Yavatmal', 'District', 21, 1),
(438, 'Bishnupur', 'District', 22, 1),
(439, 'Chandel', 'District', 22, 1),
(440, 'Churachandpur', 'District', 22, 1),
(441, 'Imphal East', 'District', 22, 1),
(442, 'Imphal West', 'District', 22, 1),
(443, 'Jiribam', 'District', 22, 1),
(444, 'Kakching', 'District', 22, 1),
(445, 'Kamjong', 'District', 22, 1),
(446, 'Kangpokpi', 'District', 22, 1),
(447, 'Noney', 'District', 22, 1),
(448, 'Pherzawl', 'District', 22, 1),
(449, 'Senapati', 'District', 22, 1),
(450, 'Tamenglong', 'District', 22, 1),
(451, 'Tengnoupal', 'District', 22, 1),
(452, 'Thoubal', 'District', 22, 1),
(453, 'Ukhrul', 'District', 22, 1),
(454, 'East Garo Hills', 'District', 23, 1),
(455, 'East Jaintia Hills', 'District', 23, 1),
(456, 'East Khasi Hills', 'District', 23, 1),
(457, 'North Garo Hills', 'District', 23, 1),
(458, 'Ri Bhoi', 'District', 23, 1),
(459, 'South Garo Hills', 'District', 23, 1),
(460, 'South West Garo Hills', 'District', 23, 1),
(461, 'South West Khasi Hills', 'District', 23, 1),
(462, 'West Garo Hills', 'District', 23, 1),
(463, 'West Jaintia Hills', 'District', 23, 1),
(464, 'West Khasi Hills', 'District', 23, 1),
(465, 'Aizawl', 'District', 24, 1),
(466, 'Champhai', 'District', 24, 1),
(467, 'Kolasib', 'District', 24, 1),
(468, 'Lawngtlai', 'District', 24, 1),
(469, 'Lunglei', 'District', 24, 1),
(470, 'Mamit', 'District', 24, 1),
(471, 'Saiha', 'District', 24, 1),
(472, 'Serchhip', 'District', 24, 1),
(473, 'Dimapur', 'District', 25, 1),
(474, 'Kiphire', 'District', 25, 1),
(475, 'Kohima', 'District', 25, 1),
(476, 'Longleng', 'District', 25, 1),
(477, 'Mokokchung', 'District', 25, 1),
(478, 'Mon', 'District', 25, 1),
(479, 'Peren', 'District', 25, 1),
(480, 'Phek', 'District', 25, 1),
(481, 'Tuensang', 'District', 25, 1),
(482, 'Wokha', 'District', 25, 1),
(483, 'Zunheboto', 'District', 25, 1),
(484, 'Angul', 'District', 26, 1),
(485, 'Balangir', 'District', 26, 1),
(486, 'Balasore', 'District', 26, 1),
(487, 'Bargarh', 'District', 26, 1),
(488, 'Bhadrak', 'District', 26, 1),
(489, 'Boudh', 'District', 26, 1),
(490, 'Cuttack', 'District', 26, 1),
(491, 'Deogarh', 'District', 26, 1),
(492, 'Dhenkanal', 'District', 26, 1),
(493, 'Gajapati', 'District', 26, 1),
(494, 'Ganjam', 'District', 26, 1),
(495, 'Jagatsinghapur', 'District', 26, 1),
(496, 'Jajpur', 'District', 26, 1),
(497, 'Jharsuguda', 'District', 26, 1),
(498, 'Kalahandi', 'District', 26, 1),
(499, 'Kandhamal', 'District', 26, 1),
(500, 'Kendrapara', 'District', 26, 1),
(501, 'Kendujhar (Keonjhar)', 'District', 26, 1),
(502, 'Khordha', 'District', 26, 1),
(503, 'Koraput', 'District', 26, 1),
(504, 'Malkangiri', 'District', 26, 1),
(505, 'Mayurbhanj', 'District', 26, 1),
(506, 'Nabarangpur', 'District', 26, 1),
(507, 'Nayagarh', 'District', 26, 1),
(508, 'Nuapada', 'District', 26, 1),
(509, 'Puri', 'District', 26, 1),
(510, 'Rayagada', 'District', 26, 1),
(511, 'Sambalpur', 'District', 26, 1),
(512, 'Sonepur', 'District', 26, 1),
(513, 'Sundargarh', 'District', 26, 1),
(514, 'Karaikal', 'District', 27, 1),
(515, 'Mahe', 'District', 27, 1),
(516, 'Pondicherry', 'District', 27, 1),
(517, 'Yanam', 'District', 27, 1),
(518, 'Amritsar', 'District', 28, 1),
(519, 'Barnala', 'District', 28, 1),
(520, 'Bathinda', 'District', 28, 1),
(521, 'Faridkot', 'District', 28, 1),
(522, 'Fatehgarh Sahib', 'District', 28, 1),
(523, 'Fazilka', 'District', 28, 1),
(524, 'Ferozepur', 'District', 28, 1),
(525, 'Gurdaspur', 'District', 28, 1),
(526, 'Hoshiarpur', 'District', 28, 1),
(527, 'Jalandhar', 'District', 28, 1),
(528, 'Kapurthala', 'District', 28, 1),
(529, 'Ludhiana', 'District', 28, 1),
(530, 'Mansa', 'District', 28, 1),
(531, 'Moga', 'District', 28, 1),
(532, 'Muktsar', 'District', 28, 1),
(533, 'Nawanshahr (Shahid Bhagat Singh Nagar)', 'District', 28, 1),
(534, 'Pathankot', 'District', 28, 1),
(535, 'Patiala', 'District', 28, 1),
(536, 'Rupnagar', 'District', 28, 1),
(537, 'Sahibzada Ajit Singh Nagar (Mohali)', 'District', 28, 1),
(538, 'Sangrur', 'District', 28, 1),
(539, 'Tarn Taran', 'District', 28, 1),
(540, 'Ajmer', 'District', 29, 1),
(541, 'Alwar', 'District', 29, 1),
(542, 'Banswara', 'District', 29, 1),
(543, 'Baran', 'District', 29, 1),
(544, 'Barmer', 'District', 29, 1),
(545, 'Bharatpur', 'District', 29, 1),
(546, 'Bhilwara', 'District', 29, 1),
(547, 'Bikaner', 'District', 29, 1),
(548, 'Bundi', 'District', 29, 1),
(549, 'Chittorgarh', 'District', 29, 1),
(550, 'Churu', 'District', 29, 1),
(551, 'Dausa', 'District', 29, 1),
(552, 'Dholpur', 'District', 29, 1),
(553, 'Dungarpur', 'District', 29, 1),
(554, 'Hanumangarh', 'District', 29, 1),
(555, 'Jaipur', 'District', 29, 1),
(556, 'Jaisalmer', 'District', 29, 1),
(557, 'Jalore', 'District', 29, 1),
(558, 'Jhalawar', 'District', 29, 1),
(559, 'Jhunjhunu', 'District', 29, 1),
(560, 'Jodhpur', 'District', 29, 1),
(561, 'Karauli', 'District', 29, 1),
(562, 'Kota', 'District', 29, 1),
(563, 'Nagaur', 'District', 29, 1),
(564, 'Pali', 'District', 29, 1),
(565, 'Pratapgarh', 'District', 29, 1),
(566, 'Rajsamand', 'District', 29, 1),
(567, 'Sawai Madhopur', 'District', 29, 1),
(568, 'Sikar', 'District', 29, 1),
(569, 'Sirohi', 'District', 29, 1),
(570, 'Sri Ganganagar', 'District', 29, 1),
(571, 'Tonk', 'District', 29, 1),
(572, 'Udaipur', 'District', 29, 1),
(573, 'East Sikkim', 'District', 30, 1),
(574, 'North Sikkim', 'District', 30, 1),
(575, 'South Sikkim', 'District', 30, 1),
(576, 'West Sikkim', 'District', 30, 1),
(577, 'Ariyalur', 'District', 31, 1),
(578, 'Chennai', 'District', 31, 1),
(579, 'Coimbatore', 'District', 31, 1),
(580, 'Cuddalore', 'District', 31, 1),
(581, 'Dharmapuri', 'District', 31, 1),
(582, 'Dindigul', 'District', 31, 1),
(583, 'Erode', 'District', 31, 1),
(584, 'Kanchipuram', 'District', 31, 1),
(585, 'Kanyakumari', 'District', 31, 1),
(586, 'Karur', 'District', 31, 1),
(587, 'Krishnagiri', 'District', 31, 1),
(588, 'Madurai', 'District', 31, 1),
(589, 'Nagapattinam', 'District', 31, 1),
(590, 'Namakkal', 'District', 31, 1),
(591, 'Nilgiris', 'District', 31, 1),
(592, 'Perambalur', 'District', 31, 1),
(593, 'Pudukkottai', 'District', 31, 1),
(594, 'Ramanathapuram', 'District', 31, 1),
(595, 'Salem', 'District', 31, 1),
(596, 'Sivaganga', 'District', 31, 1),
(597, 'Thanjavur', 'District', 31, 1),
(598, 'Theni', 'District', 31, 1),
(599, 'Thoothukudi (Tuticorin)', 'District', 31, 1),
(600, 'Tiruchirappalli', 'District', 31, 1),
(601, 'Tirunelveli', 'District', 31, 1),
(602, 'Tiruppur', 'District', 31, 1),
(603, 'Tiruvallur', 'District', 31, 1),
(604, 'Tiruvannamalai', 'District', 31, 1),
(605, 'Tiruvarur', 'District', 31, 1),
(606, 'Vellore', 'District', 31, 1),
(607, 'Viluppuram', 'District', 31, 1),
(608, 'Virudhunagar', 'District', 31, 1),
(609, 'Adilabad', 'District', 32, 1),
(610, 'Bhadradri Kothagudem', 'District', 32, 1),
(611, 'Hyderabad', 'District', 32, 1),
(612, 'Jagtial', 'District', 32, 1),
(613, 'Jangaon', 'District', 32, 1),
(614, 'Jayashankar Bhoopalpally', 'District', 32, 1),
(615, 'Jogulamba Gadwal', 'District', 32, 1),
(616, 'Kamareddy', 'District', 32, 1),
(617, 'Karimnagar', 'District', 32, 1),
(618, 'Khammam', 'District', 32, 1),
(619, 'Komaram Bheem Asifabad', 'District', 32, 1),
(620, 'Mahabubabad', 'District', 32, 1),
(621, 'Mahabubnagar', 'District', 32, 1),
(622, 'Mancherial', 'District', 32, 1),
(623, 'Medak', 'District', 32, 1),
(624, 'Medchal', 'District', 32, 1),
(625, 'Nagarkurnool', 'District', 32, 1),
(626, 'Nalgonda', 'District', 32, 1),
(627, 'Nirmal', 'District', 32, 1),
(628, 'Nizamabad', 'District', 32, 1),
(629, 'Peddapalli', 'District', 32, 1),
(630, 'Rajanna Sircilla', 'District', 32, 1),
(631, 'Rangareddy', 'District', 32, 1),
(632, 'Sangareddy', 'District', 32, 1),
(633, 'Siddipet', 'District', 32, 1),
(634, 'Suryapet', 'District', 32, 1),
(635, 'Vikarabad', 'District', 32, 1),
(636, 'Wanaparthy', 'District', 32, 1),
(637, 'Warangal (Rural)', 'District', 32, 1),
(638, 'Warangal (Urban)', 'District', 32, 1),
(639, 'Yadadri Bhuvanagiri', 'District', 32, 1),
(640, 'Dhalai', 'District', 33, 1),
(641, 'Gomati', 'District', 33, 1),
(642, 'Khowai', 'District', 33, 1),
(643, 'North Tripura', 'District', 33, 1),
(644, 'Sepahijala', 'District', 33, 1),
(645, 'South Tripura', 'District', 33, 1),
(646, 'Unakoti', 'District', 33, 1),
(647, 'West Tripura', 'District', 33, 1),
(648, 'Almora', 'District', 34, 1),
(649, 'Bageshwar', 'District', 34, 1),
(650, 'Chamoli', 'District', 34, 1),
(651, 'Champawat', 'District', 34, 1),
(652, 'Dehradun', 'District', 34, 1),
(653, 'Haridwar', 'District', 34, 1),
(654, 'Nainital', 'District', 34, 1),
(655, 'Pauri Garhwal', 'District', 34, 1),
(656, 'Pithoragarh', 'District', 34, 1),
(657, 'Rudraprayag', 'District', 34, 1),
(658, 'Tehri Garhwal', 'District', 34, 1),
(659, 'Udham Singh Nagar', 'District', 34, 1),
(660, 'Uttarkashi', 'District', 34, 1),
(661, 'Agra', 'District', 35, 1),
(662, 'Aligarh', 'District', 35, 1),
(663, 'Allahabad', 'District', 35, 1),
(664, 'Ambedkar Nagar', 'District', 35, 1),
(665, 'Amethi (Chatrapati Sahuji Mahraj Nagar)', 'District', 35, 1),
(666, 'Amroha (J.P. Nagar)', 'District', 35, 1),
(667, 'Auraiya', 'District', 35, 1),
(668, 'Azamgarh', 'District', 35, 1),
(669, 'Baghpat', 'District', 35, 1),
(670, 'Bahraich', 'District', 35, 1),
(671, 'Ballia', 'District', 35, 1),
(672, 'Balrampur', 'District', 35, 1),
(673, 'Banda', 'District', 35, 1),
(674, 'Barabanki', 'District', 35, 1),
(675, 'Bareilly', 'District', 35, 1),
(676, 'Basti', 'District', 35, 1),
(677, 'Bhadohi', 'District', 35, 1),
(678, 'Bijnor', 'District', 35, 1),
(679, 'Budaun', 'District', 35, 1),
(680, 'Bulandshahr', 'District', 35, 1),
(681, 'Chandauli', 'District', 35, 1),
(682, 'Chitrakoot', 'District', 35, 1),
(683, 'Deoria', 'District', 35, 1),
(684, 'Etah', 'District', 35, 1),
(685, 'Etawah', 'District', 35, 1),
(686, 'Faizabad', 'District', 35, 1),
(687, 'Farrukhabad', 'District', 35, 1),
(688, 'Fatehpur', 'District', 35, 1),
(689, 'Firozabad', 'District', 35, 1),
(690, 'Gautam Buddha Nagar', 'District', 35, 1),
(691, 'Ghaziabad', 'District', 35, 1),
(692, 'Ghazipur', 'District', 35, 1),
(693, 'Gonda', 'District', 35, 1),
(694, 'Gorakhpur', 'District', 35, 1),
(695, 'Hamirpur', 'District', 35, 1),
(696, 'Hapur (Panchsheel Nagar)', 'District', 35, 1),
(697, 'Hardoi', 'District', 35, 1),
(698, 'Hathras', 'District', 35, 1),
(699, 'Jalaun', 'District', 35, 1),
(700, 'Jaunpur', 'District', 35, 1),
(701, 'Jhansi', 'District', 35, 1),
(702, 'Kannauj', 'District', 35, 1),
(703, 'Kanpur Dehat', 'District', 35, 1),
(704, 'Kanpur Nagar', 'District', 35, 1),
(705, 'Kanshiram Nagar (Kasganj)', 'District', 35, 1),
(706, 'Kaushambi', 'District', 35, 1),
(707, 'Kushinagar (Padrauna)', 'District', 35, 1),
(708, 'Lakhimpur - Kheri', 'District', 35, 1),
(709, 'Lalitpur', 'District', 35, 1),
(710, 'Lucknow', 'District', 35, 1),
(711, 'Maharajganj', 'District', 35, 1),
(712, 'Mahoba', 'District', 35, 1),
(713, 'Mainpuri', 'District', 35, 1),
(714, 'Mathura', 'District', 35, 1),
(715, 'Mau', 'District', 35, 1),
(716, 'Meerut', 'District', 35, 1),
(717, 'Mirzapur', 'District', 35, 1),
(718, 'Moradabad', 'District', 35, 1),
(719, 'Muzaffarnagar', 'District', 35, 1),
(720, 'Pilibhit', 'District', 35, 1),
(721, 'Pratapgarh', 'District', 35, 1),
(722, 'RaeBareli', 'District', 35, 1),
(723, 'Rampur', 'District', 35, 1),
(724, 'Saharanpur', 'District', 35, 1),
(725, 'Sambhal (Bhim Nagar)', 'District', 35, 1),
(726, 'Sant Kabir Nagar', 'District', 35, 1),
(727, 'Shahjahanpur', 'District', 35, 1),
(728, 'Shamali (Prabuddh Nagar)', 'District', 35, 1),
(729, 'Shravasti', 'District', 35, 1),
(730, 'Siddharth Nagar', 'District', 35, 1),
(731, 'Sitapur', 'District', 35, 1),
(732, 'Sonbhadra', 'District', 35, 1),
(733, 'Sultanpur', 'District', 35, 1),
(734, 'Unnao', 'District', 35, 1),
(735, 'Varanasi', 'District', 35, 1),
(736, 'Alipurduar', 'District', 36, 1),
(737, 'Bankura', 'District', 36, 1),
(738, 'Birbhum', 'District', 36, 1),
(739, 'Cooch Behar', 'District', 36, 1),
(740, 'Dakshin Dinajpur (South Dinajpur)', 'District', 36, 1),
(741, 'Darjeeling', 'District', 36, 1),
(742, 'Hooghly', 'District', 36, 1),
(743, 'Howrah', 'District', 36, 1),
(744, 'Jalpaiguri', 'District', 36, 1),
(745, 'Jhargram', 'District', 36, 1),
(746, 'Kalimpong', 'District', 36, 1),
(747, 'Kolkata', 'District', 36, 1),
(748, 'Malda', 'District', 36, 1),
(749, 'Murshidabad', 'District', 36, 1),
(750, 'Nadia', 'District', 36, 1),
(751, 'North 24 Parganas', 'District', 36, 1),
(752, 'Paschim Medinipur (West Medinipur)', 'District', 36, 1),
(753, 'Paschim (West) Burdwan (Bardhaman)', 'District', 36, 1),
(754, 'Purba Burdwan (Bardhaman)', 'District', 36, 1),
(755, 'Purba Medinipur (East Medinipur)', 'District', 36, 1),
(756, 'Purulia', 'District', 36, 1),
(757, 'South 24 Parganas', 'District', 36, 1),
(758, 'Uttar Dinajpur (North Dinajpur)', 'District', 36, 1);

-- --------------------------------------------------------

--
-- Table structure for table `dc_banks`
--

CREATE TABLE `dc_banks` (
  `id` int(11) NOT NULL,
  `name` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `dc_banks`
--

INSERT INTO `dc_banks` (`id`, `name`) VALUES
(1, 'Allahabad Bank'),
(2, 'Andhra Bank'),
(3, 'Axis Bank'),
(4, 'Bandhan Bank'),
(5, 'Bank of Baroda'),
(6, 'Bank of India'),
(7, 'Bank of Maharashtra'),
(8, 'Canara Bank'),
(9, 'Catholic Syrian Bank'),
(10, 'Central Bank of India'),
(11, 'City Union Bank'),
(12, 'Corporation Bank'),
(13, 'DCB Bank'),
(14, 'Dena Bank'),
(15, 'Dhanlaxmi Bank'),
(16, 'Federal Bank'),
(17, 'HDFC Bank'),
(18, 'ICICI Bank'),
(19, 'IDBI Bank'),
(20, 'IDFC Bank'),
(21, 'Indian Bank'),
(22, 'Indian Overseas Bank'),
(23, 'IndusInd Bank'),
(24, 'Jammu and Kashmir Bank'),
(25, 'Karnataka Bank'),
(26, 'Karur Vysya Bank'),
(27, 'Kotak Mahindra Bank'),
(28, 'Lakshmi Vilas Bank'),
(29, 'Nainital Bank'),
(30, 'Oriental Bank of Commerce'),
(31, 'Punjab & Sindh Bank'),
(32, 'Punjab National Bank'),
(33, 'RBL Bank'),
(34, 'South Indian Bank'),
(35, 'State Bank of India'),
(36, 'Syndicate Bank'),
(37, 'Tamilnad Mercantile Bank'),
(38, 'UCO Bank'),
(39, 'Union Bank of India'),
(40, 'United Bank of India'),
(41, 'Vijaya Bank'),
(42, 'YES Bank');

-- --------------------------------------------------------

--
-- Table structure for table `dc_contact`
--

CREATE TABLE `dc_contact` (
  `id` int(11) NOT NULL,
  `name` varchar(100) NOT NULL,
  `email` varchar(50) NOT NULL,
  `location` varchar(50) NOT NULL,
  `mobile` varchar(10) NOT NULL,
  `message` varchar(255) NOT NULL,
  `status` tinyint(1) NOT NULL DEFAULT 0,
  `added_on` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Table structure for table `dc_deposits`
--

CREATE TABLE `dc_deposits` (
  `id` int(11) NOT NULL,
  `date` date NOT NULL,
  `type` varchar(10) NOT NULL,
  `trans_type` varchar(20) NOT NULL,
  `package_id` int(11) NOT NULL,
  `regid` int(11) NOT NULL,
  `amount` float NOT NULL,
  `details` varchar(200) NOT NULL,
  `image` varchar(200) NOT NULL,
  `status` tinyint(1) NOT NULL DEFAULT 0,
  `complete` tinyint(1) NOT NULL DEFAULT 0,
  `approved_on` datetime DEFAULT NULL,
  `added_on` datetime NOT NULL,
  `updated_on` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Table structure for table `dc_epins`
--

CREATE TABLE `dc_epins` (
  `id` int(11) NOT NULL,
  `epin` varchar(20) NOT NULL,
  `regid` int(11) NOT NULL,
  `package_id` int(11) NOT NULL,
  `status` tinyint(1) NOT NULL DEFAULT 0,
  `added_on` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `dc_epin_requests`
--

CREATE TABLE `dc_epin_requests` (
  `id` int(11) NOT NULL,
  `regid` int(11) NOT NULL,
  `quantity` int(11) NOT NULL,
  `extra` int(11) NOT NULL,
  `package_id` int(11) NOT NULL,
  `type` varchar(10) NOT NULL,
  `trans_type` varchar(20) NOT NULL,
  `amount` float(14,2) NOT NULL,
  `date` date NOT NULL,
  `details` text NOT NULL,
  `image` varchar(255) DEFAULT NULL,
  `approve_date` date DEFAULT NULL,
  `reason` varchar(100) NOT NULL,
  `status` int(1) NOT NULL,
  `updated_on` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `dc_epin_transfer`
--

CREATE TABLE `dc_epin_transfer` (
  `id` int(11) NOT NULL,
  `type` varchar(10) NOT NULL DEFAULT 'transfer',
  `reg_from` int(11) NOT NULL,
  `reg_to` int(11) NOT NULL,
  `epin_id` int(11) NOT NULL,
  `added_on` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `dc_epin_used`
--

CREATE TABLE `dc_epin_used` (
  `id` int(11) NOT NULL,
  `epin_id` int(11) NOT NULL,
  `used_by` int(11) NOT NULL,
  `added_on` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `dc_helpdesk`
--

CREATE TABLE `dc_helpdesk` (
  `id` int(11) NOT NULL,
  `ticket_no` varchar(20) DEFAULT NULL,
  `date` date NOT NULL,
  `regid` int(11) NOT NULL,
  `message` text NOT NULL,
  `parent_id` int(11) DEFAULT NULL,
  `user_id` int(11) NOT NULL,
  `status` tinyint(1) NOT NULL DEFAULT 0,
  `added_on` datetime NOT NULL,
  `updated_on` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Table structure for table `dc_level_members`
--

CREATE TABLE `dc_level_members` (
  `id` int(11) NOT NULL,
  `regid` int(11) NOT NULL,
  `level_id` int(11) NOT NULL,
  `member_id` int(11) NOT NULL,
  `added_on` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `dc_members`
--

CREATE TABLE `dc_members` (
  `id` int(11) NOT NULL,
  `epin` varchar(20) NOT NULL,
  `name` varchar(100) NOT NULL,
  `dob` date DEFAULT NULL,
  `father` varchar(100) NOT NULL,
  `occupation` varchar(100) NOT NULL,
  `gender` varchar(6) NOT NULL,
  `mstatus` varchar(10) NOT NULL,
  `mobile` varchar(10) NOT NULL,
  `email` varchar(50) NOT NULL,
  `aadhar` varchar(12) NOT NULL,
  `pan` varchar(10) NOT NULL,
  `address` varchar(255) NOT NULL,
  `district` varchar(30) NOT NULL,
  `state` varchar(30) NOT NULL,
  `state_id` int(11) NOT NULL,
  `district_id` int(11) NOT NULL,
  `country` varchar(50) NOT NULL,
  `pincode` varchar(6) NOT NULL,
  `photo` varchar(200) NOT NULL,
  `regid` int(11) NOT NULL,
  `refid` int(11) NOT NULL,
  `date` date NOT NULL,
  `time` time NOT NULL,
  `package_id` int(11) NOT NULL,
  `activation_date` date DEFAULT NULL,
  `activation_time` time DEFAULT NULL,
  `contact_id` varchar(50) DEFAULT NULL,
  `status` int(11) NOT NULL,
  `franchise` tinyint(1) NOT NULL DEFAULT 0,
  `added_on` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `dc_member_club`
--

CREATE TABLE `dc_member_club` (
  `id` int(11) NOT NULL,
  `regid` int(11) NOT NULL,
  `club_id` int(11) NOT NULL,
  `ci_id` int(11) NOT NULL,
  `status` tinyint(1) NOT NULL DEFAULT 0,
  `added_on` datetime NOT NULL,
  `updated_on` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Table structure for table `dc_member_rewards`
--

CREATE TABLE `dc_member_rewards` (
  `id` int(11) NOT NULL,
  `date` date NOT NULL,
  `regid` int(11) NOT NULL,
  `reward_id` int(11) NOT NULL,
  `added_on` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Table structure for table `dc_member_tree`
--

CREATE TABLE `dc_member_tree` (
  `id` int(11) NOT NULL,
  `regid` int(11) NOT NULL,
  `parent_id` int(11) NOT NULL,
  `position` varchar(1) NOT NULL,
  `club_id` int(11) NOT NULL,
  `id_index` int(11) NOT NULL,
  `added_on` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Table structure for table `dc_news`
--

CREATE TABLE `dc_news` (
  `id` int(11) NOT NULL,
  `type` varchar(20) NOT NULL,
  `news` text NOT NULL,
  `status` tinyint(1) NOT NULL DEFAULT 1,
  `user_id` int(11) NOT NULL,
  `added_on` datetime NOT NULL,
  `updated_on` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Table structure for table `dc_nominee`
--

CREATE TABLE `dc_nominee` (
  `id` int(11) NOT NULL,
  `regid` int(11) NOT NULL,
  `name` varchar(100) NOT NULL,
  `mobile` varchar(20) NOT NULL,
  `dob` date NOT NULL,
  `relation` varchar(20) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `dc_packages`
--

CREATE TABLE `dc_packages` (
  `id` int(11) NOT NULL,
  `package` varchar(50) NOT NULL,
  `type` varchar(20) NOT NULL,
  `amount` float(14,2) NOT NULL,
  `direct` float NOT NULL,
  `daily_bonus` float NOT NULL,
  `discount` float NOT NULL,
  `min_withdrawal` float NOT NULL DEFAULT 50,
  `max_withdrawal` float NOT NULL,
  `club_id` int(11) NOT NULL,
  `status` tinyint(1) NOT NULL DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `dc_renewals`
--

CREATE TABLE `dc_renewals` (
  `id` int(11) NOT NULL,
  `regid` int(11) NOT NULL,
  `club_id` int(11) NOT NULL,
  `status` tinyint(1) NOT NULL DEFAULT 0,
  `added_on` datetime NOT NULL,
  `updated_on` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Table structure for table `dc_request_log`
--

CREATE TABLE `dc_request_log` (
  `id` int(11) NOT NULL,
  `url` varchar(255) NOT NULL,
  `ip_address` varchar(40) NOT NULL,
  `req_get` text DEFAULT NULL,
  `session` longtext DEFAULT NULL,
  `post` longtext DEFAULT NULL,
  `server` longtext DEFAULT NULL,
  `cookie` longtext DEFAULT NULL,
  `headers` longtext DEFAULT NULL,
  `added_on` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `dc_rewards`
--

CREATE TABLE `dc_rewards` (
  `id` int(11) NOT NULL,
  `team` float NOT NULL,
  `reward` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Table structure for table `dc_sidebar`
--

CREATE TABLE `dc_sidebar` (
  `id` int(11) NOT NULL,
  `activate_menu` varchar(255) NOT NULL,
  `activate_not` varchar(255) NOT NULL,
  `base_url` varchar(255) NOT NULL,
  `icon` varchar(255) NOT NULL,
  `name` varchar(255) NOT NULL,
  `parent` int(11) NOT NULL,
  `position` int(11) NOT NULL,
  `role_id` varchar(255) NOT NULL,
  `status` int(11) NOT NULL DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `dc_tokens`
--

CREATE TABLE `dc_tokens` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `token` varchar(50) NOT NULL,
  `device_id` varchar(255) NOT NULL,
  `device_name` varchar(100) NOT NULL,
  `regid` varchar(255) NOT NULL,
  `status` tinyint(1) NOT NULL DEFAULT 1,
  `added_on` datetime NOT NULL,
  `updated_on` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `dc_users`
--

CREATE TABLE `dc_users` (
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
  `txn_password` varchar(100) NOT NULL,
  `token` varchar(50) NOT NULL,
  `photo` varchar(100) NOT NULL,
  `status` tinyint(1) NOT NULL DEFAULT 0,
  `created_on` datetime NOT NULL,
  `updated_on` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `dc_users`
--

INSERT INTO `dc_users` (`id`, `username`, `mobile`, `name`, `email`, `password`, `vp`, `role`, `salt`, `otp`, `txn_password`, `token`, `photo`, `status`, `created_on`, `updated_on`) VALUES
(1, 'admin', '7894561230', 'Admin', 'admin@gmail.com', '$2y$10$Lg5BcTl6Db9UkQKXYcdtEe4Vgydny.idN782s6VKDtTaRgkth0jHa', '12345', 'admin', 'MOvosy97ax56IkQD', '', '', '', '', 1, '2020-01-07 17:05:51', '2025-08-02 00:08:57');

-- --------------------------------------------------------

--
-- Table structure for table `dc_wallet`
--

CREATE TABLE `dc_wallet` (
  `id` int(11) NOT NULL,
  `date` date NOT NULL,
  `regid` int(11) NOT NULL,
  `type` varchar(10) NOT NULL,
  `level_id` int(11) NOT NULL,
  `package_id` int(11) NOT NULL,
  `members` int(11) NOT NULL,
  `member_id` int(11) NOT NULL,
  `deposit_id` int(11) NOT NULL,
  `roi_income` decimal(16,3) NOT NULL,
  `ads` int(11) NOT NULL,
  `ad_rate` float(16,2) NOT NULL,
  `percent` float NOT NULL,
  `royalty_id` int(11) NOT NULL,
  `renewal_id` int(11) NOT NULL,
  `amount` decimal(16,3) NOT NULL,
  `remarks` varchar(50) NOT NULL,
  `added_on` datetime NOT NULL,
  `updated_on` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `dc_wallet_transfers`
--

CREATE TABLE `dc_wallet_transfers` (
  `id` int(11) NOT NULL,
  `date` date NOT NULL,
  `reg_from` int(11) NOT NULL,
  `reg_to` int(11) NOT NULL,
  `type_from` varchar(10) NOT NULL,
  `type_to` varchar(10) NOT NULL,
  `amount` float(16,2) NOT NULL,
  `added_on` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `dc_withdrawals`
--

CREATE TABLE `dc_withdrawals` (
  `id` int(11) NOT NULL,
  `regid` int(11) NOT NULL,
  `trans_type` varchar(20) NOT NULL,
  `date` date NOT NULL,
  `amount` float(16,2) NOT NULL,
  `tds` float(16,2) NOT NULL,
  `admin_charge` float(16,2) NOT NULL,
  `payable` float(16,2) NOT NULL,
  `approve_date` date DEFAULT NULL,
  `order_id` varchar(100) DEFAULT NULL,
  `response` varchar(500) DEFAULT NULL,
  `status` tinyint(1) NOT NULL DEFAULT 0,
  `updated_on` datetime NOT NULL,
  `added_on` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `dc_acc_details`
--
ALTER TABLE `dc_acc_details`
  ADD PRIMARY KEY (`id`),
  ADD KEY `FK_accreg` (`regid`);

--
-- Indexes for table `dc_area`
--
ALTER TABLE `dc_area`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `dc_banks`
--
ALTER TABLE `dc_banks`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `name` (`name`);

--
-- Indexes for table `dc_contact`
--
ALTER TABLE `dc_contact`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `dc_deposits`
--
ALTER TABLE `dc_deposits`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `dc_epins`
--
ALTER TABLE `dc_epins`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `epin` (`epin`);

--
-- Indexes for table `dc_epin_requests`
--
ALTER TABLE `dc_epin_requests`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `dc_epin_transfer`
--
ALTER TABLE `dc_epin_transfer`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `dc_epin_used`
--
ALTER TABLE `dc_epin_used`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `dc_helpdesk`
--
ALTER TABLE `dc_helpdesk`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `ticket_no` (`ticket_no`);

--
-- Indexes for table `dc_level_members`
--
ALTER TABLE `dc_level_members`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_lmregid` (`regid`),
  ADD KEY `fk_lmmid` (`member_id`);

--
-- Indexes for table `dc_members`
--
ALTER TABLE `dc_members`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `regid` (`regid`),
  ADD KEY `FK_ref` (`refid`);

--
-- Indexes for table `dc_member_club`
--
ALTER TABLE `dc_member_club`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `dc_member_rewards`
--
ALTER TABLE `dc_member_rewards`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `dc_member_tree`
--
ALTER TABLE `dc_member_tree`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `dc_news`
--
ALTER TABLE `dc_news`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `dc_nominee`
--
ALTER TABLE `dc_nominee`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_nregid` (`regid`);

--
-- Indexes for table `dc_packages`
--
ALTER TABLE `dc_packages`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `package` (`package`);

--
-- Indexes for table `dc_renewals`
--
ALTER TABLE `dc_renewals`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `dc_request_log`
--
ALTER TABLE `dc_request_log`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `dc_rewards`
--
ALTER TABLE `dc_rewards`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `dc_sidebar`
--
ALTER TABLE `dc_sidebar`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `dc_tokens`
--
ALTER TABLE `dc_tokens`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `dc_users`
--
ALTER TABLE `dc_users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `username` (`username`);

--
-- Indexes for table `dc_wallet`
--
ALTER TABLE `dc_wallet`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `dc_wallet_transfers`
--
ALTER TABLE `dc_wallet_transfers`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `dc_withdrawals`
--
ALTER TABLE `dc_withdrawals`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `dc_acc_details`
--
ALTER TABLE `dc_acc_details`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `dc_area`
--
ALTER TABLE `dc_area`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=759;

--
-- AUTO_INCREMENT for table `dc_banks`
--
ALTER TABLE `dc_banks`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=43;

--
-- AUTO_INCREMENT for table `dc_contact`
--
ALTER TABLE `dc_contact`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `dc_deposits`
--
ALTER TABLE `dc_deposits`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `dc_epins`
--
ALTER TABLE `dc_epins`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `dc_epin_requests`
--
ALTER TABLE `dc_epin_requests`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `dc_epin_transfer`
--
ALTER TABLE `dc_epin_transfer`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `dc_epin_used`
--
ALTER TABLE `dc_epin_used`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `dc_helpdesk`
--
ALTER TABLE `dc_helpdesk`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `dc_level_members`
--
ALTER TABLE `dc_level_members`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `dc_members`
--
ALTER TABLE `dc_members`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `dc_member_club`
--
ALTER TABLE `dc_member_club`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `dc_member_rewards`
--
ALTER TABLE `dc_member_rewards`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `dc_member_tree`
--
ALTER TABLE `dc_member_tree`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `dc_news`
--
ALTER TABLE `dc_news`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `dc_nominee`
--
ALTER TABLE `dc_nominee`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `dc_packages`
--
ALTER TABLE `dc_packages`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `dc_renewals`
--
ALTER TABLE `dc_renewals`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `dc_request_log`
--
ALTER TABLE `dc_request_log`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `dc_rewards`
--
ALTER TABLE `dc_rewards`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `dc_sidebar`
--
ALTER TABLE `dc_sidebar`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `dc_tokens`
--
ALTER TABLE `dc_tokens`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `dc_users`
--
ALTER TABLE `dc_users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `dc_wallet`
--
ALTER TABLE `dc_wallet`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `dc_wallet_transfers`
--
ALTER TABLE `dc_wallet_transfers`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `dc_withdrawals`
--
ALTER TABLE `dc_withdrawals`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `dc_acc_details`
--
ALTER TABLE `dc_acc_details`
  ADD CONSTRAINT `FK_accreg` FOREIGN KEY (`regid`) REFERENCES `dc_users` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `dc_level_members`
--
ALTER TABLE `dc_level_members`
  ADD CONSTRAINT `fk_lmmid` FOREIGN KEY (`member_id`) REFERENCES `dc_users` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_lmregid` FOREIGN KEY (`regid`) REFERENCES `dc_users` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `dc_members`
--
ALTER TABLE `dc_members`
  ADD CONSTRAINT `FK_ref` FOREIGN KEY (`refid`) REFERENCES `dc_users` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `FK_reg` FOREIGN KEY (`regid`) REFERENCES `dc_users` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `dc_nominee`
--
ALTER TABLE `dc_nominee`
  ADD CONSTRAINT `fk_nregid` FOREIGN KEY (`regid`) REFERENCES `dc_users` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
