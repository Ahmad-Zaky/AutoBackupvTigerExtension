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
-- Table structure for table `automatedbackup_users`
--

CREATE TABLE `automatedbackup_users` (
  `id` int(11) NOT NULL,
  `username` varchar(100) NOT NULL,
  `password` varchar(255) NOT NULL,
  `is_admin` tinyint(1) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `automatedbackup_users`
--

INSERT INTO `automatedbackup_users` (`id`, `username`, `password`, `is_admin`) VALUES
(1, 'ahmed.fouad', '$2y$10$xm.iDWOtvmqmn.t8UzMSnuZ4R5SYBImo8uHZ7IZBm8TGHyr9Ql3Pq', 1),
(2, 'mahmoud.nagah', '$2y$10$26vsHMhZTg3Dv0btSlk2o.eTSvAaNEdiwXNRp2598OpWzAhMea5Oi', 0),
(3, 'ahmed.nouby', '$2y$10$26vsHMhZTg3Dv0btSlk2o.eTSvAaNEdiwXNRp2598OpWzAhMea5Oi', 1);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `automatedbackup_users`
--
ALTER TABLE `automatedbackup_users`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `automatedbackup_users`
--
ALTER TABLE `automatedbackup_users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
