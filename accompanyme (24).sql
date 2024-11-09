-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Nov 09, 2024 at 11:30 AM
-- Server version: 10.4.32-MariaDB
-- PHP Version: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `accompanyme`
--

-- --------------------------------------------------------

--
-- Table structure for table `admins`
--

CREATE TABLE `admins` (
  `id` int(11) NOT NULL,
  `username` varchar(50) NOT NULL,
  `password` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `admins`
--

INSERT INTO `admins` (`id`, `username`, `password`) VALUES
(3, 'allen', '$2y$10$YjAz5HAzz.fa6VFsAbgHYu5CnS09sdoT5/jC.rwgafqy9jA9w0CoS');

-- --------------------------------------------------------

--
-- Table structure for table `attractions`
--

CREATE TABLE `attractions` (
  `attr_id` int(11) NOT NULL,
  `attraction_name` varchar(50) NOT NULL,
  `description` varchar(1000) DEFAULT NULL,
  `image` varchar(255) NOT NULL,
  `parking` enum('Available','Not Available','Unspecified') DEFAULT 'Unspecified',
  `entrance_fee` varchar(10) DEFAULT NULL,
  `dining` enum('Available','Not Available','Unspecified') DEFAULT 'Unspecified',
  `operating_hours` time DEFAULT NULL,
  `contact_details` varchar(100) DEFAULT NULL,
  `history` varchar(1000) DEFAULT NULL,
  `operating_hours_from` varchar(50) NOT NULL,
  `operating_hours_to` varchar(50) NOT NULL,
  `longitude` decimal(18,15) NOT NULL,
  `latitude` decimal(17,14) NOT NULL,
  `city_id` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `attractions`
--

INSERT INTO `attractions` (`attr_id`, `attraction_name`, `description`, `image`, `parking`, `entrance_fee`, `dining`, `operating_hours`, `contact_details`, `history`, `operating_hours_from`, `operating_hours_to`, `longitude`, `latitude`, `city_id`) VALUES
(1, 'Historic Alberto Mansion in Biñan', 'The Historic Alberto Mansion is the maternal ancestral house of Rizal and is the only original structure in the Philippines that has a connection with the national hero.\r\n\r\nA 200-year-old Bahay na Bato withstanding ravages brought by time and nature; up until the year 2010 when the house was sold to a real estate developer. The property was illegally dismantled for transfer to a heritage resort in Bagac, Bataan. Left in Biñan are ruins of what used to be one of Laguna’s Grand ancestral houses.', '../images/5120x2880-Orange-Blue-Gradient-Mix-5K-Wallpaper-HD-Abstract-.jpg', 'Available', 'none', 'Not Available', NULL, 'unspecified', 'The Historic Alberto Mansion is the maternal ancestral house of Rizal and is the only original structure in the Philippines that has a connection with the national hero.\r\n\r\nA 200-year-old Bahay na Bato withstanding ravages brought by time and nature; up until the year 2010 when the house was sold to a real estate developer. The property was illegally dismantled for transfer to a heritage resort in Bagac, Bataan. Left in Biñan are ruins of what used to be one of Laguna’s Grand ancestral houses.', '12:00', '11:59', 14.339159286123813, 121.08441283882048, 4);

-- --------------------------------------------------------

--
-- Table structure for table `cities`
--

CREATE TABLE `cities` (
  `city_id` int(11) NOT NULL,
  `city` varchar(30) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `cities`
--

INSERT INTO `cities` (`city_id`, `city`) VALUES
(1, 'Alaminos'),
(2, 'Banilan'),
(3, 'Bay'),
(4, 'Biñan'),
(5, 'Cabuyao'),
(6, 'Calamba'),
(7, 'Calauan'),
(8, 'Canlubang'),
(9, 'Cavinti'),
(10, 'Del Remedio'),
(11, 'Famy'),
(12, 'General Mariano Alvarez'),
(13, 'Kalayaan'),
(14, 'Kay-anlog, Calamba'),
(15, 'Liliw'),
(16, 'Los Baños'),
(17, 'Lucban'),
(18, 'Lumban'),
(19, 'Luisiana'),
(20, 'Mabitac'),
(21, 'Magdalena'),
(22, 'Makiling'),
(23, 'Malitlit'),
(24, 'Mamatid'),
(25, 'Nagcarlan'),
(26, 'Paete'),
(27, 'Pagsajnan'),
(28, 'Pakil'),
(29, 'Parian'),
(30, 'Pila'),
(31, 'Pililla'),
(32, 'Punta'),
(33, 'Rizal'),
(34, 'San Pablo'),
(35, 'San Pedro'),
(36, 'Santa Cruz'),
(37, 'Santa Maria'),
(38, 'Santa Rosa'),
(39, 'Siniloan'),
(40, 'Tanay'),
(41, 'Turbina'),
(42, 'Victoria');

-- --------------------------------------------------------

--
-- Table structure for table `destinations`
--

CREATE TABLE `destinations` (
  `dest_id` int(11) NOT NULL,
  `city` varchar(30) NOT NULL,
  `attraction_name` varchar(50) NOT NULL,
  `image` varchar(500) DEFAULT NULL,
  `map` varchar(255) DEFAULT NULL,
  `weather` varchar(500) NOT NULL,
  `city_id` int(11) NOT NULL,
  `longitude` decimal(18,15) DEFAULT NULL,
  `latitude` decimal(17,14) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `destinations`
--

INSERT INTO `destinations` (`dest_id`, `city`, `attraction_name`, `image`, `map`, `weather`, `city_id`, `longitude`, `latitude`) VALUES
(1, '', 'Historic Alberto Mansion in Biñan', '../images/5120x2880-Orange-Blue-Gradient-Mix-5K-Wallpaper-HD-Abstract-.jpg', '', '', 4, 14.339159286123813, 121.08441283882048);

-- --------------------------------------------------------

--
-- Table structure for table `reviews`
--

CREATE TABLE `reviews` (
  `id` int(11) NOT NULL,
  `uid` int(11) DEFAULT NULL,
  `attr_id` int(11) DEFAULT NULL,
  `rating` int(11) DEFAULT NULL,
  `message` text DEFAULT NULL,
  `images` text DEFAULT NULL,
  `video` varchar(255) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `reviews`
--

INSERT INTO `reviews` (`id`, `uid`, `attr_id`, `rating`, `message`, `images`, `video`, `created_at`) VALUES
(1, 1, 1, 2, 'wala lang,. 2 star lang', '[\"images\\/walpy_1692614375234.png\",\"images\\/Warrior II22587_square_20230702_221154_997_41.jpg\"]', NULL, '2024-11-09 10:23:23'),
(2, 1, 1, 3, '3 star ', '[]', NULL, '2024-11-09 10:24:58'),
(3, 1, 1, 4, '4. basta 4', '[]', NULL, '2024-11-09 10:27:44');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `uid` int(11) NOT NULL,
  `uemail` text NOT NULL,
  `uname` text NOT NULL,
  `upassword` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`uid`, `uemail`, `uname`, `upassword`) VALUES
(1, 'allenallen@gmail.com', 'cedced', '$2y$10$TPnh5Zw9mYufvLEZbkQiVOnwZOCBB2KbvuXWfQwdlvRMU5A4dDxAe');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `admins`
--
ALTER TABLE `admins`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `username` (`username`);

--
-- Indexes for table `attractions`
--
ALTER TABLE `attractions`
  ADD PRIMARY KEY (`attr_id`),
  ADD KEY `fk_city` (`city_id`);

--
-- Indexes for table `cities`
--
ALTER TABLE `cities`
  ADD PRIMARY KEY (`city_id`);

--
-- Indexes for table `destinations`
--
ALTER TABLE `destinations`
  ADD PRIMARY KEY (`dest_id`),
  ADD KEY `city_id` (`city_id`);

--
-- Indexes for table `reviews`
--
ALTER TABLE `reviews`
  ADD PRIMARY KEY (`id`),
  ADD KEY `uid` (`uid`),
  ADD KEY `attr_id` (`attr_id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`uid`),
  ADD UNIQUE KEY `uemail` (`uemail`) USING HASH,
  ADD UNIQUE KEY `uname` (`uname`) USING HASH;

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `admins`
--
ALTER TABLE `admins`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `attractions`
--
ALTER TABLE `attractions`
  MODIFY `attr_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `cities`
--
ALTER TABLE `cities`
  MODIFY `city_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=46;

--
-- AUTO_INCREMENT for table `destinations`
--
ALTER TABLE `destinations`
  MODIFY `dest_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `reviews`
--
ALTER TABLE `reviews`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `uid` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `attractions`
--
ALTER TABLE `attractions`
  ADD CONSTRAINT `fk_city` FOREIGN KEY (`city_id`) REFERENCES `cities` (`city_id`);

--
-- Constraints for table `destinations`
--
ALTER TABLE `destinations`
  ADD CONSTRAINT `destinations_ibfk_1` FOREIGN KEY (`city_id`) REFERENCES `cities` (`city_id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `reviews`
--
ALTER TABLE `reviews`
  ADD CONSTRAINT `reviews_ibfk_1` FOREIGN KEY (`uid`) REFERENCES `users` (`uid`),
  ADD CONSTRAINT `reviews_ibfk_2` FOREIGN KEY (`attr_id`) REFERENCES `attractions` (`attr_id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
