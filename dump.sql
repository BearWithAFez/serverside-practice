-- phpMyAdmin SQL Dump
-- version 4.2.10
-- http://www.phpmyadmin.net
--
-- Host: localhost:3306
-- Generation Time: Jan 12, 2015 at 10:18 AM
-- Server version: 5.5.38
-- PHP Version: 5.6.2

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";

--
-- Database: `sws_voorbeeld`
--

-- --------------------------------------------------------

--
-- Table structure for table `books`
--

CREATE TABLE `books` (
`id` int(11) NOT NULL,
  `title` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `numpages` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `topic_id` int(11) NOT NULL,
  `cover_extension` enum('gif','png','jpg') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'jpg',
  `added_on` datetime NOT NULL
) ENGINE=InnoDB AUTO_INCREMENT=11 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `books`
--

INSERT INTO `books` (`id`, `title`, `numpages`, `user_id`, `topic_id`, `cover_extension`, `added_on`) VALUES
(1, 'Hands-on Node.js', 142, 1, 3, 'png', '2014-01-17 21:38:23'),
(2, 'Laravel: From Apprentice To Artisan', 67, 1, 1, 'jpg', '2014-01-19 21:38:23'),
(3, 'The Node Beginner Book', 112, 1, 3, 'png', '2014-01-20 21:38:23'),
(4, 'Build APIs You Won''t Hate', 182, 1, 1, 'png', '2014-01-23 21:38:23'),
(5, 'Laravel Testing Decoded', 273, 2, 1, 'jpg', '2014-01-24 21:38:23'),
(6, 'A Year With Symfony', 205, 1, 1, 'jpg', '2014-01-25 21:38:23'),
(7, 'Modernizing Legacy Applications In PHP', 228, 2, 1, 'png', '2014-01-26 21:38:23'),
(8, 'Principles of Object-Oriented Programming in JavaScript', 93, 2, 2, 'png', '2014-01-27 21:38:23'),
(9, 'The Node Craftsman Book', 107, 2, 3, 'png', '2014-01-28 21:38:23'),
(10, 'Ansible for DevOps', 227, 1, 5, 'jpg', '2014-01-28 21:47:17');

-- --------------------------------------------------------

--
-- Table structure for table `topics`
--

CREATE TABLE `topics` (
`id` int(11) NOT NULL,
  `title` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `topics`
--

INSERT INTO `topics` (`id`, `title`) VALUES
(1, 'PHP'),
(2, 'JavaScript'),
(3, 'Node'),
(4, 'Ruby on Rails'),
(5, 'DevOps');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
`id` int(11) NOT NULL,
  `username` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `password` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `username`, `password`) VALUES
(1, 'bramus', '$2y$10$ERrAdBi/yPrdwAzgHOx1ROSZzt1U03wubDGBZu45oWpDRCnO0Frf2'),
(2, 'rogier', '$2y$10$duIZHgGG2xCIrTvxpiIkpOQkG3kXH2pTbfaC7uufHHsY18C5odzVK');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `books`
--
ALTER TABLE `books`
 ADD PRIMARY KEY (`id`);

--
-- Indexes for table `topics`
--
ALTER TABLE `topics`
 ADD PRIMARY KEY (`id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
 ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `books`
--
ALTER TABLE `books`
MODIFY `id` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=11;
--
-- AUTO_INCREMENT for table `topics`
--
ALTER TABLE `topics`
MODIFY `id` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=6;
--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
MODIFY `id` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=3;