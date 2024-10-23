-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Oct 23, 2024 at 01:19 PM
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
(1, 'lolo', '$2y$10$JD/Dy5EbCiSrH6mTMvMLoejbhiJXuWodsrAHkL84QqN/GbyLdt9YC');

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
  `city_id` int(11) DEFAULT NULL,
  `longitude` decimal(10,8) DEFAULT NULL,
  `latitude` decimal(10,8) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `attractions`
--

INSERT INTO `attractions` (`attr_id`, `attraction_name`, `description`, `image`, `parking`, `entrance_fee`, `dining`, `operating_hours`, `contact_details`, `history`, `operating_hours_from`, `operating_hours_to`, `city_id`, `longitude`, `latitude`) VALUES
(1, 'Seascape purok', 'ASSDDGJGBDEGSDG', '../images/color-burst-wallpaper.jpg', 'Not Available', '145', 'Not Available', NULL, 'none', 'asFDGYN RTYHRTY', '9', '3', 1, NULL, NULL),
(2, 'kuku', 'qwqwewefsdf sdfgdfg', '../images/pxfuel (15).jpg', 'Available', '215', 'Available', NULL, 'fgjhgh', 'fghfgugtfu', '1', '1', 1, NULL, NULL),
(3, 'Church of Sto. Sepulcro (Landayan)', 'Located in the Christian Quarter of the Old City of Jerusalem, the church is home to two of the holiest sites in Christianity – the site where Jesus was crucified, known as Calvary, and the tomb where Jesus was buried and then resurrected. Today, the tomb is enclosed by a shrine called the Aedicula.', '../images/walpy_1684624709670.png', 'Available', '', 'Available', NULL, 'none', 'The discovery of the miraculous image of Jesus in the Holy Sepulchre is contained in a document entitled “The Parish Profile”', '7', '6', 35, 14.35230064, 99.99999999),
(4, 'sesew', 'sesew den', '../images/pxfuel - 2023-08-03T091447.931.jpg', 'Available', '120', 'Available', NULL, 'none', 'manuk', '6', '6', 1, 14.35230064, 99.99999999),
(5, 'San Pedro Circle', 'makalat', '../images/pxfuel - 2023-08-03T091549.793.jpg', 'Not Available', '100', 'Available', NULL, 'olats', 'san pedro, manokan', '1', '12', 35, 14.36870239, 99.99999999),
(6, 'San Pedro Circle', 'makalat', '../images/pxfuel (25).jpg', 'Not Available', '100', 'Available', NULL, 'olats', 'san pedro, manokan', '1', '12', 1, 14.36870239, 99.99999999),
(7, 'jojo', 'jojojojo', '../images/vector-abstract-graphics-colorful-fire-r4-3840x2400.jpg', 'Not Available', '100', 'Unspecified', NULL, 'none', 'fghfghfg', '6', '6', 2, 14.37162844, 99.99999999);

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
  `longitude` decimal(17,14) DEFAULT NULL,
  `latitude` decimal(17,14) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `destinations`
--

INSERT INTO `destinations` (`dest_id`, `city`, `attraction_name`, `image`, `map`, `weather`, `city_id`, `longitude`, `latitude`) VALUES
(1, '', 'Seascape purok', '../images/color-burst-wallpaper.jpg', '', '', 1, NULL, NULL),
(2, '', 'kuku', '../images/pxfuel (15).jpg', '', '', 1, NULL, NULL),
(3, '', 'Church of Sto. Sepulcro (Landayan)', '../images/walpy_1684624709670.png', '', '', 35, 14.35230064000000, 99.99999999000000),
(4, '', 'sesew', '../images/pxfuel - 2023-08-03T091447.931.jpg', '', '', 1, 14.35230063594833, 121.06737256765625),
(5, '', 'San Pedro Circle', '../images/pxfuel - 2023-08-03T091549.793.jpg', '', '', 35, 14.36870238859873, 121.05070826313118),
(6, '', 'San Pedro Circle', '../images/pxfuel (25).jpg', '', '', 1, 14.36870238859873, 121.05070826313118),
(7, '', 'jojo', '../images/vector-abstract-graphics-colorful-fire-r4-3840x2400.jpg', '', '', 2, 14.37162843887990, 121.05537611994018);

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
(1, 'hello@gmail.com', 'cedced', '$2y$10$cKqLDdbIgdplt/WGmod/EOjRgsZLWEUYQ0tnUZ5RBehW02KIVS3Fy'),
(2, 'allenallen@gmail.com', 'ced', '$2y$10$9Ua6y7TbbqxkSNSC/ASVqOtrSgnNJ/jjfTRFrKyORdv0d.e2CneYS'),
(3, 'al@gmail.com', 'al', '$2y$10$QIg7lQ48TUBFOFt2K/1AzechXFA0csiepnMP3/pBkv04LESd1b.Mi'),
(4, 'cedced@gmail.com', 'ceced', '$2y$10$Q8fqavmc8UTd.And5Qi4YuBSIK1C0u6Ma9gcdZkYLC6l.ybgGonNO');

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
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `attractions`
--
ALTER TABLE `attractions`
  MODIFY `attr_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT for table `cities`
--
ALTER TABLE `cities`
  MODIFY `city_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=46;

--
-- AUTO_INCREMENT for table `destinations`
--
ALTER TABLE `destinations`
  MODIFY `dest_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT for table `reviews`
--
ALTER TABLE `reviews`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `uid` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

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
