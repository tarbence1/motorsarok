-- phpMyAdmin SQL Dump
-- version 5.0.2
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Apr 23, 2021 at 03:58 PM
-- Server version: 10.4.14-MariaDB
-- PHP Version: 7.4.10

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

--
-- Dumping data for table `garage`
--

INSERT INTO `garage` (`userid`, `motorcycleid`) VALUES
(19, 178),
(19, 182),
(19, 240),
(19, 241);

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

--
-- Dumping data for table `images`
--

INSERT INTO `images` (`id`, `productid`, `thumbnailimage`, `is_mainimage`) VALUES
(368, 178, 'uploads/16154822621.jpg', 1),
(420, 178, 'uploads/16154883065 másolata (2).jpg', 0),
(421, 178, 'uploads/1615488306motor másolata másolata.jpg', 0),
(422, 178, 'uploads/1615488306motor.jpg', 0),
(425, 178, 'uploads/16154908981.jpg', 0),
(427, 178, 'uploads/16154909632.jpg', 0),
(428, 178, 'uploads/16154909634.jpg', 0),
(429, 178, 'uploads/16154909635.jpg', 0),
(430, 178, 'uploads/16154909825.jpg', 0),
(431, 178, 'uploads/1615490982motor2.jpg', 0),
(432, 179, 'uploads/16155398581.jpg', 0),
(433, 179, 'uploads/16155398582.jpg', 0),
(434, 179, 'uploads/16155398584.jpg', 0),
(435, 179, 'uploads/16155398585.jpg', 1),
(436, 179, 'uploads/1615539858motor.jpg', 0),
(444, 180, 'uploads/16163994092.jpg', 0),
(445, 180, 'uploads/16163994093.jpg', 0),
(446, 180, 'uploads/16163994094.jpg', 1),
(447, 181, 'uploads/1616918731motor.jpg', 0),
(448, 182, 'uploads/16163997211.jpg', 1),
(449, 183, 'uploads/16163997663.jpg', 0),
(450, 183, 'uploads/16163997665.jpg', 1),
(451, 183, 'uploads/1616399766motor.jpg', 0),
(464, 181, 'uploads/16169187311.jpg', 0),
(465, 181, 'uploads/16169187312.jpg', 0),
(466, 181, 'uploads/16169187313.jpg', 0),
(467, 184, 'uploads/16171127611.jpg', 1),
(468, 184, 'uploads/16171127614.jpg', 0),
(469, 184, 'uploads/1617112761motor.jpg', 0),
(470, 184, 'uploads/1617112761motor2.jpg', 0),
(471, 185, 'uploads/16178692881.jpg', 1),
(472, 185, 'uploads/16178692882.jpg', 0),
(473, 185, 'uploads/16178692883.jpg', 0),
(474, 186, 'uploads/16178696631.jpg', 1),
(475, 186, 'uploads/16178696632.jpg', 0),
(476, 186, 'uploads/16178696633.jpg', 0),
(477, 187, 'uploads/16178706281.jpg', 1),
(478, 187, 'uploads/16178706282.jpg', 0),
(479, 187, 'uploads/16178706283.jpg', 0),
(480, 188, 'uploads/16178706904.jpg', 1),
(481, 188, 'uploads/16178706905.jpg', 0),
(482, 189, 'uploads/16178708441.jpg', 1),
(483, 189, 'uploads/16178708442.jpg', 0),
(484, 189, 'uploads/16178708443.jpg', 0),
(485, 190, 'uploads/16178711341.jpg', 1),
(486, 190, 'uploads/16178711343.jpg', 0),
(487, 191, 'uploads/16178722221.jpg', 1),
(488, 191, 'uploads/16178722222.jpg', 0),
(489, 191, 'uploads/16178722223.jpg', 0),
(490, 192, 'uploads/16178724214.jpg', 1),
(491, 193, 'uploads/16178726414.jpg', 1),
(492, 194, 'uploads/16178729952.jpg', 1),
(493, 195, 'uploads/16178731233.jpg', 1),
(494, 196, 'uploads/16178732814.jpg', 1),
(495, 197, 'uploads/1617873500bill.jpg', 1),
(496, 198, 'uploads/16178737222.jpg', 1),
(497, 199, 'uploads/16178738702.jpg', 1),
(498, 200, 'uploads/16178740314.jpg', 1),
(499, 201, 'uploads/16178742013.jpg', 1),
(500, 202, 'uploads/16178742724.jpg', 1),
(501, 203, 'uploads/16178743331.jpg', 1),
(502, 204, 'uploads/16178766744.jpg', 1),
(503, 205, 'uploads/16178768463.jpg', 1),
(504, 206, 'uploads/16178768954.jpg', 1),
(505, 207, 'uploads/16178771431.jpg', 1),
(506, 208, 'uploads/16178771782.jpg', 1),
(507, 209, 'uploads/16178774371.jpg', 1),
(508, 210, 'uploads/16178779951.jpg', 1),
(509, 211, 'uploads/16178780433.jpg', 1),
(510, 212, 'uploads/16178780885.jpg', 1),
(511, 213, 'uploads/16178782432.jpg', 1),
(512, 214, 'uploads/16178782912.jpg', 1),
(513, 215, 'uploads/16183230714.jpg', 1),
(514, 216, 'uploads/16183242494.jpg', 1),
(515, 217, 'uploads/16183243474.jpg', 1),
(516, 218, 'uploads/16184030694.jpg', 1),
(517, 219, 'uploads/16184031674.jpg', 1),
(518, 220, 'uploads/16184033074.jpg', 1),
(519, 221, 'uploads/16184033784.jpg', 1),
(520, 222, 'uploads/16184040064.jpg', 1),
(521, 223, 'uploads/16184040924.jpg', 1),
(522, 224, 'uploads/16184147404.jpg', 1),
(523, 225, 'uploads/16184156433.jpg', 1),
(524, 226, 'uploads/16184161213.jpg', 1),
(525, 227, 'uploads/16184165034.jpg', 1),
(526, 228, 'uploads/16184165844.jpg', 1),
(527, 229, 'uploads/16184166574.jpg', 1),
(528, 230, 'uploads/16184170284.jpg', 1),
(529, 231, 'uploads/16184172014.jpg', 1),
(530, 232, 'uploads/16184734284.jpg', 1),
(531, 237, 'uploads/1618478538motor.jpg', 1),
(532, 238, 'uploads/1618478591motor.jpg', 1),
(533, 239, 'uploads/16184786771.jpg', 1),
(534, 239, 'uploads/16184786772.jpg', 0),
(535, 239, 'uploads/16184786773.jpg', 0),
(536, 239, 'uploads/16184786774.jpg', 0),
(537, 240, 'uploads/1618480358motor2.jpg', 1),
(538, 241, 'uploads/16191068381.jpg', 1),
(539, 242, 'uploads/16191104073.jpg', 1);

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

--
-- Dumping data for table `motorcycles`
--

INSERT INTO `motorcycles` (`id`, `userid`, `created`, `status`, `manufacturer`, `model`, `price`, `type`, `year`, `month`, `documents`, `documentsvalidity`, `kilometers`, `operatingtime`, `motyear`, `motmonth`, `capacity`, `performance`, `enginetype`, `fuel`, `color`, `cond`, `license`, `description`, `name`, `email`, `phone`, `phone2`, `county`, `settlement`) VALUES
(170, 18, '2021-02-20', 0, 'sffs', 'ssdfd', 233, 'Cruiser', 2003, 2, 'Magyar okmányokkal', 'Lejárt okmányokkal', 123, 0, 0, 0, 123, 123, '', '', '', '', 'A', '', '321', '', '123', '0', 'Baranya', '123'),
(171, 19, '2021-02-22', 0, 'hgffgh', 'ssdfd', 10, 'Trial', 2002, 2, 'Magyar okmányokkal', 'Érvényes okmányokkal', 10, 0, 0, 0, 10, 10, '', '', '', '', 'A1', '', '10', '', '10', '0', 'Vas', '10'),
(172, 18, '2021-02-22', 0, 'asd', '10', 10, 'Trial', 2003, 1, 'Magyar okmányokkal', 'Lejárt okmányokkal', 10, 0, 0, 0, 10, 10, '', '', '', '', 'A1', '', '1', '', '1', '0', 'Vas', '1'),
(173, 19, '2021-02-22', 0, 'balasdl', '1', 1, 'Chopper', 2020, 1, 'Magyar okmányokkal', 'Érvényes okmányokkal', 1, 0, 0, 0, 1, 1, '', '', '', '', 'AM', '', '1', '', '1', '0', 'Zala', '1'),
(174, 19, '2021-02-22', 0, 'gfgh', 'ssdfd', 10, 'Trike', 2003, 1, 'Külföldi okmányokkal', 'Érvényes okmányokkal', 1, 0, 0, 0, 1, 1, '', '', '', '', 'AM', '', '1', '', '1', '0', 'Zala', '1'),
(175, 19, '2021-02-22', 0, 'blablahfgfhg', 'ssdfd', 1, 'Tura', 2002, 1, 'Magyar okmányokkal', 'Forgalomból ideiglenesen kivont', 1, 0, 0, 0, 1, 1, '', '', '', '', 'AM', '', '1', '', '1', '0', 'Vas', '1'),
(176, 19, '2021-02-22', 0, 'bask', 'ssdfd', 1, 'Chopper', 2003, 1, 'Magyar okmányokkal', 'Érvényes okmányokkal', 1, 0, 0, 0, 1, 1, '', '', '', '', 'AM', '', '1', '', '1', '0', 'Zala', '1'),
(178, 19, '2021-03-11', 0, 'nhffg', 'ssdfd', 232, 'Trike', 2003, 11, 'Magyar okmányokkal', 'Lejárt okmányokkal', 23123, 0, 0, 0, 213123, 3, '', '', '', '', 'AM', '', '231', '', '123', '0', 'Veszprém', '2133111111'),
(179, 18, '2021-03-12', 0, 'blabla', '100', 100, 'Trial', 2003, 10, 'Külföldi okmányokkal', 'Érvényes okmányokkal', 1212, 0, 0, 0, 12, 112, '', '', '', '', 'A', '', '1212', '', '1212', '0', 'Zala', '1212'),
(180, 19, '2021-03-22', 0, 'hjhhg', '100', 100, 'Trike', 1967, 3, 'Külföldi okmányokkal', 'Lejárt okmányokkal', 100, 0, 0, 0, 10, 10, '', '', '', '', 'A1', '', '100', '', '100', '0', 'Veszprém', '100'),
(181, 19, '2021-03-22', 0, 'Alfer', 'sdf', 100, 'Trike', 1967, 1, 'Magyar okmányokkal', 'Érvényes okmányokkal', 100, 0, 0, 0, 100, 100, '', '', '', '', 'A', '', '100', '', '06301212121', '0', 'Veszprém', '100'),
(182, 19, '2021-03-22', 0, 'blabla', '100', 100, 'Trike', 1967, 1, 'Magyar okmányokkal', 'Érvényes okmányokkal', 100, 0, 0, 0, 100, 100, '', '', '', '', 'A', '', '100', '', '100', '0', 'Veszprém', '100'),
(183, 19, '2021-03-22', 0, 'sdfhzf', 'ssdfd', 100, 'Trial', 1968, 2, 'Magyar okmányokkal', 'Érvényes okmányokkal', 100, 0, 0, 0, 100, 100, '', '', '', '', 'A', '', '100', '', '100', '0', 'Zala', '100'),
(184, 19, '2021-03-30', 1, 'Bsa', '100', 110, 'Trial', 1967, 2, 'Magyar okmányokkal', 'Érvényes okmányokkal', 100, 0, 0, 0, 100, 100, '', '', '', '', 'A', '', '100', '', '100', '0', 'Veszprém', '100'),
(185, 19, '2021-04-08', 1, 'Agroquad', 'ssdfd', 100, 'Cruiser', 1963, 12, 'Külföldi okmányokkal', 'Érvényes okmányokkal', 10, 0, 0, 0, 10, 10, '', '', '', '', 'A1', '', '10', '', '10', '0', 'Nógrád', '10'),
(186, 19, '2021-04-08', 1, 'Ajp', '100', 100, 'Cruiser', 1951, 2, 'Külföldi okmányokkal', 'Érvényes okmányokkal', 10, 0, 0, 0, 10, 10, '', '', '', '', 'A1', '', '10', '', '10', '0', 'Borsod-Abaúj-Zemplén', '10'),
(187, 19, '2021-04-08', 1, 'Agroquad', '100', 100, 'Nagyrobogó', 1966, 10, 'Külföldi okmányokkal', 'Lejárt okmányokkal', 100, 0, 0, 0, 100, 100, '', '', '', '', 'A', '', '100', '', '100', '0', 'Borsod-Abaúj-Zemplén', '100'),
(188, 19, '2021-04-08', 1, 'Agroquad', '100', 100, 'Cruiser', 1966, 2, 'Magyar okmányokkal', 'Érvényes okmányokkal', 100, 0, 0, 0, 10, 10, '', '', '', '', 'A1', '', '100', '', '100', '0', 'Veszprém', '100'),
(189, 19, '2021-04-08', 1, 'Amazonas', 'sd', 100, 'Custom', 1968, 2, 'Magyar okmányokkal', 'Érvényes okmányokkal', 10, 0, 0, 0, 10, 10, '', '', '', '', 'A1', '', '10', '', '10', '0', 'Tolna', '10'),
(190, 19, '2021-04-08', 1, 'Ajp', '100', 100, 'Chopper', 1967, 2, 'Magyar okmányokkal', 'Érvényes okmányokkal', 10, 0, 0, 0, 10, 10, '', '', '', '', 'A1', '', '10', '', '10', '0', 'Borsod-Abaúj-Zemplén', '10'),
(191, 19, '2021-04-08', 1, 'Agroquad', 'sd', 100, 'Cruiser', 1951, 2, 'Külföldi okmányokkal', 'Érvényes okmányokkal', 100, 0, 0, 0, 100, 100, '', '', '', '', 'A', '', '100', '', '100', '0', 'Borsod-Abaúj-Zemplén', '100'),
(192, 19, '2021-04-08', 1, 'Hero', 'sd', 100, 'Cruiser', 1952, 3, 'Külföldi okmányokkal', 'Érvényes okmányokkal', 100, 0, 0, 0, 100, 100, '', '', '', '', 'A', '', '100', '', '100', '0', 'Nógrád', '100'),
(193, 19, '2021-04-08', 1, 'Ajp', '100', 10, 'Cruiser', 1950, 1, 'Külföldi okmányokkal', 'Érvényes okmányokkal', 10, 0, 0, 0, 10, 10, '', '', '', '', 'A1', '', '10', '', '10', '0', 'Budapest', '10'),
(194, 19, '2021-04-08', 1, 'Agroquad', '100', 10, 'Cruiser', 1951, 2, 'Külföldi okmányokkal', 'Érvényes okmányokkal', 1, 0, 0, 0, 10, 10, '', '', '', '', 'A1', '', '10', '', '10', '0', 'Bács-Kiskun', '10'),
(195, 19, '2021-04-08', 1, 'Ajp', 'sd', 100, 'Custom', 1951, 2, 'Magyar okmányokkal', 'Lejárt okmányokkal', 1, 0, 0, 0, 10, 10, '', '', '', '', 'A1', '', '10', '', '10', '0', 'Budapest', '10'),
(196, 19, '2021-04-08', 1, 'Alfer', 'asd', 10, 'Custom', 1952, 2, 'Magyar okmányokkal', 'Lejárt okmányokkal', 10, 0, 0, 0, 10, 10, '', '', '', '', 'A1', '', '10', '', '10', '0', 'Budapest', '10'),
(197, 19, '2021-04-08', 1, 'Jawa', '100', 10, 'Custom', 1965, 2, 'Magyar okmányokkal', 'Érvényes okmányokkal', 10, 0, 0, 0, 10, 10, '', '', '', '', 'A1', '', '10', '', '10', '0', 'Vas', '10'),
(198, 19, '2021-04-08', 1, 'Jawa', '999', 10, 'Custom', 1965, 2, 'Magyar okmányokkal', 'Érvényes okmányokkal', 10, 0, 0, 0, 10, 10, '', '', '', '', 'A1', '', '10', '', '10', '0', 'Vas', '10'),
(199, 19, '2021-04-08', 1, 'Ajp', 'sd', 10, 'Cruiser', 1950, 10, 'Külföldi okmányokkal', 'Lejárt okmányokkal', 10, 0, 0, 0, 10, 10, '', '', '', '', 'A1', '', '10', '', '10', '0', 'Borsod-Abaúj-Zemplén', '10'),
(200, 19, '2021-04-08', 1, 'KTM', '100', 10, 'Cruiser', 1952, 1, 'Külföldi okmányokkal', 'Lejárt okmányokkal', 10, 0, 0, 0, 10, 10, '', '', '', '', 'A1', '', '10', '', '10', '0', 'Budapest', '10'),
(201, 19, '2021-04-08', 1, 'Kanuni', 'sd', 10, 'Custom', 1950, 4, 'Külföldi okmányokkal', 'Érvényes okmányokkal', 10, 0, 0, 0, 10, 10, '', '', '', '', 'A1', '', '10', '', '10', '0', 'Borsod-Abaúj-Zemplén', '10'),
(202, 19, '2021-04-08', 1, 'Husqvarna', 'sd', 10, 'Cruiser', 1951, 2, 'Külföldi okmányokkal', 'Érvényes okmányokkal', 10, 0, 0, 0, 10, 10, '', '', '', '', 'A1', '', '10', '', '10', '0', 'Borsod-Abaúj-Zemplén', '10'),
(203, 19, '2021-04-08', 1, 'Ducati', 'sd', 10, 'Cruiser', 1952, 2, 'Külföldi okmányokkal', 'Lejárt okmányokkal', 10, 0, 0, 0, 10, 10, '', '', '', '', 'A1', '', '10', '', '10', '0', 'Borsod-Abaúj-Zemplén', '10'),
(204, 19, '2021-04-08', 1, 'Indian', '100', 10, 'Cruiser', 1951, 2, 'Magyar okmányokkal', 'Lejárt okmányokkal', 100, 0, 0, 0, 10, 101, '', '', '', '', 'A', '', '10', '', '10', '0', 'Budapest', '10'),
(205, 19, '2021-04-08', 1, 'Laverda', '100', 10, 'Custom', 1951, 1, 'Külföldi okmányokkal', 'Érvényes okmányokkal', 10, 0, 0, 0, 10, 10, '', '', '', '', 'A1', '', '10', '', '10', '0', 'Budapest', '10'),
(206, 19, '2021-04-08', 1, 'KTM', '100', 10, 'Custom', 1951, 1, 'Külföldi okmányokkal', 'Érvényes okmányokkal', 10, 0, 0, 0, 10, 10, '', '', '', '', 'A1', '', '10', '', '10', '0', 'Budapest', '10'),
(207, 19, '2021-04-08', 1, 'Kanuni', 'sd', 100, 'Cruiser', 1951, 2, 'Külföldi okmányokkal', 'Lejárt okmányokkal', 100, 0, 0, 0, 10, 10, '', '', '', '', 'A1', '', '10', '', '10', '0', 'Borsod-Abaúj-Zemplén', '10'),
(208, 19, '2021-04-08', 1, 'Gilera', 'sd', 100, 'Cruiser', 1951, 2, 'Külföldi okmányokkal', 'Lejárt okmányokkal', 100, 0, 0, 0, 10, 10, '', '', '', '', 'A1', '', '10', '', '10', '0', 'Borsod-Abaúj-Zemplén', '10'),
(209, 19, '2021-04-08', 1, 'Izh', 'sd', 10, 'Custom', 1951, 1, 'Külföldi okmányokkal', 'Lejárt okmányokkal', 100, 0, 0, 0, 10, 10, '', '', '', '', 'A1', '', '10', '', '10', '0', 'Nógrád', '10'),
(210, 19, '2021-04-08', 1, 'Honda', '10', 10, 'Cruiser', 1963, 12, 'Okmányok nélkül', 'Lejárt okmányokkal', 10, 0, 0, 0, 10, 10, '', '', '', '', 'A1', '', '10', '', '10', '0', 'Budapest', '10'),
(211, 19, '2021-04-08', 1, 'Dkw', '10', 10, 'Cruiser', 1963, 12, 'Okmányok nélkül', 'Lejárt okmányokkal', 10, 0, 0, 0, 10, 10, '', '', '', '', 'A1', '', '10', '', '10', '0', 'Budapest', '10'),
(212, 19, '2021-04-08', 1, 'Ccm', '10', 10, 'Cruiser', 1963, 12, 'Okmányok nélkül', 'Lejárt okmányokkal', 10, 0, 0, 0, 10, 10, '', '', '', '', 'A1', '', '10', '', '10', '0', 'Budapest', '10'),
(213, 19, '2021-04-08', 1, 'Bimota', '10', 10, 'Cruiser', 1963, 12, 'Okmányok nélkül', 'Lejárt okmányokkal', 10, 0, 0, 0, 10, 10, '', '', '', '', 'A1', '', '10', '', '10', '0', 'Budapest', '10'),
(214, 19, '2021-04-08', 1, 'Amazonas', 'sd', 10, 'Cruiser', 1952, 2, 'Külföldi okmányokkal', 'Érvényes okmányokkal', 10, 0, 0, 0, 10, 10, '', '', '', '', 'A1', '', '10', '', '10', '0', 'Bács-Kiskun', '10'),
(215, 19, '2021-04-13', 1, 'Ajp', 'sd', 100, 'Chopper', 1950, 1, 'Külföldi okmányokkal', 'Érvényes okmányokkal', 10, 0, 0, 0, 10, 10, '', '', '', '', 'A1', '', '10', '', '2147483647', '0', 'Borsod-Abaúj-Zemplén', '10'),
(216, 19, '2021-04-13', 1, 'Ajp', 'sd', 100, 'Chopper', 1950, 3, 'Külföldi okmányokkal', 'Lejárt okmányokkal', 100, 0, 0, 0, 10, 10, '', '', '', '', 'A1', '', '100', '', '2147483647', '0', 'Borsod-Abaúj-Zemplén', '10'),
(217, 19, '2021-04-13', 1, 'Alfer', '100', 100, 'Cruiser', 1952, 2, 'Okmányok nélkül', 'Lejárt okmányokkal', 100, 0, 0, 0, 10, 10, '', '', '', '', 'A1', '', '100', '', '2147483647', '0', 'Komárom-Esztergom', '100'),
(218, 19, '2021-04-14', 1, 'Agroquad', '100', 100, 'Chopper', 1951, 1, 'Magyar okmányokkal', 'Lejárt okmányokkal', 1010, 0, 0, 0, 10, 10, '', '', '', '', 'A1', '', '10', '', '2147483647', '0', 'Somogy', '10'),
(219, 19, '2021-04-14', 1, 'Agroquad', '100', 100, 'Chopper', 1951, 1, 'Magyar okmányokkal', 'Érvényes okmányokkal', 100, 0, 0, 0, 10, 10, '', '', '', '', 'A1', '', '10', '', '2147483647', '0', 'Szabolcs-Szatmár-Bereg', '100'),
(220, 19, '2021-04-14', 1, 'Agroquad', 'sd', 10, 'Cruiser', 1951, 2, 'Magyar okmányokkal', 'Érvényes okmányokkal', 100, 0, 0, 0, 10, 10, '', '', '', '', 'A1', '', '10', '', '2147483647', '0', 'Pest', '10'),
(221, 19, '2021-04-14', 1, 'Agroquad', '100', 100, 'Cruiser', 1950, 2, 'Magyar okmányokkal', 'Érvényes okmányokkal', 10, 0, 0, 0, 10, 10, '', '', '', '', 'A1', '', '10', '', '2147483647', '0', 'Somogy', '100'),
(222, 19, '2021-04-14', 1, 'Ajp', '100', 100, 'Cruiser', 1951, 1, 'Magyar okmányokkal', 'Érvényes okmányokkal', 10, 0, 0, 0, 10, 10, '', '', '', '', 'A1', '', '10', '', '2147483647', '0', 'Somogy', '100'),
(223, 19, '2021-04-14', 1, 'Agroquad', '100', 100, 'Chopper', 1950, 1, 'Magyar okmányokkal', 'Érvényes okmányokkal', 100, 0, 0, 0, 10, 10, '', '', '', '', 'A1', '', '10', '', '2147483647', '0', 'Baranya', '100'),
(224, 19, '2021-04-14', 1, 'Ajp', '100', 10, 'Custom', 1951, 2, 'Magyar okmányokkal', 'Érvényes okmányokkal', 10, 0, 0, 0, 10, 10, '', '', '', '', 'A1', '', '10', '', '2147483647', '0', 'Borsod-Abaúj-Zemplén', '100'),
(225, 19, '2021-04-14', 1, 'Agroquad', '100', 10, 'Cruiser', 1951, 2, 'Magyar okmányokkal', 'Érvényes okmányokkal', 10, 0, 0, 0, 10, 1, '', '', '', '', 'AM', '', '10', '', '2147483647', '0', 'Baranya', '10'),
(226, 19, '2021-04-14', 1, 'Alfer', 'asd', 10, 'Cruiser', 1950, 1, 'Magyar okmányokkal', 'Lejárt okmányokkal', 100, 0, 0, 0, 10, 10, '', '', '', '', 'A1', '', '10', '', '2147483647', '0', 'Borsod-Abaúj-Zemplén', '100'),
(227, 19, '2021-04-14', 1, 'Ajp', '100', 10, 'Cruiser', 1952, 2, 'Magyar okmányokkal', 'Érvényes okmányokkal', 10, 0, 0, 0, 10, 10, '', '', '', '', 'A1', '', '10', '', '2147483647', '0', 'Borsod-Abaúj-Zemplén', '100'),
(228, 19, '2021-04-14', 1, 'Ajp', 'sd', 10, 'Cruiser', 1950, 1, 'Külföldi okmányokkal', 'Lejárt okmányokkal', 10, 0, 0, 0, 10, 10, '', '', '', '', 'A1', '', '10', '', '2147483647', '0', 'Borsod-Abaúj-Zemplén', '100'),
(229, 19, '2021-04-14', 1, 'Ajp', 'sd', 10, 'Cruiser', 1950, 3, 'Külföldi okmányokkal', 'Érvényes okmányokkal', 100, 0, 0, 0, 100, 10, '', '', '', '', 'A1', '', '100', '', '2147483647', '0', 'Budapest', '100'),
(230, 19, '2021-04-14', 1, 'Agroquad', '100', 100, 'Chopper', 1967, 1, 'Magyar okmányokkal', 'Érvényes okmányokkal', 100, 0, 0, 0, 10, 10, '', '', '', '', 'A1', '', '10', '', '2147483647', '0', 'Vas', '100'),
(231, 19, '2021-04-14', 1, 'Agroquad', '100', 100, 'Chopper', 1966, 1, 'Külföldi okmányokkal', 'Lejárt okmányokkal', 10, 0, 0, 0, 10, 10, '', '', '', '', 'A1', '', '10', '', '2147483647', '0', 'Vas', '100'),
(232, 19, '2021-04-15', 1, 'Agroquad', '100', 100, 'Chopper', 1964, 1, 'Külföldi okmányokkal', 'Lejárt okmányokkal', 100, 0, 0, 0, 10, 10, '', '', '', '', 'A1', '', '10', '', '2147483647', '0', 'Veszprém', '100'),
(233, 19, '2021-04-15', 1, 'Agroquad', '100', 100, 'Chopper', 1968, 2, 'Külföldi okmányokkal', 'Érvényes okmányokkal', 10, 0, 0, 0, 10, 10, '', '', '', '', 'A1', '', '10', '', '2147483647', '0', 'Zala', '100'),
(234, 19, '2021-04-15', 1, 'Agroquad', '100', 100, 'Cruiser', 1968, 2, 'Külföldi okmányokkal', 'Lejárt okmányokkal', 10, 0, 0, 0, 10, 10, '', '', '', '', 'A1', '', '10', '', '2147483647', '0', 'Vas', '100'),
(235, 19, '2021-04-15', 1, 'Agroquad', '100', 100, 'Chopper', 1950, 2, 'Magyar okmányokkal', 'Érvényes okmányokkal', 10, 0, 0, 0, 10, 10, '', '', '', '', 'A1', '', '10', '', '2147483647', '0', 'Veszprém', '100'),
(236, 19, '2021-04-15', 1, 'Ajp', 'sd', 100, 'Cruiser', 1952, 2, 'Külföldi okmányokkal', 'Lejárt okmányokkal', 10, 0, 0, 0, 10, 10, '', '', '', '', 'A1', '', '10', '', '2147483647', '0', 'Borsod-Abaúj-Zemplén', '100'),
(237, 19, '2021-04-15', 1, 'Ajp', '1000', 10, 'Cruiser', 1951, 4, 'Külföldi okmányokkal', 'Érvényes okmányokkal', 100, 0, 0, 0, 10, 10, '', '', '', '', 'A1', '', '10', '', '2147483647', '0', 'Budapest', '100'),
(238, 19, '2021-04-15', 1, 'Ajp', 'sd', 100, 'Cruiser', 1951, 3, 'Külföldi okmányokkal', 'Lejárt okmányokkal', 100, 0, 0, 0, 10, 10, '', '', '', '', 'A1', '', '10', '', '2147483647', '0', 'Borsod-Abaúj-Zemplén', '100'),
(239, 19, '2021-04-15', 1, 'Alfer', 'sd', 100, 'Cruiser', 1952, 3, 'Külföldi okmányokkal', 'Lejárt okmányokkal', 100, 0, 0, 0, 10, 10, '', '', '', '', 'A1', '', '10', '', '2147483647', '0', 'Borsod-Abaúj-Zemplén', '100'),
(240, 19, '2021-04-15', 1, 'Amazonas', '100', 100, 'Cruiser', 1951, 2, 'Külföldi okmányokkal', 'Érvényes okmányokkal', 10, 0, 0, 0, 10, 10, '', '', '', '', 'A1', '', 'asd', '', '6301212121', '0', 'Vas', '100'),
(241, 19, '2021-04-22', 1, 'Ajp', '<script>alert();</script>', 12, 'Chopper', 1966, 1, 'Magyar okmányokkal', 'Lejárt okmányokkal', 100, 0, 0, 0, 10, 10, '', '', '', '', 'A1', '', '10', '', '06301212121', '', 'Zala', '100'),
(242, 19, '2021-04-22', 1, 'Ajp', '<script>alert();</script>', 232, 'Custom', 1968, 1, 'Magyar okmányokkal', 'Érvényes okmányokkal', 23, 34, 0, 0, 23, 23, '', '', '<script>alert();</script>', '', 'A2', '<script>alert();</script>', '<script>alert();</script>', '', '06301212121', '', 'Veszprém', '<script>alert();</script>');

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

--
-- Dumping data for table `mx_comfort_equipment`
--

INSERT INTO `mx_comfort_equipment` (`motorcycle_id`, `comfort_equipment_id`) VALUES
(240, 2);

-- --------------------------------------------------------

--
-- Table structure for table `mx_safety_equipment`
--

CREATE TABLE `mx_safety_equipment` (
  `motorcycle_id` int(11) NOT NULL,
  `safety_equipment_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_bin;

--
-- Dumping data for table `mx_safety_equipment`
--

INSERT INTO `mx_safety_equipment` (`motorcycle_id`, `safety_equipment_id`) VALUES
(240, 1),
(240, 2),
(240, 3),
(240, 4),
(240, 5);

-- --------------------------------------------------------

--
-- Table structure for table `mx_technical_equipment`
--

CREATE TABLE `mx_technical_equipment` (
  `motorcycle_id` int(11) NOT NULL,
  `technical_equipment_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_bin;

--
-- Dumping data for table `mx_technical_equipment`
--

INSERT INTO `mx_technical_equipment` (`motorcycle_id`, `technical_equipment_id`) VALUES
(239, 2),
(240, 2),
(235, 3),
(236, 3),
(239, 3),
(240, 3),
(236, 5),
(239, 5),
(240, 5),
(235, 7),
(236, 7),
(239, 7),
(240, 7),
(239, 8),
(235, 10),
(236, 10),
(239, 10),
(240, 10),
(240, 11);

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

--
-- Dumping data for table `parts`
--

INSERT INTO `parts` (`id`, `userid`, `status`, `product_name`, `price`, `motorcycle_type`, `cond`, `description`, `name`, `email`, `phone`, `phone2`, `county`, `settlement`) VALUES
(6, 19, 1, '12', 12, '', 'Kitünő', '', '12xd', '', '121212', '0', 'Budapest', '12xddx'),
(7, 19, 0, '12uj', 2199, 'asdasduj', 'Hiányos', 'dsaadsassdauj', 'asduj', 'asd@asduj.hu', '2232399', '2399', 'Budapest', 'uj'),
(8, 19, 1, 'Valami', 12, '12', 'Újszerű', '12', '12', 'asd@asd.hu', '36302187791', '', 'Pest', '12asd'),
(9, 19, 1, '<script>alert();</script>', 23, '<script>alert();</script>', 'Kitünő', '<script>alert();</script>', '<script>alert();</script>', '', '06301212121', '', 'Vas', '<script>alert();</script>');

-- --------------------------------------------------------

--
-- Table structure for table `parts_garage`
--

CREATE TABLE `parts_garage` (
  `userid` int(11) NOT NULL,
  `partid` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_bin;

--
-- Dumping data for table `parts_garage`
--

INSERT INTO `parts_garage` (`userid`, `partid`) VALUES
(19, 7);

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

--
-- Dumping data for table `parts_images`
--

INSERT INTO `parts_images` (`id`, `productid`, `thumbnailimage`, `is_mainimage`) VALUES
(9, 6, 'parts-uploads/16156246021.jpg', 1),
(10, 6, 'parts-uploads/16156246024.jpg', 0),
(11, 6, 'parts-uploads/16156246025.jpg', 0),
(12, 7, 'parts-uploads/1615796140lanc_lanckerek.jpg', 1),
(18, 7, 'parts-uploads/1615796606images.jpg', 0),
(19, 7, 'parts-uploads/1615796606szimeringek.jpg', 0),
(20, 7, 'parts-uploads/1615796606Z02.jpg', 0),
(21, 6, 'parts-uploads/1615985292mentálpercek.jpg', 0),
(22, 6, 'parts-uploads/1615985292wallpaperflare.com_wallpaper (1).jpg', 0),
(23, 6, 'parts-uploads/1615985292wallpaperflare.com_wallpaper (2).jpg', 0),
(24, 6, 'parts-uploads/1615985361wallpaperflare.com_wallpaper.jpg', 0),
(25, 6, 'parts-uploads/1615985361No-avatar - User Circle Icon Png _ Full Size PNG Download _ SeekPNG.png', 0),
(26, 8, 'parts-uploads/1616675729images.jpg', 1),
(27, 8, 'parts-uploads/1616675729lanc_lanckerek.jpg', 0),
(28, 8, 'parts-uploads/1616675729szimeringek.jpg', 0),
(35, 8, 'parts-uploads/1617006396szimeringek.jpg', 0),
(36, 8, 'parts-uploads/1617006396Z02.jpg', 0),
(37, 8, 'parts-uploads/16170064613.jpg', 0),
(38, 8, 'parts-uploads/16170064614.jpg', 0),
(39, 8, 'parts-uploads/1617006461motor.jpg', 0),
(40, 9, 'parts-uploads/1619112451lanc_lanckerek.jpg', 1);

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
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `username`, `email`, `password`, `avatar`, `created`, `premium`, `premiumstart`, `premiumexpiration`, `is_admin`) VALUES
(2, 'asd2', 'asd@asd.hu', '$2y$10$aAevoqJh/9gKLYxihZs.pe7NZjvzcAVCI9v8bHKksRw5XIAwT9bGG', '', '2020-12-22 12:23:51', NULL, '0000-00-00 00:00:00', NULL, 0),
(3, 'ékezetűúő', 'asd@asd.hi', '$2y$10$iVOdrM5Lw9nMcaETYyTj9ObqU0RnXtEhRC0XtuvawAjM2YGPPMM8.', '', '2020-12-22 12:25:43', NULL, '0000-00-00 00:00:00', NULL, 0),
(4, 'Béla', 'asd@g.hu', '$2y$10$2eH9PAAVIb5REwa3LiXYpenA2NRmzE4CTB.hUPO8kGThCCuMrZvkK', '', '2020-12-22 12:27:28', NULL, '0000-00-00 00:00:00', NULL, 0),
(5, 'béla', 'asd@gs.hu', '$2y$10$hRVTIfMbV7dmN/Y3TNsgyO4.TMBPGSeh3izIsnGFwi5fydURV008S', '', '2020-12-22 12:28:11', NULL, '0000-00-00 00:00:00', NULL, 0),
(7, 'bbb', 'asd@gmail.com', '$2y$10$URZUJ2OhW89Zm7TgaNcskOJZXYGRhejhHBop3gVLSr/QnFcPplXA6', '', '2020-12-23 10:50:53', NULL, '0000-00-00 00:00:00', NULL, 0),
(8, '1234', 'df@asd.hu', '$2y$10$sz4/p3H9zeTU3tgM0wbJpek4a19KktopW9s3lrg1JsTRp7FkJBFS.', '', '2020-12-23 10:52:50', NULL, '0000-00-00 00:00:00', NULL, 0),
(9, '555', '45@gmail.com', '$2y$10$MxAnsmDReIjF8nhAjI.OqOSnb8HdO5g6lJqWlYpj5zTuYUvO131Ba', '', '2020-12-23 10:58:35', NULL, '0000-00-00 00:00:00', NULL, 0),
(10, '4433434', '34@g.hu', '$2y$10$H2ONg13jLgNq87Vkiep9ye4wlnSyi.dXo9evoxY712m2j8uT3OzKW', '', '2020-12-23 10:59:41', NULL, '0000-00-00 00:00:00', NULL, 0),
(11, '123', 'asd@asd12.hu', '$2y$10$Zv/xAINHhKVnjyEWB09JYuKTkjmSxbFBfdrOVCSOA4C9YG01n41Cy', '', '2020-12-23 11:02:26', NULL, '0000-00-00 00:00:00', NULL, 0),
(12, '4234342', 'da@gmail.com', '$2y$10$eicRz9tz3GWeUiRqiLteNeLvg8tlYu3ITYZgcN25M87sbpgxmwyHW', '', '2020-12-23 11:18:53', NULL, '0000-00-00 00:00:00', NULL, 0),
(13, '43434', 'g@g.hu', '$2y$10$4hy4Pg11oPqKHxy3mHP54OB.cKGTqq0/AeRIgQvYmOKAA8wb9PuRC', '', '2020-12-23 11:31:46', NULL, '0000-00-00 00:00:00', NULL, 0),
(14, '232323', 'fdf@g.hu', '$2y$10$GuLe4u9pKu7x.xYn/rMy4OZIbyWxjrFKUvYRYjEj4MTe09biqCmUe', '', '2020-12-23 11:59:36', NULL, '0000-00-00 00:00:00', NULL, 0),
(15, '233223', 'fd@g.hz', '$2y$10$d6cqaS6cnIOMWZmc.oOeJOSard4pJTekF.C176C1r2o.byN23anRe', '', '2020-12-23 12:03:14', NULL, '0000-00-00 00:00:00', NULL, 0),
(16, '23232323', 'df@h.hu', '$2y$10$q0cfacLkLxR7TXzoyH5VDu69n/5V.Jnxj81/6a7Dz8Mp4UDk7X65W', '', '2020-12-23 12:03:49', NULL, '0000-00-00 00:00:00', NULL, 0),
(17, 'asd', 'j@xn--1ca4cwoked.hu', '$2y$10$Tf5FLoO8Bz1y/Hg8W23OU.vrNQYm6gui9ltdHFwtK/GcMmFFJaVVi', 'avatars/1614541428bill.jpg', '2020-12-26 19:15:54', 0, '2021-03-24 14:29:56', '2021-04-23 14:29:56', 1),
(18, 'űűű', 'asd@xn--5gaaa.hu', '$2y$10$/ol8VtoSh8/k/Dx0tURTBOw5zjc7o5VhRbaDWpxdOb8HsEwhidNxW', '', '2020-12-26 19:16:50', 0, '2021-03-24 12:47:43', '2021-04-23 12:47:43', 0),
(19, 'qwe', 'tarbence1@gmail.com', '$2y$10$kx7QPh.YvqnahRKlJ4dLRO.G.n9XJkqSPJaXQCEcVCoDKHdmPaMl6', 'avatars/1619011806bill.jpg', '2020-12-26 19:36:06', 0, '2021-03-24 11:31:37', '2021-04-23 11:31:37', 1),
(20, 'teszt', 'test@teszt.hu', '$2y$10$3eePdig5TekbPJ5Fw15n2e/iOCzVZgWjR8CzRsgEF5Y0ZnoKGK6xi', '', '2021-03-24 14:24:33', 0, '2021-03-24 14:27:52', '2021-04-23 14:27:52', 1),
(21, 'belaksdksdm', 'gdldm@asdasdknog.hu', '$2y$10$QYfHjQJ3y7eg81TRFY531uxOVJR9qwZA3y6o6KdKKXmwMoTBdpaMC', '', '2021-04-22 21:22:58', 0, NULL, NULL, 0);

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
  ADD PRIMARY KEY (`id`);

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
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=22;

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
