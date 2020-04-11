-- phpMyAdmin SQL Dump
-- version 4.8.1
-- https://www.phpmyadmin.net/
--
-- Host: localhost
-- Generation Time: Apr 10, 2020 at 02:21 PM
-- Server version: 5.7.24
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
-- Database: `vendor`
--

-- --------------------------------------------------------

--
-- Table structure for table `admin`
--

CREATE TABLE `admin` (
  `admin_id` int(11) NOT NULL,
  `admin_name` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `admin_email` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `admin_auth_key` varchar(32) COLLATE utf8_unicode_ci NOT NULL,
  `admin_password_hash` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `admin_password_reset_token` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `admin_status` smallint(6) NOT NULL DEFAULT '10',
  `admin_created_at` datetime NOT NULL,
  `admin_updated_at` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `admin`
--

INSERT INTO `admin` (`admin_id`, `admin_name`, `admin_email`, `admin_auth_key`, `admin_password_hash`, `admin_password_reset_token`, `admin_status`, `admin_created_at`, `admin_updated_at`) VALUES
(1, 'Saoud AlTurki', 'saoud@bawes.net', 'Lu4vPW4Npfgce6WkXdt9OErpxXdB7GW4', '$2y$13$LdNaUZOdyyL5.TYl/tbfI.i9YVkhxFd/9LTzaaCFgn4lCeTNgL8le', NULL, 10, '2018-08-21 19:20:58', '2018-08-21 19:40:58');

-- --------------------------------------------------------

--
-- Table structure for table `agent`
--

CREATE TABLE `agent` (
  `agent_id` bigint(20) NOT NULL,
  `agent_name` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `agent_email` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `agent_auth_key` varchar(32) COLLATE utf8_unicode_ci NOT NULL,
  `agent_password_hash` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `agent_password_reset_token` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `agent_status` smallint(6) NOT NULL DEFAULT '10',
  `email_notification` smallint(6) DEFAULT '0',
  `agent_created_at` datetime NOT NULL,
  `agent_updated_at` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `agent`
--

INSERT INTO `agent` (`agent_id`, `agent_name`, `agent_email`, `agent_auth_key`, `agent_password_hash`, `agent_password_reset_token`, `agent_status`, `email_notification`, `agent_created_at`, `agent_updated_at`) VALUES
(1, 'Saoud', 'saoud@bawes.net', 'VIuogqkTca1m_JabMhTN6Vk9caSGiCLv', '$2y$13$KHfSkyH71T6u.JK/c/l40O4mA8jP.DATvazY/KvLx9Wwq9fQIv3hy', NULL, 10, 0, '2020-03-21 15:28:38', '2020-03-21 15:52:18');

-- --------------------------------------------------------

--
-- Table structure for table `agent_assignment`
--

CREATE TABLE `agent_assignment` (
  `assignment_id` int(10) UNSIGNED NOT NULL,
  `restaurant_uuid` char(60) COLLATE utf8_unicode_ci NOT NULL,
  `agent_id` bigint(20) DEFAULT NULL,
  `assignment_agent_email` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `assignment_created_at` datetime NOT NULL,
  `assignment_updated_at` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `area`
--

CREATE TABLE `area` (
  `area_id` int(11) NOT NULL,
  `city_id` int(11) NOT NULL,
  `area_name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `area_name_ar` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `latitude` decimal(9,6) DEFAULT NULL,
  `longitude` decimal(9,6) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `area`
--

INSERT INTO `area` (`area_id`, `city_id`, `area_name`, `area_name_ar`, `latitude`, `longitude`) VALUES
(1, 1, 'Abdullah al-Salem', 'ضاحية عبدالله السالم', '29.351859', '47.983692'),
(2, 1, 'Adailiya', 'العديلية', '29.328058', '47.983692'),
(3, 1, 'Bneid Al Qar', 'بنيد القار', '29.373051', '48.004744'),
(4, 1, 'Daiya', 'الدعية', '29.360440', '48.018371'),
(5, 1, 'Dasma', 'الدسمة', '29.366434', '48.000698'),
(6, 1, 'Dasman', 'دسمان', '29.387804', '47.999790'),
(7, 4, 'Doha', 'الدوحة', '29.315517', '47.815570'),
(8, 1, 'Faiha', 'الفيحاء', '29.340433', '47.978739'),
(9, 1, 'Granada', 'غرناطة', '29.312521', '47.878553'),
(10, 1, 'Jaber Al Ahmad', 'جابر الاحمد', '29.348166', '47.758825'),
(11, 1, 'Kaifan', 'كيفان', '29.337567', '47.958935'),
(12, 1, 'Khaldiya', 'الخالدية', '29.325196', '47.963885'),
(14, 1, 'Mansouriya', 'المنصورية', '29.357338', '47.994836'),
(15, 1, 'Mirqab', 'المرقاب', '29.366138', '47.983692'),
(16, 1, 'Mubarekiya Camps and Collages', 'معسكرات المباركية', '29.313741', '47.909455'),
(17, 1, 'Nuzha', 'النزهة', '29.341390', '47.993598'),
(18, 1, 'Qadsiya', 'القادسية', '29.349962', '48.003505'),
(19, 1, 'Jibla', 'قبلة', '29.369934', '47.968836'),
(20, 1, 'Qortuba', 'قرطبة', '29.312348', '47.986168'),
(21, 1, 'Rawda', 'الروضة', '29.329013', '47.998551'),
(22, 1, 'Salhiya', 'صالحية', '29.363824', '47.966386'),
(23, 1, 'Shamiya', 'الشامية', '29.350899', '47.968836'),
(24, 1, 'Sharq', 'شرق', '29.382323', '47.988644'),
(25, 1, 'Shuwaikh Residential', 'شويخ السكنية', '29.354695', '47.953984'),
(26, 1, 'Sulaibikhat', 'الصليبيخات', '29.315559', '47.840260'),
(27, 1, 'Surra', 'السرة', '29.313966', '48.008443'),
(28, 1, 'Yarmouk', 'اليرموك', '29.312822', '47.968836'),
(30, 2, 'Bayan', 'بيان', '29.297990', '48.051087'),
(31, 2, 'Hawally', 'حولي', '29.333298', '48.015893'),
(33, 2, 'Jabriya', 'الجابرية', '29.321911', '48.031675'),
(34, 2, 'Maidan Hawally', 'ميدان حولي', '29.337376', '48.041139'),
(35, 2, 'Mishref', 'مشرف', '29.276103', '48.065471'),
(37, 2, 'Rumaithiya', 'الرميثية', '29.318030', '48.075392'),
(38, 2, 'Salam', 'السلام', '29.296629', '48.013415'),
(39, 2, 'Salmiya', 'السالمية', '29.335294', '48.071561'),
(40, 2, 'Salwa', 'سلوى', '29.296487', '48.079379'),
(41, 2, 'Shaab', 'الشعب', '29.349965', '48.028283'),
(42, 2, 'Shuhada', 'الشهداء', '29.270894', '48.033240'),
(43, 2, 'Al-Siddeeq', 'الصديق', '29.293778', '47.993598'),
(44, 2, 'Zahra', 'الزهراء', '29.278063', '47.996074'),
(45, 3, 'Abu Fatira', 'أبو فطيرة', '29.197372', '48.102684'),
(46, 3, 'Abu Al Hasaniya', 'أبو الحصانية', '29.190906', '48.113853'),
(47, 3, 'Adan', 'العدان', '29.228440', '48.065471'),
(48, 3, 'Al-Masayel', 'المسايل', '29.239367', '48.087796'),
(49, 3, 'Qurain', 'القرين', '29.202195', '48.077872'),
(50, 3, 'Qusor', 'القصور', '29.214601', '48.072911'),
(51, 3, 'Coast Strip B', 'الشريط الساحلى', NULL, NULL),
(52, 3, 'Fnaitees', 'فنيطيس', '29.220757', '48.095239'),
(53, 3, 'Messila', 'المسيله', '29.250674', '48.094248'),
(54, 3, 'Mubarak Al Kabeer', 'مبارك الكبير', '29.187155', '48.084724'),
(56, 3, 'Sabhan Industrial Area', 'صبحان الصناعية', '29.230903', '48.003505'),
(57, 3, 'South Wista', 'جنوب وسطى', '29.231709', '48.040437'),
(58, 3, 'West Abu Fatira Small Industrial', 'غرب أبو فطيرة الصناعية', '29.187957', '48.045186'),
(59, 3, 'Wista', 'وسطي', '29.230698', '48.037519'),
(60, 4, 'Abbasiya', 'عباسيه', '29.261273', '47.933046'),
(61, 4, 'Abdullah Al Mubarak Al Sabah', 'عبدالله المبارك الصباح', '29.243378', '47.900341'),
(62, 4, 'Abraq Khaitan', 'أبرق خيطان', '29.295332', '47.972326'),
(64, 4, 'Andalous', 'الأندلس', '29.304205', '47.884732'),
(65, 4, 'Ardiya', 'العارضية', '29.287567', '47.897092'),
(66, 4, 'Ardiya Small Industrial', 'العارضية الصناعية', '29.294712', '47.909455'),
(67, 4, 'Ardiya Storage Zone', 'منطقة تخزين العارضية', '29.295672', '47.924294'),
(69, 4, 'Al-Dajeej', 'الضجيج', '29.261645', '47.962647'),
(70, 4, 'South Khaitan - Exhibits', 'منطقة المعارض جنوب خيطان', '29.276617', '47.977733'),
(71, 4, 'Farwaniya', 'الفروانية', '29.281596', '47.960252'),
(72, 4, 'Firdous', 'الفردوس', '29.286122', '47.874846'),
(73, 4, 'Jeleeb Al-Shuyoukh', 'جليب الشيوخ', '29.255222', '47.936663'),
(74, 4, 'Khaitan', 'خيطان', '29.279973', '47.976263'),
(75, 4, 'Omariya', 'العمرية', '29.297211', '47.955858'),
(76, 4, 'Rabia', 'الرابية', '29.296630', '47.939137'),
(77, 4, 'Rai', 'الري', '29.308054', '47.944086'),
(78, 2, 'Hateen', 'حطين', '29.284243', '48.018371'),
(79, 4, 'Ishbiliya', 'إشبيلية', '29.272833', '47.939137'),
(80, 2, 'Mubarak Al-Abdullah', 'مبارك العبدالله - غرب مشرف', '29.277204', '48.045251'),
(81, 3, 'Sabah Al Salem', 'صباح السالم', '29.256564', '48.062605'),
(82, 1, 'Kuwait City', 'مدينة الكويت', '29.375859', '47.977405'),
(83, 4, 'Rehab', 'الرحاب', '29.285207', '47.934189'),
(84, 1, 'Shuwaikh Industrial', 'الشويخ الصناعية', '29.331366', '47.936663'),
(85, 2, 'Al-Bidea', 'البدع', '29.315423', '48.089166'),
(86, 6, 'Abu Halifa', 'أبو حليفة', '29.128633', '48.117405'),
(87, 6, 'Ahmadi', 'الأحمدي', '29.085504', '48.054379'),
(88, 6, 'Al-Riqqa', 'الرقة', '29.150787', '48.105140'),
(89, 6, 'Assabahiyah', 'الصباحية', '29.107026', '48.096893'),
(90, 6, 'Dahar', 'الظهر', '29.163880', '48.057818'),
(91, 6, 'Eqaila', 'العقيلة', '29.170833', '48.093632'),
(92, 6, 'Fahad Al Ahmad', 'فهد الأحمد', '29.128054', '48.099605'),
(93, 6, 'Fahaheel', 'الفحيحيل', '29.081097', '48.121995'),
(94, 6, 'Fintas', 'الفنطاس', '29.173157', '48.117679'),
(96, 6, 'Hadiya', 'هدية', '29.144453', '48.082212'),
(97, 6, 'Jaber Al Ali', 'جابر العلي', '29.170083', '48.085000'),
(98, 6, 'Mahboula', 'المهبولة', '29.148880', '48.113387'),
(99, 6, 'Mangaf', 'المنقف', '29.105927', '48.119454'),
(100, 1, 'Shuwaikh Administrative', 'شويخ الإدارية', '29.345207', '47.944652'),
(101, 1, 'Shuwaikh Industrial 1', 'شويخ الصناعية 1', '29.336119', '47.930028'),
(102, 4, 'Rigai', 'الرقعي', '29.306690', '47.925696'),
(103, 4, 'Sabah Al Nasser', 'صباح الناصر', '29.273602', '47.867530'),
(104, 1, 'Kuwait Free Trade Zone', 'المنطقة الحرة', '29.352145', '47.901923'),
(105, 7, 'Naeem', 'النعيم', '29.333393', '47.693838'),
(106, 7, 'Amgarah Industrial Area', 'منطقة أمغرة الصناعية', '29.301535', '47.758416'),
(107, 7, 'Jahra', 'الجهراء', '29.327986', '47.640767'),
(108, 7, 'Naseem', 'النسيم', '29.319314', '47.660260'),
(109, 7, 'Oyoun', 'العيون', '29.332673', '47.654092'),
(110, 1, 'Qairawan', 'القيروان', '29.303985', '47.795923'),
(111, 7, 'Qasr', 'القصر', '29.340549', '47.672486'),
(112, 7, 'Saad Al Abdullah', 'سعد العبدالله', '29.313301', '47.693447'),
(113, 7, 'Sulaibiya', 'الصليبية', '29.282850', '47.793486'),
(114, 7, 'Al Sulaibiya Industrial 1', 'الصليبية الصناعية 1', '29.286854', '47.833275'),
(115, 7, 'Al Sulaibiya Industrial 2', 'الصليبية الصناعية 2', '29.283086', '47.857108'),
(116, 7, 'Taima\'', 'تيماء', '29.329026', '47.670636'),
(117, 7, 'Waha', 'الواحة', '29.344576', '47.658130'),
(118, 2, 'Anjafa', 'أنجفة', '29.297939', '48.087227'),
(119, 4, 'Al-Shadadiya', 'الشدادية', '29.259276', '47.896957'),
(120, 1, 'Shuwaikh Industrial 2', 'شويخ الصناعية 2', '29.324354', '47.939661'),
(121, 1, 'Shuwaikh Industrial 3', 'شويخ الصناعية 3', '29.321903', '47.928452'),
(122, 1, 'Nahdha', 'النهضة', '29.304531', '47.859881'),
(123, 1, 'North West Al-Sulaibikhat', 'شمال غرب الصليبيخات', '29.327324', '47.807614'),
(124, 6, 'Ali Sabah Al Salem', 'علي صباح السالم', '28.957320', '48.145604'),
(125, 6, 'Shuaiba Block 1', 'الشعيبة قطعه 1', '29.048984', '48.133103'),
(126, 6, 'West Industrial Shuaiba', 'الشعيبة الصناعية الغربية', '29.012088', '48.110331'),
(127, 6, 'Shalayhat Mina Abdullah', 'شاليهات ميناء عبدالله', '28.970485', '48.172955'),
(128, 6, 'Shalayhat Al Dubaiya', 'الضباعية', '28.918279', '48.192992'),
(129, 6, 'Al-Julaia\'a', 'الجليعة', '28.883725', '48.252833'),
(130, 6, 'Bnaider', 'بنيدر', '28.800663', '48.258669'),
(131, 6, 'Zour', 'الزور', '28.712485', '48.305142'),
(132, 6, 'Al Khiran', 'الخيران', '28.656633', '48.295335'),
(133, 6, 'Al-Nuwaiseeb', 'النويصيب', '28.585259', '48.346367'),
(134, 6, 'Al Wafrah', 'الوفرة', '28.605514', '48.035926'),
(135, 6, 'Sabah Al Ahmad', 'صباح الأحمد', '28.777166', '48.029964'),
(136, 4, 'Kuwait International Airport', 'مطار الكويت الدولي', '29.239757', '47.971149'),
(137, 4, 'Sheikh Saad Aviation Terminal', 'مبنى الشيخ سعد للطيران العام', '29.224968', '47.992477');

-- --------------------------------------------------------

--
-- Table structure for table `category`
--

CREATE TABLE `category` (
  `category_id` int(11) NOT NULL,
  `restaurant_uuid` char(60) COLLATE utf8_unicode_ci DEFAULT NULL,
  `category_name` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `category_name_ar` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `sort_number` int(11) UNSIGNED DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `category`
--

INSERT INTO `category` (`category_id`, `restaurant_uuid`, `category_name`, `category_name_ar`, `sort_number`) VALUES
(447, 'rest_c2af4218-6b72-11ea-bfa2-270399f50534', 'Flatbreads', 'فطائر مسطحة', 2),
(448, 'rest_c2af4218-6b72-11ea-bfa2-270399f50534', 'Most selling', ' الأكثر مبيعا', 1),
(449, 'rest_c2af4218-6b72-11ea-bfa2-270399f50534', 'Think Pink', 'فكر بالوردي', 3);

-- --------------------------------------------------------

--
-- Table structure for table `category_item`
--

CREATE TABLE `category_item` (
  `category_item_id` int(11) NOT NULL,
  `category_id` int(11) NOT NULL,
  `item_uuid` varchar(300) COLLATE utf8_unicode_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `category_item`
--

INSERT INTO `category_item` (`category_item_id`, `category_id`, `item_uuid`) VALUES
(66, 448, 'item_c6eaf116-6b76-11ea-bfa2-270399f50534'),
(67, 449, 'item_c6eaf116-6b76-11ea-bfa2-270399f50534'),
(68, 447, 'item_d0dec88c-6d06-11ea-bfa2-270399f50534'),
(69, 448, 'item_d0dec88c-6d06-11ea-bfa2-270399f50534'),
(70, 447, 'item_4c29b73a-6b73-11ea-bfa2-270399f50534'),
(71, 448, 'item_4c29b73a-6b73-11ea-bfa2-270399f50534');

-- --------------------------------------------------------

--
-- Table structure for table `city`
--

CREATE TABLE `city` (
  `city_id` int(11) NOT NULL,
  `city_name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `city_name_ar` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `city`
--

INSERT INTO `city` (`city_id`, `city_name`, `city_name_ar`) VALUES
(1, 'Kuwait City', 'مدينة الكويت'),
(2, 'Hawally', 'حولي'),
(3, 'Mubarak Al Kabir', 'مبارك الكبير'),
(4, 'Farwaniya', 'الفروانية'),
(6, 'Ahmadi', 'الأحمدي'),
(7, 'Jahra', 'الجهراء');

-- --------------------------------------------------------

--
-- Table structure for table `customer`
--

CREATE TABLE `customer` (
  `customer_id` bigint(20) NOT NULL,
  `restaurant_uuid` char(60) COLLATE utf8_unicode_ci DEFAULT NULL,
  `customer_name` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `customer_phone_number` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `customer_email` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `customer_created_at` datetime NOT NULL,
  `customer_updated_at` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `extra_option`
--

CREATE TABLE `extra_option` (
  `extra_option_id` int(11) NOT NULL,
  `option_id` int(11) DEFAULT NULL,
  `extra_option_name` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `extra_option_name_ar` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `extra_option_price` float UNSIGNED DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `extra_option`
--

INSERT INTO `extra_option` (`extra_option_id`, `option_id`, `extra_option_name`, `extra_option_name_ar`, `extra_option_price`) VALUES
(96503, 99613, 'Pearl (Akawi Cheese)', 'بيرل ( جبن عكاوي )  ', 0),
(96504, 99613, 'Zaatar  ', 'زعتر  ', 0),
(96505, 99613, 'Levant (Zaatar with Akkawi)  ', 'ليفانت زعتر و عكاوي  ', 0),
(96506, 99614, 'White dough  ', 'لعجين الابيض  ', 0),
(96507, 99614, 'Brown dough (Sugar free) ', 'العجين الاسمر (خالية من السكر) ', 0.2),
(96508, 99618, 'Extra Chicken ', 'دجاج إكسترا', 0.85),
(96509, 99618, 'Extra Mushroom ', 'مشروم إكسترا  ', 0.75),
(96510, 99619, 'Extra Zaatar ', 'زعتر إضافي ', 0.35),
(96511, 99619, 'Pomegranate Dip ', 'غطسة رمان', 0.2),
(96512, 99620, 'White dough ', 'العجين الابيض  ', 0),
(96513, 99620, 'Brown dough (Sugar free) ', 'العجين الاسمر (خالية من السكر) ', 0.2);

-- --------------------------------------------------------

--
-- Table structure for table `item`
--

CREATE TABLE `item` (
  `item_uuid` varchar(300) COLLATE utf8_unicode_ci NOT NULL,
  `restaurant_uuid` char(60) COLLATE utf8_unicode_ci NOT NULL,
  `item_name` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `item_name_ar` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `item_description` varchar(1000) COLLATE utf8_unicode_ci DEFAULT NULL,
  `item_description_ar` varchar(1000) COLLATE utf8_unicode_ci DEFAULT NULL,
  `sort_number` int(11) UNSIGNED DEFAULT NULL,
  `stock_qty` int(11) UNSIGNED DEFAULT NULL,
  `item_image` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `item_price` float UNSIGNED DEFAULT NULL,
  `item_created_at` datetime DEFAULT NULL,
  `item_updated_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `item`
--

INSERT INTO `item` (`item_uuid`, `restaurant_uuid`, `item_name`, `item_name_ar`, `item_description`, `item_description_ar`, `sort_number`, `stock_qty`, `item_image`, `item_price`, `item_created_at`, `item_updated_at`) VALUES
('item_4c29b73a-6b73-11ea-bfa2-270399f50534', 'rest_c2af4218-6b72-11ea-bfa2-270399f50534', 'Make It Half & Half', 'نص ونص', 'With your choice of two different selections.', 'اختيارك لنوعين من الفطائر', 1, 946, 'd-au1nLzdWNxjD7yfCe8lKrGnoG8LFJp.jpg', 2, '2020-03-21 15:55:53', '2020-04-10 15:24:02'),
('item_c6eaf116-6b76-11ea-bfa2-270399f50534', 'rest_c2af4218-6b72-11ea-bfa2-270399f50534', 'Pink Pasta', ' باستا وردية', 'Pink sauce, parmesan, parsley.', 'الصلصة الورديه. بارميزان، بقدونس', 2, 9, 'xcDlSnz3PXQ0x8INsWbpQe4uCwns_ijH.jpg', 3.25, '2020-03-21 16:20:47', '2020-04-10 15:12:53'),
('item_d0dec88c-6d06-11ea-bfa2-270399f50534', 'rest_c2af4218-6b72-11ea-bfa2-270399f50534', 'Zaatar', 'زعتر', 'Mixture of high quality zataar prepared from organic ingredient.', 'خليط الزعتر الفاخر غني بالمكونات الطبيعيه', 2, 9, 'oiDrGDMIJLxirdLVAawVzGNQxbzOv-Ra.jpg', 1.5, '2020-03-23 16:04:22', '2020-04-10 15:13:06');

-- --------------------------------------------------------

--
-- Table structure for table `migration`
--

CREATE TABLE `migration` (
  `version` varchar(180) NOT NULL,
  `apply_time` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `migration`
--

INSERT INTO `migration` (`version`, `apply_time`) VALUES
('m000000_000000_base', 1586520248),
('m130524_201442_init', 1586520254),
('m200119_140105_create_city_table', 1586520254),
('m200119_140111_create_area_table', 1586520254),
('m200119_140122_create_payment_method_table', 1586520254),
('m200119_140530_create_restaurant_table', 1586520254),
('m200119_140601_create_restaurant_payment_method_table', 1586520255),
('m200119_140648_create_restaurant_delivery_table', 1586520255),
('m200119_140711_create_item_table', 1586520255),
('m200119_140720_create_category_table', 1586520255),
('m200119_140733_create_category_item_table', 1586520255),
('m200119_140748_create_option_table', 1586520255),
('m200119_141034_create_extra_option_table', 1586520255),
('m200130_194447_create_order_table', 1586520255),
('m200314_113339_create_working_day_table', 1586520255),
('m200314_113345_create_working_hours_table', 1586520256),
('m200317_125607_create_restaurant_branch_table', 1586520256),
('m200325_152726_add_restaurant_api_key_column_to_restaurant_table', 1586520256),
('m200327_171548_create_agent_assignment', 1586520256),
('m200328_170639_add_email_notification_column_to_agent_table', 1586520256),
('m200328_195944_create_payment_table', 1586520293),
('m200328_210254_add_column_received_callback_to_payment', 1586520293),
('m200407_133656_add_delivery_time_ar_field_in_restaurant_delivery_table', 1586520293),
('m200408_144200_add_new_fields_to_restaurant_table', 1586520294);

-- --------------------------------------------------------

--
-- Table structure for table `option`
--

CREATE TABLE `option` (
  `option_id` int(11) NOT NULL,
  `item_uuid` varchar(300) COLLATE utf8_unicode_ci NOT NULL,
  `min_qty` tinyint(1) DEFAULT '0',
  `max_qty` int(11) UNSIGNED DEFAULT NULL,
  `option_name` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `option_name_ar` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `option`
--

INSERT INTO `option` (`option_id`, `item_uuid`, `min_qty`, `max_qty`, `option_name`, `option_name_ar`) VALUES
(99613, 'item_4c29b73a-6b73-11ea-bfa2-270399f50534', 1, 2, 'your choice of ', 'ختيارك من '),
(99614, 'item_4c29b73a-6b73-11ea-bfa2-270399f50534', 1, 1, ' your choice of Dough (Choose 1)', 'ختيارك من العجين'),
(99618, 'item_c6eaf116-6b76-11ea-bfa2-270399f50534', 1, 100, 'Add On\'s', 'إضافات'),
(99619, 'item_d0dec88c-6d06-11ea-bfa2-270399f50534', 0, 100, 'Add On', 'ختيارك من '),
(99620, 'item_d0dec88c-6d06-11ea-bfa2-270399f50534', 0, 1, 'your choice of Dough', 'ختيارك من العجين');

-- --------------------------------------------------------

--
-- Table structure for table `order`
--

CREATE TABLE `order` (
  `order_uuid` char(40) COLLATE utf8_unicode_ci NOT NULL,
  `payment_uuid` char(36) COLLATE utf8_unicode_ci DEFAULT NULL,
  `customer_id` bigint(20) DEFAULT NULL,
  `customer_name` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `customer_phone_number` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `customer_email` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `restaurant_uuid` char(60) COLLATE utf8_unicode_ci DEFAULT NULL,
  `area_id` int(11) DEFAULT NULL,
  `area_name` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `area_name_ar` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `unit_type` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `block` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `street` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `avenue` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `house_number` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `special_directions` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `payment_method_id` int(11) NOT NULL,
  `payment_method_name` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `payment_method_name_ar` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `total_price` float UNSIGNED DEFAULT '0',
  `total_items_price` float UNSIGNED DEFAULT '0',
  `delivery_fee` float UNSIGNED DEFAULT '0',
  `order_status` tinyint(1) UNSIGNED DEFAULT '1',
  `order_mode` tinyint(1) UNSIGNED NOT NULL,
  `estimated_time_of_arrival` time DEFAULT NULL,
  `delivery_time` int(11) DEFAULT NULL,
  `order_created_at` datetime NOT NULL,
  `order_updated_at` datetime NOT NULL,
  `restaurant_branch_id` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `order_item`
--

CREATE TABLE `order_item` (
  `order_item_id` bigint(20) NOT NULL,
  `order_uuid` char(40) COLLATE utf8_unicode_ci NOT NULL,
  `item_uuid` varchar(300) COLLATE utf8_unicode_ci DEFAULT NULL,
  `item_name` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `item_price` float NOT NULL,
  `qty` int(11) DEFAULT NULL,
  `customer_instruction` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `order_item_extra_option`
--

CREATE TABLE `order_item_extra_option` (
  `order_item_extra_option_id` bigint(20) NOT NULL,
  `order_item_id` bigint(20) NOT NULL,
  `extra_option_id` int(11) DEFAULT NULL,
  `extra_option_name` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `extra_option_name_ar` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `extra_option_price` float DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `payment`
--

CREATE TABLE `payment` (
  `payment_uuid` char(36) COLLATE utf8_unicode_ci NOT NULL,
  `customer_id` bigint(20) NOT NULL,
  `order_uuid` char(40) COLLATE utf8_unicode_ci NOT NULL,
  `payment_gateway_order_id` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `payment_gateway_transaction_id` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `payment_mode` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `payment_current_status` text COLLATE utf8_unicode_ci,
  `payment_amount_charged` double NOT NULL,
  `payment_net_amount` double DEFAULT NULL,
  `payment_gateway_fee` double DEFAULT NULL,
  `payment_udf1` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `payment_udf2` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `payment_udf3` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `payment_udf4` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `payment_udf5` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `payment_created_at` datetime DEFAULT NULL,
  `payment_updated_at` datetime DEFAULT NULL,
  `received_callback` tinyint(1) NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `payment_method`
--

CREATE TABLE `payment_method` (
  `payment_method_id` int(11) NOT NULL,
  `payment_method_name` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `payment_method_name_ar` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `payment_method`
--

INSERT INTO `payment_method` (`payment_method_id`, `payment_method_name`, `payment_method_name_ar`) VALUES
(1, 'K-net', 'كي نت'),
(2, 'Credit Card', 'بطاقة الائتمان'),
(3, 'Cash', 'نقدي');

-- --------------------------------------------------------

--
-- Table structure for table `restaurant`
--

CREATE TABLE `restaurant` (
  `restaurant_uuid` char(60) COLLATE utf8_unicode_ci NOT NULL,
  `agent_id` bigint(20) NOT NULL,
  `name` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `name_ar` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `tagline` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `tagline_ar` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `restaurant_status` smallint(1) NOT NULL DEFAULT '1',
  `thumbnail_image` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `logo` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `support_delivery` tinyint(1) NOT NULL,
  `support_pick_up` tinyint(1) NOT NULL,
  `phone_number` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `restaurant_created_at` datetime DEFAULT NULL,
  `restaurant_updated_at` datetime DEFAULT NULL,
  `business_id` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `business_entity_id` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `wallet_id` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `merchant_id` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `operator_id` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `live_api_key` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `test_api_key` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `business_type` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `vendor_sector` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `license_number` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `not_for_profit` tinyint(1) NOT NULL DEFAULT '0',
  `document_issuing_country` varchar(255) COLLATE utf8_unicode_ci NOT NULL DEFAULT 'KW',
  `document_issuing_date` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `document_expiry_date` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `document_title` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `document_file` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `document_file_id` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `document_file_purpose` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `iban` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `owner_first_name` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `owner_last_name` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `owner_email` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `owner_customer_number` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `identification_issuing_country` varchar(255) COLLATE utf8_unicode_ci NOT NULL DEFAULT 'KW',
  `identification_issuing_date` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `identification_expiry_date` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `identification_file` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `identification_file_id` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `identification_title` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `identification_file_purpose` varchar(255) COLLATE utf8_unicode_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `restaurant`
--

INSERT INTO `restaurant` (`restaurant_uuid`, `agent_id`, `name`, `name_ar`, `tagline`, `tagline_ar`, `restaurant_status`, `thumbnail_image`, `logo`, `support_delivery`, `support_pick_up`, `phone_number`, `restaurant_created_at`, `restaurant_updated_at`, `business_id`, `business_entity_id`, `wallet_id`, `merchant_id`, `operator_id`, `live_api_key`, `test_api_key`, `business_type`, `vendor_sector`, `license_number`, `not_for_profit`, `document_issuing_country`, `document_issuing_date`, `document_expiry_date`, `document_title`, `document_file`, `document_file_id`, `document_file_purpose`, `iban`, `owner_first_name`, `owner_last_name`, `owner_email`, `owner_customer_number`, `identification_issuing_country`, `identification_issuing_date`, `identification_expiry_date`, `identification_file`, `identification_file_id`, `identification_title`, `identification_file_purpose`) VALUES
('rest_c2af4218-6b72-11ea-bfa2-270399f50534', 1, 'OVENLY', 'أوفينلي', 'Pizzas, Breakfast, Italian', 'بيتزا, فطور, ايطالي', 1, 'XVQ_Xbw6LvT0PuVfkstCZAZ3x1qkGWJl.jpg', '92GgpQIQXiC3TUtYrAUW5hHJCFElYkBa.jpg', 1, 1, '', '2020-03-21 15:52:02', '2020-04-10 15:23:43', '', '', '', '', '', NULL, '', '', '', '', 0, 'KW', '', '', '', NULL, NULL, '', '', '', '', '', '', 'KW', '', '', NULL, NULL, '', '');

-- --------------------------------------------------------

--
-- Table structure for table `restaurant_branch`
--

CREATE TABLE `restaurant_branch` (
  `restaurant_branch_id` int(11) NOT NULL,
  `restaurant_uuid` char(60) COLLATE utf8_unicode_ci DEFAULT NULL,
  `branch_name_en` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `branch_name_ar` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `prep_time` int(11) UNSIGNED DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `restaurant_branch`
--

INSERT INTO `restaurant_branch` (`restaurant_branch_id`, `restaurant_uuid`, `branch_name_en`, `branch_name_ar`, `prep_time`) VALUES
(6, 'rest_c2af4218-6b72-11ea-bfa2-270399f50534', 'Salmiya', 'السالمية', 23);

-- --------------------------------------------------------

--
-- Table structure for table `restaurant_delivery`
--

CREATE TABLE `restaurant_delivery` (
  `restaurant_uuid` char(60) COLLATE utf8_unicode_ci NOT NULL,
  `area_id` int(11) NOT NULL,
  `delivery_time` int(11) UNSIGNED DEFAULT '60',
  `delivery_time_ar` int(11) UNSIGNED DEFAULT '60',
  `delivery_fee` float UNSIGNED DEFAULT '0',
  `min_charge` float UNSIGNED DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `restaurant_delivery`
--

INSERT INTO `restaurant_delivery` (`restaurant_uuid`, `area_id`, `delivery_time`, `delivery_time_ar`, `delivery_fee`, `min_charge`) VALUES
('rest_c2af4218-6b72-11ea-bfa2-270399f50534', 1, 60, 60, 0, 0),
('rest_c2af4218-6b72-11ea-bfa2-270399f50534', 35, 60, 60, 0, 0);

-- --------------------------------------------------------

--
-- Table structure for table `restaurant_payment_method`
--

CREATE TABLE `restaurant_payment_method` (
  `restaurant_uuid` char(60) COLLATE utf8_unicode_ci NOT NULL,
  `payment_method_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `restaurant_payment_method`
--

INSERT INTO `restaurant_payment_method` (`restaurant_uuid`, `payment_method_id`) VALUES
('rest_c2af4218-6b72-11ea-bfa2-270399f50534', 1),
('rest_c2af4218-6b72-11ea-bfa2-270399f50534', 3);

-- --------------------------------------------------------

--
-- Table structure for table `working_day`
--

CREATE TABLE `working_day` (
  `working_day_id` bigint(20) NOT NULL,
  `name` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `name_ar` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `working_day`
--

INSERT INTO `working_day` (`working_day_id`, `name`, `name_ar`) VALUES
(1, 'Sunday', 'الأحد'),
(2, 'Monday', 'الإثنين'),
(3, 'Tuesday', 'الثلاثاء'),
(4, 'Wednesday', 'الأربعاء'),
(5, 'Thursday', 'الخميس'),
(6, 'Friday', 'الجمعة'),
(7, 'Saturday', 'السبت');

-- --------------------------------------------------------

--
-- Table structure for table `working_hours`
--

CREATE TABLE `working_hours` (
  `working_day_id` bigint(20) NOT NULL,
  `restaurant_uuid` char(60) COLLATE utf8_unicode_ci NOT NULL,
  `operating_from` time DEFAULT NULL,
  `operating_to` time DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `admin`
--
ALTER TABLE `admin`
  ADD PRIMARY KEY (`admin_id`),
  ADD UNIQUE KEY `admin_email` (`admin_email`),
  ADD UNIQUE KEY `admin_password_reset_token` (`admin_password_reset_token`);

--
-- Indexes for table `agent`
--
ALTER TABLE `agent`
  ADD PRIMARY KEY (`agent_id`),
  ADD UNIQUE KEY `agent_email` (`agent_email`),
  ADD UNIQUE KEY `agent_password_reset_token` (`agent_password_reset_token`);

--
-- Indexes for table `agent_assignment`
--
ALTER TABLE `agent_assignment`
  ADD PRIMARY KEY (`assignment_id`),
  ADD KEY `idx-agent_assignment-restaurant_uuid` (`restaurant_uuid`),
  ADD KEY `idx-agent_assignment-agent_id` (`agent_id`);

--
-- Indexes for table `area`
--
ALTER TABLE `area`
  ADD PRIMARY KEY (`area_id`),
  ADD KEY `idx-area-city_id` (`city_id`);

--
-- Indexes for table `category`
--
ALTER TABLE `category`
  ADD PRIMARY KEY (`category_id`),
  ADD KEY `idx-category-restaurant_uuid` (`restaurant_uuid`);

--
-- Indexes for table `category_item`
--
ALTER TABLE `category_item`
  ADD PRIMARY KEY (`category_item_id`),
  ADD KEY `idx-category_item-item_uuid` (`item_uuid`),
  ADD KEY `idx-category_item-category_id` (`category_id`);

--
-- Indexes for table `city`
--
ALTER TABLE `city`
  ADD PRIMARY KEY (`city_id`);

--
-- Indexes for table `customer`
--
ALTER TABLE `customer`
  ADD PRIMARY KEY (`customer_id`),
  ADD KEY `idx-customer-restaurant_uuid` (`restaurant_uuid`);

--
-- Indexes for table `extra_option`
--
ALTER TABLE `extra_option`
  ADD PRIMARY KEY (`extra_option_id`),
  ADD KEY `idx-extra_option-option_id` (`option_id`);

--
-- Indexes for table `item`
--
ALTER TABLE `item`
  ADD PRIMARY KEY (`item_uuid`),
  ADD UNIQUE KEY `item_uuid` (`item_uuid`),
  ADD KEY `idx-item-restaurant_uuid` (`restaurant_uuid`);

--
-- Indexes for table `migration`
--
ALTER TABLE `migration`
  ADD PRIMARY KEY (`version`);

--
-- Indexes for table `option`
--
ALTER TABLE `option`
  ADD PRIMARY KEY (`option_id`),
  ADD KEY `idx-option-item_uuid` (`item_uuid`);

--
-- Indexes for table `order`
--
ALTER TABLE `order`
  ADD PRIMARY KEY (`order_uuid`),
  ADD KEY `idx-order-customer_id` (`customer_id`),
  ADD KEY `idx-order-restaurant_uuid` (`restaurant_uuid`),
  ADD KEY `idx-order-area_id` (`area_id`),
  ADD KEY `idx-order-payment_method_id` (`payment_method_id`),
  ADD KEY `idx-order-restaurant_branch_id` (`restaurant_branch_id`),
  ADD KEY `idx-order-payment_uuid` (`payment_uuid`);

--
-- Indexes for table `order_item`
--
ALTER TABLE `order_item`
  ADD PRIMARY KEY (`order_item_id`),
  ADD KEY `idx-order_item-order_uuid` (`order_uuid`),
  ADD KEY `idx-order_item-item_uuid` (`item_uuid`);

--
-- Indexes for table `order_item_extra_option`
--
ALTER TABLE `order_item_extra_option`
  ADD PRIMARY KEY (`order_item_extra_option_id`),
  ADD KEY `idx-order_item_extra_option-order_item_id` (`order_item_id`),
  ADD KEY `idx-order_item_extra_option-extra_option_id` (`extra_option_id`);

--
-- Indexes for table `payment`
--
ALTER TABLE `payment`
  ADD PRIMARY KEY (`payment_uuid`),
  ADD KEY `idx-payment-payment_gateway_order_id` (`payment_gateway_order_id`),
  ADD KEY `idx-payment-payment_gateway_transaction_id` (`payment_gateway_transaction_id`),
  ADD KEY `idx-payment-customer_id` (`customer_id`),
  ADD KEY `idx-payment-order_uuid` (`order_uuid`);

--
-- Indexes for table `payment_method`
--
ALTER TABLE `payment_method`
  ADD PRIMARY KEY (`payment_method_id`);

--
-- Indexes for table `restaurant`
--
ALTER TABLE `restaurant`
  ADD PRIMARY KEY (`restaurant_uuid`),
  ADD UNIQUE KEY `restaurant_uuid` (`restaurant_uuid`),
  ADD KEY `idx-restaurant-agent_id` (`agent_id`);

--
-- Indexes for table `restaurant_branch`
--
ALTER TABLE `restaurant_branch`
  ADD PRIMARY KEY (`restaurant_branch_id`),
  ADD KEY `idx-restaurant_branch-restaurant_uuid` (`restaurant_uuid`);

--
-- Indexes for table `restaurant_delivery`
--
ALTER TABLE `restaurant_delivery`
  ADD PRIMARY KEY (`restaurant_uuid`,`area_id`),
  ADD KEY `idx-restaurant_delivery-restaurant_uuid` (`restaurant_uuid`),
  ADD KEY `idx-restaurant_delivery-area_id` (`area_id`);

--
-- Indexes for table `restaurant_payment_method`
--
ALTER TABLE `restaurant_payment_method`
  ADD PRIMARY KEY (`restaurant_uuid`,`payment_method_id`),
  ADD KEY `idx-restaurant_payment_method-restaurant_uuid` (`restaurant_uuid`),
  ADD KEY `idx-restaurant_payment_method-payment_method_id` (`payment_method_id`);

--
-- Indexes for table `working_day`
--
ALTER TABLE `working_day`
  ADD PRIMARY KEY (`working_day_id`);

--
-- Indexes for table `working_hours`
--
ALTER TABLE `working_hours`
  ADD PRIMARY KEY (`working_day_id`,`restaurant_uuid`),
  ADD KEY `idx-working_hours-working_day_id` (`working_day_id`),
  ADD KEY `idx-working_hours-restaurant_uuid` (`restaurant_uuid`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `admin`
--
ALTER TABLE `admin`
  MODIFY `admin_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `agent`
--
ALTER TABLE `agent`
  MODIFY `agent_id` bigint(20) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `agent_assignment`
--
ALTER TABLE `agent_assignment`
  MODIFY `assignment_id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `area`
--
ALTER TABLE `area`
  MODIFY `area_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=138;

--
-- AUTO_INCREMENT for table `category`
--
ALTER TABLE `category`
  MODIFY `category_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=450;

--
-- AUTO_INCREMENT for table `category_item`
--
ALTER TABLE `category_item`
  MODIFY `category_item_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=72;

--
-- AUTO_INCREMENT for table `city`
--
ALTER TABLE `city`
  MODIFY `city_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT for table `customer`
--
ALTER TABLE `customer`
  MODIFY `customer_id` bigint(20) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `extra_option`
--
ALTER TABLE `extra_option`
  MODIFY `extra_option_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=96517;

--
-- AUTO_INCREMENT for table `option`
--
ALTER TABLE `option`
  MODIFY `option_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=99621;

--
-- AUTO_INCREMENT for table `order_item`
--
ALTER TABLE `order_item`
  MODIFY `order_item_id` bigint(20) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `order_item_extra_option`
--
ALTER TABLE `order_item_extra_option`
  MODIFY `order_item_extra_option_id` bigint(20) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `payment_method`
--
ALTER TABLE `payment_method`
  MODIFY `payment_method_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `restaurant_branch`
--
ALTER TABLE `restaurant_branch`
  MODIFY `restaurant_branch_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `working_day`
--
ALTER TABLE `working_day`
  MODIFY `working_day_id` bigint(20) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `agent_assignment`
--
ALTER TABLE `agent_assignment`
  ADD CONSTRAINT `fk-agent_assignment-agent_id` FOREIGN KEY (`agent_id`) REFERENCES `agent` (`agent_id`) ON DELETE CASCADE,
  ADD CONSTRAINT `fk-agent_assignment-restaurant_uuid` FOREIGN KEY (`restaurant_uuid`) REFERENCES `restaurant` (`restaurant_uuid`) ON DELETE CASCADE;

--
-- Constraints for table `area`
--
ALTER TABLE `area`
  ADD CONSTRAINT `fk-area-city_id` FOREIGN KEY (`city_id`) REFERENCES `city` (`city_id`) ON DELETE CASCADE;

--
-- Constraints for table `category`
--
ALTER TABLE `category`
  ADD CONSTRAINT `fk-category-restaurant_uuid` FOREIGN KEY (`restaurant_uuid`) REFERENCES `restaurant` (`restaurant_uuid`) ON DELETE CASCADE;

--
-- Constraints for table `category_item`
--
ALTER TABLE `category_item`
  ADD CONSTRAINT `fk-category_item-category_id` FOREIGN KEY (`category_id`) REFERENCES `category` (`category_id`) ON DELETE CASCADE,
  ADD CONSTRAINT `fk-category_item-item_uuid` FOREIGN KEY (`item_uuid`) REFERENCES `item` (`item_uuid`) ON DELETE CASCADE;

--
-- Constraints for table `customer`
--
ALTER TABLE `customer`
  ADD CONSTRAINT `fk-customer-restaurant_uuid` FOREIGN KEY (`restaurant_uuid`) REFERENCES `restaurant` (`restaurant_uuid`) ON DELETE CASCADE;

--
-- Constraints for table `extra_option`
--
ALTER TABLE `extra_option`
  ADD CONSTRAINT `fk-extra_option-option_id` FOREIGN KEY (`option_id`) REFERENCES `option` (`option_id`) ON DELETE CASCADE;

--
-- Constraints for table `item`
--
ALTER TABLE `item`
  ADD CONSTRAINT `fk-item-restaurant_uuid` FOREIGN KEY (`restaurant_uuid`) REFERENCES `restaurant` (`restaurant_uuid`) ON DELETE CASCADE;

--
-- Constraints for table `option`
--
ALTER TABLE `option`
  ADD CONSTRAINT `fk-option-item_uuid` FOREIGN KEY (`item_uuid`) REFERENCES `item` (`item_uuid`) ON DELETE CASCADE;

--
-- Constraints for table `order`
--
ALTER TABLE `order`
  ADD CONSTRAINT `fk-order-area_id` FOREIGN KEY (`area_id`) REFERENCES `area` (`area_id`) ON DELETE CASCADE,
  ADD CONSTRAINT `fk-order-customer_id` FOREIGN KEY (`customer_id`) REFERENCES `customer` (`customer_id`) ON DELETE CASCADE,
  ADD CONSTRAINT `fk-order-payment_method_id` FOREIGN KEY (`payment_method_id`) REFERENCES `payment_method` (`payment_method_id`) ON DELETE CASCADE,
  ADD CONSTRAINT `fk-order-payment_uuid` FOREIGN KEY (`payment_uuid`) REFERENCES `payment` (`payment_uuid`) ON DELETE CASCADE,
  ADD CONSTRAINT `fk-order-restaurant_branch_id` FOREIGN KEY (`restaurant_branch_id`) REFERENCES `restaurant_branch` (`restaurant_branch_id`) ON DELETE CASCADE,
  ADD CONSTRAINT `fk-order-restaurant_uuid` FOREIGN KEY (`restaurant_uuid`) REFERENCES `restaurant` (`restaurant_uuid`) ON DELETE CASCADE;

--
-- Constraints for table `order_item`
--
ALTER TABLE `order_item`
  ADD CONSTRAINT `fk-order_item-item_uuid` FOREIGN KEY (`item_uuid`) REFERENCES `item` (`item_uuid`) ON DELETE SET NULL ON UPDATE SET NULL,
  ADD CONSTRAINT `fk-order_item-order_uuid` FOREIGN KEY (`order_uuid`) REFERENCES `order` (`order_uuid`) ON DELETE CASCADE;

--
-- Constraints for table `order_item_extra_option`
--
ALTER TABLE `order_item_extra_option`
  ADD CONSTRAINT `fk-order_item_extra_option-extra_option_id` FOREIGN KEY (`extra_option_id`) REFERENCES `extra_option` (`extra_option_id`) ON DELETE SET NULL ON UPDATE SET NULL,
  ADD CONSTRAINT `fk-order_item_extra_option-order_item_id` FOREIGN KEY (`order_item_id`) REFERENCES `order_item` (`order_item_id`) ON DELETE CASCADE;

--
-- Constraints for table `payment`
--
ALTER TABLE `payment`
  ADD CONSTRAINT `fk-payment-customer_id` FOREIGN KEY (`customer_id`) REFERENCES `customer` (`customer_id`) ON DELETE CASCADE,
  ADD CONSTRAINT `fk-payment-order_uuid` FOREIGN KEY (`order_uuid`) REFERENCES `order` (`order_uuid`) ON DELETE CASCADE;

--
-- Constraints for table `restaurant`
--
ALTER TABLE `restaurant`
  ADD CONSTRAINT `fk-restaurant-agent_id` FOREIGN KEY (`agent_id`) REFERENCES `agent` (`agent_id`) ON DELETE CASCADE;

--
-- Constraints for table `restaurant_branch`
--
ALTER TABLE `restaurant_branch`
  ADD CONSTRAINT `fk-restaurant_branch-restaurant_uuid` FOREIGN KEY (`restaurant_uuid`) REFERENCES `restaurant` (`restaurant_uuid`) ON DELETE CASCADE;

--
-- Constraints for table `restaurant_delivery`
--
ALTER TABLE `restaurant_delivery`
  ADD CONSTRAINT `fk-restaurant_delivery-area_id` FOREIGN KEY (`area_id`) REFERENCES `area` (`area_id`) ON DELETE CASCADE,
  ADD CONSTRAINT `fk-restaurant_delivery-restaurant_uuid` FOREIGN KEY (`restaurant_uuid`) REFERENCES `restaurant` (`restaurant_uuid`) ON DELETE CASCADE;

--
-- Constraints for table `restaurant_payment_method`
--
ALTER TABLE `restaurant_payment_method`
  ADD CONSTRAINT `fk-restaurant_payment_method-payment_method_id` FOREIGN KEY (`payment_method_id`) REFERENCES `payment_method` (`payment_method_id`) ON DELETE CASCADE,
  ADD CONSTRAINT `fk-restaurant_payment_method-restaurant_uuid` FOREIGN KEY (`restaurant_uuid`) REFERENCES `restaurant` (`restaurant_uuid`) ON DELETE CASCADE;

--
-- Constraints for table `working_hours`
--
ALTER TABLE `working_hours`
  ADD CONSTRAINT `fk-working_hours-restaurant_uuid` FOREIGN KEY (`restaurant_uuid`) REFERENCES `restaurant` (`restaurant_uuid`) ON DELETE CASCADE,
  ADD CONSTRAINT `fk-working_hours-working_day_id` FOREIGN KEY (`working_day_id`) REFERENCES `working_day` (`working_day_id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
