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
-- Table structure for table `automatedbackup_users_logs`
--

CREATE TABLE `automatedbackup_users_logs` (
  `id` int(11) NOT NULL,
  `username` varchar(100) NOT NULL,
  `ip` varchar(100) NOT NULL,
  `signed_in` datetime NOT NULL,
  `signed_out` datetime NOT NULL,
  `status` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `automatedbackup_users_logs`
--

INSERT INTO `automatedbackup_users_logs` (`id`, `username`, `ip`, `signed_in`, `signed_out`, `status`) VALUES
(1, 'mahmoud.nagah', '156.196.137.101', '2021-05-26 16:36:56', '2021-05-26 16:37:00', 'Signed Out'),
(2, 'ahmed.fouad', '41.69.186.116', '2021-05-26 16:37:10', '2021-05-26 16:38:55', 'Signed Out'),
(3, 'ahmed.fouad', '156.196.137.101', '2021-05-26 16:43:27', '2021-05-26 16:48:45', 'Signed Out'),
(4, 'ahmed.fouad', '41.234.24.178', '2021-05-27 09:09:19', '2021-05-27 10:43:10', 'Signed Out'),
(5, 'ahmed.fouad', '41.69.186.116', '2021-05-27 10:43:20', '0000-00-00 00:00:00', 'Signed In');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `automatedbackup_users_logs`
--
ALTER TABLE `automatedbackup_users_logs`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `automatedbackup_users_logs`
--
ALTER TABLE `automatedbackup_users_logs`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
