-- phpMyAdmin SQL Dump
-- version 4.7.0
-- https://www.phpmyadmin.net/
--
-- Host: localhost
-- Generation Time: May 27, 2021 at 01:45 PM
-- Server version: 10.1.25-MariaDB
-- PHP Version: 5.6.31

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `vtigercrm7_3_db`
--

-- --------------------------------------------------------

--
-- Table structure for table `automatedbackup_settings`
--

CREATE TABLE `automatedbackup_settings` (
  `id` int(11) NOT NULL,
  `key` varchar(255) CHARACTER SET latin1 NOT NULL,
  `value` text CHARACTER SET utf8 COLLATE utf8_estonian_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `automatedbackup_settings`
--

INSERT INTO `automatedbackup_settings` (`id`, `key`, `value`) VALUES
(1, 'localbackup_status', 'Active'),
(2, 'localbackup_database', 'on'),
(3, 'localbackup_files', ''),
(4, 'localbackup_frequency', '1'),
(5, 'localbackup_number', '6'),
(6, 'localbackup_directory', '/opt/lampp/htdocs/vtigercrm7.3/autoBackup/backup/'),
(7, 'emailreport_status', 'Active'),
(8, 'emailreport_email', 'ahmed.fouad@clavisbs.com'),
(9, 'emailreport_backuptype', 'localbackup|##|ftpbackup'),
(10, 'emailreport_subject', 'Database Backup for CityClub CRM'),
(11, 'emailreport_body', 'Database Backup (%s) has been created successfully - %s                                                                                                                                                                                                '),
(12, 'frequency_unit', 'days'),
(13, 'specific_time', '02:00'),
(14, 'next_triger_time', '2021-05-28 02:00');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `automatedbackup_settings`
--
ALTER TABLE `automatedbackup_settings`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `automatedbackup_settings`
--
ALTER TABLE `automatedbackup_settings`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=15;COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
