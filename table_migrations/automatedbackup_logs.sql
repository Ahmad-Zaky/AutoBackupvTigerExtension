-- phpMyAdmin SQL Dump
-- version 4.7.0
-- https://www.phpmyadmin.net/
--
-- Host: localhost
-- Generation Time: May 27, 2021 at 01:37 PM
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
-- Table structure for table `automatedbackup_logs`
--

CREATE TABLE `automatedbackup_logs` (
  `id` int(11) UNSIGNED NOT NULL,
  `createdtime` datetime DEFAULT NULL,
  `filename` varchar(255) CHARACTER SET latin1 NOT NULL,
  `filetype` varchar(255) CHARACTER SET latin1 NOT NULL,
  `filesize` varchar(255) CHARACTER SET latin1 NOT NULL,
  `path` varchar(255) CHARACTER SET latin1 NOT NULL,
  `deleted` int(1) NOT NULL,
  `type` varchar(255) CHARACTER SET latin1 NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `automatedbackup_logs`
--

INSERT INTO `automatedbackup_logs` (`id`, `createdtime`, `filename`, `filetype`, `filesize`, `path`, `deleted`, `type`) VALUES
(1, '2021-05-25 06:03:03', 'vtigercrm7_3_db_2021-05-25_18-03-03.zip', 'Localbackup', '454714', '/opt/lampp/htdocs/vtigercrm7.3/db_backup/auto_backups/', 1, 'Localbackup'),
(2, '2021-05-25 08:21:07', 'vtigercrm7_3_db_2021-05-25_20-21-07.zip', 'Localbackup', '455074', '/opt/lampp/htdocs/vtigercrm7.3/db_backup/auto_backups/', 1, 'Localbackup'),
(3, '2021-05-25 08:53:30', 'vtigercrm7_3_db_2021-05-25_20-53-30.zip', 'Localbackup', '455114', '/opt/lampp/htdocs/vtigercrm7.3/db_backup/auto_backups/', 1, 'Localbackup'),
(4, '2021-05-26 04:00:04', 'vtigercrm7_3_db_2021-05-26_04-00-04.zip', 'Localbackup', '455147', '/opt/lampp/htdocs/vtigercrm7.3/db_backup/auto_backups/', 1, 'Localbackup'),
(5, '2021-05-26 02:32:09', 'vtigercrm7_3_db_2021-05-26_14-32-08.zip', 'Localbackup', '455286', '/opt/lampp/htdocs/vtigercrm7.3/db_backup/auto_backups/', 1, 'Localbackup'),
(6, '2021-05-26 02:33:05', 'vtigercrm7_3_db_2021-05-26_14-33-05.zip', 'Localbackup', '455313', '/opt/lampp/htdocs/vtigercrm7.3/db_backup/auto_backups/', 1, 'Localbackup'),
(7, '2021-05-26 02:34:05', 'vtigercrm7_3_db_2021-05-26_14-34-05.zip', 'Localbackup', '455341', '/opt/lampp/htdocs/vtigercrm7.3/db_backup/auto_backups/', 1, 'Localbackup'),
(8, '2021-05-26 03:04:04', 'vtigercrm7_3_db_2021-05-26_15-04-04.zip', 'Localbackup', '455343', '/opt/lampp/htdocs/vtigercrm7.3/db_backup/backup/', 1, 'Localbackup'),
(9, '2021-05-26 16:25:42', 'vtigercrm7_3_db_2021-05-26_16-25-42.zip', 'Localbackup', '455488', '/opt/lampp/htdocs/vtigercrm7.3/db_backup/backup/', 1, 'Localbackup'),
(10, '2021-05-26 16:34:55', 'vtigercrm7_3_db_2021-05-26_16-34-54.zip', 'Localbackup', '455525', '/opt/lampp/htdocs/vtigercrm7.3/db_backup/backup/', 1, 'Localbackup'),
(11, '2021-05-26 16:36:43', 'vtigercrm7_3_db_2021-05-26_16-36-43.zip', 'Localbackup', '455553', '/opt/lampp/htdocs/vtigercrm7.3/db_backup/backup/', 1, 'Localbackup'),
(12, '2021-05-26 16:37:36', 'vtigercrm7_3_db_2021-05-26_16-37-36.zip', 'Localbackup', '455575', '/opt/lampp/htdocs/vtigercrm7.3/db_backup/backup/', 1, 'Localbackup'),
(13, '2021-05-26 16:41:51', 'vtigercrm7_3_db_2021-05-26_16-41-50.zip', 'Localbackup', '455597', '/opt/lampp/htdocs/vtigercrm7.3/db_backup/backup/', 0, 'Localbackup'),
(14, '2021-05-26 16:42:14', 'vtigercrm7_3_db_2021-05-26_16-42-14.zip', 'Localbackup', '455622', '/opt/lampp/htdocs/vtigercrm7.3/db_backup/backup/', 0, 'Localbackup'),
(15, '2021-05-26 18:38:07', 'vtigercrm7_3_db_2021-05-26_18-38-06.zip', 'Localbackup', '455735', '/opt/lampp/htdocs/vtigercrm7.3/db_backup/backup/', 0, 'Localbackup'),
(16, '2021-05-26 18:48:04', 'vtigercrm7_3_db_2021-05-26_18-48-03.zip', 'Localbackup', '455800', '/opt/lampp/htdocs/vtigercrm7.3/db_backup/backup/', 0, 'Localbackup'),
(17, '2021-05-27 02:03:04', 'vtigercrm7_3_db_2021-05-27_02-03-04.zip', 'Localbackup', '455807', '/opt/lampp/htdocs/vtigercrm7.3/db_backup/backup/', 0, 'Localbackup'),
(18, '2021-05-27 12:42:46', 'vtigercrm7_3_db_2021-05-27_12-42-46.zip', 'Localbackup', '455888', '/opt/lampp/htdocs/vtigercrm7.3/db_backup/backup/', 0, 'Localbackup');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `automatedbackup_logs`
--
ALTER TABLE `automatedbackup_logs`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `automatedbackup_logs`
--
ALTER TABLE `automatedbackup_logs`
  MODIFY `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=19;COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
