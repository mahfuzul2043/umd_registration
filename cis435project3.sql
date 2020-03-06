-- phpMyAdmin SQL Dump
-- version 4.9.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Nov 16, 2019 at 07:01 PM
-- Server version: 10.4.8-MariaDB
-- PHP Version: 7.3.11

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `cis435project3`
--

-- --------------------------------------------------------

--
-- Table structure for table `students`
--

CREATE TABLE `students` (
  `umid` char(8) NOT NULL COMMENT 'ID must be 8 digits',
  `first_name` varchar(20) NOT NULL,
  `last_name` varchar(20) NOT NULL,
  `project_title` varchar(50) NOT NULL,
  `phone` char(12) NOT NULL,
  `email` varchar(50) NOT NULL,
  `timeslot_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `students`
--

INSERT INTO `students` (`umid`, `first_name`, `last_name`, `project_title`, `phone`, `email`, `timeslot_id`) VALUES
('09423920', 'Test', 'Test', 'test', '313-555-5555', 'test@test.com', 5),
('10485739', 'Test4', 'Test4', 'Test4', '329-238-2382', 'Test4@gmail.com', 2),
('12345678', 'Test1', 'Test1', 'Test1', '123-456-7890', 'test@test.com', 2),
('23984723', 'Test', 'Test', 'a title', '313-555-5555', 'test@test.com', 5),
('29848374', 'Test', 'Test', '32', '313-555-5555', 'test@test.com', 5),
('38294857', 'Test3', 'Test3', 'Test3', '238-238-2382', 'Test3@gmail.com', 2),
('74337940', 'Test', 'Test', 'f', '313-555-5555', 'test@test.com', 5),
('74337944', 'Test', 'Test', 'Registration Change', '313-555-5555', 'test@test.com', 5),
('74337945', 'Khairul', 'Haque', 'dad', '313-377-5746', 'khairul2108@gmail.com', 2),
('74337955', 'Test', 'Test', 'a test project', '313-555-5555', 'test@test.com', 5),
('87654321', 'Test2', 'Test2', 'Test2', '987-654-3210', 'Test2@gmail.com', 2);

-- --------------------------------------------------------

--
-- Table structure for table `timeslots`
--

CREATE TABLE `timeslots` (
  `id` int(11) NOT NULL,
  `title` varchar(50) NOT NULL,
  `seats_remaining` int(11) NOT NULL DEFAULT 6
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `timeslots`
--

INSERT INTO `timeslots` (`id`, `title`, `seats_remaining`) VALUES
(1, '12/9/19, 6:00 PM – 7:00 PM', 6),
(2, '12/9/19, 7:00 PM – 8:00 PM', 1),
(3, '12/9/19, 8:00 PM – 9:00 PM', 6),
(4, '12/10/19, 6:00 PM – 7:00 PM', 6),
(5, '12/10/19, 7:00 PM – 8:00 PM', 0),
(6, '12/10/19, 8:00 PM – 9:00 PM', 6);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `students`
--
ALTER TABLE `students`
  ADD PRIMARY KEY (`umid`),
  ADD KEY `timeslot_id` (`timeslot_id`);

--
-- Indexes for table `timeslots`
--
ALTER TABLE `timeslots`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `timeslots`
--
ALTER TABLE `timeslots`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `students`
--
ALTER TABLE `students`
  ADD CONSTRAINT `students_ibfk_1` FOREIGN KEY (`timeslot_id`) REFERENCES `timeslots` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
