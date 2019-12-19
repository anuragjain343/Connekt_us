-- phpMyAdmin SQL Dump
-- version 4.7.4
-- https://www.phpmyadmin.net/
--
-- Host: localhost
-- Generation Time: Apr 13, 2019 at 09:35 AM
-- Server version: 10.1.28-MariaDB
-- PHP Version: 7.0.25

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `connektus`
--

-- --------------------------------------------------------

--
-- Table structure for table `admin`
--

CREATE TABLE `admin` (
  `id` int(10) NOT NULL,
  `name` varchar(500) NOT NULL,
  `email` varchar(60) DEFAULT NULL,
  `password` varchar(300) DEFAULT NULL,
  `image` varchar(255) NOT NULL,
  `status` enum('0','1') NOT NULL DEFAULT '1' COMMENT '1:Active,:0:Deactive',
  `crd` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `upd` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `admin`
--

INSERT INTO `admin` (`id`, `name`, `email`, `password`, `image`, `status`, `crd`, `upd`) VALUES
(1, 'admin', 'admin@connektus.com.au', '$2y$10$.KJbw./imTTlcuPuvTqBS.C/sTLgb1C1D1.FA02QuQxPXAYsnHjj2', '2e4819d12419c39f5412b69a266bf2bc.jpg', '1', '2017-09-27 11:44:10', '2018-10-03 09:15:15');

-- --------------------------------------------------------

--
-- Table structure for table `contactUs`
--

CREATE TABLE `contactUs` (
  `contactUsId` bigint(20) NOT NULL,
  `name` varchar(100) NOT NULL,
  `subject` varchar(100) NOT NULL,
  `email` varchar(250) NOT NULL,
  `message` text NOT NULL,
  `rating` varchar(100) NOT NULL,
  `status` tinyint(4) NOT NULL DEFAULT '1' COMMENT '1 for open, 0 for closed',
  `type` varchar(100) NOT NULL,
  `crd` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `upd` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `favourites`
--

CREATE TABLE `favourites` (
  `favouriteId` int(11) NOT NULL,
  `favourite_for` int(11) NOT NULL,
  `favourite_by` int(11) NOT NULL,
  `status` tinyint(4) NOT NULL DEFAULT '1',
  `created_on` varchar(250) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `interviews`
--

CREATE TABLE `interviews` (
  `interviewId` int(11) NOT NULL,
  `request_id` int(11) NOT NULL,
  `type` varchar(250) NOT NULL,
  `interviewer_name` varchar(250) NOT NULL,
  `location` varchar(250) NOT NULL,
  `latitude` varchar(250) NOT NULL,
  `longitude` varchar(250) NOT NULL,
  `date` varchar(250) NOT NULL,
  `time` varchar(250) NOT NULL,
  `is_delete` int(11) NOT NULL DEFAULT '0' COMMENT '0 for non delte 1 for delete',
  `interview_status` tinyint(4) NOT NULL DEFAULT '0' COMMENT '0 for request sent: 1 for accept: 2 for declined',
  `created_on` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `upd` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00'
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `interview_request`
--

CREATE TABLE `interview_request` (
  `requestId` int(11) NOT NULL,
  `interview_id` int(11) NOT NULL,
  `type` varchar(250) NOT NULL,
  `interviewer_name` varchar(250) NOT NULL,
  `location` varchar(250) NOT NULL,
  `latitude` varchar(250) NOT NULL,
  `longitude` varchar(250) NOT NULL,
  `date` varchar(250) NOT NULL,
  `time` varchar(250) NOT NULL,
  `stauts` tinyint(4) NOT NULL DEFAULT '1',
  `created_on` varchar(250) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `job_titles`
--

CREATE TABLE `job_titles` (
  `jobTitleId` int(11) NOT NULL,
  `jobTitleName` varchar(255) NOT NULL,
  `userType` varchar(255) NOT NULL,
  `status` tinyint(4) NOT NULL DEFAULT '1',
  `crd` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `upd` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `job_titles`
--

INSERT INTO `job_titles` (`jobTitleId`, `jobTitleName`, `userType`, `status`, `crd`, `upd`) VALUES
(3, 'Java Developer', 'both', 1, '2018-04-20 12:43:55', '2018-08-28 07:43:15'),
(4, 'Android Developer', 'both', 1, '2018-04-20 13:11:45', '2018-08-28 07:43:15'),
(5, 'Manager', 'both', 1, '2018-04-26 05:32:35', '2018-08-28 07:43:15'),
(6, 'Web Designer', 'both', 1, '2018-05-02 13:56:20', '2018-08-28 07:43:15'),
(7, 'Chief Executive Officer', 'both', 1, '2018-05-02 23:38:23', '2018-10-26 06:27:03'),
(8, 'Executive Assistant', 'both', 1, '2018-05-02 23:38:52', '2018-08-28 07:43:15'),
(9, 'Senior Credit Officer', 'both', 1, '2018-05-03 05:03:35', '2018-08-28 07:43:15'),
(10, 'Credit Manager', 'both', 1, '2018-05-03 05:03:48', '2018-08-28 07:43:15'),
(11, 'Team Leader', 'both', 1, '2018-05-03 05:03:59', '2018-08-28 07:43:15'),
(13, 'Business Analyst', 'both', 1, '2018-05-03 05:04:22', '2018-08-28 07:43:15'),
(14, 'Recepionist', 'both', 1, '2018-05-03 05:05:14', '2018-08-28 07:43:15'),
(15, 'Administration Manager', 'both', 1, '2018-05-03 05:05:28', '2018-08-28 07:43:15'),
(16, 'Chief Financial Officer', 'both', 1, '2018-05-03 05:05:48', '2018-08-28 07:43:15'),
(17, 'Financial Controller', 'both', 1, '2018-05-03 05:05:59', '2018-08-28 07:43:15'),
(18, 'Tax Accountant', 'both', 1, '2018-05-03 05:06:11', '2018-08-28 07:43:15'),
(19, 'HOD', 'both', 1, '2018-05-22 05:03:12', '2018-08-28 07:43:15'),
(20, 'Graphics Designer', 'both', 1, '2018-05-22 05:03:49', '2018-08-28 07:43:15'),
(22, 'Administration Assistant', 'both', 1, '2018-05-24 05:56:51', '2018-08-28 07:43:15'),
(23, 'Senior Payroll Officer', 'both', 1, '2018-05-24 05:57:03', '2018-08-28 07:43:15'),
(24, 'Payroll Officer', 'both', 1, '2018-05-24 05:57:13', '2018-08-28 07:43:15'),
(25, 'Graduate Accountant', 'both', 1, '2018-05-24 05:57:21', '2018-08-28 07:43:15'),
(26, 'Senior Accountant', 'both', 1, '2018-05-24 05:57:31', '2018-08-28 07:43:15'),
(27, 'Data Analyst', 'both', 1, '2018-05-24 05:57:38', '2018-08-28 07:43:15'),
(28, 'Finance Business Partner', 'both', 1, '2018-05-24 05:57:44', '2018-08-28 07:43:15'),
(29, 'Supervisor Accountant', 'both', 1, '2018-05-24 05:57:52', '2018-08-28 07:43:15'),
(30, 'Financial Accountant', 'both', 1, '2018-05-24 05:57:57', '2018-08-28 07:43:15'),
(31, 'Revenue Accountant', 'both', 1, '2018-05-24 05:58:04', '2018-08-28 07:43:15'),
(32, 'Senior Commerical Analyst', 'both', 1, '2018-05-24 05:58:10', '2018-08-28 07:43:15'),
(34, 'Assistant Manager', 'both', 1, '2018-05-24 05:58:21', '2018-08-28 07:43:15'),
(35, 'Accounts Receivable Team Leader', 'both', 1, '2018-05-24 05:58:27', '2018-08-28 07:43:15'),
(36, 'Accounts Payable Officer', 'both', 1, '2018-05-24 05:58:39', '2018-08-28 07:43:15'),
(37, 'Accounts Receivable Officer', 'both', 1, '2018-05-24 05:58:47', '2018-08-28 07:43:15'),
(40, 'Accountant', 'both', 1, '2018-05-24 05:59:31', '2018-08-28 07:43:15'),
(41, 'General Manager', 'both', 1, '2018-05-24 05:59:39', '2018-08-28 07:43:15'),
(42, 'Accounts Clerk', 'both', 1, '2018-05-24 05:59:48', '2018-08-28 07:43:15'),
(43, 'Senior Assistant Accountant', 'both', 1, '2018-05-24 05:59:54', '2018-08-28 07:43:15'),
(44, 'Salesforce Technical Lead', 'both', 1, '2018-05-24 06:00:00', '2018-08-28 07:43:15'),
(45, 'Senior Tax Accountant', 'both', 1, '2018-05-24 06:00:06', '2018-08-28 07:43:15'),
(46, 'Senior Financial Accountant', 'both', 1, '2018-05-24 06:00:11', '2018-08-28 07:43:15'),
(47, 'Junior Collections Officer', 'both', 1, '2018-05-24 06:00:17', '2018-08-28 07:43:15'),
(48, 'Finance Officer', 'both', 1, '2018-05-24 06:00:23', '2018-08-28 07:43:15'),
(49, 'Finance Manager', 'both', 1, '2018-05-24 06:00:29', '2018-08-28 07:43:15'),
(50, 'Credit Controller', 'both', 1, '2018-05-24 06:00:35', '2018-08-28 07:43:15'),
(51, 'Senior Credit Controller', 'both', 1, '2018-05-24 06:00:40', '2018-08-28 07:43:15'),
(53, 'Credit Team Leader', 'both', 1, '2018-05-24 06:00:52', '2018-08-28 07:43:15'),
(54, 'Senior Finance Officer', 'both', 1, '2018-05-24 06:00:57', '2018-08-28 07:43:15'),
(55, 'Book Keeper', 'both', 1, '2018-05-24 06:01:03', '2018-10-26 06:31:14'),
(56, 'Finance Analyst', 'both', 1, '2018-05-24 06:01:08', '2018-08-28 07:43:15'),
(57, 'Internal Auditor', 'both', 1, '2018-05-24 06:01:14', '2018-08-28 07:43:15'),
(58, 'Senior Internal Auditor', 'both', 1, '2018-05-24 06:01:19', '2018-08-28 07:43:15'),
(59, 'Auditor', 'both', 1, '2018-05-24 06:01:24', '2018-08-28 07:43:15'),
(60, 'Billing Officer', 'both', 1, '2018-05-24 06:01:30', '2018-08-28 07:43:15'),
(61, 'Senior Billing Officer', 'both', 1, '2018-05-24 06:01:36', '2018-08-28 07:43:15'),
(63, 'Client Support', 'both', 1, '2018-05-24 06:01:48', '2018-08-28 07:43:15'),
(64, 'Project Administrator', 'both', 1, '2018-05-24 06:01:54', '2018-08-28 07:43:15'),
(65, 'Project Officer', 'both', 1, '2018-05-24 06:02:00', '2018-08-28 07:43:15'),
(66, 'Senior Contracts Administrator', 'both', 1, '2018-05-24 06:02:06', '2018-08-28 07:43:15'),
(67, 'Project Coordinator', 'both', 1, '2018-05-24 06:02:13', '2018-08-28 07:43:15'),
(68, 'Administration Officer', 'both', 1, '2018-05-24 06:02:19', '2018-08-28 07:43:15'),
(69, 'Senior Business Analyst', 'both', 1, '2018-05-24 06:02:25', '2018-10-26 06:31:50'),
(70, 'Training Coordinator', 'both', 1, '2018-05-24 06:02:31', '2018-08-28 07:43:15'),
(71, 'Commercial Officer', 'both', 1, '2018-05-24 06:02:36', '2018-08-28 07:43:15'),
(72, 'Officer Manager', 'both', 1, '2018-05-24 06:02:43', '2018-08-28 07:43:15'),
(74, 'Group Account Director', 'both', 1, '2018-05-24 06:03:03', '2018-08-28 07:43:15'),
(75, 'Group Sales Manager', 'both', 1, '2018-05-24 06:03:09', '2018-08-28 07:43:15'),
(76, 'Executive Director', 'both', 1, '2018-05-24 06:03:14', '2018-08-28 07:43:15'),
(77, 'Creative Manager', 'both', 1, '2018-05-24 06:03:20', '2018-08-28 07:43:15'),
(78, 'Junior Designer', 'both', 1, '2018-05-24 06:03:27', '2018-08-28 07:43:15'),
(79, 'Chief Of Staff', 'both', 1, '2018-05-24 06:03:35', '2018-08-28 07:43:15'),
(80, 'Senior Chief Of Staff', 'both', 1, '2018-05-24 06:03:41', '2018-08-28 07:43:15'),
(81, 'Marketing Coordinator', 'both', 1, '2018-05-24 06:03:47', '2018-08-28 07:43:15'),
(82, 'Associate Director', 'both', 1, '2018-05-24 06:03:54', '2018-08-28 07:43:15'),
(83, 'Business Manager', 'both', 1, '2018-05-24 06:04:00', '2018-08-28 07:43:15'),
(84, 'Compliance Manager', 'both', 1, '2018-05-24 06:04:05', '2018-08-28 07:43:15'),
(86, 'Executive', 'both', 1, '2018-08-28 07:16:44', '2018-08-28 07:16:44');

-- --------------------------------------------------------

--
-- Table structure for table `notifications`
--

CREATE TABLE `notifications` (
  `id` bigint(20) NOT NULL,
  `notification_by` int(11) NOT NULL,
  `notification_for` int(11) NOT NULL,
  `notification_message` text NOT NULL,
  `notification_type` varchar(100) NOT NULL,
  `isViewed` tinyint(4) NOT NULL DEFAULT '0' COMMENT '0: not viewed, 1: viewed',
  `status` int(11) NOT NULL DEFAULT '1' COMMENT '1:active, 0:Inactive',
  `created_on` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `options`
--

CREATE TABLE `options` (
  `optionId` bigint(20) NOT NULL,
  `option_name` varchar(100) NOT NULL,
  `option_value` text NOT NULL,
  `crd` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `upd` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `options`
--

INSERT INTO `options` (`optionId`, `option_name`, `option_value`, `crd`, `upd`) VALUES
(1, 'tc_page', 'ConnektUs_Terms_-_No_Subscription3.pdf', '2018-06-13 11:18:39', '2018-10-11 09:47:59'),
(4, 'about_page', 'About_Us_app.pdf', '2018-06-13 13:20:04', '2018-08-24 06:51:49'),
(5, 'contact_type', 'Help_&_support_job_seeker.pdf', '2018-06-13 13:39:30', '2018-08-24 10:30:00'),
(6, 'pp_page', 'ConnektUs_Privacy4.pdf', '2018-08-13 08:54:47', '2018-10-16 09:20:18'),
(7, 'contact_type_employer', 'Help_&_support_employer.pdf', '2018-08-24 09:49:07', '2018-08-24 10:30:05'),
(8, 'verify_email', '0', '2018-10-03 05:59:16', '2018-10-27 10:18:10');

-- --------------------------------------------------------

--
-- Table structure for table `recommends`
--

CREATE TABLE `recommends` (
  `recommendId` int(11) NOT NULL,
  `recommend_for` int(11) NOT NULL,
  `recommend_by` int(11) NOT NULL,
  `status` tinyint(4) NOT NULL DEFAULT '1',
  `created_on` varchar(250) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `requests`
--

CREATE TABLE `requests` (
  `requestId` int(11) NOT NULL,
  `request_by` int(11) NOT NULL,
  `request_for` int(11) NOT NULL,
  `request_offer_status` tinyint(4) NOT NULL DEFAULT '0' COMMENT '2: not offer, 1: offered,0 for pending',
  `is_finished` tinyint(4) NOT NULL DEFAULT '0' COMMENT '0 for running: 1 for completed: 2 for deleted',
  `created_on` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `upd` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `reviews`
--

CREATE TABLE `reviews` (
  `reviewId` int(20) NOT NULL,
  `review_by` int(20) NOT NULL,
  `review_for` int(20) NOT NULL,
  `rating` float NOT NULL DEFAULT '0',
  `comments` varchar(250) CHARACTER SET utf8mb4 COLLATE utf8mb4_bin NOT NULL,
  `is_anonymous` tinyint(4) NOT NULL DEFAULT '0',
  `status` int(11) NOT NULL DEFAULT '1' COMMENT '1: Active, 0: Inactive ',
  `created_on` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `specializations`
--

CREATE TABLE `specializations` (
  `specializationId` int(11) NOT NULL,
  `specializationName` varchar(200) NOT NULL,
  `userType` varchar(255) NOT NULL,
  `status` tinyint(4) NOT NULL DEFAULT '1',
  `crd` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `upd` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `specializations`
--

INSERT INTO `specializations` (`specializationId`, `specializationName`, `userType`, `status`, `crd`, `upd`) VALUES
(3, 'Sales', 'both', 1, '2018-04-20 12:46:54', '2018-08-28 09:50:28'),
(4, 'Banking And Financial Services', 'both', 1, '2018-04-20 12:47:14', '2018-08-28 09:50:28'),
(6, 'Accounting And Finance', 'both', 1, '2018-05-04 00:41:19', '2018-08-28 09:50:28'),
(8, 'Human Resources', 'both', 1, '2018-05-04 00:42:29', '2018-08-28 09:50:28'),
(11, 'Construction', 'both', 1, '2018-05-04 00:51:26', '2018-08-28 09:50:28'),
(13, 'Engineering And Manufacturing', 'both', 1, '2018-05-04 00:51:54', '2018-08-28 09:50:28'),
(14, 'Legal', 'both', 1, '2018-05-04 00:52:34', '2018-08-28 09:50:28'),
(15, 'Retail', 'both', 1, '2018-05-04 00:52:47', '2018-08-28 09:50:28'),
(16, 'Information Technology', 'both', 1, '2018-05-04 00:53:02', '2018-08-28 09:50:28'),
(17, 'Marketing And Digital', 'both', 1, '2018-05-04 00:53:53', '2018-08-28 09:50:28'),
(18, 'Trades And Labour', 'both', 1, '2018-05-04 00:55:24', '2018-08-28 09:50:28'),
(19, 'Executive', 'both', 1, '2018-05-04 00:56:42', '2018-08-28 09:50:28'),
(20, 'Warehouse And Logistics', 'both', 1, '2018-05-04 00:57:21', '2018-08-28 09:50:28'),
(21, 'Business Support', 'both', 1, '2018-05-04 00:57:36', '2018-08-28 09:50:28'),
(31, 'Mining And Resources', 'both', 1, '2018-05-13 06:03:47', '2018-08-28 09:50:28'),
(32, 'Consulting And Strategy', 'both', 1, '2018-05-13 06:04:28', '2018-08-28 09:50:28'),
(33, 'Trades And Services', 'both', 1, '2018-05-13 06:04:43', '2018-08-28 09:50:28'),
(34, 'Science And Technology', 'both', 1, '2018-05-13 06:05:00', '2018-08-28 09:50:28'),
(35, 'Engineering', 'both', 1, '2018-05-13 06:05:12', '2018-08-28 09:50:28'),
(36, 'Human Resources And Recruitment', 'both', 1, '2018-05-13 06:05:30', '2018-08-28 09:50:28'),
(37, 'Hospitality And Tourism', 'both', 1, '2018-05-13 06:05:51', '2018-08-28 09:50:28'),
(38, 'Government And Defence', 'both', 1, '2018-05-13 06:06:05', '2018-08-28 09:50:28'),
(39, 'Information And Communication Technology', 'both', 1, '2018-05-13 06:06:25', '2018-08-28 09:50:28'),
(40, 'Transport And Logistics', 'both', 1, '2018-05-13 06:07:09', '2018-08-28 09:50:28'),
(42, 'Healthcare And Medical', 'both', 1, '2018-05-13 06:07:31', '2018-08-28 09:50:28'),
(43, 'Education And Training', 'both', 1, '2018-05-13 06:07:43', '2018-08-28 09:50:28'),
(44, 'Administration And Office Support', 'both', 1, '2018-05-13 06:08:04', '2018-08-28 09:50:28'),
(45, 'Marketing And Communication', 'both', 1, '2018-05-13 06:08:19', '2018-08-28 09:50:28'),
(47, 'Insurance And Superannuation', 'both', 1, '2018-05-13 06:09:30', '2018-08-28 09:50:28'),
(48, 'Sports And Recreation', 'both', 1, '2018-05-13 06:09:41', '2018-08-28 09:50:28'),
(49, 'Advertising And Media', 'both', 1, '2018-05-13 06:09:54', '2018-08-28 09:50:28'),
(50, 'Design And Architecture', 'both', 1, '2018-05-13 06:10:09', '2018-08-28 09:50:28'),
(51, 'Accounting', 'both', 1, '2018-05-13 06:10:21', '2018-08-28 09:50:28'),
(55, 'Real Estate And Property', 'both', 1, '2018-05-13 06:11:26', '2018-08-28 09:50:28'),
(56, 'Call Centre And Customer Service', 'both', 1, '2018-05-13 06:11:41', '2018-08-28 09:50:28');

-- --------------------------------------------------------

--
-- Table structure for table `strengths`
--

CREATE TABLE `strengths` (
  `strengthId` int(11) NOT NULL,
  `strengthName` varchar(255) NOT NULL,
  `status` tinyint(4) NOT NULL DEFAULT '1',
  `crd` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `upd` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `strengths`
--

INSERT INTO `strengths` (`strengthId`, `strengthName`, `status`, `crd`, `upd`) VALUES
(2, 'Decision Making', 1, '2018-04-20 12:22:06', '2018-05-04 01:02:10'),
(3, 'Flexibility And Adaptability', 1, '2018-04-20 12:46:27', '2018-05-04 01:01:58'),
(4, 'Written And Verbal Communication Skills', 1, '2018-04-20 12:46:41', '2018-05-04 01:01:25'),
(5, 'Work Ethic', 1, '2018-04-26 11:08:38', '2018-05-04 01:01:13'),
(6, 'Coaching And Mentoring', 1, '2018-05-04 01:02:22', '2018-05-04 01:02:22'),
(7, 'Problem Solving', 1, '2018-05-04 01:02:33', '2018-05-04 01:02:33'),
(8, 'Team Work', 1, '2018-05-04 01:02:43', '2018-05-04 01:02:43'),
(9, 'Reliable', 1, '2018-05-04 01:02:56', '2018-05-04 01:02:56'),
(10, 'Resilience', 1, '2018-05-04 01:03:15', '2018-05-04 01:03:15'),
(12, 'Maintaining Deadlines', 1, '2018-05-04 01:05:04', '2018-05-04 01:05:04'),
(13, 'Delegation', 1, '2018-05-04 01:05:26', '2018-05-04 01:05:26'),
(14, 'Detailed And Thorough', 1, '2018-05-04 01:05:48', '2018-05-04 01:05:48'),
(15, 'Empowering', 1, '2018-05-04 01:05:58', '2018-05-04 01:05:58'),
(16, 'Effective Feedback', 1, '2018-05-04 01:06:41', '2018-05-04 01:06:41'),
(17, 'Presentation Skills', 1, '2018-05-04 01:07:43', '2018-05-04 01:07:43'),
(18, 'Hard Working', 1, '2018-05-04 01:08:34', '2018-05-04 01:08:34'),
(19, 'Commitment And Dedication', 1, '2018-05-04 01:08:48', '2018-05-04 02:28:40'),
(20, 'Enthusiatic', 1, '2018-05-04 01:11:43', '2018-05-04 01:12:55'),
(22, 'Creative', 1, '2018-05-04 01:12:01', '2018-05-13 06:27:36'),
(23, 'Patience', 1, '2018-05-04 01:12:09', '2018-05-04 01:12:09'),
(25, 'Determination', 1, '2018-05-04 01:12:24', '2018-05-04 01:12:24'),
(27, 'Positive Attitude', 1, '2018-05-04 01:13:58', '2018-05-04 01:13:58'),
(28, 'Self Motivating', 1, '2018-05-04 01:14:29', '2018-05-04 01:14:29'),
(29, 'Innovation', 1, '2018-05-04 02:26:19', '2018-05-04 02:26:19'),
(30, 'Willingness To Learn', 1, '2018-05-04 02:27:07', '2018-05-04 02:27:07'),
(31, 'Time Management Skills', 1, '2018-05-04 02:27:18', '2018-05-04 02:27:18'),
(32, 'Adaptability', 1, '2018-05-04 02:27:33', '2018-05-04 02:27:33'),
(33, 'Interpersonal Skills', 1, '2018-05-04 02:28:03', '2018-05-04 02:28:03'),
(35, 'Leadership Skills', 1, '2018-05-13 06:14:15', '2018-05-13 06:18:09'),
(36, 'Dependability', 1, '2018-05-13 06:14:29', '2018-05-13 06:14:29'),
(37, 'Efficient', 1, '2018-05-13 06:16:34', '2018-05-13 06:16:34'),
(38, 'Effective', 1, '2018-05-13 06:16:42', '2018-05-13 06:16:42'),
(39, 'Accurate', 1, '2018-05-13 06:16:54', '2018-05-13 06:16:54'),
(40, 'Over Achiever', 1, '2018-05-13 06:17:31', '2018-05-13 06:17:31'),
(41, 'Inspirational', 1, '2018-05-13 06:17:43', '2018-05-13 06:17:43'),
(42, 'Listening Skills', 1, '2018-05-13 06:18:17', '2018-05-13 06:18:17'),
(43, 'People Skills', 1, '2018-05-13 06:18:36', '2018-05-13 06:18:36'),
(44, 'Professional', 1, '2018-05-13 06:18:44', '2018-05-13 06:18:44'),
(45, 'Public Speaking', 1, '2018-05-13 06:18:55', '2018-05-13 06:18:55'),
(46, 'Conflict Resolution', 1, '2018-05-13 06:19:08', '2018-05-13 06:19:08'),
(47, 'Confidence', 1, '2018-05-13 06:34:45', '2018-05-17 06:01:37');

-- --------------------------------------------------------

--
-- Table structure for table `usermeta`
--

CREATE TABLE `usermeta` (
  `metaID` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `jobTitle_id` int(11) NOT NULL,
  `address` varchar(250) CHARACTER SET utf8 COLLATE utf8_bin NOT NULL,
  `city` varchar(250) CHARACTER SET utf8 COLLATE utf8_bin NOT NULL,
  `state` varchar(250) CHARACTER SET utf8 COLLATE utf8_bin NOT NULL,
  `country` varchar(250) CHARACTER SET utf8 COLLATE utf8_bin NOT NULL,
  `latitude` varchar(250) NOT NULL,
  `longitude` varchar(250) NOT NULL,
  `bio` text CHARACTER SET utf8mb4 NOT NULL,
  `description` text NOT NULL,
  `phone` varchar(100) NOT NULL,
  `user_resume` varchar(250) NOT NULL,
  `user_cv` varchar(250) NOT NULL,
  `company_logo` varchar(255) NOT NULL,
  `status` tinyint(4) NOT NULL DEFAULT '1',
  `crd` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `upd` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `usermeta`
--

INSERT INTO `usermeta` (`metaID`, `user_id`, `jobTitle_id`, `address`, `city`, `state`, `country`, `latitude`, `longitude`, `bio`, `description`, `phone`, `user_resume`, `user_cv`, `company_logo`, `status`, `crd`, `upd`) VALUES
(1, 1, 0, 'Indore, Madhya Pradesh, India', 'Indore', 'Madhya Pradesh', 'India', '22.719569', '75.857726', 'What is Lorem Ipsum?', '', '', '', '', '', 1, '2019-04-09 14:04:03', '2019-04-09 14:04:03'),
(2, 2, 0, 'Indore, Madhya Pradesh, India', 'Indore', 'Madhya Pradesh', 'India', '22.719569', '75.857726', 'What is Lorem Ipsum?', '', '', '', '', '', 1, '2019-04-11 14:13:44', '2019-04-11 14:13:44');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `userId` int(11) NOT NULL,
  `fullName` varchar(250) NOT NULL,
  `businessName` varchar(255) NOT NULL,
  `email` varchar(250) NOT NULL,
  `phone` varchar(100) NOT NULL,
  `password` varchar(250) NOT NULL,
  `profileImage` varchar(250) NOT NULL,
  `userType` varchar(50) NOT NULL,
  `deviceType` tinyint(4) NOT NULL COMMENT '0:Android , 1:Ios',
  `deviceToken` varchar(250) NOT NULL,
  `isProfile` tinyint(4) NOT NULL DEFAULT '0',
  `jobProfileCompleted` tinyint(4) NOT NULL DEFAULT '0' COMMENT '1: Yes, 0: No',
  `isNotify` tinyint(4) NOT NULL DEFAULT '1' COMMENT '0:not send, 1:send',
  `visit_counts` bigint(20) NOT NULL DEFAULT '0',
  `authToken` varchar(255) NOT NULL,
  `status` tinyint(4) NOT NULL DEFAULT '1',
  `isActive` tinyint(4) NOT NULL DEFAULT '1' COMMENT '1 : active, 0: deactive',
  `isVerified` tinyint(4) NOT NULL DEFAULT '0' COMMENT '0: unverified,; verified',
  `verifiedLink` varchar(200) NOT NULL,
  `crd` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `upd` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`userId`, `fullName`, `businessName`, `email`, `phone`, `password`, `profileImage`, `userType`, `deviceType`, `deviceToken`, `isProfile`, `jobProfileCompleted`, `isNotify`, `visit_counts`, `authToken`, `status`, `isActive`, `isVerified`, `verifiedLink`, `crd`, `upd`) VALUES
(1, 'Manish Business', 'Dev Infotech', 'manish.mindiii@gmail.com', '123456123', '$2y$10$VmLhl8Gjadu3Q8vaeAEiaejsU1xNHcdr.3ORQAqb4TTw134i9ACuO', '', 'business', 0, '', 1, 0, 1, 0, '7902d2dedd470f1b054176bc2073e9ed27952b13', 1, 1, 1, '983f23282de89d10bac4622950206a1f', '2019-04-09 13:49:59', '2019-04-09 08:34:03'),
(2, 'Sunil', '', 'sunil@gmail.com', '13251658', '$2y$10$GFAuak6waYyECAolHFHKUOpnX92/3.Ncdksx/DOiGHbu6RRjPnKdq', '', 'individual', 0, '', 1, 0, 1, 0, '5d63c0673746686e0931fd53070a31488e2688f7', 1, 1, 1, 'd6e12ea7cd0a7f040f76bb06175dc106', '2019-04-11 14:09:33', '2019-04-11 08:43:44');

-- --------------------------------------------------------

--
-- Table structure for table `user_job_profile`
--

CREATE TABLE `user_job_profile` (
  `jobProfileId` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `current_job_title` int(11) NOT NULL,
  `current_company` varchar(250) NOT NULL,
  `current_start_date` varchar(250) NOT NULL,
  `current_finish_date` varchar(250) NOT NULL,
  `current_description` text CHARACTER SET utf8mb4 COLLATE utf8mb4_bin NOT NULL,
  `next_availability` varchar(250) NOT NULL,
  `next_speciality` int(250) NOT NULL,
  `next_location` varchar(250) NOT NULL,
  `expectedSalaryFrom` varchar(100) NOT NULL,
  `expectedSalaryTo` varchar(100) NOT NULL,
  `employementType` varchar(100) NOT NULL,
  `totalExperience` double(10,1) NOT NULL,
  `currentExperience` double(10,1) NOT NULL,
  `status` tinyint(4) NOT NULL DEFAULT '1',
  `crd` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `upd` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `user_job_profile`
--

INSERT INTO `user_job_profile` (`jobProfileId`, `user_id`, `current_job_title`, `current_company`, `current_start_date`, `current_finish_date`, `current_description`, `next_availability`, `next_speciality`, `next_location`, `expectedSalaryFrom`, `expectedSalaryTo`, `employementType`, `totalExperience`, `currentExperience`, `status`, `crd`, `upd`) VALUES
(1, 1, 86, 'Midiiii system Pvt Ltd', '', '', 'test', '', 0, '', '0', '20000', '', 0.0, 0.0, 1, '2019-04-09 14:06:40', '2019-04-09 14:06:40');

-- --------------------------------------------------------

--
-- Table structure for table `user_previous_role`
--

CREATE TABLE `user_previous_role` (
  `previousRoleId` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `previous_job_title_id` int(20) NOT NULL,
  `previousCompanyName` varchar(100) NOT NULL,
  `previousDescription` varchar(255) NOT NULL,
  `experience` double(10,1) NOT NULL,
  `crd` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `upd` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00' ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `user_specialization_mapping`
--

CREATE TABLE `user_specialization_mapping` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `specialization_id` int(11) NOT NULL,
  `status` tinyint(4) NOT NULL DEFAULT '1',
  `crd` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `upd` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `user_specialization_mapping`
--

INSERT INTO `user_specialization_mapping` (`id`, `user_id`, `specialization_id`, `status`, `crd`, `upd`) VALUES
(1, 1, 3, 1, '2019-04-09 14:04:03', '2019-04-09 14:04:03'),
(2, 2, 3, 1, '2019-04-11 14:13:44', '2019-04-11 14:13:44');

-- --------------------------------------------------------

--
-- Table structure for table `user_strength_mapping`
--

CREATE TABLE `user_strength_mapping` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `strength_id` int(11) NOT NULL,
  `status` tinyint(4) NOT NULL DEFAULT '1',
  `crd` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `upd` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `user_strength_mapping`
--

INSERT INTO `user_strength_mapping` (`id`, `user_id`, `strength_id`, `status`, `crd`, `upd`) VALUES
(1, 1, 3, 1, '2019-04-09 14:04:03', '2019-04-09 14:04:03'),
(2, 1, 4, 1, '2019-04-09 14:04:03', '2019-04-09 14:04:03'),
(3, 2, 3, 1, '2019-04-11 14:13:44', '2019-04-11 14:13:44'),
(4, 2, 4, 1, '2019-04-11 14:13:44', '2019-04-11 14:13:44');

-- --------------------------------------------------------

--
-- Table structure for table `user_value_mapping`
--

CREATE TABLE `user_value_mapping` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `value_id` int(11) NOT NULL,
  `status` tinyint(4) NOT NULL DEFAULT '1',
  `crd` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `upd` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `user_value_mapping`
--

INSERT INTO `user_value_mapping` (`id`, `user_id`, `value_id`, `status`, `crd`, `upd`) VALUES
(1, 1, 3, 1, '2019-04-09 14:04:03', '2019-04-09 14:04:03'),
(2, 2, 3, 1, '2019-04-11 14:13:44', '2019-04-11 14:13:44');

-- --------------------------------------------------------

--
-- Table structure for table `value`
--

CREATE TABLE `value` (
  `valueId` int(11) NOT NULL,
  `valueName` varchar(200) NOT NULL,
  `status` tinyint(4) NOT NULL DEFAULT '1',
  `crd` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `upd` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `value`
--

INSERT INTO `value` (`valueId`, `valueName`, `status`, `crd`, `upd`) VALUES
(1, 'Honesty', 1, '2018-04-20 12:23:12', '2018-04-26 11:10:59'),
(3, 'Peace', 1, '2018-04-20 12:45:52', '2018-04-26 11:10:50'),
(4, 'Loyalty', 1, '2018-04-20 12:46:02', '2018-04-26 11:10:32'),
(6, 'Caring', 1, '2018-05-13 06:20:05', '2018-05-13 06:20:05'),
(7, 'Work Life Balance', 1, '2018-05-13 06:20:29', '2018-05-13 06:20:29'),
(8, 'Authentic', 1, '2018-05-13 06:20:37', '2018-05-13 06:20:37'),
(10, 'Curious', 1, '2018-05-13 06:21:05', '2018-05-13 06:21:05'),
(11, 'Fairness', 1, '2018-05-13 06:21:17', '2018-05-13 06:21:17'),
(12, 'Happiness', 1, '2018-05-13 06:21:25', '2018-05-13 06:21:25'),
(13, 'Growth', 1, '2018-05-13 06:21:32', '2018-05-13 06:21:32'),
(14, 'Fun', 1, '2018-05-13 06:21:44', '2018-05-13 06:21:44'),
(15, 'Humour', 1, '2018-05-13 06:21:53', '2018-05-13 06:21:53'),
(16, 'Kindness', 1, '2018-05-13 06:22:06', '2018-05-13 06:22:06'),
(17, 'Knowledge', 1, '2018-05-13 06:22:12', '2018-05-13 06:22:12'),
(18, 'Learning', 1, '2018-05-13 06:22:21', '2018-05-13 06:22:21'),
(19, 'Transparency', 1, '2018-05-13 06:22:47', '2018-05-13 06:22:47'),
(20, 'Recognition', 1, '2018-05-13 06:22:57', '2018-05-13 06:22:57'),
(21, 'Diversity', 1, '2018-05-13 06:23:11', '2018-05-13 06:23:11'),
(22, 'Equality', 1, '2018-05-13 06:23:20', '2018-05-13 06:23:20'),
(23, 'Respect', 1, '2018-05-13 06:23:28', '2018-05-13 06:23:28'),
(24, 'Integrity', 1, '2018-05-13 06:24:06', '2018-05-13 06:24:06'),
(25, 'Security', 1, '2018-05-13 06:24:20', '2018-05-13 06:24:20'),
(26, 'Responsibility', 1, '2018-05-13 06:24:30', '2018-05-13 06:24:30'),
(27, 'Opinions', 1, '2018-05-13 06:24:39', '2018-05-13 06:24:39'),
(28, 'Reputation', 1, '2018-05-13 06:24:48', '2018-05-13 06:24:48'),
(29, 'Friendships', 1, '2018-05-13 06:25:38', '2018-05-13 06:25:38'),
(30, 'Contribution', 1, '2018-05-13 06:25:47', '2018-05-13 06:25:47'),
(31, 'Authority', 1, '2018-05-13 06:25:58', '2018-05-13 06:25:58'),
(32, 'Acceptance', 1, '2018-05-13 06:31:44', '2018-05-13 06:31:44'),
(33, 'Openness', 1, '2018-05-13 06:33:18', '2018-05-13 06:33:18'),
(34, 'Ambition', 1, '2018-05-13 06:33:31', '2018-05-13 06:33:31'),
(35, 'Bravery', 1, '2018-05-13 06:33:38', '2018-05-13 06:33:38'),
(36, 'Resilience', 1, '2018-05-13 06:33:53', '2018-05-13 06:33:53'),
(37, 'Charity', 1, '2018-05-13 06:34:10', '2018-05-13 06:34:10'),
(38, 'Compassion', 1, '2018-05-13 06:34:18', '2018-05-13 06:34:18'),
(39, 'Competence', 1, '2018-05-13 06:34:29', '2018-05-13 06:34:29');

-- --------------------------------------------------------

--
-- Table structure for table `view`
--

CREATE TABLE `view` (
  `viewId` int(11) NOT NULL,
  `view_for` int(11) NOT NULL,
  `view_by` int(11) NOT NULL,
  `status` tinyint(4) NOT NULL DEFAULT '1' COMMENT '0:Inactive , 1:Active',
  `crd` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `upd` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `admin`
--
ALTER TABLE `admin`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `email` (`email`);

--
-- Indexes for table `contactUs`
--
ALTER TABLE `contactUs`
  ADD PRIMARY KEY (`contactUsId`);

--
-- Indexes for table `favourites`
--
ALTER TABLE `favourites`
  ADD PRIMARY KEY (`favouriteId`),
  ADD KEY `favourite_by` (`favourite_by`),
  ADD KEY `favourite_for` (`favourite_for`);

--
-- Indexes for table `interviews`
--
ALTER TABLE `interviews`
  ADD PRIMARY KEY (`interviewId`),
  ADD KEY `interview_id` (`request_id`);

--
-- Indexes for table `interview_request`
--
ALTER TABLE `interview_request`
  ADD PRIMARY KEY (`requestId`),
  ADD KEY `interview_id` (`interview_id`);

--
-- Indexes for table `job_titles`
--
ALTER TABLE `job_titles`
  ADD PRIMARY KEY (`jobTitleId`);

--
-- Indexes for table `notifications`
--
ALTER TABLE `notifications`
  ADD PRIMARY KEY (`id`),
  ADD KEY `notification_by` (`notification_by`),
  ADD KEY `notification_for` (`notification_for`);

--
-- Indexes for table `options`
--
ALTER TABLE `options`
  ADD PRIMARY KEY (`optionId`);

--
-- Indexes for table `recommends`
--
ALTER TABLE `recommends`
  ADD PRIMARY KEY (`recommendId`),
  ADD KEY `recommend_by` (`recommend_by`),
  ADD KEY `recommend_for` (`recommend_for`);

--
-- Indexes for table `requests`
--
ALTER TABLE `requests`
  ADD PRIMARY KEY (`requestId`),
  ADD KEY `ineterview_by` (`request_by`),
  ADD KEY `interview_for` (`request_for`);

--
-- Indexes for table `reviews`
--
ALTER TABLE `reviews`
  ADD PRIMARY KEY (`reviewId`),
  ADD KEY `review_for` (`review_for`),
  ADD KEY `review_by` (`review_by`);

--
-- Indexes for table `specializations`
--
ALTER TABLE `specializations`
  ADD PRIMARY KEY (`specializationId`);

--
-- Indexes for table `strengths`
--
ALTER TABLE `strengths`
  ADD PRIMARY KEY (`strengthId`);

--
-- Indexes for table `usermeta`
--
ALTER TABLE `usermeta`
  ADD PRIMARY KEY (`metaID`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`userId`);

--
-- Indexes for table `user_job_profile`
--
ALTER TABLE `user_job_profile`
  ADD PRIMARY KEY (`jobProfileId`),
  ADD KEY `user_id` (`user_id`),
  ADD KEY `next_speciality` (`next_speciality`),
  ADD KEY `current_job_title` (`current_job_title`);

--
-- Indexes for table `user_previous_role`
--
ALTER TABLE `user_previous_role`
  ADD PRIMARY KEY (`previousRoleId`),
  ADD KEY `user_id` (`user_id`),
  ADD KEY `previous_job_title_id` (`previous_job_title_id`);

--
-- Indexes for table `user_specialization_mapping`
--
ALTER TABLE `user_specialization_mapping`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`),
  ADD KEY `specialization_id` (`specialization_id`);

--
-- Indexes for table `user_strength_mapping`
--
ALTER TABLE `user_strength_mapping`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`),
  ADD KEY `strength_id` (`strength_id`);

--
-- Indexes for table `user_value_mapping`
--
ALTER TABLE `user_value_mapping`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`),
  ADD KEY `value_id` (`value_id`);

--
-- Indexes for table `value`
--
ALTER TABLE `value`
  ADD PRIMARY KEY (`valueId`);

--
-- Indexes for table `view`
--
ALTER TABLE `view`
  ADD PRIMARY KEY (`viewId`),
  ADD KEY `view_by` (`view_by`),
  ADD KEY `view_for` (`view_for`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `admin`
--
ALTER TABLE `admin`
  MODIFY `id` int(10) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `contactUs`
--
ALTER TABLE `contactUs`
  MODIFY `contactUsId` bigint(20) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `favourites`
--
ALTER TABLE `favourites`
  MODIFY `favouriteId` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `interviews`
--
ALTER TABLE `interviews`
  MODIFY `interviewId` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `interview_request`
--
ALTER TABLE `interview_request`
  MODIFY `requestId` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `job_titles`
--
ALTER TABLE `job_titles`
  MODIFY `jobTitleId` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=87;

--
-- AUTO_INCREMENT for table `notifications`
--
ALTER TABLE `notifications`
  MODIFY `id` bigint(20) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `options`
--
ALTER TABLE `options`
  MODIFY `optionId` bigint(20) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT for table `recommends`
--
ALTER TABLE `recommends`
  MODIFY `recommendId` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `requests`
--
ALTER TABLE `requests`
  MODIFY `requestId` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `reviews`
--
ALTER TABLE `reviews`
  MODIFY `reviewId` int(20) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `specializations`
--
ALTER TABLE `specializations`
  MODIFY `specializationId` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=57;

--
-- AUTO_INCREMENT for table `strengths`
--
ALTER TABLE `strengths`
  MODIFY `strengthId` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=48;

--
-- AUTO_INCREMENT for table `usermeta`
--
ALTER TABLE `usermeta`
  MODIFY `metaID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `userId` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `user_job_profile`
--
ALTER TABLE `user_job_profile`
  MODIFY `jobProfileId` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `user_previous_role`
--
ALTER TABLE `user_previous_role`
  MODIFY `previousRoleId` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `user_specialization_mapping`
--
ALTER TABLE `user_specialization_mapping`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `user_strength_mapping`
--
ALTER TABLE `user_strength_mapping`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `user_value_mapping`
--
ALTER TABLE `user_value_mapping`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `value`
--
ALTER TABLE `value`
  MODIFY `valueId` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=40;

--
-- AUTO_INCREMENT for table `view`
--
ALTER TABLE `view`
  MODIFY `viewId` int(11) NOT NULL AUTO_INCREMENT;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `favourites`
--
ALTER TABLE `favourites`
  ADD CONSTRAINT `favourites_ibfk_1` FOREIGN KEY (`favourite_for`) REFERENCES `users` (`userId`) ON DELETE CASCADE,
  ADD CONSTRAINT `favourites_ibfk_2` FOREIGN KEY (`favourite_by`) REFERENCES `users` (`userId`) ON DELETE CASCADE;

--
-- Constraints for table `interviews`
--
ALTER TABLE `interviews`
  ADD CONSTRAINT `interviews_ibfk_1` FOREIGN KEY (`request_id`) REFERENCES `requests` (`requestId`) ON DELETE CASCADE;

--
-- Constraints for table `recommends`
--
ALTER TABLE `recommends`
  ADD CONSTRAINT `recommends_ibfk_1` FOREIGN KEY (`recommend_for`) REFERENCES `users` (`userId`) ON DELETE CASCADE,
  ADD CONSTRAINT `recommends_ibfk_2` FOREIGN KEY (`recommend_by`) REFERENCES `users` (`userId`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
