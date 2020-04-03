-- phpMyAdmin SQL Dump
-- version 4.7.4
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Apr 03, 2020 at 05:34 PM
-- Server version: 10.1.29-MariaDB
-- PHP Version: 7.1.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `spark`
--

-- --------------------------------------------------------

--
-- Table structure for table `addcase`
--

CREATE TABLE `addcase` (
  `id` int(11) NOT NULL,
  `id_law` int(11) NOT NULL,
  `id_title` int(11) NOT NULL,
  `id_section` int(11) NOT NULL,
  `id_nature` int(11) NOT NULL,
  `id_result` int(11) NOT NULL,
  `details` varchar(200) NOT NULL,
  `date_added` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `addcase`
--

INSERT INTO `addcase` (`id`, `id_law`, `id_title`, `id_section`, `id_nature`, `id_result`, `details`, `date_added`) VALUES
(1, 1, 1, 5, 5, 1, 'Case No 1', '2020-04-03 10:16:10'),
(2, 1, 1, 5, 5, 1, 'Case Submit', '2020-04-03 10:41:31');

-- --------------------------------------------------------

--
-- Table structure for table `law`
--

CREATE TABLE `law` (
  `id` int(11) NOT NULL,
  `law` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `law`
--

INSERT INTO `law` (`id`, `law`) VALUES
(1, 'CRPC'),
(2, 'QSO'),
(3, 'abc');

-- --------------------------------------------------------

--
-- Table structure for table `nature`
--

CREATE TABLE `nature` (
  `id` int(11) NOT NULL,
  `id_law` int(11) NOT NULL,
  `nature` varchar(200) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `nature`
--

INSERT INTO `nature` (`id`, `id_law`, `nature`) VALUES
(5, 1, 'nature1'),
(6, 2, 'qsotestnature');

-- --------------------------------------------------------

--
-- Table structure for table `result`
--

CREATE TABLE `result` (
  `id` int(11) NOT NULL,
  `id_law` int(11) NOT NULL,
  `id_nature` int(11) NOT NULL,
  `result` varchar(200) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `result`
--

INSERT INTO `result` (`id`, `id_law`, `id_nature`, `result`) VALUES
(1, 1, 5, 'result1');

-- --------------------------------------------------------

--
-- Table structure for table `section`
--

CREATE TABLE `section` (
  `id` int(11) NOT NULL,
  `id_law` int(11) NOT NULL,
  `section` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `section`
--

INSERT INTO `section` (`id`, `id_law`, `section`) VALUES
(5, 1, 'section1');

-- --------------------------------------------------------

--
-- Table structure for table `title`
--

CREATE TABLE `title` (
  `id` int(11) NOT NULL,
  `id_law` int(11) NOT NULL,
  `title` varchar(200) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `title`
--

INSERT INTO `title` (`id`, `id_law`, `title`) VALUES
(1, 1, 'title1');

-- --------------------------------------------------------

--
-- Table structure for table `user_pro`
--

CREATE TABLE `user_pro` (
  `id` int(100) NOT NULL,
  `acc_typ` tinyint(1) DEFAULT NULL COMMENT '0=ad,1=client,2=broker,3=staff, 4=hotel',
  `first_name` varchar(200) NOT NULL,
  `last_name` varchar(200) NOT NULL,
  `ad_user` varchar(255) NOT NULL,
  `ad_email` varchar(100) DEFAULT NULL,
  `ad_pwd` varchar(100) DEFAULT NULL,
  `profile_picture` varchar(255) DEFAULT 'DSC05241.jpg',
  `status` int(4) DEFAULT '0' COMMENT '0=>"Pending",1=>"Not Verified",2=>"Verified",3=>"Suspended"',
  `address` varchar(300) DEFAULT NULL,
  `phone` varchar(100) NOT NULL,
  `sms_code` int(100) NOT NULL,
  `is_online` tinyint(1) NOT NULL DEFAULT '0',
  `profile_type` varchar(32) NOT NULL,
  `latitude` varchar(200) NOT NULL,
  `longitude` varchar(200) NOT NULL,
  `commission` varchar(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `user_pro`
--

INSERT INTO `user_pro` (`id`, `acc_typ`, `first_name`, `last_name`, `ad_user`, `ad_email`, `ad_pwd`, `profile_picture`, `status`, `address`, `phone`, `sms_code`, `is_online`, `profile_type`, `latitude`, `longitude`, `commission`) VALUES
(1, 0, '', '', 'admin', 'superadmin@gmail.com', 'c93ccd78b2076528346216b3b2f701e6', 'DSC05241.jpg', 2, 'Carnaby Street, London, UK', '1234', 14634, 1, '', '51.5131801', '-0.13886060000004363', '0');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `addcase`
--
ALTER TABLE `addcase`
  ADD PRIMARY KEY (`id`),
  ADD KEY `id_law` (`id_law`),
  ADD KEY `id_section` (`id_section`),
  ADD KEY `id_nature` (`id_nature`),
  ADD KEY `id_result` (`id_result`),
  ADD KEY `id_title` (`id_title`);

--
-- Indexes for table `law`
--
ALTER TABLE `law`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `nature`
--
ALTER TABLE `nature`
  ADD PRIMARY KEY (`id`),
  ADD KEY `id_law` (`id_law`);

--
-- Indexes for table `result`
--
ALTER TABLE `result`
  ADD PRIMARY KEY (`id`),
  ADD KEY `id_law` (`id_law`),
  ADD KEY `id_nature` (`id_nature`);

--
-- Indexes for table `section`
--
ALTER TABLE `section`
  ADD PRIMARY KEY (`id`),
  ADD KEY `id_law` (`id_law`);

--
-- Indexes for table `title`
--
ALTER TABLE `title`
  ADD PRIMARY KEY (`id`),
  ADD KEY `id_law` (`id_law`);

--
-- Indexes for table `user_pro`
--
ALTER TABLE `user_pro`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `addcase`
--
ALTER TABLE `addcase`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `law`
--
ALTER TABLE `law`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `nature`
--
ALTER TABLE `nature`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `result`
--
ALTER TABLE `result`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `section`
--
ALTER TABLE `section`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `title`
--
ALTER TABLE `title`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `user_pro`
--
ALTER TABLE `user_pro`
  MODIFY `id` int(100) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `addcase`
--
ALTER TABLE `addcase`
  ADD CONSTRAINT `adcaseidlaw_fk_law` FOREIGN KEY (`id_law`) REFERENCES `law` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `adcaseidnature_fk_nature` FOREIGN KEY (`id_nature`) REFERENCES `nature` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `adcaseidresult_fk_result` FOREIGN KEY (`id_result`) REFERENCES `result` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `adcaseidsection_fk_section` FOREIGN KEY (`id_section`) REFERENCES `section` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `adcaseidtitle_fk_title` FOREIGN KEY (`id_title`) REFERENCES `title` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `nature`
--
ALTER TABLE `nature`
  ADD CONSTRAINT `nidlaw_fk_law` FOREIGN KEY (`id_law`) REFERENCES `law` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `section`
--
ALTER TABLE `section`
  ADD CONSTRAINT `idlaw_fk_law` FOREIGN KEY (`id_law`) REFERENCES `law` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `title`
--
ALTER TABLE `title`
  ADD CONSTRAINT `titleidlaw_fk_law` FOREIGN KEY (`id_law`) REFERENCES `law` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
