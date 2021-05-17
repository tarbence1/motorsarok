-- phpMyAdmin SQL Dump
-- version 5.1.0
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: May 17, 2021 at 03:23 PM
-- Server version: 10.4.18-MariaDB
-- PHP Version: 7.3.27

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `motorsarok`
--

-- --------------------------------------------------------

--
-- Table structure for table `comfort_equipment`
--

CREATE TABLE `comfort_equipment` (
  `id` int(11) NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_bin NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_bin;

--
-- Dumping data for table `comfort_equipment`
--

INSERT INTO `comfort_equipment` (`id`, `name`) VALUES
(1, 'Gyári dobozok'),
(2, 'Hátsó dobozok'),
(3, 'Oldalsó dobozok'),
(4, 'GPS navigáció'),
(5, 'Bőr ülés'),
(6, 'Fűthető ülés'),
(7, 'Középsztender'),
(8, 'Lábtartó'),
(9, 'Markolat fűtés'),
(10, 'Plexi');

-- --------------------------------------------------------

--
-- Table structure for table `counties`
--

CREATE TABLE `counties` (
  `id` int(11) NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_bin NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_bin;

--
-- Dumping data for table `counties`
--

INSERT INTO `counties` (`id`, `name`) VALUES
(1, 'Bács-Kiskun'),
(2, 'Baranya'),
(3, 'Békés'),
(4, 'Borsod-Abaúj-Zemplén'),
(5, 'Csongrád-Csanád'),
(6, 'Fejér'),
(7, 'Győr-Moson-Sopron'),
(8, 'Hajdú-Bihar'),
(9, 'Heves'),
(10, 'Jász-Nagykun-Szolnok'),
(11, 'Komárom-Esztergom'),
(12, 'Nógrád'),
(13, 'Pest'),
(14, 'Somogy'),
(15, 'Szabolcs-Szatmár-Bereg'),
(16, 'Tolna'),
(17, 'Vas'),
(18, 'Veszprém'),
(19, 'Zala'),
(20, 'Budapest');

-- --------------------------------------------------------

--
-- Table structure for table `garage`
--

CREATE TABLE `garage` (
  `userid` int(11) NOT NULL,
  `motorcycleid` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_bin;

-- --------------------------------------------------------

--
-- Table structure for table `images`
--

CREATE TABLE `images` (
  `id` int(11) NOT NULL,
  `productid` int(11) DEFAULT NULL,
  `thumbnailimage` varchar(255) COLLATE utf8mb4_bin NOT NULL,
  `is_mainimage` tinyint(4) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_bin;

-- --------------------------------------------------------

--
-- Table structure for table `manufacturers`
--

CREATE TABLE `manufacturers` (
  `id` int(11) NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_bin NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_bin;

--
-- Dumping data for table `manufacturers`
--

INSERT INTO `manufacturers` (`id`, `name`) VALUES
(2, 'Aermacchi'),
(3, 'Agroquad'),
(4, 'Ajp'),
(5, 'Alfer'),
(6, 'Amazonas'),
(7, 'Aprilia'),
(14, 'BMW'),
(8, 'Babetta'),
(9, 'Bajaj'),
(10, 'Benelli'),
(11, 'Beta'),
(12, 'Bimota'),
(13, 'Blata'),
(15, 'Bombardier'),
(16, 'Borile'),
(17, 'Boss'),
(18, 'Bsa'),
(19, 'Buell'),
(20, 'Bultaco'),
(21, 'Cagiva'),
(22, 'Can-Am'),
(23, 'Ccm'),
(24, 'Chang-Jiang'),
(25, 'Cpi'),
(26, 'Csepel'),
(27, 'Cz'),
(28, 'Daelim'),
(29, 'Danuvia'),
(30, 'Db'),
(31, 'Derbi'),
(32, 'Di'),
(33, 'Diamo'),
(34, 'Dkw'),
(35, 'Dnepr'),
(36, 'Dodge'),
(37, 'Ducati'),
(39, 'ETZ'),
(38, 'Enfield'),
(40, 'Factory'),
(41, 'Fantic'),
(42, 'G'),
(43, 'GasGas'),
(44, 'Ghezzi-Brian'),
(45, 'Gilera'),
(46, 'Harley-Davidson'),
(47, 'Hartford'),
(48, 'Hercules'),
(49, 'Hero'),
(50, 'Highland'),
(51, 'Honda'),
(52, 'Horex'),
(53, 'Husaberg'),
(54, 'Husqvarna'),
(55, 'Hyosung'),
(56, 'Indian'),
(57, 'Italjet'),
(58, 'Izh'),
(59, 'Jawa'),
(60, 'Jincheng'),
(61, 'Jinlun'),
(65, 'KTM'),
(62, 'Kanuni'),
(63, 'Kawasaki'),
(64, 'Keeway'),
(66, 'Kymco'),
(67, 'Laverda'),
(68, 'Lem'),
(69, 'Linhai'),
(85, 'MV'),
(86, 'MZ'),
(70, 'Macbor'),
(71, 'Maico'),
(72, 'Malaguti'),
(73, 'Malanca'),
(74, 'Marine'),
(75, 'Matchless'),
(76, 'Mbk'),
(77, 'Mondial'),
(78, 'Moto'),
(79, 'Moto Guzzi'),
(80, 'Motobi'),
(81, 'Motorhispania'),
(82, 'Motowell'),
(83, 'Munch'),
(84, 'Muz'),
(87, 'Norton'),
(88, 'Pannonia'),
(89, 'Peugeot'),
(90, 'Pgo'),
(91, 'Piaggio'),
(92, 'Polaris'),
(93, 'Polini'),
(94, 'Puch'),
(95, 'Rieju'),
(96, 'Riga'),
(97, 'Romet'),
(98, 'Roxon'),
(99, 'Sachs'),
(100, 'Sanglas'),
(101, 'Sherco'),
(102, 'Siamoto'),
(103, 'Simson'),
(104, 'Starway-chu'),
(105, 'Sukida'),
(106, 'Sundiro'),
(107, 'Suzuki'),
(108, 'Sym'),
(109, 'Titan'),
(110, 'Tm'),
(111, 'Tomos'),
(112, 'Triumph'),
(113, 'Troll'),
(114, 'Ural'),
(115, 'Van'),
(116, 'Veli'),
(117, 'Vertemati'),
(118, 'Vespa'),
(119, 'Victory'),
(120, 'Vor'),
(121, 'Voskhod'),
(122, 'Voxan'),
(123, 'Vyrus'),
(124, 'Wexxtor'),
(125, 'Xingfu'),
(126, 'Yamaha'),
(127, 'Yomoto'),
(128, 'Zundapp'),
(258, 'asd'),
(1, 'blabla');

-- --------------------------------------------------------

--
-- Table structure for table `motorcycles`
--

CREATE TABLE `motorcycles` (
  `id` int(11) NOT NULL,
  `userid` int(11) NOT NULL,
  `created` date NOT NULL DEFAULT current_timestamp(),
  `status` int(2) NOT NULL DEFAULT 1,
  `manufacturer` varchar(255) COLLATE utf8mb4_bin NOT NULL,
  `model` varchar(255) COLLATE utf8mb4_bin NOT NULL,
  `price` int(10) NOT NULL,
  `type` varchar(255) COLLATE utf8mb4_bin NOT NULL,
  `year` int(10) NOT NULL,
  `month` int(10) NOT NULL,
  `documents` varchar(255) COLLATE utf8mb4_bin NOT NULL,
  `documentsvalidity` varchar(255) COLLATE utf8mb4_bin NOT NULL,
  `kilometers` int(255) NOT NULL,
  `operatingtime` int(255) NOT NULL,
  `motyear` int(10) NOT NULL,
  `motmonth` int(10) NOT NULL,
  `capacity` int(255) NOT NULL,
  `performance` int(255) NOT NULL,
  `enginetype` varchar(255) COLLATE utf8mb4_bin NOT NULL,
  `fuel` varchar(255) COLLATE utf8mb4_bin NOT NULL,
  `color` varchar(255) COLLATE utf8mb4_bin NOT NULL,
  `cond` varchar(255) COLLATE utf8mb4_bin NOT NULL,
  `license` varchar(5) COLLATE utf8mb4_bin NOT NULL,
  `description` varchar(255) COLLATE utf8mb4_bin NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_bin NOT NULL,
  `email` varchar(255) COLLATE utf8mb4_bin NOT NULL,
  `phone` varchar(255) COLLATE utf8mb4_bin NOT NULL,
  `phone2` varchar(255) COLLATE utf8mb4_bin NOT NULL,
  `county` varchar(255) COLLATE utf8mb4_bin NOT NULL,
  `settlement` varchar(255) COLLATE utf8mb4_bin NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_bin;

-- --------------------------------------------------------

--
-- Table structure for table `motorcycle_types`
--

CREATE TABLE `motorcycle_types` (
  `id` int(11) NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_bin NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_bin;

--
-- Dumping data for table `motorcycle_types`
--

INSERT INTO `motorcycle_types` (`id`, `name`) VALUES
(1, 'Chopper'),
(2, 'Cruiser'),
(3, 'Custom'),
(4, 'Veterán'),
(5, 'Cross'),
(6, 'Pitbike'),
(7, 'Enduro'),
(8, 'Gyerekmotor'),
(9, 'Sport'),
(10, 'Oldalkocsis'),
(11, 'Quad'),
(12, 'Gyerekquad'),
(13, 'Robogó'),
(14, 'Nagyrobogó'),
(15, 'Túrarobogó'),
(16, 'Segédmotoros kerékpár'),
(17, 'Moped'),
(18, 'Supermoto'),
(19, 'Trial'),
(20, 'Trike'),
(21, 'Túra'),
(22, 'Naked'),
(23, 'Túra-sport'),
(24, 'Túraenduro'),
(25, 'Versenymotor'),
(26, 'Dragbike'),
(27, 'Pályamotor'),
(28, 'Pocket-bike'),
(29, 'Streetfighter'),
(30, 'Egyéb');

-- --------------------------------------------------------

--
-- Table structure for table `mx_comfort_equipment`
--

CREATE TABLE `mx_comfort_equipment` (
  `motorcycle_id` int(11) NOT NULL,
  `comfort_equipment_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_bin;

-- --------------------------------------------------------

--
-- Table structure for table `mx_safety_equipment`
--

CREATE TABLE `mx_safety_equipment` (
  `motorcycle_id` int(11) NOT NULL,
  `safety_equipment_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_bin;

-- --------------------------------------------------------

--
-- Table structure for table `mx_technical_equipment`
--

CREATE TABLE `mx_technical_equipment` (
  `motorcycle_id` int(11) NOT NULL,
  `technical_equipment_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_bin;

-- --------------------------------------------------------

--
-- Table structure for table `parts`
--

CREATE TABLE `parts` (
  `id` int(11) NOT NULL,
  `userid` int(11) NOT NULL,
  `status` int(2) NOT NULL DEFAULT 1,
  `product_name` varchar(255) COLLATE utf8mb4_bin NOT NULL,
  `price` int(11) NOT NULL,
  `motorcycle_type` varchar(255) COLLATE utf8mb4_bin NOT NULL,
  `cond` varchar(255) COLLATE utf8mb4_bin NOT NULL,
  `description` varchar(255) COLLATE utf8mb4_bin NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_bin NOT NULL,
  `email` varchar(255) COLLATE utf8mb4_bin NOT NULL,
  `phone` varchar(255) COLLATE utf8mb4_bin NOT NULL,
  `phone2` varchar(255) COLLATE utf8mb4_bin DEFAULT NULL,
  `county` varchar(255) COLLATE utf8mb4_bin NOT NULL,
  `settlement` varchar(255) COLLATE utf8mb4_bin NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_bin;

-- --------------------------------------------------------

--
-- Table structure for table `parts_garage`
--

CREATE TABLE `parts_garage` (
  `userid` int(11) NOT NULL,
  `partid` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_bin;

-- --------------------------------------------------------

--
-- Table structure for table `parts_images`
--

CREATE TABLE `parts_images` (
  `id` int(11) NOT NULL,
  `productid` int(11) NOT NULL,
  `thumbnailimage` varchar(255) COLLATE utf8mb4_bin NOT NULL,
  `is_mainimage` tinyint(4) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_bin;

-- --------------------------------------------------------

--
-- Table structure for table `safety_equipment`
--

CREATE TABLE `safety_equipment` (
  `id` int(11) NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_bin NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_bin;

--
-- Dumping data for table `safety_equipment`
--

INSERT INTO `safety_equipment` (`id`, `name`) VALUES
(1, 'ABS'),
(2, 'Bukócső/Gomba'),
(3, 'DTC'),
(4, 'Kézvédők'),
(5, 'Ködlámpa'),
(6, 'Légzsák'),
(7, 'Xenon fényszóró');

-- --------------------------------------------------------

--
-- Table structure for table `technical_equipment`
--

CREATE TABLE `technical_equipment` (
  `id` int(11) NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_bin NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_bin;

--
-- Dumping data for table `technical_equipment`
--

INSERT INTO `technical_equipment` (`id`, `name`) VALUES
(1, 'Tárcsafék elöl'),
(2, 'Tárcsafék hátul'),
(3, 'Fedélzeti computer'),
(4, 'Sport légszűrő'),
(5, 'Tempomat'),
(6, 'Fűthető tükör'),
(7, 'Immobiliser'),
(8, 'Önindító'),
(9, 'Riasztó'),
(10, 'Sport kipufogó'),
(11, 'most is jo');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `username` varchar(255) COLLATE utf8mb4_bin NOT NULL,
  `email` varchar(255) COLLATE utf8mb4_bin NOT NULL,
  `password` varchar(255) COLLATE utf8mb4_bin NOT NULL,
  `avatar` varchar(255) COLLATE utf8mb4_bin NOT NULL,
  `created` datetime NOT NULL DEFAULT current_timestamp(),
  `premium` int(10) DEFAULT 0,
  `premiumstart` datetime DEFAULT NULL,
  `premiumexpiration` datetime DEFAULT NULL,
  `is_admin` tinyint(4) DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_bin;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `comfort_equipment`
--
ALTER TABLE `comfort_equipment`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `counties`
--
ALTER TABLE `counties`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `garage`
--
ALTER TABLE `garage`
  ADD PRIMARY KEY (`userid`,`motorcycleid`),
  ADD KEY `userid` (`userid`),
  ADD KEY `motorcycleid` (`motorcycleid`);

--
-- Indexes for table `images`
--
ALTER TABLE `images`
  ADD PRIMARY KEY (`id`),
  ADD KEY `productid` (`productid`);

--
-- Indexes for table `manufacturers`
--
ALTER TABLE `manufacturers`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `name` (`name`);

--
-- Indexes for table `motorcycles`
--
ALTER TABLE `motorcycles`
  ADD PRIMARY KEY (`id`),
  ADD KEY `userid` (`userid`);

--
-- Indexes for table `motorcycle_types`
--
ALTER TABLE `motorcycle_types`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `mx_comfort_equipment`
--
ALTER TABLE `mx_comfort_equipment`
  ADD PRIMARY KEY (`comfort_equipment_id`,`motorcycle_id`),
  ADD KEY `motorcycle_id` (`motorcycle_id`) USING BTREE;

--
-- Indexes for table `mx_safety_equipment`
--
ALTER TABLE `mx_safety_equipment`
  ADD PRIMARY KEY (`safety_equipment_id`,`motorcycle_id`),
  ADD KEY `motorcycle_id` (`motorcycle_id`) USING BTREE;

--
-- Indexes for table `mx_technical_equipment`
--
ALTER TABLE `mx_technical_equipment`
  ADD PRIMARY KEY (`technical_equipment_id`,`motorcycle_id`),
  ADD KEY `motorcycle_id` (`motorcycle_id`) USING BTREE;

--
-- Indexes for table `parts`
--
ALTER TABLE `parts`
  ADD PRIMARY KEY (`id`),
  ADD KEY `userid` (`userid`);

--
-- Indexes for table `parts_garage`
--
ALTER TABLE `parts_garage`
  ADD PRIMARY KEY (`partid`,`userid`) USING BTREE,
  ADD KEY `parts_userid` (`userid`),
  ADD KEY `partid` (`partid`);

--
-- Indexes for table `parts_images`
--
ALTER TABLE `parts_images`
  ADD PRIMARY KEY (`id`),
  ADD KEY `productid` (`productid`);

--
-- Indexes for table `safety_equipment`
--
ALTER TABLE `safety_equipment`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `technical_equipment`
--
ALTER TABLE `technical_equipment`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `username` (`username`),
  ADD UNIQUE KEY `email` (`email`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `comfort_equipment`
--
ALTER TABLE `comfort_equipment`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- AUTO_INCREMENT for table `counties`
--
ALTER TABLE `counties`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=21;

--
-- AUTO_INCREMENT for table `images`
--
ALTER TABLE `images`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=540;

--
-- AUTO_INCREMENT for table `manufacturers`
--
ALTER TABLE `manufacturers`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=266;

--
-- AUTO_INCREMENT for table `motorcycles`
--
ALTER TABLE `motorcycles`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=243;

--
-- AUTO_INCREMENT for table `motorcycle_types`
--
ALTER TABLE `motorcycle_types`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=31;

--
-- AUTO_INCREMENT for table `parts`
--
ALTER TABLE `parts`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT for table `parts_images`
--
ALTER TABLE `parts_images`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=41;

--
-- AUTO_INCREMENT for table `safety_equipment`
--
ALTER TABLE `safety_equipment`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT for table `technical_equipment`
--
ALTER TABLE `technical_equipment`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=23;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `garage`
--
ALTER TABLE `garage`
  ADD CONSTRAINT `garage_motorcycleid` FOREIGN KEY (`motorcycleid`) REFERENCES `motorcycles` (`id`),
  ADD CONSTRAINT `garage_userid` FOREIGN KEY (`userid`) REFERENCES `users` (`id`);

--
-- Constraints for table `images`
--
ALTER TABLE `images`
  ADD CONSTRAINT `images_ibfk_1` FOREIGN KEY (`productid`) REFERENCES `motorcycles` (`id`);

--
-- Constraints for table `motorcycles`
--
ALTER TABLE `motorcycles`
  ADD CONSTRAINT `motorcycles_ibfk_1` FOREIGN KEY (`userid`) REFERENCES `users` (`id`);

--
-- Constraints for table `mx_comfort_equipment`
--
ALTER TABLE `mx_comfort_equipment`
  ADD CONSTRAINT `mx_comfort_equipment_ibfk_1` FOREIGN KEY (`motorcycle_id`) REFERENCES `motorcycles` (`id`),
  ADD CONSTRAINT `mx_comfort_equipment_ibfk_2` FOREIGN KEY (`comfort_equipment_id`) REFERENCES `comfort_equipment` (`id`);

--
-- Constraints for table `mx_safety_equipment`
--
ALTER TABLE `mx_safety_equipment`
  ADD CONSTRAINT `mx_safety_equipment_ibfk_1` FOREIGN KEY (`motorcycle_id`) REFERENCES `motorcycles` (`id`),
  ADD CONSTRAINT `mx_safety_equipment_ibfk_2` FOREIGN KEY (`safety_equipment_id`) REFERENCES `safety_equipment` (`id`);

--
-- Constraints for table `mx_technical_equipment`
--
ALTER TABLE `mx_technical_equipment`
  ADD CONSTRAINT `mx_technical_equipment_ibfk_1` FOREIGN KEY (`motorcycle_id`) REFERENCES `motorcycles` (`id`),
  ADD CONSTRAINT `mx_technical_equipment_ibfk_2` FOREIGN KEY (`technical_equipment_id`) REFERENCES `technical_equipment` (`id`);

--
-- Constraints for table `parts`
--
ALTER TABLE `parts`
  ADD CONSTRAINT `userid_foreignkey` FOREIGN KEY (`userid`) REFERENCES `users` (`id`);

--
-- Constraints for table `parts_garage`
--
ALTER TABLE `parts_garage`
  ADD CONSTRAINT `parts_partid` FOREIGN KEY (`partid`) REFERENCES `parts` (`id`);

--
-- Constraints for table `parts_images`
--
ALTER TABLE `parts_images`
  ADD CONSTRAINT `productid_foreign` FOREIGN KEY (`productid`) REFERENCES `parts` (`id`);

DELIMITER $$
--
-- Events
--
CREATE DEFINER=`root`@`localhost` EVENT `statusChange` ON SCHEDULE EVERY 1 DAY STARTS '2021-01-01 14:35:20' ON COMPLETION NOT PRESERVE ENABLE DO UPDATE motorcycles SET status = 0 WHERE created<=CURRENT_DATE - INTERVAL 30 DAY$$

CREATE DEFINER=`root`@`localhost` EVENT `premiumExp` ON SCHEDULE EVERY 1 DAY STARTS '2021-04-23 13:18:22' ON COMPLETION NOT PRESERVE ENABLE DO UPDATE users SET premium= 0 WHERE premiumexpiration<=CURRENT_DATE$$

DELIMITER ;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
