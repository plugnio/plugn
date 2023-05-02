-- phpMyAdmin SQL Dump
-- version 5.1.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Apr 27, 2023 at 08:11 AM
-- Server version: 10.4.22-MariaDB
-- PHP Version: 8.1.2

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";

START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `plugn`
--

-- --------------------------------------------------------

--
-- Table structure for table `addon`
--

CREATE TABLE `addon` (
  `addon_uuid` char(60) COLLATE utf8_unicode_ci NOT NULL,
  `name` varchar(100) COLLATE utf8_unicode_ci NOT NULL,
  `name_ar` varchar(100) COLLATE utf8_unicode_ci NOT NULL,
  `description` text COLLATE utf8_unicode_ci NOT NULL,
  `description_ar` text COLLATE utf8_unicode_ci DEFAULT NULL,
  `price` decimal(10,3) NOT NULL,
  `special_price` decimal(10,3) DEFAULT NULL,
  `slug` varchar(100) COLLATE utf8_unicode_ci NOT NULL,
  `expected_delivery` smallint(3) DEFAULT NULL COMMENT 'in days',
  `sort_number` int(11) UNSIGNED DEFAULT 0,
  `status` tinyint(2) DEFAULT 10,
  `created_by` int(11) DEFAULT NULL,
  `updated_by` int(11) DEFAULT NULL,
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `addon_payment`
--

CREATE TABLE `addon_payment` (
  `payment_uuid` char(60) COLLATE utf8_unicode_ci NOT NULL,
  `restaurant_uuid` char(60) COLLATE utf8_unicode_ci DEFAULT NULL,
  `addon_uuid` char(60) COLLATE utf8_unicode_ci DEFAULT NULL,
  `payment_gateway_order_id` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `payment_gateway_transaction_id` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `payment_mode` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `payment_current_status` text COLLATE utf8_unicode_ci DEFAULT NULL,
  `payment_amount_charged` decimal(10,3) NOT NULL,
  `payment_net_amount` decimal(10,3) DEFAULT NULL,
  `payment_gateway_fee` decimal(10,3) DEFAULT NULL,
  `payment_udf1` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `payment_udf2` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `payment_udf3` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `payment_udf4` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `payment_udf5` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `received_callback` tinyint(1) NOT NULL DEFAULT 0,
  `response_message` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `is_sandbox` tinyint(1) DEFAULT 0,
  `payment_token` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `payment_created_at` datetime DEFAULT NULL,
  `payment_updated_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `admin`
--

CREATE TABLE `admin` (
  `admin_id` int(11) NOT NULL,
  `admin_name` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `admin_email` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `admin_role` smallint(1) DEFAULT 1,
  `admin_auth_key` varchar(32) COLLATE utf8_unicode_ci NOT NULL,
  `admin_password_hash` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `admin_password_reset_token` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `admin_status` smallint(6) NOT NULL DEFAULT 10,
  `admin_created_at` datetime NOT NULL,
  `admin_updated_at` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `admin`
--

INSERT INTO `admin` (`admin_id`, `admin_name`, `admin_email`, `admin_role`, `admin_auth_key`, `admin_password_hash`, `admin_password_reset_token`, `admin_status`, `admin_created_at`, `admin_updated_at`) VALUES
(1, 'Dewayne 1', 'flatley.keyon@gutmann.com', 1, 'eNY2xviIX4sOl3SNqZHOabLwowkPxTR6', '$2y$13$KCfqrEBRiG/FjiOH3TEII.kss4XDkcudlqOphZ4QurOr5NodFfanq', NULL, 10, '2011-08-14 23:17:58', '1989-01-24 19:14:12'),
(2, 'Chyna', 'gquigley@rohan.com', 1, 'oPEbG8M1z_XTtjpLiV8NSor3ih0hIpzm', '$2y$13$MqNC3zQAYrCeXtHnemjQ.eUxWSVVhG5RxOPMknINdieEu3aroP0qC', NULL, 10, '1987-02-07 05:16:42', '1981-04-27 11:28:12'),
(3, 'Hyman', 'fred03@hotmail.com', 1, 'g9UcTXKh-F9pvr3y2YXh8sSMB1X-onc3', '$2y$13$CU8FRH23c.0vZkt0ebiyL.PRmoi6pPf1bHM7l8MyutLdcIROX3hTe', NULL, 10, '1975-12-08 08:08:02', '1998-09-15 04:12:03'),
(4, 'Fletcher', 'collins.erich@gmail.com', 1, 'aSzzH0lEU3Uo_Diry6YXl1A4jVa62LCR', '$2y$13$oxrbX0.B1lpSc19pIMD.UuH/ggJOR63xdEggB.tv89iZOF0A1IgEO', NULL, 10, '1972-08-31 06:51:34', '1976-01-31 14:33:16'),
(5, 'Ellie', 'adubuque@gmail.com', 1, '89Wz8Qhtp_oez3bXUb1qV7H1J8LRwi5z', '$2y$13$5C8rgZQu1UGvRrb4UOh43uJifWQzo/tq1tuDq9TkfUGiXkxCSQiIG', NULL, 10, '1971-06-03 08:39:46', '1973-04-26 04:05:49'),
(6, 'Hulda', 'torphy.camden@yahoo.com', 1, 'ZaRBomjvLh6fJ9IYAx22a0HLuR5sVTXz', '$2y$13$DKDFZeHQqT6S35WkiW7lEOWzmPAAzhhUiTX6R25GgnUKKstCEIx6i', NULL, 10, '2019-04-05 11:33:25', '1983-12-28 17:23:50'),
(7, 'Laurianne', 'edwardo73@padberg.com', 1, 'O3nMlhIXQ0PRc1hqRya5vrYDLtDTez1I', '$2y$13$2Nk4.sjp3KZSV9Y.zUOaeu55MHtPMMm1eVz0ruSYmlfEoFmdJFYey', NULL, 10, '2005-04-11 04:57:35', '1987-03-18 10:54:30'),
(8, 'Trinity', 'osinski.giovanny@hotmail.com', 1, 'TatwWnNYZmQdSigz9CbQEy7O6PoV8D6f', '$2y$13$Djf2TDTKQ6IxNAAZC/XwNegvIM6B5L9dTjToyYA4HjAo5hqcRat4a', NULL, 10, '2014-11-01 01:02:46', '2018-04-29 23:36:22'),
(9, 'Lowell', 'stokes.mariana@hotmail.com', 1, 'oiphbd7XR32Wz4fuaSv_xeq5CvOdEzvK', '$2y$13$l23I3xFpI4CHTkgMxKyEH.GKNawihC6vo6utEKGSRf46q8Z8MbL4K', NULL, 10, '2005-11-04 21:24:23', '2011-03-24 14:45:45'),
(10, 'Elenora', 'beatty.jalen@bechtelar.com', 1, '_S1ED1PG2qbgGYxCS7ZcxB_1dlgylDLG', '$2y$13$S/KwWqHu3Fl/X6knXcpyE.BPSlAZrwaSVpd30Spkyn0N.sMIaqyTq', NULL, 10, '2017-05-10 18:27:22', '2016-08-11 15:46:52');

-- --------------------------------------------------------

--
-- Table structure for table `agent`
--

CREATE TABLE `agent` (
  `agent_id` bigint(20) NOT NULL,
  `agent_name` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `agent_email` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `agent_new_email` varchar(100) COLLATE utf8_unicode_ci DEFAULT NULL,
  `agent_email_verification` tinyint(1) DEFAULT 0,
  `agent_limit_email` datetime DEFAULT NULL,
  `agent_auth_key` varchar(32) COLLATE utf8_unicode_ci NOT NULL,
  `agent_password_hash` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `agent_password_reset_token` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `agent_status` smallint(6) NOT NULL DEFAULT 10,
  `email_notification` smallint(6) DEFAULT 1,
  `reminder_email` smallint(6) DEFAULT 0,
  `agent_language_pref` char(2) COLLATE utf8_unicode_ci DEFAULT NULL,
  `last_active_at` datetime DEFAULT NULL,
  `agent_created_at` datetime NOT NULL,
  `agent_updated_at` datetime NOT NULL,
  `receive_weekly_stats` smallint(6) DEFAULT 1,
  `deleted` tinyint(1) DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `agent`
--

INSERT INTO `agent` (`agent_id`, `agent_name`, `agent_email`, `agent_new_email`, `agent_email_verification`, `agent_limit_email`, `agent_auth_key`, `agent_password_hash`, `agent_password_reset_token`, `agent_status`, `email_notification`, `reminder_email`, `agent_language_pref`, `last_active_at`, `agent_created_at`, `agent_updated_at`, `receive_weekly_stats`, `deleted`) VALUES
(1, 'Saoud', 'saoud@bawes.net', NULL, 0, NULL, '5NcP6XSBAm1iHv2QJSS8EiTVdazGMF39', '$2y$13$lYld8hC1hecGOrLec2Z2G.TEsgne3K4TNitySLWgQFNL331iYyBy2', NULL, 10, 1, 1, NULL, NULL, '1989-11-01 22:53:19', '2002-05-20 19:52:23', 1, 0),
(2, 'Jaron', 'briana.kub@king.com', NULL, 0, NULL, '80_PlnHyz1NnuQful_QzxYEPjl5wAnR4', '$2y$13$qbobQVGaRKSmFArb10rmlu8I3y5zNtD5DB6dl2mL2n2rHA/1VNxse', NULL, 10, 1, 1, NULL, NULL, '2019-06-12 06:43:37', '2004-07-31 05:59:47', 1, 0),
(3, 'Rhianna', 'schimmel.dario@yahoo.com', NULL, 0, NULL, 'np66QFOAEMgsbU6cVo2YLdmell3bjm76', '$2y$13$75TzSskVxpQtqdNTYhNKe.cVByWX7AotASmuJvZQ114Fm5jGV2jGS', NULL, 10, 1, 1, NULL, NULL, '2021-04-05 22:56:25', '2019-12-07 07:38:09', 1, 0),
(4, 'Kaitlyn', 'reilly.kaylah@gmail.com', NULL, 0, NULL, 'fu49mnOOuX-tV13npvtQS5SUNYmHGzK2', '$2y$13$cWR7PbLLT8vYZdG74QLnbeksDjPW.qcTShatmvWbavf0xJOGGTTva', NULL, 10, 1, 1, NULL, NULL, '1977-08-22 00:24:49', '2009-07-03 07:43:52', 1, 0),
(5, 'Kiara', 'adams.kyla@hotmail.com', NULL, 0, NULL, 'FqEkv0spM0phioiv0wyPVERCBWU19SJh', '$2y$13$/Q8Wy9rRhqKgMih0J8ObYu0jgkacITuadIl3rlxw1YECJl9V3tDV6', NULL, 10, 1, 1, NULL, NULL, '1985-11-06 07:33:34', '2007-10-01 13:09:17', 1, 0),
(6, 'Walton', 'gsawayn@hotmail.com', NULL, 0, NULL, 'wFffYjLnDbayXcwxtSHgBmGaync-5C2f', '$2y$13$sQwzReAOCXgsSewYNPZFMemo5y.m01L1kR44lPVPcjA96QTa5QFOi', NULL, 10, 1, 1, NULL, NULL, '2018-12-31 16:43:31', '1972-03-27 02:47:29', 1, 0),
(7, 'Cathryn', 'krajcik.abelardo@yahoo.com', NULL, 0, NULL, 'uvQvl5iL4aa6W9nEsdJUAXJ3GZKSKJLi', '$2y$13$Kui1q/bNkW335RivvuE.PO3DgVMNle.mU.K60QbnL3PAKTCO2d14e', NULL, 10, 1, 1, NULL, NULL, '2008-01-05 16:10:58', '1983-05-11 12:23:53', 1, 0),
(8, 'Dedric', 'kessler.rico@douglas.com', NULL, 0, NULL, 'd8RHtk0A-LD4YtDbvR284q8ntTylxTdL', '$2y$13$339PL.l2hOMZ7z8t8TfRk.o6DvQ6/ZKOFnijRhfZlcnSuYmz21/3q', NULL, 10, 1, 1, NULL, NULL, '2005-02-09 02:50:05', '1970-04-30 04:23:20', 1, 0),
(9, 'Thurman', 'kfahey@reinger.com', NULL, 0, NULL, 'qVLnqONNN3-J53aux8QSnX54h4AHz8y0', '$2y$13$c5ZtDV70/q6yXxWQUNJzJe8dXu6wpOGKAlNMPqNCXJqmczectY2O2', NULL, 10, 1, 1, NULL, NULL, '1999-08-27 18:04:33', '2019-06-19 15:26:32', 1, 0),
(10, 'Lorenza', 'david73@yahoo.com', NULL, 0, NULL, '-TzaRyMULl9QZlQ5Ng-WjDEWWnmeyio6', '$2y$13$bMH/iS1ntexGNZn2MTWjT.EitHZBV821VnooOgLBzSz6teHnm8wsu', NULL, 10, 1, 1, NULL, NULL, '2010-03-16 04:54:40', '2007-05-13 03:45:23', 1, 0),
(11, 'Kennedy', 'buster.conroy@mraz.com', NULL, 0, NULL, 'p74zABvibPZBLM0m0g_VkRGBiy7wC059', '$2y$13$jb7bIWA8h6E9OfS5W6GT6Ofw5z8muGdZY7tRBkXutlr4Qu17WZWNC', NULL, 10, 1, 1, NULL, NULL, '2009-08-15 21:27:58', '1977-06-23 04:14:38', 1, 0),
(12, 'Webster', 'abelardo.effertz@schmitt.com', NULL, 0, NULL, 'JUev1AsOe9OMCg2gwF0e8-ml5AOY9wir', '$2y$13$TeXJPOHVgC1lV3CZPYAAd..kifx6afq7Rai9lrNPQoKXDIVI2EQzq', NULL, 10, 1, 1, NULL, NULL, '2018-08-08 11:31:14', '2012-03-18 19:02:48', 1, 0),
(13, 'Stephen', 'gerhold.treva@yahoo.com', NULL, 0, NULL, 'uWv6wtzQWN-z_2Fn8bU5o-TvftqIUcFv', '$2y$13$EsMo63FTg1VNoIwwVY2z0usdok4bEQpdQUtPlY5QrtrUuH80Pavzi', NULL, 10, 1, 1, NULL, NULL, '1991-02-24 12:36:52', '1982-09-16 15:23:50', 1, 0),
(14, 'Lauriane', 'assunta.okuneva@mraz.com', NULL, 0, NULL, 'aFE2V1jM9Zza06TBL7XxwRI5y5o_hUkW', '$2y$13$XPffQK9/z3g1XZrAGbFhieexMQWbNxY.5u.vfLYK.Wt.iOaV0/B.i', NULL, 10, 1, 1, NULL, NULL, '1996-04-06 16:22:34', '2016-10-29 22:58:36', 1, 0),
(15, 'Kayleigh', 'myrna.brakus@kulas.com', NULL, 0, NULL, 'ckD5YnJurRMgr_QYnM0OSfprOTIt69qA', '$2y$13$1fLkQQ5yWCQxOzeQTRg73OI8YoEUUp5G.Pw4ZduBjwAqXiwuqc8TS', NULL, 10, 1, 1, NULL, NULL, '2001-03-15 05:35:00', '1975-05-22 11:11:50', 1, 0),
(16, 'Willard', 'marvin47@gmail.com', NULL, 0, NULL, 'j_-Y1zdsEOsPH_tvF__63FXdWhC0m1ud', '$2y$13$gxAODzHBYZ6xjuPx2BiS5eb5k35CLW4rCAN8o9W7CiOg16sqDh9Di', NULL, 10, 1, 1, NULL, NULL, '2000-09-01 02:18:06', '2012-10-04 11:32:44', 1, 0),
(17, 'Jaunita', 'jadyn20@feest.com', NULL, 0, NULL, 'DEhnEUnxND427sWFKZQkB2cHlQxtoczn', '$2y$13$jUKR0b0CQ7APu/N6sF5VDO53gRkDf/ELes373BzkcJ95nxb6E6RD.', NULL, 10, 1, 1, NULL, NULL, '2016-05-05 18:11:50', '1993-02-17 14:57:10', 1, 0),
(18, 'Betty', 'welch.daniela@gmail.com', NULL, 0, NULL, 'pn9kisfY1f1GtwdOf1Xr4OXwTq_M7vS4', '$2y$13$a8QO9FkFjpbUwLx7GV4iPOmBDF9uvkJhJC4Kc074oyA3Hd5TZYasG', NULL, 10, 1, 1, NULL, NULL, '1971-10-01 21:44:21', '2006-09-01 01:42:47', 1, 0),
(19, 'Vida', 'dorris68@hotmail.com', NULL, 0, NULL, 'qCe4tsRIUFin5L1RpUaunJMRR5Ckhno4', '$2y$13$wy67e914PJhIBccEJBdaIOJ.dPdtZadQvEpOmQuaNkMVYmoM9ymLq', NULL, 10, 1, 1, NULL, NULL, '2009-11-09 00:48:15', '1998-04-12 02:49:04', 1, 0),
(20, 'Darion', 'mariane.torp@hotmail.com', NULL, 0, NULL, '34SLmOBPHF3VXuTWxBReF6k1uWldpop1', '$2y$13$nQiRk/NkuzvsxRONdmvZk.u4Dyde/IMEf8IidHdqoMn9oMF5MzDJ6', NULL, 10, 1, 1, NULL, NULL, '2007-04-19 23:42:02', '1993-06-05 18:51:27', 1, 0),
(21, 'Graciela', 'xavier.hauck@gmail.com', NULL, 0, NULL, '9039xa0HZrMxeVkVGLOZ3-5mQBYsSRyw', '$2y$13$9B.3aZ2ech9WGjPiOZ24nOXkny8d5pLy5gEGxf4B4Tmo63SOLu1ia', NULL, 10, 1, 1, NULL, NULL, '1991-10-03 21:26:14', '1983-12-20 15:04:25', 1, 0),
(22, 'Brennon', 'kertzmann.hollis@shanahan.com', NULL, 0, NULL, '2QqGhTROvuD-R-TBNg6L2nYHdibKMfve', '$2y$13$fUa8qxN8gtEYA0nNtapoHuhVDaYkkZwLc9iACWdYwFr25/bbu8r62', NULL, 10, 1, 1, NULL, NULL, '1979-11-09 01:48:59', '1995-03-31 05:14:27', 1, 0),
(23, 'Cleveland', 'grace73@mayert.org', NULL, 0, NULL, 'NbFtsWF9BthxR-ZZwJzWkLF4rNJx1V6w', '$2y$13$joOSSwYuZMH9ejJQwtVLtOAw5K6dSBWLR4aonjo6H2tn62SSUK2/u', NULL, 10, 1, 1, NULL, NULL, '2017-08-01 17:03:21', '1996-06-15 21:54:54', 1, 0),
(24, 'Harry', 'morissette.kyra@collins.net', NULL, 0, NULL, 'QcQqED6O0LrdOWTHuxyh3Py0ajgN454L', '$2y$13$jTB0qZ1doAH4gdZP5gVoruLF7tcGmnQKbyttkQjZCGPDxwb7q5z4i', NULL, 10, 1, 1, NULL, NULL, '1979-09-20 15:00:28', '2002-11-21 11:54:32', 1, 0),
(25, 'Liliana', 'lmante@langworth.com', NULL, 0, NULL, 'FjBk7YsMbvB92OFBtV6WtpepEVr_dpgx', '$2y$13$G.x5VCwwjXee7UPI936Bl.eDvGoT6XySsTCvKjvlApoRyaZF4.Ty2', NULL, 10, 1, 1, NULL, NULL, '2016-08-22 22:50:44', '2002-02-05 16:27:53', 1, 0),
(26, 'Olga', 'hermann.talon@corkery.net', NULL, 0, NULL, 'nDmX56wpivOEonyVUVa5mbHLQpup_C40', '$2y$13$MQo2xErJSoSTdQXJ.KaEpOiULuQD05oMmVY0x9WxurRQKqVtfZQ6m', NULL, 10, 1, 1, NULL, NULL, '2004-01-17 17:49:37', '1995-04-24 15:58:14', 1, 0),
(27, 'Myah', 'xernser@cole.net', NULL, 0, NULL, 'uRpfMiirEc2ch88SDgLunTCmBSiuM5fw', '$2y$13$qaIrFDZPv1FcqRdPr4GhHeN1cjh8bFAHQ8yQOc2SnHnTPawAoFI26', NULL, 10, 1, 1, NULL, NULL, '1980-06-07 21:32:04', '2003-03-24 06:54:48', 1, 0),
(28, 'Mertie', 'cartwright.edgardo@gmail.com', NULL, 0, NULL, 'IkOHhl0ax1oQ4iwm5E6vtlIeABRu6g8J', '$2y$13$3NLKLOzPc3r7wNFkOyF2suoNQxgchXMU/r7ZuSRX7kAO4m30jnLZ6', NULL, 10, 1, 1, NULL, NULL, '2015-08-14 21:33:07', '1983-07-13 11:00:28', 1, 0),
(29, 'Emelie', 'brayan.zieme@hotmail.com', NULL, 0, NULL, 'hSuD4t_uVs2jGE-bEPltk6NlgLfB2BSf', '$2y$13$NTbwulqBMgwHzx86hw1GX.yQU2LUFnUWtMnP8WmV5cgle1sv9lcgi', NULL, 10, 1, 1, NULL, NULL, '2015-10-21 04:13:37', '1985-11-26 20:52:58', 1, 0),
(30, 'Osvaldo', 'lindgren.ahmad@hotmail.com', NULL, 0, NULL, 'xSKfJV9MWzw6JIzVcVw_mK3--BBqSANZ', '$2y$13$CExt3DoHHG7eEU5sedvHZOxHWaXSvq7GUy1sT9tt53WiCk3KTqIte', NULL, 10, 1, 1, NULL, NULL, '2016-03-04 06:53:40', '1990-05-15 11:03:29', 1, 0),
(31, 'Kristoffer', 'stone.rempel@gmail.com', NULL, 0, NULL, 'gxUC_JKw3UoktGQKmEQxl8tZBMd00wmV', '$2y$13$8/7bGERJrlVySty1s1pOpuWWqoj6891qQxb/bUZOr5blmQ9h8vBHq', NULL, 10, 1, 1, NULL, NULL, '1977-01-24 08:04:47', '1971-04-17 01:37:26', 1, 0),
(32, 'Antonina', 'antonietta66@zboncak.info', NULL, 0, NULL, 'D3jqdMJYxddOg8G_rga8cB4rpOh3xyD1', '$2y$13$OexAVZs8RJF3H3zlmmwSU.B1vabQ4lBQAuTV9kOYEln8WFFxKtxxG', NULL, 10, 1, 1, NULL, NULL, '2003-05-19 16:58:28', '2011-04-04 08:45:30', 1, 0),
(33, 'Nikolas', 'ricky71@gmail.com', NULL, 0, NULL, 'LwoKmgdyGxhYGMNIc5EYbUGd-hw2ShBE', '$2y$13$fXxnZisplm7gH.qOQhOZl.Nu3ms5kAoVdfNg4ka0H5mK3egLAqUTC', NULL, 10, 1, 1, NULL, NULL, '2021-05-16 00:19:21', '2002-11-24 07:24:26', 1, 0),
(34, 'Alberta', 'lou50@gmail.com', NULL, 0, NULL, 'yYre71SvkkETtILBPmGiJQ8Dxp-SAQqZ', '$2y$13$lwFDd6ZhdA7dm0ouZZqGcOKHM52wqg4lVYt5DBqHySGOLgJ4PfMRi', NULL, 10, 1, 1, NULL, NULL, '1976-07-07 04:40:20', '2020-10-20 00:40:53', 1, 0),
(35, 'Malachi', 'green.cristopher@yahoo.com', NULL, 0, NULL, 'ojuYVzv8fkSwG4pKX1pFUfwaQ7j13sA0', '$2y$13$idCIdye57N6Plsweo2toPOuiv98zZIrpQEHenZnHdfmaZ/W0mENXq', NULL, 10, 1, 1, NULL, NULL, '1981-06-25 15:08:20', '2014-08-01 02:13:20', 1, 0),
(36, 'Glenda', 'kerluke.leopoldo@dubuque.net', NULL, 0, NULL, '7iLUA9A3Ixi7-1MTv48hog2LC0LcsTZf', '$2y$13$qGh5Opwit2SnRalYoBJ/NeN1kYFzgmRCucTTGngSrbHw5UTEC8LSe', NULL, 10, 1, 1, NULL, NULL, '1972-10-16 17:06:52', '1973-06-12 16:48:36', 1, 0),
(37, 'Caterina', 'quitzon.geovany@gmail.com', NULL, 0, NULL, 'LpJqWc2eSAHFxkO81TX5wHFCUsRMlHea', '$2y$13$Q/lYrqykCPtmEWLw68VqzOV2jcGqacmfjSmSFFAJxP5138Cp6d.Fe', NULL, 10, 1, 1, NULL, NULL, '1982-09-17 12:17:36', '2012-01-11 00:09:21', 1, 0),
(38, 'Jesus', 'boehm.reta@yahoo.com', NULL, 0, NULL, 'ZbO0ugNXYGrNE0_ryt2GsCffQLQXJ0p2', '$2y$13$Yrkvvyr3qQy82/1bywecBuZ2rPYKbbHho8TjDWFKbokjs1HuoXL/C', NULL, 10, 1, 1, NULL, NULL, '2016-04-17 13:47:23', '2016-09-09 19:29:37', 1, 0),
(39, 'Jayce', 'wunsch.gabriel@yahoo.com', NULL, 0, NULL, 'FmIMnV3bn_yyaY6lRI5ywQBTEKhNemrv', '$2y$13$bYZPnKsyhfOeZsSlEm4jhe4zofYVp/E0Tmp9AlGNBkvjN8ww2KoDq', NULL, 10, 1, 1, NULL, NULL, '2002-04-10 18:50:46', '1978-07-27 20:47:13', 1, 0),
(40, 'Foster', 'elissa.yost@gmail.com', NULL, 0, NULL, 'BfvaJXt2NsHf2015YnzJMCxxzFxffMXj', '$2y$13$EDIp/q9XaA6SRmxBXqmoPuJX6LUzB93uP8r.MvN0nKeJzsXn71xSW', NULL, 10, 1, 1, NULL, NULL, '1999-11-03 17:46:03', '1975-08-15 17:01:59', 1, 0),
(41, 'Marcelino', 'hermann.clarissa@yahoo.com', NULL, 0, NULL, 'w5GtZNe7KfX2SOaB8s259l_ekj6XqATu', '$2y$13$ah7effl6Y20LQAqzSq6PHO91DFBbfdOj4g29V2j7IBXTZS8cbK5Ya', NULL, 10, 1, 1, NULL, NULL, '1990-01-31 04:10:38', '1976-03-04 03:39:39', 1, 0),
(42, 'Coty', 'emmet06@green.org', NULL, 0, NULL, '6VvLM5UsKCieNbXKkqK7WN0mkoULFPfd', '$2y$13$a3f.4XKpTWqnBknyOZxFTOvRblPUG49a7oD9QtOK/NWfdumsGgqUK', NULL, 10, 1, 1, NULL, NULL, '1994-01-21 18:16:49', '2000-06-15 11:35:11', 1, 0),
(43, 'Meaghan', 'wisoky.laurine@reichert.com', NULL, 0, NULL, 'ZJwxQGMslHjUCuY28R232Gri10ppoacJ', '$2y$13$dWDRZnC4QfEL8X3E3PeMEuKSrTcxfgvXky9CDf16MmbUCGp5it0kG', NULL, 10, 1, 1, NULL, NULL, '2016-01-28 00:45:23', '2007-10-16 03:15:05', 1, 0),
(44, 'Antoinette', 'alice85@koelpin.com', NULL, 0, NULL, 'pR-ZWK6DT5iifPV_QfK_U7Eo8oLXojVe', '$2y$13$S2Bn6Dy9KN4tbN.nQ1SeuORawOvEnkvjBQ935FNdGC30XwKLtUDy6', NULL, 10, 1, 1, NULL, NULL, '1987-11-21 22:38:35', '1981-08-08 13:39:59', 1, 0),
(45, 'Ava', 'audrey99@gislason.com', NULL, 0, NULL, 'BrJyaJF7Et9ptDDdVCZjwJa0a3IFUpQw', '$2y$13$BvwHe20pyWe2kYtJmwDdye.ba2p/aA.xARWb0ntjOETUtQ6NYlCuu', NULL, 10, 1, 1, NULL, NULL, '2020-02-13 19:47:19', '1978-03-18 03:37:04', 1, 0),
(46, 'Myra', 'collins.manley@corkery.com', NULL, 0, NULL, '41czHBHkYmE1SRAahfSenhl7z7zFhsHN', '$2y$13$3v.H7ZUUjwuFrZcWZEb2B.exswIoUujhug.irgLlE8ET.z9SBNQXS', NULL, 10, 1, 1, NULL, NULL, '2007-09-16 03:40:29', '1993-12-10 18:50:15', 1, 0),
(47, 'Dulce', 'bergnaum.jayde@buckridge.info', NULL, 0, NULL, '3sKDZWsBUqkcszivuZQtMZOQUEy5EDeY', '$2y$13$FMnjhY246PA6sa0HlV9hrukGNOsyoxB2v/zGfB62tjRlfM60fF4JO', NULL, 10, 1, 1, NULL, NULL, '1997-10-02 05:02:49', '1982-09-30 05:18:02', 1, 0),
(48, 'Alfredo', 'eichmann.haskell@yahoo.com', NULL, 0, NULL, '0EKFLQwmdWxYpqrDwVjeV1z1XlujP45F', '$2y$13$b7nPMDCmJitMljr/KHd/i.mb.uZ259DXkhFgcVdIw7gB/jF5/KHuC', NULL, 10, 1, 1, NULL, NULL, '1979-10-14 07:04:25', '1995-07-08 19:52:26', 1, 0),
(49, 'Layla', 'pritchie@yahoo.com', NULL, 0, NULL, 'J6NCfIv1_UQg1SABDd5IcZquqOpbl08h', '$2y$13$4wgvH8ArKjWHcwpST3u49eqmZ/D37QFIaieSbPwa6ZN19zipCWTkO', NULL, 10, 1, 1, NULL, NULL, '1999-07-02 15:14:46', '1996-06-09 15:17:18', 1, 0),
(50, 'Amani', 'dillon66@davis.net', NULL, 0, NULL, 'SOeKsfPt4b9jComwdmzIxIPNiGv_imx7', '$2y$13$VzpbqvPt/AnIi9yd9Sv2UOnpJQXOVwqXQSkrbSi5i9M8EERxY4VBm', NULL, 10, 1, 1, NULL, NULL, '2017-12-12 15:01:35', '2006-03-05 10:29:28', 1, 0);

-- --------------------------------------------------------

--
-- Table structure for table `agent_assignment`
--

CREATE TABLE `agent_assignment` (
  `assignment_id` int(10) UNSIGNED NOT NULL,
  `restaurant_uuid` char(60) COLLATE utf8_unicode_ci NOT NULL,
  `agent_id` bigint(20) DEFAULT NULL,
  `business_location_id` bigint(20) DEFAULT NULL,
  `assignment_agent_email` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `assignment_created_at` datetime NOT NULL,
  `assignment_updated_at` datetime NOT NULL,
  `role` smallint(6) NOT NULL,
  `email_notification` smallint(6) DEFAULT 0,
  `reminder_email` smallint(6) DEFAULT 0,
  `receive_weekly_stats` smallint(6) DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `agent_assignment`
--

INSERT INTO `agent_assignment` (`assignment_id`, `restaurant_uuid`, `agent_id`, `business_location_id`, `assignment_agent_email`, `assignment_created_at`, `assignment_updated_at`, `role`, `email_notification`, `reminder_email`, `receive_weekly_stats`) VALUES
(1, '1', 1, 1, 'marguerite.stokes@gmail.com', '1987-12-05 08:54:17', '2014-05-11 22:41:54', 1, 1, 1, 1),
(2, '2', 2, 2, 'doug45@bartoletti.biz', '1982-04-02 04:51:32', '1998-07-22 07:54:59', 1, 1, 1, 1),
(3, '3', 3, 3, 'miller.ethel@yahoo.com', '1979-12-20 07:28:37', '1990-12-19 11:10:09', 1, 1, 1, 1),
(4, '4', 4, 4, 'uswift@stracke.com', '1974-05-10 07:36:59', '2005-02-06 17:49:46', 1, 1, 1, 1),
(5, '5', 5, 5, 'erdman.kara@yahoo.com', '1973-07-08 02:38:29', '2018-05-16 05:11:10', 1, 1, 1, 1),
(6, '6', 6, 6, 'kailey.fisher@morissette.com', '2008-06-21 06:47:21', '1975-05-10 07:20:56', 1, 1, 1, 1),
(7, '7', 7, 7, 'ullrich.kiera@rice.com', '2013-03-13 17:00:05', '1979-03-18 23:22:24', 1, 1, 1, 1),
(8, '8', 8, 8, 'haag.ara@jenkins.biz', '1979-08-28 05:48:38', '1982-11-12 22:36:52', 1, 1, 1, 1),
(9, '9', 9, 9, 'morgan.hessel@hotmail.com', '2004-05-05 22:45:59', '1985-06-12 05:48:05', 1, 1, 1, 1),
(10, '10', 10, 10, 'schoen.elinore@hoppe.com', '1978-10-08 17:00:56', '1988-12-22 11:53:23', 1, 1, 1, 1),
(11, '11', 11, 11, 'kailey35@rosenbaum.biz', '2007-04-19 05:17:22', '2010-03-27 21:11:14', 1, 1, 1, 1),
(12, '12', 12, 12, 'swaniawski.katelin@gmail.com', '1970-01-13 17:04:05', '1976-01-25 20:45:58', 1, 1, 1, 1),
(13, '13', 13, 13, 'roxane.kassulke@hotmail.com', '1988-03-29 19:19:32', '2011-03-26 13:52:11', 1, 1, 1, 1),
(14, '14', 14, 14, 'ijakubowski@yahoo.com', '1995-05-30 04:53:38', '1976-10-25 15:18:11', 1, 1, 1, 1),
(15, '15', 15, 15, 'brendon71@feil.com', '2011-03-29 02:30:01', '1979-05-06 02:52:14', 1, 1, 1, 1),
(16, '16', 16, 16, 'jasen43@hahn.com', '2013-09-02 00:27:43', '2017-07-09 11:37:08', 1, 1, 1, 1),
(17, '17', 17, 17, 'thiel.trudie@zboncak.com', '1970-06-29 11:27:25', '1979-08-03 09:06:32', 1, 1, 1, 1),
(18, '18', 18, 18, 'colby18@mcclure.info', '2017-05-02 14:35:43', '1971-08-22 16:27:10', 1, 1, 1, 1),
(19, '19', 19, 19, 'sebastian98@ullrich.com', '2001-12-22 09:18:03', '2003-07-29 15:46:39', 1, 1, 1, 1),
(20, '20', 20, 20, 'chaz.balistreri@yahoo.com', '2009-05-26 13:56:57', '1973-01-14 06:44:49', 1, 1, 1, 1),
(21, '21', 21, 21, 'nicholas86@brown.com', '1995-01-02 21:05:40', '1985-07-13 06:24:15', 1, 1, 1, 1),
(22, '22', 22, 22, 'rowland37@feest.com', '1978-05-11 07:40:37', '2004-03-30 01:38:41', 1, 1, 1, 1),
(23, '23', 23, 23, 'xschiller@gmail.com', '2021-03-01 00:59:37', '2011-02-25 15:17:46', 1, 1, 1, 1),
(24, '24', 24, 24, 'jermey.adams@hoeger.com', '1985-06-05 13:02:53', '2017-10-09 20:37:37', 1, 1, 1, 1),
(25, '25', 25, 25, 'gusikowski.elza@wuckert.com', '2020-02-08 07:58:39', '2000-09-25 07:32:22', 1, 1, 1, 1),
(26, '26', 26, 26, 'kassandra40@hotmail.com', '1973-03-04 03:16:19', '1980-05-08 10:53:46', 1, 1, 1, 1),
(27, '27', 27, 27, 'runte.jacklyn@hotmail.com', '1999-07-27 02:07:41', '2012-08-26 08:43:05', 1, 1, 1, 1),
(28, '28', 28, 28, 'kovacek.genoveva@hotmail.com', '2014-04-10 00:18:13', '1970-02-06 09:44:15', 1, 1, 1, 1),
(29, '29', 29, 29, 'botsford.lambert@gmail.com', '1974-03-29 00:20:34', '1983-02-13 03:09:23', 1, 1, 1, 1),
(30, '30', 30, 30, 'aufderhar.brenna@lubowitz.com', '2013-12-07 20:37:58', '2010-11-26 08:35:49', 1, 1, 1, 1),
(31, '31', 31, 31, 'zbeatty@yahoo.com', '2018-12-20 12:00:54', '1980-01-22 04:48:32', 1, 1, 1, 1),
(32, '32', 32, 32, 'carol.durgan@mckenzie.net', '2020-08-14 04:16:12', '2019-11-03 23:06:36', 1, 1, 1, 1),
(33, '33', 33, 33, 'hraynor@hotmail.com', '1997-08-17 23:55:36', '2014-07-02 20:34:48', 1, 1, 1, 1),
(34, '34', 34, 34, 'fbrown@grimes.org', '1978-06-17 07:56:03', '1974-12-21 07:43:17', 1, 1, 1, 1),
(35, '35', 35, 35, 'winona.keeling@gleason.com', '1994-01-25 08:16:53', '1993-05-27 23:22:16', 1, 1, 1, 1),
(36, '36', 36, 36, 'lakin.elta@lehner.com', '1983-08-03 09:18:53', '2001-03-30 11:47:43', 1, 1, 1, 1),
(37, '37', 37, 37, 'harber.amie@runte.biz', '1992-05-28 17:25:19', '2013-12-31 17:08:14', 1, 1, 1, 1),
(38, '38', 38, 38, 'kaela83@witting.com', '1972-05-15 02:17:37', '1978-09-21 22:51:14', 1, 1, 1, 1),
(39, '39', 39, 39, 'jennifer80@gmail.com', '2011-04-12 20:27:33', '1983-07-29 23:09:16', 1, 1, 1, 1),
(40, '40', 40, 40, 'haley.emmalee@grimes.com', '1990-12-13 03:13:23', '1972-11-26 15:53:36', 1, 1, 1, 1),
(41, '41', 41, 41, 'meghan.farrell@yahoo.com', '1988-08-27 09:46:02', '1982-12-20 03:23:22', 1, 1, 1, 1),
(42, '42', 42, 42, 'tromp.demarco@hotmail.com', '2003-11-22 03:14:21', '2015-05-02 14:49:50', 1, 1, 1, 1),
(43, '43', 43, 43, 'valerie56@yahoo.com', '1984-01-31 10:50:14', '1999-06-11 08:14:36', 1, 1, 1, 1),
(44, '44', 44, 44, 'lexi.johns@hotmail.com', '2020-12-27 03:42:46', '2006-11-30 08:36:08', 1, 1, 1, 1),
(45, '45', 45, 45, 'gia51@will.biz', '2006-05-16 04:52:56', '2006-04-25 02:51:06', 1, 1, 1, 1),
(46, '46', 46, 46, 'jaeden57@hotmail.com', '1996-10-01 01:14:56', '1982-06-20 23:30:55', 1, 1, 1, 1),
(47, '47', 47, 47, 'rhoeger@yahoo.com', '1997-09-27 02:36:42', '1977-02-21 13:11:53', 1, 1, 1, 1),
(48, '48', 48, 48, 'nharber@schaden.com', '1973-11-10 06:18:14', '2011-10-04 19:35:49', 1, 1, 1, 1),
(49, '49', 49, 49, 'roy95@gmail.com', '2012-04-19 00:56:34', '1989-08-20 02:21:34', 1, 1, 1, 1),
(50, '50', 50, 50, 'kunde.maude@gmail.com', '1979-04-22 16:17:40', '2019-10-05 03:25:21', 1, 1, 1, 1);

-- --------------------------------------------------------

--
-- Table structure for table `agent_email_verify_attempt`
--

CREATE TABLE `agent_email_verify_attempt` (
  `aeva_uuid` char(60) COLLATE utf8mb4_unicode_ci NOT NULL,
  `agent_email` varchar(50) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `code` varchar(32) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `ip_address` varchar(45) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `agent_token`
--

CREATE TABLE `agent_token` (
  `token_uuid` char(60) COLLATE utf8mb4_unicode_ci NOT NULL,
  `agent_id` bigint(20) NOT NULL,
  `token_value` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `token_device` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `token_device_id` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `token_status` smallint(6) DEFAULT NULL,
  `token_last_used_datetime` datetime DEFAULT NULL,
  `token_expiry_datetime` datetime DEFAULT NULL,
  `token_created_datetime` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `agent_token`
--

INSERT INTO `agent_token` (`token_uuid`, `agent_id`, `token_value`, `token_device`, `token_device_id`, `token_status`, `token_last_used_datetime`, `token_expiry_datetime`, `token_created_datetime`) VALUES
('1', 1, 'crk6MUA2bzOkBS2-b8dkXuqCYrZmVyhv', NULL, NULL, 10, '1972-04-21 10:13:47', '1986-11-04 12:56:11', '2006-12-15 19:44:46'),
('10', 10, 'mDCUW_1yVq2ZhoUlLo7d5iHKOEg1y1uu', NULL, NULL, 10, '1987-08-13 18:45:50', '1995-08-08 07:23:52', '2003-06-09 00:47:22'),
('11', 11, 'LFjaiwwbDFRT6rEc_W2dJfDO3JoefOur', NULL, NULL, 10, '1980-01-22 07:50:13', '2002-03-18 18:20:00', '2014-08-31 13:01:09'),
('12', 12, 'vao_VEHBe7oBaj6Wv7UyeiamoQNDQSja', NULL, NULL, 10, '1984-08-16 20:09:31', '1987-01-21 19:50:20', '2017-04-06 20:54:45'),
('13', 13, 'W9_B8uK6lriWI1vkAZ4MQzBKEcfJDfjL', NULL, NULL, 10, '2004-04-10 10:59:59', '1974-06-04 00:24:40', '2008-01-11 06:56:09'),
('14', 14, '-M0rTCyHYSViLWAlEgZGHHswnBT-yTdB', NULL, NULL, 10, '1994-09-18 15:59:43', '2006-06-09 12:56:16', '2005-11-16 23:29:46'),
('15', 15, 'IkXBSogcS76NHNJCncQKBXO5ZGUFmyv7', NULL, NULL, 10, '2020-06-25 08:26:02', '2011-12-31 21:04:47', '2017-07-22 02:34:35'),
('16', 16, '7DzoJtIjWbXfwP-_hu2NuOJzDNX0GqLp', NULL, NULL, 10, '1991-04-06 08:45:22', '1984-02-27 13:56:57', '2000-12-08 23:36:06'),
('17', 17, 'UTrXYQJ7iOHYOOBmopwRRZ-bqMcUftj3', NULL, NULL, 10, '2006-09-03 00:45:04', '2013-07-21 08:51:26', '2020-02-09 11:23:00'),
('18', 18, 't72FGr3-_aACy4Q-Cl6r7YcjRrf3o985', NULL, NULL, 10, '2015-02-08 15:05:46', '2018-02-19 12:07:45', '2019-07-31 16:47:43'),
('19', 19, 'Fq3lKEv_tQDrV9YiiGN_rVb_3BHD4RfK', NULL, NULL, 10, '1976-12-02 09:37:10', '2013-09-19 20:41:25', '1973-08-03 04:42:38'),
('2', 2, 'EaqOLnWAkQdPUELp4lLM51n9hiifGdha', NULL, NULL, 10, '1989-08-29 19:49:38', '1994-08-19 20:44:48', '1985-08-26 08:59:07'),
('20', 20, 'SQuDKsKO8dogw4p_EZeMkGGaeWiCCuQl', NULL, NULL, 10, '1976-12-22 17:09:02', '1976-02-06 19:36:36', '1986-05-24 21:34:39'),
('21', 21, 'rbnbULZZsjVFqh3d0IF93uXCLmEelsbr', NULL, NULL, 10, '1979-01-07 05:09:54', '2015-10-07 14:59:13', '1987-01-22 04:54:21'),
('22', 22, 'MZzjVtblYI7Vu04hikDWhcqEJcDCH1LN', NULL, NULL, 10, '1981-08-05 06:56:50', '1986-09-15 09:52:52', '2005-06-16 10:17:59'),
('23', 23, 'GnAWjf-yaiRNaCRUJd-jOLdJlfhD9MmL', NULL, NULL, 10, '2006-07-28 23:03:22', '1995-08-28 03:25:09', '2006-02-16 14:17:58'),
('24', 24, '5VrwwyQVdR0IzO19uYGLnDj8h6HEcbec', NULL, NULL, 10, '2006-04-27 13:54:30', '1994-02-18 09:11:43', '1984-06-02 22:33:11'),
('25', 25, 'nbnc74IX85UgzQ53Zbkf9Cnd5mTY6N9W', NULL, NULL, 10, '2017-12-29 07:00:17', '1977-08-30 10:55:14', '2008-11-14 11:56:14'),
('26', 26, '_25bIuP-Yy-YIyCbQQCcUG3h_QeUpIP2', NULL, NULL, 10, '2019-04-12 03:31:46', '1980-06-12 22:56:12', '2017-01-04 07:13:47'),
('27', 27, 'efLvINqSMe05ab-vF8GdybSLjp1543eO', NULL, NULL, 10, '2004-04-22 16:12:37', '2009-03-29 06:01:17', '2004-10-30 19:00:14'),
('28', 28, 'EOpQUnK5GqpR59C6KC-gdl1pmWA7FHYB', NULL, NULL, 10, '1981-10-17 21:05:01', '2007-07-25 12:24:17', '1986-08-27 11:58:45'),
('29', 29, 'KlUFmrLkprD7jzjy6-DbEx_TAEh1R8VN', NULL, NULL, 10, '2004-01-31 00:38:02', '1988-07-04 13:08:36', '1974-04-08 18:30:05'),
('3', 3, '5wN2z5uegpAV1mqn0n2iBfQDloDQ-ib9', NULL, NULL, 10, '2011-01-06 03:06:58', '2020-12-12 05:17:13', '1971-11-25 14:37:40'),
('30', 30, 'vbDO8O3zeN8ZwD218JnAufUa7I5S0ESf', NULL, NULL, 10, '1986-07-07 00:11:21', '2004-11-15 20:26:29', '1988-10-15 06:47:56'),
('31', 31, 'k4QfyV6H1o-QABvq1uM2u4bREWXjbJ0O', NULL, NULL, 10, '2014-03-21 13:55:44', '2012-03-02 13:16:10', '2008-09-17 07:51:37'),
('32', 32, 'klJd5PMtuYi_yTdo6vtOK66RenFEd3Fg', NULL, NULL, 10, '2000-10-17 02:55:46', '2010-05-28 03:21:21', '1988-05-07 12:43:26'),
('33', 33, 'dySXonqE5GcETODpPTxaanbmCdSED35P', NULL, NULL, 10, '1996-01-20 20:09:15', '2017-07-23 18:42:47', '1986-12-17 19:56:25'),
('34', 34, 'EasJFQCpJHBixAA9yOipQKdmCBFg7oct', NULL, NULL, 10, '2004-05-22 15:32:11', '1978-02-07 17:18:44', '1986-08-10 01:26:33'),
('35', 35, 'OeKUWAIf6K6X-rx3mYwSR5CL5n2WvZIr', NULL, NULL, 10, '1985-05-12 08:48:23', '1994-06-18 00:12:47', '2017-11-26 13:38:55'),
('36', 36, 'pqQ5gTMty9UbiEWJlmaqhAI3z9Fvx9N1', NULL, NULL, 10, '1970-10-12 16:54:36', '2018-07-12 05:34:37', '2001-08-07 18:46:16'),
('37', 37, 'ymWPXbsXQmoBL0Ess8-e6G4LmstAdsPW', NULL, NULL, 10, '2020-08-26 13:29:02', '1972-01-24 00:16:45', '1996-07-03 05:31:13'),
('38', 38, 'h8rzNwl6YnPSyq2oCyjNtElok3d1QV9y', NULL, NULL, 10, '2016-08-02 11:19:49', '2015-09-23 04:01:20', '1997-02-15 21:14:16'),
('39', 39, 'QCFInbXLdWt_S3vP62MgM-HRa9gxkeq_', NULL, NULL, 10, '2009-03-17 17:46:03', '2018-02-09 14:30:59', '1972-07-12 16:08:08'),
('4', 4, 'A_45ZThOyc4UyZDFy_sEvp2eQWF76HiG', NULL, NULL, 10, '2010-09-30 18:07:16', '1973-05-04 13:27:44', '1997-06-03 02:11:45'),
('40', 40, 'n_XnWUXY18wYRUXhr1vleUu9BkI1UrFY', NULL, NULL, 10, '2001-12-01 14:50:09', '2020-10-29 22:37:12', '1986-04-02 18:47:20'),
('41', 41, 'hHQO-e2QZ_OZTdfzsMetWQSSLUJQ9Btj', NULL, NULL, 10, '1987-06-22 16:59:40', '2000-10-09 02:32:54', '2011-06-20 17:00:04'),
('42', 42, 'PXp2hicRaNJxNJ7d0lzms5sFGAjsgGLS', NULL, NULL, 10, '1975-01-09 09:24:13', '2011-03-25 19:46:52', '2015-08-25 17:20:09'),
('43', 43, 'VfI4aSdjP8Sj0xy8NZhCO-pU8llLSpbF', NULL, NULL, 10, '1988-09-06 10:27:33', '1999-10-21 01:18:46', '1973-02-16 15:54:05'),
('44', 44, '1XG0HWbtJCtDZY5h_Cg_6mmeCxaIashC', NULL, NULL, 10, '2015-07-29 11:28:58', '1993-12-14 19:03:16', '1971-03-29 12:41:05'),
('45', 45, 'ile8MZqzKeu_heDqyn8MqrYiGffIinGP', NULL, NULL, 10, '1986-08-06 23:12:36', '2005-09-18 23:32:33', '2020-02-04 05:26:21'),
('46', 46, 'LJv6TCo8ncTXlxpjPFbxTvtwUKd-14r6', NULL, NULL, 10, '1999-05-12 14:27:06', '1980-12-13 18:23:31', '1987-01-01 14:37:13'),
('47', 47, 'FYEBcNBh4yDkhsJ7TJYfXG8NGWCQx9iz', NULL, NULL, 10, '2010-09-15 17:46:10', '1978-07-06 23:48:13', '2009-10-27 17:31:05'),
('48', 48, 'WYOtel0l_3XQZ9X_6xI3ZnaC46gIVcFl', NULL, NULL, 10, '1977-10-30 02:42:21', '2010-07-23 05:40:36', '2001-01-12 23:29:42'),
('49', 49, 'wDCHkTFBvgtI5VMttdBL7SVtRxnNCl3Y', NULL, NULL, 10, '1996-08-22 14:28:12', '1992-12-20 17:52:42', '2013-10-14 22:01:40'),
('5', 5, 'ipCIY6RVjCH8_NQSa7IcKAlyxVAs3zEZ', NULL, NULL, 10, '2017-05-22 22:56:24', '1977-01-29 01:21:54', '2003-01-12 14:44:20'),
('50', 50, '0ThZyWuejCBoJOtCoPPd6oDfe1eVZ1t_', NULL, NULL, 10, '2014-04-18 07:01:36', '1988-02-24 07:51:19', '1999-08-08 17:41:07'),
('6', 6, 'xMneIINAfihkje8wwDZE6Memyr3dwo2U', NULL, NULL, 10, '2019-10-02 17:43:45', '1982-08-10 05:17:40', '1976-07-12 03:19:04'),
('7', 7, 'gDm5kGapstrLE4bBpSqglLa9cCtJa60R', NULL, NULL, 10, '2001-11-24 13:22:20', '2020-04-02 22:24:47', '2000-02-02 04:03:00'),
('8', 8, 'qxNH2PVqwrxhYjoZ2eYdT16F4NBmFCzm', NULL, NULL, 10, '1971-11-29 03:34:15', '1996-08-06 23:55:50', '2006-08-23 13:03:27'),
('9', 9, 'YKWU5hyKyc0MlRCYWyNj9DncfREWiSJF', NULL, NULL, 10, '1983-12-14 17:32:29', '2016-08-27 16:50:09', '1984-03-11 22:03:47');

-- --------------------------------------------------------

--
-- Table structure for table `area`
--

CREATE TABLE `area` (
  `area_id` int(11) NOT NULL,
  `city_id` int(11) NOT NULL,
  `area_name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `area_name_ar` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `latitude` decimal(18,8) DEFAULT NULL,
  `longitude` decimal(18,8) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `area`
--

INSERT INTO `area` (`area_id`, `city_id`, `area_name`, `area_name_ar`, `latitude`, `longitude`) VALUES
(1, 1, 'quo', 'corporis', '-79.20959900', '-125.50662600'),
(2, 2, 'incidunt', 'dolorum', '65.98590300', '-90.45944100'),
(3, 3, 'enim', 'libero', '-79.72686000', '147.26632100'),
(4, 4, 'quasi', 'molestiae', '86.85273700', '37.75857600'),
(5, 5, 'ab', 'qui', '-69.52646200', '112.91228100'),
(6, 6, 'nihil', 'quisquam', '-67.23058000', '53.62936600'),
(7, 7, 'quo', 'consequatur', '-89.32354600', '-167.62068000'),
(8, 8, 'pariatur', 'omnis', '66.43774100', '105.69738200'),
(9, 9, 'eum', 'eligendi', '52.25267100', '126.95443800'),
(10, 10, 'cumque', 'corrupti', '-80.18021700', '-48.47295400'),
(11, 11, 'et', 'quia', '72.66419700', '88.03196600'),
(12, 12, 'consequatur', 'facere', '-32.73472600', '-101.35234300'),
(13, 13, 'delectus', 'est', '60.64710600', '-115.28250100'),
(14, 14, 'perspiciatis', 'hic', '-85.55455700', '161.50615800'),
(15, 15, 'voluptate', 'magni', '64.87284100', '-105.19230500'),
(16, 16, 'non', 'recusandae', '-40.33868400', '137.82455700'),
(17, 17, 'vitae', 'asperiores', '54.69465900', '54.60184700'),
(18, 18, 'perferendis', 'minus', '-9.82118600', '-60.25320700'),
(19, 19, 'odio', 'asperiores', '-77.95620700', '-43.70267600'),
(20, 20, 'molestias', 'omnis', '-8.92085600', '49.71451700'),
(21, 21, 'eos', 'est', '54.63713100', '-60.20833400'),
(22, 22, 'et', 'nulla', '-62.49208500', '-1.46743400'),
(23, 23, 'excepturi', 'exercitationem', '-40.97942800', '179.34146400'),
(24, 24, 'sint', 'a', '-1.53801700', '-177.45858100'),
(25, 25, 'repellendus', 'nam', '59.59108000', '17.72977100'),
(26, 26, 'cum', 'est', '6.55182700', '-88.08588100'),
(27, 27, 'assumenda', 'et', '-47.54337100', '87.10155800'),
(28, 28, 'ad', 'et', '-44.81887100', '-31.99391200'),
(29, 29, 'numquam', 'dolorem', '-20.42549600', '176.72839800'),
(30, 30, 'nesciunt', 'ducimus', '28.81160100', '66.32076200'),
(31, 31, 'soluta', 'perspiciatis', '-40.16527100', '-113.48912300'),
(32, 32, 'placeat', 'nulla', '-10.43154900', '115.06248800'),
(33, 33, 'dolores', 'est', '-0.09436400', '-70.65059400'),
(34, 34, 'quae', 'sint', '80.07684400', '-76.86822800'),
(35, 35, 'iure', 'quaerat', '-31.67007600', '7.46551200'),
(36, 36, 'ullam', 'necessitatibus', '53.93988200', '101.47262300'),
(37, 37, 'tempora', 'quibusdam', '71.75318300', '-123.47407300'),
(38, 38, 'aperiam', 'facere', '-57.81710500', '104.15739500'),
(39, 39, 'nesciunt', 'quia', '-73.41622800', '0.69487400'),
(40, 40, 'provident', 'quas', '-76.19960600', '-108.03011100'),
(41, 41, 'possimus', 'aut', '-58.33887800', '120.34498800'),
(42, 42, 'maxime', 'eveniet', '58.51669200', '9.86975300'),
(43, 43, 'omnis', 'quia', '-47.17663600', '-153.54790400'),
(44, 44, 'veniam', 'magni', '-59.20308700', '-10.26276500'),
(45, 45, 'ipsa', 'dolor', '-70.65196700', '39.19933600'),
(46, 46, 'quas', 'est', '5.73922200', '62.19668700'),
(47, 47, 'beatae', 'voluptates', '-58.89530100', '102.75976300'),
(48, 48, 'voluptatem', 'voluptas', '19.48056400', '-151.54347500'),
(49, 49, 'nisi', 'aliquid', '-30.82751900', '54.98343900'),
(50, 50, 'dolor', 'doloribus', '37.37429400', '160.58268800');

-- --------------------------------------------------------

--
-- Table structure for table `area_delivery_zone`
--

CREATE TABLE `area_delivery_zone` (
  `area_delivery_zone` bigint(20) NOT NULL,
  `restaurant_uuid` char(60) COLLATE utf8_unicode_ci NOT NULL,
  `delivery_zone_id` bigint(20) NOT NULL,
  `country_id` int(11) DEFAULT NULL,
  `city_id` int(11) DEFAULT NULL,
  `area_id` int(11) DEFAULT NULL,
  `is_deleted` tinyint(1) DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `area_delivery_zone`
--

INSERT INTO `area_delivery_zone` (`area_delivery_zone`, `restaurant_uuid`, `delivery_zone_id`, `country_id`, `city_id`, `area_id`, `is_deleted`) VALUES
(1, '1', 1, 1, 1, 1, 0),
(2, '2', 2, 2, 2, 2, 0),
(3, '3', 3, 3, 3, 3, 0),
(4, '4', 4, 4, 4, 4, 0),
(5, '5', 5, 5, 5, 5, 0),
(6, '6', 6, 6, 6, 6, 0),
(7, '7', 7, 7, 7, 7, 0),
(8, '8', 8, 8, 8, 8, 0),
(9, '9', 9, 9, 9, 9, 0),
(10, '10', 10, 10, 10, 10, 0),
(11, '11', 11, 11, 11, 11, 0),
(12, '12', 12, 12, 12, 12, 0),
(13, '13', 13, 13, 13, 13, 0),
(14, '14', 14, 14, 14, 14, 0),
(15, '15', 15, 15, 15, 15, 0),
(16, '16', 16, 16, 16, 16, 0),
(17, '17', 17, 17, 17, 17, 0),
(18, '18', 18, 18, 18, 18, 0),
(19, '19', 19, 19, 19, 19, 0),
(20, '20', 20, 20, 20, 20, 0),
(21, '21', 21, 21, 21, 21, 0),
(22, '22', 22, 22, 22, 22, 0),
(23, '23', 23, 23, 23, 23, 0),
(24, '24', 24, 24, 24, 24, 0),
(25, '25', 25, 25, 25, 25, 0),
(26, '26', 26, 26, 26, 26, 0),
(27, '27', 27, 27, 27, 27, 0),
(28, '28', 28, 28, 28, 28, 0),
(29, '29', 29, 29, 29, 29, 0),
(30, '30', 30, 30, 30, 30, 0),
(31, '31', 31, 31, 31, 31, 0),
(32, '32', 32, 32, 32, 32, 0),
(33, '33', 33, 33, 33, 33, 0),
(34, '34', 34, 34, 34, 34, 0),
(35, '35', 35, 35, 35, 35, 0),
(36, '36', 36, 36, 36, 36, 0),
(37, '37', 37, 37, 37, 37, 0),
(38, '38', 38, 38, 38, 38, 0),
(39, '39', 39, 39, 39, 39, 0),
(40, '40', 40, 40, 40, 40, 0),
(41, '41', 41, 41, 41, 41, 0),
(42, '42', 42, 42, 42, 42, 0),
(43, '43', 43, 43, 43, 43, 0),
(44, '44', 44, 44, 44, 44, 0),
(45, '45', 45, 45, 45, 45, 0),
(46, '46', 46, 46, 46, 46, 0),
(47, '47', 47, 47, 47, 47, 0),
(48, '48', 48, 48, 48, 48, 0),
(49, '49', 49, 49, 49, 49, 0),
(50, '50', 50, 50, 50, 50, 0);

-- --------------------------------------------------------

--
-- Table structure for table `attachment`
--

CREATE TABLE `attachment` (
  `attachment_uuid` char(60) COLLATE utf8_unicode_ci NOT NULL,
  `file_path` varchar(250) COLLATE utf8_unicode_ci DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `bank`
--

CREATE TABLE `bank` (
  `bank_id` int(11) NOT NULL,
  `bank_name` varchar(100) COLLATE utf8_unicode_ci DEFAULT NULL,
  `bank_iban_code` varchar(64) COLLATE utf8_unicode_ci NOT NULL,
  `bank_swift_code` varchar(64) COLLATE utf8_unicode_ci NOT NULL,
  `bank_address` varchar(100) COLLATE utf8_unicode_ci NOT NULL,
  `bank_transfer_type` varchar(100) COLLATE utf8_unicode_ci NOT NULL,
  `bank_created_at` datetime NOT NULL,
  `bank_updated_at` datetime NOT NULL,
  `deleted` smallint(1) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `bank`
--

INSERT INTO `bank` (`bank_id`, `bank_name`, `bank_iban_code`, `bank_swift_code`, `bank_address`, `bank_transfer_type`, `bank_created_at`, `bank_updated_at`, `deleted`) VALUES
(1, 'consequatur', 'occaecati', 'et', '67051 Kenna Camp Apt. 264\r\nPort Madyson, NJ 74497-8851', 'NEFT', '1995-08-06 14:59:06', '2012-05-01 23:55:22', 0),
(2, 'rerum', 'voluptatem', 'ipsam', '21585 Kemmer Ramp\r\nWalkerburgh, OK 01562', 'NEFT', '1980-10-06 18:00:04', '1975-07-10 17:46:54', 0),
(3, 'at', 'inventore', 'aut', '22409 Elinore Drives\r\nSouth Verniemouth, SC 23895', 'NEFT', '1998-06-06 15:45:15', '1993-10-12 10:55:49', 0),
(4, 'ullam', 'aliquam', 'quasi', '902 Wilford Prairie Suite 566\r\nNorth Zelda, ND 79932-3019', 'NEFT', '1999-11-11 00:56:23', '2008-02-02 18:21:54', 0),
(5, 'nulla', 'voluptates', 'nobis', '2284 Eleanore Mall Apt. 525\r\nGleichnerberg, WA 41816-1142', 'NEFT', '1981-02-03 05:37:32', '2017-03-20 14:43:46', 0),
(6, 'nihil', 'possimus', 'minus', '712 Renee Island\r\nKrajcikfurt, NJ 16682-0936', 'NEFT', '1976-07-10 14:50:42', '1977-06-12 19:22:56', 0),
(7, 'quasi', 'sed', 'veniam', '65709 Antone Rue\r\nKenyaland, TX 39600', 'NEFT', '1971-05-27 08:43:39', '2001-12-20 15:12:10', 0),
(8, 'recusandae', 'alias', 'dicta', '79636 Kaleigh Lock\r\nPort Destineychester, CA 01290', 'NEFT', '1999-05-28 03:49:44', '1987-04-20 22:36:55', 0),
(9, 'dignissimos', 'autem', 'dolores', '67192 Enola Square Suite 348\r\nGulgowskifort, AZ 97317', 'NEFT', '1983-07-01 23:23:27', '2004-04-17 03:56:13', 0),
(10, 'perspiciatis', 'quia', 'dolorum', '6915 Conor Parkway\r\nCecilechester, MI 57398', 'NEFT', '1975-04-02 06:24:25', '1985-08-03 07:52:27', 0),
(11, 'itaque', 'dolorem', 'rerum', '352 Krystel Shores Suite 816\r\nLuciennemouth, IA 70674-2546', 'NEFT', '2002-10-07 19:38:50', '1998-01-14 00:42:27', 0),
(12, 'aperiam', 'sint', 'ab', '392 Kelsi Rapid\r\nSouth Braulio, SD 70828', 'NEFT', '1987-11-11 14:02:12', '2001-11-20 21:39:45', 0),
(13, 'ut', 'soluta', 'consequuntur', '102 Purdy Shoals\r\nBergehaven, IL 00315-5766', 'NEFT', '1972-07-10 21:21:02', '2008-10-03 11:24:01', 0),
(14, 'quasi', 'voluptas', 'distinctio', '98456 Buckridge Fort\r\nRatkehaven, OR 83687-5450', 'NEFT', '1976-12-31 19:34:54', '2006-06-22 14:32:07', 0),
(15, 'et', 'quod', 'atque', '35462 Koepp Dale Suite 095\r\nSouth Adahview, WA 27621', 'NEFT', '2006-01-01 12:07:05', '2018-05-02 12:09:46', 0),
(16, 'provident', 'qui', 'atque', '39291 Jadon Road\r\nSmithamport, TN 79752', 'NEFT', '2002-04-13 19:01:41', '2016-09-26 21:57:34', 0),
(17, 'quia', 'natus', 'sequi', '96446 Paul Inlet\r\nSouth Maggieview, MS 39249', 'NEFT', '2018-07-02 15:33:33', '2012-05-28 20:30:05', 0),
(18, 'voluptas', 'eveniet', 'voluptas', '206 Stark Motorway\r\nHalvorsonhaven, OK 54017', 'NEFT', '1985-10-19 10:49:08', '2003-12-16 10:50:02', 0),
(19, 'minima', 'atque', 'ut', '2629 Heidenreich Track\r\nNew Josue, ME 42963-2193', 'NEFT', '1997-08-25 16:41:59', '1988-12-19 13:58:04', 0),
(20, 'corrupti', 'excepturi', 'qui', '5626 Tony Course\r\nPort Lawrenceberg, WY 93180-8751', 'NEFT', '2015-05-07 11:10:54', '2017-12-27 08:42:40', 0),
(21, 'distinctio', 'quis', 'earum', '17979 Monty Shoals Apt. 565\r\nEast Julius, CA 80461', 'NEFT', '1993-10-28 13:58:08', '2019-09-27 23:25:34', 0),
(22, 'omnis', 'numquam', 'illum', '672 Novella Centers\r\nNorth Walker, OK 97737', 'NEFT', '1982-03-05 05:43:45', '2006-02-27 04:56:36', 0),
(23, 'consequatur', 'in', 'at', '873 Abigail Ways Apt. 990\r\nPort Marilie, NE 24666', 'NEFT', '1975-08-11 09:55:00', '2014-07-01 02:34:54', 0),
(24, 'porro', 'incidunt', 'at', '591 Kuhn Path\r\nRueckerchester, WV 25290-7177', 'NEFT', '1982-02-04 00:34:00', '1988-03-13 13:41:47', 0),
(25, 'nihil', 'nihil', 'nihil', '92400 Aron Pines\r\nSchambergermouth, NY 87669', 'NEFT', '2011-06-27 04:40:53', '1995-04-20 17:58:52', 0),
(26, 'aliquam', 'ut', 'accusamus', '624 Jast Rapid Suite 658\r\nWest Amani, NC 29945-3162', 'NEFT', '1970-03-18 16:17:25', '1978-09-23 14:26:22', 0),
(27, 'et', 'sunt', 'autem', '89361 Lucio Spur Suite 109\r\nEast Shaylee, NJ 04166-7868', 'NEFT', '1993-08-01 02:06:39', '2003-01-25 07:32:16', 0),
(28, 'iste', 'consequatur', 'tempora', '6363 Gilda Lodge\r\nLake Elva, MT 30166', 'NEFT', '1989-10-09 02:47:32', '1982-11-04 02:12:32', 0),
(29, 'rerum', 'rem', 'repellendus', '616 Osinski Cliff\r\nEast Agustina, AR 30698-1464', 'NEFT', '1986-01-22 20:14:59', '1979-12-17 21:30:05', 0),
(30, 'impedit', 'autem', 'ad', '487 Emilio Lock Suite 497\r\nWest Sallie, TN 71837-3940', 'NEFT', '1972-04-22 05:33:00', '1999-01-11 02:33:42', 0),
(31, 'eveniet', 'rerum', 'rerum', '91220 Dedric Valleys Suite 637\r\nEast Karley, VT 24300', 'NEFT', '2014-03-06 16:27:38', '2016-07-24 07:31:41', 0),
(32, 'earum', 'impedit', 'molestiae', '10167 Dandre Tunnel\r\nLake Kelton, AR 06055-6110', 'NEFT', '2016-03-21 03:45:52', '2003-01-12 22:52:50', 0),
(33, 'ad', 'vel', 'voluptas', '6967 Alyce Circle\r\nCummingsstad, CA 31565-4875', 'NEFT', '2006-10-20 22:21:37', '1974-10-04 07:18:49', 0),
(34, 'itaque', 'aut', 'voluptate', '10877 Heidenreich Ramp\r\nEltaview, KY 47206-2480', 'NEFT', '2004-09-10 06:21:43', '1982-05-24 12:19:33', 0),
(35, 'facilis', 'omnis', 'ipsam', '6873 Kelly Rue Apt. 220\r\nLorinebury, MN 34434', 'NEFT', '1991-07-25 17:05:06', '1994-12-13 05:13:34', 0),
(36, 'repudiandae', 'necessitatibus', 'consequatur', '72274 Jesse Forks Suite 043\r\nPort Orvillehaven, NE 38151', 'NEFT', '2003-07-25 00:33:26', '2019-07-16 01:31:05', 0),
(37, 'et', 'consequatur', 'est', '91408 Hessel Street\r\nWest Krystelville, NC 07515-5622', 'NEFT', '2011-09-25 09:21:08', '2014-06-02 07:39:08', 0),
(38, 'ut', 'omnis', 'assumenda', '5074 Wiegand Lane Suite 172\r\nEast Dewitt, KS 50133-9573', 'NEFT', '2012-10-30 11:22:23', '2005-04-22 15:14:25', 0),
(39, 'fugiat', 'voluptate', 'aut', '786 Saige Islands Suite 823\r\nNew Jamirstad, ID 80846', 'NEFT', '1979-04-12 19:35:16', '1982-04-23 09:16:17', 0),
(40, 'magni', 'consequatur', 'itaque', '705 Bergstrom Walks\r\nKatrinamouth, VA 94810', 'NEFT', '1986-05-28 19:25:59', '2014-06-09 07:50:52', 0),
(41, 'sit', 'praesentium', 'ipsum', '642 Powlowski Squares Suite 608\r\nLarsonton, ME 29451-8066', 'NEFT', '1972-06-16 15:41:14', '2018-05-28 22:19:05', 0),
(42, 'consectetur', 'earum', 'alias', '784 Hegmann Underpass Apt. 451\r\nSouth Hallie, TN 90126', 'NEFT', '2000-12-14 02:38:05', '2001-10-30 13:37:10', 0),
(43, 'quam', 'nesciunt', 'omnis', '49273 Bernhard Crossroad Apt. 928\r\nEast Isidro, WA 87445', 'NEFT', '2004-09-18 06:22:16', '2014-02-11 12:04:48', 0),
(44, 'voluptatum', 'asperiores', 'facilis', '36985 Greenfelder Bridge Apt. 113\r\nWest Theron, KY 93195-9081', 'NEFT', '1974-09-26 17:12:42', '1988-10-26 11:18:11', 0),
(45, 'possimus', 'fugit', 'in', '775 Luettgen Extensions Apt. 592\r\nZiemannborough, LA 27183', 'NEFT', '2012-01-09 22:05:33', '2007-04-12 16:46:19', 0),
(46, 'qui', 'ut', 'dolore', '80280 Torey Mills Apt. 273\r\nBernhardtown, AZ 15299-1782', 'NEFT', '2008-12-09 13:14:29', '2018-04-29 08:11:00', 0),
(47, 'dicta', 'autem', 'nesciunt', '8060 Johnson Islands\r\nConroymouth, OH 89527-2007', 'NEFT', '2016-03-22 04:35:26', '1990-07-09 11:14:56', 0),
(48, 'aut', 'illum', 'rerum', '68041 Doug Parkways\r\nLake Dominic, ID 37235-4574', 'NEFT', '1997-02-26 02:20:25', '2006-08-21 09:30:53', 0),
(49, 'iste', 'accusantium', 'et', '700 Rippin Roads Suite 441\r\nEast Leslytown, OH 60943-4595', 'NEFT', '1981-02-22 15:25:12', '1981-02-16 21:03:55', 0),
(50, 'dolores', 'animi', 'officia', '72240 Santa Turnpike\r\nMaryamshire, PA 58487-2879', 'NEFT', '1981-02-26 07:07:16', '2011-10-19 15:34:47', 0);

-- --------------------------------------------------------

--
-- Table structure for table `bank_discount`
--

CREATE TABLE `bank_discount` (
  `bank_discount_id` bigint(20) NOT NULL,
  `bank_id` int(11) DEFAULT NULL,
  `restaurant_uuid` char(60) COLLATE utf8_unicode_ci NOT NULL,
  `discount_type` smallint(6) NOT NULL DEFAULT 0,
  `discount_amount` int(11) NOT NULL,
  `bank_discount_status` smallint(6) DEFAULT 1,
  `valid_from` datetime DEFAULT NULL,
  `valid_until` datetime DEFAULT NULL,
  `max_redemption` int(11) DEFAULT 0,
  `limit_per_customer` int(11) DEFAULT 0,
  `minimum_order_amount` int(11) DEFAULT 0,
  `bank_discount_created_at` datetime DEFAULT NULL,
  `bank_discount_updated_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `bank_discount`
--

INSERT INTO `bank_discount` (`bank_discount_id`, `bank_id`, `restaurant_uuid`, `discount_type`, `discount_amount`, `bank_discount_status`, `valid_from`, `valid_until`, `max_redemption`, `limit_per_customer`, `minimum_order_amount`, `bank_discount_created_at`, `bank_discount_updated_at`) VALUES
(1, 1, '1', 1, 14, 1, '2010-04-28 20:48:56', '2013-08-24 17:02:14', 43, 21, 45, '1983-06-08 03:20:21', '1980-08-22 16:28:01'),
(2, 2, '2', 2, 15, 1, '1972-08-25 13:24:18', '1976-11-10 23:19:08', 97, 49, 83, '2002-03-20 08:52:36', '1997-01-08 17:04:32'),
(3, 3, '3', 1, 14, 1, '2004-11-09 02:48:32', '2016-02-21 21:41:31', 44, 46, 65, '1995-01-15 20:24:52', '1970-03-08 21:20:36'),
(4, 4, '4', 2, 10, 1, '2000-03-18 15:59:01', '2010-09-26 21:04:01', 21, 33, 14, '1987-06-22 09:56:20', '1979-03-10 11:18:34'),
(5, 5, '5', 1, 15, 1, '2019-11-25 07:50:32', '1970-07-29 05:34:14', 19, 62, 75, '2008-02-01 19:09:57', '1984-12-02 19:04:46'),
(6, 6, '6', 2, 12, 1, '1979-05-11 05:30:00', '1993-11-03 09:23:25', 12, 36, 59, '1973-07-19 11:32:46', '1993-02-05 10:17:37'),
(7, 7, '7', 2, 17, 1, '1985-10-18 04:57:30', '1977-02-19 22:53:00', 35, 70, 78, '1996-08-12 04:45:09', '1982-10-16 19:56:33'),
(8, 8, '8', 1, 16, 1, '1970-10-21 06:52:24', '1986-06-06 23:25:36', 59, 57, 66, '1978-10-02 17:11:11', '1988-08-08 04:25:05'),
(9, 9, '9', 1, 13, 1, '1986-06-05 08:28:30', '1976-03-21 08:45:52', 70, 32, 56, '1976-12-17 15:08:26', '1999-02-07 23:31:33'),
(10, 10, '10', 1, 10, 1, '1989-01-01 17:38:56', '1983-09-07 17:47:29', 81, 69, 21, '1996-02-12 03:02:00', '1984-03-04 04:55:27'),
(11, 11, '11', 1, 10, 1, '1987-04-10 07:00:30', '1994-06-09 02:08:22', 64, 14, 58, '1974-12-12 21:24:00', '1987-06-02 18:07:04'),
(12, 12, '12', 2, 20, 1, '1986-04-26 07:15:36', '1992-08-04 09:03:55', 25, 36, 88, '1979-04-05 11:53:19', '2002-06-28 22:57:37'),
(13, 13, '13', 2, 19, 1, '1999-11-10 03:32:15', '2004-04-06 10:38:17', 58, 36, 67, '2007-04-24 17:12:45', '2008-02-06 12:26:59'),
(14, 14, '14', 1, 20, 1, '1970-05-23 08:50:46', '1991-08-15 18:42:03', 41, 90, 62, '1992-09-04 03:52:07', '1972-04-26 09:33:15'),
(15, 15, '15', 2, 19, 1, '1988-04-13 15:56:59', '1989-08-18 11:15:05', 90, 16, 88, '1991-02-16 04:25:07', '1991-05-31 09:11:52'),
(16, 16, '16', 1, 18, 1, '1974-07-06 09:21:54', '2017-09-14 11:00:46', 92, 13, 96, '1988-02-16 22:07:20', '2001-03-30 23:39:21'),
(17, 17, '17', 1, 15, 1, '1970-01-19 21:20:52', '1975-04-21 07:04:12', 92, 84, 98, '2012-09-18 20:27:10', '1972-09-17 22:33:06'),
(18, 18, '18', 1, 10, 1, '2015-06-06 07:44:24', '1999-11-21 08:55:12', 41, 57, 36, '1989-02-07 20:42:29', '2000-09-10 15:37:16'),
(19, 19, '19', 2, 18, 1, '1999-04-23 07:00:26', '2014-11-10 11:06:27', 27, 51, 61, '2011-02-02 06:50:04', '1983-07-11 17:50:50'),
(20, 20, '20', 2, 19, 1, '2001-07-24 10:07:29', '1983-03-08 06:00:03', 74, 42, 92, '2016-02-07 08:28:06', '2007-10-20 19:38:25'),
(21, 21, '21', 2, 19, 1, '1999-08-07 03:03:41', '1989-08-04 18:47:01', 21, 18, 59, '1999-02-27 20:24:58', '2018-12-26 08:28:58'),
(22, 22, '22', 2, 11, 1, '2020-04-27 04:50:01', '2009-01-05 01:55:50', 82, 76, 60, '2019-05-16 01:53:44', '1992-06-08 04:37:25'),
(23, 23, '23', 2, 14, 1, '1997-06-25 00:26:48', '2012-03-15 14:05:54', 14, 41, 47, '1981-09-03 00:13:23', '1980-11-19 07:42:55'),
(24, 24, '24', 1, 18, 1, '2014-03-17 08:26:35', '2005-10-05 00:12:25', 10, 77, 12, '2005-02-01 15:15:48', '1983-11-27 05:00:31'),
(25, 25, '25', 2, 19, 1, '1990-02-16 20:23:18', '1976-07-28 09:38:40', 92, 80, 60, '1976-08-06 17:28:02', '1974-12-31 17:58:00'),
(26, 26, '26', 2, 17, 1, '1996-04-24 18:42:05', '1991-08-09 03:54:34', 27, 94, 24, '1975-01-03 08:39:44', '1998-08-03 07:30:36'),
(27, 27, '27', 1, 15, 1, '2004-04-16 10:54:12', '1974-11-28 02:09:59', 61, 88, 62, '1975-06-20 10:19:34', '2020-01-18 04:32:44'),
(28, 28, '28', 1, 10, 1, '1974-07-17 05:00:50', '2007-09-16 03:24:34', 56, 29, 90, '1972-05-22 12:51:09', '1977-02-28 09:53:09'),
(29, 29, '29', 2, 20, 1, '1988-06-24 05:05:14', '1976-07-03 12:38:18', 70, 47, 23, '2009-04-10 13:31:26', '1994-03-20 07:36:17'),
(30, 30, '30', 1, 12, 1, '1995-12-25 00:29:53', '1984-04-06 00:23:30', 31, 19, 14, '1977-06-11 11:39:25', '1981-07-24 04:20:41'),
(31, 31, '31', 2, 14, 1, '1970-01-09 20:18:00', '2012-11-07 01:50:07', 70, 65, 78, '2018-06-06 07:48:53', '2002-04-19 16:28:08'),
(32, 32, '32', 1, 11, 1, '2017-05-03 04:09:37', '1999-03-13 12:02:55', 61, 28, 98, '2004-11-24 18:52:31', '1997-01-01 09:11:47'),
(33, 33, '33', 1, 20, 1, '1972-10-28 12:24:46', '1999-12-10 11:50:24', 69, 67, 14, '2017-01-06 23:51:30', '1987-11-17 11:55:59'),
(34, 34, '34', 2, 18, 1, '1980-10-17 11:33:59', '1981-01-20 01:19:37', 71, 85, 55, '1993-09-17 13:53:48', '1971-10-03 11:13:42'),
(35, 35, '35', 1, 17, 1, '1981-12-16 06:31:27', '1999-09-10 08:55:41', 32, 67, 66, '2013-01-03 19:14:32', '1986-11-05 14:50:35'),
(36, 36, '36', 1, 14, 1, '1974-07-20 14:20:06', '1998-08-02 16:59:31', 85, 27, 79, '1996-04-18 14:58:39', '2010-04-08 05:50:48'),
(37, 37, '37', 2, 17, 1, '2003-10-16 02:26:19', '1981-03-12 01:08:06', 62, 66, 42, '1988-05-30 23:53:33', '1976-09-15 01:10:57'),
(38, 38, '38', 1, 14, 1, '1992-07-02 03:32:45', '1989-02-07 08:01:52', 55, 83, 11, '2000-08-29 13:34:49', '2018-02-20 21:09:44'),
(39, 39, '39', 1, 10, 1, '1970-03-17 08:18:32', '1972-10-27 23:29:10', 63, 34, 41, '1976-10-17 06:57:30', '1996-03-19 15:46:20'),
(40, 40, '40', 1, 20, 1, '1976-05-31 17:23:39', '1972-10-26 07:07:48', 12, 46, 17, '2019-09-20 05:50:12', '1984-04-20 21:22:55'),
(41, 41, '41', 2, 11, 1, '2008-01-16 07:39:04', '2006-10-03 12:17:24', 40, 64, 75, '1991-10-21 06:32:33', '1987-06-11 12:25:01'),
(42, 42, '42', 2, 18, 1, '2014-06-08 11:11:59', '2004-10-03 06:37:15', 14, 90, 12, '1989-09-11 14:01:27', '2012-03-28 22:45:35'),
(43, 43, '43', 1, 15, 1, '2018-03-04 22:49:56', '1993-02-22 16:33:37', 43, 78, 17, '2014-02-27 19:54:36', '1985-02-26 21:10:41'),
(44, 44, '44', 2, 14, 1, '2005-01-26 17:07:22', '1987-06-11 03:25:40', 25, 62, 58, '2015-08-08 06:33:20', '2001-12-18 16:44:20'),
(45, 45, '45', 2, 10, 1, '2005-02-19 05:34:45', '2018-08-01 16:03:43', 87, 54, 90, '1972-07-15 22:53:18', '2021-05-18 06:37:27'),
(46, 46, '46', 1, 13, 1, '1988-05-27 18:43:39', '1986-04-09 11:07:22', 77, 87, 65, '1971-01-30 20:42:08', '1981-04-26 23:38:16'),
(47, 47, '47', 1, 15, 1, '1971-01-05 04:20:44', '2000-05-01 17:16:46', 41, 74, 53, '1972-04-21 08:11:02', '2002-01-23 03:02:22'),
(48, 48, '48', 1, 13, 1, '1999-04-15 03:33:59', '1977-07-18 13:36:31', 33, 27, 58, '2006-09-01 13:09:39', '2015-02-24 08:04:34'),
(49, 49, '49', 2, 14, 1, '1986-04-18 15:02:13', '1974-01-19 01:44:42', 46, 99, 63, '2011-10-24 08:34:06', '1976-05-05 12:44:03'),
(50, 50, '50', 2, 18, 1, '2018-04-20 01:34:59', '1970-01-21 14:40:02', 26, 20, 83, '1979-08-19 02:25:24', '1970-12-01 01:03:52');

-- --------------------------------------------------------

--
-- Table structure for table `business_location`
--

CREATE TABLE `business_location` (
  `business_location_id` bigint(20) NOT NULL,
  `country_id` int(11) NOT NULL,
  `restaurant_uuid` char(60) COLLATE utf8_unicode_ci NOT NULL,
  `business_location_name` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `business_location_name_ar` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `support_pick_up` tinyint(1) NOT NULL,
  `business_location_tax` float UNSIGNED NOT NULL DEFAULT 0,
  `address` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `latitude` decimal(9,6) DEFAULT NULL,
  `longitude` decimal(9,6) DEFAULT NULL,
  `mashkor_branch_id` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `armada_api_key` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `diggipack_customer_id` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `is_deleted` tinyint(1) DEFAULT 0,
  `max_num_orders` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `business_location`
--

INSERT INTO `business_location` (`business_location_id`, `country_id`, `restaurant_uuid`, `business_location_name`, `business_location_name_ar`, `support_pick_up`, `business_location_tax`, `address`, `latitude`, `longitude`, `mashkor_branch_id`, `armada_api_key`, `diggipack_customer_id`, `is_deleted`, `max_num_orders`) VALUES
(1, 1, '1', 'Wilfredo Mountain', 'Amara Mountain', 1, 16, '27007 Rhianna Summit Apt. 366\r\nRosieside, OK 35010', '47.298858', '54.887862', NULL, NULL, NULL, 0, NULL),
(2, 2, '2', 'Yadira Stream', 'Veum Avenue', 1, 18, '50833 Deshawn Highway\r\nMargarettetown, CA 32674-5632', '9.726508', '2.868737', NULL, NULL, NULL, 0, NULL),
(3, 3, '3', 'Kameron Stravenue', 'Lucio Neck', 0, 16, '46723 Susana Dam Apt. 232\r\nZackeryborough, MN 53761', '-81.028039', '-52.844876', NULL, NULL, NULL, 0, NULL),
(4, 4, '4', 'Gusikowski Turnpike', 'Hand Park', 1, 14, '6530 Howell Glen\r\nEast Rosalee, WV 87018', '41.374967', '-171.674706', NULL, NULL, NULL, 0, NULL),
(5, 5, '5', 'Cristian Bridge', 'Bechtelar Cape', 1, 19, '47459 Amelie Villages\r\nErnaburgh, NC 16802-8975', '6.138155', '-98.602927', NULL, NULL, NULL, 0, NULL),
(6, 6, '6', 'Powlowski Grove', 'Jace Drive', 1, 18, '4498 Dolores Springs\r\nNew Ilianamouth, MA 94945-6810', '45.651686', '78.478672', NULL, NULL, NULL, 0, NULL),
(7, 7, '7', 'Frederic Manors', 'Camren Road', 0, 14, '81113 Chaya Flat Suite 627\r\nPort Logan, AZ 16900', '14.309936', '-118.353522', NULL, NULL, NULL, 0, NULL),
(8, 8, '8', 'Stone Ville', 'Nico Rapids', 0, 19, '84466 Towne Burg Suite 937\r\nHegmannmouth, NV 86060-4408', '-48.444729', '167.716802', NULL, NULL, NULL, 0, NULL),
(9, 9, '9', 'Frederik Extension', 'Kreiger Stravenue', 1, 12, '4038 Rodriguez Valleys\r\nJeromeview, CT 20280-6674', '-43.352372', '-158.537855', NULL, NULL, NULL, 0, NULL),
(10, 10, '10', 'Heaney Flats', 'Sister Brook', 0, 11, '327 Lesley Streets Apt. 178\r\nParkerberg, AK 98699-5633', '-20.546641', '37.586261', NULL, NULL, NULL, 0, NULL),
(11, 11, '11', 'Carlotta Rapids', 'Will Rapids', 0, 20, '2627 Tatyana Islands Suite 287\r\nJaidenview, WA 95717-9963', '25.774364', '-131.530211', NULL, NULL, NULL, 0, NULL),
(12, 12, '12', 'Dibbert Dale', 'Harris Roads', 0, 15, '82643 Donnelly Estate Apt. 578\r\nPort Berta, AL 69326-5998', '-29.856308', '-137.413955', NULL, NULL, NULL, 0, NULL),
(13, 13, '13', 'Gleichner Trail', 'Aufderhar Shores', 1, 18, '87307 Murphy Stravenue\r\nEast Clementina, NJ 38239', '61.587180', '153.418035', NULL, NULL, NULL, 0, NULL),
(14, 14, '14', 'McDermott Run', 'Chelsie Well', 0, 19, '832 Stephania Knoll\r\nPort Trevorborough, RI 05701-3400', '24.033640', '-167.620675', NULL, NULL, NULL, 0, NULL),
(15, 15, '15', 'Abshire Village', 'Gerhold Unions', 1, 20, '438 Wiegand Fort Apt. 835\r\nEast Destineyview, DE 83200-9137', '-31.855142', '-122.405509', NULL, NULL, NULL, 0, NULL),
(16, 16, '16', 'Weimann Center', 'Langosh Forges', 0, 14, '1174 Medhurst Islands\r\nNorth Magdalena, LA 99860-3987', '24.826813', '55.887139', NULL, NULL, NULL, 0, NULL),
(17, 17, '17', 'Madaline Crossroad', 'Jeanie Alley', 1, 10, '62984 Ferry Neck\r\nMurphyside, CO 56734-2269', '-87.189166', '124.406064', NULL, NULL, NULL, 0, NULL),
(18, 18, '18', 'Jules Forge', 'Kraig Harbor', 1, 18, '4556 Lesly Mews Suite 180\r\nJaviermouth, TX 72417-4720', '-86.258877', '-54.787144', NULL, NULL, NULL, 0, NULL),
(19, 19, '19', 'Price Grove', 'McGlynn View', 1, 11, '72064 Carroll Oval\r\nWest Janice, VA 84831', '-35.955425', '50.746871', NULL, NULL, NULL, 0, NULL),
(20, 20, '20', 'Elinore Port', 'Powlowski Vista', 0, 15, '99133 Conn Extensions Apt. 402\r\nEast Green, OH 69773-9440', '-37.343181', '81.779725', NULL, NULL, NULL, 0, NULL),
(21, 21, '21', 'Hermann Islands', 'Altenwerth Creek', 1, 18, '60618 Hickle Gateway\r\nLeopoldfurt, WA 80174', '62.556911', '-138.729901', NULL, NULL, NULL, 0, NULL),
(22, 22, '22', 'Shaylee Ports', 'Schuster Brook', 1, 10, '244 Schowalter Fort Apt. 536\r\nParkerstad, TX 71995', '-34.738744', '-149.928016', NULL, NULL, NULL, 0, NULL),
(23, 23, '23', 'Baumbach Fords', 'Lauryn Lodge', 0, 15, '681 Rebecca Track Apt. 164\r\nNaderstad, NE 29233', '-89.651402', '-97.819582', NULL, NULL, NULL, 0, NULL),
(24, 24, '24', 'Mante Expressway', 'Koelpin Path', 1, 11, '4876 Alexandre Island\r\nNicolaschester, MA 20636', '-51.923605', '152.893118', NULL, NULL, NULL, 0, NULL),
(25, 25, '25', 'Ola Summit', 'Austen Landing', 1, 15, '86891 Rempel Well\r\nPort Jovanytown, OH 24948', '-1.941643', '-132.565335', NULL, NULL, NULL, 0, NULL),
(26, 26, '26', 'Collins Ports', 'Nicole Glens', 0, 11, '58807 Monahan Shore Apt. 306\r\nRutherfordfurt, IA 80449', '-42.673402', '-160.638033', NULL, NULL, NULL, 0, NULL),
(27, 27, '27', 'Arielle Shoals', 'Verona Cape', 0, 20, '21855 Ines Road\r\nMedhurststad, MT 61805-3717', '-1.033157', '90.823228', NULL, NULL, NULL, 0, NULL),
(28, 28, '28', 'Watsica Spur', 'Carolina Ranch', 1, 14, '53452 Graham Pines\r\nWintheisershire, RI 94865-2483', '-16.721809', '6.584288', NULL, NULL, NULL, 0, NULL),
(29, 29, '29', 'Rippin Rest', 'Polly Wells', 0, 18, '9327 Ena Stravenue\r\nPort Gustave, HI 73924', '58.603723', '-148.466364', NULL, NULL, NULL, 0, NULL),
(30, 30, '30', 'Gus Square', 'Pollich Courts', 1, 12, '2503 Parisian Extensions\r\nWest Edatown, IN 48682', '18.524715', '90.631640', NULL, NULL, NULL, 0, NULL),
(31, 31, '31', 'Jena Ways', 'Macejkovic Via', 0, 16, '27833 Raynor Inlet\r\nHansenmouth, OH 64569', '-49.012879', '-116.832584', NULL, NULL, NULL, 0, NULL),
(32, 32, '32', 'Macejkovic Roads', 'Bradley Brooks', 0, 18, '6949 Owen Spur\r\nLake Conradbury, WI 82906', '-7.161022', '21.928088', NULL, NULL, NULL, 0, NULL),
(33, 33, '33', 'Pierce Station', 'Rachel Port', 0, 17, '3446 Friesen Camp\r\nNorth Norval, UT 05494-0752', '20.159062', '-124.178386', NULL, NULL, NULL, 0, NULL),
(34, 34, '34', 'Mante Mall', 'Keebler Street', 1, 20, '152 Collins Springs\r\nHowefort, IL 53306', '14.151259', '-18.691056', NULL, NULL, NULL, 0, NULL),
(35, 35, '35', 'Abigail Vista', 'Murazik Track', 1, 20, '7157 Walker Summit\r\nNew Brodyland, RI 47031', '-0.344787', '-143.153429', NULL, NULL, NULL, 0, NULL),
(36, 36, '36', 'Wolff Hill', 'Green Dam', 1, 11, '828 Runolfsson Trace\r\nNew Edwardberg, ND 97767-4666', '-88.810819', '-139.987349', NULL, NULL, NULL, 0, NULL),
(37, 37, '37', 'Carmela Trace', 'Arlene Circles', 0, 19, '9189 Grady Square Apt. 887\r\nKuphalland, WI 22181', '4.147156', '-95.067763', NULL, NULL, NULL, 0, NULL),
(38, 38, '38', 'Kling Mountain', 'Gunnar Plain', 1, 13, '6929 Elyse Crossing\r\nMichaleborough, AK 34259-1670', '-62.556155', '164.401953', NULL, NULL, NULL, 0, NULL),
(39, 39, '39', 'Edward Path', 'Fisher Dale', 0, 11, '96723 Rice Pines\r\nTanyabury, AL 31887-1599', '24.641281', '56.094381', NULL, NULL, NULL, 0, NULL),
(40, 40, '40', 'Robel Port', 'Adeline Brook', 1, 19, '28974 Gerhold Crest\r\nSouth Janelle, CO 46363-0388', '59.920356', '-179.344282', NULL, NULL, NULL, 0, NULL),
(41, 41, '41', 'Octavia Springs', 'Colton Isle', 1, 17, '785 Myah Corners\r\nOkunevaberg, TX 96638-1486', '-38.619322', '-155.302489', NULL, NULL, NULL, 0, NULL),
(42, 42, '42', 'Agustin Gardens', 'Ruthe Unions', 0, 11, '327 Hermiston Lane\r\nGideonburgh, CO 56810-6571', '-34.038609', '55.909355', NULL, NULL, NULL, 0, NULL),
(43, 43, '43', 'Schinner Stream', 'Camila Run', 1, 10, '58154 Tristian Drive\r\nWest Kevenside, ND 24039', '-9.709785', '71.969002', NULL, NULL, NULL, 0, NULL),
(44, 44, '44', 'Agnes Motorway', 'Janie Union', 1, 14, '648 Spencer Groves Apt. 090\r\nWest Hudson, KY 72633', '9.223798', '-82.101147', NULL, NULL, NULL, 0, NULL),
(45, 45, '45', 'Herminia Underpass', 'Karlee Mills', 1, 15, '3911 Kathlyn Station Suite 701\r\nLockmanville, OR 06719-4506', '-18.216881', '-10.423339', NULL, NULL, NULL, 0, NULL),
(46, 46, '46', 'Jast River', 'Gleichner Spring', 0, 16, '589 Weissnat Coves\r\nEast Perrystad, AK 21785', '-84.901277', '-94.390343', NULL, NULL, NULL, 0, NULL),
(47, 47, '47', 'Graham Estates', 'Wendy Tunnel', 1, 20, '651 Christiansen Radial Apt. 625\r\nCormiermouth, NV 94455', '-68.777395', '-101.408399', NULL, NULL, NULL, 0, NULL),
(48, 48, '48', 'Kenneth Orchard', 'Mann Crescent', 1, 18, '675 Abshire Extension\r\nSouth Brandohaven, IL 63814-6567', '-85.069233', '77.804579', NULL, NULL, NULL, 0, NULL),
(49, 49, '49', 'Jast Squares', 'George Glens', 1, 12, '473 Harvey Lights Apt. 754\r\nJacobsonshire, OR 81793', '-27.325658', '-89.909731', NULL, NULL, NULL, 0, NULL),
(50, 50, '50', 'Deonte Green', 'Bergnaum Row', 1, 19, '654 Zoey Row Suite 749\r\nPort Lisandro, OK 48916', '7.735478', '-15.191890', NULL, NULL, NULL, 0, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `campaign`
--

CREATE TABLE `campaign` (
  `utm_uuid` char(60) COLLATE utf8_unicode_ci NOT NULL,
  `restaurant_uuid` char(60) COLLATE utf8_unicode_ci DEFAULT NULL,
  `utm_source` varchar(100) COLLATE utf8_unicode_ci DEFAULT NULL COMMENT 'e.g. newsletter, twitter, google, etc.',
  `utm_medium` varchar(100) COLLATE utf8_unicode_ci DEFAULT NULL COMMENT 'e.g. email, social, cpc, etc.',
  `utm_campaign` varchar(100) COLLATE utf8_unicode_ci DEFAULT NULL COMMENT 'e.g. promotion, sale, etc.',
  `utm_content` varchar(100) COLLATE utf8_unicode_ci DEFAULT NULL COMMENT 'Any call-to-action or headline, e.g. buy-now.',
  `utm_term` varchar(100) COLLATE utf8_unicode_ci DEFAULT NULL COMMENT 'Keywords for your paid search campaigns',
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `category`
--

CREATE TABLE `category` (
  `category_id` int(11) NOT NULL,
  `restaurant_uuid` char(60) COLLATE utf8_unicode_ci DEFAULT NULL,
  `title` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `title_ar` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `subtitle` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `subtitle_ar` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `category_meta_title` text COLLATE utf8_unicode_ci DEFAULT NULL,
  `category_meta_title_ar` text COLLATE utf8_unicode_ci DEFAULT NULL,
  `category_meta_description` text COLLATE utf8_unicode_ci DEFAULT NULL,
  `category_meta_description_ar` text COLLATE utf8_unicode_ci DEFAULT NULL,
  `category_image` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `sort_number` int(11) UNSIGNED DEFAULT NULL,
  `slug` varchar(255) COLLATE utf8_unicode_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `category`
--

INSERT INTO `category` (`category_id`, `restaurant_uuid`, `title`, `title_ar`, `subtitle`, `subtitle_ar`, `category_meta_title`, `category_meta_title_ar`, `category_meta_description`, `category_meta_description_ar`, `category_image`, `sort_number`, `slug`) VALUES
(1, '1', 'velit', 'dolores', 'voluptas a earum', 'dolore non deleniti', NULL, NULL, NULL, NULL, NULL, 1, 'nostrum-vel-amet-perspiciatis-sed-nemo-numquam'),
(2, '1', 'aperiam', 'deleniti', 'rerum nihil eaque', 'aut sed dolor', NULL, NULL, NULL, NULL, NULL, 3, 'nulla-possimus-ut-quam-placeat-aliquam-ipsam-sit'),
(3, '3', 'quidem', 'error', 'velit nobis illo', 'saepe illo laborum', NULL, NULL, NULL, NULL, NULL, 1, 'ratione-architecto-blanditiis-tenetur-totam'),
(4, '4', 'commodi', 'amet', 'necessitatibus ullam soluta', 'nobis ipsum voluptatum', NULL, NULL, NULL, NULL, NULL, 5, 'perferendis-recusandae-totam-est-magnam-ex-consequuntur-qui-in'),
(5, '5', 'esse', 'totam', 'pariatur molestiae laboriosam', 'facilis velit et', NULL, NULL, NULL, NULL, NULL, 2, 'reiciendis-quis-ratione-itaque-corrupti'),
(6, '6', 'maiores', 'asperiores', 'numquam fugiat ut', 'et quibusdam sapiente', NULL, NULL, NULL, NULL, NULL, 4, 'quas-dolorem-non-ut-possimus-facilis'),
(7, '7', 'et', 'dicta', 'est veritatis delectus', 'dolorum eius error', NULL, NULL, NULL, NULL, NULL, 4, 'rerum-et-quia-est-et-libero-aut-eius-qui'),
(8, '8', 'alias', 'sint', 'quod in beatae', 'sed et nesciunt', NULL, NULL, NULL, NULL, NULL, 3, 'commodi-itaque-et-officiis-ex-suscipit-aut'),
(9, '9', 'qui', 'et', 'aut quod adipisci', 'officiis praesentium incidunt', NULL, NULL, NULL, NULL, NULL, 5, 'non-soluta-voluptates-ad-aspernatur'),
(10, '10', 'consequatur', 'id', 'cum et fugiat', 'accusamus ut et', NULL, NULL, NULL, NULL, NULL, 1, 'eos-qui-veritatis-consequatur-iusto-nesciunt-earum-qui-praesentium'),
(11, '11', 'rerum', 'recusandae', 'harum quisquam ullam', 'laudantium suscipit enim', NULL, NULL, NULL, NULL, NULL, 2, 'tempora-fugiat-ratione-necessitatibus-rerum-occaecati-occaecati-voluptatem'),
(12, '12', 'delectus', 'ut', 'officiis blanditiis dolorum', 'temporibus non explicabo', NULL, NULL, NULL, NULL, NULL, 4, 'saepe-aut-est-delectus-labore-eos-aliquid-ut'),
(13, '13', 'expedita', 'ut', 'et quos ea', 'sint in quis', NULL, NULL, NULL, NULL, NULL, 2, 'amet-non-soluta-dolor'),
(14, '14', 'vitae', 'quis', 'et perspiciatis asperiores', 'enim magni ducimus', NULL, NULL, NULL, NULL, NULL, 2, 'quam-ab-magni-dolores'),
(15, '15', 'aut', 'minus', 'a aut nesciunt', 'omnis nam distinctio', NULL, NULL, NULL, NULL, NULL, 5, 'et-odio-quis-rerum-autem'),
(16, '16', 'atque', 'saepe', 'vel corporis facere', 'quas ratione velit', NULL, NULL, NULL, NULL, NULL, 5, 'quia-est-architecto-quibusdam-voluptatibus'),
(17, '17', 'omnis', 'tempora', 'sapiente itaque officiis', 'laboriosam quia sequi', NULL, NULL, NULL, NULL, NULL, 3, 'et-sed-cupiditate-dolores-asperiores-a-hic'),
(18, '18', 'est', 'rem', 'sint cupiditate et', 'libero magni mollitia', NULL, NULL, NULL, NULL, NULL, 4, 'ipsa-molestiae-in-aut-voluptatem-autem-quia-ad'),
(19, '19', 'doloremque', 'sint', 'natus architecto quibusdam', 'voluptate sed sed', NULL, NULL, NULL, NULL, NULL, 3, 'mollitia-qui-est-dolore-excepturi-ea'),
(20, '20', 'dolores', 'in', 'at quas aut', 'qui nemo nemo', NULL, NULL, NULL, NULL, NULL, 5, 'illo-recusandae-unde-qui-voluptatem-totam-aut-rerum-eius'),
(21, '21', 'deserunt', 'totam', 'dolor quis architecto', 'ipsum iure sequi', NULL, NULL, NULL, NULL, NULL, 4, 'ut-debitis-necessitatibus-sit-non'),
(22, '22', 'non', 'enim', 'dolor sapiente eum', 'quasi magni impedit', NULL, NULL, NULL, NULL, NULL, 5, 'et-iusto-eaque-consequuntur-nisi-non-enim-sit'),
(23, '23', 'quia', 'distinctio', 'maiores nemo totam', 'ab eos eos', NULL, NULL, NULL, NULL, NULL, 1, 'deleniti-illum-qui-asperiores'),
(24, '24', 'dolorem', 'minima', 'beatae sed voluptas', 'blanditiis laborum vero', NULL, NULL, NULL, NULL, NULL, 3, 'consequuntur-voluptatem-quisquam-quia'),
(25, '25', 'omnis', 'sed', 'illum pariatur suscipit', 'quasi omnis omnis', NULL, NULL, NULL, NULL, NULL, 5, 'quaerat-optio-ipsa-expedita-temporibus-doloribus-modi-eligendi'),
(26, '26', 'commodi', 'ut', 'voluptatem est quia', 'ut facilis sed', NULL, NULL, NULL, NULL, NULL, 4, 'dolorem-et-voluptatem-aut-nihil-consequatur-tempore'),
(27, '27', 'impedit', 'nulla', 'dolore sunt natus', 'doloremque culpa illum', NULL, NULL, NULL, NULL, NULL, 4, 'quod-sunt-at-nemo-reiciendis-ab-voluptas-ea'),
(28, '28', 'sapiente', 'temporibus', 'quisquam ut quis', 'eum sit in', NULL, NULL, NULL, NULL, NULL, 5, 'quos-distinctio-et-ut-qui-cum-ut-est'),
(29, '29', 'eum', 'et', 'fugit beatae quisquam', 'et nemo ut', NULL, NULL, NULL, NULL, NULL, 4, 'officiis-consequatur-distinctio-nemo-cumque'),
(30, '30', 'tenetur', 'tempore', 'totam quaerat aliquid', 'qui eos at', NULL, NULL, NULL, NULL, NULL, 3, 'quia-vitae-sit-aut-maiores-temporibus-ut'),
(31, '31', 'tempora', 'asperiores', 'ut dolor non', 'itaque ratione animi', NULL, NULL, NULL, NULL, NULL, 3, 'saepe-porro-ut-illum-voluptatem-qui-ut-eveniet'),
(32, '32', 'odit', 'recusandae', 'et itaque sapiente', 'numquam temporibus et', NULL, NULL, NULL, NULL, NULL, 3, 'et-repellat-quia-qui-omnis-omnis'),
(33, '33', 'similique', 'eius', 'sed non quis', 'cumque nihil fugit', NULL, NULL, NULL, NULL, NULL, 3, 'et-sit-delectus-animi-magni-voluptatibus-omnis-aut'),
(34, '34', 'est', 'minus', 'voluptas amet omnis', 'corporis voluptates inventore', NULL, NULL, NULL, NULL, NULL, 4, 'aspernatur-qui-iure-quia-molestiae-commodi-quas-vitae'),
(35, '35', 'voluptas', 'qui', 'repudiandae in molestiae', 'saepe iste quisquam', NULL, NULL, NULL, NULL, NULL, 4, 'voluptas-quibusdam-officia-expedita-quos-unde-sit-voluptates'),
(36, '36', 'inventore', 'aut', 'molestias magni illo', 'molestiae qui id', NULL, NULL, NULL, NULL, NULL, 2, 'consequatur-voluptatem-iure-et-numquam-in-iste'),
(37, '37', 'commodi', 'beatae', 'ullam dignissimos repellat', 'maxime dignissimos quos', NULL, NULL, NULL, NULL, NULL, 2, 'aut-adipisci-cupiditate-qui-quia-eum-eum'),
(38, '38', 'expedita', 'nam', 'aspernatur velit qui', 'tenetur omnis vitae', NULL, NULL, NULL, NULL, NULL, 1, 'maiores-laborum-omnis-autem-harum-veritatis-et-inventore'),
(39, '39', 'fugiat', 'corporis', 'repellat ut unde', 'quia perspiciatis mollitia', NULL, NULL, NULL, NULL, NULL, 3, 'doloribus-earum-officia-dolores-laboriosam-vel-fugiat'),
(40, '40', 'earum', 'maiores', 'voluptas nobis eos', 'reprehenderit sit commodi', NULL, NULL, NULL, NULL, NULL, 1, 'asperiores-qui-vel-libero-quia-modi'),
(41, '41', 'unde', 'quod', 'atque eum quia', 'vitae repudiandae ut', NULL, NULL, NULL, NULL, NULL, 2, 'quos-sit-eligendi-eaque-et'),
(42, '42', 'eligendi', 'id', 'quis tempore autem', 'a et omnis', NULL, NULL, NULL, NULL, NULL, 1, 'voluptates-veniam-aut-eaque-sequi'),
(43, '43', 'enim', 'vel', 'eligendi dignissimos sint', 'tenetur voluptas amet', NULL, NULL, NULL, NULL, NULL, 3, 'dolorum-non-ipsam-est-sequi-dolorem-saepe'),
(44, '44', 'quis', 'enim', 'voluptate consequatur quasi', 'quam animi dignissimos', NULL, NULL, NULL, NULL, NULL, 2, 'qui-nemo-quia-voluptatibus-quia-dolor-fuga'),
(45, '45', 'non', 'assumenda', 'omnis tempora eum', 'ut perspiciatis quasi', NULL, NULL, NULL, NULL, NULL, 5, 'est-blanditiis-eum-autem-aut-quibusdam-magni-sit'),
(46, '46', 'veniam', 'sed', 'nemo possimus maiores', 'at sapiente ratione', NULL, NULL, NULL, NULL, NULL, 2, 'expedita-et-nam-qui-voluptas'),
(47, '47', 'rem', 'voluptatem', 'aut consequatur officiis', 'beatae eum ea', NULL, NULL, NULL, NULL, NULL, 4, 'vero-iusto-temporibus-ut-ipsam-ut'),
(48, '48', 'delectus', 'adipisci', 'eum ut est', 'fuga et voluptatem', NULL, NULL, NULL, NULL, NULL, 4, 'totam-sed-sit-quia-labore-velit'),
(49, '49', 'ut', 'est', 'quia ab in', 'ut voluptatem dolores', NULL, NULL, NULL, NULL, NULL, 4, 'qui-consectetur-sequi-nostrum-ipsam-voluptates-et-id-ex'),
(50, '50', 'excepturi', 'unde', 'et saepe cum', 'omnis natus sed', NULL, NULL, NULL, NULL, NULL, 2, 'consequuntur-sint-voluptatum-deleniti-necessitatibus-et-nisi-praesentium');

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
(1, 1, '1'),
(2, 2, '2'),
(3, 3, '3'),
(4, 4, '4'),
(5, 5, '5'),
(6, 6, '6'),
(7, 7, '7'),
(8, 8, '8'),
(9, 9, '9'),
(10, 10, '10'),
(11, 11, '11'),
(12, 12, '12'),
(13, 13, '13'),
(14, 14, '14'),
(15, 15, '15'),
(16, 16, '16'),
(17, 17, '17'),
(18, 18, '18'),
(19, 19, '19'),
(20, 20, '20'),
(21, 21, '21'),
(22, 22, '22'),
(23, 23, '23'),
(24, 24, '24'),
(25, 25, '25'),
(26, 26, '26'),
(27, 27, '27'),
(28, 28, '28'),
(29, 29, '29'),
(30, 30, '30'),
(31, 31, '31'),
(32, 32, '32'),
(33, 33, '33'),
(34, 34, '34'),
(35, 35, '35'),
(36, 36, '36'),
(37, 37, '37'),
(38, 38, '38'),
(39, 39, '39'),
(40, 40, '40'),
(41, 41, '41'),
(42, 42, '42'),
(43, 43, '43'),
(44, 44, '44'),
(45, 45, '45'),
(46, 46, '46'),
(47, 47, '47'),
(48, 48, '48'),
(49, 49, '49'),
(50, 50, '50');

-- --------------------------------------------------------

--
-- Table structure for table `city`
--

CREATE TABLE `city` (
  `city_id` int(11) NOT NULL,
  `country_id` int(11) NOT NULL DEFAULT 84,
  `city_name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `city_name_ar` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `city`
--

INSERT INTO `city` (`city_id`, `country_id`, `city_name`, `city_name_ar`) VALUES
(1, 1, 'Ankundingmouth', 'Port Hubertshire'),
(2, 2, 'Lemkeshire', 'McDermotthaven'),
(3, 3, 'South Shaniafort', 'Dickensmouth'),
(4, 4, 'Lake Ledahaven', 'West Jeromyshire'),
(5, 5, 'Claraview', 'Lake Eveline'),
(6, 6, 'Tillmanview', 'Wisokyport'),
(7, 7, 'Port Eunice', 'Willmouth'),
(8, 8, 'Greenholtfort', 'Boyermouth'),
(9, 9, 'North Kayleighshire', 'Bessiefurt'),
(10, 10, 'Hodkiewiczchester', 'East Efrainberg'),
(11, 11, 'West Janickland', 'Port Luluville'),
(12, 12, 'North Sammie', 'Martaton'),
(13, 13, 'South Wilhelmineton', 'Port Marquestown'),
(14, 14, 'Geoffreyborough', 'East Vince'),
(15, 15, 'Eleanoreburgh', 'Hartmannview'),
(16, 16, 'Eunicestad', 'Rhodaview'),
(17, 17, 'Schulistborough', 'North Elenora'),
(18, 18, 'South Carolannestad', 'Lake Ivaborough'),
(19, 19, 'Itzelmouth', 'Robertsstad'),
(20, 20, 'Daijafurt', 'North Elyse'),
(21, 21, 'Halvorsonburgh', 'Lake Evangeline'),
(22, 22, 'East Avaland', 'East Urielfurt'),
(23, 23, 'Onaville', 'New Katarinamouth'),
(24, 24, 'Cristville', 'North Manuel'),
(25, 25, 'Greenmouth', 'North Yvonnemouth'),
(26, 26, 'Ziemannfort', 'Port Margie'),
(27, 27, 'Wildermanland', 'New Camilla'),
(28, 28, 'Sengerland', 'Boyerfurt'),
(29, 29, 'South Josieside', 'New Roosevelt'),
(30, 30, 'Ullrichshire', 'Lake Deonteville'),
(31, 31, 'Kovacekland', 'Adrianbury'),
(32, 32, 'North Libbie', 'Pacochaborough'),
(33, 33, 'Bethanymouth', 'Ryanbury'),
(34, 34, 'Lake Faeburgh', 'Bartonburgh'),
(35, 35, 'Olsonport', 'Port Sterling'),
(36, 36, 'Lake Brice', 'Lake Maramouth'),
(37, 37, 'Johannaborough', 'Kulasview'),
(38, 38, 'Port Marcelino', 'Moriahfurt'),
(39, 39, 'West Giuseppefort', 'North Lorenza'),
(40, 40, 'Freddyhaven', 'Spencerbury'),
(41, 41, 'Port Peggie', 'Odieville'),
(42, 42, 'West Monteside', 'Conniehaven'),
(43, 43, 'Annabelmouth', 'Port Loren'),
(44, 44, 'North Ayanaburgh', 'Veumfurt'),
(45, 45, 'Yasmeenfort', 'Stammfurt'),
(46, 46, 'East Deven', 'Santaview'),
(47, 47, 'Yasminefort', 'Larryview'),
(48, 48, 'North Clifton', 'East Ernestineborough'),
(49, 49, 'North Meaganbury', 'Goldnerside'),
(50, 50, 'Rossieton', 'Clemensside');

-- --------------------------------------------------------

--
-- Table structure for table `country`
--

CREATE TABLE `country` (
  `country_id` int(11) NOT NULL,
  `country_name` varchar(80) DEFAULT NULL,
  `country_name_ar` varchar(80) DEFAULT NULL,
  `iso` char(2) DEFAULT NULL,
  `emoji` char(3) DEFAULT NULL,
  `country_code` int(3) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `country`
--

INSERT INTO `country` (`country_id`, `country_name`, `country_name_ar`, `iso`, `emoji`, `country_code`) VALUES
(1, 'Rwanda', 'El Salvador', 'HK', NULL, 0),
(2, 'Fiji', 'Iran', 'TW', NULL, 0),
(3, 'Uganda', 'New Caledonia', 'VA', NULL, 0),
(4, 'Estonia', 'Cyprus', 'CR', NULL, 0),
(5, 'Morocco', 'Macedonia', 'BZ', NULL, 0),
(6, 'Macedonia', 'Netherlands', 'DZ', NULL, 0),
(7, 'Niger', 'Grenada', 'RE', NULL, 0),
(8, 'Palau', 'Croatia', 'ER', NULL, 0),
(9, 'Germany', 'Cote d\'Ivoire', 'PR', NULL, 0),
(10, 'Anguilla', 'Hungary', 'SO', NULL, 0),
(11, 'United States Minor Outlying Islands', 'Libyan Arab Jamahiriya', 'ER', NULL, 0),
(12, 'Czech Republic', 'Finland', 'NC', NULL, 0),
(13, 'Uruguay', 'Palau', 'VG', NULL, 0),
(14, 'El Salvador', 'Moldova', 'MT', NULL, 0),
(15, 'Christmas Island', 'Bermuda', 'KR', NULL, 0),
(16, 'Malta', 'French Polynesia', 'TR', NULL, 0),
(17, 'Haiti', 'Tunisia', 'MD', NULL, 0),
(18, 'Zambia', 'Cocos (Keeling) Islands', 'NZ', NULL, 0),
(19, 'Christmas Island', 'Madagascar', 'GE', NULL, 0),
(20, 'Lebanon', 'Sudan', 'LK', NULL, 0),
(21, 'Norfolk Island', 'Denmark', 'BH', NULL, 0),
(22, 'Cook Islands', 'British Virgin Islands', 'AW', NULL, 0),
(23, 'Netherlands Antilles', 'Niue', 'BJ', NULL, 0),
(24, 'South Africa', 'Finland', 'MS', NULL, 0),
(25, 'Saint Vincent and the Grenadines', 'Western Sahara', 'UA', NULL, 0),
(26, 'Nigeria', 'Martinique', 'PL', NULL, 0),
(27, 'Israel', 'Guadeloupe', 'NR', NULL, 0),
(28, 'Uruguay', 'Tunisia', 'BJ', NULL, 0),
(29, 'Malaysia', 'Namibia', 'SV', NULL, 0),
(30, 'Greenland', 'United States Virgin Islands', 'ML', NULL, 0),
(31, 'Chile', 'Azerbaijan', 'KR', NULL, 0),
(32, 'Albania', 'Wallis and Futuna', 'QA', NULL, 0),
(33, 'Fiji', 'Palestinian Territories', 'BB', NULL, 0),
(34, 'Puerto Rico', 'Nepal', 'BT', NULL, 0),
(35, 'Austria', 'Belgium', 'TL', NULL, 0),
(36, 'Sri Lanka', 'Poland', 'DM', NULL, 0),
(37, 'Morocco', 'Gabon', 'MS', NULL, 0),
(38, 'Wallis and Futuna', 'Mongolia', 'FR', NULL, 0),
(39, 'Macao', 'Ethiopia', 'PA', NULL, 0),
(40, 'Lesotho', 'Brunei Darussalam', 'BG', NULL, 0),
(41, 'Turkey', 'Peru', 'BN', NULL, 0),
(42, 'Kyrgyz Republic', 'French Guiana', 'DO', NULL, 0),
(43, 'British Indian Ocean Territory (Chagos Archipelago)', 'Cuba', 'AD', NULL, 0),
(44, 'Tokelau', 'Benin', 'NO', NULL, 0),
(45, 'Bulgaria', 'Morocco', 'SK', NULL, 0),
(46, 'South Georgia and the South Sandwich Islands', 'Kuwait', 'CC', NULL, 0),
(47, 'Sudan', 'Philippines', 'JP', NULL, 0),
(48, 'Spain', 'South Africa', 'KZ', NULL, 0),
(49, 'Cote d\'Ivoire', 'Barbados', 'TG', NULL, 0),
(50, 'Cayman Islands', 'Colombia', 'NR', NULL, 0);

-- --------------------------------------------------------

--
-- Table structure for table `country_payment_method`
--

CREATE TABLE `country_payment_method` (
  `payment_method_id` int(11) NOT NULL,
  `country_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `country_payment_method`
--

INSERT INTO `country_payment_method` (`payment_method_id`, `country_id`) VALUES
(1, 1),
(2, 2),
(3, 3),
(4, 4),
(5, 5),
(6, 6),
(7, 7),
(8, 8),
(9, 9),
(10, 10);

-- --------------------------------------------------------

--
-- Table structure for table `currency`
--

CREATE TABLE `currency` (
  `currency_id` int(11) NOT NULL,
  `title` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `code` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `currency_symbol` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `rate` double DEFAULT NULL,
  `decimal_place` tinyint(1) DEFAULT 2,
  `sort_order` smallint(3) NOT NULL DEFAULT 0,
  `status` tinyint(1) DEFAULT 1,
  `datetime` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `currency`
--

INSERT INTO `currency` (`currency_id`, `title`, `code`, `currency_symbol`, `rate`, `decimal_place`, `sort_order`, `status`, `datetime`) VALUES
(1, 'Bitcoin', 'BTC', '₿', 10, 8, 0, 1, '2019-07-01 19:14:14'),
(2, 'United Arab Emirates dirham ', 'AED', ' د.إ', 3.673036, 2, 0, 1, '2019-07-01 19:14:14'),
(3, 'Afghan afghani ', 'AFN', ' ؋', 81.503827, 2, 0, 1, '2019-07-01 19:14:14'),
(4, 'Albanian lek ', 'ALL', ' Lek', 107.774824, 2, 0, 1, '2019-07-01 19:14:14'),
(5, 'Armenian dram ', 'AMD', ' ֏', 477.73943, 2, 0, 1, '2019-07-01 19:14:14'),
(6, 'Netherlands Antillean guilder ', 'ANG', ' ƒ', 1.876648, 2, 0, 1, '2019-07-01 19:14:14'),
(7, 'Angolan kwanza ', 'AOA', ' Kz', 340.27135, 2, 0, 1, '2019-07-01 19:14:14'),
(8, 'Argentine peso ', 'ARS', ' N$', 42.406957, 2, 0, 1, '2019-07-01 19:14:14'),
(9, 'Australian dollar ', 'AUD', ' AU$', 1.424104, 2, 0, 1, '2019-07-01 19:14:14'),
(10, 'Aruban florin ', 'AWG', ' ƒ', 1.799999, 2, 0, 1, '2019-07-01 19:14:14'),
(12, 'Azerbaijani manat ', 'AZN', ' ₼', 1.705039, 2, 0, 1, '2019-07-01 19:14:14'),
(13, 'Barbadian dollar ', 'BBD', ' Bds$', 2.020897, 2, 0, 1, '2019-07-01 19:14:14'),
(14, 'Bangladeshi taka ', 'BDT', ' ৳', 84.57023, 2, 0, 1, '2019-07-01 19:14:14'),
(15, 'Bulgarian lev ', 'BGN', ' лв', 1.720603, 2, 0, 1, '2019-07-01 19:14:14'),
(16, 'Bahraini dinar ', 'BHD', ' د.ب', 0.377011, 3, 0, 1, '2019-07-01 19:14:14'),
(17, 'Burundian franc ', 'BIF', ' Fr', 1845.996239, 0, 0, 1, '2019-07-01 19:14:14'),
(18, 'Bermudian dollar ', 'BMD', ' BD$', 1, 2, 0, 1, '2019-07-01 19:14:14'),
(19, 'Brunei dollar ', 'BND', ' B$', 1.350803, 2, 0, 1, '2019-07-01 19:14:14'),
(20, 'Bolivian boliviano ', 'BOB', ' $b', 6.916238, 2, 0, 1, '2019-07-01 19:14:14'),
(21, 'Brazilian real ', 'BRL', ' R$', 3.851035, 2, 0, 1, '2019-07-01 19:14:14'),
(22, 'Bahamian dollar ', 'BSD', ' B$', 1.000749, 2, 0, 1, '2019-07-01 19:14:14'),
(23, 'Bhutanese ngultrum ', 'BTN', ' Nu.', 68.949357, 2, 0, 1, '2019-07-01 19:14:14'),
(24, 'Botswana pula ', 'BWP', ' P', 10.648022, 2, 0, 1, '2019-07-01 19:14:14'),
(25, 'Belarusian ruble ', 'BYN', ' Br', 2.042303, 0, 0, 1, '2019-07-01 19:14:14'),
(26, 'Belarusian ruble ', 'BYR', ' Br', 19599.960042, 0, 0, 1, '2019-07-01 19:14:14'),
(27, 'Belize dollar ', 'BZD', ' BZ$', 2.017449, 2, 0, 1, '2019-07-01 19:14:14'),
(28, 'Canadian dollar ', 'CAD', ' C$', 1.312549, 2, 0, 1, '2019-07-01 19:14:14'),
(29, 'Congolese franc ', 'CDF', ' Fr', 1660.996977, 2, 0, 1, '2019-07-01 19:14:14'),
(30, 'Swiss franc ', 'CHF', ' CHF', 0.976525, 2, 0, 1, '2019-07-01 19:14:14'),
(31, 'Chilean Unit of Account ', 'CLF', ' UF', 0.024555, 4, 0, 1, '2019-07-01 19:14:14'),
(32, 'Chilean peso ', 'CLP', ' CLP$', 677.502532, 0, 0, 1, '2019-07-01 19:14:14'),
(33, 'Chinese yuan ', 'CNY', ' 元', 6.866791, 2, 0, 1, '2019-07-01 19:14:14'),
(34, 'Colombian peso ', 'COP', ' COL$', 3213.493451, 2, 0, 1, '2019-07-01 19:14:14'),
(35, 'Costa Rican colón ', 'CRC', ' ₡', 582.909208, 2, 0, 1, '2019-07-01 19:14:14'),
(36, 'Cuban convertible peso ', 'CUC', ' CUC$', 1, 2, 0, 1, '2019-07-01 19:14:14'),
(37, 'Cuban peso ', 'CUP', ' $MN', 26.499946, 2, 0, 1, '2019-07-01 19:14:14'),
(38, 'Cape Verdean escudo ', 'CVE', ' Esc', 97.350197, 2, 0, 1, '2019-07-01 19:14:14'),
(39, 'Czech koruna ', 'CZK', ' Kč', 22.35636, 2, 0, 1, '2019-07-01 19:14:14'),
(40, 'Djiboutian franc ', 'DJF', ' Fr', 177.720034, 0, 0, 1, '2019-07-01 19:14:14'),
(41, 'Danish krone ', 'DKK', ' kr', 6.563293, 2, 0, 1, '2019-07-01 19:14:14'),
(42, 'Dominican peso ', 'DOP', ' RD$', 51.010292, 2, 0, 1, '2019-07-01 19:14:14'),
(43, 'Algerian dinar ', 'DZD', ' د.ج', 118.6448, 2, 0, 1, '2019-07-01 19:14:14'),
(44, 'Egyptian pound ', 'EGP', ' £', 16.669007, 2, 0, 1, '2019-07-01 19:14:14'),
(45, 'Eritrean nakfa ', 'ERN', ' Nfk', 15.000328, 2, 0, 1, '2019-07-01 19:14:14'),
(46, 'Ethiopian birr ', 'ETB', ' Br', 29.103819, 2, 0, 1, '2019-07-01 19:14:14'),
(47, 'Euro ', 'EUR', ' €', 0.878003, 2, 0, 1, '2019-07-01 19:14:14'),
(48, 'Fijian dollar ', 'FJD', ' FJ$', 2.132248, 2, 0, 1, '2019-07-01 19:14:14'),
(49, 'Falkland Islands pound ', 'FKP', ' £', 0.788216, 2, 0, 1, '2019-07-01 19:14:14'),
(50, 'British pound ', 'GBP', ' £', 0.787709, 2, 0, 1, '2019-07-01 19:14:14'),
(51, 'Georgian lari ', 'GEL', ' ₾', 2.845035, 2, 0, 1, '2019-07-01 19:14:14'),
(52, 'Guernsey pound ', 'GGP', ' £', 0.787446, 2, 0, 1, '2019-07-01 19:14:14'),
(53, 'Ghanaian cedi ', 'GHS', ' ₵', 5.42503, 2, 0, 1, '2019-07-01 19:14:14'),
(54, 'Gibraltar pound ', 'GIP', ' £', 0.788214, 2, 0, 1, '2019-07-01 19:14:14'),
(55, 'Gambian dalasi ', 'GMD', ' D', 49.703753, 2, 0, 1, '2019-07-01 19:14:14'),
(56, 'Guinean franc ', 'GNF', ' Fr', 9224.981549, 0, 0, 1, '2019-07-01 19:14:14'),
(57, 'Guatemalan quetzal ', 'GTQ', ' Q', 7.711736, 2, 0, 1, '2019-07-01 19:14:14'),
(58, 'Guyanese dollar ', 'GYD', ' GY$', 209.394614, 2, 0, 1, '2019-07-01 19:14:14'),
(59, 'Hong Kong dollar ', 'HKD', ' HK$', 7.813237, 2, 0, 1, '2019-07-01 19:14:14'),
(60, 'Honduran lempira ', 'HNL', ' L', 24.703792, 2, 0, 1, '2019-07-01 19:14:14'),
(61, 'Croatian kuna ', 'HRK', ' kn', 6.505093, 2, 0, 1, '2019-07-01 19:14:14'),
(62, 'Haitian gourde ', 'HTG', ' G', 93.836849, 2, 0, 1, '2019-07-01 19:14:14'),
(63, 'Hungarian forint ', 'HUF', ' Ft', 284.103257, 2, 0, 1, '2019-07-01 19:14:14'),
(64, 'Indonesian rupiah ', 'IDR', ' Rp', 14124.521205, 2, 0, 1, '2019-07-01 19:14:14'),
(65, 'Israeli new shekel ', 'ILS', ' ₪', 3.566598, 2, 0, 1, '2019-07-01 19:14:14'),
(66, 'Manx pound ', 'IMP', ' £', 0.787447, 2, 0, 1, '2019-07-01 19:14:14'),
(67, 'Indian rupee ', 'INR', ' ₹', 68.950165, 2, 0, 1, '2019-07-01 19:14:14'),
(68, 'Iraqi dinar ', 'IQD', ' ع.د', 1189.997577, 3, 0, 1, '2019-07-01 19:14:14'),
(69, 'Iranian rial ', 'IRR', ' ﷼', 42104.914514, 2, 0, 1, '2019-07-01 19:14:14'),
(70, 'Icelandic króna ', 'ISK', ' kr', 124.630134, 0, 0, 1, '2019-07-01 19:14:14'),
(71, 'Jersey pound ', 'JEP', ' £', 0.787445, 2, 0, 1, '2019-07-01 19:14:14'),
(72, 'Jamaican dollar ', 'JMD', ' J$', 130.650122, 2, 0, 1, '2019-07-01 19:14:14'),
(73, 'Jordanian dinar ', 'JOD', ' د.ا', 0.709039, 3, 0, 1, '2019-07-01 19:14:14'),
(74, 'Japanese yen ', 'JPY', ' ¥', 107.894822, 0, 0, 1, '2019-07-01 19:14:14'),
(75, 'Kenyan shilling ', 'KES', ' Sh', 102.340178, 2, 0, 1, '2019-07-01 19:14:14'),
(76, 'Kyrgyzstani som ', 'KGS', ' с', 69.492663, 2, 0, 1, '2019-07-01 19:14:14'),
(77, 'Cambodian riel ', 'KHR', ' ៛', 4069.992054, 2, 0, 1, '2019-07-01 19:14:14'),
(78, 'Comorian franc ', 'KMF', ' Fr', 433.124158, 0, 0, 1, '2019-07-01 19:14:14'),
(79, 'North Korean won ', 'KPW', ' ₩', 900.109615, 2, 0, 1, '2019-07-01 19:14:14'),
(80, 'South Korean won ', 'KRW', ' ₩', 1156.428028, 0, 0, 1, '2019-07-01 19:14:14'),
(81, 'Kuwaiti dinar ', 'KWD', ' د.ك', 0.303451, 3, 0, 1, '2019-07-01 19:14:14'),
(82, 'Cayman Islands dollar ', 'KYD', ' CI$', 0.834134, 2, 0, 1, '2019-07-01 19:14:14'),
(83, 'Kazakhstani tenge ', 'KZT', ' ₸', 380.959607, 2, 0, 1, '2019-07-01 19:14:14'),
(84, 'Lao kip ', 'LAK', ' ₭', 8665.982683, 2, 0, 1, '2019-07-01 19:14:14'),
(85, 'Lebanese pound ', 'LBP', ' ل.ل', 1507.997276, 2, 0, 1, '2019-07-01 19:14:14'),
(86, 'Sri Lankan rupee ', 'LKR', ' Rs', 176.490024, 2, 0, 1, '2019-07-01 19:14:14'),
(87, 'Liberian dollar ', 'LRD', ' LD$', 196.249983, 2, 0, 1, '2019-07-01 19:14:14'),
(88, 'Lesotho loti ', 'LSL', ' L', 14.090355, 2, 0, 1, '2019-07-01 19:14:14'),
(89, 'Lithuania Litas ', 'LTL', ' Lt', 2.952737, 2, 0, 1, '2019-07-01 19:14:14'),
(90, 'Latvia Lat ', 'LVL', ' ‎Ls', 0.604891, 2, 0, 1, '2019-07-01 19:14:14'),
(91, 'Libyan dinar ', 'LYD', ' ل.د', 1.390379, 3, 0, 1, '2019-07-01 19:14:14'),
(92, 'Moroccan dirham ', 'MAD', ' د.م.', 9.580187, 2, 0, 1, '2019-07-01 19:14:14'),
(93, 'Moldovan leu ', 'MDL', ' L', 18.090469, 2, 0, 1, '2019-07-01 19:14:14'),
(94, 'Malagasy ariary ', 'MGA', ' Ar', 3602.496414, 2, 0, 1, '2019-07-01 19:14:14'),
(95, 'Macedonian denar ', 'MKD', ' ден', 54.159396, 2, 0, 1, '2019-07-01 19:14:14'),
(96, 'Burmese kyat ', 'MMK', ' Ks', 1516.347289, 2, 0, 1, '2019-07-01 19:14:14'),
(97, 'Mongolian tögrög ', 'MNT', ' ₮', 2636.538158, 2, 0, 1, '2019-07-01 19:14:14'),
(98, 'Macanese pataca ', 'MOP', ' P', 8.050835, 2, 0, 1, '2019-07-01 19:14:14'),
(99, 'Mauritanian ouguiya ', 'MRO', ' UM', 356.999621, 2, 0, 1, '2019-07-01 19:14:14'),
(100, 'Mauritian rupee ', 'MUR', ' ₨', 35.651434, 2, 0, 1, '2019-07-01 19:14:14'),
(101, 'Maldivian rufiyaa ', 'MVR', ' .ރ', 15.45035, 2, 0, 1, '2019-07-01 19:14:14'),
(102, 'Malawian kwacha ', 'MWK', ' MK', 759.998796, 2, 0, 1, '2019-07-01 19:14:14'),
(103, 'Mexican peso ', 'MXN', ' Mex$', 19.229666, 2, 0, 1, '2019-07-01 19:14:14'),
(104, 'Malaysian ringgit ', 'MYR', ' RM', 4.133033, 2, 0, 1, '2019-07-01 19:14:14'),
(105, 'Mozambican metical ', 'MZN', ' MT', 62.103606, 2, 0, 1, '2019-07-01 19:14:14'),
(106, 'Namibian dollar ', 'NAD', ' N$', 14.090351, 2, 0, 1, '2019-07-01 19:14:14'),
(107, 'Nigerian naira ', 'NGN', ' ₦', 359.999611, 2, 0, 1, '2019-07-01 19:14:14'),
(108, 'Nicaraguan córdoba ', 'NIO', ' C', 33.45031, 2, 0, 1, '2019-07-01 19:14:14'),
(109, 'Norwegian krone ', 'NOK', ' kr', 8.531022, 2, 0, 1, '2019-07-01 19:14:14'),
(110, 'Nepalese rupee ', 'NPR', ' ₨', 110.654814, 2, 0, 1, '2019-07-01 19:14:14'),
(111, 'New Zealand dollar ', 'NZD', ' NZ$', 1.487649, 2, 0, 1, '2019-07-01 19:14:14'),
(112, 'Omani rial ', 'OMR', ' ﷼', 0.384961, 3, 0, 1, '2019-07-01 19:14:14'),
(113, 'Panamanian balboa ', 'PAB', ' B/.', 1.00095, 2, 0, 1, '2019-07-01 19:14:14'),
(114, 'Peruvian sol ', 'PEN', ' S/.', 3.2925, 2, 0, 1, '2019-07-01 19:14:14'),
(115, 'Papua New Guinean kina ', 'PGK', ' K', 3.382499, 2, 0, 1, '2019-07-01 19:14:14'),
(116, 'Philippine piso ', 'PHP', ' ₱', 51.244937, 2, 0, 1, '2019-07-01 19:14:14'),
(117, 'Pakistani rupee ', 'PKR', ' ₨', 163.000011, 2, 0, 1, '2019-07-01 19:14:14'),
(118, 'Polish złoty ', 'PLN', ' zł', 3.733343, 2, 0, 1, '2019-07-01 19:14:14'),
(119, 'Paraguayan guaraní ', 'PYG', ' ₲', 6200.737735, 0, 0, 1, '2019-07-01 19:14:14'),
(120, 'Qatari riyal ', 'QAR', ' ﷼', 3.641033, 2, 0, 1, '2019-07-01 19:14:14'),
(121, 'Romanian leu ', 'RON', ' lei', 4.154397, 2, 0, 1, '2019-07-01 19:14:14'),
(122, 'Serbian dinar ', 'RSD', ' дин.', 103.703483, 2, 0, 1, '2019-07-01 19:14:14'),
(123, 'Russian ruble ', 'RUB', ' ₽', 63.27491, 2, 0, 1, '2019-07-01 19:14:14'),
(124, 'Rwandan franc ', 'RWF', ' Fr', 909.998146, 0, 0, 1, '2019-07-01 19:14:14'),
(125, 'Saudi riyal ', 'SAR', ' ر.س', 3.749899, 2, 0, 1, '2019-07-01 19:14:14'),
(126, 'Solomon Islands dollar ', 'SBD', ' SI$', 8.241734, 2, 0, 1, '2019-07-01 19:14:14'),
(127, 'Seychellois rupee ', 'SCR', ' ₨', 13.657477, 2, 0, 1, '2019-07-01 19:14:14'),
(128, 'Sudanese pound ', 'SDG', ' ‫ج.س.‬', 45.150948, 2, 0, 1, '2019-07-01 19:14:14'),
(129, 'Swedish krona ', 'SEK', ' kr', 9.283587, 2, 0, 1, '2019-07-01 19:14:14'),
(130, 'Singapore dollar ', 'SGD', ' S$', 1.352802, 2, 0, 1, '2019-07-01 19:14:14'),
(131, 'Saint Helena pound ', 'SHP', ' £', 1.320903, 2, 0, 1, '2019-07-01 19:14:14'),
(132, 'Sierra Leonean leone ', 'SLL', ' Le', 8924.982144, 2, 0, 1, '2019-07-01 19:14:14'),
(133, 'Somali shilling ', 'SOS', ' Sh', 583.502479, 2, 0, 1, '2019-07-01 19:14:14'),
(134, 'Surinamese dollar ', 'SRD', ' Sr$', 7.458025, 2, 0, 1, '2019-07-01 19:14:14'),
(135, 'South Sudanese pound ', 'STD', ' £', 21560.746014, 2, 0, 1, '2019-07-01 19:14:14'),
(136, 'São Tomé and Príncipe dobra ', 'SVC', ' Db', 8.758135, 2, 0, 1, '2019-07-01 19:14:14'),
(137, 'Syrian pound ', 'SYP', ' £', 514.999291, 2, 0, 1, '2019-07-01 19:14:14'),
(138, 'Swazi lilangeni ', 'SZL', ' L', 14.090342, 2, 0, 1, '2019-07-01 19:14:14'),
(139, 'Thai baht ', 'THB', ' ฿', 30.631978, 2, 0, 1, '2019-07-01 19:14:14'),
(140, 'Tajikistani somoni ', 'TJS', ' ЅМ', 9.443087, 2, 0, 1, '2019-07-01 19:14:14'),
(141, 'Turkmenistan manat ', 'TMT', ' m', 3.499995, 2, 0, 1, '2019-07-01 19:14:14'),
(142, 'Tunisian dinar ', 'TND', ' د.ت', 2.878034, 3, 0, 1, '2019-07-01 19:14:14'),
(143, 'Tongan paʻanga ', 'TOP', ' T', 2.276647, 2, 0, 1, '2019-07-01 19:14:14'),
(144, 'Turkish lira ', 'TRY', ' ₺', 5.792095, 2, 0, 1, '2019-07-01 19:14:14'),
(145, 'Trinidad and Tobago dollar ', 'TTD', ' TT$', 6.774538, 2, 0, 1, '2019-07-01 19:14:14'),
(146, 'New Taiwan dollar ', 'TWD', ' NT$', 30.972978, 2, 0, 1, '2019-07-01 19:14:14'),
(147, 'Tanzanian shilling ', 'TZS', ' Sh', 2299.39895, 2, 0, 1, '2019-07-01 19:14:14'),
(148, 'Ukrainian hryvnia ', 'UAH', ' ₴', 26.177987, 2, 0, 1, '2019-07-01 19:14:14'),
(149, 'Ugandan shilling ', 'UGX', ' Sh', 3693.296104, 0, 0, 1, '2019-07-01 19:14:14'),
(150, 'United States dollar ', 'USD', ' US$', 1, 2, 0, 1, '2019-07-01 19:14:14'),
(151, 'Uruguayan peso ', 'UYU', ' $U', 35.240295, 2, 0, 1, '2019-07-01 19:14:14'),
(152, 'Uzbekistani soʻm ', 'UZS', ' сўм', 8554.982894, 2, 0, 1, '2019-07-01 19:14:14'),
(153, 'Venezuelan bolívar soberano ', 'VEF', ' Bs.', 9.987485, 2, 0, 1, '2019-07-01 19:14:14'),
(154, 'Vietnamese đồng ', 'VND', ' ₫', 23309.952478, 0, 0, 1, '2019-07-01 19:14:14'),
(155, 'Vanuatu vatu ', 'VUV', ' Vt', 114.07977, 0, 0, 1, '2019-07-01 19:14:14'),
(156, 'Samoan tālā ', 'WST', ' T', 2.656717, 2, 0, 1, '2019-07-01 19:14:14'),
(157, 'Central African CFA franc ', 'XAF', ' Fr', 576.809191, 0, 0, 1, '2019-07-01 19:14:14'),
(158, 'Silver Ounce ', 'XAG', ' oz', 0.065303, 0, 0, 1, '2019-07-01 19:14:14'),
(159, 'Gold Ounce ', 'XAU', ' oz', 0.000712, 0, 0, 1, '2019-07-01 19:14:14'),
(160, 'Eastern Caribbean dollar ', 'XCD', ' EC$', 2.702547, 2, 0, 1, '2019-07-01 19:14:14'),
(161, 'IMF Special Drawing Rights ', 'XDR', ' SDR', 0.719316, 0, 0, 1, '2019-07-01 19:14:14'),
(162, 'West African CFA franc ', 'XOF', ' Fr', 576.999157, 0, 0, 1, '2019-07-01 19:14:14'),
(163, 'CFP franc ', 'XPF', ' Fr', 105.303388, 0, 0, 1, '2019-07-01 19:14:14'),
(164, 'Yemeni rial ', 'YER', ' ﷼', 250.303089, 2, 0, 1, '2019-07-01 19:14:14'),
(165, 'South African rand ', 'ZAR', ' R', 14.086076, 2, 0, 1, '2019-07-01 19:14:14'),
(166, 'Zambian Kwacha ', 'ZMK', ' ZK', 9001.185245, 2, 0, 1, '2019-07-01 19:14:14'),
(167, 'Zambian kwacha ', 'ZMW', ' ZK', 12.83648, 2, 0, 1, '2019-07-01 19:14:14'),
(168, 'Zimbabwean dollar ', 'ZWL', ' Z$', 321.999345, 2, 0, 1, '2019-07-01 19:14:14'),
(169, 'Bosnia and Herzegovina convertible mark ', 'BAM', ' KM', 1.719803, 2, 0, 1, '2019-07-01 19:14:14');

-- --------------------------------------------------------

--
-- Table structure for table `customer`
--

CREATE TABLE `customer` (
  `customer_id` bigint(20) NOT NULL,
  `restaurant_uuid` char(60) COLLATE utf8_unicode_ci DEFAULT NULL,
  `customer_name` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `customer_phone_number` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `country_code` int(3) DEFAULT 965,
  `customer_email` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `customer_email_verification` tinyint(1) DEFAULT 0,
  `customer_limit_email` datetime DEFAULT NULL,
  `customer_auth_key` varchar(32) COLLATE utf8_unicode_ci DEFAULT NULL,
  `customer_new_email` varchar(100) COLLATE utf8_unicode_ci DEFAULT NULL,
  `customer_language_pref` char(2) COLLATE utf8_unicode_ci DEFAULT NULL,
  `customer_created_at` datetime NOT NULL,
  `customer_updated_at` datetime NOT NULL,
  `deleted` tinyint(1) DEFAULT 0,
  `civil_id` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `section` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `class` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `customer`
--

INSERT INTO `customer` (`customer_id`, `restaurant_uuid`, `customer_name`, `customer_phone_number`, `country_code`, `customer_email`, `customer_email_verification`, `customer_limit_email`, `customer_auth_key`, `customer_new_email`, `customer_language_pref`, `customer_created_at`, `customer_updated_at`, `deleted`, `civil_id`, `section`, `class`) VALUES
(1, '1', 'Constance', '518-248-9534 x334', 965, 'schimmel.darrell@kuhlman.com', 0, NULL, NULL, NULL, NULL, '1996-08-01 06:57:25', '1974-01-29 10:52:21', 0, NULL, NULL, NULL),
(2, NULL, 'Percival', '606.660.0351 x5138', 965, 'skuhic@oberbrunner.biz', 0, NULL, NULL, NULL, NULL, '2020-06-01 17:40:17', '1988-04-26 17:55:52', 0, NULL, NULL, NULL),
(3, NULL, 'Ellie', '379.744.2832', 965, 'mackenzie.raynor@dietrich.com', 0, NULL, NULL, NULL, NULL, '2002-05-30 18:45:24', '1977-11-09 08:14:52', 0, NULL, NULL, NULL),
(4, NULL, 'Gerry', '(998) 637-3696 x19479', 965, 'zstamm@nolan.com', 0, NULL, NULL, NULL, NULL, '2016-10-30 17:17:42', '2016-06-22 08:35:15', 0, NULL, NULL, NULL),
(5, NULL, 'Ulises', '1-597-558-4296 x66511', 965, 'selina.hahn@heaney.com', 0, NULL, NULL, NULL, NULL, '1984-05-18 11:04:27', '2020-09-23 13:17:58', 0, NULL, NULL, NULL),
(6, NULL, 'Lloyd', '1-734-572-4992 x4463', 965, 'beaulah.blanda@roberts.biz', 0, NULL, NULL, NULL, NULL, '1998-08-23 02:10:51', '1975-02-12 02:58:21', 0, NULL, NULL, NULL),
(7, NULL, 'Emilio', '1-757-324-8611 x6660', 965, 'turner.anahi@dach.com', 0, NULL, NULL, NULL, NULL, '2012-05-10 06:26:11', '1987-08-05 19:52:38', 0, NULL, NULL, NULL),
(8, NULL, 'Tess', '(928) 319-8150', 965, 'xbartell@smitham.com', 0, NULL, NULL, NULL, NULL, '2000-02-14 09:45:19', '2000-11-16 17:23:41', 0, NULL, NULL, NULL),
(9, NULL, 'Elroy', '+1.701.916.9677', 965, 'swift.adelia@swaniawski.com', 0, NULL, NULL, NULL, NULL, '2012-11-17 22:20:51', '1996-10-30 10:40:57', 0, NULL, NULL, NULL),
(10, NULL, 'Lauriane', '(918) 517-8957 x6593', 965, 'sgislason@crist.org', 0, NULL, NULL, NULL, NULL, '1993-07-08 10:39:40', '2004-12-02 22:34:12', 0, NULL, NULL, NULL),
(11, NULL, 'Jayden', '+1 (956) 921-8641', 965, 'wrau@oconner.net', 0, NULL, NULL, NULL, NULL, '2016-04-08 12:15:22', '1975-03-31 22:09:40', 0, NULL, NULL, NULL),
(12, NULL, 'Javier', '214.945.9029 x41681', 965, 'rohan.shanel@halvorson.com', 0, NULL, NULL, NULL, NULL, '1991-07-25 02:47:29', '1992-06-20 12:23:54', 0, NULL, NULL, NULL),
(13, NULL, 'Westley', '(829) 505-2465', 965, 'lavada22@rosenbaum.org', 0, NULL, NULL, NULL, NULL, '2019-06-20 04:15:04', '1993-07-30 21:43:55', 0, NULL, NULL, NULL),
(14, NULL, 'Diego', '(623) 390-3156 x370', 965, 'darrick.heaney@gorczany.com', 0, NULL, NULL, NULL, NULL, '1988-11-04 06:39:32', '2018-11-20 16:36:30', 0, NULL, NULL, NULL),
(15, NULL, 'Jazmyne', '1-747-273-8717 x2546', 965, 'kaleigh38@gerlach.org', 0, NULL, NULL, NULL, NULL, '1978-10-26 20:18:20', '1993-02-04 00:42:32', 0, NULL, NULL, NULL),
(16, NULL, 'Leonor', '(413) 754-3037', 965, 'heaven16@deckow.net', 0, NULL, NULL, NULL, NULL, '2006-11-23 17:30:05', '2007-02-04 17:02:25', 0, NULL, NULL, NULL),
(17, NULL, 'Marianna', '271-570-1571 x873', 965, 'alford.doyle@gaylord.com', 0, NULL, NULL, NULL, NULL, '1997-01-21 01:39:55', '2005-08-17 03:44:38', 0, NULL, NULL, NULL),
(18, NULL, 'Dario', '+16329310496', 965, 'gsauer@olson.com', 0, NULL, NULL, NULL, NULL, '2018-08-14 00:33:05', '1994-09-08 03:38:07', 0, NULL, NULL, NULL),
(19, NULL, 'Victoria', '1-514-500-9449 x829', 965, 'gschneider@legros.info', 0, NULL, NULL, NULL, NULL, '1997-08-30 09:31:57', '2002-01-24 03:52:54', 0, NULL, NULL, NULL),
(20, NULL, 'Paris', '261.684.8597 x12808', 965, 'tcartwright@bartoletti.org', 0, NULL, NULL, NULL, NULL, '1988-05-23 00:46:58', '2016-12-10 17:46:40', 0, NULL, NULL, NULL),
(21, NULL, 'Henri', '(887) 546-1210', 965, 'lauren91@keebler.biz', 0, NULL, NULL, NULL, NULL, '1973-08-24 18:12:39', '1988-12-03 14:03:29', 0, NULL, NULL, NULL),
(22, NULL, 'Rossie', '+1.675.409.1746', 965, 'ava.schroeder@corkery.com', 0, NULL, NULL, NULL, NULL, '2002-08-15 03:27:02', '1977-12-13 01:19:28', 0, NULL, NULL, NULL),
(23, NULL, 'Brooke', '507-220-1844', 965, 'lauretta.lind@zboncak.biz', 0, NULL, NULL, NULL, NULL, '1998-05-04 08:46:41', '1976-05-21 18:17:27', 0, NULL, NULL, NULL),
(24, NULL, 'Arch', '703.583.0758', 965, 'erdman.alicia@berge.biz', 0, NULL, NULL, NULL, NULL, '2003-02-10 22:04:37', '2019-06-25 20:33:38', 0, NULL, NULL, NULL),
(25, NULL, 'Barton', '796.690.0132', 965, 'rice.blake@dach.com', 0, NULL, NULL, NULL, NULL, '2020-03-28 01:27:08', '2014-11-17 15:23:17', 0, NULL, NULL, NULL),
(26, NULL, 'Dallin', '390.692.0018 x69711', 965, 'rhahn@bahringer.com', 0, NULL, NULL, NULL, NULL, '1993-01-14 13:12:42', '2009-01-26 00:35:48', 0, NULL, NULL, NULL),
(27, NULL, 'Luther', '(207) 601-3675 x07745', 965, 'graciela34@daniel.com', 0, NULL, NULL, NULL, NULL, '1992-04-25 02:25:53', '2012-12-23 02:28:07', 0, NULL, NULL, NULL),
(28, NULL, 'Xavier', '589-714-9123 x4915', 965, 'evalyn.bode@lehner.com', 0, NULL, NULL, NULL, NULL, '1974-04-04 08:55:39', '1971-10-14 20:51:30', 0, NULL, NULL, NULL),
(29, NULL, 'Madie', '561.366.2230', 965, 'kara80@wehner.com', 0, NULL, NULL, NULL, NULL, '2018-02-27 21:22:33', '1981-09-07 20:27:16', 0, NULL, NULL, NULL),
(30, NULL, 'Yasmine', '+1-990-851-6035', 965, 'bergnaum.hope@will.com', 0, NULL, NULL, NULL, NULL, '2010-03-11 00:27:49', '1987-03-07 05:39:45', 0, NULL, NULL, NULL),
(31, NULL, 'Meta', '253-448-4493 x065', 965, 'cummerata.gordon@leffler.org', 0, NULL, NULL, NULL, NULL, '1987-09-26 08:39:38', '2018-04-06 20:00:59', 0, NULL, NULL, NULL),
(32, NULL, 'Sallie', '851-362-8410', 965, 'marjorie66@jerde.com', 0, NULL, NULL, NULL, NULL, '1972-09-01 09:28:36', '1995-02-06 00:05:37', 0, NULL, NULL, NULL),
(33, NULL, 'Jeanne', '+1 (535) 246-6509', 965, 'gideon.moen@nikolaus.info', 0, NULL, NULL, NULL, NULL, '2015-12-27 11:03:46', '2018-05-27 02:30:02', 0, NULL, NULL, NULL),
(34, NULL, 'Devan', '(893) 534-0574 x494', 965, 'fabernathy@russel.com', 0, NULL, NULL, NULL, NULL, '1999-04-20 05:46:32', '1996-08-28 21:07:12', 0, NULL, NULL, NULL),
(35, NULL, 'Serena', '1-898-341-0165', 965, 'eusebio.cassin@spinka.com', 0, NULL, NULL, NULL, NULL, '1981-02-22 11:36:48', '1982-06-17 14:19:38', 0, NULL, NULL, NULL),
(36, NULL, 'Juvenal', '1-592-224-6045', 965, 'kiley45@fahey.com', 0, NULL, NULL, NULL, NULL, '1980-03-07 10:53:34', '2012-11-22 22:04:18', 0, NULL, NULL, NULL),
(37, NULL, 'Raoul', '+13185076439', 965, 'ceasar72@breitenberg.com', 0, NULL, NULL, NULL, NULL, '1980-04-04 13:27:38', '1970-01-23 05:41:11', 0, NULL, NULL, NULL),
(38, NULL, 'June', '+1-695-239-8656', 965, 'skonopelski@schoen.net', 0, NULL, NULL, NULL, NULL, '2006-11-12 07:23:33', '1997-01-17 03:15:09', 0, NULL, NULL, NULL),
(39, NULL, 'Jacinthe', '473.266.5266 x3125', 965, 'ybrakus@block.org', 0, NULL, NULL, NULL, NULL, '2014-02-06 09:24:08', '2009-08-13 09:37:11', 0, NULL, NULL, NULL),
(40, NULL, 'Lee', '805.252.4418 x538', 965, 'rae.will@langosh.biz', 0, NULL, NULL, NULL, NULL, '2018-10-01 11:04:46', '1980-04-14 08:54:28', 0, NULL, NULL, NULL),
(41, NULL, 'Forrest', '528.214.1251', 965, 'katelynn.morar@grimes.info', 0, NULL, NULL, NULL, NULL, '1970-06-25 09:06:24', '2013-01-07 18:51:03', 0, NULL, NULL, NULL),
(42, NULL, 'Maiya', '+19825357969', 965, 'lilla21@beier.biz', 0, NULL, NULL, NULL, NULL, '2002-04-18 17:54:00', '1975-01-12 16:16:12', 0, NULL, NULL, NULL),
(43, NULL, 'Natalia', '426.228.8308 x081', 965, 'eichmann.abe@shields.com', 0, NULL, NULL, NULL, NULL, '2007-09-05 14:02:13', '1990-08-30 13:37:25', 0, NULL, NULL, NULL),
(44, NULL, 'Roy', '861.689.7108 x41907', 965, 'xrenner@hegmann.info', 0, NULL, NULL, NULL, NULL, '1985-11-17 05:37:53', '1990-06-03 00:05:58', 0, NULL, NULL, NULL),
(45, NULL, 'Beryl', '693-321-1654', 965, 'vgusikowski@hoeger.biz', 0, NULL, NULL, NULL, NULL, '2010-01-02 21:10:51', '2017-06-14 14:43:01', 0, NULL, NULL, NULL),
(46, NULL, 'Alexandra', '845-424-7289', 965, 'ibarrows@abshire.com', 0, NULL, NULL, NULL, NULL, '1989-03-29 06:58:10', '1993-11-12 10:26:15', 0, NULL, NULL, NULL),
(47, NULL, 'Gayle', '+1-446-344-4303', 965, 'howe.pattie@beer.com', 0, NULL, NULL, NULL, NULL, '1985-09-15 08:30:20', '2005-01-06 22:24:48', 0, NULL, NULL, NULL),
(48, NULL, 'Laurie', '391.281.1246', 965, 'lhaley@walker.com', 0, NULL, NULL, NULL, NULL, '1998-05-01 16:54:36', '1981-03-28 04:31:49', 0, NULL, NULL, NULL),
(49, NULL, 'Vicente', '450-477-7973 x06727', 965, 'reagan31@mann.info', 0, NULL, NULL, NULL, NULL, '2019-07-29 06:13:04', '1989-02-03 15:41:03', 0, NULL, NULL, NULL),
(50, NULL, 'Lulu', '1-652-474-9628', 965, 'west.nels@lynch.net', 0, NULL, NULL, NULL, NULL, '1975-09-01 16:07:17', '1977-08-11 22:39:10', 0, NULL, NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `customer_bank_discount`
--

CREATE TABLE `customer_bank_discount` (
  `customer_bank_discount_id` bigint(20) NOT NULL,
  `customer_id` bigint(20) DEFAULT NULL,
  `bank_discount_id` bigint(20) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `customer_bank_discount`
--

INSERT INTO `customer_bank_discount` (`customer_bank_discount_id`, `customer_id`, `bank_discount_id`) VALUES
(1, 1, 1),
(2, 2, 2),
(3, 3, 3),
(4, 4, 4),
(5, 5, 5),
(6, 6, 6),
(7, 7, 7),
(8, 8, 8),
(9, 9, 9),
(10, 10, 10),
(11, 11, 11),
(12, 12, 12),
(13, 13, 13),
(14, 14, 14),
(15, 15, 15),
(16, 16, 16),
(17, 17, 17),
(18, 18, 18),
(19, 19, 19),
(20, 20, 20),
(21, 21, 21),
(22, 22, 22),
(23, 23, 23),
(24, 24, 24),
(25, 25, 25),
(26, 26, 26),
(27, 27, 27),
(28, 28, 28),
(29, 29, 29),
(30, 30, 30),
(31, 31, 31),
(32, 32, 32),
(33, 33, 33),
(34, 34, 34),
(35, 35, 35),
(36, 36, 36),
(37, 37, 37),
(38, 38, 38),
(39, 39, 39),
(40, 40, 40),
(41, 41, 41),
(42, 42, 42),
(43, 43, 43),
(44, 44, 44),
(45, 45, 45),
(46, 46, 46),
(47, 47, 47),
(48, 48, 48),
(49, 49, 49),
(50, 50, 50);

-- --------------------------------------------------------

--
-- Table structure for table `customer_campaign`
--

CREATE TABLE `customer_campaign` (
  `campaign_uuid` char(60) COLLATE utf8_unicode_ci NOT NULL,
  `template_uuid` char(60) COLLATE utf8_unicode_ci NOT NULL,
  `restaurant_uuid` char(60) COLLATE utf8_unicode_ci DEFAULT NULL,
  `progress` int(11) DEFAULT 0,
  `status` tinyint(1) DEFAULT 0,
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `customer_email_template`
--

CREATE TABLE `customer_email_template` (
  `template_uuid` char(60) COLLATE utf8_unicode_ci NOT NULL,
  `restaurant_uuid` char(60) COLLATE utf8_unicode_ci DEFAULT NULL,
  `subject` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `message` text COLLATE utf8_unicode_ci DEFAULT NULL,
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `customer_email_verify_attempt`
--

CREATE TABLE `customer_email_verify_attempt` (
  `ceva_uuid` char(60) COLLATE utf8mb4_unicode_ci NOT NULL,
  `customer_email` varchar(50) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `code` varchar(32) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `ip_address` varchar(45) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `customer_voucher`
--

CREATE TABLE `customer_voucher` (
  `customer_voucher_id` bigint(20) NOT NULL,
  `customer_id` bigint(20) DEFAULT NULL,
  `voucher_id` bigint(20) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `customer_voucher`
--

INSERT INTO `customer_voucher` (`customer_voucher_id`, `customer_id`, `voucher_id`) VALUES
(1, 1, 1),
(2, 2, 2),
(3, 3, 3),
(4, 4, 4),
(5, 5, 5),
(6, 6, 6),
(7, 7, 7),
(8, 8, 8),
(9, 9, 9),
(10, 10, 10),
(11, 11, 11),
(12, 12, 12),
(13, 13, 13),
(14, 14, 14),
(15, 15, 15),
(16, 16, 16),
(17, 17, 17),
(18, 18, 18),
(19, 19, 19),
(20, 20, 20),
(21, 21, 21),
(22, 22, 22),
(23, 23, 23),
(24, 24, 24),
(25, 25, 25),
(26, 26, 26),
(27, 27, 27),
(28, 28, 28),
(29, 29, 29),
(30, 30, 30),
(31, 31, 31),
(32, 32, 32),
(33, 33, 33),
(34, 34, 34),
(35, 35, 35),
(36, 36, 36),
(37, 37, 37),
(38, 38, 38),
(39, 39, 39),
(40, 40, 40),
(41, 41, 41),
(42, 42, 42),
(43, 43, 43),
(44, 44, 44),
(45, 45, 45),
(46, 46, 46),
(47, 47, 47),
(48, 48, 48),
(49, 49, 49),
(50, 50, 50);

-- --------------------------------------------------------

--
-- Table structure for table `delivery_zone`
--

CREATE TABLE `delivery_zone` (
  `delivery_zone_id` bigint(20) NOT NULL,
  `country_id` int(11) NOT NULL,
  `restaurant_uuid` char(60) COLLATE utf8_unicode_ci NOT NULL,
  `business_location_id` bigint(20) NOT NULL,
  `delivery_time` int(11) UNSIGNED DEFAULT 60,
  `delivery_fee` decimal(10,3) UNSIGNED DEFAULT 0.000,
  `min_charge` decimal(10,3) UNSIGNED DEFAULT 0.000,
  `delivery_zone_tax` float UNSIGNED DEFAULT NULL,
  `time_unit` char(3) COLLATE utf8_unicode_ci NOT NULL DEFAULT 'min',
  `is_deleted` tinyint(1) DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `delivery_zone`
--

INSERT INTO `delivery_zone` (`delivery_zone_id`, `country_id`, `restaurant_uuid`, `business_location_id`, `delivery_time`, `delivery_fee`, `min_charge`, `delivery_zone_tax`, `time_unit`, `is_deleted`) VALUES
(1, 1, '1', 1, 11, '28.000', '12.000', 31, 'hrs', 0),
(2, 2, '2', 2, 11, '100.000', '57.000', 93, 'hrs', 0),
(3, 3, '3', 3, 11, '57.000', '78.000', 29, 'hrs', 0),
(4, 4, '4', 4, 11, '79.000', '88.000', 60, 'hrs', 0),
(5, 5, '5', 5, 11, '96.000', '53.000', 78, 'hrs', 0),
(6, 6, '6', 6, 11, '29.000', '99.000', 38, 'hrs', 0),
(7, 7, '7', 7, 11, '88.000', '40.000', 95, 'hrs', 0),
(8, 8, '8', 8, 11, '62.000', '90.000', 41, 'hrs', 0),
(9, 9, '9', 9, 11, '22.000', '63.000', 54, 'hrs', 0),
(10, 10, '10', 10, 11, '16.000', '37.000', 70, 'hrs', 0),
(11, 11, '11', 11, 11, '59.000', '47.000', 66, 'hrs', 0),
(12, 12, '12', 12, 11, '41.000', '97.000', 49, 'hrs', 0),
(13, 13, '13', 13, 11, '52.000', '56.000', 17, 'hrs', 0),
(14, 14, '14', 14, 11, '97.000', '67.000', 21, 'hrs', 0),
(15, 15, '15', 15, 11, '100.000', '38.000', 98, 'hrs', 0),
(16, 16, '16', 16, 11, '80.000', '57.000', 16, 'hrs', 0),
(17, 17, '17', 17, 11, '67.000', '66.000', 55, 'hrs', 0),
(18, 18, '18', 18, 11, '26.000', '60.000', 42, 'hrs', 0),
(19, 19, '19', 19, 11, '78.000', '47.000', 81, 'hrs', 0),
(20, 20, '20', 20, 11, '80.000', '24.000', 34, 'hrs', 0),
(21, 21, '21', 21, 11, '52.000', '40.000', 76, 'hrs', 0),
(22, 22, '22', 22, 11, '21.000', '40.000', 80, 'hrs', 0),
(23, 23, '23', 23, 11, '61.000', '76.000', 13, 'hrs', 0),
(24, 24, '24', 24, 11, '98.000', '66.000', 17, 'hrs', 0),
(25, 25, '25', 25, 11, '25.000', '95.000', 89, 'hrs', 0),
(26, 26, '26', 26, 11, '38.000', '27.000', 48, 'hrs', 0),
(27, 27, '27', 27, 11, '40.000', '33.000', 61, 'hrs', 0),
(28, 28, '28', 28, 11, '92.000', '14.000', 22, 'hrs', 0),
(29, 29, '29', 29, 11, '34.000', '26.000', 61, 'hrs', 0),
(30, 30, '30', 30, 11, '11.000', '20.000', 18, 'hrs', 0),
(31, 31, '31', 31, 11, '88.000', '94.000', 42, 'hrs', 0),
(32, 32, '32', 32, 11, '87.000', '68.000', 50, 'hrs', 0),
(33, 33, '33', 33, 11, '77.000', '48.000', 15, 'hrs', 0),
(34, 34, '34', 34, 11, '60.000', '57.000', 88, 'hrs', 0),
(35, 35, '35', 35, 11, '38.000', '93.000', 38, 'hrs', 0),
(36, 36, '36', 36, 11, '53.000', '44.000', 78, 'hrs', 0),
(37, 37, '37', 37, 11, '90.000', '64.000', 20, 'hrs', 0),
(38, 38, '38', 38, 11, '84.000', '53.000', 38, 'hrs', 0),
(39, 39, '39', 39, 11, '46.000', '95.000', 14, 'hrs', 0),
(40, 40, '40', 40, 11, '75.000', '52.000', 57, 'hrs', 0),
(41, 41, '41', 41, 11, '39.000', '23.000', 85, 'hrs', 0),
(42, 42, '42', 42, 11, '48.000', '19.000', 58, 'hrs', 0),
(43, 43, '43', 43, 11, '86.000', '27.000', 15, 'hrs', 0),
(44, 44, '44', 44, 11, '62.000', '26.000', 67, 'hrs', 0),
(45, 45, '45', 45, 11, '65.000', '46.000', 83, 'hrs', 0),
(46, 46, '46', 46, 11, '20.000', '76.000', 88, 'hrs', 0),
(47, 47, '47', 47, 11, '83.000', '14.000', 80, 'hrs', 0),
(48, 48, '48', 48, 11, '74.000', '30.000', 20, 'hrs', 0),
(49, 49, '49', 49, 11, '78.000', '77.000', 55, 'hrs', 0),
(50, 50, '50', 50, 11, '70.000', '83.000', 65, 'hrs', 0);

-- --------------------------------------------------------

--
-- Table structure for table `extra_option`
--

CREATE TABLE `extra_option` (
  `extra_option_id` int(11) NOT NULL,
  `option_id` int(11) DEFAULT NULL,
  `extra_option_name` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `extra_option_name_ar` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `extra_option_price` float UNSIGNED DEFAULT 0,
  `stock_qty` int(11) UNSIGNED DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `extra_option`
--

INSERT INTO `extra_option` (`extra_option_id`, `option_id`, `extra_option_name`, `extra_option_name_ar`, `extra_option_price`, `stock_qty`) VALUES
(1103, 0, 'Eleanore', 'Timmy', 2, NULL),
(1154, 0, 'Gunnar', 'Joanie', 1, NULL),
(2381, 0, 'Mozelle', 'Kade', 3, NULL),
(5389, 0, 'Ellen', 'Gladys', 1, NULL),
(6537, 0, 'Leslie', 'Jasper', 2, NULL),
(8027, 0, 'Laura', 'Richard', 2, NULL),
(11584, 0, 'Nathanial', 'Lauryn', 3, NULL),
(12290, 0, 'Lucas', 'Dawson', 5, NULL),
(13206, 0, 'Everette', 'Eduardo', 2, NULL),
(17716, 0, 'Dalton', 'Rae', 3, NULL),
(18862, 0, 'Winnifred', 'Monte', 1, NULL),
(18942, 0, 'Samanta', 'Liza', 1, NULL),
(19527, 0, 'Quinten', 'Monserrate', 1, NULL),
(24647, 0, 'Mavis', 'Agustina', 3, NULL),
(25312, 0, 'Lurline', 'Yvonne', 5, NULL),
(29676, 0, 'Alexandro', 'Joanie', 1, NULL),
(31586, 0, 'Jerrell', 'Angelica', 5, NULL),
(35157, 0, 'Horace', 'Adrianna', 1, NULL),
(42806, 0, 'Christina', 'Shanny', 2, NULL),
(44431, 0, 'Jamey', 'Remington', 4, NULL),
(48124, 0, 'Cassie', 'Hudson', 3, NULL),
(50116, 0, 'Adaline', 'Dino', 3, NULL),
(50892, 0, 'Kurtis', 'Jaclyn', 3, NULL),
(51027, 0, 'Zelma', 'Martina', 4, NULL),
(51963, 0, 'Brittany', 'Elta', 3, NULL),
(52321, 0, 'Georgette', 'Justina', 3, NULL),
(52364, 0, 'Marlin', 'Helene', 3, NULL),
(53215, 0, 'Marcel', 'Luciano', 4, NULL),
(56150, 0, 'Arielle', 'Fabiola', 3, NULL),
(56883, 0, 'Arch', 'Ethelyn', 5, NULL),
(58003, 0, 'Roel', 'Hazle', 5, NULL),
(59466, 0, 'Terence', 'Lois', 3, NULL),
(59894, 0, 'Lexie', 'Trey', 3, NULL),
(61766, 0, 'Malika', 'Daren', 4, NULL),
(62432, 0, 'Winston', 'Madonna', 3, NULL),
(63297, 0, 'Alana', 'Ida', 3, NULL),
(63670, 0, 'Jazmyne', 'Kamille', 4, NULL),
(65712, 0, 'Tevin', 'Hermann', 1, NULL),
(69983, 0, 'Moises', 'Viviane', 5, NULL),
(71865, 0, 'Horacio', 'Myrtice', 5, NULL),
(74837, 0, 'Cletus', 'Nathen', 3, NULL),
(76769, 0, 'Malachi', 'Rosalyn', 3, NULL),
(78101, 0, 'Juliet', 'Stephania', 3, NULL),
(78411, 0, 'Geo', 'Broderick', 2, NULL),
(81846, 0, 'Shayna', 'Vinnie', 2, NULL),
(81923, 0, 'Marcelo', 'Cristian', 3, NULL),
(87625, 0, 'Hallie', 'Jamir', 3, NULL),
(90495, 0, 'Maybell', 'Aaron', 4, NULL),
(92133, 0, 'Wilma', 'Raoul', 3, NULL),
(95710, 0, 'Guido', 'Ivah', 4, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `invoice_item`
--

CREATE TABLE `invoice_item` (
  `invoice_item_uuid` char(60) COLLATE utf8_unicode_ci NOT NULL,
  `restaurant_uuid` char(60) COLLATE utf8_unicode_ci DEFAULT NULL,
  `invoice_uuid` char(60) COLLATE utf8_unicode_ci DEFAULT NULL,
  `plan_id` int(11) DEFAULT NULL,
  `addon_uuid` char(60) COLLATE utf8_unicode_ci DEFAULT NULL,
  `order_uuid` char(40) COLLATE utf8_unicode_ci DEFAULT NULL,
  `comment` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `total` decimal(10,3) NOT NULL,
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `invoice_payment`
--

CREATE TABLE `invoice_payment` (
  `payment_uuid` char(60) COLLATE utf8_unicode_ci NOT NULL,
  `restaurant_uuid` char(60) COLLATE utf8_unicode_ci DEFAULT NULL,
  `invoice_uuid` char(60) COLLATE utf8_unicode_ci DEFAULT NULL,
  `payment_gateway_transaction_id` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `payment_mode` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `payment_current_status` text COLLATE utf8_unicode_ci DEFAULT NULL,
  `payment_amount_charged` decimal(10,3) NOT NULL,
  `payment_net_amount` decimal(10,3) DEFAULT NULL,
  `payment_gateway_fee` decimal(10,3) DEFAULT NULL,
  `currency_code` char(3) COLLATE utf8_unicode_ci DEFAULT 'KWD',
  `received_callback` tinyint(1) NOT NULL DEFAULT 0,
  `is_sandbox` tinyint(1) DEFAULT 0,
  `payment_created_at` datetime DEFAULT NULL,
  `payment_updated_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `item`
--

CREATE TABLE `item` (
  `item_uuid` varchar(300) COLLATE utf8_unicode_ci NOT NULL,
  `restaurant_uuid` char(60) COLLATE utf8_unicode_ci NOT NULL,
  `item_name` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `item_name_ar` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `item_description` varchar(2000) COLLATE utf8_unicode_ci DEFAULT NULL,
  `item_description_ar` varchar(2000) COLLATE utf8_unicode_ci DEFAULT NULL,
  `item_meta_title` text COLLATE utf8_unicode_ci DEFAULT NULL,
  `item_meta_title_ar` text COLLATE utf8_unicode_ci DEFAULT NULL,
  `item_meta_description` text COLLATE utf8_unicode_ci DEFAULT NULL,
  `item_meta_description_ar` text COLLATE utf8_unicode_ci DEFAULT NULL,
  `sort_number` int(11) UNSIGNED DEFAULT NULL,
  `item_type` tinyint(1) DEFAULT 1,
  `stock_qty` int(11) UNSIGNED DEFAULT 0,
  `track_quantity` tinyint(1) DEFAULT 1,
  `sku` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `barcode` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `unit_sold` int(11) UNSIGNED DEFAULT 0,
  `item_image` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `item_price` decimal(10,3) UNSIGNED NOT NULL DEFAULT 0.000,
  `compare_at_price` decimal(10,3) UNSIGNED DEFAULT NULL,
  `item_created_at` datetime DEFAULT NULL,
  `item_updated_at` datetime DEFAULT NULL,
  `item_status` tinyint(1) UNSIGNED DEFAULT 1,
  `slug` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `prep_time` int(11) DEFAULT 0,
  `prep_time_unit` char(3) COLLATE utf8_unicode_ci NOT NULL DEFAULT 'min'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `item`
--

INSERT INTO `item` (`item_uuid`, `restaurant_uuid`, `item_name`, `item_name_ar`, `item_description`, `item_description_ar`, `item_meta_title`, `item_meta_title_ar`, `item_meta_description`, `item_meta_description_ar`, `sort_number`, `item_type`, `stock_qty`, `track_quantity`, `sku`, `barcode`, `unit_sold`, `item_image`, `item_price`, `compare_at_price`, `item_created_at`, `item_updated_at`, `item_status`, `slug`, `prep_time`, `prep_time_unit`) VALUES
('1', '1', 'Elyssa', 'Nathen', 'Minima illo corrupti commodi doloribus cumque.', 'Distinctio est maiores animi.', NULL, NULL, NULL, NULL, 1, 1, 15, 1, 'sint', 'PL84633401456030273094757429', 118, '8e1lUGUnUKbjFbq2ZNbd80Pg9xYkLrLs.png', '3.000', '5.000', '2022-12-19 11:39:06', '1998-08-14 06:34:39', 10, 'facere-recusandae-in-tempora-ea-omnis', 11, 'hrs'),
('10', '10', 'Syble', 'Murphy', 'Mollitia et odit dolor aut.', 'Et qui dolorem.', NULL, NULL, NULL, NULL, 2, 1, 13, 10, 'blanditiis', 'MC3432815922303V75TVX0H8S89', 167, '8e1lUGUnUKbjFbq2ZNbd80Pg9xYkLrLs.png', '2.000', '5.000', '2011-07-19 10:45:39', '1975-12-22 12:36:49', 10, 'et-praesentium-et-qui-fugiat-quas-quam', 11, 'hrs'),
('11', '11', 'Verna', 'Madisyn', 'Ea in officia quibusdam.', 'Ipsam repudiandae consequatur necessitatibus laudantium.', NULL, NULL, NULL, NULL, 5, 1, 9, 10, 'consequuntur', 'LT855603023180761148', 190, '8e1lUGUnUKbjFbq2ZNbd80Pg9xYkLrLs.png', '4.000', '4.000', '2007-04-23 17:16:51', '1996-07-08 19:22:55', 10, 'aut-dolores-nobis-aut', 11, 'hrs'),
('12', '12', 'Kasandra', 'Laura', 'Perspiciatis vitae dolore.', 'Dolore dicta aut eligendi.', NULL, NULL, NULL, NULL, 1, 1, 4, 14, 'autem', 'PT81669882070914404415485', 179, '8e1lUGUnUKbjFbq2ZNbd80Pg9xYkLrLs.png', '1.000', '4.000', '1972-11-13 12:29:10', '1979-11-07 02:53:55', 10, 'ipsam-eos-sunt-accusamus-eos-nostrum', 11, 'hrs'),
('13', '13', 'Mazie', 'Newton', 'Vitae inventore voluptatibus nam.', 'Velit in voluptatem est temporibus.', NULL, NULL, NULL, NULL, 4, 1, 14, 8, 'impedit', 'LI845857311O5X17R0N9L', 172, '8e1lUGUnUKbjFbq2ZNbd80Pg9xYkLrLs.png', '1.000', '2.000', '1985-05-24 13:11:57', '1999-04-09 10:40:08', 10, 'sint-ut-aperiam-animi-doloribus-animi-rerum', 11, 'hrs'),
('14', '14', 'Okey', 'Heidi', 'Quo voluptatibus quae sed.', 'Qui pariatur et eum quibusdam.', NULL, NULL, NULL, NULL, 4, 1, 14, 4, 'deleniti', 'GI07LLHYC433D2WI8THU1R4', 182, '8e1lUGUnUKbjFbq2ZNbd80Pg9xYkLrLs.png', '4.000', '4.000', '1971-09-08 17:19:59', '1991-10-27 12:28:14', 10, 'ex-quia-veniam-consequatur-quaerat-laudantium-consequatur-odit-harum', 11, 'hrs'),
('15', '15', 'Alf', 'Demetrius', 'Deleniti veniam quis consequatur ducimus.', 'Tempora animi autem.', NULL, NULL, NULL, NULL, 4, 1, 13, 1, 'minus', 'DO15FZ4341422233188685875550', 141, '8e1lUGUnUKbjFbq2ZNbd80Pg9xYkLrLs.png', '4.000', '5.000', '1983-12-23 21:43:26', '1982-04-25 02:14:58', 10, 'voluptatem-harum-exercitationem-et-magni-nihil-consequatur-ut', 11, 'hrs'),
('16', '16', 'Bailey', 'Gwen', 'Quia non voluptatem quod dolores at.', 'Dolorum ut aut provident.', NULL, NULL, NULL, NULL, 3, 1, 11, 4, 'minima', 'IT27C73340423141Y04K948Y879', 125, '8e1lUGUnUKbjFbq2ZNbd80Pg9xYkLrLs.png', '3.000', '3.000', '1997-05-06 00:41:53', '1983-11-14 23:27:11', 10, 'beatae-ratione-voluptas-laudantium-delectus-omnis', 11, 'hrs'),
('17', '17', 'Magali', 'Irving', 'Harum explicabo commodi dolores enim.', 'Dolor quidem sequi.', NULL, NULL, NULL, NULL, 5, 1, 6, 13, 'corporis', 'AD5330302180Z1W7D2AWJ409', 114, '8e1lUGUnUKbjFbq2ZNbd80Pg9xYkLrLs.png', '2.000', '4.000', '1971-10-23 06:09:00', '1990-05-23 01:11:06', 10, 'accusamus-esse-beatae-unde-magni', 11, 'hrs'),
('18', '18', 'Jamey', 'Lisandro', 'Qui sit aut dolorum laborum.', 'Et labore nulla architecto.', NULL, NULL, NULL, NULL, 1, 1, 8, 9, 'quae', 'IE20IOYK06360530428894', 100, '8e1lUGUnUKbjFbq2ZNbd80Pg9xYkLrLs.png', '3.000', '3.000', '2021-11-13 01:26:03', '2022-04-25 18:09:31', 10, 'qui-ea-vel-id-explicabo-aut-quis-sunt-excepturi', 11, 'hrs'),
('19', '19', 'Kenyatta', 'Darren', 'Quia consequatur repellendus rerum.', 'Voluptas et laborum ut sunt.', NULL, NULL, NULL, NULL, 1, 1, 12, 9, 'ut', 'SM16D8747395848ZYK87447ET8L', 157, '8e1lUGUnUKbjFbq2ZNbd80Pg9xYkLrLs.png', '4.000', '3.000', '1974-04-05 18:07:45', '1992-11-19 18:33:06', 10, 'minima-harum-eum-est-odit-odio', 11, 'hrs'),
('2', '1', 'Wallace', 'Gonzalo', 'Quo adipisci quo ipsum et.', 'Molestiae nihil rem molestiae.', NULL, NULL, NULL, NULL, 3, 1, 7, 15, 'ratione', 'LU55318X70LK677571M9', 101, '8e1lUGUnUKbjFbq2ZNbd80Pg9xYkLrLs.png', '1.000', '4.000', '1976-09-09 19:47:42', '1974-10-01 19:16:32', 10, 'optio-qui-architecto-recusandae', 11, 'hrs'),
('20', '20', 'Ben', 'Terry', 'Laborum molestiae alias autem.', 'Vel repudiandae fuga laudantium hic.', NULL, NULL, NULL, NULL, 1, 1, 6, 15, 'maiores', 'LB087022804SV3QIERX655934727', 148, '8e1lUGUnUKbjFbq2ZNbd80Pg9xYkLrLs.png', '4.000', '3.000', '1996-12-25 04:06:37', '1990-11-30 14:58:38', 10, 'ut-et-consequatur-quis-aut-libero-maiores-ut', 11, 'hrs'),
('21', '21', 'Laron', 'Maude', 'Nihil ut veniam atque ex.', 'Dolor quia autem.', NULL, NULL, NULL, NULL, 1, 1, 10, 7, 'et', 'BR6270508744391010904069388R2', 193, '8e1lUGUnUKbjFbq2ZNbd80Pg9xYkLrLs.png', '1.000', '3.000', '2017-06-03 10:32:23', '1972-02-19 15:18:34', 10, 'aut-laboriosam-illo-repellendus-illo-debitis-fugiat-officia-quia', 11, 'hrs'),
('22', '22', 'Letha', 'Sierra', 'Dolor nobis repudiandae.', 'Temporibus laborum sunt voluptatem qui.', NULL, NULL, NULL, NULL, 1, 1, 13, 9, 'alias', 'MR7150769377609540516455284', 143, '8e1lUGUnUKbjFbq2ZNbd80Pg9xYkLrLs.png', '3.000', '4.000', '1993-01-13 16:31:52', '1983-04-28 23:03:03', 10, 'corporis-ullam-placeat-cupiditate-dolores-et-ut-temporibus', 11, 'hrs'),
('23', '23', 'Darius', 'Kenna', 'Suscipit in qui.', 'Natus voluptate quis eos fugit.', NULL, NULL, NULL, NULL, 5, 1, 9, 4, 'aut', 'IS769045990534045480326065', 188, '8e1lUGUnUKbjFbq2ZNbd80Pg9xYkLrLs.png', '5.000', '2.000', '1993-06-29 06:34:06', '2020-04-26 07:03:17', 10, 'doloremque-repudiandae-nisi-sed-nulla-iure', 11, 'hrs'),
('24', '24', 'Angeline', 'Merl', 'Nostrum et quidem eaque quod.', 'Quas accusantium quas eum fuga consequatur.', NULL, NULL, NULL, NULL, 3, 1, 5, 6, 'dolore', 'BR7198484986969575872787362A5', 108, '8e1lUGUnUKbjFbq2ZNbd80Pg9xYkLrLs.png', '3.000', '2.000', '2010-05-24 15:42:04', '1975-05-19 18:40:29', 10, 'et-nemo-aut-illum-dolorem-esse-recusandae-repellat-est', 11, 'hrs'),
('25', '25', 'Kylie', 'Andreane', 'Doloremque esse natus.', 'Natus culpa deleniti sed beatae repellendus.', NULL, NULL, NULL, NULL, 5, 1, 12, 10, 'consequatur', 'HR5036368809804347622', 153, '8e1lUGUnUKbjFbq2ZNbd80Pg9xYkLrLs.png', '5.000', '3.000', '2001-09-26 17:59:13', '2004-11-25 17:25:19', 10, 'quis-pariatur-laboriosam-dolorem-nisi-itaque-in-qui-quae', 11, 'hrs'),
('26', '26', 'Kiarra', 'Verona', 'Reiciendis consequatur distinctio architecto.', 'Hic quod nesciunt.', NULL, NULL, NULL, NULL, 3, 1, 2, 1, 'nostrum', 'NL22XRHY1568218421', 139, '8e1lUGUnUKbjFbq2ZNbd80Pg9xYkLrLs.png', '5.000', '1.000', '2003-07-05 16:31:27', '1970-02-28 10:41:11', 10, 'enim-corporis-delectus-ducimus-deserunt', 11, 'hrs'),
('27', '27', 'Murl', 'Deion', 'Ut dolorum provident qui eaque dolores.', 'Nulla hic rerum magni.', NULL, NULL, NULL, NULL, 4, 1, 13, 10, 'in', 'SE8980044592253588102897', 130, '8e1lUGUnUKbjFbq2ZNbd80Pg9xYkLrLs.png', '4.000', '1.000', '1979-05-02 01:58:25', '2003-05-05 21:25:25', 10, 'aut-nulla-dolores-pariatur-aut-soluta-exercitationem-asperiores', 11, 'hrs'),
('28', '28', 'Brett', 'Cynthia', 'Architecto nihil hic quisquam earum.', 'Asperiores id nam molestias molestias exercitationem.', NULL, NULL, NULL, NULL, 1, 1, 15, 4, 'accusamus', 'BA873755403977536927', 151, '8e1lUGUnUKbjFbq2ZNbd80Pg9xYkLrLs.png', '4.000', '3.000', '2001-01-31 00:18:18', '1999-05-07 03:59:16', 10, 'consequatur-est-ad-qui-placeat-minus-esse-sequi-enim', 11, 'hrs'),
('29', '29', 'Camron', 'Ottilie', 'Voluptatem enim quae nulla.', 'Repudiandae ex quae explicabo.', NULL, NULL, NULL, NULL, 4, 1, 2, 2, 'labore', 'IT18B4226832778H908945T1O2F', 183, '8e1lUGUnUKbjFbq2ZNbd80Pg9xYkLrLs.png', '5.000', '1.000', '2012-10-23 23:05:16', '1974-05-04 07:56:56', 10, 'in-esse-architecto-sed-et', 11, 'hrs'),
('3', '3', 'Jairo', 'Ransom', 'Illo consequuntur eveniet ducimus a quo.', 'Reprehenderit sapiente similique voluptates.', NULL, NULL, NULL, NULL, 2, 1, 6, 5, 'dolorem', 'AT607062424101244175', 108, '8e1lUGUnUKbjFbq2ZNbd80Pg9xYkLrLs.png', '5.000', '2.000', '2015-08-08 07:11:45', '2004-11-19 23:50:26', 10, 'ut-dolorem-dolorem-qui-nisi', 11, 'hrs'),
('30', '30', 'Assunta', 'Otho', 'Mollitia adipisci voluptas nihil.', 'Sapiente pariatur sit.', NULL, NULL, NULL, NULL, 4, 1, 4, 5, 'unde', 'IS576163618233767222338123', 105, '8e1lUGUnUKbjFbq2ZNbd80Pg9xYkLrLs.png', '1.000', '4.000', '1979-09-02 01:00:19', '2007-01-10 17:35:03', 10, 'sapiente-inventore-natus-est-rerum-assumenda-placeat', 11, 'hrs'),
('31', '31', 'Maude', 'Jake', 'Illo et odit sed voluptas.', 'Quaerat vero provident.', NULL, NULL, NULL, NULL, 2, 1, 2, 8, 'iste', 'SK8674133204855620326144', 112, '8e1lUGUnUKbjFbq2ZNbd80Pg9xYkLrLs.png', '4.000', '2.000', '1978-11-06 14:53:13', '1999-07-27 21:07:14', 10, 'aut-est-id-et-illum-incidunt', 11, 'hrs'),
('32', '32', 'Rafael', 'Hillard', 'Quisquam fuga illum quas.', 'Velit qui id sint.', NULL, NULL, NULL, NULL, 2, 1, 9, 4, 'quas', 'EE256000564032367338', 135, '8e1lUGUnUKbjFbq2ZNbd80Pg9xYkLrLs.png', '1.000', '1.000', '2017-01-21 13:55:16', '2011-04-13 11:59:03', 10, 'et-et-molestias-provident-eos', 11, 'hrs'),
('33', '33', 'Darby', 'Roscoe', 'Voluptatem dolor velit nisi facere.', 'Distinctio at incidunt ut.', NULL, NULL, NULL, NULL, 3, 1, 6, 1, 'nostrum', 'AZ34FMHLIAE14N17RL37KK3827U0', 120, '8e1lUGUnUKbjFbq2ZNbd80Pg9xYkLrLs.png', '5.000', '2.000', '2013-10-14 20:58:38', '2013-07-28 08:20:41', 10, 'fugiat-ex-ut-enim-numquam-rerum-quos', 11, 'hrs'),
('34', '34', 'Cassandre', 'Brooke', 'Dicta et vel qui.', 'Doloribus nulla dolorem omnis quasi impedit.', NULL, NULL, NULL, NULL, 2, 1, 7, 2, 'accusantium', 'BR5861174033391989545193460I3', 162, '8e1lUGUnUKbjFbq2ZNbd80Pg9xYkLrLs.png', '4.000', '2.000', '1998-11-19 08:00:18', '2005-05-25 23:27:07', 10, 'distinctio-consequuntur-et-placeat-corporis', 11, 'hrs'),
('35', '35', 'Paris', 'Rosanna', 'Odio enim et eum excepturi corporis.', 'Aliquam quia officia similique.', NULL, NULL, NULL, NULL, 5, 1, 2, 10, 'dolorum', 'MD07CE2CRPQ00TGWO3552V2J', 181, '8e1lUGUnUKbjFbq2ZNbd80Pg9xYkLrLs.png', '4.000', '1.000', '1987-06-18 08:01:31', '1991-01-12 02:31:53', 10, 'ut-molestiae-adipisci-dolorem-ipsum-voluptatem-non', 11, 'hrs'),
('36', '36', 'Dallin', 'Joshua', 'Qui ipsam dolor mollitia tempore quo.', 'Laboriosam odit sit.', NULL, NULL, NULL, NULL, 1, 1, 4, 11, 'laudantium', 'IS788083337081294506470713', 112, '8e1lUGUnUKbjFbq2ZNbd80Pg9xYkLrLs.png', '2.000', '5.000', '1973-07-18 22:59:11', '1991-09-23 22:16:57', 10, 'amet-vel-maxime-enim-sed', 11, 'hrs'),
('37', '37', 'Myrtis', 'Myrtle', 'Fugit debitis eos culpa.', 'Consequuntur et ex possimus.', NULL, NULL, NULL, NULL, 4, 1, 15, 5, 'possimus', 'RS11832413269344711937', 196, '8e1lUGUnUKbjFbq2ZNbd80Pg9xYkLrLs.png', '3.000', '5.000', '1988-08-15 22:32:18', '1998-08-29 04:13:08', 10, 'hic-explicabo-fugit-eligendi-accusantium-aut-dolorum-perspiciatis', 11, 'hrs'),
('38', '38', 'Demetrius', 'Dariana', 'Rem temporibus nihil nisi.', 'Sequi dolorem officiis voluptate.', NULL, NULL, NULL, NULL, 1, 1, 3, 11, 'ut', 'NO9114263922898', 195, '8e1lUGUnUKbjFbq2ZNbd80Pg9xYkLrLs.png', '5.000', '4.000', '2016-07-14 02:49:18', '2003-07-11 14:07:35', 10, 'voluptas-odit-autem-velit-corporis-unde-a-et', 11, 'hrs'),
('39', '39', 'Kaleb', 'Manuela', 'Temporibus aliquid ex facilis dolor fuga.', 'Ipsa harum qui repudiandae molestias.', NULL, NULL, NULL, NULL, 2, 1, 12, 2, 'quaerat', 'SA6179TLD9LC577E06HB65TC', 136, '8e1lUGUnUKbjFbq2ZNbd80Pg9xYkLrLs.png', '4.000', '3.000', '2016-04-01 04:55:23', '1987-03-13 13:18:29', 10, 'totam-tempora-porro-aspernatur-perferendis', 11, 'hrs'),
('4', '4', 'June', 'Valentin', 'Tenetur rerum perferendis rerum iure.', 'Quo ut iure et eligendi.', NULL, NULL, NULL, NULL, 3, 1, 9, 8, 'quas', 'VG94YKWW3929228395927835', 191, '8e1lUGUnUKbjFbq2ZNbd80Pg9xYkLrLs.png', '3.000', '3.000', '2003-01-20 07:37:52', '1999-06-12 23:01:01', 10, 'accusamus-veniam-deserunt-asperiores', 11, 'hrs'),
('40', '40', 'Karine', 'Theresa', 'Rerum placeat sed eum.', 'Aut quos ut.', NULL, NULL, NULL, NULL, 2, 1, 10, 8, 'alias', 'MC106834753565348P74R24HY20', 109, '8e1lUGUnUKbjFbq2ZNbd80Pg9xYkLrLs.png', '5.000', '1.000', '2017-05-16 09:18:55', '1978-03-21 05:53:33', 10, 'quia-facere-ipsum-et-voluptatem-ducimus', 11, 'hrs'),
('41', '41', 'Dejuan', 'Karianne', 'Accusamus quis vel facilis blanditiis.', 'Architecto est totam temporibus.', NULL, NULL, NULL, NULL, 2, 1, 10, 11, 'fuga', 'EG025229168467487646898197787', 103, '8e1lUGUnUKbjFbq2ZNbd80Pg9xYkLrLs.png', '5.000', '2.000', '2022-05-20 08:20:01', '2001-05-10 03:20:57', 10, 'vero-qui-veniam-illum-praesentium-eum-praesentium-est', 11, 'hrs'),
('42', '42', 'Simone', 'Weldon', 'Vel reprehenderit nam atque autem ad.', 'Nam odit dignissimos ipsa nisi at.', NULL, NULL, NULL, NULL, 1, 1, 1, 9, 'perspiciatis', 'KW73DCGD8045873528913915472199', 102, '8e1lUGUnUKbjFbq2ZNbd80Pg9xYkLrLs.png', '3.000', '5.000', '2020-02-20 22:46:23', '1998-12-20 18:53:12', 10, 'perspiciatis-rerum-voluptatem-eum-accusamus-et', 11, 'hrs'),
('43', '43', 'Santos', 'Korbin', 'Natus non nesciunt alias.', 'Officiis esse porro architecto.', NULL, NULL, NULL, NULL, 1, 1, 12, 5, 'voluptatem', 'BA133954648662332544', 122, '8e1lUGUnUKbjFbq2ZNbd80Pg9xYkLrLs.png', '1.000', '3.000', '2023-01-08 15:52:33', '1975-02-15 05:54:17', 10, 'occaecati-ut-fugiat-officia-et-laudantium-ad', 11, 'hrs'),
('44', '44', 'Enos', 'Raquel', 'Corporis neque et voluptatem sed.', 'Rerum in eius quibusdam.', NULL, NULL, NULL, NULL, 1, 1, 6, 14, 'doloribus', 'AE204626816192236263771', 143, '8e1lUGUnUKbjFbq2ZNbd80Pg9xYkLrLs.png', '1.000', '3.000', '1983-07-29 21:31:21', '1997-07-25 23:37:00', 10, 'eligendi-tempore-vel-in', 11, 'hrs'),
('45', '45', 'Marianna', 'Shanelle', 'Sed distinctio temporibus modi neque est.', 'Autem accusantium animi laborum.', NULL, NULL, NULL, NULL, 2, 1, 7, 6, 'incidunt', 'BR5387490121368513201869187AG', 156, '8e1lUGUnUKbjFbq2ZNbd80Pg9xYkLrLs.png', '5.000', '4.000', '2014-11-13 08:46:07', '2013-12-17 01:37:37', 10, 'sed-rerum-perspiciatis-id-esse-sequi', 11, 'hrs'),
('46', '46', 'Porter', 'Cleveland', 'A incidunt error.', 'Repellat quaerat voluptas iusto dolores similique.', NULL, NULL, NULL, NULL, 1, 1, 15, 12, 'corporis', 'FI9181124357938925', 199, '8e1lUGUnUKbjFbq2ZNbd80Pg9xYkLrLs.png', '2.000', '3.000', '2020-01-10 04:39:37', '1987-03-26 07:44:25', 10, 'ut-consequatur-repudiandae-quia-quasi', 11, 'hrs'),
('47', '47', 'Selena', 'Jaiden', 'Sit dolores asperiores facere quis.', 'Dolorum officia veritatis aut dolorum.', NULL, NULL, NULL, NULL, 3, 1, 5, 4, 'sapiente', 'BA140813133385012590', 157, '8e1lUGUnUKbjFbq2ZNbd80Pg9xYkLrLs.png', '1.000', '2.000', '1976-03-06 15:21:16', '1991-10-20 10:54:12', 10, 'est-quae-quis-voluptatem-tempora-aut-quo-vitae-tenetur', 11, 'hrs'),
('48', '48', 'Bernadette', 'Casey', 'Excepturi reiciendis eaque itaque consequatur et.', 'Quam eius quia sed et.', NULL, NULL, NULL, NULL, 1, 1, 12, 2, 'perferendis', 'MT37OFKK55963A22XVG64KI4H8883GK', 103, '8e1lUGUnUKbjFbq2ZNbd80Pg9xYkLrLs.png', '1.000', '2.000', '2012-06-15 13:39:09', '1984-10-04 18:00:28', 10, 'culpa-eligendi-quos-ducimus-nemo', 11, 'hrs'),
('49', '49', 'Baron', 'Paxton', 'Ab repellat asperiores est delectus.', 'Voluptatem qui qui.', NULL, NULL, NULL, NULL, 5, 1, 10, 11, 'sunt', 'DE75156698966793111479', 169, '8e1lUGUnUKbjFbq2ZNbd80Pg9xYkLrLs.png', '1.000', '1.000', '1997-05-22 16:44:29', '1979-12-18 08:40:05', 10, 'dolore-dolorem-soluta-consequatur-sit', 11, 'hrs'),
('5', '5', 'Lucious', 'Emmie', 'Enim nam rerum.', 'Quas natus esse.', NULL, NULL, NULL, NULL, 2, 1, 14, 13, 'repellendus', 'CY3421074231H40SOZ322021R994', 134, '8e1lUGUnUKbjFbq2ZNbd80Pg9xYkLrLs.png', '2.000', '5.000', '1980-12-10 01:22:20', '2001-05-01 10:13:24', 10, 'autem-voluptatibus-asperiores-asperiores-iusto-tempora-dolore-saepe-itaque', 11, 'hrs'),
('50', '50', 'Lura', 'Ana', 'Sequi eligendi aut.', 'Ut consequatur quia et aliquam.', NULL, NULL, NULL, NULL, 2, 1, 13, 10, 'eum', 'LT172593871390787966', 137, '8e1lUGUnUKbjFbq2ZNbd80Pg9xYkLrLs.png', '5.000', '2.000', '1992-11-15 01:19:19', '1999-11-23 23:53:38', 10, 'nemo-impedit-voluptatibus-dignissimos-ut', 11, 'hrs'),
('6', '6', 'Tavares', 'Amaya', 'Ut aut molestiae nemo dolore.', 'Minus ut sapiente debitis velit.', NULL, NULL, NULL, NULL, 5, 1, 1, 6, 'mollitia', 'AZ69CXQL8959O27Z0684559R3RNE', 101, '8e1lUGUnUKbjFbq2ZNbd80Pg9xYkLrLs.png', '5.000', '5.000', '2011-12-30 01:33:25', '2003-01-10 00:23:20', 10, 'beatae-omnis-et-distinctio', 11, 'hrs'),
('7', '7', 'Kennith', 'Candida', 'Cum laudantium voluptatibus et.', 'Dolores aut praesentium alias dolorem.', NULL, NULL, NULL, NULL, 2, 1, 5, 9, 'aut', 'AD6446466524RK2IYL62CE99', 193, '8e1lUGUnUKbjFbq2ZNbd80Pg9xYkLrLs.png', '1.000', '3.000', '2008-04-05 12:58:08', '1987-01-08 10:12:04', 10, 'debitis-sunt-ea-et-commodi', 11, 'hrs'),
('8', '8', 'Geoffrey', 'Danyka', 'Error excepturi quae est labore perspiciatis.', 'Ducimus minima hic minima eum aut.', NULL, NULL, NULL, NULL, 4, 1, 7, 14, 'sint', 'AT284514227136036464', 132, '8e1lUGUnUKbjFbq2ZNbd80Pg9xYkLrLs.png', '4.000', '4.000', '1996-10-24 14:50:01', '2003-03-21 18:30:26', 10, 'enim-hic-accusantium-odio-voluptas-ut-corrupti', 11, 'hrs'),
('9', '9', 'Murl', 'Maci', 'Dolorum qui est rem illo.', 'Numquam sunt est et.', NULL, NULL, NULL, NULL, 2, 1, 1, 13, 'dolorem', 'GT17FG2J2UF31BUFO8PK1KB24XX4', 136, '8e1lUGUnUKbjFbq2ZNbd80Pg9xYkLrLs.png', '3.000', '1.000', '2022-10-11 08:30:05', '2021-05-03 08:45:02', 10, 'est-iure-corrupti-est-libero-est-aliquid', 11, 'hrs');

-- --------------------------------------------------------

--
-- Table structure for table `item_image`
--

CREATE TABLE `item_image` (
  `item_image_id` bigint(20) NOT NULL,
  `item_uuid` varchar(300) COLLATE utf8_unicode_ci DEFAULT NULL,
  `product_file_name` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `item_image`
--

INSERT INTO `item_image` (`item_image_id`, `item_uuid`, `product_file_name`) VALUES
(1, '', 'YSXEB58IS0em3uODSnMZS6dXnhG0seaS.jpg'),
(2, '', 'YSXEB58IS0em3uODSnMZS6dXnhG0seaS.jpg'),
(3, '', 'YSXEB58IS0em3uODSnMZS6dXnhG0seaS.jpg'),
(4, '', 'YSXEB58IS0em3uODSnMZS6dXnhG0seaS.jpg'),
(5, '', 'YSXEB58IS0em3uODSnMZS6dXnhG0seaS.jpg'),
(6, '', 'YSXEB58IS0em3uODSnMZS6dXnhG0seaS.jpg'),
(7, '', 'YSXEB58IS0em3uODSnMZS6dXnhG0seaS.jpg'),
(8, '', 'YSXEB58IS0em3uODSnMZS6dXnhG0seaS.jpg'),
(9, '', 'YSXEB58IS0em3uODSnMZS6dXnhG0seaS.jpg'),
(10, '', 'YSXEB58IS0em3uODSnMZS6dXnhG0seaS.jpg'),
(11, '', 'YSXEB58IS0em3uODSnMZS6dXnhG0seaS.jpg'),
(12, '', 'YSXEB58IS0em3uODSnMZS6dXnhG0seaS.jpg'),
(13, '', 'YSXEB58IS0em3uODSnMZS6dXnhG0seaS.jpg'),
(14, '', 'YSXEB58IS0em3uODSnMZS6dXnhG0seaS.jpg'),
(15, '', 'YSXEB58IS0em3uODSnMZS6dXnhG0seaS.jpg'),
(16, '', 'YSXEB58IS0em3uODSnMZS6dXnhG0seaS.jpg'),
(17, '', 'YSXEB58IS0em3uODSnMZS6dXnhG0seaS.jpg'),
(18, '', 'YSXEB58IS0em3uODSnMZS6dXnhG0seaS.jpg'),
(19, '', 'YSXEB58IS0em3uODSnMZS6dXnhG0seaS.jpg'),
(20, '', 'YSXEB58IS0em3uODSnMZS6dXnhG0seaS.jpg'),
(21, '', 'YSXEB58IS0em3uODSnMZS6dXnhG0seaS.jpg'),
(22, '', 'YSXEB58IS0em3uODSnMZS6dXnhG0seaS.jpg'),
(23, '', 'YSXEB58IS0em3uODSnMZS6dXnhG0seaS.jpg'),
(24, '', 'YSXEB58IS0em3uODSnMZS6dXnhG0seaS.jpg'),
(25, '', 'YSXEB58IS0em3uODSnMZS6dXnhG0seaS.jpg'),
(26, '', 'YSXEB58IS0em3uODSnMZS6dXnhG0seaS.jpg'),
(27, '', 'YSXEB58IS0em3uODSnMZS6dXnhG0seaS.jpg'),
(28, '', 'YSXEB58IS0em3uODSnMZS6dXnhG0seaS.jpg'),
(29, '', 'YSXEB58IS0em3uODSnMZS6dXnhG0seaS.jpg'),
(30, '', 'YSXEB58IS0em3uODSnMZS6dXnhG0seaS.jpg'),
(31, '', 'YSXEB58IS0em3uODSnMZS6dXnhG0seaS.jpg'),
(32, '', 'YSXEB58IS0em3uODSnMZS6dXnhG0seaS.jpg'),
(33, '', 'YSXEB58IS0em3uODSnMZS6dXnhG0seaS.jpg'),
(34, '', 'YSXEB58IS0em3uODSnMZS6dXnhG0seaS.jpg'),
(35, '', 'YSXEB58IS0em3uODSnMZS6dXnhG0seaS.jpg'),
(36, '', 'YSXEB58IS0em3uODSnMZS6dXnhG0seaS.jpg'),
(37, '', 'YSXEB58IS0em3uODSnMZS6dXnhG0seaS.jpg'),
(38, '', 'YSXEB58IS0em3uODSnMZS6dXnhG0seaS.jpg'),
(39, '', 'YSXEB58IS0em3uODSnMZS6dXnhG0seaS.jpg'),
(40, '', 'YSXEB58IS0em3uODSnMZS6dXnhG0seaS.jpg'),
(41, '', 'YSXEB58IS0em3uODSnMZS6dXnhG0seaS.jpg'),
(42, '', 'YSXEB58IS0em3uODSnMZS6dXnhG0seaS.jpg'),
(43, '', 'YSXEB58IS0em3uODSnMZS6dXnhG0seaS.jpg'),
(44, '', 'YSXEB58IS0em3uODSnMZS6dXnhG0seaS.jpg'),
(45, '', 'YSXEB58IS0em3uODSnMZS6dXnhG0seaS.jpg'),
(46, '', 'YSXEB58IS0em3uODSnMZS6dXnhG0seaS.jpg'),
(47, '', 'YSXEB58IS0em3uODSnMZS6dXnhG0seaS.jpg'),
(48, '', 'YSXEB58IS0em3uODSnMZS6dXnhG0seaS.jpg'),
(49, '', 'YSXEB58IS0em3uODSnMZS6dXnhG0seaS.jpg'),
(50, '', 'YSXEB58IS0em3uODSnMZS6dXnhG0seaS.jpg');

-- --------------------------------------------------------

--
-- Table structure for table `item_variant`
--

CREATE TABLE `item_variant` (
  `item_variant_uuid` char(60) COLLATE utf8_unicode_ci NOT NULL,
  `item_uuid` varchar(100) COLLATE utf8_unicode_ci NOT NULL,
  `stock_qty` int(11) DEFAULT NULL,
  `sku` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `barcode` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `price` decimal(10,3) DEFAULT NULL,
  `compare_at_price` decimal(10,3) DEFAULT NULL,
  `weight` int(11) DEFAULT NULL,
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `item_variant_image`
--

CREATE TABLE `item_variant_image` (
  `item_variant_image_uuid` char(60) COLLATE utf8_unicode_ci NOT NULL,
  `item_variant_uuid` char(60) COLLATE utf8_unicode_ci NOT NULL,
  `item_uuid` varchar(100) COLLATE utf8_unicode_ci NOT NULL,
  `product_file_name` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `item_variant_option`
--

CREATE TABLE `item_variant_option` (
  `item_variant_option_uuid` char(60) COLLATE utf8_unicode_ci NOT NULL,
  `item_variant_uuid` char(60) COLLATE utf8_unicode_ci NOT NULL,
  `item_uuid` varchar(100) COLLATE utf8_unicode_ci NOT NULL,
  `option_id` int(11) NOT NULL,
  `extra_option_id` int(11) NOT NULL,
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `migration`
--

CREATE TABLE `migration` (
  `version` varchar(180) NOT NULL,
  `apply_time` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `migration`
--

INSERT INTO `migration` (`version`, `apply_time`) VALUES
('m000000_000000_base', 1657433135),
('m130524_201442_init', 1657433137),
('m200119_140105_create_city_table', 1657433137),
('m200119_140111_create_area_table', 1657433137),
('m200119_140122_create_payment_method_table', 1657433137),
('m200119_140530_create_restaurant_table', 1657433137),
('m200119_140601_create_restaurant_payment_method_table', 1657433137),
('m200119_140648_create_restaurant_delivery_table', 1657433137),
('m200119_140711_create_item_table', 1657433137),
('m200119_140720_create_category_table', 1657433137),
('m200119_140733_create_category_item_table', 1657433137),
('m200119_140748_create_option_table', 1657433137),
('m200119_141034_create_extra_option_table', 1657433137),
('m200130_194447_create_order_table', 1657433138),
('m200314_113339_create_working_day_table', 1657433138),
('m200314_113345_create_working_hours_table', 1657433138),
('m200317_125607_create_restaurant_branch_table', 1657433138),
('m200325_152726_add_restaurant_api_key_column_to_restaurant_table', 1657433138),
('m200327_171548_create_agent_assignment', 1657433138),
('m200328_170639_add_email_notification_column_to_agent_table', 1657433138),
('m200328_195944_create_payment_table', 1657433138),
('m200328_210254_add_column_received_callback_to_payment', 1657433138),
('m200407_133656_add_delivery_time_ar_field_in_restaurant_delivery_table', 1657433138),
('m200408_144200_add_new_fields_to_restaurant_table', 1657433138),
('m200411_113002_create_restaurant_theme_table', 1657433138),
('m200412_111439_add_restaurant_domain_field_to_restaurant_table', 1657433138),
('m200413_203425_alert_restaurant_theme', 1657433138),
('m200418_170325_add_response_message_to_payment_table', 1657433138),
('m200418_225006_add_restaurant_uuid_to_payment_table', 1657433138),
('m200419_101304_create_refund_table', 1657433138),
('m200422_222648_add_restaurant_email_field_to_restaurant_table', 1657433138),
('m200424_132523_add_item_name_to_order_item_extra_option_table', 1657433138),
('m200424_183221_update_area_list', 1657433138),
('m200501_200101_add_email_notification_field_to_restaurant_table', 1657433138),
('m200502_131814_add_developer_id_to_restaurant_table', 1657433138),
('m200503_225551_add_tracking_link_field_to_order_table', 1657433138),
('m200504_012107_add_role_field_to_agent_assignment_table', 1657433138),
('m200504_034009_drop_agent_id_field', 1657433139),
('m200506_002932_add_armada_api_key_field_to_restaurant_table', 1657433139),
('m200511_003753_add_phone_number_display_field_to_restaurant_table', 1657433139),
('m200512_205407_add_store_branch_name_field_to_restaurant_field', 1657433139),
('m200513_035540_add_custom_css_field_to_restaurant_table', 1657433139),
('m200514_231729_add_sub_category_field_to_category_table', 1657433139),
('m200515_173322_rename_categorys_field', 1657433139),
('m200521_202607_create_store_layout_field_to_restaurant_table', 1657433139),
('m200523_211901_rename_fields_in_restaurant_table', 1657433139),
('m200524_001300_add_commercial_license_field_to_restaurant_table', 1657433139),
('m200526_211422_rename_owner_customer_number_field_in_restaurant_table', 1657433139),
('m200530_172958_increase_item_description_length_field', 1657433139),
('m200602_120742_add_refunded_amount_field_to_order_table', 1657433139),
('m200602_132551_create_refunded_items_table', 1657433139),
('m200602_203200_create_item_image_table', 1657433139),
('m200605_162826_add_new_fileds_to_order_table', 1657433139),
('m200607_203849_drop_refunded_amount_field_from_order_table', 1657433139),
('m200608_175623_add_platform_fee_to_restaurant_table', 1657433139),
('m200610_120032_add_unit_sold_field_to_item_table', 1657433139),
('m200615_113217_add_items_has_been_restocked_field_to_order_table', 1657433139),
('m200615_233833_change_estimated_time_of_arrival_date_type', 1657433139),
('m200621_161441_create_agent_token_table', 1657433139),
('m200629_145708_create_app_id_field_to_restaurant_table', 1657433139),
('m200629_164828_add_facebook_pixil_id_to_restaurant_table', 1657433139),
('m200629_224650_drop_working_hours_table', 1657433139),
('m200629_235611_create_opening_hours_table', 1657433139),
('m200705_194837_add_lat_and_lng_fields_to_order_table', 1657433139),
('m200706_171759_alter_lat_lng_fields', 1657433139),
('m200707_194719_create_show_opening_hours_field_to_restaurant_table', 1657433139),
('m200708_213959_add_instagram_url_field_to_restaurant_table', 1657433139),
('m200709_195321_add_is_closed_field_to_opening_hour_table', 1657433139),
('m200710_154139_add_schedule_order_field_to_restaurant_table', 1657433139),
('m200714_112526_alert_opening_hrs_table', 1657433139),
('m200718_213126_add_is_order_scheduled_field_to_order_table', 1657433139),
('m200723_153505_create_voucher_table', 1657433139),
('m200723_153545_create_customer_voucher_table', 1657433140),
('m200726_120052_add_armada_qr_code_link_field_to_order_table', 1657433140),
('m200726_222633_add_armada_delivery_code_field_to_order_table', 1657433140),
('m200727_132052_drop_voucher_title_field', 1657433140),
('m200728_152635_add_description_field_to_voucher_table', 1657433140),
('m200805_120150_update_discount_amount_data_type', 1657433140),
('m200807_115044_add_category_image_field_to_category_table', 1657433140),
('m200907_064719_create_web_link_table', 1657433140),
('m200907_064729_create_store_web_link_table', 1657433140),
('m200914_164529_add_mashkor_api_key_field_to_restaurant_table', 1657433140),
('m200915_095430_add_credit_card_token_field_to_payment_table', 1657433140),
('m200915_101700_add_live_public_key_field_to_restaurant_table', 1657433140),
('m200918_172409_alert_live_public_key', 1657433140),
('m200918_172420_create_bank_discount_table', 1657433140),
('m200919_154201_create_bank_discount_table', 1657433140),
('m200919_165653_add_bank_discount_id_field_to_order_table', 1657433140),
('m200920_173306_add_mashkor_order_number_field_to_order_table', 1657433140),
('m200921_111016_drop_mashkor_api_key_from_restaurant_table', 1657433140),
('m200921_112820_add_mashkor_tracking_link_field_to_restaurant_table', 1657433140),
('m200922_114739_alert_mashkor_order_status_field', 1657433140),
('m200925_170517_alert_live_public_key_field', 1657433140),
('m200926_174955_alert_discount_amount_field', 1657433140),
('m200928_190156_add_track_quantity_field_to_item_table', 1657433140),
('m200930_073754_add_item_status_field_to_item_table', 1657433140),
('m200930_161744_add_reminder_email_field_to_agent_table', 1657433140),
('m201001_201125_track_quantity_field_to_extra_option_table', 1657433140),
('m201002_212451_add_qty_field_to_order_extra_option_table', 1657433140),
('m201003_210134_alert_support_pick_up_field_to_restaurant_table', 1657433140),
('m201007_122323_add_site_id_field_to_restaurant_table', 1657433140),
('m201012_184039_create_queue_table', 1657433140),
('m201017_184430_add_site_id_for_restaurant_table', 1657433140),
('m201018_180502_add_sms_sent_field_in_restaurant_table', 1657433140),
('m201018_215107_alert_field_in_restaurant_table', 1657433141),
('m201019_163936_alert_business_id_field', 1657433141),
('m201020_133036_create_plan_table', 1657433141),
('m201022_210253_create_subscription_payment_table', 1657433141),
('m201022_220439_add_column_received_callback_to_subscription', 1657433141),
('m201024_192821_alert_subscription_table', 1657433141),
('m201025_193348_add_company_name_field_to_restaurant_table', 1657433141),
('m201026_212912_alert_authorized_signature_purpose_field', 1657433141),
('m201028_131103_alert_subscription_status_field', 1657433141),
('m201028_142149_add_description_field_to_plan_table', 1657433141),
('m201028_162639_add_payment_method_id_to_subscription_table', 1657433141),
('m201101_113204_add_has_deployed_field_to_restaurant_table', 1657433141),
('m201105_154957_create_tap_queue_table', 1657433142),
('m201109_172533_create_country_table', 1657433142),
('m201109_180428_create_currency_table', 1657433142),
('m201110_124222_add_identification_file_backside', 1657433142),
('m201110_181718_add_country_code_field_to_customer_table', 1657433142),
('m201110_183917_add_customer_phone_country_code_field_to_order_table', 1657433142),
('m201112_151847_add_source_id_to_payment_method_table', 1657433142),
('m201112_232545_create_country_payment_method_table', 1657433142),
('m201114_105311_create_business_location_table', 1657433142),
('m201114_110425_create_delivery_zone_table', 1657433142),
('m201116_164637_add_country_id_field_to_city_table', 1657433142),
('m201119_172315_add_plugn_fee_field_to_payment_table', 1657433142),
('m201126_164544_add_delivery_zone_id_field_to_order_table', 1657433142),
('m201127_150520_add_default_language_field_to_restaurant_table', 1657433142),
('m201202_132406_drop_issuing_coutnry', 1657433142),
('m201203_180924_add_vat_field_to_restaurant_table', 1657433142),
('m201207_115315_drop_vat_field', 1657433142),
('m201214_165245_alter_delivery_zone_tax_field', 1657433142),
('m201222_175532_add_pickup_location_id_field_to_order_table', 1657433142),
('m201225_104344_add_vat_field_to_order_table', 1657433142),
('m201228_135306_add_hotjar_id_to_store_table', 1657433143),
('m201230_101531_drop_hotjar_id_column', 1657433143),
('m210112_104329_add_hide_request_driver_button_field_to_restaurant_table', 1657433143),
('m210118_184312_alert_min_order_amount_field_to_voucher_table', 1657433143),
('m210118_192354_alert_min_order_amount_field_to_voucher_table', 1657433143),
('m210121_101346_change_float_datatype_to_decimal', 1657433143),
('m210127_162342_add_version_field_to_restaurant_table', 1657433143),
('m210201_111007_add_sitemap_require_update', 1657433143),
('m210202_173657_add_warehouse_fee_field_to_restaurant_table', 1657433143),
('m210208_163412_alert_email_notification_field', 1657433143),
('m210213_122412_alert_tax_field', 1657433143),
('m210228_164816_alert_office_field', 1657433143),
('m210301_105809_add_missing_orders', 1657433143),
('m210309_170041_alter_orders_foreign_keys', 1657433143),
('m210309_170051_add_missing_order_for_lachurreros', 1657433143),
('m210314_184444_add_business_location_name_in_order_table', 1657433143),
('m210320_100524_add_is_my_fatoora_enable_field_to_restaurant_table', 1657433143),
('m210320_103431_create_payment_gateway_queue_table', 1657433143),
('m210321_223840_add_payment_gateway_invoice_id_field_to_payment_table', 1657433144),
('m210322_210828_add_payment_gateway_field_to_payment_table', 1657433144),
('m210324_113153_alert_foreign_keys_in_refund_table', 1657433144),
('m210324_124515_add_payment_uuid_field_to_refund_table', 1657433144),
('m210325_201230_add_refund_created_at_field_to_refund_table', 1657433144),
('m210328_203413_add_snapchat_pixil_id_field_to_restaurant_table', 1657433144),
('m210405_205458_alert_item_price_field', 1657433144),
('m210411_190001_alert_refund_status_field_in_refund_table', 1657433144),
('m210414_200518_add_retention_email_sent_field_to_restaurant_table', 1657433144),
('m210425_100317_add_recipient_name_field_to_order_table', 1657433144),
('m210428_225652_add_payment_method_code_filed_to_payment_method_table', 1657433144),
('m210502_220325_alert_primary_field_restaurant_theme_table', 1657433144),
('m210506_215407_alert_refund_status_field', 1657433144),
('m210508_230634_add_receive_weekly_stats_field_to_agent_table', 1657433144),
('m210514_192452_add_lat_and_lng_fields_to_business_location_table', 1657433144),
('m210516_151136_add_email_notification_field_to_agent_assignment_table', 1657433144),
('m210528_084559_add_armada_api_key_field_to_business_location_table', 1657433144),
('m210610_114347_add_sender_name_field_to_order_table', 1657433144),
('m210613_184840_add_armada_order_status_field_to_order_table', 1657433144),
('m210614_143807_order_item_datetime', 1657433145),
('m210621_121716_add_vat_field_to_payment_table', 1657433145),
('m210710_075754_create_partner_table', 1657433146),
('m210714_095138_add_delivery_fee_field_to_restaurant_table', 1657433146),
('m210729_170821_add_payout_status_field_to_partner_payout_table', 1657433146),
('m210803_132951_add_partner_fee_field_to_subscription_payment_table', 1657433146),
('m210816_135804_report', 1657433146),
('m210816_140328_order_item', 1657433146),
('m210824_113904_update_resturant_tbl', 1657433146),
('m210912_113426_add_bank_id_field_to_partner_table', 1657433146),
('m210913_102640_add_transfer_benef_iban_feild_to_partner_payout_table', 1657433146),
('m210915_123343_add_transfer_file_field_to_partner_payout_table', 1657433146),
('m210918_181759_add_civil_id_field_to_customer_table', 1657433146),
('m210919_091413_add_phone_number_country_code_field_to_partner_table', 1657433146),
('m210919_172924_add_class_field_to_customer_table', 1657433146),
('m210926_093755_fix_partner_payout_foreign_key', 1657433146),
('m210930_101552_item_name_ar', 1657433146),
('m211006_110448_add_diggipacks_customer_id_field_to_restaurant_table', 1657433146),
('m211008_073951_item', 1657433146),
('m211019_102314_add_compare_at_price_field_to_item_table', 1657433147),
('m211029_102937_soft_delete_order', 1657433147),
('m211101_100915_business_delete', 1657433147),
('m211103_102006_store_curr', 1657433147),
('m211103_104151_store_curr', 1657433147),
('m211103_105549_currency_conversion_table', 1657433746),
('m211108_110809_fk_changes', 1657433746),
('m211109_112639_store_currency', 1657433746),
('m211116_084242_currency', 1657433746),
('m211205_114342_alert_discount_amount_field', 1657433746),
('m220117_095738_create_soft_delete_field_to_delivery_zone_table', 1657433746),
('m220121_063606_customer_bank_discount_changes', 1657433746),
('m220201_115245_language_pref', 1657433746),
('m220201_134543_email_verification', 1657433746),
('m220204_063459_remove_relation_field_from_restaurant_branch_id', 1657433746),
('m220221_115736_voucher', 1657433746),
('m220225_112755_free_checkout', 1657433746),
('m220307_073438_variant', 1657433747),
('m220307_080512_variant_option', 1657433747),
('m220311_081548_currency_status', 1657433767),
('m220311_103631_voucher', 1657433767),
('m220315_100601_item_variant', 1657433768),
('m220318_070454_sluggable', 1657433768),
('m220318_075125_item_slug', 1657433768),
('m220318_111546_category_slug', 1657433768),
('m220321_081439_seo_meta_tags', 1657433768),
('m220330_111120_optionh', 1657433768),
('m220401_104334_add_max_num_orders_field_to_restaurant_table', 1657433768),
('m220404_163807_order_option', 1657433768),
('m220404_201523_add_demand_delivery_field_to_restaurant_table', 1657433768),
('m220405_160631_item', 1657433768),
('m220419_165447_order_discount', 1657433821),
('m220420_140313_item_price', 1657433821),
('m220530_110723_crm', 1657433821),
('m220530_125457_staff_access', 1657433823),
('m220605_160907_admin_role', 1657433823),
('m220613_112845_refund_response', 1657433823),
('m220620_064415_utm', 1657433823),
('m220628_071015_restaurant_payment_method_status_field', 1657433823),
('m220629_124139_order_table_changes', 1657433823),
('m220707_094900_netlify_response', 1657433823),
('m220728_061411_config', 1679400893),
('m220731_134419_custom_subscription_price', 1679400893),
('m220809_122922_addons', 1679400893),
('m220825_122342_sandbox', 1679400893),
('m220918_074639_accept_order', 1679400893),
('m221006_080652_store_market', 1679400893),
('m221009_093414_tap_response', 1679400893),
('m221012_122745_debugger', 1679400893),
('m221106_120941_store_delete', 1679400893),
('m221111_064623_sandbox', 1679400893),
('m221227_115137_payment_error', 1679400893),
('m230105_094951_payment_currencies', 1679400893),
('m230108_045908_last_active', 1679400893),
('m230108_180112_updates', 1679400893),
('m230115_062701_moyasar', 1679400893),
('m230117_082451_invoice', 1679400894),
('m230118_072747_invoice_payment', 1679400894),
('m230119_054715_invoice_item', 1679400894),
('m230122_044650_email_campaign', 1679400894),
('m230130_062037_stripe', 1679400894),
('m230219_090316_hotfix_payment', 1679400894),
('m230222_024807_invoice', 1679400894),
('m230222_062236_invoice_payment', 1679400894),
('m230301_123925_restaurant_invoice', 1679400894),
('m230315_105326_event', 1679400894),
('m230402_092357_warn_inactive', 1682575820),
('m230410_063218_store_domain', 1682575820),
('m230418_090645_restaurant_upload', 1682575820);

-- --------------------------------------------------------

--
-- Table structure for table `opening_hour`
--

CREATE TABLE `opening_hour` (
  `opening_hour_id` int(11) NOT NULL,
  `restaurant_uuid` char(60) COLLATE utf8_unicode_ci NOT NULL,
  `day_of_week` smallint(6) NOT NULL,
  `open_at` time NOT NULL,
  `close_at` time NOT NULL,
  `is_closed` smallint(6) DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `opening_hour`
--

INSERT INTO `opening_hour` (`opening_hour_id`, `restaurant_uuid`, `day_of_week`, `open_at`, `close_at`, `is_closed`) VALUES
(1, '1', 0, '12:41:04', '18:50:54', 0),
(2, '1', 1, '17:56:53', '23:31:15', 0),
(3, '1', 2, '14:43:54', '02:00:01', 0),
(4, '1', 3, '13:11:59', '10:58:04', 0),
(5, '1', 4, '06:04:48', '16:20:28', 0),
(6, '1', 5, '19:24:32', '12:58:39', 0),
(7, '1', 6, '09:28:18', '23:44:06', 0),
(8, '8', 4, '08:38:35', '11:29:52', 0),
(9, '9', 4, '01:39:10', '22:31:26', 0),
(10, '10', 0, '18:48:32', '05:10:33', 0),
(11, '11', 0, '12:59:58', '22:33:00', 0),
(12, '12', 5, '18:31:22', '18:51:47', 0),
(13, '13', 1, '17:50:27', '00:55:24', 0),
(14, '14', 6, '11:46:40', '02:42:37', 0),
(15, '15', 0, '09:57:00', '20:31:53', 0),
(16, '16', 6, '00:13:55', '21:46:24', 0),
(17, '17', 0, '05:00:23', '00:39:40', 0),
(18, '18', 1, '04:54:36', '01:51:10', 0),
(19, '19', 6, '12:09:03', '11:46:11', 0),
(20, '20', 1, '05:30:16', '08:06:08', 0),
(21, '21', 1, '08:59:12', '15:40:20', 0),
(22, '22', 2, '18:27:20', '13:19:19', 0),
(23, '23', 4, '04:06:40', '14:39:47', 0),
(24, '24', 0, '14:12:55', '16:40:30', 0),
(25, '25', 0, '16:39:26', '17:01:51', 0),
(26, '26', 5, '21:52:35', '19:41:36', 0),
(27, '27', 5, '07:00:48', '10:56:25', 0),
(28, '28', 2, '19:13:46', '03:15:21', 0),
(29, '29', 0, '15:45:30', '18:01:28', 0),
(30, '30', 3, '00:10:57', '15:10:34', 0),
(31, '31', 3, '23:20:11', '17:17:46', 0),
(32, '32', 3, '06:45:51', '19:42:41', 0),
(33, '33', 6, '04:42:12', '16:00:38', 0),
(34, '34', 0, '11:55:28', '03:16:43', 0),
(35, '35', 2, '23:22:13', '14:30:45', 0),
(36, '36', 4, '09:54:54', '10:16:36', 0),
(37, '37', 6, '06:23:07', '19:38:24', 0),
(38, '38', 0, '16:30:39', '12:35:20', 0),
(39, '39', 5, '19:51:49', '21:20:04', 0),
(40, '40', 2, '21:01:51', '10:45:44', 0),
(41, '41', 0, '05:30:41', '14:28:09', 0),
(42, '42', 5, '21:54:33', '06:20:01', 0),
(43, '43', 4, '02:46:05', '06:09:29', 0),
(44, '44', 0, '17:08:24', '10:43:12', 0),
(45, '45', 1, '01:31:39', '20:27:32', 0),
(46, '46', 1, '12:58:13', '23:35:40', 0),
(47, '47', 0, '23:27:13', '17:51:07', 0),
(48, '48', 0, '07:31:18', '15:33:51', 0),
(49, '49', 3, '18:39:58', '14:54:57', 0),
(50, '50', 0, '08:29:38', '01:23:51', 0);

-- --------------------------------------------------------

--
-- Table structure for table `option`
--

CREATE TABLE `option` (
  `option_id` int(11) NOT NULL,
  `item_uuid` varchar(300) COLLATE utf8_unicode_ci NOT NULL,
  `min_qty` tinyint(1) DEFAULT 0,
  `max_qty` int(11) UNSIGNED DEFAULT NULL,
  `is_required` tinyint(1) DEFAULT 0,
  `option_name` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `option_name_ar` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `option_type` tinyint(1) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `option`
--

INSERT INTO `option` (`option_id`, `item_uuid`, `min_qty`, `max_qty`, `is_required`, `option_name`, `option_name_ar`, `option_type`) VALUES
(1, '1', 1, 3, 0, 'Sylvester', 'D\'angelo', NULL),
(2, '2', 0, 3, 0, 'Rickie', 'Emmett', NULL),
(3, '3', 1, 2, 0, 'Billy', 'Elyssa', NULL),
(4, '4', 0, 2, 0, 'Tracy', 'Larry', NULL),
(5, '5', 0, 1, 0, 'Afton', 'Fredy', NULL),
(6, '6', 1, 1, 0, 'Sim', 'Carissa', NULL),
(7, '7', 0, 8, 0, 'Jarvis', 'Magdalen', NULL),
(8, '8', 0, 9, 0, 'Ted', 'Payton', NULL),
(9, '9', 0, 5, 0, 'Vesta', 'Shyann', NULL),
(10, '10', 1, 4, 0, 'Chloe', 'Alek', NULL),
(11, '11', 0, 1, 0, 'Mittie', 'Isadore', NULL),
(12, '12', 0, 5, 0, 'Berniece', 'Letha', NULL),
(13, '13', 0, 4, 0, 'Brandyn', 'Izaiah', NULL),
(14, '14', 1, 9, 0, 'Thurman', 'Theodora', NULL),
(15, '15', 0, 3, 0, 'Tristian', 'Christ', NULL),
(16, '16', 0, 6, 0, 'Tyler', 'Juwan', NULL),
(17, '17', 1, 5, 0, 'Telly', 'Helen', NULL),
(18, '18', 0, 4, 0, 'Tate', 'Darius', NULL),
(19, '19', 1, 8, 0, 'Gonzalo', 'Lucio', NULL),
(20, '20', 1, 5, 0, 'Naomie', 'Lynn', NULL),
(21, '21', 0, 3, 0, 'Dolly', 'Maurine', NULL),
(22, '22', 0, 2, 0, 'Jevon', 'Keaton', NULL),
(23, '23', 1, 1, 0, 'Filomena', 'Eloy', NULL),
(24, '24', 0, 7, 0, 'Joanny', 'Mohammad', NULL),
(25, '25', 0, 4, 0, 'Mable', 'Vivian', NULL),
(26, '26', 1, 8, 0, 'Vita', 'Casimir', NULL),
(27, '27', 0, 8, 0, 'Ervin', 'Lavern', NULL),
(28, '28', 1, 9, 0, 'Americo', 'Bette', NULL),
(29, '29', 1, 4, 0, 'Kylie', 'Gabriel', NULL),
(30, '30', 0, 6, 0, 'Kamren', 'Myah', NULL),
(31, '31', 1, 9, 0, 'Eileen', 'Frederick', NULL),
(32, '32', 1, 5, 0, 'Desiree', 'Watson', NULL),
(33, '33', 0, 6, 0, 'Jace', 'Johathan', NULL),
(34, '34', 0, 4, 0, 'Vernon', 'Montana', NULL),
(35, '35', 0, 1, 0, 'Sigmund', 'Frieda', NULL),
(36, '36', 1, 9, 0, 'Kimberly', 'Emery', NULL),
(37, '37', 1, 2, 0, 'Cassandra', 'Laron', NULL),
(38, '38', 1, 1, 0, 'Jeff', 'Destini', NULL),
(39, '39', 0, 2, 0, 'Madisyn', 'Freddie', NULL),
(40, '40', 0, 6, 0, 'Toy', 'Kristopher', NULL),
(41, '41', 1, 5, 0, 'Tyrique', 'Florence', NULL),
(42, '42', 1, 3, 0, 'Georgette', 'Richard', NULL),
(43, '43', 0, 9, 0, 'Serena', 'Delmer', NULL),
(44, '44', 0, 7, 0, 'Dandre', 'Reece', NULL),
(45, '45', 1, 9, 0, 'Maryam', 'Vena', NULL),
(46, '46', 0, 10, 0, 'Rosetta', 'Heber', NULL),
(47, '47', 0, 8, 0, 'Jayme', 'June', NULL),
(48, '48', 0, 1, 0, 'Maxie', 'Roberto', NULL),
(49, '49', 0, 8, 0, 'Cecilia', 'Monty', NULL),
(50, '50', 1, 1, 0, 'Terrance', 'Rosie', NULL);

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
  `customer_phone_country_code` int(3) DEFAULT 965,
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
  `delivery_zone_id` bigint(20) DEFAULT NULL,
  `shipping_country_id` int(11) DEFAULT NULL,
  `country_name` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `country_name_ar` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `business_location_name` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `floor` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `apartment` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `building` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `office` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `city` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `postalcode` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `address_1` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `address_2` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `special_directions` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `payment_method_id` int(11) DEFAULT NULL,
  `payment_method_name` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `payment_method_name_ar` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `currency_code` char(3) COLLATE utf8_unicode_ci NOT NULL,
  `store_currency_code` char(3) COLLATE utf8_unicode_ci DEFAULT NULL,
  `currency_rate` double DEFAULT NULL,
  `total_price` decimal(10,3) UNSIGNED DEFAULT 0.000,
  `subtotal` decimal(10,3) UNSIGNED DEFAULT 0.000,
  `subtotal_before_refund` decimal(10,3) UNSIGNED DEFAULT 0.000,
  `total_price_before_refund` decimal(10,3) UNSIGNED DEFAULT 0.000,
  `delivery_fee` decimal(10,3) UNSIGNED DEFAULT 0.000,
  `order_status` tinyint(1) UNSIGNED DEFAULT 9,
  `is_deleted` tinyint(1) DEFAULT 0,
  `order_mode` tinyint(1) UNSIGNED NOT NULL,
  `estimated_time_of_arrival` datetime DEFAULT NULL,
  `delivery_time` int(11) DEFAULT NULL,
  `order_created_at` datetime NOT NULL,
  `order_updated_at` datetime NOT NULL,
  `restaurant_branch_id` int(11) DEFAULT NULL,
  `armada_tracking_link` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `items_has_been_restocked` tinyint(1) NOT NULL DEFAULT 0,
  `latitude` decimal(25,20) DEFAULT NULL,
  `longitude` decimal(25,20) DEFAULT NULL,
  `is_order_scheduled` smallint(6) DEFAULT NULL,
  `scheduled_time_start_from` datetime DEFAULT NULL,
  `scheduled_time_to` datetime DEFAULT NULL,
  `voucher_id` bigint(20) DEFAULT NULL,
  `voucher_code` varchar(100) COLLATE utf8_unicode_ci DEFAULT NULL,
  `discount_type` decimal(10,3) DEFAULT NULL,
  `voucher_discount` decimal(10,3) DEFAULT NULL,
  `armada_qr_code_link` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `armada_delivery_code` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `armada_order_status` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `bank_discount_id` bigint(20) DEFAULT NULL,
  `bank_discount` decimal(10,3) DEFAULT NULL,
  `mashkor_order_number` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `mashkor_tracking_link` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `mashkor_driver_name` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `mashkor_driver_phone` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `mashkor_order_status` tinyint(1) UNSIGNED DEFAULT NULL,
  `reminder_sent` tinyint(1) NOT NULL DEFAULT 0,
  `sms_sent` smallint(6) DEFAULT 0,
  `pickup_location_id` bigint(20) DEFAULT NULL,
  `tax` decimal(10,3) UNSIGNED DEFAULT 0.000,
  `recipient_name` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `recipient_phone_number` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `gift_message` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `order_instruction` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `sender_name` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `diggipack_awb_no` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `diggipack_order_status` char(4) COLLATE utf8_unicode_ci DEFAULT NULL,
  `utm_uuid` varchar(100) COLLATE utf8_unicode_ci DEFAULT NULL,
  `is_sandbox` tinyint(1) DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `order`
--

INSERT INTO `order` (`order_uuid`, `payment_uuid`, `customer_id`, `customer_name`, `customer_phone_number`, `customer_phone_country_code`, `customer_email`, `restaurant_uuid`, `area_id`, `area_name`, `area_name_ar`, `unit_type`, `block`, `street`, `avenue`, `house_number`, `delivery_zone_id`, `shipping_country_id`, `country_name`, `country_name_ar`, `business_location_name`, `floor`, `apartment`, `building`, `office`, `city`, `postalcode`, `address_1`, `address_2`, `special_directions`, `payment_method_id`, `payment_method_name`, `payment_method_name_ar`, `currency_code`, `store_currency_code`, `currency_rate`, `total_price`, `subtotal`, `subtotal_before_refund`, `total_price_before_refund`, `delivery_fee`, `order_status`, `is_deleted`, `order_mode`, `estimated_time_of_arrival`, `delivery_time`, `order_created_at`, `order_updated_at`, `restaurant_branch_id`, `armada_tracking_link`, `items_has_been_restocked`, `latitude`, `longitude`, `is_order_scheduled`, `scheduled_time_start_from`, `scheduled_time_to`, `voucher_id`, `voucher_code`, `discount_type`, `voucher_discount`, `armada_qr_code_link`, `armada_delivery_code`, `armada_order_status`, `bank_discount_id`, `bank_discount`, `mashkor_order_number`, `mashkor_tracking_link`, `mashkor_driver_name`, `mashkor_driver_phone`, `mashkor_order_status`, `reminder_sent`, `sms_sent`, `pickup_location_id`, `tax`, `recipient_name`, `recipient_phone_number`, `gift_message`, `order_instruction`, `sender_name`, `diggipack_awb_no`, `diggipack_order_status`, `utm_uuid`, `is_sandbox`) VALUES
('1', '1', 1, 'Catharine', '1-570-838-7598 x578', 91, 'thoeger@hintz.info', '1', 1, 'Kristina Burg', 'Elias Lock', 'House', '6', '2', '3', '8', 1, 1, 'Mongolia', 'Brunei Darussalam', 'Balistreri Club', '4', 'ut', 'A4', '412', 'North Ruth', NULL, '8614 Howell Port\r\nSouth Kitty, NC 67322-1215', '4794 Anderson Station\r\nMyraville, NM 84228-7594', 'Pariatur animi libero omnis alias et ratione aut ut.', 1, 'Pablo', 'Johnathon', 'KWD', NULL, NULL, '51.000', '87.000', '98.000', '92.000', '21.000', 1, 0, 1, '1988-07-26 20:44:42', 11, '2016-07-09 07:09:15', '1993-05-13 06:41:27', 1, 'http://pollich.biz/minus-quis-aut-dolorem-quo.html', 1, '58.65198200000000000000', '-37.47553200000000000000', 0, '1981-09-04 19:44:48', '1974-01-30 02:22:59', 1, NULL, NULL, NULL, 'http://www.stehr.info/veniam-fugit-dolorem-fugiat-quia-aut-quisquam-mollitia-nobis.html', '7d0b6c17d9a67b3c1283623ac1c23c27', NULL, 1, NULL, '1', 'http://www.bailey.biz/optio-quaerat-qui-ipsum-iure', 'Sim Schaefer', '682-903-6845 x23570', 0, 1, 1, 1, '18.000', 'Raquel', '+1.339.799.3126', 'Consequatur et assumenda magni error vel amet.', NULL, NULL, NULL, NULL, NULL, 0),
('10', '10', 10, 'Coy', '1-249-656-2473', 91, 'earl44@hotmail.com', '10', 10, 'Abbott Ranch', 'Dolly Squares', 'House', '1', '8', '9', '1', 10, 10, 'Bolivia', 'Antigua and Barbuda', 'Kieran Freeway', '4', 'ipsa', 'A4', '412', 'Steuberberg', NULL, '55424 Moen Orchard\r\nSchinnerfort, OR 01726-3944', '96163 Carmelo Way\r\nCrooksland, NC 87052', 'Corrupti doloribus vitae omnis voluptas.', 10, 'Earline', 'Gene', 'KWD', NULL, NULL, '86.000', '60.000', '16.000', '88.000', '78.000', 4, 0, 1, '1996-04-17 06:29:59', 11, '1982-10-27 06:47:27', '1981-08-16 08:49:07', 10, 'http://ernser.biz/', 1, '66.78153800000000000000', '60.90916000000000000000', 1, '1991-08-03 01:33:02', '2000-11-19 17:49:53', 10, NULL, NULL, NULL, 'https://www.mcglynn.com/quia-omnis-at-ullam-fugit-eaque-sapiente', '2e27dc045d491e5f795e0188958027b7', NULL, 10, NULL, '10', 'http://www.schmitt.com/tempora-aperiam-est-et-est-accusamus-qui-tenetur.html', 'Monserrate Barrows', '1-358-679-6301 x6221', 0, 1, 1, 10, '17.000', 'Breanne', '1-970-858-9759', 'Et in officiis sit quo voluptatem qui.', NULL, NULL, NULL, NULL, NULL, 0),
('11', '11', 11, 'Monica', '(327) 378-7332 x21457', 91, 'sonny.windler@wintheiser.net', '11', 11, 'Daisha Trail', 'Klein Crescent', 'House', '3', '5', '3', '9', 11, 11, 'Belarus', 'Morocco', 'Sally Land', '4', 'vero', 'A4', '412', 'New Joany', NULL, '277 Corwin Via\r\nAglaeton, NJ 18805', '101 Bogan Crossing\r\nLake Elisha, SC 38375', 'Praesentium nesciunt necessitatibus rerum quo.', 11, 'Maximus', 'Lorenz', 'KWD', NULL, NULL, '81.000', '27.000', '45.000', '75.000', '42.000', 7, 0, 1, '1976-09-17 05:48:36', 11, '2002-06-10 15:04:27', '1977-07-10 01:20:34', 11, 'http://www.hills.biz/culpa-similique-sint-vel-consequatur-consequatur-repellendus-quo-quo.html', 1, '55.00318200000000000000', '-129.65926500000000000000', 1, '1991-09-28 13:48:32', '2005-09-04 09:25:08', 11, NULL, NULL, NULL, 'http://kshlerin.biz/cum-sint-quia-et-eligendi.html', '9c949785e65b2fb8f733383d8cc1caf4', NULL, 11, NULL, '11', 'http://predovic.com/omnis-sed-rerum-eaque-dignissimos-magni-et.html', 'Ricardo Schultz', '(269) 265-5403', 0, 1, 1, 11, '17.000', 'Cesar', '1-734-951-5464', 'Dolore illum ut velit aliquid dolorem deleniti.', NULL, NULL, NULL, NULL, NULL, 0),
('12', '12', 12, 'Roxanne', '(830) 958-7286', 91, 'zena.hilpert@erdman.com', '12', 12, 'Zachariah Overpass', 'Heathcote Brooks', 'House', '7', '6', '7', '2', 12, 12, 'Croatia', 'Rwanda', 'Larson Road', '4', 'facere', 'A4', '412', 'South Velvachester', NULL, '3695 Senger Walk Apt. 028\r\nNew Raphaellemouth, IA 78550-8320', '983 Danielle Well\r\nEast Karinaland, KS 74302-9897', 'Est nihil error ad et perferendis asperiores non esse.', 12, 'Darius', 'Alfreda', 'KWD', NULL, NULL, '27.000', '53.000', '71.000', '78.000', '69.000', 1, 0, 1, '2015-09-22 19:41:43', 11, '1977-10-21 05:24:18', '2018-05-30 18:23:08', 12, 'http://www.walsh.net/', 1, '-83.05101800000000000000', '140.42163500000000000000', 0, '2007-11-11 03:19:28', '1974-03-08 15:05:16', 12, NULL, NULL, NULL, 'https://shields.com/veritatis-est-similique-tempore-aperiam-vero.html', '1a96172e861d46698c8ac44dd1499b70', NULL, 12, NULL, '12', 'http://mayer.com/', 'Helmer Abbott', '204.871.3637 x7990', 0, 1, 1, 12, '14.000', 'Elian', '+1-757-356-8015', 'Laboriosam expedita delectus voluptas temporibus earum.', NULL, NULL, NULL, NULL, NULL, 0),
('13', '13', 13, 'Emelia', '347-676-3868', 91, 'prudence69@eichmann.com', '13', 13, 'Blick Camp', 'Berge Turnpike', 'House', '2', '10', '10', '10', 13, 13, 'Falkland Islands (Malvinas)', 'Tuvalu', 'Korey Stravenue', '4', 'similique', 'A4', '412', 'Ervinport', NULL, '62338 Schaefer Groves Suite 640\r\nPort Donatohaven, NJ 35839-5000', '2540 Little Springs Apt. 437\r\nPort Niko, NE 69396', 'Quis ut ab esse et hic.', 13, 'Terrance', 'Payton', 'KWD', NULL, NULL, '13.000', '21.000', '55.000', '83.000', '37.000', 7, 0, 1, '2008-08-04 11:54:38', 11, '2019-05-27 07:39:16', '1976-12-28 05:38:43', 13, 'http://corkery.com/', 1, '-21.88415700000000000000', '58.13689600000000000000', 1, '1982-01-22 15:53:52', '1976-12-16 09:51:02', 13, NULL, NULL, NULL, 'http://miller.com/est-facere-praesentium-perspiciatis-ducimus-repellendus-aspernatur-autem-delectus', '4d100b773ea03f5bddd69585b71cea11', NULL, 13, NULL, '13', 'http://berge.com/', 'Rubie Kuhn', '(864) 473-6425', 0, 1, 1, 13, '14.000', 'Adam', '729.916.3060', 'Impedit consectetur et cupiditate voluptates provident voluptates veritatis.', NULL, NULL, NULL, NULL, NULL, 0),
('14', '14', 14, 'Odie', '+1.857.276.6728', 91, 'maybelle59@gmail.com', '14', 14, 'Beahan Overpass', 'Levi Pass', 'House', '9', '8', '7', '8', 14, 14, 'Cote d\'Ivoire', 'Israel', 'Beaulah Isle', '4', 'qui', 'A4', '412', 'Kailynchester', NULL, '1085 Okuneva Station Suite 228\r\nMorarside, CO 06527-2131', '5991 Boyle Greens Suite 550\r\nRathmouth, MS 39824', 'Debitis et et in.', 14, 'Orlando', 'Hank', 'KWD', NULL, NULL, '79.000', '71.000', '23.000', '75.000', '86.000', 6, 0, 1, '1978-05-22 10:21:38', 11, '1981-05-31 06:46:12', '2020-11-19 00:01:08', 14, 'http://hegmann.com/et-qui-voluptate-vero-eum-velit-ut-laudantium-natus', 1, '36.83563100000000000000', '-179.95702200000000000000', 0, '1971-10-06 13:56:31', '1982-07-12 23:03:42', 14, NULL, NULL, NULL, 'http://www.schulist.com/', '1c255ff458a9ee61de221a467c897c34', NULL, 14, NULL, '14', 'http://www.wyman.com/nobis-veniam-ipsam-consequatur-ut-enim-quia-quis-quod', 'Elmira Tromp', '+1-979-327-1753', 0, 1, 1, 14, '17.000', 'Shanna', '764-279-8131 x0779', 'Incidunt recusandae magnam officiis.', NULL, NULL, NULL, NULL, NULL, 0),
('15', '15', 15, 'Blaise', '(361) 909-5254', 91, 'dolly33@yahoo.com', '15', 15, 'Reinger Drive', 'Peyton Squares', 'House', '6', '7', '7', '3', 15, 15, 'Guyana', 'Dominica', 'Meda Mountain', '4', 'in', 'A4', '412', 'New Juneland', NULL, '309 Camilla Wall Suite 695\r\nNorth Emeryville, MD 56430-5721', '7854 Kessler Brook Suite 843\r\nTarachester, NE 53406', 'Aut quia assumenda at dicta dignissimos possimus.', 15, 'Chet', 'Callie', 'KWD', NULL, NULL, '58.000', '83.000', '26.000', '24.000', '18.000', 4, 0, 1, '2009-10-11 21:23:41', 11, '1992-08-30 11:29:10', '2019-10-19 11:28:42', 15, 'http://haag.biz/amet-et-autem-et', 1, '39.26702600000000000000', '-75.63937200000000000000', 1, '1996-02-09 20:40:09', '1992-02-24 00:08:15', 15, NULL, NULL, NULL, 'http://www.franecki.info/', '68acd92233fef00b166264c5f1ccf813', NULL, 15, NULL, '15', 'http://hettinger.com/dolorem-suscipit-assumenda-est-animi', 'Jeramie Bergstrom', '+1-816-989-5713', 0, 1, 1, 15, '14.000', 'Makenzie', '1-930-798-6666', 'Quis praesentium ducimus incidunt debitis optio in in.', NULL, NULL, NULL, NULL, NULL, 0),
('16', '16', 16, 'Isabell', '318-631-5936 x976', 91, 'joshua.leuschke@hotmail.com', '16', 16, 'Bridie Corners', 'Katrine Well', 'House', '10', '10', '3', '2', 16, 16, 'United Kingdom', 'Djibouti', 'Freddy Oval', '4', 'tempore', 'A4', '412', 'Patfurt', NULL, '90132 Satterfield Place\r\nGustborough, NY 32957-1208', '4072 Cruickshank Route Suite 920\r\nLake Alessandraland, WV 03158-8418', 'Sit quasi accusamus praesentium facilis ut illum.', 16, 'Aida', 'Mose', 'KWD', NULL, NULL, '22.000', '80.000', '12.000', '74.000', '95.000', 6, 0, 1, '1973-01-29 16:00:04', 11, '1990-09-05 18:50:04', '1978-05-02 11:30:40', 16, 'http://www.bogan.info/', 1, '9.73290100000000000000', '145.03933900000000000000', 1, '2016-09-05 11:14:49', '1999-07-26 17:51:48', 16, NULL, NULL, NULL, 'http://www.corkery.com/reiciendis-ut-qui-architecto-et', '33f9f4c572ca63e3c792b59377d37730', NULL, 16, NULL, '16', 'http://hyatt.org/qui-animi-et-officiis.html', 'Haven Johnston', '1-208-810-3848 x608', 0, 1, 1, 16, '12.000', 'Jeffry', '+1 (353) 278-7930', 'Sequi totam tenetur quia consequatur dolorem autem incidunt aut.', NULL, NULL, NULL, NULL, NULL, 0),
('17', '17', 17, 'Jammie', '+1-950-652-0305', 91, 'cremin.luna@yahoo.com', '17', 17, 'Cristobal Greens', 'Lucile Lodge', 'House', '3', '5', '5', '3', 17, 17, 'Greenland', 'Sweden', 'Grimes Crest', '4', 'inventore', 'A4', '412', 'Ritchieton', NULL, '123 Ziemann Inlet\r\nRiceshire, WA 42725-7484', '78955 Heathcote Estate\r\nNew Carolynestad, MA 18652-4862', 'Ullam omnis aut molestias aliquid adipisci eum esse.', 17, 'Gonzalo', 'Sheridan', 'KWD', NULL, NULL, '94.000', '40.000', '31.000', '43.000', '89.000', 10, 0, 1, '2002-01-08 17:02:05', 11, '1986-08-14 09:57:10', '1977-09-23 10:00:43', 17, 'http://www.cormier.com/et-ea-est-sed', 1, '-33.12187500000000000000', '114.77019400000000000000', 0, '1997-01-18 11:58:04', '1987-05-04 23:10:26', 17, NULL, NULL, NULL, 'https://wintheiser.com/molestiae-inventore-voluptas-sit-velit-tempora-atque-voluptas.html', '18fd8d994a2657f703380613d51ffdc1', NULL, 17, NULL, '17', 'https://kreiger.com/rerum-qui-expedita-ut-dolores-quia-iusto.html', 'Sonya Lakin', '+1-819-660-0552', 0, 1, 1, 17, '11.000', 'Myrtice', '451.297.4755 x669', 'Consequatur porro qui rerum doloremque perspiciatis.', NULL, NULL, NULL, NULL, NULL, 0),
('18', '18', 18, 'Alanis', '+1 (654) 312-6012', 91, 'reichmann@hotmail.com', '18', 18, 'Hans Village', 'Darron Via', 'House', '5', '7', '4', '8', 18, 18, 'Honduras', 'Latvia', 'Mills Court', '4', 'eos', 'A4', '412', 'Heloisechester', NULL, '6410 Dion Wall Apt. 957\r\nEast Willytown, ND 27010-6697', '26397 Bartell Common Apt. 077\r\nNew Calistaland, AL 32581', 'Cumque cum repudiandae ipsum nesciunt provident voluptate eaque.', 18, 'Waylon', 'Chance', 'KWD', NULL, NULL, '76.000', '89.000', '99.000', '75.000', '75.000', 4, 0, 1, '1995-11-04 22:18:10', 11, '2002-04-15 00:13:30', '2014-11-01 14:50:59', 18, 'https://lindgren.com/eum-qui-esse-rerum-delectus-dolores-in-distinctio-nemo.html', 1, '-75.45334400000000000000', '67.01683700000000000000', 1, '2007-06-03 15:05:43', '1982-08-09 05:37:17', 18, NULL, NULL, NULL, 'http://harber.biz/dolores-perspiciatis-tenetur-quia-velit', '58dc640aa7b25c661c43ca1d483478bd', NULL, 18, NULL, '18', 'https://www.marvin.com/non-nihil-animi-id-ab-perferendis', 'Marilyne Ritchie', '1-846-483-9209', 0, 1, 1, 18, '16.000', 'Doyle', '234-440-9212 x061', 'Doloribus quia numquam vero assumenda itaque numquam.', NULL, NULL, NULL, NULL, NULL, 0),
('19', '19', 19, 'Caroline', '1-581-855-9805', 91, 'beatty.elinore@gmail.com', '19', 19, 'Eileen Forges', 'Brakus Pine', 'House', '3', '10', '6', '8', 19, 19, 'Senegal', 'Saint Pierre and Miquelon', 'Trudie Lights', '4', 'doloribus', 'A4', '412', 'Elouiseville', NULL, '523 Arlie Extensions\r\nJohnathonview, NJ 46381', '84829 Candace Passage Suite 431\r\nLarkinview, MI 47283', 'Nemo voluptatem mollitia optio nostrum nihil.', 19, 'Helene', 'Erling', 'KWD', NULL, NULL, '25.000', '57.000', '38.000', '65.000', '16.000', 4, 0, 1, '1970-07-30 02:12:58', 11, '1992-11-24 13:17:31', '1981-10-25 02:40:05', 19, 'https://www.rolfson.com/dignissimos-aut-voluptas-voluptates-accusamus', 1, '78.83935400000000000000', '53.31874000000000000000', 1, '2008-09-22 19:09:01', '1970-04-06 17:54:07', 19, NULL, NULL, NULL, 'http://www.wilkinson.com/qui-illum-similique-dolor-quia-dolore-laborum.html', 'c89e5bd5f540acf95cc3cea86b75ed6a', NULL, 19, NULL, '19', 'http://www.stamm.com/alias-rerum-labore-aut-occaecati-in-voluptatem-sit', 'Filomena Mante', '+1-572-263-0966', 0, 1, 1, 19, '16.000', 'Leif', '767.719.5048 x16544', 'Rerum at enim sequi recusandae et repudiandae.', NULL, NULL, NULL, NULL, NULL, 0),
('2', '2', 2, 'Lydia', '463.276.9733', 91, 'ocole@reichel.com', '2', 2, 'Emanuel Cliffs', 'Keith Village', 'House', '2', '9', '4', '9', 2, 2, 'Benin', 'Russian Federation', 'Hegmann Mills', '4', 'non', 'A4', '412', 'Collinsbury', NULL, '6651 Macejkovic Key Suite 014\r\nFramiview, MD 16680-2369', '479 Jacques Isle\r\nNew Noemi, IN 43557', 'Eum ea animi suscipit quasi aliquid numquam et magni.', 2, 'Emory', 'Donald', 'KWD', NULL, NULL, '95.000', '19.000', '68.000', '35.000', '72.000', 4, 0, 1, '1985-03-10 05:53:37', 11, '1981-03-22 18:45:24', '2020-03-25 08:09:44', 2, 'https://fritsch.com/ipsa-excepturi-rem-est-autem-et-aperiam-nisi.html', 1, '76.10887600000000000000', '18.66791900000000000000', 0, '2011-04-11 20:48:56', '1996-02-26 16:21:28', 2, NULL, NULL, NULL, 'http://orn.net/quis-sed-assumenda-et-asperiores', 'b0b2f3741bb5e040ea7c787bda7241f3', NULL, 2, NULL, '2', 'https://green.com/delectus-beatae-quam-eaque-aspernatur-sit-sit.html', 'Jules Marquardt', '331-377-8327', 0, 1, 1, 2, '13.000', 'Mertie', '515-582-5216 x75635', 'Aliquid ipsum molestias dolores consequatur.', NULL, NULL, NULL, NULL, NULL, 0),
('20', '20', 20, 'Liana', '+1-563-495-1027', 91, 'deangelo.walsh@larson.com', '20', 20, 'Keebler Light', 'Emmanuelle Mission', 'House', '3', '3', '9', '1', 20, 20, 'Djibouti', 'Algeria', 'Morar Forge', '4', 'tenetur', 'A4', '412', 'North Gilbertohaven', NULL, '20165 Eulalia Knoll Suite 387\r\nNew Clareview, AK 60494-8701', '16983 Beier Meadows\r\nLakinburgh, PA 18115-8076', 'Qui aspernatur nihil voluptatem cum labore quia consequatur.', 20, 'Orrin', 'Pierre', 'KWD', NULL, NULL, '73.000', '48.000', '55.000', '88.000', '77.000', 1, 0, 1, '2017-03-09 12:30:54', 11, '1991-04-29 04:06:06', '1982-02-12 06:37:55', 20, 'http://www.kozey.com/voluptatum-dolores-quasi-neque.html', 1, '14.28712800000000000000', '-168.50681500000000000000', 0, '1986-11-30 07:59:53', '1970-03-03 20:34:18', 20, NULL, NULL, NULL, 'http://ledner.info/eos-quia-voluptatem-ipsam-est-qui', 'b33bc33a05d0274e5279d05891dc2dde', NULL, 20, NULL, '20', 'http://hamill.com/enim-error-reprehenderit-quis-soluta-sed', 'Jacques Collins', '627.217.3332 x4177', 0, 1, 1, 20, '15.000', 'Kaci', '(815) 448-9484', 'Eum fugiat inventore veniam odit aperiam dignissimos ad.', NULL, NULL, NULL, NULL, NULL, 0),
('21', '21', 21, 'Dejuan', '1-642-818-6447', 91, 'wunsch.catalina@hotmail.com', '21', 21, 'Kirlin Centers', 'Sawayn Wall', 'House', '2', '6', '10', '6', 21, 21, 'Bosnia and Herzegovina', 'Panama', 'Kaya River', '4', 'vel', 'A4', '412', 'Port Gideonport', NULL, '54676 Alexandrine Road\r\nGreenfelderborough, WY 21356', '21336 Franecki Motorway\r\nHartmannmouth, AZ 59868-1583', 'Suscipit sed at consequatur voluptatibus quia possimus.', 21, 'Althea', 'Harry', 'KWD', NULL, NULL, '64.000', '93.000', '33.000', '95.000', '33.000', 6, 0, 1, '2003-12-23 20:58:30', 11, '1993-05-25 15:40:54', '1976-05-16 08:05:12', 21, 'https://witting.biz/nemo-itaque-optio-reiciendis-ipsum.html', 1, '-25.26568300000000000000', '-175.80531200000000000000', 1, '1991-04-04 03:17:11', '1981-04-04 20:37:31', 21, NULL, NULL, NULL, 'http://johns.com/et-molestiae-enim-harum', 'bdc4faed884dfae8c3a9ccf6cec59b81', NULL, 21, NULL, '21', 'http://www.romaguera.org/deserunt-enim-amet-vitae', 'Collin Gorczany', '942-486-0323 x81159', 0, 1, 1, 21, '11.000', 'Ashtyn', '915-644-1637 x868', 'Mollitia inventore mollitia deserunt aut eum dignissimos.', NULL, NULL, NULL, NULL, NULL, 0),
('22', '22', 22, 'Christine', '507.982.5348', 91, 'curtis76@yahoo.com', '22', 22, 'Brekke Fall', 'Cole Villages', 'House', '3', '8', '8', '8', 22, 22, 'Antarctica (the territory South of 60 deg S)', 'Cook Islands', 'Torphy Villages', '4', 'neque', 'A4', '412', 'Port Cleoville', NULL, '2974 Grant Overpass Suite 521\r\nLake Niko, ME 53221-9407', '13340 Nicole Knolls\r\nNorth Derrickside, VT 03230-6853', 'Qui fugiat nobis molestias qui perferendis ullam.', 22, 'Shea', 'Nathan', 'KWD', NULL, NULL, '26.000', '97.000', '37.000', '92.000', '93.000', 6, 0, 1, '1971-06-06 12:58:39', 11, '2002-09-02 01:17:07', '1980-12-22 15:33:30', 22, 'http://von.net/accusantium-cupiditate-et-accusamus-in', 1, '-36.64586900000000000000', '-112.55080000000000000000', 0, '1990-01-24 23:50:24', '1984-10-23 09:23:46', 22, NULL, NULL, NULL, 'http://www.kunze.com/', 'cefb29863ee31154f3c4783af40dd707', NULL, 22, NULL, '22', 'http://mcglynn.com/et-qui-molestiae-quaerat-non-quia-aspernatur.html', 'Helena Jakubowski', '1-248-903-8571 x939', 0, 1, 1, 22, '20.000', 'Amira', '1-750-955-7269 x643', 'Id aut reprehenderit numquam aperiam molestiae.', NULL, NULL, NULL, NULL, NULL, 0),
('23', '23', 23, 'Annabell', '350-739-4608 x790', 91, 'hluettgen@welch.com', '23', 23, 'Rupert Summit', 'Elisa Point', 'House', '2', '5', '10', '7', 23, 23, 'Norfolk Island', 'Italy', 'Jayne Parks', '4', 'repellendus', 'A4', '412', 'East Lily', NULL, '999 Ferry Key\r\nRoweland, NV 15019-3494', '71808 Wilderman Trace Suite 202\r\nConnview, MT 13810', 'Totam non placeat qui tenetur deserunt quo.', 23, 'William', 'Jensen', 'KWD', NULL, NULL, '46.000', '83.000', '82.000', '98.000', '26.000', 4, 0, 1, '1977-12-10 12:32:33', 11, '2006-06-12 23:49:18', '2004-09-18 01:59:07', 23, 'http://gleason.com/qui-et-delectus-excepturi', 1, '-60.64466300000000000000', '84.38214100000000000000', 0, '2017-12-27 23:30:38', '1975-07-30 16:44:16', 23, NULL, NULL, NULL, 'http://mills.com/dignissimos-voluptatibus-est-soluta-non-amet-tenetur-facere-et', '2f72964943f56a8bd79ab3628137b22f', NULL, 23, NULL, '23', 'http://shanahan.net/perferendis-provident-recusandae-repellendus-nihil-unde-tenetur', 'Easton Walker', '624.599.7141 x2762', 0, 1, 1, 23, '17.000', 'Aiden', '1-336-623-3528 x75212', 'Dolores et quam rerum minus voluptates.', NULL, NULL, NULL, NULL, NULL, 0),
('24', '24', 24, 'Dusty', '520.995.3087', 91, 'lang.roscoe@gmail.com', '24', 24, 'Narciso Park', 'Meagan Loaf', 'House', '1', '4', '5', '8', 24, 24, 'American Samoa', 'Lesotho', 'Reanna Turnpike', '4', 'unde', 'A4', '412', 'Giovannyfurt', NULL, '83701 Reinger Street Apt. 099\r\nStiedemanntown, VT 97459-7737', '632 Watsica Mission Apt. 192\r\nSouth Lyla, NJ 11734-3586', 'Et qui deserunt in quibusdam cupiditate voluptate tempora.', 24, 'Ashleigh', 'Rachael', 'KWD', NULL, NULL, '91.000', '88.000', '38.000', '44.000', '49.000', 6, 0, 1, '2020-03-07 03:20:02', 11, '1987-01-16 03:41:41', '1991-07-17 03:53:43', 24, 'http://schumm.com/tempora-laudantium-eos-molestiae-ipsam-ratione-nesciunt.html', 1, '8.83275300000000000000', '-50.35523100000000000000', 0, '2017-08-15 09:33:51', '1980-05-26 10:08:23', 24, NULL, NULL, NULL, 'http://connelly.com/amet-repellat-illo-tempora-adipisci-quia-nostrum', '1380327a1955966d968fa31c5787ddf1', NULL, 24, NULL, '24', 'http://www.senger.com/corporis-nulla-soluta-non-nihil-aut-repellat-non-accusantium.html', 'Luciano Kessler', '1-321-251-6298', 0, 1, 1, 24, '15.000', 'Sonia', '413-417-3972', 'Ipsa eum ipsa in et nihil.', NULL, NULL, NULL, NULL, NULL, 0),
('25', '25', 25, 'Verdie', '1-232-654-2625', 91, 'isabell.boehm@heaney.com', '25', 25, 'Lindgren Extension', 'Velda Orchard', 'House', '6', '5', '7', '3', 25, 25, 'French Guiana', 'Portugal', 'Rachel Manors', '4', 'eveniet', 'A4', '412', 'Pagacport', NULL, '96171 Sabrina Fork\r\nRolfsonborough, CO 34384-3251', '514 Boris Highway Apt. 547\r\nJessetown, FL 85986', 'Aut in vel aut.', 25, 'Loyce', 'Precious', 'KWD', NULL, NULL, '62.000', '47.000', '37.000', '91.000', '19.000', 1, 0, 1, '1989-07-11 10:30:56', 11, '2011-12-06 10:52:48', '1996-12-13 08:02:40', 25, 'http://johnston.com/quae-unde-voluptate-numquam-expedita-aut', 1, '-29.53211400000000000000', '-102.39198700000000000000', 0, '2012-08-12 18:51:25', '2016-01-21 19:33:55', 25, NULL, NULL, NULL, 'http://conroy.com/modi-qui-accusantium-qui-ex-est-ea', 'ce07023dc54e45b522b612c985f3bef0', NULL, 25, NULL, '25', 'http://gottlieb.biz/ut-consequatur-omnis-quo-sed.html', 'Randy VonRueden', '+1-797-455-8230', 0, 1, 1, 25, '15.000', 'Lukas', '1-848-638-4672 x078', 'Ut recusandae velit et.', NULL, NULL, NULL, NULL, NULL, 0),
('26', '26', 26, 'Rae', '403-913-6508 x965', 91, 'hking@yahoo.com', '26', 26, 'Schneider Road', 'Dickens Stravenue', 'House', '7', '3', '9', '2', 26, 26, 'French Polynesia', 'Saudi Arabia', 'Dena Extensions', '4', 'consequatur', 'A4', '412', 'New Virgilfort', NULL, '535 Koch Grove Suite 915\r\nLake Rosetta, AL 07344-2467', '266 Tracy Point Apt. 140\r\nPort Gino, WI 71067-2555', 'Sit possimus sequi sint nesciunt qui earum quas.', 26, 'Maryse', 'Jennifer', 'KWD', NULL, NULL, '47.000', '90.000', '83.000', '11.000', '95.000', 7, 0, 1, '1992-10-31 01:38:10', 11, '1986-01-28 06:07:57', '1975-06-14 15:38:59', 26, 'http://cartwright.biz/cumque-sint-aspernatur-incidunt-qui-iste-temporibus.html', 1, '-2.61938000000000000000', '-21.54326700000000000000', 1, '1990-04-29 21:04:15', '1980-08-29 11:05:17', 26, NULL, NULL, NULL, 'http://feil.net/ullam-laborum-quo-dolor-pariatur.html', '391fb5384571e84088f577955a1808c2', NULL, 26, NULL, '26', 'http://toy.com/qui-id-et-deserunt-id-adipisci-fugiat.html', 'Moshe Rempel', '(431) 577-6244', 0, 1, 1, 26, '20.000', 'Isabel', '(920) 303-8353 x550', 'Error dolor molestiae eaque consequuntur et ex beatae quibusdam.', NULL, NULL, NULL, NULL, NULL, 0),
('27', '27', 27, 'Lesley', '601.250.7705 x38055', 91, 'rosemary.zemlak@bins.info', '27', 27, 'Ottilie Mission', 'Kelsie Curve', 'House', '4', '6', '6', '8', 27, 27, 'Suriname', 'Gibraltar', 'Moshe Groves', '4', 'quasi', 'A4', '412', 'North Britneyburgh', NULL, '299 Brook Manor\r\nLake Fredside, AR 05090', '821 Elijah Throughway Suite 807\r\nWest Lilianaland, WI 88301', 'Nihil autem earum tenetur omnis nesciunt.', 27, 'Mekhi', 'Esteban', 'KWD', NULL, NULL, '51.000', '65.000', '19.000', '11.000', '75.000', 1, 0, 1, '1973-08-30 06:36:50', 11, '1991-06-19 21:31:36', '1989-08-16 21:48:28', 27, 'http://marvin.com/vel-id-ipsum-vero-fuga-sint-aut-labore', 1, '-83.05263100000000000000', '-8.72546600000000000000', 0, '2004-02-23 22:17:27', '1986-07-11 16:55:50', 27, NULL, NULL, NULL, 'http://www.hintz.com/earum-fugiat-consectetur-sit-et', 'd27a52853464cc0af0d29df4b0106973', NULL, 27, NULL, '27', 'http://reilly.com/ut-explicabo-est-nisi-et-numquam-vitae.html', 'Xavier Corkery', '517.401.7289 x721', 0, 1, 1, 27, '11.000', 'Leif', '707-330-5250 x24304', 'Aut qui laborum cumque architecto fuga non.', NULL, NULL, NULL, NULL, NULL, 0),
('28', '28', 28, 'Jerod', '753.253.1403 x463', 91, 'xwhite@ziemann.net', '28', 28, 'Vivian Centers', 'Vicenta Gardens', 'House', '6', '8', '7', '7', 28, 28, 'South Africa', 'Antigua and Barbuda', 'Marty Gateway', '4', 'vitae', 'A4', '412', 'Derickville', NULL, '9364 Rigoberto Lake Suite 281\r\nLake Paris, TX 54922-8223', '597 Cummerata Forks\r\nNew Mateomouth, OK 47337-0947', 'In omnis omnis est ut.', 28, 'Elise', 'Herminia', 'KWD', NULL, NULL, '54.000', '19.000', '49.000', '13.000', '46.000', 1, 0, 1, '2021-03-16 11:37:51', 11, '1997-03-04 16:49:53', '1976-09-05 09:23:09', 28, 'http://metz.com/qui-quia-deleniti-assumenda', 1, '-65.78779900000000000000', '-126.57915900000000000000', 0, '1987-12-12 11:35:25', '1973-05-18 16:46:30', 28, NULL, NULL, NULL, 'http://yundt.com/', '928498e6a31e9fa8a2358dd4c1ee7e0c', NULL, 28, NULL, '28', 'http://www.goyette.com/explicabo-dolores-voluptatem-ab-blanditiis-dolores', 'Jamir Mayert', '1-513-484-9442', 0, 1, 1, 28, '12.000', 'Dexter', '+14284867830', 'Quam quasi dolor ut ipsum dolorem illo pariatur.', NULL, NULL, NULL, NULL, NULL, 0),
('29', '29', 29, 'Armand', '1-373-317-8084', 91, 'jena54@paucek.net', '29', 29, 'Sporer Causeway', 'Mariano Shoals', 'House', '3', '10', '2', '10', 29, 29, 'Micronesia', 'Aruba', 'Ramiro Isle', '4', 'consequatur', 'A4', '412', 'Kochville', NULL, '5679 Ondricka Mountain Apt. 044\r\nKimberlystad, TX 53781-3449', '94201 Goyette Road Suite 148\r\nCassinville, MS 87821', 'Doloremque reiciendis nam animi asperiores commodi deleniti.', 29, 'Stacy', 'Rebeca', 'KWD', NULL, NULL, '48.000', '63.000', '46.000', '74.000', '24.000', 7, 0, 1, '2011-03-08 20:50:46', 11, '2003-03-18 15:34:02', '1992-06-22 03:06:32', 29, 'http://miller.com/voluptas-quia-eum-ad-quia', 1, '78.10659500000000000000', '-26.92312500000000000000', 0, '1992-09-01 05:12:08', '1974-07-03 08:16:23', 29, NULL, NULL, NULL, 'http://www.brakus.com/', 'a93782a71e051214535dae00674aff61', NULL, 29, NULL, '29', 'http://crist.com/', 'Horacio Goyette', '576-964-5907', 0, 1, 1, 29, '13.000', 'Delfina', '656-785-5540 x2475', 'Autem vero et a unde aut quia voluptate.', NULL, NULL, NULL, NULL, NULL, 0),
('3', '3', 3, 'Nicolas', '+1.476.780.8768', 91, 'tad62@yahoo.com', '3', 3, 'Walter Oval', 'Olaf Parks', 'House', '4', '1', '1', '4', 3, 3, 'Pitcairn Islands', 'Tanzania', 'Beahan Estates', '4', 'iusto', 'A4', '412', 'East Lianaberg', NULL, '944 Kaleigh Shoal\r\nJanessaport, AZ 90382', '8592 Ian Skyway Suite 135\r\nWest Laurencestad, VT 35049-5088', 'Sed id consequatur aut sit.', 3, 'Kiley', 'Glenda', 'KWD', NULL, NULL, '67.000', '84.000', '38.000', '29.000', '57.000', 6, 0, 1, '2006-05-16 20:54:46', 11, '1998-04-13 02:03:26', '1970-10-02 11:47:07', 3, 'http://www.haag.org/excepturi-non-ab-ducimus-corporis-commodi-et', 1, '28.66015200000000000000', '99.04434400000000000000', 1, '2002-02-13 15:35:10', '1983-03-15 07:43:24', 3, NULL, NULL, NULL, 'http://www.ryan.info/quia-eligendi-enim-possimus-ratione', 'da0c2d6dc860e0c8acf2d3c0186b862c', NULL, 3, NULL, '3', 'http://kshlerin.com/minus-aut-eaque-explicabo-et-ut-veritatis-omnis', 'Anthony Huel', '640-263-4039 x5974', 0, 1, 1, 3, '13.000', 'Kattie', '+13305789810', 'Optio velit est corrupti animi libero ut sunt.', NULL, NULL, NULL, NULL, NULL, 0),
('30', '30', 30, 'Jalyn', '+15325133823', 91, 'evangeline78@cronin.com', '30', 30, 'Stracke Fort', 'Lockman Lake', 'House', '9', '1', '7', '9', 30, 30, 'Estonia', 'Canada', 'Orn Run', '4', 'dolore', 'A4', '412', 'Damonside', NULL, '81259 Leone Divide Suite 172\r\nLake Nonabury, AR 72181-6437', '10420 Aubrey Ford Suite 682\r\nPort Kimfort, MD 31691', 'Officia ex aperiam et quos consectetur omnis aut.', 30, 'Jermaine', 'Vita', 'KWD', NULL, NULL, '55.000', '93.000', '52.000', '13.000', '97.000', 10, 0, 1, '2009-10-25 20:18:42', 11, '1985-05-17 11:42:20', '1975-10-09 15:44:44', 30, 'http://spinka.com/voluptas-rem-consequatur-et-maxime-quia-ut-quia-aut', 1, '-10.14550700000000000000', '-6.12093800000000000000', 1, '1999-01-18 00:19:16', '2017-10-04 14:33:51', 30, NULL, NULL, NULL, 'https://borer.com/reprehenderit-nostrum-illo-consequuntur-ut-nihil-quis.html', '6b2473c455b0ef87ab205f9b14af3dca', NULL, 30, NULL, '30', 'http://hettinger.com/deleniti-corrupti-incidunt-architecto-voluptas.html', 'Matilda Becker', '541.364.9033 x9856', 0, 1, 1, 30, '13.000', 'Carson', '+1-361-400-5831', 'Omnis perspiciatis sed amet ratione reiciendis.', NULL, NULL, NULL, NULL, NULL, 0),
('31', '31', 31, 'Bulah', '787.378.8339', 91, 'armstrong.adriel@oberbrunner.com', '31', 31, 'Lucile Route', 'Asa Common', 'House', '4', '2', '3', '10', 31, 31, 'Turkey', 'Djibouti', 'Cummerata Gardens', '4', 'est', 'A4', '412', 'Neomaborough', NULL, '3240 Bahringer Mount\r\nLake Prince, KY 63890', '307 Abshire Locks Suite 743\r\nWest Sean, OK 12082', 'Rerum aut corrupti nobis aliquam.', 31, 'Evelyn', 'Rowan', 'KWD', NULL, NULL, '31.000', '78.000', '66.000', '80.000', '34.000', 4, 0, 1, '1974-11-07 16:51:31', 11, '2012-01-22 11:37:20', '2000-11-16 14:40:24', 31, 'https://www.sauer.com/culpa-unde-unde-harum-vel-nihil-est', 1, '31.22882100000000000000', '97.59102700000000000000', 0, '2003-04-14 22:41:10', '2000-11-21 22:42:10', 31, NULL, NULL, NULL, 'http://lesch.info/', '079e50fc196969693fc285df93d29355', NULL, 31, NULL, '31', 'http://wisoky.com/dicta-maiores-vitae-et-culpa', 'Russ Beatty', '+15309333310', 0, 1, 1, 31, '19.000', 'Elmo', '(830) 477-2662 x5666', 'Omnis sit et debitis sed aut consectetur tempora facere.', NULL, NULL, NULL, NULL, NULL, 0),
('32', '32', 32, 'Jasper', '1-993-980-9337 x58202', 91, 'wisoky.isom@hotmail.com', '32', 32, 'Douglas Cape', 'Mabelle Fords', 'House', '3', '1', '6', '3', 32, 32, 'Iran', 'Sri Lanka', 'Monty Valley', '4', 'recusandae', 'A4', '412', 'Stephaniatown', NULL, '712 Kohler Expressway Suite 026\r\nWest Silas, PA 90098-7844', '8954 Emerald Shoals\r\nSouth Elinoreton, MD 76583-6872', 'Autem alias nostrum quo ipsam et.', 32, 'Ruben', 'Jesus', 'KWD', NULL, NULL, '23.000', '54.000', '75.000', '11.000', '26.000', 6, 0, 1, '2018-06-11 08:06:44', 11, '2020-02-08 00:37:56', '2013-12-28 03:02:24', 32, 'http://rutherford.com/', 1, '-54.51713100000000000000', '-119.53432500000000000000', 0, '2013-12-23 15:47:14', '2008-12-29 22:20:55', 32, NULL, NULL, NULL, 'http://shanahan.info/pariatur-blanditiis-qui-ut-porro-provident-exercitationem-fuga.html', '7016bb238b812eb76dd6ff40027c2840', NULL, 32, NULL, '32', 'http://www.homenick.com/et-dolorem-quaerat-corporis-aut-et-sit-est', 'Madaline Brekke', '+1 (929) 212-0076', 0, 1, 1, 32, '19.000', 'Andreane', '+1-604-396-3285', 'Distinctio distinctio voluptatem quidem in.', NULL, NULL, NULL, NULL, NULL, 0),
('33', '33', 33, 'Buford', '+18468990196', 91, 'hills.amy@yahoo.com', '33', 33, 'Talia Stravenue', 'Cormier Street', 'House', '5', '4', '6', '8', 33, 33, 'French Southern Territories', 'Cayman Islands', 'Daniel Knolls', '4', 'beatae', 'A4', '412', 'Gregoriaborough', NULL, '828 Roberta Village\r\nLake Maudie, TX 70702-1135', '735 Kevin Circles\r\nDickenshaven, AK 09624', 'Quibusdam rem accusantium occaecati possimus minus.', 33, 'Jerry', 'Elmore', 'KWD', NULL, NULL, '14.000', '80.000', '79.000', '24.000', '96.000', 7, 0, 1, '1980-10-16 18:31:13', 11, '2007-07-26 04:50:02', '2004-10-20 13:00:11', 33, 'http://www.cole.org/quam-repellat-illo-dicta-commodi-rerum-voluptatem-voluptatem', 1, '50.26242200000000000000', '50.31360400000000000000', 0, '2015-06-16 13:46:08', '2019-12-15 03:35:53', 33, NULL, NULL, NULL, 'https://kuhic.com/et-quia-vitae-saepe-aut-recusandae-sed.html', '97ac37de655796dde6c78f10ab2bf9c6', NULL, 33, NULL, '33', 'http://weber.com/eligendi-possimus-consequatur-est-quia-ut-velit-autem', 'Dexter McKenzie', '1-420-601-6159', 0, 1, 1, 33, '16.000', 'Jed', '+1-548-543-4292', 'Iure molestias vel quis.', NULL, NULL, NULL, NULL, NULL, 0),
('34', '34', 34, 'Madisyn', '1-397-514-4272 x601', 91, 'doyle95@spencer.com', '34', 34, 'Jett Highway', 'Luther Expressway', 'House', '4', '8', '1', '9', 34, 34, 'Falkland Islands (Malvinas)', 'Bosnia and Herzegovina', 'Emmanuel Key', '4', 'maiores', 'A4', '412', 'New Rosalind', NULL, '47708 Gibson Alley\r\nMelyssastad, SC 72543-9175', '5739 Schamberger Harbors Apt. 307\r\nNew Deon, IA 98241-9237', 'Eius dolorem magni fugit facilis.', 34, 'Jeffrey', 'Genoveva', 'KWD', NULL, NULL, '10.000', '93.000', '62.000', '70.000', '41.000', 1, 0, 1, '1993-08-11 08:48:51', 11, '1978-10-04 00:38:26', '1981-06-05 14:58:16', 34, 'http://quigley.com/molestiae-harum-possimus-architecto-in-culpa-totam.html', 1, '34.26411400000000000000', '176.73348700000000000000', 0, '1984-06-10 08:46:31', '1988-11-02 16:49:51', 34, NULL, NULL, NULL, 'http://keeling.org/voluptas-ut-fugiat-excepturi.html', 'caa42d854141ba6b22bea42bd4a3c1ce', NULL, 34, NULL, '34', 'http://considine.com/iusto-dignissimos-vel-molestiae-libero-dolorem', 'Zion Kunze', '457.271.7797 x6227', 0, 1, 1, 34, '12.000', 'Peggie', '364-218-7068 x5346', 'Corporis accusamus sed maiores sunt officia aliquid.', NULL, NULL, NULL, NULL, NULL, 0),
('35', '35', 35, 'Destany', '671-864-6267 x22171', 91, 'lueilwitz.lyric@hotmail.com', '35', 35, 'Conroy Summit', 'Beer Harbor', 'House', '8', '4', '7', '3', 35, 35, 'United Kingdom', 'New Caledonia', 'Alena Land', '4', 'assumenda', 'A4', '412', 'West Majorfurt', NULL, '5648 Goodwin Hill\r\nValerieville, OK 83211-6600', '88269 Cremin Hollow Suite 115\r\nEast Emersonside, SD 79079-4723', 'Et et velit amet temporibus adipisci et.', 35, 'Cecile', 'Randal', 'KWD', NULL, NULL, '67.000', '63.000', '26.000', '51.000', '86.000', 7, 0, 1, '1975-06-05 19:08:38', 11, '2018-07-11 18:41:11', '2004-11-18 08:27:03', 35, 'http://kunze.org/facilis-impedit-accusamus-blanditiis-corrupti-quod.html', 1, '-24.76132000000000000000', '19.30272600000000000000', 0, '2012-01-04 04:50:36', '1981-02-05 06:04:39', 35, NULL, NULL, NULL, 'https://labadie.info/animi-molestiae-qui-labore-qui-deleniti.html', '377b75899b224f5e18c789a82e18bb61', NULL, 35, NULL, '35', 'http://www.miller.com/quidem-dolorem-facilis-qui-consequatur-aperiam-libero', 'Krystal Tremblay', '(209) 287-3472', 0, 1, 1, 35, '11.000', 'Fatima', '+1.965.434.0558', 'Non sit dolor repudiandae vitae.', NULL, NULL, NULL, NULL, NULL, 0),
('36', '36', 36, 'Mason', '386-352-0541', 91, 'caleigh.west@reichert.com', '36', 36, 'Franecki Village', 'Davis Cove', 'House', '9', '2', '4', '10', 36, 36, 'Syrian Arab Republic', 'Dominica', 'Romaguera Haven', '4', 'id', 'A4', '412', 'Boydborough', NULL, '4464 Treutel Square\r\nNew Zelma, NV 56604-4353', '4112 Sipes Oval\r\nLuciohaven, HI 55227', 'Maxime et aperiam eaque sequi ut in ut.', 36, 'Aric', 'Felipa', 'KWD', NULL, NULL, '62.000', '98.000', '69.000', '71.000', '26.000', 7, 0, 1, '2011-07-26 10:06:33', 11, '1989-04-30 03:08:34', '2000-03-01 17:46:01', 36, 'https://parker.com/aspernatur-cum-sapiente-tempore-dolore-perferendis-consequatur-eligendi.html', 1, '32.72027800000000000000', '104.07314300000000000000', 0, '1971-08-30 09:37:30', '1975-05-10 15:00:25', 36, NULL, NULL, NULL, 'http://kemmer.com/', 'c4de31aa52786924186431b0d5df8333', NULL, 36, NULL, '36', 'http://www.ziemann.com/vitae-impedit-maxime-eaque-similique-eaque-quia-delectus', 'Carli Metz', '+1.773.836.4025', 0, 1, 1, 36, '17.000', 'Kyle', '(770) 347-6803', 'Deserunt deserunt qui delectus harum.', NULL, NULL, NULL, NULL, NULL, 0),
('37', '37', 37, 'Giovanna', '1-494-394-6347 x63355', 91, 'hand.arvilla@stiedemann.org', '37', 37, 'Ernser Road', 'Susana Courts', 'House', '1', '2', '6', '9', 37, 37, 'Tuvalu', 'Macedonia', 'Don Inlet', '4', 'aut', 'A4', '412', 'Valeriemouth', NULL, '2940 Mayer Park Suite 402\r\nMullerchester, DC 22238', '5724 Becker Canyon\r\nLake Name, UT 85508-0306', 'Eaque fugiat mollitia quis.', 37, 'Westley', 'Ericka', 'KWD', NULL, NULL, '36.000', '80.000', '67.000', '49.000', '92.000', 6, 0, 1, '2003-11-06 03:47:20', 11, '1972-04-12 10:08:56', '1979-11-05 06:15:14', 37, 'https://collins.com/reiciendis-odio-quaerat-quisquam-fuga.html', 1, '-74.93612400000000000000', '36.96550800000000000000', 0, '2001-12-24 19:02:29', '2006-03-11 07:13:46', 37, NULL, NULL, NULL, 'http://www.strosin.com/ut-enim-placeat-est-ipsum-alias-minus-qui-id.html', '53968ec7b1cc3df246829bf1a7342229', NULL, 37, NULL, '37', 'http://www.huels.com/vel-qui-laudantium-molestias-odio.html', 'Kaylah Zieme', '(541) 867-8350', 0, 1, 1, 37, '20.000', 'Makenzie', '(350) 528-5758 x875', 'Rerum aut non autem molestias sint.', NULL, NULL, NULL, NULL, NULL, 0),
('38', '38', 38, 'Muhammad', '826.252.4655', 91, 'hand.isabell@yahoo.com', '38', 38, 'Heaney Haven', 'Clemmie Crescent', 'House', '1', '5', '5', '9', 38, 38, 'Brazil', 'Zimbabwe', 'King Mount', '4', 'qui', 'A4', '412', 'South Johnpaulfort', NULL, '528 Minerva Trail\r\nEast Eulalia, PA 78558-3094', '9952 Retha Ports Apt. 023\r\nNorth Jessyville, MN 03352', 'Est hic unde aspernatur minus.', 38, 'Zena', 'Clemmie', 'KWD', NULL, NULL, '57.000', '50.000', '60.000', '56.000', '76.000', 6, 0, 1, '2014-11-18 03:15:10', 11, '1970-06-11 06:51:11', '2000-01-15 14:24:07', 38, 'http://www.kiehn.info/amet-totam-mollitia-temporibus-aliquid-voluptate.html', 1, '-65.58218900000000000000', '-140.88643100000000000000', 0, '2004-08-05 02:55:39', '2000-09-03 10:35:32', 38, NULL, NULL, NULL, 'https://www.farrell.com/facilis-et-molestiae-recusandae-dolorem-veniam-modi', 'f8d67b6c7e59b8abc91f54e0f745f187', NULL, 38, NULL, '38', 'http://www.lang.info/', 'Raegan Crona', '878-215-3572', 0, 1, 1, 38, '17.000', 'Andreanne', '(984) 631-9187 x7252', 'In in inventore quam officia.', NULL, NULL, NULL, NULL, NULL, 0),
('39', '39', 39, 'Marques', '547-438-4294 x138', 91, 'hansen.audreanne@fisher.com', '39', 39, 'Fay Drives', 'Destini Canyon', 'House', '9', '1', '9', '10', 39, 39, 'Denmark', 'Aruba', 'Nicklaus Rue', '4', 'ut', 'A4', '412', 'South Karli', NULL, '1218 Catharine Rest Apt. 416\r\nEast Chynaport, AZ 92893-6802', '257 Norval Mews Apt. 789\r\nRunteshire, LA 83373', 'Fugiat corrupti deserunt delectus veniam architecto expedita nihil qui.', 39, 'Hilton', 'Leila', 'KWD', NULL, NULL, '41.000', '63.000', '56.000', '16.000', '49.000', 6, 0, 1, '1977-05-13 12:36:07', 11, '1981-05-12 03:06:28', '2000-08-14 22:42:02', 39, 'http://jerde.net/similique-recusandae-expedita-veritatis-facilis-eos-cum.html', 1, '-52.40306200000000000000', '109.47480700000000000000', 0, '1983-12-10 07:23:47', '1985-03-07 11:47:25', 39, NULL, NULL, NULL, 'http://jacobi.com/dolorem-repellendus-est-repellat-deserunt-accusamus', '5fa7d59cba5001f8c8e5f6bc3e5c071d', NULL, 39, NULL, '39', 'http://gutmann.net/dolorum-nisi-alias-laboriosam-minima-minus-doloremque-quibusdam.html', 'Drake Quitzon', '(549) 689-0775 x8526', 0, 1, 1, 39, '20.000', 'Elfrieda', '+1.580.342.7656', 'Consequatur autem dolores est quisquam at cum.', NULL, NULL, NULL, NULL, NULL, 0),
('4', '4', 4, 'Linnie', '(282) 903-2571 x50904', 91, 'cleuschke@zboncak.com', '4', 4, 'Krajcik Extension', 'Gusikowski Turnpike', 'House', '8', '1', '1', '6', 4, 4, 'Bolivia', 'Georgia', 'Cleo Springs', '4', 'ex', 'A4', '412', 'West Enola', NULL, '9724 Denesik Isle\r\nStehrland, NC 64997', '214 Elza Land Suite 870\r\nPort Joey, IL 12299', 'Temporibus odio minus voluptatem voluptatem minus aliquid.', 4, 'Ollie', 'Laverne', 'KWD', NULL, NULL, '68.000', '48.000', '21.000', '76.000', '14.000', 10, 0, 1, '1972-11-05 21:50:20', 11, '1994-11-24 05:09:00', '2017-09-21 04:50:22', 4, 'http://www.armstrong.com/', 1, '29.08926700000000000000', '160.57151200000000000000', 1, '1991-12-05 13:15:59', '1981-02-25 23:36:48', 4, NULL, NULL, NULL, 'http://schmitt.com/', '81af6b959bb8f15dbfcef60428a09b66', NULL, 4, NULL, '4', 'http://www.ratke.com/', 'Vern Ruecker', '294-517-5280 x8147', 0, 1, 1, 4, '12.000', 'Estella', '1-592-516-8208 x1617', 'Minus dolor aliquam placeat animi.', NULL, NULL, NULL, NULL, NULL, 0),
('40', '40', 40, 'Tomasa', '1-347-792-7443 x2142', 91, 'hassie19@gmail.com', '40', 40, 'Melody Stravenue', 'Freddie Square', 'House', '4', '10', '3', '10', 40, 40, 'Vietnam', 'Poland', 'Windler Square', '4', 'aut', 'A4', '412', 'Rosefort', NULL, '637 Goodwin Divide\r\nIleneview, DC 54085', '1780 Roberts Prairie Apt. 788\r\nLake Aubreymouth, PA 95771', 'Dolorem numquam ipsam harum commodi.', 40, 'Jeramy', 'Megane', 'KWD', NULL, NULL, '35.000', '63.000', '90.000', '52.000', '10.000', 1, 0, 1, '1974-07-31 08:08:51', 11, '2007-03-21 21:20:03', '1973-11-19 17:48:23', 40, 'http://www.herman.info/est-quia-doloribus-voluptatem.html', 1, '52.29663800000000000000', '128.80466500000000000000', 0, '1972-04-13 17:56:50', '2004-09-22 12:23:41', 40, NULL, NULL, NULL, 'http://bergstrom.info/', '03963bbaae6c4f90feb56b3c7776c60e', NULL, 40, NULL, '40', 'http://www.bogan.com/magnam-ut-dicta-modi-commodi-omnis-quia-fugiat', 'Thalia Nader', '921.722.2311', 0, 1, 1, 40, '14.000', 'Bruce', '(304) 213-1341 x9182', 'Consectetur vitae quia optio laudantium excepturi sequi officiis.', NULL, NULL, NULL, NULL, NULL, 0),
('41', '41', 41, 'Kyra', '1-976-542-1608 x7211', 91, 'zita49@rolfson.biz', '41', 41, 'Upton Brooks', 'Nader Trail', 'House', '10', '2', '8', '5', 41, 41, 'Swaziland', 'Greenland', 'Howell Circles', '4', 'optio', 'A4', '412', 'Port Laurenmouth', NULL, '7891 Adelbert Knoll\r\nVidatown, VA 25094', '80502 Lottie Mews\r\nFlatleyview, MA 87437-8283', 'Ratione quas laudantium distinctio natus consequatur saepe accusamus.', 41, 'Keely', 'Kaylah', 'KWD', NULL, NULL, '60.000', '25.000', '21.000', '49.000', '56.000', 6, 0, 1, '1971-10-29 17:52:19', 11, '1988-01-02 22:22:17', '1999-11-24 13:25:52', 41, 'https://boyle.com/eaque-sint-aut-aut-eveniet-nesciunt-perspiciatis-id-odio.html', 1, '-84.03412000000000000000', '77.79449700000000000000', 1, '2012-11-21 01:15:39', '1992-07-31 01:09:31', 41, NULL, NULL, NULL, 'http://www.bartoletti.info/', 'e2dcbcbae1d2347f54a0e1c037b52622', NULL, 41, NULL, '41', 'http://wunsch.biz/eligendi-sint-ab-atque-quos.html', 'Julia Stracke', '779-648-0041 x033', 0, 1, 1, 41, '11.000', 'Clementine', '+1 (965) 830-3445', 'Officia voluptatem illum vero.', NULL, NULL, NULL, NULL, NULL, 0),
('42', '42', 42, 'Madie', '1-640-966-7654 x6227', 91, 'hilma05@gmail.com', '42', 42, 'Berniece Roads', 'Dejah Road', 'House', '5', '5', '6', '9', 42, 42, 'Central African Republic', 'Tokelau', 'Hahn Estates', '4', 'expedita', 'A4', '412', 'Yeseniaton', NULL, '890 Harber Vista\r\nKaitlinbury, KS 61839', '19609 McKenzie Mountain\r\nPort Casper, PA 04303', 'A non dicta sed assumenda.', 42, 'Maude', 'Elisa', 'KWD', NULL, NULL, '54.000', '32.000', '35.000', '24.000', '55.000', 10, 0, 1, '1971-11-06 05:32:11', 11, '1990-05-05 00:00:09', '2003-01-06 20:24:20', 42, 'http://ruecker.com/quae-et-suscipit-sed-possimus-commodi-laudantium-ipsam-temporibus.html', 1, '-54.29077200000000000000', '22.41498500000000000000', 0, '1984-07-16 18:37:41', '1983-05-17 23:24:49', 42, NULL, NULL, NULL, 'http://www.block.com/autem-odit-iste-beatae-enim-dolorum-accusantium.html', 'd66d903a49bac5347c8c652ad30f2703', NULL, 42, NULL, '42', 'https://www.cormier.info/quis-facilis-id-eaque-laudantium-quisquam', 'Jovany Emard', '971-359-6445 x070', 0, 1, 1, 42, '12.000', 'Shanna', '465-612-5137 x4003', 'Autem quibusdam nemo et qui perspiciatis voluptatem.', NULL, NULL, NULL, NULL, NULL, 0),
('43', '43', 43, 'Roy', '(620) 724-0519', 91, 'trisha.abshire@gmail.com', '43', 43, 'Jacklyn Island', 'Rex Shore', 'House', '2', '3', '8', '8', 43, 43, 'Samoa', 'El Salvador', 'Stamm Rapid', '4', 'delectus', 'A4', '412', 'Lake D\'angelo', NULL, '8087 Virginie Crescent\r\nSchowalterborough, AK 81001', '57576 Anita Orchard\r\nSouth Revatown, NC 92181-1492', 'Et quis totam minima nisi ducimus.', 43, 'Ezekiel', 'Ariane', 'KWD', NULL, NULL, '90.000', '33.000', '14.000', '61.000', '69.000', 10, 0, 1, '2018-05-10 17:51:23', 11, '1980-01-04 10:03:00', '1990-09-11 22:35:18', 43, 'http://schinner.biz/est-sed-iusto-alias-atque-optio-dolorum-assumenda-ratione', 1, '4.40375700000000000000', '-162.50490700000000000000', 1, '1996-07-14 19:19:54', '2007-07-26 12:11:42', 43, NULL, NULL, NULL, 'http://www.kozey.org/nemo-id-porro-dignissimos-qui-quisquam-qui-expedita', 'e408d8e5f4d447250c353d1a9264db92', NULL, 43, NULL, '43', 'http://skiles.com/quia-vel-amet-doloremque-tenetur-ut-nam-quae.html', 'Declan Bruen', '1-280-240-9122', 0, 1, 1, 43, '10.000', 'Adrien', '779.430.9300 x973', 'Sunt ipsam minus id incidunt qui sed at.', NULL, NULL, NULL, NULL, NULL, 0),
('44', '44', 44, 'Candice', '(467) 660-3078 x39588', 91, 'ulises.ebert@gmail.com', '44', 44, 'Demarcus Brook', 'Larkin Avenue', 'House', '4', '2', '7', '1', 44, 44, 'United States of America', 'Algeria', 'Watsica Spur', '4', 'rerum', 'A4', '412', 'Breannaside', NULL, '216 Clair Terrace Apt. 607\r\nWest Murielbury, IN 25013-0778', '5169 Quinton Mountains\r\nNew Lennie, SC 45433-1342', 'Blanditiis nemo autem at et error dicta.', 44, 'Jorge', 'Gabrielle', 'KWD', NULL, NULL, '35.000', '47.000', '42.000', '62.000', '92.000', 1, 0, 1, '1988-03-25 22:09:54', 11, '2012-12-09 18:04:03', '1978-07-13 19:41:16', 44, 'https://www.rice.com/reiciendis-sapiente-totam-nesciunt-sunt-omnis-eligendi-accusamus', 1, '74.98833100000000000000', '80.46643900000000000000', 0, '2020-04-12 02:04:41', '1997-10-29 00:17:12', 44, NULL, NULL, NULL, 'http://robel.net/natus-et-quas-nulla-neque-saepe.html', 'fe7750f183d11f4c064c2d7da7c62885', NULL, 44, NULL, '44', 'http://windler.info/quisquam-qui-iste-eaque-magnam-ab-dolores-corrupti.html', 'Gregoria Oberbrunner', '+14513412490', 0, 1, 1, 44, '16.000', 'Paige', '424-925-4905', 'Animi ea sed est praesentium doloremque eligendi illo.', NULL, NULL, NULL, NULL, NULL, 0),
('45', '45', 45, 'Lamar', '1-735-432-5228', 91, 'tabitha80@yahoo.com', '45', 45, 'Upton Rest', 'Odell Street', 'House', '9', '4', '7', '7', 45, 45, 'Thailand', 'Reunion', 'Lula Loaf', '4', 'ipsum', 'A4', '412', 'Izabellabury', NULL, '136 Elna Keys\r\nKenview, WY 06248', '2246 Murazik Brooks Apt. 208\r\nRohanberg, MT 84455', 'Doloribus sit facilis assumenda aut consectetur est.', 45, 'Lola', 'Alan', 'KWD', NULL, NULL, '61.000', '16.000', '61.000', '45.000', '14.000', 10, 0, 1, '1993-06-11 23:05:24', 11, '2014-04-27 05:22:02', '2012-10-26 12:03:04', 45, 'https://doyle.com/rem-aliquam-esse-voluptatem-natus-voluptatem-laborum.html', 1, '62.53231100000000000000', '177.75464200000000000000', 0, '1989-05-22 14:45:17', '1995-08-01 05:14:46', 45, NULL, NULL, NULL, 'http://hauck.com/', '51a8f763045b2be5b9c030af8fdb2e30', NULL, 45, NULL, '45', 'http://www.purdy.com/at-hic-optio-maiores-eos-quia-non', 'Claire Pollich', '(507) 253-0605 x316', 0, 1, 1, 45, '17.000', 'Elissa', '(674) 935-0865', 'Ut quo perspiciatis placeat dolor doloribus.', NULL, NULL, NULL, NULL, NULL, 0),
('46', '46', 46, 'Clovis', '1-463-559-9665', 91, 'enola.buckridge@hirthe.com', '46', 46, 'O\'Hara Prairie', 'Providenci Crest', 'House', '3', '6', '6', '6', 46, 46, 'Slovenia', 'Libyan Arab Jamahiriya', 'Eloy Path', '4', 'quia', 'A4', '412', 'Karianneton', NULL, '9068 Mertz Loop Apt. 150\r\nWest Robertaport, OK 57734-1544', '56463 Bernhard Avenue\r\nPort Donato, DE 03657-6963', 'Consequatur quo commodi qui praesentium.', 46, 'Angelina', 'Tiffany', 'KWD', NULL, NULL, '67.000', '67.000', '19.000', '39.000', '82.000', 6, 0, 1, '1974-04-23 09:41:40', 11, '2009-06-04 21:21:28', '1990-04-29 22:55:21', 46, 'http://ziemann.com/ad-qui-officia-aut-aliquid-voluptatem-vel.html', 1, '59.38896600000000000000', '-36.84564900000000000000', 0, '1984-08-28 23:50:46', '1988-11-17 21:16:30', 46, NULL, NULL, NULL, 'http://www.rice.com/', '57f2dcd56183c5c6b54856fce181df9d', NULL, 46, NULL, '46', 'http://www.hauck.org/', 'Doyle Lynch', '+16724203095', 0, 1, 1, 46, '12.000', 'Felicity', '640-701-4855 x0829', 'Tempore id aspernatur ut neque.', NULL, NULL, NULL, NULL, NULL, 0),
('47', '47', 47, 'Elias', '(785) 568-9417', 91, 'brekke.laurie@runolfsdottir.com', '47', 47, 'Botsford Ford', 'Christa Haven', 'House', '6', '6', '8', '4', 47, 47, 'Iraq', 'Saint Helena', 'Ebert Plain', '4', 'magnam', 'A4', '412', 'North Darrionberg', NULL, '320 Cole Motorway\r\nNorth Ed, NJ 08305', '46455 Parker Fort\r\nAdelleview, PA 66535-3516', 'Tempora excepturi et molestiae voluptatem eum tenetur.', 47, 'Ezekiel', 'Cade', 'KWD', NULL, NULL, '73.000', '64.000', '65.000', '89.000', '49.000', 6, 0, 1, '1988-01-03 08:54:59', 11, '1992-05-04 14:57:08', '1978-08-07 20:34:29', 47, 'http://www.kassulke.biz/', 1, '69.72980300000000000000', '138.19115600000000000000', 1, '1985-05-25 14:00:01', '2014-09-29 05:41:18', 47, NULL, NULL, NULL, 'http://www.von.com/est-vel-qui-repellat-optio', 'a6fbb971466b4fb152f49ee3f8d97c63', NULL, 47, NULL, '47', 'http://www.jacobson.org/id-et-consectetur-suscipit-laborum-quae-quibusdam-magnam.html', 'Kayley Toy', '252-615-1644 x727', 0, 1, 1, 47, '14.000', 'Dannie', '(451) 820-3136', 'Sequi qui magni illum culpa sunt blanditiis.', NULL, NULL, NULL, NULL, NULL, 0);
INSERT INTO `order` (`order_uuid`, `payment_uuid`, `customer_id`, `customer_name`, `customer_phone_number`, `customer_phone_country_code`, `customer_email`, `restaurant_uuid`, `area_id`, `area_name`, `area_name_ar`, `unit_type`, `block`, `street`, `avenue`, `house_number`, `delivery_zone_id`, `shipping_country_id`, `country_name`, `country_name_ar`, `business_location_name`, `floor`, `apartment`, `building`, `office`, `city`, `postalcode`, `address_1`, `address_2`, `special_directions`, `payment_method_id`, `payment_method_name`, `payment_method_name_ar`, `currency_code`, `store_currency_code`, `currency_rate`, `total_price`, `subtotal`, `subtotal_before_refund`, `total_price_before_refund`, `delivery_fee`, `order_status`, `is_deleted`, `order_mode`, `estimated_time_of_arrival`, `delivery_time`, `order_created_at`, `order_updated_at`, `restaurant_branch_id`, `armada_tracking_link`, `items_has_been_restocked`, `latitude`, `longitude`, `is_order_scheduled`, `scheduled_time_start_from`, `scheduled_time_to`, `voucher_id`, `voucher_code`, `discount_type`, `voucher_discount`, `armada_qr_code_link`, `armada_delivery_code`, `armada_order_status`, `bank_discount_id`, `bank_discount`, `mashkor_order_number`, `mashkor_tracking_link`, `mashkor_driver_name`, `mashkor_driver_phone`, `mashkor_order_status`, `reminder_sent`, `sms_sent`, `pickup_location_id`, `tax`, `recipient_name`, `recipient_phone_number`, `gift_message`, `order_instruction`, `sender_name`, `diggipack_awb_no`, `diggipack_order_status`, `utm_uuid`, `is_sandbox`) VALUES
('48', '48', 48, 'Parker', '(307) 347-6193 x598', 91, 'tiffany42@gmail.com', '48', 48, 'Wisoky Lake', 'Georgiana Glens', 'House', '3', '10', '10', '3', 48, 48, 'Saint Pierre and Miquelon', 'Sudan', 'Katarina Wells', '4', 'quo', 'A4', '412', 'West Jalentown', NULL, '78182 Towne Skyway Suite 145\r\nSouth Kelsimouth, SD 39026', '6815 Myah Rue Apt. 737\r\nMortimermouth, WY 82495', 'Omnis ullam officiis quia nostrum omnis sit.', 48, 'Jamel', 'Mertie', 'KWD', NULL, NULL, '95.000', '25.000', '26.000', '34.000', '38.000', 6, 0, 1, '1995-11-06 04:34:42', 11, '1986-04-10 11:24:12', '2016-06-03 06:11:13', 48, 'http://www.damore.com/molestias-nostrum-id-ut-aliquid', 1, '24.29631000000000000000', '163.59639000000000000000', 1, '2020-01-13 19:44:04', '1974-10-02 16:31:20', 48, NULL, NULL, NULL, 'http://www.bruen.com/quaerat-provident-hic-error-officiis', '059cff7a725fb98324b8db7c49f49bf9', NULL, 48, NULL, '48', 'http://schmitt.com/', 'Loraine Hayes', '914-420-5415 x074', 0, 1, 1, 48, '10.000', 'Gloria', '823-879-6492', 'Dignissimos quia sequi vel ipsa et dolores consequatur sapiente.', NULL, NULL, NULL, NULL, NULL, 0),
('49', '49', 49, 'Jerod', '+13799124646', 91, 'tremblay.dax@gmail.com', '49', 49, 'Grady Harbors', 'Phyllis Manor', 'House', '9', '2', '2', '6', 49, 49, 'Belarus', 'Aruba', 'Beatty Square', '4', 'veritatis', 'A4', '412', 'South Alexanne', NULL, '8896 Luis Cape Apt. 639\r\nPort Queenie, CO 37313-8882', '119 Cummerata Green Apt. 390\r\nLake Fayville, TX 43421', 'Et esse neque deserunt.', 49, 'Robb', 'Leslie', 'KWD', NULL, NULL, '24.000', '15.000', '10.000', '58.000', '68.000', 10, 0, 1, '2013-11-04 20:32:29', 11, '1977-12-28 00:45:50', '1994-01-17 14:17:32', 49, 'http://www.wiza.com/et-impedit-earum-voluptas-nemo-atque-dolorum-iusto', 1, '46.96055600000000000000', '17.46667600000000000000', 0, '2001-08-10 00:19:28', '2003-01-19 07:41:18', 49, NULL, NULL, NULL, 'http://runolfsson.org/et-quod-aut-nostrum.html', 'e21a30558ae2c8bfc3efc20451ac889e', NULL, 49, NULL, '49', 'http://www.leffler.com/', 'Marion Kuvalis', '851-866-7503', 0, 1, 1, 49, '18.000', 'Myrtie', '(538) 247-9717 x739', 'Ut et et sit voluptas provident maiores et.', NULL, NULL, NULL, NULL, NULL, 0),
('5', '5', 5, 'Mohamed', '287.581.0759 x45733', 91, 'mohr.zachariah@gmail.com', '5', 5, 'Jarret Haven', 'Cruickshank Course', 'House', '8', '4', '9', '10', 5, 5, 'Montenegro', 'Anguilla', 'Robel Lane', '4', 'debitis', 'A4', '412', 'Doylestad', NULL, '9861 Wunsch Shores Apt. 410\r\nSonnychester, MD 17903-5428', '976 Mariah Summit\r\nBrandonstad, OH 18174-1142', 'Voluptates dolore hic enim quam eos occaecati.', 5, 'Foster', 'Cortez', 'KWD', NULL, NULL, '81.000', '32.000', '84.000', '67.000', '26.000', 10, 0, 1, '2002-08-22 12:15:15', 11, '1978-09-08 06:05:55', '1989-11-03 14:34:51', 5, 'http://gislason.com/cum-quae-est-a-sit-laborum-tenetur.html', 1, '-34.08750800000000000000', '37.84689400000000000000', 0, '1981-02-07 19:20:24', '1984-05-01 11:56:00', 5, NULL, NULL, NULL, 'https://www.padberg.org/voluptas-itaque-eligendi-consequatur-aut-in-laboriosam-aspernatur', '6b6e0addb1204903e896fa2a56038935', NULL, 5, NULL, '5', 'https://www.vonrueden.com/nisi-quas-mollitia-rerum-pariatur-voluptatem-quidem', 'Larissa Rodriguez', '330-816-8631 x0700', 0, 1, 1, 5, '13.000', 'Rowan', '1-245-299-6934 x937', 'Nam ducimus aut et vel.', NULL, NULL, NULL, NULL, NULL, 0),
('50', '50', 50, 'Kevin', '1-915-321-1739', 91, 'jgerlach@reynolds.biz', '50', 50, 'Jacobs View', 'Irving Extensions', 'House', '9', '3', '10', '3', 50, 50, 'Mauritius', 'Equatorial Guinea', 'Lehner Prairie', '4', 'necessitatibus', 'A4', '412', 'Lindgrenland', NULL, '473 Reilly Oval\r\nLake Katelyn, AK 81695-8439', '89451 Treutel Centers Suite 042\r\nPort Murphyburgh, DC 66300-5617', 'Quam veritatis exercitationem magnam architecto.', 50, 'Arden', 'Aileen', 'KWD', NULL, NULL, '83.000', '24.000', '90.000', '79.000', '46.000', 10, 0, 1, '2009-06-10 19:21:27', 11, '1992-09-17 17:39:52', '1995-12-22 01:42:23', 50, 'https://fisher.org/aut-inventore-voluptatem-sapiente-quae.html', 1, '-25.59949300000000000000', '-13.30321500000000000000', 1, '1976-05-12 19:26:52', '1978-09-10 00:22:41', 50, NULL, NULL, NULL, 'http://reynolds.com/nemo-accusantium-magnam-non-earum-corrupti-voluptas-iusto', '2a571028de363d970cbac47d9a6efb0f', NULL, 50, NULL, '50', 'http://www.schoen.com/eos-quia-inventore-repudiandae-quia.html', 'Paolo Wilkinson', '(961) 308-6677 x5153', 0, 1, 1, 50, '19.000', 'Lia', '984.937.9099 x8551', 'Tenetur odit nostrum ut aliquam nobis.', NULL, NULL, NULL, NULL, NULL, 0),
('6', '6', 6, 'Jefferey', '819.331.5612 x8909', 91, 'mcdermott.lesly@kutch.info', '6', 6, 'O\'Reilly Mountain', 'Bartoletti Rue', 'House', '2', '3', '7', '2', 6, 6, 'Cyprus', 'Togo', 'Nolan Mount', '4', 'nihil', 'A4', '412', 'Williamsonfort', NULL, '281 Leann Plains Suite 084\r\nSmithtown, WI 53647', '86295 Hermiston Spurs\r\nDouglasport, SD 46147', 'Similique quo quidem et laborum.', 6, 'Easter', 'Lia', 'KWD', NULL, NULL, '27.000', '53.000', '65.000', '65.000', '12.000', 7, 0, 1, '1973-05-15 10:43:29', 11, '2001-09-23 10:25:53', '1981-07-22 21:21:34', 6, 'http://www.cronin.com/id-ipsam-quidem-soluta-nobis-at-ut-sint', 1, '-80.99743700000000000000', '-70.02560800000000000000', 1, '1983-03-27 09:06:30', '1972-10-16 08:13:32', 6, NULL, NULL, NULL, 'https://www.collins.com/debitis-voluptatum-omnis-ea-aperiam-est-pariatur', 'd128f2a5ccce34309df58455abd77635', NULL, 6, NULL, '6', 'http://www.strosin.org/', 'Bernadette Stokes', '+1-524-746-4094', 0, 1, 1, 6, '17.000', 'Ismael', '(252) 357-1831 x741', 'Labore labore aspernatur non nihil sed id.', NULL, NULL, NULL, NULL, NULL, 0),
('7', '7', 7, 'Shanon', '+1.414.990.1842', 91, 'wiegand.ophelia@bartell.com', '7', 7, 'Mosciski Expressway', 'Hyatt Freeway', 'House', '3', '6', '6', '10', 7, 7, 'Nicaragua', 'Cayman Islands', 'Klocko Expressway', '4', 'delectus', 'A4', '412', 'West Maribel', NULL, '18311 Emard Trafficway Suite 281\r\nNew Kassandra, IL 90788', '99939 Ratke Lock Suite 690\r\nLake Reid, TN 64825', 'Doloremque suscipit atque quos ut tempora alias voluptas facere.', 7, 'Lauren', 'Roger', 'KWD', NULL, NULL, '63.000', '92.000', '82.000', '45.000', '23.000', 10, 0, 1, '1972-12-17 06:27:38', 11, '2011-09-29 04:14:13', '1984-02-07 07:39:04', 7, 'https://www.borer.com/doloremque-possimus-quas-et-velit-quia', 1, '-77.49295900000000000000', '-133.79253200000000000000', 0, '2020-12-26 13:47:49', '2009-07-14 22:40:19', 7, NULL, NULL, NULL, 'https://fritsch.org/sed-pariatur-sunt-architecto-vel-voluptas-est-adipisci.html', 'bb56a0d80146574a7e263240d79c0653', NULL, 7, NULL, '7', 'http://www.grimes.info/sequi-excepturi-deleniti-qui-dolorem-rerum-impedit.html', 'Marianne Kemmer', '791-651-4436', 0, 1, 1, 7, '19.000', 'Nathanial', '+1-838-649-8844', 'Inventore nam repellendus sint ab.', NULL, NULL, NULL, NULL, NULL, 0),
('8', '8', 8, 'Payton', '707.642.2224 x20443', 91, 'fiona.watsica@russel.com', '8', 8, 'Torphy Fords', 'Junior Stravenue', 'House', '7', '3', '7', '5', 8, 8, 'Macao', 'Bhutan', 'Kertzmann Crest', '4', 'veniam', 'A4', '412', 'Waelchiton', NULL, '4120 Hermiston Gardens Apt. 838\r\nEast Astrid, OR 86454', '9179 Chelsey Garden Suite 899\r\nEast Janessaberg, NM 87665-8605', 'Est et dolores numquam quia et voluptas explicabo porro.', 8, 'Aurore', 'Raphael', 'KWD', NULL, NULL, '95.000', '100.000', '45.000', '13.000', '93.000', 6, 0, 1, '1998-04-26 12:40:09', 11, '2014-11-26 13:38:20', '1977-04-25 20:35:06', 8, 'http://predovic.net/quia-aut-iure-consequuntur-officia-quo', 1, '3.10182400000000000000', '-125.44276200000000000000', 1, '1991-10-22 03:13:35', '1978-08-08 22:30:10', 8, NULL, NULL, NULL, 'http://wyman.com/qui-a-maxime-ipsam-rerum-voluptatem-temporibus', 'd02aa3e3856ecb696c0954d63f0d6e81', NULL, 8, NULL, '8', 'http://johns.info/quam-commodi-eveniet-temporibus-dolorum-sint-non-sint-et', 'Jaylin Block', '+1-515-859-3383', 0, 1, 1, 8, '10.000', 'Presley', '453.822.7460 x66239', 'Ratione excepturi voluptatem dolorum.', NULL, NULL, NULL, NULL, NULL, 0),
('9', '9', 9, 'Jaclyn', '680.244.9732', 91, 'jason18@goyette.info', '9', 9, 'Dahlia Island', 'Dicki Row', 'House', '1', '5', '8', '5', 9, 9, 'Guadeloupe', 'Madagascar', 'Susie Course', '4', 'et', 'A4', '412', 'Lake Rozellaborough', NULL, '33978 Dawn Junctions Apt. 394\r\nMantebury, TX 72049-3740', '86036 Spinka Extension Apt. 559\r\nAlveraburgh, OH 83406', 'Esse et qui inventore.', 9, 'Ewell', 'Santa', 'KWD', NULL, NULL, '98.000', '44.000', '97.000', '12.000', '84.000', 4, 0, 1, '1978-06-21 23:25:16', 11, '1975-11-23 08:26:53', '1996-04-15 03:04:20', 9, 'http://www.halvorson.com/', 1, '-9.16164200000000000000', '-176.46110000000000000000', 0, '1976-12-19 07:50:33', '2010-01-23 18:38:16', 9, NULL, NULL, NULL, 'http://lowe.biz/sunt-asperiores-ex-et.html', 'aa6cbf8f837a2b2393fd19b2bbbc8d2b', NULL, 9, NULL, '9', 'https://volkman.net/culpa-pariatur-est-dignissimos-animi.html', 'Brittany Deckow', '+1-814-764-9961', 0, 1, 1, 9, '16.000', 'Tremayne', '+1.860.600.3404', 'Vitae enim eos sint ut dicta et ipsa est.', NULL, NULL, NULL, NULL, NULL, 0);

-- --------------------------------------------------------

--
-- Table structure for table `order_item`
--

CREATE TABLE `order_item` (
  `order_item_id` bigint(20) NOT NULL,
  `order_uuid` char(40) COLLATE utf8_unicode_ci NOT NULL,
  `restaurant_uuid` char(60) COLLATE utf8_unicode_ci DEFAULT NULL,
  `item_uuid` varchar(300) COLLATE utf8_unicode_ci DEFAULT NULL,
  `item_variant_uuid` char(60) COLLATE utf8_unicode_ci DEFAULT NULL,
  `item_name` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `item_price` decimal(10,3) UNSIGNED NOT NULL,
  `qty` int(11) DEFAULT NULL,
  `customer_instruction` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `order_item_created_at` datetime DEFAULT NULL,
  `order_item_updated_at` datetime DEFAULT NULL,
  `item_name_ar` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `item_unit_price` decimal(10,3) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `order_item`
--

INSERT INTO `order_item` (`order_item_id`, `order_uuid`, `restaurant_uuid`, `item_uuid`, `item_variant_uuid`, `item_name`, `item_price`, `qty`, `customer_instruction`, `order_item_created_at`, `order_item_updated_at`, `item_name_ar`, `item_unit_price`) VALUES
(1, '1', '1', '1', NULL, 'placeat', '41.000', 9, 'Consectetur ut placeat ullam dolore.', NULL, NULL, 'nam', NULL),
(2, '2', NULL, '2', NULL, 'quae', '11.000', 2, 'Magnam tenetur quis iste quo quasi.', NULL, NULL, 'necessitatibus', NULL),
(3, '3', NULL, '3', NULL, 'maxime', '30.000', 9, 'Minus alias est consectetur aut et repellat aut.', NULL, NULL, 'eos', NULL),
(4, '4', NULL, '4', NULL, 'saepe', '87.000', 4, 'Est et culpa velit sed minus recusandae.', NULL, NULL, 'vel', NULL),
(5, '5', NULL, '5', NULL, 'nihil', '88.000', 4, 'Delectus vel ad aut aut repudiandae.', NULL, NULL, 'laborum', NULL),
(6, '6', NULL, '6', NULL, 'rem', '65.000', 8, 'Veniam culpa id eum accusamus consequatur.', NULL, NULL, 'itaque', NULL),
(7, '7', NULL, '7', NULL, 'incidunt', '51.000', 7, 'Impedit debitis fugiat a eveniet rerum est in.', NULL, NULL, 'eaque', NULL),
(8, '8', NULL, '8', NULL, 'a', '40.000', 8, 'Qui et dolor voluptatibus dolore.', NULL, NULL, 'nostrum', NULL),
(9, '9', NULL, '9', NULL, 'vel', '14.000', 5, 'Vero harum debitis magnam esse quia.', NULL, NULL, 'voluptas', NULL),
(10, '10', NULL, '10', NULL, 'voluptatibus', '73.000', 3, 'Libero error sit est quo itaque odio hic.', NULL, NULL, 'corporis', NULL),
(11, '11', NULL, '11', NULL, 'quo', '72.000', 1, 'Facere hic officiis est enim rerum et.', NULL, NULL, 'neque', NULL),
(12, '12', NULL, '12', NULL, 'sit', '63.000', 6, 'Enim esse consequatur cum velit et blanditiis quisquam.', NULL, NULL, 'omnis', NULL),
(13, '13', NULL, '13', NULL, 'quia', '22.000', 9, 'Ex dolores laboriosam quod doloribus est laborum.', NULL, NULL, 'consectetur', NULL),
(14, '14', NULL, '14', NULL, 'sint', '25.000', 1, 'Aut dolor quaerat a illum et.', NULL, NULL, 'enim', NULL),
(15, '15', NULL, '15', NULL, 'accusantium', '12.000', 7, 'Voluptate rerum quod vel ipsa magnam.', NULL, NULL, 'sequi', NULL),
(16, '16', NULL, '16', NULL, 'quibusdam', '52.000', 10, 'Assumenda voluptatem porro voluptatem mollitia praesentium.', NULL, NULL, 'non', NULL),
(17, '17', NULL, '17', NULL, 'nisi', '20.000', 8, 'Ut et excepturi quae eos ex necessitatibus velit fuga.', NULL, NULL, 'ut', NULL),
(18, '18', NULL, '18', NULL, 'odit', '37.000', 4, 'Optio maxime dolorem repellat eum cumque excepturi.', NULL, NULL, 'minima', NULL),
(19, '19', NULL, '19', NULL, 'a', '73.000', 1, 'Cupiditate in vel labore nihil.', NULL, NULL, 'nihil', NULL),
(20, '20', NULL, '20', NULL, 'modi', '14.000', 8, 'Atque sunt beatae minus exercitationem ratione aliquid cum.', NULL, NULL, 'repellat', NULL),
(21, '21', NULL, '21', NULL, 'sequi', '97.000', 4, 'Voluptas dolore illo rerum quis sed nihil et maxime.', NULL, NULL, 'omnis', NULL),
(22, '22', NULL, '22', NULL, 'laudantium', '79.000', 1, 'Nobis dolore reiciendis rerum temporibus iusto voluptate a qui.', NULL, NULL, 'consequatur', NULL),
(23, '23', NULL, '23', NULL, 'et', '93.000', 9, 'Quia et quam quo eos pariatur occaecati.', NULL, NULL, 'ut', NULL),
(24, '24', NULL, '24', NULL, 'quia', '57.000', 10, 'Qui qui sunt eveniet est.', NULL, NULL, 'et', NULL),
(25, '25', NULL, '25', NULL, 'dolor', '57.000', 5, 'Aut architecto similique est mollitia minus.', NULL, NULL, 'alias', NULL),
(26, '26', NULL, '26', NULL, 'illum', '62.000', 8, 'Quia eum est alias aut hic optio.', NULL, NULL, 'provident', NULL),
(27, '27', NULL, '27', NULL, 'cumque', '63.000', 3, 'At explicabo esse cumque ut delectus.', NULL, NULL, 'et', NULL),
(28, '28', NULL, '28', NULL, 'cupiditate', '13.000', 3, 'Cum saepe optio hic cum aspernatur qui fugiat.', NULL, NULL, 'quo', NULL),
(29, '29', NULL, '29', NULL, 'est', '78.000', 5, 'Veniam deserunt molestiae impedit pariatur delectus.', NULL, NULL, 'mollitia', NULL),
(30, '30', NULL, '30', NULL, 'vero', '94.000', 10, 'Dolor numquam quaerat sed.', NULL, NULL, 'dolorem', NULL),
(31, '31', NULL, '31', NULL, 'ea', '82.000', 9, 'Velit porro ut ipsa laudantium dolor ea suscipit.', NULL, NULL, 'dolor', NULL),
(32, '32', NULL, '32', NULL, 'placeat', '39.000', 1, 'Cumque id eos quas maiores tempora voluptatibus.', NULL, NULL, 'alias', NULL),
(33, '33', NULL, '33', NULL, 'et', '62.000', 1, 'Repudiandae mollitia eaque tenetur eum.', NULL, NULL, 'minima', NULL),
(34, '34', NULL, '34', NULL, 'quo', '69.000', 6, 'Ipsum nisi quis ratione voluptatem rem vero.', NULL, NULL, 'doloribus', NULL),
(35, '35', NULL, '35', NULL, 'asperiores', '82.000', 5, 'Reprehenderit quisquam sapiente quidem.', NULL, NULL, 'quas', NULL),
(36, '36', NULL, '36', NULL, 'explicabo', '22.000', 1, 'Laboriosam quo ut molestiae harum consequatur.', NULL, NULL, 'est', NULL),
(37, '37', NULL, '37', NULL, 'ut', '58.000', 6, 'Aperiam cupiditate ex alias quaerat qui aut necessitatibus.', NULL, NULL, 'quis', NULL),
(38, '38', NULL, '38', NULL, 'ut', '40.000', 2, 'Est vitae delectus corrupti sit reprehenderit sunt quas.', NULL, NULL, 'quis', NULL),
(39, '39', NULL, '39', NULL, 'dolores', '47.000', 8, 'Quod qui itaque repudiandae.', NULL, NULL, 'vel', NULL),
(40, '40', NULL, '40', NULL, 'iste', '91.000', 9, 'Qui ipsa odio nihil quos beatae vel delectus veritatis.', NULL, NULL, 'libero', NULL),
(41, '41', NULL, '41', NULL, 'est', '55.000', 8, 'Dolorem repellendus sit sit consequatur eos vitae doloremque.', NULL, NULL, 'ipsam', NULL),
(42, '42', NULL, '42', NULL, 'perferendis', '86.000', 7, 'Nesciunt consequatur assumenda quasi non.', NULL, NULL, 'incidunt', NULL),
(43, '43', NULL, '43', NULL, 'exercitationem', '60.000', 4, 'Quia mollitia corrupti autem consequatur nostrum voluptate et.', NULL, NULL, 'possimus', NULL),
(44, '44', NULL, '44', NULL, 'autem', '85.000', 5, 'Animi temporibus fuga dolores reprehenderit sunt ad quia.', NULL, NULL, 'molestiae', NULL),
(45, '45', NULL, '45', NULL, 'non', '28.000', 2, 'Commodi consectetur aspernatur voluptatum.', NULL, NULL, 'eum', NULL),
(46, '46', NULL, '46', NULL, 'quibusdam', '74.000', 6, 'Eius ut aliquid autem quia eum.', NULL, NULL, 'et', NULL),
(47, '47', NULL, '47', NULL, 'aliquam', '97.000', 3, 'Et dolor enim et non hic qui architecto.', NULL, NULL, 'qui', NULL),
(48, '48', NULL, '48', NULL, 'officia', '31.000', 10, 'Eum nam eum neque aut reprehenderit commodi.', NULL, NULL, 'voluptatum', NULL),
(49, '49', NULL, '49', NULL, 'debitis', '31.000', 4, 'Corporis voluptatem suscipit eaque consequatur iure quos.', NULL, NULL, 'quibusdam', NULL),
(50, '50', NULL, '50', NULL, 'aut', '90.000', 9, 'Labore eveniet dolor maiores labore praesentium esse.', NULL, NULL, 'et', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `order_item_extra_option`
--

CREATE TABLE `order_item_extra_option` (
  `order_item_extra_option_id` bigint(20) NOT NULL,
  `order_item_id` bigint(20) NOT NULL,
  `option_id` int(11) DEFAULT NULL,
  `extra_option_id` int(11) DEFAULT NULL,
  `option_name` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `option_name_ar` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `extra_option_name` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `extra_option_name_ar` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `extra_option_price` decimal(10,3) UNSIGNED NOT NULL,
  `qty` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `order_item_extra_option`
--

INSERT INTO `order_item_extra_option` (`order_item_extra_option_id`, `order_item_id`, `option_id`, `extra_option_id`, `option_name`, `option_name_ar`, `extra_option_name`, `extra_option_name_ar`, `extra_option_price`, `qty`) VALUES
(1, 1, NULL, 1, NULL, NULL, 'nam', 'velit', '84.000', 46),
(2, 2, NULL, 2, NULL, NULL, 'quia', 'nihil', '22.000', 86),
(3, 3, NULL, 3, NULL, NULL, 'reiciendis', 'quam', '51.000', 77),
(4, 4, NULL, 4, NULL, NULL, 'eos', 'rem', '22.000', 22),
(5, 5, NULL, 5, NULL, NULL, 'quidem', 'dicta', '72.000', 60),
(6, 6, NULL, 6, NULL, NULL, 'vel', 'sit', '89.000', 41),
(7, 7, NULL, 7, NULL, NULL, 'quisquam', 'iste', '74.000', 89),
(8, 8, NULL, 8, NULL, NULL, 'veritatis', 'earum', '33.000', 69),
(9, 9, NULL, 9, NULL, NULL, 'ipsam', 'voluptatem', '17.000', 85),
(10, 10, NULL, 10, NULL, NULL, 'dolores', 'maxime', '100.000', 34),
(11, 11, NULL, 11, NULL, NULL, 'blanditiis', 'quibusdam', '99.000', 75),
(12, 12, NULL, 12, NULL, NULL, 'error', 'dolores', '22.000', 63),
(13, 13, NULL, 13, NULL, NULL, 'inventore', 'quasi', '61.000', 28),
(14, 14, NULL, 14, NULL, NULL, 'aut', 'minima', '30.000', 68),
(15, 15, NULL, 15, NULL, NULL, 'officia', 'recusandae', '59.000', 62),
(16, 16, NULL, 16, NULL, NULL, 'omnis', 'molestiae', '54.000', 87),
(17, 17, NULL, 17, NULL, NULL, 'non', 'iure', '99.000', 26),
(18, 18, NULL, 18, NULL, NULL, 'qui', 'sit', '63.000', 35),
(19, 19, NULL, 19, NULL, NULL, 'dolores', 'ut', '89.000', 68),
(20, 20, NULL, 20, NULL, NULL, 'voluptatem', 'minus', '87.000', 72),
(21, 21, NULL, 21, NULL, NULL, 'ullam', 'adipisci', '48.000', 15),
(22, 22, NULL, 22, NULL, NULL, 'facere', 'dolor', '26.000', 48),
(23, 23, NULL, 23, NULL, NULL, 'sint', 'non', '42.000', 77),
(24, 24, NULL, 24, NULL, NULL, 'veritatis', 'odit', '94.000', 23),
(25, 25, NULL, 25, NULL, NULL, 'numquam', 'neque', '51.000', 94),
(26, 26, NULL, 26, NULL, NULL, 'sit', 'enim', '77.000', 14),
(27, 27, NULL, 27, NULL, NULL, 'dolor', 'animi', '98.000', 80),
(28, 28, NULL, 28, NULL, NULL, 'impedit', 'eos', '19.000', 48),
(29, 29, NULL, 29, NULL, NULL, 'sit', 'ducimus', '33.000', 19),
(30, 30, NULL, 30, NULL, NULL, 'fugit', 'cum', '14.000', 18),
(31, 31, NULL, 31, NULL, NULL, 'labore', 'soluta', '51.000', 40),
(32, 32, NULL, 32, NULL, NULL, 'illum', 'voluptas', '96.000', 55),
(33, 33, NULL, 33, NULL, NULL, 'quisquam', 'officia', '10.000', 43),
(34, 34, NULL, 34, NULL, NULL, 'porro', 'voluptatem', '26.000', 55),
(35, 35, NULL, 35, NULL, NULL, 'earum', 'nesciunt', '98.000', 97),
(36, 36, NULL, 36, NULL, NULL, 'ea', 'debitis', '20.000', 51),
(37, 37, NULL, 37, NULL, NULL, 'eum', 'voluptates', '64.000', 26),
(38, 38, NULL, 38, NULL, NULL, 'earum', 'iste', '89.000', 76),
(39, 39, NULL, 39, NULL, NULL, 'laudantium', 'enim', '88.000', 68),
(40, 40, NULL, 40, NULL, NULL, 'officia', 'pariatur', '92.000', 82),
(41, 41, NULL, 41, NULL, NULL, 'odit', 'quaerat', '19.000', 95),
(42, 42, NULL, 42, NULL, NULL, 'distinctio', 'dicta', '80.000', 68),
(43, 43, NULL, 43, NULL, NULL, 'praesentium', 'commodi', '69.000', 46),
(44, 44, NULL, 44, NULL, NULL, 'laudantium', 'voluptas', '10.000', 93),
(45, 45, NULL, 45, NULL, NULL, 'eveniet', 'aliquid', '98.000', 65),
(46, 46, NULL, 46, NULL, NULL, 'ipsam', 'id', '49.000', 84),
(47, 47, NULL, 47, NULL, NULL, 'dolore', 'eaque', '21.000', 95),
(48, 48, NULL, 48, NULL, NULL, 'veniam', 'est', '95.000', 48),
(49, 49, NULL, 49, NULL, NULL, 'quo', 'quas', '22.000', 78),
(50, 50, NULL, 50, NULL, NULL, 'illum', 'omnis', '86.000', 26);

-- --------------------------------------------------------

--
-- Table structure for table `partner`
--

CREATE TABLE `partner` (
  `partner_uuid` char(60) COLLATE utf8_unicode_ci NOT NULL,
  `username` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `partner_auth_key` varchar(32) COLLATE utf8_unicode_ci NOT NULL,
  `partner_password_hash` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `partner_password_reset_token` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `partner_email` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `partner_status` smallint(6) NOT NULL DEFAULT 10,
  `referral_code` varchar(6) COLLATE utf8_unicode_ci NOT NULL,
  `partner_iban` varchar(100) COLLATE utf8_unicode_ci DEFAULT NULL,
  `commission` decimal(10,3) UNSIGNED DEFAULT 0.200,
  `partner_created_at` datetime DEFAULT NULL,
  `partner_updated_at` datetime DEFAULT NULL,
  `benef_name` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `bank_id` int(11) DEFAULT NULL,
  `partner_phone_number` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `partner_phone_number_country_code` int(3) DEFAULT 965
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `partner`
--

INSERT INTO `partner` (`partner_uuid`, `username`, `partner_auth_key`, `partner_password_hash`, `partner_password_reset_token`, `partner_email`, `partner_status`, `referral_code`, `partner_iban`, `commission`, `partner_created_at`, `partner_updated_at`, `benef_name`, `bank_id`, `partner_phone_number`, `partner_phone_number_country_code`) VALUES
('1', 'user_1', 'UrHcCwToK4R8BZw-Jd_ew1eTJasoarxD', '$2y$13$dO6zyx2rPSMDXkiKsCxKK.XrYMG7OHUQQmVDsBP84MXy8b0PyUAjW', NULL, 'adolfo19@yahoo.com', 10, '123112', 'consectetur', '10.000', '2007-08-26 08:35:11', '2000-08-10 02:38:17', 'Eula Ritchie', 1, '(325) 364-9952', 91),
('10', 'user_10', 'UT6bDkUC0Yb8bHClhPZQkr43eq5olxpD', '$2y$13$2Z2WeqvrclJN/f8BK2ExCu7XpmyVtS0QierBYcTzI/xJX80biaMEG', NULL, 'czulauf@cole.com', 10, '123112', 'autem', '10.000', '1989-10-30 12:19:42', '1987-08-27 17:41:15', 'Sienna Skiles II', 10, '+1.509.737.8873', 91),
('2', 'user_2', 'p3PIyA-PMbm80MV04C2eBOsjx7I95Fhm', '$2y$13$dxbhf.aVYwTTrnM/UbaErOZKTU14sz0MwCtcmNY14eqpUR.lgPtOS', NULL, 'walker.luisa@gmail.com', 10, '123112', 'delectus', '10.000', '1970-07-20 16:24:45', '1988-07-06 17:59:11', 'Kelli Luettgen', 2, '+18135641024', 91),
('3', 'user_3', 'c8gXhumzn2vor__VD1iIanVjWGSio-GP', '$2y$13$jU25gf.9PnipRinbye5C6eKgJVZyVOVMcXX4OgrorRLAFO2DT/alS', NULL, 'hosinski@russel.com', 10, '123112', 'qui', '10.000', '1995-04-12 22:14:11', '1991-09-30 21:54:23', 'Wilson Maggio II', 3, '341-973-8798', 91),
('4', 'user_4', 'fhaspRPqJHfPCe1SzVPBYvNP_R-kaU4O', '$2y$13$2d5oFDC5zU3wd59IfgKNjOl9acmivqDvJcACKBVEeaqv81q6VwDsC', NULL, 'yherzog@gmail.com', 10, '123112', 'ipsum', '10.000', '2010-12-24 05:54:57', '1974-02-02 16:34:56', 'Gustave Luettgen', 4, '862.396.1530', 91),
('5', 'user_5', 'S-Jao_7EIA0qLL1YOjIXlaDvwJLe88c5', '$2y$13$9jeeMp0EfI1IlkqxXPjF9OwM4fYliBd8AXvTsiWitDYAi4Fr.Urh2', NULL, 'jefferey13@mccullough.com', 10, '123112', 'temporibus', '10.000', '1998-05-09 08:06:54', '1997-05-02 19:21:40', 'Ms. Kristy Senger', 5, '1-720-270-6585', 91),
('6', 'user_6', 'OBPLx5bCiUrX-Jjbdlk5Ql-kW8kHCG3P', '$2y$13$ER3qv8XKLrrYllqTbRDUbORyLhOx77ZMeLtoZdWfhMK7gnQgNas7K', NULL, 'quentin.reichert@lemke.com', 10, '123112', 'distinctio', '10.000', '1972-07-06 23:18:55', '1977-04-21 06:35:41', 'Newton Larson', 6, '626-868-2763', 91),
('7', 'user_7', 'umBg54M2X9NwCsd_BMG3ijbyh2N9O28F', '$2y$13$5u7TcdOMqDci7QWuR9kVf.NNER.GzEfYFDPJ.3UXOKn27ZYwEN/Ga', NULL, 'esanford@graham.org', 10, '123112', 'tenetur', '10.000', '1997-03-05 19:46:37', '1989-03-23 04:14:19', 'Prof. Carolyne Leannon', 7, '+1-339-859-0753', 91),
('8', 'user_8', 'H-CpLOipi70kjC1M-GI8RHsT84W0d2Nv', '$2y$13$HiOtrmJUw/pYFspZ.pebJesnvAfDOWuY.N1XHofqAvDkrtb8edFW.', NULL, 'egaylord@rohan.com', 10, '123112', 'molestiae', '10.000', '1989-11-26 06:30:44', '1976-07-22 22:42:53', 'Prof. Litzy Keeling II', 8, '(682) 857-2137', 91),
('9', 'user_9', 'n0rCeiTVAnUPky18wjQwC0J-EdIeg4zP', '$2y$13$IvecTH315SPB2om2aWX95Oz1Gu1ziF0wru3u.lfR9T1iEKFqLhHge', NULL, 'glenda19@bahringer.com', 10, '123112', 'est', '10.000', '1991-07-18 18:54:11', '1970-10-06 02:10:40', 'Zachary Gerlach', 9, '1-561-294-2125', 91);

-- --------------------------------------------------------

--
-- Table structure for table `partner_payout`
--

CREATE TABLE `partner_payout` (
  `partner_payout_uuid` char(35) COLLATE utf8_unicode_ci NOT NULL,
  `partner_uuid` char(60) COLLATE utf8_unicode_ci NOT NULL,
  `amount` decimal(10,3) UNSIGNED DEFAULT 0.000,
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL,
  `payout_status` smallint(6) DEFAULT 0,
  `transfer_benef_iban` varchar(100) COLLATE utf8_unicode_ci DEFAULT NULL,
  `transfer_benef_name` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `bank_id` int(11) DEFAULT NULL,
  `transfer_file` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `partner_payout`
--

INSERT INTO `partner_payout` (`partner_payout_uuid`, `partner_uuid`, `amount`, `created_at`, `updated_at`, `payout_status`, `transfer_benef_iban`, `transfer_benef_name`, `bank_id`, `transfer_file`) VALUES
('1', '1', '164.000', '1975-02-06 03:05:05', '2002-11-30 21:30:31', 1, 'perferendis', 'Rey Ankunding', 1, NULL),
('10', '10', '142.000', '1983-02-01 12:07:03', '2020-01-07 11:03:28', 1, 'sunt', 'Lavina Olson', 10, NULL),
('2', '2', '192.000', '1986-10-05 04:39:10', '2015-04-29 11:50:31', 1, 'non', 'Aurelia Zieme', 2, NULL),
('3', '3', '192.000', '1977-02-26 04:47:16', '2006-03-05 03:35:00', 1, 'est', 'Coralie Homenick', 3, NULL),
('4', '4', '185.000', '2006-06-01 10:29:42', '1973-10-29 04:00:18', 1, 'totam', 'Stephania Conroy', 4, NULL),
('5', '5', '152.000', '1972-01-07 07:46:38', '2010-08-10 17:41:54', 1, 'maxime', 'Paula Murphy', 5, NULL),
('6', '6', '114.000', '2006-01-11 09:16:18', '2021-11-08 07:28:51', 1, 'aut', 'Micah Rau', 6, NULL),
('7', '7', '131.000', '2011-11-23 08:23:30', '2011-08-05 11:00:33', 1, 'sunt', 'Prof. Brenden Robel IV', 7, NULL),
('8', '8', '150.000', '1970-05-07 22:30:57', '1982-01-10 07:42:01', 1, 'ex', 'Candace Wuckert', 8, NULL),
('9', '9', '169.000', '2004-03-18 21:23:01', '2005-07-15 06:24:06', 1, 'facilis', 'Evelyn Marvin Jr.', 9, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `partner_token`
--

CREATE TABLE `partner_token` (
  `token_uuid` char(60) COLLATE utf8_unicode_ci NOT NULL,
  `partner_uuid` char(60) COLLATE utf8_unicode_ci NOT NULL,
  `token_value` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `token_device` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `token_device_id` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `token_status` smallint(6) DEFAULT NULL,
  `token_last_used_datetime` datetime DEFAULT NULL,
  `token_expiry_datetime` datetime DEFAULT NULL,
  `token_created_datetime` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `partner_token`
--

INSERT INTO `partner_token` (`token_uuid`, `partner_uuid`, `token_value`, `token_device`, `token_device_id`, `token_status`, `token_last_used_datetime`, `token_expiry_datetime`, `token_created_datetime`) VALUES
('1', '1', 'WwertOXyQ5DhU6VjUadGWIgEVBWv1nKx', NULL, NULL, 10, '1998-03-08 03:43:10', '1978-04-23 23:01:24', '2017-09-15 08:08:04'),
('10', '10', '0HuJfZyVWH96ADNhZ-s8P4d9ITWfri72', NULL, NULL, 10, '2009-04-02 23:14:12', '1983-02-23 16:45:33', '1981-11-01 13:53:25'),
('2', '2', 'OIpZpTa-AffHNCmWCE0NeIFN4x7h4mRJ', NULL, NULL, 10, '1973-04-11 18:39:09', '1991-07-22 12:05:55', '1981-02-03 06:49:02'),
('3', '3', 'KObjkvzaLBz_XQFUK2LOoX7-9lll6YmP', NULL, NULL, 10, '1997-08-08 17:49:01', '2005-07-01 05:02:28', '2010-12-05 12:20:37'),
('4', '4', 'HzPo9Q63_enIrsRw4a7KTYavvCQCakii', NULL, NULL, 10, '2002-04-15 02:50:05', '1999-08-29 21:15:46', '1979-12-29 14:49:08'),
('5', '5', 'Upuw7MO3ClVpT6xO4XaEYJqlt7zmJy21', NULL, NULL, 10, '2011-07-31 05:16:31', '1996-05-11 07:26:48', '1979-03-23 06:00:50'),
('6', '6', '9MsMat5LeQKv1Cw3JsfyxPRRaBs1eR7b', NULL, NULL, 10, '1999-04-02 00:51:38', '2016-10-21 06:20:03', '2020-05-05 00:20:45'),
('7', '7', '760HI_lq2gImjEWLrVf5dR9z0goMI-OL', NULL, NULL, 10, '2018-09-10 10:35:44', '2008-03-11 14:45:32', '2014-11-07 07:04:53'),
('8', '8', 'Ft2-AKMEC7chPzVfEgcdmbpG4zKZYDA1', NULL, NULL, 10, '1982-01-23 04:05:44', '1977-07-14 03:22:42', '1985-09-13 00:34:33'),
('9', '9', 'I_Eu_BboL85d4HstJlhjtdB37-EbSKdv', NULL, NULL, 10, '1975-02-23 16:07:08', '2017-07-11 05:27:07', '2001-10-13 16:34:13');

-- --------------------------------------------------------

--
-- Table structure for table `payment`
--

CREATE TABLE `payment` (
  `payment_uuid` char(36) COLLATE utf8_unicode_ci NOT NULL,
  `restaurant_uuid` char(60) COLLATE utf8_unicode_ci DEFAULT NULL,
  `customer_id` bigint(20) NOT NULL,
  `order_uuid` char(40) COLLATE utf8_unicode_ci NOT NULL,
  `payment_gateway_order_id` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `payment_gateway_transaction_id` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `payment_gateway_payment_id` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `payment_gateway_invoice_id` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `payment_mode` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `payment_current_status` text COLLATE utf8_unicode_ci DEFAULT NULL,
  `payment_amount_charged` double NOT NULL,
  `payment_net_amount` double DEFAULT NULL,
  `payment_gateway_fee` double DEFAULT NULL,
  `payment_vat` decimal(10,3) UNSIGNED NOT NULL DEFAULT 0.000,
  `plugn_fee` double DEFAULT NULL,
  `payment_udf1` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `payment_udf2` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `payment_udf3` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `payment_udf4` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `payment_udf5` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `payment_created_at` datetime DEFAULT NULL,
  `payment_updated_at` datetime DEFAULT NULL,
  `received_callback` tinyint(1) NOT NULL DEFAULT 0,
  `response_message` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `payment_token` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `payment_gateway_name` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `partner_fee` decimal(10,3) UNSIGNED DEFAULT 0.000,
  `payout_status` smallint(6) DEFAULT 0,
  `partner_payout_uuid` char(35) COLLATE utf8_unicode_ci DEFAULT NULL,
  `is_sandbox` tinyint(1) DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `payment`
--

INSERT INTO `payment` (`payment_uuid`, `restaurant_uuid`, `customer_id`, `order_uuid`, `payment_gateway_order_id`, `payment_gateway_transaction_id`, `payment_gateway_payment_id`, `payment_gateway_invoice_id`, `payment_mode`, `payment_current_status`, `payment_amount_charged`, `payment_net_amount`, `payment_gateway_fee`, `payment_vat`, `plugn_fee`, `payment_udf1`, `payment_udf2`, `payment_udf3`, `payment_udf4`, `payment_udf5`, `payment_created_at`, `payment_updated_at`, `received_callback`, `response_message`, `payment_token`, `payment_gateway_name`, `partner_fee`, `payout_status`, `partner_payout_uuid`, `is_sandbox`) VALUES
('1', '1', 1, '1', '1', '1', NULL, NULL, 'KNET', 'captured', 175, 140, 2, '0.000', 2, NULL, NULL, NULL, NULL, NULL, '1976-11-11 19:04:23', '1992-12-02 01:50:39', 1, 'In quasi sunt omnis nihil rerum qui et.', 'c4ca4238a0b923820dcc509a6f75849b', NULL, '0.000', 0, NULL, 0),
('10', '10', 10, '10', '10', '10', NULL, NULL, 'KNET', 'captured', 100, 179, 2, '0.000', 2, NULL, NULL, NULL, NULL, NULL, '2018-02-14 13:02:19', '1976-01-02 15:14:26', 1, 'Nulla tempore dolores dolores totam officia.', 'd3d9446802a44259755d38e6d163e820', NULL, '0.000', 0, NULL, 0),
('11', '11', 11, '11', '11', '11', NULL, NULL, 'KNET', 'captured', 184, 142, 2, '0.000', 2, NULL, NULL, NULL, NULL, NULL, '1994-08-14 01:10:09', '2011-09-18 04:15:30', 1, 'Ut voluptatem velit eligendi quae aut beatae praesentium.', '6512bd43d9caa6e02c990b0a82652dca', NULL, '0.000', 0, NULL, 0),
('12', '12', 12, '12', '12', '12', NULL, NULL, 'KNET', 'captured', 150, 173, 2, '0.000', 2, NULL, NULL, NULL, NULL, NULL, '1972-07-31 20:19:54', '1982-07-16 13:06:53', 1, 'Provident eum rerum quisquam quo omnis.', 'c20ad4d76fe97759aa27a0c99bff6710', NULL, '0.000', 0, NULL, 0),
('13', '13', 13, '13', '13', '13', NULL, NULL, 'KNET', 'captured', 134, 158, 2, '0.000', 2, NULL, NULL, NULL, NULL, NULL, '2002-01-29 22:26:07', '2010-12-25 23:45:19', 1, 'Qui et et aspernatur saepe.', 'c51ce410c124a10e0db5e4b97fc2af39', NULL, '0.000', 0, NULL, 0),
('14', '14', 14, '14', '14', '14', NULL, NULL, 'KNET', 'captured', 113, 156, 2, '0.000', 2, NULL, NULL, NULL, NULL, NULL, '2010-10-09 15:50:04', '2016-12-05 06:25:42', 1, 'Neque harum dolor ipsum dolorem provident tempore.', 'aab3238922bcc25a6f606eb525ffdc56', NULL, '0.000', 0, NULL, 0),
('15', '15', 15, '15', '15', '15', NULL, NULL, 'KNET', 'captured', 157, 143, 2, '0.000', 2, NULL, NULL, NULL, NULL, NULL, '2004-03-09 20:57:25', '1999-05-18 16:43:39', 1, 'Dolor sit sapiente praesentium est odio repellendus corrupti.', '9bf31c7ff062936a96d3c8bd1f8f2ff3', NULL, '0.000', 0, NULL, 0),
('16', '16', 16, '16', '16', '16', NULL, NULL, 'KNET', 'captured', 116, 113, 2, '0.000', 2, NULL, NULL, NULL, NULL, NULL, '1977-12-09 00:14:04', '1981-10-26 18:10:07', 1, 'Placeat enim sed temporibus quas ut in qui.', 'c74d97b01eae257e44aa9d5bade97baf', NULL, '0.000', 0, NULL, 0),
('17', '17', 17, '17', '17', '17', NULL, NULL, 'KNET', 'captured', 122, 128, 2, '0.000', 2, NULL, NULL, NULL, NULL, NULL, '1981-04-30 01:51:48', '2015-08-04 07:34:14', 1, 'Porro sed rerum vitae nulla iusto totam.', '70efdf2ec9b086079795c442636b55fb', NULL, '0.000', 0, NULL, 0),
('18', '18', 18, '18', '18', '18', NULL, NULL, 'KNET', 'captured', 108, 142, 2, '0.000', 2, NULL, NULL, NULL, NULL, NULL, '1973-03-06 06:01:42', '1985-11-06 07:33:33', 1, 'Ipsa perspiciatis non officiis nihil.', '6f4922f45568161a8cdf4ad2299f6d23', NULL, '0.000', 0, NULL, 0),
('19', '19', 19, '19', '19', '19', NULL, NULL, 'KNET', 'captured', 162, 116, 2, '0.000', 2, NULL, NULL, NULL, NULL, NULL, '2006-06-18 23:30:54', '1975-03-24 16:40:14', 1, 'Quis cum assumenda enim aut ipsum deleniti et.', '1f0e3dad99908345f7439f8ffabdffc4', NULL, '0.000', 0, NULL, 0),
('2', '2', 2, '2', '2', '2', NULL, NULL, 'KNET', 'captured', 137, 200, 2, '0.000', 2, NULL, NULL, NULL, NULL, NULL, '2012-07-27 00:18:50', '1991-09-17 00:41:34', 1, 'Commodi ea omnis autem.', 'c81e728d9d4c2f636f067f89cc14862c', NULL, '0.000', 0, NULL, 0),
('20', '20', 20, '20', '20', '20', NULL, NULL, 'KNET', 'captured', 184, 162, 2, '0.000', 2, NULL, NULL, NULL, NULL, NULL, '2008-06-22 09:13:45', '1993-12-09 00:04:24', 1, 'Ea pariatur dolore recusandae voluptatibus dolores aut.', '98f13708210194c475687be6106a3b84', NULL, '0.000', 0, NULL, 0),
('21', '21', 21, '21', '21', '21', NULL, NULL, 'KNET', 'captured', 112, 164, 2, '0.000', 2, NULL, NULL, NULL, NULL, NULL, '1997-03-29 20:36:53', '1980-01-07 19:08:50', 1, 'Maxime dolore sed non ut repellendus debitis dolorum.', '3c59dc048e8850243be8079a5c74d079', NULL, '0.000', 0, NULL, 0),
('22', '22', 22, '22', '22', '22', NULL, NULL, 'KNET', 'captured', 198, 140, 2, '0.000', 2, NULL, NULL, NULL, NULL, NULL, '2021-02-18 21:36:44', '1983-01-20 00:40:42', 1, 'Est nulla et consequuntur aspernatur rerum qui qui.', 'b6d767d2f8ed5d21a44b0e5886680cb9', NULL, '0.000', 0, NULL, 0),
('23', '23', 23, '23', '23', '23', NULL, NULL, 'KNET', 'captured', 133, 138, 2, '0.000', 2, NULL, NULL, NULL, NULL, NULL, '1989-11-08 23:26:47', '2007-01-29 10:51:46', 1, 'Quibusdam nam minima modi culpa.', '37693cfc748049e45d87b8c7d8b9aacd', NULL, '0.000', 0, NULL, 0),
('24', '24', 24, '24', '24', '24', NULL, NULL, 'KNET', 'captured', 163, 145, 2, '0.000', 2, NULL, NULL, NULL, NULL, NULL, '1997-03-24 11:48:18', '1988-08-04 01:46:29', 1, 'Quibusdam in sint sit tenetur.', '1ff1de774005f8da13f42943881c655f', NULL, '0.000', 0, NULL, 0),
('25', '25', 25, '25', '25', '25', NULL, NULL, 'KNET', 'captured', 186, 104, 2, '0.000', 2, NULL, NULL, NULL, NULL, NULL, '1980-11-06 03:24:34', '1988-06-22 11:43:34', 1, 'Aut beatae sunt saepe a ut dolorem ipsa.', '8e296a067a37563370ded05f5a3bf3ec', NULL, '0.000', 0, NULL, 0),
('26', '26', 26, '26', '26', '26', NULL, NULL, 'KNET', 'captured', 200, 101, 2, '0.000', 2, NULL, NULL, NULL, NULL, NULL, '2010-04-18 06:23:19', '1974-03-03 04:33:39', 1, 'Et labore qui aut porro et voluptatem.', '4e732ced3463d06de0ca9a15b6153677', NULL, '0.000', 0, NULL, 0),
('27', '27', 27, '27', '27', '27', NULL, NULL, 'KNET', 'captured', 121, 114, 2, '0.000', 2, NULL, NULL, NULL, NULL, NULL, '2021-04-22 01:29:59', '2008-08-14 16:25:57', 1, 'Qui voluptas provident accusantium omnis.', '02e74f10e0327ad868d138f2b4fdd6f0', NULL, '0.000', 0, NULL, 0),
('28', '28', 28, '28', '28', '28', NULL, NULL, 'KNET', 'captured', 124, 155, 2, '0.000', 2, NULL, NULL, NULL, NULL, NULL, '2011-09-15 05:12:32', '1971-03-21 07:42:35', 1, 'Aliquid sit non quam vitae corporis voluptatem.', '33e75ff09dd601bbe69f351039152189', NULL, '0.000', 0, NULL, 0),
('29', '29', 29, '29', '29', '29', NULL, NULL, 'KNET', 'captured', 181, 123, 2, '0.000', 2, NULL, NULL, NULL, NULL, NULL, '1993-08-30 05:16:58', '2006-09-29 10:07:27', 1, 'Est placeat architecto voluptas nulla incidunt atque et.', '6ea9ab1baa0efb9e19094440c317e21b', NULL, '0.000', 0, NULL, 0),
('3', '3', 3, '3', '3', '3', NULL, NULL, 'KNET', 'captured', 136, 146, 2, '0.000', 2, NULL, NULL, NULL, NULL, NULL, '1996-10-11 17:59:57', '2009-06-15 16:38:04', 1, 'Eligendi ea nemo vel tenetur quidem quidem quae.', 'eccbc87e4b5ce2fe28308fd9f2a7baf3', NULL, '0.000', 0, NULL, 0),
('30', '30', 30, '30', '30', '30', NULL, NULL, 'KNET', 'captured', 155, 116, 2, '0.000', 2, NULL, NULL, NULL, NULL, NULL, '1986-03-24 02:43:22', '2018-11-28 01:59:51', 1, 'Ut facere blanditiis placeat atque iusto.', '34173cb38f07f89ddbebc2ac9128303f', NULL, '0.000', 0, NULL, 0),
('31', '31', 31, '31', '31', '31', NULL, NULL, 'KNET', 'captured', 158, 119, 2, '0.000', 2, NULL, NULL, NULL, NULL, NULL, '2006-05-15 21:41:50', '1991-01-01 17:44:40', 1, 'Distinctio tempora sunt fugiat dolorem.', 'c16a5320fa475530d9583c34fd356ef5', NULL, '0.000', 0, NULL, 0),
('32', '32', 32, '32', '32', '32', NULL, NULL, 'KNET', 'captured', 163, 198, 2, '0.000', 2, NULL, NULL, NULL, NULL, NULL, '1977-09-04 06:17:53', '1996-03-22 19:58:26', 1, 'Voluptatem molestias ipsam magnam ut amet aut cupiditate.', '6364d3f0f495b6ab9dcf8d3b5c6e0b01', NULL, '0.000', 0, NULL, 0),
('33', '33', 33, '33', '33', '33', NULL, NULL, 'KNET', 'captured', 104, 125, 2, '0.000', 2, NULL, NULL, NULL, NULL, NULL, '1984-12-26 22:32:16', '1984-06-22 19:52:22', 1, 'In voluptas impedit cum earum et nihil.', '182be0c5cdcd5072bb1864cdee4d3d6e', NULL, '0.000', 0, NULL, 0),
('34', '34', 34, '34', '34', '34', NULL, NULL, 'KNET', 'captured', 111, 183, 2, '0.000', 2, NULL, NULL, NULL, NULL, NULL, '2013-11-07 01:14:25', '2009-01-16 23:56:06', 1, 'Eius eaque quasi sit voluptas.', 'e369853df766fa44e1ed0ff613f563bd', NULL, '0.000', 0, NULL, 0),
('35', '35', 35, '35', '35', '35', NULL, NULL, 'KNET', 'captured', 131, 188, 2, '0.000', 2, NULL, NULL, NULL, NULL, NULL, '1988-10-06 19:13:43', '1970-08-29 09:21:02', 1, 'Id ullam quasi dolorem quia ut et perferendis.', '1c383cd30b7c298ab50293adfecb7b18', NULL, '0.000', 0, NULL, 0),
('36', '36', 36, '36', '36', '36', NULL, NULL, 'KNET', 'captured', 132, 104, 2, '0.000', 2, NULL, NULL, NULL, NULL, NULL, '1981-04-04 22:11:04', '1978-10-12 13:23:55', 1, 'Optio cum et explicabo quas fugiat.', '19ca14e7ea6328a42e0eb13d585e4c22', NULL, '0.000', 0, NULL, 0),
('37', '37', 37, '37', '37', '37', NULL, NULL, 'KNET', 'captured', 116, 163, 2, '0.000', 2, NULL, NULL, NULL, NULL, NULL, '1981-08-07 09:24:35', '1995-04-27 06:56:17', 1, 'Doloremque eos quam sit suscipit.', 'a5bfc9e07964f8dddeb95fc584cd965d', NULL, '0.000', 0, NULL, 0),
('38', '38', 38, '38', '38', '38', NULL, NULL, 'KNET', 'captured', 120, 101, 2, '0.000', 2, NULL, NULL, NULL, NULL, NULL, '1990-08-10 09:51:07', '2003-04-01 01:00:34', 1, 'Doloribus et earum temporibus rerum.', 'a5771bce93e200c36f7cd9dfd0e5deaa', NULL, '0.000', 0, NULL, 0),
('39', '39', 39, '39', '39', '39', NULL, NULL, 'KNET', 'captured', 126, 188, 2, '0.000', 2, NULL, NULL, NULL, NULL, NULL, '1997-06-10 10:04:24', '1981-06-17 11:47:38', 1, 'Hic cum consectetur neque aperiam autem enim non.', 'd67d8ab4f4c10bf22aa353e27879133c', NULL, '0.000', 0, NULL, 0),
('4', '4', 4, '4', '4', '4', NULL, NULL, 'KNET', 'captured', 176, 182, 2, '0.000', 2, NULL, NULL, NULL, NULL, NULL, '2017-04-10 22:53:44', '2007-08-21 17:50:12', 1, 'Repudiandae voluptas quas neque consequuntur.', 'a87ff679a2f3e71d9181a67b7542122c', NULL, '0.000', 0, NULL, 0),
('40', '40', 40, '40', '40', '40', NULL, NULL, 'KNET', 'captured', 189, 166, 2, '0.000', 2, NULL, NULL, NULL, NULL, NULL, '1984-06-30 03:53:24', '1986-04-18 21:38:24', 1, 'At nisi est voluptate aut.', 'd645920e395fedad7bbbed0eca3fe2e0', NULL, '0.000', 0, NULL, 0),
('41', '41', 41, '41', '41', '41', NULL, NULL, 'KNET', 'captured', 193, 170, 2, '0.000', 2, NULL, NULL, NULL, NULL, NULL, '1984-08-26 12:12:48', '2006-09-25 03:27:22', 1, 'Cum mollitia et aliquid enim.', '3416a75f4cea9109507cacd8e2f2aefc', NULL, '0.000', 0, NULL, 0),
('42', '42', 42, '42', '42', '42', NULL, NULL, 'KNET', 'captured', 173, 168, 2, '0.000', 2, NULL, NULL, NULL, NULL, NULL, '2000-03-27 06:38:18', '1999-11-26 02:14:29', 1, 'Omnis beatae est ullam beatae eaque et dolorum placeat.', 'a1d0c6e83f027327d8461063f4ac58a6', NULL, '0.000', 0, NULL, 0),
('43', '43', 43, '43', '43', '43', NULL, NULL, 'KNET', 'captured', 112, 172, 2, '0.000', 2, NULL, NULL, NULL, NULL, NULL, '1979-08-11 19:16:34', '1998-01-31 14:56:17', 1, 'Dolorem quo reprehenderit ipsam ipsum laudantium ducimus culpa.', '17e62166fc8586dfa4d1bc0e1742c08b', NULL, '0.000', 0, NULL, 0),
('44', '44', 44, '44', '44', '44', NULL, NULL, 'KNET', 'captured', 161, 122, 2, '0.000', 2, NULL, NULL, NULL, NULL, NULL, '1997-03-30 15:03:50', '1973-07-04 09:21:09', 1, 'Voluptates distinctio veniam eveniet at molestias molestiae optio.', 'f7177163c833dff4b38fc8d2872f1ec6', NULL, '0.000', 0, NULL, 0),
('45', '45', 45, '45', '45', '45', NULL, NULL, 'KNET', 'captured', 148, 145, 2, '0.000', 2, NULL, NULL, NULL, NULL, NULL, '1986-07-18 02:05:44', '2009-05-10 14:07:37', 1, 'Fugit et laborum eius similique voluptatem.', '6c8349cc7260ae62e3b1396831a8398f', NULL, '0.000', 0, NULL, 0),
('46', '46', 46, '46', '46', '46', NULL, NULL, 'KNET', 'captured', 160, 190, 2, '0.000', 2, NULL, NULL, NULL, NULL, NULL, '2003-10-26 10:12:44', '1988-05-18 06:34:45', 1, 'Inventore non accusamus veniam consequatur.', 'd9d4f495e875a2e075a1a4a6e1b9770f', NULL, '0.000', 0, NULL, 0),
('47', '47', 47, '47', '47', '47', NULL, NULL, 'KNET', 'captured', 180, 145, 2, '0.000', 2, NULL, NULL, NULL, NULL, NULL, '1977-11-15 19:15:55', '2001-12-30 17:23:13', 1, 'Et vel illum ex consequatur.', '67c6a1e7ce56d3d6fa748ab6d9af3fd7', NULL, '0.000', 0, NULL, 0),
('48', '48', 48, '48', '48', '48', NULL, NULL, 'KNET', 'captured', 166, 118, 2, '0.000', 2, NULL, NULL, NULL, NULL, NULL, '2010-10-24 11:52:08', '1977-02-15 06:34:43', 1, 'Doloribus atque quo et numquam rerum.', '642e92efb79421734881b53e1e1b18b6', NULL, '0.000', 0, NULL, 0),
('49', '49', 49, '49', '49', '49', NULL, NULL, 'KNET', 'captured', 157, 173, 2, '0.000', 2, NULL, NULL, NULL, NULL, NULL, '1984-10-07 08:01:25', '2007-04-27 13:57:18', 1, 'Cum esse quaerat dicta.', 'f457c545a9ded88f18ecee47145a72c0', NULL, '0.000', 0, NULL, 0),
('5', '5', 5, '5', '5', '5', NULL, NULL, 'KNET', 'captured', 188, 134, 2, '0.000', 2, NULL, NULL, NULL, NULL, NULL, '1974-02-15 15:44:48', '1993-01-14 05:42:16', 1, 'Corrupti saepe vitae soluta aut.', 'e4da3b7fbbce2345d7772b0674a318d5', NULL, '0.000', 0, NULL, 0),
('50', '50', 50, '50', '50', '50', NULL, NULL, 'KNET', 'captured', 133, 198, 2, '0.000', 2, NULL, NULL, NULL, NULL, NULL, '1986-02-24 20:51:04', '1971-03-05 02:15:57', 1, 'Molestiae in adipisci iste porro.', 'c0c7c76d30bd3dcaefc96f40275bdc0a', NULL, '0.000', 0, NULL, 0),
('6', '6', 6, '6', '6', '6', NULL, NULL, 'KNET', 'captured', 180, 191, 2, '0.000', 2, NULL, NULL, NULL, NULL, NULL, '1977-03-29 06:37:22', '2006-12-15 03:01:59', 1, 'Quasi architecto voluptatum nesciunt omnis.', '1679091c5a880faf6fb5e6087eb1b2dc', NULL, '0.000', 0, NULL, 0),
('7', '7', 7, '7', '7', '7', NULL, NULL, 'KNET', 'captured', 120, 134, 2, '0.000', 2, NULL, NULL, NULL, NULL, NULL, '1972-06-19 18:24:35', '1983-03-24 15:12:38', 1, 'Non vel possimus fuga qui optio.', '8f14e45fceea167a5a36dedd4bea2543', NULL, '0.000', 0, NULL, 0),
('8', '8', 8, '8', '8', '8', NULL, NULL, 'KNET', 'captured', 157, 188, 2, '0.000', 2, NULL, NULL, NULL, NULL, NULL, '2015-05-02 20:40:14', '1987-09-06 00:39:10', 1, 'Ab laudantium et rerum expedita id.', 'c9f0f895fb98ab9159f51fd0297e236d', NULL, '0.000', 0, NULL, 0),
('9', '9', 9, '9', '9', '9', NULL, NULL, 'KNET', 'captured', 144, 146, 2, '0.000', 2, NULL, NULL, NULL, NULL, NULL, '2010-02-04 18:49:57', '1991-12-22 03:04:34', 1, 'Qui est dolorem fugit.', '45c48cce2e2d7fbdea1afc51c7c6ad26', NULL, '0.000', 0, NULL, 0);

-- --------------------------------------------------------

--
-- Table structure for table `payment_failed`
--

CREATE TABLE `payment_failed` (
  `payment_failed_uuid` char(60) COLLATE utf8_unicode_ci NOT NULL,
  `payment_uuid` char(60) COLLATE utf8_unicode_ci NOT NULL,
  `order_uuid` char(40) COLLATE utf8_unicode_ci DEFAULT NULL,
  `customer_id` bigint(20) DEFAULT NULL,
  `response` text COLLATE utf8_unicode_ci DEFAULT NULL,
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `payment_gateway_queue`
--

CREATE TABLE `payment_gateway_queue` (
  `payment_gateway_queue_id` int(11) NOT NULL,
  `restaurant_uuid` char(60) COLLATE utf8_unicode_ci NOT NULL,
  `payment_gateway` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `queue_status` smallint(6) DEFAULT 1,
  `queue_response` text COLLATE utf8_unicode_ci DEFAULT NULL,
  `queue_created_at` datetime DEFAULT NULL,
  `queue_updated_at` datetime DEFAULT NULL,
  `queue_start_at` datetime DEFAULT NULL,
  `queue_end_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `payment_gateway_queue`
--

INSERT INTO `payment_gateway_queue` (`payment_gateway_queue_id`, `restaurant_uuid`, `payment_gateway`, `queue_status`, `queue_response`, `queue_created_at`, `queue_updated_at`, `queue_start_at`, `queue_end_at`) VALUES
(1, '1', '1', 1, NULL, '1987-10-24 22:48:34', '2011-06-01 04:53:25', '2011-04-06 03:23:05', '2009-02-01 00:14:32'),
(2, '2', '2', 1, NULL, '1972-05-13 04:08:31', '2000-09-10 07:19:32', '2013-12-11 01:35:48', '1998-06-22 02:02:16'),
(3, '3', '3', 1, NULL, '1988-11-16 02:44:52', '1975-05-10 23:00:12', '1978-05-23 05:05:18', '2010-01-19 19:20:39'),
(4, '4', '4', 1, NULL, '2005-05-25 01:07:43', '1973-09-04 21:30:13', '2010-07-23 09:36:34', '2019-04-12 17:53:52'),
(5, '5', '5', 1, NULL, '1970-07-25 11:13:52', '2011-06-26 18:21:07', '1974-10-16 19:38:37', '1986-04-14 19:28:29'),
(6, '6', '6', 1, NULL, '1988-04-14 23:55:48', '1989-05-09 06:08:00', '1990-04-19 15:01:12', '2004-08-13 06:42:48'),
(7, '7', '7', 1, NULL, '1975-08-23 23:47:45', '1970-12-06 01:44:50', '2020-06-11 05:00:48', '2013-05-15 16:38:24'),
(8, '8', '8', 1, NULL, '1994-04-28 08:17:45', '1999-11-23 07:50:53', '1984-12-21 21:09:23', '1988-07-20 01:12:33'),
(9, '9', '9', 1, NULL, '1980-01-26 07:37:58', '2010-03-02 15:27:48', '1982-07-20 13:45:05', '2007-09-01 20:06:24'),
(10, '10', '10', 1, NULL, '1995-04-27 14:44:08', '2015-04-28 20:38:32', '1975-01-27 09:19:31', '1972-03-24 09:17:50');

-- --------------------------------------------------------

--
-- Table structure for table `payment_method`
--

CREATE TABLE `payment_method` (
  `payment_method_id` int(11) NOT NULL,
  `payment_method_name` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `payment_method_name_ar` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `vat` decimal(10,3) UNSIGNED NOT NULL DEFAULT 0.000,
  `source_id` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `payment_method_code` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `payment_method`
--

INSERT INTO `payment_method` (`payment_method_id`, `payment_method_name`, `payment_method_name_ar`, `vat`, `source_id`, `payment_method_code`) VALUES
(1, 'Pablo', 'Johnathon', '0.000', NULL, NULL),
(2, 'Emory', 'Donald', '0.000', NULL, NULL),
(3, 'Kiley', 'Glenda', '0.000', NULL, NULL),
(4, 'Ollie', 'Laverne', '0.000', NULL, NULL),
(5, 'Foster', 'Cortez', '0.000', NULL, NULL),
(6, 'Easter', 'Lia', '0.000', NULL, NULL),
(7, 'Lauren', 'Roger', '0.000', NULL, NULL),
(8, 'Aurore', 'Raphael', '0.000', NULL, NULL),
(9, 'Ewell', 'Santa', '0.000', NULL, NULL),
(10, 'Earline', 'Gene', '0.000', NULL, NULL),
(11, 'Maximus', 'Lorenz', '0.000', NULL, NULL),
(12, 'Darius', 'Alfreda', '0.000', NULL, NULL),
(13, 'Terrance', 'Payton', '0.000', NULL, NULL),
(14, 'Orlando', 'Hank', '0.000', NULL, NULL),
(15, 'Chet', 'Callie', '0.000', NULL, NULL),
(16, 'Aida', 'Mose', '0.000', NULL, NULL),
(17, 'Gonzalo', 'Sheridan', '0.000', NULL, NULL),
(18, 'Waylon', 'Chance', '0.000', NULL, NULL),
(19, 'Helene', 'Erling', '0.000', NULL, NULL),
(20, 'Orrin', 'Pierre', '0.000', NULL, NULL),
(21, 'Althea', 'Harry', '0.000', NULL, NULL),
(22, 'Shea', 'Nathan', '0.000', NULL, NULL),
(23, 'William', 'Jensen', '0.000', NULL, NULL),
(24, 'Ashleigh', 'Rachael', '0.000', NULL, NULL),
(25, 'Loyce', 'Precious', '0.000', NULL, NULL),
(26, 'Maryse', 'Jennifer', '0.000', NULL, NULL),
(27, 'Mekhi', 'Esteban', '0.000', NULL, NULL),
(28, 'Elise', 'Herminia', '0.000', NULL, NULL),
(29, 'Stacy', 'Rebeca', '0.000', NULL, NULL),
(30, 'Jermaine', 'Vita', '0.000', NULL, NULL),
(31, 'Evelyn', 'Rowan', '0.000', NULL, NULL),
(32, 'Ruben', 'Jesus', '0.000', NULL, NULL),
(33, 'Jerry', 'Elmore', '0.000', NULL, NULL),
(34, 'Jeffrey', 'Genoveva', '0.000', NULL, NULL),
(35, 'Cecile', 'Randal', '0.000', NULL, NULL),
(36, 'Aric', 'Felipa', '0.000', NULL, NULL),
(37, 'Westley', 'Ericka', '0.000', NULL, NULL),
(38, 'Zena', 'Clemmie', '0.000', NULL, NULL),
(39, 'Hilton', 'Leila', '0.000', NULL, NULL),
(40, 'Jeramy', 'Megane', '0.000', NULL, NULL),
(41, 'Keely', 'Kaylah', '0.000', NULL, NULL),
(42, 'Maude', 'Elisa', '0.000', NULL, NULL),
(43, 'Ezekiel', 'Ariane', '0.000', NULL, NULL),
(44, 'Jorge', 'Gabrielle', '0.000', NULL, NULL),
(45, 'Lola', 'Alan', '0.000', NULL, NULL),
(46, 'Angelina', 'Tiffany', '0.000', NULL, NULL),
(47, 'Ezekiel', 'Cade', '0.000', NULL, NULL),
(48, 'Jamel', 'Mertie', '0.000', NULL, NULL),
(49, 'Robb', 'Leslie', '0.000', NULL, NULL),
(50, 'Arden', 'Aileen', '0.000', NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `payment_method_currency`
--

CREATE TABLE `payment_method_currency` (
  `pmc_id` int(11) NOT NULL,
  `payment_method_id` int(11) NOT NULL,
  `currency` char(3) COLLATE utf8_unicode_ci NOT NULL,
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `plan`
--

CREATE TABLE `plan` (
  `plan_id` int(11) NOT NULL,
  `name` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `price` float UNSIGNED DEFAULT NULL,
  `valid_for` int(11) UNSIGNED DEFAULT NULL,
  `platform_fee` float UNSIGNED DEFAULT NULL,
  `description` varchar(255) COLLATE utf8_unicode_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `plan`
--

INSERT INTO `plan` (`plan_id`, `name`, `price`, `valid_for`, `platform_fee`, `description`) VALUES
(1, 'excepturi', 100, 0, 7, 'Porro sint et enim nisi debitis.'),
(2, 'molestiae', 13, 27, 34, 'Illo autem aut quis suscipit soluta.'),
(3, 'perferendis', 52, 62, 50, 'Ex veniam fugit veritatis ducimus qui qui.'),
(4, 'voluptatum', 39, 47, 44, 'Beatae cupiditate non incidunt.'),
(5, 'dolor', 10, 44, 8, 'Expedita animi aut numquam voluptas hic non sapiente qui.'),
(6, 'et', 56, 38, 47, 'Ut dolorum distinctio totam assumenda.'),
(7, 'ea', 55, 33, 27, 'Perspiciatis ipsum suscipit consequatur illo.'),
(8, 'et', 90, 94, 28, 'Vitae voluptate sed ad nam nam.'),
(9, 'dolores', 39, 62, 39, 'Nulla quo labore deserunt.'),
(10, 'explicabo', 69, 89, 43, 'Eos doloremque voluptas pariatur distinctio.'),
(11, 'sint', 17, 49, 21, 'Quas quis architecto deserunt quaerat dolores.'),
(12, 'cumque', 64, 81, 30, 'Odit ipsam exercitationem ut quia.'),
(13, 'magnam', 97, 40, 24, 'Molestiae dolores itaque perferendis iure qui non corrupti.'),
(14, 'nisi', 13, 15, 24, 'Molestiae aut sed quibusdam.'),
(15, 'blanditiis', 87, 89, 36, 'Inventore ad natus excepturi quas.'),
(16, 'unde', 46, 53, 14, 'Qui porro porro aut et dignissimos repudiandae.'),
(17, 'excepturi', 13, 37, 38, 'Quos omnis debitis accusantium perspiciatis.'),
(18, 'iusto', 52, 41, 33, 'Perspiciatis rem totam blanditiis sunt qui.'),
(19, 'quas', 51, 95, 13, 'Consequatur voluptates et similique inventore illo sit.'),
(20, 'quibusdam', 76, 62, 46, 'Omnis voluptatibus quidem quae iste cumque ipsa non.'),
(21, 'laborum', 32, 86, 13, 'Nemo eaque deserunt sunt voluptate.'),
(22, 'animi', 42, 29, 49, 'Non ut et ipsa molestiae et dolorem.'),
(23, 'accusamus', 39, 18, 6, 'Aspernatur et animi quisquam debitis.'),
(24, 'magnam', 67, 95, 40, 'Sint non dolores numquam aut mollitia.'),
(25, 'quis', 17, 100, 7, 'Maxime velit aut rem recusandae delectus dolore voluptates.'),
(26, 'vitae', 12, 51, 11, 'Maiores repellendus corporis nihil reprehenderit.'),
(27, 'ea', 89, 74, 42, 'Quia repellat corporis blanditiis.'),
(28, 'qui', 22, 71, 22, 'Odio sed voluptatem dicta nihil molestias qui.'),
(29, 'illo', 30, 87, 16, 'Consectetur beatae atque quae ipsam ea.'),
(30, 'culpa', 20, 78, 39, 'Exercitationem delectus non ut cumque ut in.'),
(31, 'numquam', 23, 41, 47, 'Nisi ullam voluptates recusandae.'),
(32, 'rerum', 93, 97, 26, 'Rem officia autem ad asperiores.'),
(33, 'corrupti', 58, 64, 8, 'Tempora assumenda voluptates necessitatibus ut accusamus quo.'),
(34, 'et', 78, 76, 36, 'Delectus placeat quasi ipsam nemo doloribus molestiae.'),
(35, 'nostrum', 89, 29, 29, 'Et earum est labore unde aut sed facere aliquam.'),
(36, 'animi', 97, 98, 14, 'Id laudantium ut libero.'),
(37, 'dolores', 26, 79, 25, 'Vero quam inventore et reiciendis.'),
(38, 'sequi', 54, 79, 23, 'Explicabo voluptatum et voluptas vel dignissimos accusamus quae.'),
(39, 'quae', 65, 98, 37, 'Autem illum et ut adipisci ducimus blanditiis.'),
(40, 'unde', 70, 18, 49, 'Aut rerum quae debitis fuga fugit autem.'),
(41, 'pariatur', 75, 75, 12, 'Est minus sit est repudiandae veritatis est.'),
(42, 'aut', 75, 24, 8, 'Ipsum voluptatibus harum occaecati odit et quibusdam hic.'),
(43, 'eum', 81, 93, 43, 'Incidunt earum sunt assumenda optio.'),
(44, 'sunt', 30, 79, 40, 'Laborum qui aut dolorem distinctio.'),
(45, 'ut', 47, 89, 15, 'Cumque quis odio labore ipsam quos vero.'),
(46, 'vitae', 84, 36, 36, 'Qui quod qui cumque assumenda.'),
(47, 'et', 26, 68, 31, 'Officiis voluptatibus ut quidem dolorem.'),
(48, 'ab', 65, 73, 38, 'Recusandae quas qui voluptatum.'),
(49, 'omnis', 74, 61, 5, 'Sint quod voluptatibus dicta iste mollitia laboriosam unde.'),
(50, 'quo', 82, 76, 10, 'Dicta unde repellat fugiat velit consequatur id asperiores.');

-- --------------------------------------------------------

--
-- Table structure for table `plugn_updates`
--

CREATE TABLE `plugn_updates` (
  `update_uuid` varchar(60) COLLATE utf8_unicode_ci NOT NULL,
  `title` varchar(100) COLLATE utf8_unicode_ci NOT NULL,
  `content` text COLLATE utf8_unicode_ci NOT NULL,
  `title_ar` varchar(100) COLLATE utf8_unicode_ci NOT NULL,
  `content_ar` text COLLATE utf8_unicode_ci NOT NULL,
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `prebuilt_email_template`
--

CREATE TABLE `prebuilt_email_template` (
  `template_uuid` char(60) COLLATE utf8_unicode_ci NOT NULL,
  `subject` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `message` text COLLATE utf8_unicode_ci DEFAULT NULL,
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `queue`
--

CREATE TABLE `queue` (
  `queue_id` int(11) NOT NULL,
  `restaurant_uuid` char(60) COLLATE utf8_unicode_ci NOT NULL,
  `queue_status` smallint(6) DEFAULT 1,
  `queue_response` text COLLATE utf8_unicode_ci DEFAULT NULL,
  `queue_created_at` datetime DEFAULT NULL,
  `queue_updated_at` datetime DEFAULT NULL,
  `queue_start_at` datetime DEFAULT NULL,
  `queue_end_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `queue`
--

INSERT INTO `queue` (`queue_id`, `restaurant_uuid`, `queue_status`, `queue_response`, `queue_created_at`, `queue_updated_at`, `queue_start_at`, `queue_end_at`) VALUES
(1, '1', 2, NULL, '2018-08-24 13:56:12', '2000-03-28 03:19:34', '1990-10-11 17:18:31', '2018-10-29 06:16:45'),
(2, '2', 1, NULL, '2007-03-01 07:44:42', '2010-09-05 20:32:41', '1998-08-12 04:22:00', '1999-02-09 23:30:53'),
(3, '3', 2, NULL, '1989-07-04 01:02:01', '1998-12-18 23:57:37', '1996-09-03 05:27:37', '1998-10-19 19:06:17'),
(4, '4', 2, NULL, '1982-05-08 10:05:29', '1974-02-12 05:28:02', '1982-01-03 11:12:41', '2008-09-22 22:56:49'),
(5, '5', 1, NULL, '1981-07-23 13:46:44', '1981-08-06 06:08:42', '1997-12-14 16:52:38', '2001-12-17 00:22:10'),
(6, '6', 1, NULL, '1993-10-16 15:25:51', '2010-11-08 02:24:15', '2008-01-16 11:56:21', '1980-10-01 19:21:27'),
(7, '7', 2, NULL, '1988-02-27 19:27:34', '2003-05-12 08:17:35', '1994-06-08 19:34:14', '1991-07-22 16:33:32'),
(8, '8', 1, NULL, '1970-04-14 08:43:44', '1993-06-10 14:53:28', '1990-11-06 02:08:34', '2015-11-15 21:57:10'),
(9, '9', 1, NULL, '2014-06-05 03:32:03', '1974-07-14 23:20:57', '1999-06-22 16:14:16', '2008-06-12 18:09:46'),
(10, '10', 3, NULL, '1973-10-09 01:34:52', '1984-10-17 16:46:50', '2002-08-17 08:48:18', '2015-08-04 04:25:30'),
(11, '11', 3, NULL, '1982-01-29 11:48:39', '1993-08-21 05:59:10', '1974-06-13 08:56:28', '1987-06-02 01:51:51'),
(12, '12', 3, NULL, '2014-05-29 06:08:02', '1986-09-13 16:25:38', '1988-06-28 00:23:45', '1970-07-27 17:05:51'),
(13, '13', 2, NULL, '1972-03-14 16:49:52', '2017-10-03 19:26:18', '1991-04-12 21:12:32', '2010-12-04 12:08:34'),
(14, '14', 2, NULL, '2003-07-17 13:20:23', '1998-04-28 12:50:42', '1990-06-23 22:34:38', '2019-09-17 10:59:27'),
(15, '15', 2, NULL, '2012-05-28 14:29:14', '1980-01-09 16:32:56', '1978-02-18 01:28:08', '1995-12-26 07:18:29'),
(16, '16', 2, NULL, '1989-03-14 11:53:41', '1993-09-10 02:21:44', '1989-11-17 00:20:01', '2019-05-30 21:16:31'),
(17, '17', 3, NULL, '1988-05-09 20:57:26', '2001-08-06 05:50:19', '1971-04-14 09:00:12', '2015-02-28 08:12:20'),
(18, '18', 3, NULL, '2000-10-20 19:44:33', '1987-09-27 14:29:34', '2016-02-08 08:21:50', '2017-04-20 14:35:30'),
(19, '19', 1, NULL, '2011-03-02 11:36:51', '1987-07-09 15:27:11', '1977-01-04 06:15:47', '2012-04-13 00:52:01'),
(20, '20', 1, NULL, '1986-07-21 11:15:52', '1993-10-25 20:39:56', '2008-10-15 07:13:09', '1986-02-26 13:03:16'),
(21, '21', 1, NULL, '1974-08-11 14:18:53', '1989-03-09 22:19:46', '1990-08-08 13:35:29', '1972-07-26 00:17:48'),
(22, '22', 3, NULL, '2021-01-03 05:48:54', '1988-10-16 01:53:34', '2004-09-27 00:09:01', '1973-06-19 04:07:23'),
(23, '23', 1, NULL, '1982-01-14 23:01:35', '1998-06-05 23:55:47', '1975-10-16 22:30:26', '1992-01-10 07:39:52'),
(24, '24', 3, NULL, '1985-03-19 01:24:50', '2002-02-22 16:37:45', '1996-06-03 05:09:34', '2001-06-20 09:04:20'),
(25, '25', 2, NULL, '1971-11-17 23:11:59', '1999-11-04 05:47:56', '2010-01-06 13:43:47', '2016-03-15 11:03:42'),
(26, '26', 1, NULL, '2015-08-08 17:08:29', '2018-09-11 00:52:23', '2003-03-28 22:02:13', '2014-01-16 19:44:50'),
(27, '27', 3, NULL, '2004-05-16 21:13:52', '1972-10-20 00:16:46', '2004-12-26 10:16:25', '1973-07-30 08:37:58'),
(28, '28', 3, NULL, '2001-03-16 04:18:36', '2012-02-04 00:40:10', '1996-08-02 14:50:21', '1998-12-30 21:13:11'),
(29, '29', 3, NULL, '1977-01-09 16:58:36', '2000-01-06 12:31:21', '1983-03-05 07:32:44', '2006-04-25 13:01:51'),
(30, '30', 3, NULL, '1974-09-05 11:31:47', '1989-08-03 05:43:11', '1999-03-28 02:01:50', '2000-04-14 16:56:25'),
(31, '31', 3, NULL, '1980-08-21 05:23:43', '2008-05-16 00:50:00', '1999-03-29 09:05:40', '2005-12-30 18:49:20'),
(32, '32', 3, NULL, '1999-07-10 19:13:49', '2013-01-31 12:06:34', '2003-06-04 09:44:46', '1993-03-27 21:39:29'),
(33, '33', 3, NULL, '1999-08-17 13:14:03', '1971-08-17 03:32:28', '1973-07-18 21:27:45', '2000-09-28 08:36:32'),
(34, '34', 1, NULL, '1972-12-22 07:45:17', '2006-06-03 14:43:17', '1995-11-28 13:50:47', '2001-11-10 23:58:30'),
(35, '35', 3, NULL, '1970-10-23 00:11:40', '2000-01-05 11:56:14', '2006-07-15 20:54:54', '1990-09-16 04:50:36'),
(36, '36', 2, NULL, '2014-06-25 12:34:20', '1999-09-04 00:47:10', '2019-07-16 01:59:02', '1989-02-10 05:24:51'),
(37, '37', 1, NULL, '1999-08-07 16:35:04', '2015-01-14 19:15:06', '2014-03-02 14:15:14', '2009-07-26 10:52:34'),
(38, '38', 2, NULL, '2017-09-03 10:18:24', '1978-03-13 04:23:17', '2004-02-10 17:55:54', '1994-05-17 11:33:53'),
(39, '39', 1, NULL, '1978-12-10 22:12:48', '2001-05-23 04:40:04', '1984-06-23 12:57:09', '2012-12-28 00:15:55'),
(40, '40', 2, NULL, '1995-02-07 09:58:52', '1993-11-30 00:57:50', '2001-04-12 17:31:19', '2016-10-27 06:31:30'),
(41, '41', 1, NULL, '1980-09-05 13:33:11', '1984-01-12 04:20:38', '2006-11-15 16:41:44', '2008-03-29 11:45:19'),
(42, '42', 2, NULL, '2018-04-27 12:58:14', '2020-04-24 03:09:23', '2007-06-26 05:47:08', '1995-03-06 18:25:18'),
(43, '43', 3, NULL, '2008-02-14 22:35:56', '2012-07-13 00:39:01', '1977-12-22 23:36:08', '2016-08-14 17:15:09'),
(44, '44', 2, NULL, '1984-11-26 01:47:43', '1977-04-23 21:03:55', '2017-10-22 09:15:16', '1990-11-26 11:07:24'),
(45, '45', 3, NULL, '2013-03-24 12:16:53', '2017-05-21 17:52:23', '2011-06-11 11:12:06', '2020-05-18 03:45:18'),
(46, '46', 3, NULL, '1976-09-30 22:18:38', '1984-05-02 03:59:12', '2016-07-11 04:12:04', '2015-03-23 01:25:46'),
(47, '47', 3, NULL, '1983-02-05 16:31:11', '1980-06-14 13:26:08', '2011-01-14 01:27:55', '1994-02-11 19:29:19'),
(48, '48', 3, NULL, '1991-01-21 18:06:51', '1987-07-22 08:28:11', '2011-11-15 07:41:19', '2014-05-09 19:44:42'),
(49, '49', 3, NULL, '1995-06-16 03:51:44', '1998-07-08 23:07:10', '1977-09-29 18:04:49', '1978-03-07 15:19:32'),
(50, '50', 1, NULL, '2017-03-16 03:44:27', '2008-12-06 02:20:11', '2001-11-06 23:27:53', '2018-06-27 01:39:19');

-- --------------------------------------------------------

--
-- Table structure for table `refund`
--

CREATE TABLE `refund` (
  `refund_id` char(60) COLLATE utf8_unicode_ci NOT NULL,
  `payment_uuid` char(36) COLLATE utf8_unicode_ci DEFAULT NULL,
  `restaurant_uuid` char(60) COLLATE utf8_unicode_ci NOT NULL,
  `order_uuid` char(40) COLLATE utf8_unicode_ci DEFAULT NULL,
  `refund_amount` float NOT NULL,
  `reason` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `refund_status` varchar(255) COLLATE utf8_unicode_ci DEFAULT 'Initiated',
  `refund_message` text COLLATE utf8_unicode_ci DEFAULT NULL,
  `refund_created_at` datetime DEFAULT NULL,
  `refund_updated_at` datetime DEFAULT NULL,
  `refund_reference` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `refund`
--

INSERT INTO `refund` (`refund_id`, `payment_uuid`, `restaurant_uuid`, `order_uuid`, `refund_amount`, `reason`, `refund_status`, `refund_message`, `refund_created_at`, `refund_updated_at`, `refund_reference`) VALUES
('1', NULL, '1', '1', 88, 'Ratione et vel deserunt et voluptatem et.', 'Pending', NULL, NULL, NULL, NULL),
('10', NULL, '10', '10', 70, 'In sed quia voluptatem dolores.', 'Pending', NULL, NULL, NULL, NULL),
('11', NULL, '11', '11', 72, 'Autem illo ut rerum soluta eum fugit.', 'Pending', NULL, NULL, NULL, NULL),
('12', NULL, '12', '12', 59, 'Itaque voluptatem dolorum nam cum.', 'Pending', NULL, NULL, NULL, NULL),
('13', NULL, '13', '13', 66, 'Quia quibusdam sint odio vel esse officiis aut aut.', 'Pending', NULL, NULL, NULL, NULL),
('14', NULL, '14', '14', 43, 'Non quia fugit consequuntur ea illum.', 'Pending', NULL, NULL, NULL, NULL),
('15', NULL, '15', '15', 88, 'Et veritatis similique quae deserunt.', 'Pending', NULL, NULL, NULL, NULL),
('16', NULL, '16', '16', 40, 'Sapiente voluptate consectetur quia.', 'Pending', NULL, NULL, NULL, NULL),
('17', NULL, '17', '17', 92, 'Harum nesciunt eligendi at qui doloremque aut.', 'Pending', NULL, NULL, NULL, NULL),
('18', NULL, '18', '18', 54, 'Sint sunt libero itaque assumenda quis aliquid impedit.', 'Pending', NULL, NULL, NULL, NULL),
('19', NULL, '19', '19', 62, 'Repudiandae sint autem omnis.', 'Pending', NULL, NULL, NULL, NULL),
('2', NULL, '2', '2', 74, 'Nihil quo a voluptates ab.', 'Pending', NULL, NULL, NULL, NULL),
('20', NULL, '20', '20', 13, 'Saepe ut facere molestias explicabo consectetur rerum.', 'Pending', NULL, NULL, NULL, NULL),
('21', NULL, '21', '21', 45, 'Cum cumque porro officia ex autem dignissimos qui omnis.', 'Pending', NULL, NULL, NULL, NULL),
('22', NULL, '22', '22', 77, 'Et qui dolores nulla placeat aliquid commodi nihil corporis.', 'Pending', NULL, NULL, NULL, NULL),
('23', NULL, '23', '23', 94, 'Facere aut harum voluptatum exercitationem recusandae tenetur et dolor.', 'Pending', NULL, NULL, NULL, NULL),
('24', NULL, '24', '24', 83, 'A odit sit et dignissimos omnis quia autem.', 'Pending', NULL, NULL, NULL, NULL),
('25', NULL, '25', '25', 85, 'Esse nam iure est velit nihil sequi quaerat aut.', 'Pending', NULL, NULL, NULL, NULL),
('26', NULL, '26', '26', 22, 'Nobis dolorem eos animi magni laborum.', 'Pending', NULL, NULL, NULL, NULL),
('27', NULL, '27', '27', 94, 'Sunt ab cum quisquam cumque cupiditate possimus.', 'Pending', NULL, NULL, NULL, NULL),
('28', NULL, '28', '28', 26, 'Necessitatibus maxime ut voluptate ex.', 'Pending', NULL, NULL, NULL, NULL),
('29', NULL, '29', '29', 42, 'Voluptatem laborum reiciendis nisi est rerum non est.', 'Pending', NULL, NULL, NULL, NULL),
('3', NULL, '3', '3', 24, 'Labore sequi corrupti et commodi minus commodi.', 'Pending', NULL, NULL, NULL, NULL),
('30', NULL, '30', '30', 69, 'Ut qui quia voluptas et ea.', 'Pending', NULL, NULL, NULL, NULL),
('31', NULL, '31', '31', 71, 'Perferendis esse dicta doloremque qui.', 'Pending', NULL, NULL, NULL, NULL),
('32', NULL, '32', '32', 57, 'Sit perferendis qui consequatur nam.', 'Pending', NULL, NULL, NULL, NULL),
('33', NULL, '33', '33', 74, 'Minima itaque mollitia accusantium consequatur quo iure rerum unde.', 'Pending', NULL, NULL, NULL, NULL),
('34', NULL, '34', '34', 72, 'Et ut saepe eveniet velit id nihil iusto id.', 'Pending', NULL, NULL, NULL, NULL),
('35', NULL, '35', '35', 13, 'Quo iusto sunt aut facilis voluptates.', 'Pending', NULL, NULL, NULL, NULL),
('36', NULL, '36', '36', 41, 'Et dolor beatae et corrupti.', 'Pending', NULL, NULL, NULL, NULL),
('37', NULL, '37', '37', 90, 'Consequatur enim saepe sapiente nisi quis sunt impedit.', 'Pending', NULL, NULL, NULL, NULL),
('38', NULL, '38', '38', 28, 'Quisquam sed tempora voluptatem.', 'Pending', NULL, NULL, NULL, NULL),
('39', NULL, '39', '39', 87, 'Vel molestiae exercitationem nostrum ad enim nobis sunt.', 'Pending', NULL, NULL, NULL, NULL),
('4', NULL, '4', '4', 78, 'Ullam dolores exercitationem perferendis fuga repudiandae.', 'Pending', NULL, NULL, NULL, NULL),
('40', NULL, '40', '40', 38, 'Illo eum magni sunt harum veniam.', 'Pending', NULL, NULL, NULL, NULL),
('41', NULL, '41', '41', 99, 'Illum eum aut iusto pariatur eum laborum.', 'Pending', NULL, NULL, NULL, NULL),
('42', NULL, '42', '42', 70, 'Doloremque quia dignissimos cumque voluptatem.', 'Pending', NULL, NULL, NULL, NULL),
('43', NULL, '43', '43', 48, 'Maxime consequatur magnam qui tempore.', 'Pending', NULL, NULL, NULL, NULL),
('44', NULL, '44', '44', 88, 'Distinctio aut consequatur et quos dolorem.', 'Pending', NULL, NULL, NULL, NULL),
('45', NULL, '45', '45', 76, 'Debitis numquam molestiae perspiciatis soluta rerum laudantium et voluptatum.', 'Pending', NULL, NULL, NULL, NULL),
('46', NULL, '46', '46', 96, 'Ut a dolorem dolor est modi iste aliquam.', 'Pending', NULL, NULL, NULL, NULL),
('47', NULL, '47', '47', 82, 'Qui libero iure omnis similique.', 'Pending', NULL, NULL, NULL, NULL),
('48', NULL, '48', '48', 45, 'Nisi tempore id enim corrupti.', 'Pending', NULL, NULL, NULL, NULL),
('49', NULL, '49', '49', 18, 'Est sed nesciunt delectus exercitationem at et alias.', 'Pending', NULL, NULL, NULL, NULL),
('5', NULL, '5', '5', 52, 'Qui laborum magni qui rerum pariatur odit est et.', 'Pending', NULL, NULL, NULL, NULL),
('50', NULL, '50', '50', 32, 'Quod enim est ut quam magnam.', 'Pending', NULL, NULL, NULL, NULL),
('6', NULL, '6', '6', 51, 'Neque cumque asperiores iure cumque.', 'Pending', NULL, NULL, NULL, NULL),
('7', NULL, '7', '7', 38, 'Ut amet est et officia suscipit.', 'Pending', NULL, NULL, NULL, NULL),
('8', NULL, '8', '8', 82, 'Aut nostrum laudantium rerum ut.', 'Pending', NULL, NULL, NULL, NULL),
('9', NULL, '9', '9', 51, 'Molestiae minus maiores quam praesentium nihil sed nihil.', 'Pending', NULL, NULL, NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `refunded_item`
--

CREATE TABLE `refunded_item` (
  `refunded_item_id` int(11) NOT NULL,
  `refund_id` char(60) COLLATE utf8_unicode_ci NOT NULL,
  `order_item_id` bigint(20) DEFAULT NULL,
  `order_uuid` char(40) COLLATE utf8_unicode_ci DEFAULT NULL,
  `item_uuid` varchar(300) COLLATE utf8_unicode_ci DEFAULT NULL,
  `item_name` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `item_name_ar` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `item_price` float NOT NULL,
  `qty` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `refunded_item`
--

INSERT INTO `refunded_item` (`refunded_item_id`, `refund_id`, `order_item_id`, `order_uuid`, `item_uuid`, `item_name`, `item_name_ar`, `item_price`, `qty`) VALUES
(1, '1', 1, '1', '1', 'quo suscipit dolores', NULL, 76, 22),
(2, '2', 2, '2', '2', 'neque adipisci tempora', NULL, 86, 41),
(3, '3', 3, '3', '3', 'nam quis et', NULL, 40, 31),
(4, '4', 4, '4', '4', 'ducimus voluptatum architecto', NULL, 16, 89),
(5, '5', 5, '5', '5', 'sapiente repudiandae officia', NULL, 69, 18),
(6, '6', 6, '6', '6', 'a illum sed', NULL, 96, 73),
(7, '7', 7, '7', '7', 'quasi impedit repudiandae', NULL, 13, 14),
(8, '8', 8, '8', '8', 'nulla harum aut', NULL, 89, 15),
(9, '9', 9, '9', '9', 'libero cum illum', NULL, 19, 62),
(10, '10', 10, '10', '10', 'tempore est ipsa', NULL, 90, 43),
(11, '11', 11, '11', '11', 'velit voluptatem qui', NULL, 91, 14),
(12, '12', 12, '12', '12', 'ducimus quibusdam molestias', NULL, 49, 100),
(13, '13', 13, '13', '13', 'quia dolorem quos', NULL, 72, 20),
(14, '14', 14, '14', '14', 'placeat velit vero', NULL, 33, 41),
(15, '15', 15, '15', '15', 'temporibus cumque repudiandae', NULL, 90, 51),
(16, '16', 16, '16', '16', 'dolores vitae soluta', NULL, 94, 91),
(17, '17', 17, '17', '17', 'et sit tempore', NULL, 49, 15),
(18, '18', 18, '18', '18', 'nemo qui laborum', NULL, 26, 15),
(19, '19', 19, '19', '19', 'et et corrupti', NULL, 42, 22),
(20, '20', 20, '20', '20', 'enim dolor quo', NULL, 10, 37),
(21, '21', 21, '21', '21', 'dicta aperiam laborum', NULL, 76, 38),
(22, '22', 22, '22', '22', 'et qui dicta', NULL, 69, 19),
(23, '23', 23, '23', '23', 'quia rerum voluptatem', NULL, 63, 79),
(24, '24', 24, '24', '24', 'rerum aut quo', NULL, 45, 94),
(25, '25', 25, '25', '25', 'ad molestiae ut', NULL, 12, 42),
(26, '26', 26, '26', '26', 'maxime quo libero', NULL, 47, 82),
(27, '27', 27, '27', '27', 'exercitationem autem magni', NULL, 22, 49),
(28, '28', 28, '28', '28', 'consectetur ut aut', NULL, 62, 47),
(29, '29', 29, '29', '29', 'ab ullam sed', NULL, 77, 40),
(30, '30', 30, '30', '30', 'impedit incidunt corporis', NULL, 10, 34),
(31, '31', 31, '31', '31', 'et quis cupiditate', NULL, 53, 91),
(32, '32', 32, '32', '32', 'et dolores ipsam', NULL, 78, 49),
(33, '33', 33, '33', '33', 'porro itaque labore', NULL, 35, 51),
(34, '34', 34, '34', '34', 'minus sit aut', NULL, 63, 24),
(35, '35', 35, '35', '35', 'sed doloribus corrupti', NULL, 14, 14),
(36, '36', 36, '36', '36', 'molestiae porro inventore', NULL, 13, 17),
(37, '37', 37, '37', '37', 'ut quo est', NULL, 98, 49),
(38, '38', 38, '38', '38', 'maxime qui excepturi', NULL, 11, 46),
(39, '39', 39, '39', '39', 'non quia veritatis', NULL, 77, 87),
(40, '40', 40, '40', '40', 'unde modi et', NULL, 18, 70),
(41, '41', 41, '41', '41', 'repellendus rerum ipsum', NULL, 16, 37),
(42, '42', 42, '42', '42', 'explicabo vero fuga', NULL, 72, 91),
(43, '43', 43, '43', '43', 'id quis soluta', NULL, 43, 30),
(44, '44', 44, '44', '44', 'ullam sed autem', NULL, 29, 61),
(45, '45', 45, '45', '45', 'ut quod ut', NULL, 89, 19),
(46, '46', 46, '46', '46', 'dolor omnis iusto', NULL, 15, 47),
(47, '47', 47, '47', '47', 'et illum sit', NULL, 81, 55),
(48, '48', 48, '48', '48', 'rerum fuga hic', NULL, 84, 98),
(49, '49', 49, '49', '49', 'aperiam sint accusamus', NULL, 39, 65),
(50, '50', 50, '50', '50', 'optio aliquid maiores', NULL, 49, 48);

-- --------------------------------------------------------

--
-- Table structure for table `restaurant`
--

CREATE TABLE `restaurant` (
  `restaurant_uuid` char(60) COLLATE utf8_unicode_ci NOT NULL,
  `country_id` int(11) NOT NULL DEFAULT 84,
  `currency_id` int(11) NOT NULL DEFAULT 2,
  `name` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `name_ar` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `meta_title` text COLLATE utf8_unicode_ci DEFAULT NULL,
  `meta_title_ar` text COLLATE utf8_unicode_ci DEFAULT NULL,
  `meta_description` text COLLATE utf8_unicode_ci DEFAULT NULL,
  `meta_description_ar` text COLLATE utf8_unicode_ci DEFAULT NULL,
  `tagline` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `tagline_ar` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `restaurant_domain` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `app_id` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `restaurant_status` smallint(1) NOT NULL DEFAULT 1,
  `thumbnail_image` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `logo` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `support_delivery` tinyint(1) DEFAULT 1,
  `support_pick_up` tinyint(1) DEFAULT 1,
  `phone_number` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `phone_number_country_code` int(3) DEFAULT 965,
  `restaurant_email` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `restaurant_created_at` datetime DEFAULT NULL,
  `restaurant_updated_at` datetime DEFAULT NULL,
  `business_id` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `business_entity_id` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `wallet_id` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `merchant_id` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `operator_id` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `live_api_key` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `test_api_key` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `business_type` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `vendor_sector` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `license_number` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `not_for_profit` tinyint(1) NOT NULL DEFAULT 0,
  `authorized_signature_issuing_date` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `authorized_signature_expiry_date` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `authorized_signature_title` varchar(255) COLLATE utf8_unicode_ci DEFAULT 'Authorized Signature',
  `authorized_signature_file` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `authorized_signature_file_id` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `authorized_signature_file_purpose` varchar(255) COLLATE utf8_unicode_ci DEFAULT 'customer_signature',
  `iban` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `owner_first_name` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `owner_last_name` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `owner_email` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `owner_number` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `owner_phone_country_code` int(3) DEFAULT 965,
  `identification_issuing_date` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `identification_expiry_date` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `identification_file_front_side` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `identification_file_id_front_side` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `identification_title` varchar(255) COLLATE utf8_unicode_ci DEFAULT 'Owner civil id',
  `identification_file_purpose` varchar(255) COLLATE utf8_unicode_ci DEFAULT 'identity_document',
  `restaurant_email_notification` smallint(6) DEFAULT 0,
  `developer_id` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `armada_api_key` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `phone_number_display` smallint(6) DEFAULT 3,
  `store_branch_name` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `custom_css` text COLLATE utf8_unicode_ci DEFAULT NULL,
  `store_layout` smallint(6) DEFAULT 1,
  `commercial_license_issuing_date` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `commercial_license_expiry_date` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `commercial_license_title` varchar(255) COLLATE utf8_unicode_ci DEFAULT 'Commercial License',
  `commercial_license_file` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `commercial_license_file_id` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `commercial_license_file_purpose` varchar(255) COLLATE utf8_unicode_ci DEFAULT 'customer_signature',
  `platform_fee` float UNSIGNED DEFAULT 0.05,
  `google_analytics_id` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `facebook_pixil_id` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `show_opening_hours` smallint(6) DEFAULT 0,
  `instagram_url` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `schedule_order` smallint(6) DEFAULT 0,
  `schedule_interval` smallint(6) DEFAULT 60,
  `mashkor_branch_id` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `live_public_key` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `test_public_key` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `site_id` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `company_name` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `is_tap_enable` tinyint(1) DEFAULT 0,
  `is_myfatoorah_enable` tinyint(1) DEFAULT 0,
  `supplierCode` tinyint(1) DEFAULT NULL,
  `has_deployed` tinyint(1) UNSIGNED DEFAULT 0,
  `tap_queue_id` int(11) DEFAULT NULL,
  `annual_revenue` char(60) COLLATE utf8_unicode_ci DEFAULT NULL,
  `identification_file_back_side` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `identification_file_id_back_side` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `default_language` char(2) COLLATE utf8_unicode_ci NOT NULL DEFAULT 'en',
  `hide_request_driver_button` tinyint(1) UNSIGNED DEFAULT 1,
  `version` smallint(6) UNSIGNED DEFAULT 1,
  `sitemap_require_update` smallint(6) UNSIGNED DEFAULT 0,
  `warehouse_fee` decimal(10,3) UNSIGNED DEFAULT 0.000,
  `warehouse_delivery_charges` decimal(10,3) UNSIGNED NOT NULL DEFAULT 0.000,
  `payment_gateway_queue_id` int(11) DEFAULT NULL,
  `snapchat_pixil_id` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `retention_email_sent` smallint(6) DEFAULT 0,
  `enable_gift_message` smallint(6) DEFAULT 0,
  `referral_code` varchar(6) COLLATE utf8_unicode_ci DEFAULT NULL,
  `custom_subscription_price` decimal(10,3) DEFAULT NULL,
  `demand_delivery` smallint(6) DEFAULT 1,
  `is_public` tinyint(1) DEFAULT 1,
  `accept_order_247` tinyint(1) DEFAULT 0,
  `is_sandbox` tinyint(1) DEFAULT 0,
  `is_under_maintenance` tinyint(1) DEFAULT 0,
  `is_deleted` tinyint(1) DEFAULT 0,
  `enable_debugger` tinyint(1) DEFAULT 0,
  `last_active_at` datetime DEFAULT NULL,
  `last_order_at` datetime DEFAULT NULL,
  `warned_delete_at` date DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `restaurant`
--

INSERT INTO `restaurant` (`restaurant_uuid`, `country_id`, `currency_id`, `name`, `name_ar`, `meta_title`, `meta_title_ar`, `meta_description`, `meta_description_ar`, `tagline`, `tagline_ar`, `restaurant_domain`, `app_id`, `restaurant_status`, `thumbnail_image`, `logo`, `support_delivery`, `support_pick_up`, `phone_number`, `phone_number_country_code`, `restaurant_email`, `restaurant_created_at`, `restaurant_updated_at`, `business_id`, `business_entity_id`, `wallet_id`, `merchant_id`, `operator_id`, `live_api_key`, `test_api_key`, `business_type`, `vendor_sector`, `license_number`, `not_for_profit`, `authorized_signature_issuing_date`, `authorized_signature_expiry_date`, `authorized_signature_title`, `authorized_signature_file`, `authorized_signature_file_id`, `authorized_signature_file_purpose`, `iban`, `owner_first_name`, `owner_last_name`, `owner_email`, `owner_number`, `owner_phone_country_code`, `identification_issuing_date`, `identification_expiry_date`, `identification_file_front_side`, `identification_file_id_front_side`, `identification_title`, `identification_file_purpose`, `restaurant_email_notification`, `developer_id`, `armada_api_key`, `phone_number_display`, `store_branch_name`, `custom_css`, `store_layout`, `commercial_license_issuing_date`, `commercial_license_expiry_date`, `commercial_license_title`, `commercial_license_file`, `commercial_license_file_id`, `commercial_license_file_purpose`, `platform_fee`, `google_analytics_id`, `facebook_pixil_id`, `show_opening_hours`, `instagram_url`, `schedule_order`, `schedule_interval`, `mashkor_branch_id`, `live_public_key`, `test_public_key`, `site_id`, `company_name`, `is_tap_enable`, `is_myfatoorah_enable`, `supplierCode`, `has_deployed`, `tap_queue_id`, `annual_revenue`, `identification_file_back_side`, `identification_file_id_back_side`, `default_language`, `hide_request_driver_button`, `version`, `sitemap_require_update`, `warehouse_fee`, `warehouse_delivery_charges`, `payment_gateway_queue_id`, `snapchat_pixil_id`, `retention_email_sent`, `enable_gift_message`, `referral_code`, `custom_subscription_price`, `demand_delivery`, `is_public`, `accept_order_247`, `is_sandbox`, `is_under_maintenance`, `is_deleted`, `enable_debugger`, `last_active_at`, `last_order_at`, `warned_delete_at`) VALUES
('1', 1, 1, 'voluptatibus', 'aliquid', NULL, NULL, NULL, NULL, 'Repudiandae voluptatibus error rerum minus.', 'Repellendus sint magni expedita eaque fuga.', 'rolfson.info', '7b58c3ff5f8301c3c0ec52ff7a3b6bd1', 1, 'uiAiNXR9gxH7LzPB2D4JJGVGAaQaoYE9.jpg', 'lG8AZqroaynHiHYSnMJ-ZZsF_bc0C6KY.jpg', 1, 1, '894-652-7035 x767', 0, 'bogan.dwight@yahoo.com', '1982-07-31 00:09:29', '1970-02-16 11:26:37', '1', '1', '1', '1', '1', 'b81122499a182f5e0170358e55a7a6a8', '1e96f9cfeaf6cfa2fedc81c9bd638f6a', NULL, 'quaerat', 'in', 0, NULL, NULL, 'et', NULL, '1', 'Authorized Signature', 'GB19WHBK64903335152913', 'Anabelle', 'Hauck', 'cjast@gmail.com', '(953) 516-1652 x442', 91, NULL, NULL, NULL, NULL, 'voluptatibus', 'natus', 1, 'evan.kerluke@hotmail.com', 'd154d1b1de956d5e30960bcc3e9e0898', 1, 'Smith Key', NULL, 1, NULL, NULL, 'voluptatem', NULL, NULL, NULL, 321, 'a4ce1324a28d7e2072fef2a0833183b0', 'cbf592a39ffdd351d2f4f3e492a0e8d7', 1, 'http://www.howell.com/', 1, 1, 'b4e1e4b61fba22f15eb6c1440f7b7329', 'e600293b502f7f67451bbc886ac2e58f', 'c80b6c88d9b1896fd1d1228ce396a189', '1', 'Bednar, Wehner and Huels', 1, 0, NULL, 1, 401, NULL, 'autem', 'neque', 'sg', 0, 2, 1, '98.000', '0.000', NULL, 'b12def24a3a9c42c0dffbad871d99297', 1, 1, NULL, NULL, 1, 1, 0, 0, 0, 0, 0, NULL, NULL, NULL),
('10', 10, 10, 'occaecati', 'enim', NULL, NULL, NULL, NULL, 'Enim voluptas laudantium.', 'Nobis qui unde aut in laborum.', 'upton.com', 'b360915b3b9765d1a6de587ed5e6ac06', 1, 'uiAiNXR9gxH7LzPB2D4JJGVGAaQaoYE9.jpg', 'lG8AZqroaynHiHYSnMJ-ZZsF_bc0C6KY.jpg', 1, 1, '+1-593-648-2210', 0, 'mayra.stark@yahoo.com', '1988-10-18 08:47:35', '1982-02-16 09:49:21', '10', '10', '10', '10', '10', 'bf374df9f78366f2b02f5038e0609b31', 'e0a221121bd7a13d5704e0419b32f2a0', NULL, 'assumenda', 'mollitia', 0, NULL, NULL, 'omnis', NULL, '10', 'Authorized Signature', 'HU57950471911347229913796562', 'Claire', 'Blick', 'letha.bauch@toy.com', '721.945.2543 x20962', 91, NULL, NULL, NULL, NULL, 'esse', 'in', 1, 'dcollier@abshire.com', '3576ec94ff2e55ef7b23267f78d9a650', 1, 'Pedro Creek', NULL, 1, NULL, NULL, 'ut', NULL, NULL, NULL, 370, '7379c2360d8951729859a17096bfd343', '44de5086c0f9d293aafd25578f92ec4f', 1, 'http://paucek.com/nobis-mollitia-possimus-optio-nesciunt', 1, 1, '6e3183d277aa1ee3c4fc27ef7c5e4112', '4e3e62930f8c9e5f95554f75a4f8fa15', '60942abc42587997c387b4c98bca17a2', '10', 'Schultz, Haley and Eichmann', 1, 0, NULL, 1, 596, NULL, 'id', 'qui', 'mg', 0, 2, 1, '15.000', '0.000', NULL, 'cdc6c68673b82504e44a474161902c94', 1, 1, NULL, NULL, 1, 1, 0, 0, 0, 0, 0, NULL, NULL, NULL),
('11', 11, 11, 'cumque', 'dolorum', NULL, NULL, NULL, NULL, 'Iusto quis aut et.', 'Eligendi sunt nostrum.', 'howell.com', '7a7923c0ae07aef772f63168b9a6c31e', 1, 'uiAiNXR9gxH7LzPB2D4JJGVGAaQaoYE9.jpg', 'lG8AZqroaynHiHYSnMJ-ZZsF_bc0C6KY.jpg', 1, 1, '+1-221-578-1546', 0, 'auer.deonte@hotmail.com', '1998-08-01 06:35:24', '2019-08-15 15:36:38', '11', '11', '11', '11', '11', 'd594a9504a040e30d99d65be124bed45', '91cd2c571e5fe9ba626e473c055b0124', NULL, 'voluptas', 'reiciendis', 0, NULL, NULL, 'earum', NULL, '11', 'Authorized Signature', 'FI4936007880816940', 'Danika', 'Reynolds', 'burley45@hotmail.com', '384-423-6240 x43794', 91, NULL, NULL, NULL, NULL, 'nihil', 'nam', 1, 'oritchie@will.com', 'd91a3f462d3a0474a5fa2707fd5ffe25', 1, 'Rashawn Key', NULL, 1, NULL, NULL, 'quidem', NULL, NULL, NULL, 806, 'eda9a75a3661424e5371f788968001d4', '170221de390c0c8e4d66b265e70ae91e', 1, 'https://murphy.net/nulla-labore-blanditiis-et-odio.html', 1, 1, '7ac24a27ccd889b7599289236b7f6ad5', 'a4741ef96f2ec88a83711e9c4a2ca983', 'cd4c63660603d37349f28046ea1b0494', '11', 'Bogan, Ferry and Spinka', 1, 0, NULL, 1, 470, NULL, 'veritatis', 'dolor', 'sl', 0, 2, 1, '53.000', '0.000', NULL, '53daf0e85a5053f13de343dc55b5efc2', 1, 1, NULL, NULL, 1, 1, 0, 0, 0, 0, 0, NULL, NULL, NULL),
('12', 12, 12, 'fugit', 'nihil', NULL, NULL, NULL, NULL, 'Soluta voluptatem quis cumque sit sunt.', 'Voluptatem quas odit animi.', 'monahan.com', 'c356ba72e5502f880b6eb8675186f122', 1, 'uiAiNXR9gxH7LzPB2D4JJGVGAaQaoYE9.jpg', 'lG8AZqroaynHiHYSnMJ-ZZsF_bc0C6KY.jpg', 1, 1, '(832) 976-8609 x371', 0, 'cordia.dubuque@hotmail.com', '2013-11-12 22:52:44', '1996-01-13 18:19:39', '12', '12', '12', '12', '12', '3a15b0db85087474595f66015fbe21f0', '56886524b2b94cf46fa3ef4cf37c237b', NULL, 'nobis', 'et', 0, NULL, NULL, 'vel', NULL, '12', 'Authorized Signature', 'FI4324326802003905', 'Christine', 'Cormier', 'veda.barrows@yahoo.com', '1-548-369-4899 x4117', 91, NULL, NULL, NULL, NULL, 'error', 'natus', 1, 'lavonne30@gmail.com', '3b6ef271e5e2aed7567a62580f0575f2', 1, 'Flossie Crest', NULL, 1, NULL, NULL, 'dolore', NULL, NULL, NULL, 809, '6ec5efc13ef4a413eaede3d388bafb5b', '976443c8c69872d6d2e39fb37e94f8c9', 1, 'http://bernier.net/voluptatem-tempora-consequatur-in-quia-eum-illum', 1, 1, '06f6fd622be346d83b02ef60668334d5', '6606809c4a2b8079797c07776ed44244', 'ee97a8de118030bc1cc28ac9c92f6ec9', '12', 'O\'Reilly Group', 1, 0, NULL, 1, 895, NULL, 'doloremque', 'sint', 'xh', 0, 2, 1, '50.000', '0.000', NULL, '2e31c4878d6463fae53525324045a617', 1, 1, NULL, NULL, 1, 1, 0, 0, 0, 0, 0, NULL, NULL, NULL),
('13', 13, 13, 'quo', 'et', NULL, NULL, NULL, NULL, 'Qui consequatur maxime quo.', 'Illo amet maxime est beatae.', 'schaden.com', '42058c230e5662a1915fb73a1f8066d8', 1, 'uiAiNXR9gxH7LzPB2D4JJGVGAaQaoYE9.jpg', 'lG8AZqroaynHiHYSnMJ-ZZsF_bc0C6KY.jpg', 1, 1, '1-578-957-0474 x3566', 0, 'matteo87@daugherty.net', '1983-09-28 09:32:18', '1970-05-17 22:09:16', '13', '13', '13', '13', '13', '2cc81da965489d67b6fe8bad8da55137', '58500b033a044f7d746b5e61ece5fffc', NULL, 'sint', 'veritatis', 0, NULL, NULL, 'doloribus', NULL, '13', 'Authorized Signature', 'ES9564828501299803602193', 'Alfonso', 'Daugherty', 'yveum@gmail.com', '(621) 736-0602 x815', 91, NULL, NULL, NULL, NULL, 'non', 'veritatis', 1, 'mckenzie.justice@yahoo.com', '92205541519c4391fe1123f37c9197ca', 1, 'Wilmer Common', NULL, 1, NULL, NULL, 'non', NULL, NULL, NULL, 148, '3dbb4f282e3ef366d96dfe4103369060', 'e9ca4ab4ce00bda0652c4d68d15820a1', 1, 'http://erdman.com/', 1, 1, '8a3ab062bd50127b70cfa47bbe3f45a7', 'ac067ddca608c9634c12ff0dd9012965', 'dc59e006260d60f624e162377c79928d', '13', 'Von, Kreiger and Bruen', 1, 0, NULL, 1, 591, NULL, 'illum', 'ipsam', 'ur', 0, 2, 1, '75.000', '0.000', NULL, '6e9067f88e3aeb263d696a96425b25f7', 1, 1, NULL, NULL, 1, 1, 0, 0, 0, 0, 0, NULL, NULL, NULL),
('14', 14, 14, 'consequatur', 'sunt', NULL, NULL, NULL, NULL, 'Esse et officia odit qui.', 'Ut aliquam officiis quidem voluptas.', 'lynch.com', '770461d7caa6a60a3b6546e4509bf675', 1, 'uiAiNXR9gxH7LzPB2D4JJGVGAaQaoYE9.jpg', 'lG8AZqroaynHiHYSnMJ-ZZsF_bc0C6KY.jpg', 1, 1, '+1-337-918-3669', 0, 'barbara.paucek@yahoo.com', '2012-04-27 06:06:22', '1997-02-10 03:58:32', '14', '14', '14', '14', '14', 'af22a6a5f945e4fc95bee5670962f273', 'b886582083fb9eac69e83b7930fb6110', NULL, 'error', 'accusamus', 0, NULL, NULL, 'et', NULL, '14', 'Authorized Signature', 'GI52GFBB67703TC53WKB9O9', 'Neva', 'Towne', 'tvolkman@gmail.com', '665-420-8654 x33807', 91, NULL, NULL, NULL, NULL, 'nesciunt', 'nihil', 1, 'xsatterfield@gmail.com', '8622ca108773c03b0fcc0c5bbec771c2', 1, 'Florida Shoal', NULL, 1, NULL, NULL, 'reiciendis', NULL, NULL, NULL, 363, '6b084aa6dfbe45ecccf8c1a0b76b5d6c', '03b645055bae3ff584b83e2a8904a89b', 1, 'http://www.rosenbaum.biz/quam-voluptate-reiciendis-ex', 1, 1, 'f4e3f7d0234384184345da26ce6baf51', '46e2c43cf135119b480e4898bc0af496', '8a4e2a2e360d95ac3e009071cde91015', '14', 'Harris Ltd', 1, 0, NULL, 1, 622, NULL, 'vitae', 'est', 'nb', 0, 2, 1, '93.000', '0.000', NULL, 'cf740cf752bf4354aa423a527cb0b1df', 1, 1, NULL, NULL, 1, 1, 0, 0, 0, 0, 0, NULL, NULL, NULL),
('15', 15, 15, 'ut', 'ut', NULL, NULL, NULL, NULL, 'Consectetur reprehenderit consequatur.', 'Quidem optio dolores.', 'gusikowski.com', '12f3fc1c67fbf089c4ecd84871f35163', 1, 'uiAiNXR9gxH7LzPB2D4JJGVGAaQaoYE9.jpg', 'lG8AZqroaynHiHYSnMJ-ZZsF_bc0C6KY.jpg', 1, 1, '1-575-891-6081 x467', 0, 'littel.griffin@yahoo.com', '1996-04-11 06:27:09', '2014-01-22 01:05:40', '15', '15', '15', '15', '15', '01f82792ffd5cd13fe352c6c75e2a931', '7a302002ad8006584a2157c3067190f1', NULL, 'et', 'quo', 0, NULL, NULL, 'nisi', NULL, '15', 'Authorized Signature', 'MT59YHMS09684VP0A9E0Z48D458F9B9', 'Arvid', 'Barton', 'dschaden@bernhard.com', '(545) 953-8461 x44985', 91, NULL, NULL, NULL, NULL, 'quidem', 'enim', 1, 'genoveva.marquardt@schneider.com', '54db78728f475a78cbe8a73c0f0ee041', 1, 'Hessel Stravenue', NULL, 1, NULL, NULL, 'assumenda', NULL, NULL, NULL, 195, '73479955c6688384b07bd0b0c7f03396', '930201c7d66242342856e1bc91141587', 1, 'http://heathcote.com/autem-doloribus-tenetur-aut-et-quidem-alias-quidem.html', 1, 1, 'f69d95e180c164b5dcc28cfb9cef0e95', '69c529904e82e8762e9a77fff5507b03', '4751fe8c914394fd4442b34bbbc0c53e', '15', 'Steuber, Jacobson and Ward', 1, 0, NULL, 1, 380, NULL, 'voluptatum', 'nesciunt', 'la', 0, 2, 1, '70.000', '0.000', NULL, '7e18ccd5baf1448431b60b50a83c19cf', 1, 1, NULL, NULL, 1, 1, 0, 0, 0, 0, 0, NULL, NULL, NULL),
('16', 16, 16, 'voluptatem', 'nobis', NULL, NULL, NULL, NULL, 'Quasi architecto asperiores voluptas eius.', 'Accusamus dignissimos voluptas possimus occaecati.', 'mcclure.com', '05af69192a7f7410220be6dc38749e04', 1, 'uiAiNXR9gxH7LzPB2D4JJGVGAaQaoYE9.jpg', 'lG8AZqroaynHiHYSnMJ-ZZsF_bc0C6KY.jpg', 1, 1, '+1-665-277-8874', 0, 'zcarter@hotmail.com', '1998-05-22 08:55:43', '2002-02-05 04:13:01', '16', '16', '16', '16', '16', 'aab8abe15724c369a45ebfd2109fb354', 'db12c3f9c2897e6f02a4d9db67d10159', NULL, 'officiis', 'non', 0, NULL, NULL, 'assumenda', NULL, '16', 'Authorized Signature', 'TR115384840Y1915E34D5E7CA0', 'Jennings', 'Lueilwitz', 'bjerde@yahoo.com', '+1-964-662-0179', 91, NULL, NULL, NULL, NULL, 'ea', 'aut', 1, 'cruz43@heidenreich.biz', '996e31796e999d44e2dfd828bb28288b', 1, 'Pansy Vista', NULL, 1, NULL, NULL, 'eaque', NULL, NULL, NULL, 404, '52e8628a1a65a7436c18c431f1f3ebb5', 'e516ae05a238264a1c6bc759ac169f1e', 1, 'http://www.trantow.net/nesciunt-et-perspiciatis-doloribus-repellendus-perferendis-reprehenderit-laudantium', 1, 1, 'ba94c54bc00e22f3f26d7fb211b7c50a', 'fcb917e67bbd427d1a2fc40d904d86ea', '571286b127db42dae4803e2cab12f7da', '16', 'Kunde, Rutherford and Hegmann', 1, 0, NULL, 1, 715, NULL, 'enim', 'est', 'af', 0, 2, 1, '26.000', '0.000', NULL, 'b18ea5ab9ce3e1d6fa517149779e9cd1', 1, 1, NULL, NULL, 1, 1, 0, 0, 0, 0, 0, NULL, NULL, NULL),
('17', 17, 17, 'et', 'quos', NULL, NULL, NULL, NULL, 'Dolorum minima id.', 'Ea in eaque quidem laudantium.', 'armstrong.com', 'b91a2dd019b4a2131436d598cd2d1fdb', 1, 'uiAiNXR9gxH7LzPB2D4JJGVGAaQaoYE9.jpg', 'lG8AZqroaynHiHYSnMJ-ZZsF_bc0C6KY.jpg', 1, 1, '(584) 839-2759 x114', 0, 'bernie.kris@gmail.com', '1992-03-21 15:49:10', '1997-05-04 21:46:16', '17', '17', '17', '17', '17', '6293c26d39b60a92a118941809ce1ffb', 'fd5cdb28c4cf782e034714c5e00b204d', NULL, 'facere', 'nesciunt', 0, NULL, NULL, 'earum', NULL, '17', 'Authorized Signature', 'BE54646303405385', 'Stacey', 'Bosco', 'xebert@hotmail.com', '1-432-353-9396', 91, NULL, NULL, NULL, NULL, 'facilis', 'dolore', 1, 'ghermann@metz.com', '8a7d3091be9c957383d7f5af361ded0c', 1, 'Brown Cliffs', NULL, 1, NULL, NULL, 'illo', NULL, NULL, NULL, 384, 'c3838f54b67e2ee32aa60cf3e382cc2e', 'bf7d45a9df8d90b7e69bbe3fc6ff826b', 1, 'https://doyle.com/unde-aut-in-ab-ratione-voluptatem.html', 1, 1, '7a324bc9425b28efe1d9a11319e6af87', 'a3eafd6de731ec1f4bad0930f9001072', '002595c2e0f67a668ea3c721b557de0f', '17', 'Powlowski LLC', 1, 0, NULL, 1, 350, NULL, 'molestiae', 'atque', 'kj', 0, 2, 1, '77.000', '0.000', NULL, 'b2b7766189497735fc815af471d324d4', 1, 1, NULL, NULL, 1, 1, 0, 0, 0, 0, 0, NULL, NULL, NULL),
('18', 18, 18, 'est', 'totam', NULL, NULL, NULL, NULL, 'Dolor architecto temporibus.', 'Sit omnis ut assumenda.', 'hane.com', '181a88cac9708a5be05297f56ce5018d', 1, 'uiAiNXR9gxH7LzPB2D4JJGVGAaQaoYE9.jpg', 'lG8AZqroaynHiHYSnMJ-ZZsF_bc0C6KY.jpg', 1, 1, '675.364.2521', 0, 'rheaney@yahoo.com', '1994-08-23 21:27:13', '2016-11-10 17:01:03', '18', '18', '18', '18', '18', '82fc11bf48a5f046e4e801e532bb19c9', '6a4cf5e27cd43e45499fd5394b573b9f', NULL, 'eos', 'autem', 0, NULL, NULL, 'quibusdam', NULL, '18', 'Authorized Signature', 'DK4737662565152410', 'Antone', 'Bailey', 'lang.carole@gmail.com', '+1.770.284.8738', 91, NULL, NULL, NULL, NULL, 'qui', 'velit', 1, 'torp.dangelo@hotmail.com', 'd78711d5023f5041c929fbf032a32a90', 1, 'Bauch Canyon', NULL, 1, NULL, NULL, 'ea', NULL, NULL, NULL, 245, '1c880b508f0645e675111e0bdf914801', 'ba70fc651825fdb83be20c479d5bf1c7', 1, 'http://lueilwitz.biz/at-accusantium-et-ducimus-saepe-laborum-saepe-dicta', 1, 1, 'c6161a6f11f2ac5d00b03dfa38b6464f', '4b47c6fdcd417c8a3131cec290361b7f', '4f15a25b054a6bae2479bd77245ec706', '18', 'Kshlerin Ltd', 1, 0, NULL, 1, 411, NULL, 'et', 'sed', 'fy', 0, 2, 1, '57.000', '0.000', NULL, '3a4e1dfce8c25ea92d1318ce46c769bc', 1, 1, NULL, NULL, 1, 1, 0, 0, 0, 0, 0, NULL, NULL, NULL),
('19', 19, 19, 'quos', 'voluptas', NULL, NULL, NULL, NULL, 'Ducimus eveniet et.', 'Totam reiciendis aperiam.', 'kuhlman.com', '17b3b531d16a6669db029aa136956b4a', 1, 'uiAiNXR9gxH7LzPB2D4JJGVGAaQaoYE9.jpg', 'lG8AZqroaynHiHYSnMJ-ZZsF_bc0C6KY.jpg', 1, 1, '981.396.8389', 0, 'thiel.laron@hotmail.com', '1975-04-11 03:23:40', '1979-10-15 23:27:55', '19', '19', '19', '19', '19', '3a27463be322c469503b43f4dd152930', 'da7bcdca526569920656f720026200f2', NULL, 'ut', 'sapiente', 0, NULL, NULL, 'quia', NULL, '19', 'Authorized Signature', 'EE443904159499691886', 'Pearl', 'D\'Amore', 'thompson.kassandra@hotmail.com', '+1 (667) 482-5427', 91, NULL, NULL, NULL, NULL, 'voluptas', 'error', 1, 'vilma.stark@yahoo.com', '977a78aeed0974fcd1c6f3957ca2de7e', 1, 'Buddy Land', NULL, 1, NULL, NULL, 'cupiditate', NULL, NULL, NULL, 215, 'b37631f217d1e56a2c76c158bd884474', 'b91f5020490e67a0a76169d816d808f3', 1, 'http://www.schumm.org/', 1, 1, '61bf4ee06cdad6abbe148498a1281b95', '835119f990205ac55ba520dfee4b30e6', '9d7459080172de32b6ac7a087152dd2d', '19', 'Gutmann-Wilderman', 1, 0, NULL, 1, 806, NULL, 'voluptate', 'architecto', 'hi', 0, 2, 1, '48.000', '0.000', NULL, '51b314f3d41f9345146a112aca1e5942', 1, 1, NULL, NULL, 1, 1, 0, 0, 0, 0, 0, NULL, NULL, NULL),
('2', 2, 2, 'perferendis', 'eos', NULL, NULL, NULL, NULL, 'Doloremque et non qui molestias voluptatem.', 'Occaecati natus doloribus est rerum aut.', 'rogahn.biz', 'e129f1e250c973029dc26e3145465f99', 1, 'uiAiNXR9gxH7LzPB2D4JJGVGAaQaoYE9.jpg', 'lG8AZqroaynHiHYSnMJ-ZZsF_bc0C6KY.jpg', 1, 1, '563.860.0378 x257', 0, 'justus.yost@sanford.net', '1996-08-05 10:47:25', '1978-10-26 05:57:33', '2', '2', '2', '2', '2', '9982f90b1854434007c21a8d6bc3f5bf', 'cfc50eca97dfded46f4d192c8e92015a', NULL, 'excepturi', 'consequuntur', 0, NULL, NULL, 'dolor', NULL, '2', 'Authorized Signature', 'TR820166680SQZ2T44X8265Q79', 'Terrance', 'Homenick', 'jkub@effertz.com', '(965) 386-0381 x61126', 91, NULL, NULL, NULL, NULL, 'minus', 'est', 1, 'rashawn24@hotmail.com', '5576789a64ead0ab2f3630d15ddaa5dd', 1, 'Adell Ramp', NULL, 1, NULL, NULL, 'harum', NULL, NULL, NULL, 105, '41605818f6d23dbf810d601abd92f2c4', 'da91387d8eaaef869d5d9b5918e72600', 1, 'http://www.harvey.info/quaerat-et-temporibus-incidunt-debitis-asperiores-et', 1, 1, 'b86546f2c943a86e2f9b468efa517de4', '2dcbcb0e4a6ef79d29dcb9864bd0265d', 'e574c60d97cab24e5ab96d0605dddf4a', '2', 'Kohler-Lesch', 1, 0, NULL, 1, 346, NULL, 'aut', 'laboriosam', 'rn', 0, 2, 1, '88.000', '0.000', NULL, '280acc76795d554153d0153569f9d02a', 1, 1, NULL, NULL, 1, 1, 0, 0, 0, 0, 0, NULL, NULL, NULL),
('20', 20, 20, 'magni', 'non', NULL, NULL, NULL, NULL, 'Ea quo officiis perspiciatis.', 'Consequatur officia harum a quidem quia.', 'hackett.com', '39c17b66eb128322da67226f5205b36d', 1, 'uiAiNXR9gxH7LzPB2D4JJGVGAaQaoYE9.jpg', 'lG8AZqroaynHiHYSnMJ-ZZsF_bc0C6KY.jpg', 1, 1, '393.952.3885', 0, 'morris08@yahoo.com', '2008-06-14 19:52:54', '1976-05-17 14:10:39', '20', '20', '20', '20', '20', '7c581eac07e2f42313affb2c37f60fd8', '78e7a8dccc95e23aa0992e4bf7b6faf8', NULL, 'asperiores', 'excepturi', 0, NULL, NULL, 'sapiente', NULL, '20', 'Authorized Signature', 'TN1837113512098394583096', 'Estel', 'Crist', 'myost@gmail.com', '1-752-850-9659 x9914', 91, NULL, NULL, NULL, NULL, 'nihil', 'saepe', 1, 'tremblay.katelynn@becker.com', '8dc6de5e1d90471043cbb155d9a71fc0', 1, 'Prosacco Points', NULL, 1, NULL, NULL, 'qui', NULL, NULL, NULL, 677, '941d3233c5d4c8469f7df2bbe422ad17', '9f00c58c0eb7f3d322e2ba67431ab088', 1, 'http://waelchi.com/nihil-molestiae-adipisci-provident-quo-incidunt-repellendus-ab', 1, 1, '68d87ce108b2dd0fbaa724c2dbd2eb93', '04439af23dc019dc502c1019db3a6aeb', 'dbd2e7cad740897f71f4bc4b82e32a87', '20', 'Marvin, Stark and Bogan', 1, 0, NULL, 1, 91, NULL, 'illo', 'rerum', 'nl', 0, 2, 1, '65.000', '0.000', NULL, 'd410c6dc3bd7c9142a553c8b51a7ad48', 1, 1, NULL, NULL, 1, 1, 0, 0, 0, 0, 0, NULL, NULL, NULL),
('21', 21, 21, 'mollitia', 'velit', NULL, NULL, NULL, NULL, 'Quaerat asperiores consequuntur maiores quae eligendi.', 'Quia quia ab laboriosam eaque aut.', 'pouros.com', '46f2fb805f08887006c5852c95edc3ab', 1, 'uiAiNXR9gxH7LzPB2D4JJGVGAaQaoYE9.jpg', 'lG8AZqroaynHiHYSnMJ-ZZsF_bc0C6KY.jpg', 1, 1, '+1-340-331-7020', 0, 'reva51@hoppe.org', '1988-01-17 10:55:47', '2003-10-07 08:56:23', '21', '21', '21', '21', '21', '6e283d458ff5ae5775dd0545726b5bb6', '092456f2c850b160872ef8c78e1e4a5e', NULL, 'quo', 'quos', 0, NULL, NULL, 'dolor', NULL, '21', 'Authorized Signature', 'SI10588125849532561', 'Marcella', 'Orn', 'mateo66@emard.com', '+1 (864) 770-2364', 91, NULL, NULL, NULL, NULL, 'fugit', 'sint', 1, 'halle.rowe@hudson.com', '5292a72540c16b5e13529355ac5baac5', 1, 'Haley Trafficway', NULL, 1, NULL, NULL, 'nisi', NULL, NULL, NULL, 972, 'b9d209a29180c88d0db0532b6048b5e8', 'cad39d18b8b01bf60b1d3ad31133f084', 1, 'http://www.erdman.net/mollitia-amet-praesentium-porro-vel-aperiam-nihil', 1, 1, 'ebbeadfe5a77eeaa8e950f97ba89b10e', 'cef741b5b4a91e437d26006822ae67b5', 'e9bf859e4e34aa2072d33223accd748a', '21', 'Kuhlman, Champlin and Cummerata', 1, 0, NULL, 1, 803, NULL, 'totam', 'est', 'rm', 0, 2, 1, '90.000', '0.000', NULL, 'db8db5ac017cf59402acb730516f25f8', 1, 1, NULL, NULL, 1, 1, 0, 0, 0, 0, 0, NULL, NULL, NULL),
('22', 22, 22, 'ullam', 'molestias', NULL, NULL, NULL, NULL, 'Veritatis enim labore ut quam ipsa.', 'Qui temporibus animi et.', 'oconnell.com', '726e5238c5c646185135cfeca4640b7c', 1, 'uiAiNXR9gxH7LzPB2D4JJGVGAaQaoYE9.jpg', 'lG8AZqroaynHiHYSnMJ-ZZsF_bc0C6KY.jpg', 1, 1, '+1-380-915-2908', 0, 'wade.hahn@gmail.com', '1998-12-22 06:50:56', '1972-01-30 09:51:55', '22', '22', '22', '22', '22', 'ba018b79952e3c4d7f53bc69d322a0e0', '7cbaea24ebac9764e038b34bf67da078', NULL, 'sint', 'est', 0, NULL, NULL, 'quae', NULL, '22', 'Authorized Signature', 'MR2831221762135030343081098', 'Rosalia', 'Crist', 'cory59@mills.info', '526-873-3025', 91, NULL, NULL, NULL, NULL, 'quo', 'sit', 1, 'jalon03@okeefe.com', '66d4d49e1e67361abab7e81394c3b06d', 1, 'Beer Track', NULL, 1, NULL, NULL, 'cupiditate', NULL, NULL, NULL, 460, '46161e794f870249dca9a210fda97f38', 'c3061dcc8f58f0caa6629c54bfbeb6c6', 1, 'http://www.corwin.biz/culpa-voluptas-quia-ea', 1, 1, 'f0a089b25a41e59f22fe72a2ba8ab9e5', '35863de1b7b5fe5dce2ae617f6718c99', '925370f8bcb86651cd0cf6a2be67e804', '22', 'Rau-Wiza', 1, 0, NULL, 1, 340, NULL, 'facilis', 'dolores', 'mi', 0, 2, 1, '20.000', '0.000', NULL, '4c0b5f857739fb70b0adff76124b8048', 1, 1, NULL, NULL, 1, 1, 0, 0, 0, 0, 0, NULL, NULL, NULL),
('23', 23, 23, 'quas', 'doloremque', NULL, NULL, NULL, NULL, 'Numquam tenetur consequuntur.', 'Illo molestiae omnis et.', 'yundt.info', '7a803bfa0b866bd6cd77d8636e9a35ed', 1, 'uiAiNXR9gxH7LzPB2D4JJGVGAaQaoYE9.jpg', 'lG8AZqroaynHiHYSnMJ-ZZsF_bc0C6KY.jpg', 1, 1, '953-941-2342 x57833', 0, 'halle.kiehn@yahoo.com', '2006-01-14 09:58:41', '1996-12-22 00:12:19', '23', '23', '23', '23', '23', 'dd34947367285bf8882198e2f83ed159', 'fe36103cf5100d2a320edaa9f18caadf', NULL, 'placeat', 'sed', 0, NULL, NULL, 'pariatur', NULL, '23', 'Authorized Signature', 'DE64557165551503500249', 'Elizabeth', 'Bauch', 'mmcclure@yahoo.com', '809-312-7719 x15193', 91, NULL, NULL, NULL, NULL, 'sint', 'et', 1, 'hailee.ortiz@maggio.com', 'a4bdb6e5943cb0fd3f818d630ecab644', 1, 'Dennis Expressway', NULL, 1, NULL, NULL, 'corporis', NULL, NULL, NULL, 784, 'b10330f3b772134753f5bf49b80b288f', '2f29976e6458b81068906d2dce1ab87c', 1, 'http://www.spinka.com/ea-ratione-illo-voluptates-hic.html', 1, 1, '23926b4bd1403dd8bf113ce8471631f8', 'f68752419051b8fc1deb66672dbdfeb8', '926b847374a93e3bf5d9bc850a8ab36c', '23', 'Farrell, Kessler and Hoeger', 1, 0, NULL, 1, 413, NULL, 'repudiandae', 'nam', 'ee', 0, 2, 1, '67.000', '0.000', NULL, '4689d60698608e3b6c468ef62db4da92', 1, 1, NULL, NULL, 1, 1, 0, 0, 0, 0, 0, NULL, NULL, NULL),
('24', 24, 24, 'architecto', 'deserunt', NULL, NULL, NULL, NULL, 'Quasi quibusdam sint aut expedita.', 'Facilis sapiente quo.', 'mclaughlin.info', '8663b9a8dbbeb40550476be8de4dfc10', 1, 'uiAiNXR9gxH7LzPB2D4JJGVGAaQaoYE9.jpg', 'lG8AZqroaynHiHYSnMJ-ZZsF_bc0C6KY.jpg', 1, 1, '(287) 400-2465 x8265', 0, 'hane.wilma@satterfield.com', '2019-03-10 23:24:54', '1986-08-13 08:37:25', '24', '24', '24', '24', '24', '7be15ed72c5978857014e7eaf432c1d4', '87f94c2ae5ce6d718a511e281780d018', NULL, 'et', 'atque', 0, NULL, NULL, 'consectetur', NULL, '24', 'Authorized Signature', 'FI7305223622894310', 'Lexi', 'Schimmel', 'haley.orion@yahoo.com', '540.945.1746', 91, NULL, NULL, NULL, NULL, 'tempora', 'et', 1, 'nienow.dorcas@pagac.com', 'b30a077f6fe9003355d78987969aaa3d', 1, 'Heaney Valley', NULL, 1, NULL, NULL, 'cumque', NULL, NULL, NULL, 333, '48e9b09693fd77a91e7518a28a6cb8e2', '0af36b4eece91fb7b52038d05f25126b', 1, 'http://www.paucek.com/ipsum-non-qui-dignissimos-reprehenderit-hic', 1, 1, '8840626d25b18273d01acb931ab8f8aa', 'dd388b4d41da236314f5699918c67faf', 'bb2a7fef6bc9c9f7fba6b0556ce7bc36', '24', 'Schroeder Ltd', 1, 0, NULL, 1, 94, NULL, 'facilis', 'autem', 'gd', 0, 2, 1, '91.000', '0.000', NULL, '6dd16b429728764f758a90af6705be11', 1, 1, NULL, NULL, 1, 1, 0, 0, 0, 0, 0, NULL, NULL, NULL),
('25', 25, 25, 'sed', 'ut', NULL, NULL, NULL, NULL, 'Ab dolore rem deserunt.', 'Voluptas aut nisi consequuntur accusamus quod.', 'kulas.com', '7335d060a3b927d94d07564c678c4621', 1, 'uiAiNXR9gxH7LzPB2D4JJGVGAaQaoYE9.jpg', 'lG8AZqroaynHiHYSnMJ-ZZsF_bc0C6KY.jpg', 1, 1, '(772) 263-1331', 0, 'domenic.berge@hotmail.com', '1984-02-04 19:21:10', '2007-05-31 05:39:21', '25', '25', '25', '25', '25', 'feb4390cba346cd9d51a9d5748d7797b', '4a8457f8502e09f051b28ca3688f13cf', NULL, 'quaerat', 'illum', 0, NULL, NULL, 'sed', NULL, '25', 'Authorized Signature', 'TN6228512210571394515668', 'Noah', 'Hettinger', 'adolf.quitzon@stark.biz', '505.517.9907', 91, NULL, NULL, NULL, NULL, 'aut', 'itaque', 1, 'wehner.nikita@block.com', '3e20bf2919d5edce7d56a846d8452534', 1, 'Alejandra Forks', NULL, 1, NULL, NULL, 'a', NULL, NULL, NULL, 878, 'da94b1d07c186b26a7b28a54aa2a45d3', '10431be5edbb7c7650ec35247fb7bd7a', 1, 'http://jaskolski.com/rerum-necessitatibus-non-occaecati-ratione', 1, 1, 'f666fb242afebc4b9b0e91dd80b87840', '4d8412f488b41622ffa6a6809ca9bf6e', 'ba97e595e5034d74468322248d501108', '25', 'Raynor-Schaden', 1, 0, NULL, 1, 737, NULL, 'consequuntur', 'qui', 'sq', 0, 2, 1, '16.000', '0.000', NULL, '2ba2d766f65fd08e4a18e40f4e1f49cf', 1, 1, NULL, NULL, 1, 1, 0, 0, 0, 0, 0, NULL, NULL, NULL),
('26', 26, 26, 'et', 'alias', NULL, NULL, NULL, NULL, 'Aut aspernatur quo et culpa ut.', 'Enim esse sed.', 'stokes.com', '396d710d456371dacdf75ab2a922bc0b', 1, 'uiAiNXR9gxH7LzPB2D4JJGVGAaQaoYE9.jpg', 'lG8AZqroaynHiHYSnMJ-ZZsF_bc0C6KY.jpg', 1, 1, '254-606-3358', 0, 'hilpert.chaya@streich.net', '2011-01-28 22:01:56', '2000-09-16 12:21:23', '26', '26', '26', '26', '26', 'f3c3385902373d3dd36f00d29a3f20ac', '17231616e99522074b339a3b58b529f2', NULL, 'quaerat', 'beatae', 0, NULL, NULL, 'vel', NULL, '26', 'Authorized Signature', 'IS491794295322829919663304', 'Aron', 'Romaguera', 'yohara@hotmail.com', '(324) 876-0694 x652', 91, NULL, NULL, NULL, NULL, 'dolores', 'aut', 1, 'jaime.mclaughlin@yahoo.com', '5d8c1b6c18f6ed36053f43748e9cdefd', 1, 'Keeling Valley', NULL, 1, NULL, NULL, 'a', NULL, NULL, NULL, 636, '82684c000437a92402d1fc681d56e1a3', '8042d648924a290c2399bab90caefbb8', 1, 'https://www.erdman.com/facilis-nemo-ut-quod-quia-molestiae-ad-blanditiis', 1, 1, '911d3023248e73afc62d708a180364bb', '07a7b2803d90739c1ad03cac25b19357', 'e5232c7e4e87f9b070f9f484489fa901', '26', 'Reichel-Koch', 1, 0, NULL, 1, 642, NULL, 'facilis', 'modi', 'tg', 0, 2, 1, '70.000', '0.000', NULL, '13af5d0a9331559e06342aa7c98bb3fa', 1, 1, NULL, NULL, 1, 1, 0, 0, 0, 0, 0, NULL, NULL, NULL),
('27', 27, 27, 'veritatis', 'et', NULL, NULL, NULL, NULL, 'Ad ullam mollitia magnam.', 'Eius molestiae sed iusto fuga veniam.', 'balistreri.com', '2b765b3b4174a9d4115087bbd5be4b55', 1, 'uiAiNXR9gxH7LzPB2D4JJGVGAaQaoYE9.jpg', 'lG8AZqroaynHiHYSnMJ-ZZsF_bc0C6KY.jpg', 1, 1, '(569) 329-3923 x813', 0, 'francisco17@gmail.com', '1983-11-10 06:16:17', '2009-06-11 21:17:11', '27', '27', '27', '27', '27', '43844d61b56e6f5734bce5c7b720b0e9', '5b303e6d303e2ee8e7d4a1358e22d848', NULL, 'voluptas', 'aut', 0, NULL, NULL, 'ullam', NULL, '27', 'Authorized Signature', 'RO32ZWQS6LQG9L7A7LJ62I7N', 'Brent', 'Stoltenberg', 'reed.leannon@hotmail.com', '(585) 298-7954', 91, NULL, NULL, NULL, NULL, 'amet', 'unde', 1, 'tressa.hudson@damore.com', '7ab02c7eed23ae43b3b141a666de27ac', 1, 'Anne Valley', NULL, 1, NULL, NULL, 'quibusdam', NULL, NULL, NULL, 686, '7678f7fc25fa57ccf342b9c42e474c72', 'd51db65d4d936a211aeea192a4155658', 1, 'https://www.harris.biz/quibusdam-non-temporibus-eveniet-harum-unde-ad-dolore', 1, 1, '61de0c00a684a7f93039bf53fd3d0edf', '1cf352b92f25441a3930e307761e7dee', '365d86a1be3203e5baad350f943e836c', '27', 'Hilpert, Emmerich and Fahey', 1, 0, NULL, 1, 799, NULL, 'quo', 'commodi', 'lt', 0, 2, 1, '37.000', '0.000', NULL, '4322c3560cd2afe039ff2cd7c1d0ed76', 1, 1, NULL, NULL, 1, 1, 0, 0, 0, 0, 0, NULL, NULL, NULL),
('28', 28, 28, 'quia', 'quidem', NULL, NULL, NULL, NULL, 'Dolor aut quia.', 'Et rerum ut nihil at.', 'becker.com', '0aa29bee3657e69e03f1b44a519bafd3', 1, 'uiAiNXR9gxH7LzPB2D4JJGVGAaQaoYE9.jpg', 'lG8AZqroaynHiHYSnMJ-ZZsF_bc0C6KY.jpg', 1, 1, '407-939-8468', 0, 'denesik.abigail@howe.com', '1982-03-12 19:01:44', '1982-09-08 02:26:41', '28', '28', '28', '28', '28', '8983e228bb46d224456f91c21a9dedc8', '54144d37e96ba9162b855c000cc350cc', NULL, 'ut', 'et', 0, NULL, NULL, 'aut', NULL, '28', 'Authorized Signature', 'CH19103402O3XXWH13F8D', 'Pietro', 'Sawayn', 'christelle18@hotmail.com', '(261) 277-8140 x4783', 91, NULL, NULL, NULL, NULL, 'soluta', 'voluptates', 1, 'wgoyette@gmail.com', '1e697df58bf39afe325153a7c99ebaac', 1, 'King Alley', NULL, 1, NULL, NULL, 'est', NULL, NULL, NULL, 121, '85a7d0da5c587a621cd757f46df33f97', 'a3b7a12aad98a56aac8e14902dcd4a08', 1, 'http://www.klein.com/dolorem-iusto-similique-totam-cupiditate', 1, 1, '59adf85c840c9836a0af9edecb749572', 'e88d54a89e773206f25f0e5f679786f4', 'cea3a1593b900a1235f2869f068c66f6', '28', 'Gutkowski-Oberbrunner', 1, 0, NULL, 1, 109, NULL, 'exercitationem', 'repellat', 'rm', 0, 2, 1, '60.000', '0.000', NULL, '264ffabb20cc82723c90ce28cc617124', 1, 1, NULL, NULL, 1, 1, 0, 0, 0, 0, 0, NULL, NULL, NULL),
('29', 29, 29, 'velit', 'quia', NULL, NULL, NULL, NULL, 'Odio libero et quibusdam repellat.', 'Nemo inventore doloremque aut aut rem.', 'mayert.org', '055a92624859ae05a96d276b5fd86a74', 1, 'uiAiNXR9gxH7LzPB2D4JJGVGAaQaoYE9.jpg', 'lG8AZqroaynHiHYSnMJ-ZZsF_bc0C6KY.jpg', 1, 1, '450.640.4488 x206', 0, 'bherman@bauch.org', '1984-04-01 23:09:48', '2003-12-30 22:13:14', '29', '29', '29', '29', '29', '2b64a22f3025ba385ccc83b6c583ba72', '2b421f7ec7825b4e4aab62266969a1f3', NULL, 'officiis', 'fugiat', 0, NULL, NULL, 'necessitatibus', NULL, '29', 'Authorized Signature', 'SA4419XZ157J2J11211E413U', 'Christy', 'Kozey', 'stark.cathy@gmail.com', '346-941-9516 x96643', 91, NULL, NULL, NULL, NULL, 'iure', 'error', 1, 'martine93@konopelski.biz', '24aeeaaf5448d671c6c11ea5956cba2f', 1, 'Mann Corner', NULL, 1, NULL, NULL, 'sunt', NULL, NULL, NULL, 308, '3cb6df80aeae58342b03c0964052a457', '4620a9af4f4a4297f08b5667aa718bf7', 1, 'http://okon.com/', 1, 1, '730117b70205a9fb469c4a716e715fc3', '544fcb831282d8241fd1697045b3128d', 'e1d0d1fb9664371b82bff6090fc34edb', '29', 'Kassulke Ltd', 1, 0, NULL, 1, 813, NULL, 'corporis', 'placeat', 'xh', 0, 2, 1, '19.000', '0.000', NULL, '0ecdb9c8d22492a2c065eb9198de6251', 1, 1, NULL, NULL, 1, 1, 0, 0, 0, 0, 0, NULL, NULL, NULL),
('3', 3, 3, 'aliquam', 'rerum', NULL, NULL, NULL, NULL, 'Cum ullam adipisci.', 'Vero ratione consequatur aut exercitationem.', 'weissnat.org', '9034fc65442fe9627333e24fb6d47ec3', 1, 'uiAiNXR9gxH7LzPB2D4JJGVGAaQaoYE9.jpg', 'lG8AZqroaynHiHYSnMJ-ZZsF_bc0C6KY.jpg', 1, 1, '(256) 219-4103 x02805', 0, 'eloisa.crona@hotmail.com', '1996-09-30 23:39:41', '2012-08-17 19:00:52', '3', '3', '3', '3', '3', '0c9377e3a5b31265392b9a39c864afa3', 'c69e0bfff58fd7ea7d4126e7086a7d0a', NULL, 'ipsa', 'aliquid', 0, NULL, NULL, 'molestiae', NULL, '3', 'Authorized Signature', 'RS02858437501917148408', 'Maci', 'Schaden', 'albin49@bernier.com', '(280) 535-2742', 91, NULL, NULL, NULL, NULL, 'optio', 'quidem', 1, 'rodriguez.haven@ortiz.net', 'e0965d8bb6a6a0f68d86e4c848b395c5', 1, 'Mohr Dale', NULL, 1, NULL, NULL, 'tenetur', NULL, NULL, NULL, 453, '446e89692ea4a7f1f3a21e4767db9915', '0bfe68d48f53d4ab42ce4c774097977f', 1, 'http://www.conn.com/nulla-qui-minima-assumenda-aperiam-libero-alias', 1, 1, 'a131aea76263202d3cbac8fb9fab4576', '949aa952c3dc70c94ec914ace189effd', '0d05d1f2b0851022ee010a0ac7eb4eb3', '3', 'Tromp, Friesen and Reilly', 1, 0, NULL, 1, 800, NULL, 'id', 'corrupti', 'sm', 0, 2, 1, '73.000', '0.000', NULL, 'a27563d9352e24ef7d26c5976dd8a003', 1, 1, NULL, NULL, 1, 1, 0, 0, 0, 0, 0, NULL, NULL, NULL),
('30', 30, 30, 'laudantium', 'voluptatem', NULL, NULL, NULL, NULL, 'Eos dolor officia et.', 'Possimus possimus ut iusto deserunt vel.', 'bergstrom.biz', '49e2659a948fbe1d47aa04bdd1df513c', 1, 'uiAiNXR9gxH7LzPB2D4JJGVGAaQaoYE9.jpg', 'lG8AZqroaynHiHYSnMJ-ZZsF_bc0C6KY.jpg', 1, 1, '1-392-736-3678 x90372', 0, 'abdul.auer@yahoo.com', '1995-06-21 18:20:43', '2010-04-01 11:26:02', '30', '30', '30', '30', '30', '60abcc97895a80fd2fde3245538b28ed', '444c939f5479597ccb05788df4f66d0b', NULL, 'odio', 'hic', 0, NULL, NULL, 'et', NULL, '30', 'Authorized Signature', 'PK15VOQZF35T49XL8U06JL2G', 'Morgan', 'Schinner', 'walker.raymundo@willms.info', '1-815-423-8802', 91, NULL, NULL, NULL, NULL, 'illo', 'quod', 1, 'jovan.pagac@ortiz.info', '71a99e7011bf5cf47b1cfb2f86386cd5', 1, 'Pagac Orchard', NULL, 1, NULL, NULL, 'dolorem', NULL, NULL, NULL, 266, '3d93372d70172327e90be2f1cc0be5cb', '398d5e86ab9fdb7d923585bb1ed6a149', 1, 'http://mayer.com/molestiae-maxime-optio-tempora-eum-rerum-eos-magni', 1, 1, 'b5297e4b2fa6bccaf0b66427645ba1c2', '1c29cf775848b40863c9cce315b8f90f', 'f0eb348c6426db74669eb8b413de07a8', '30', 'Considine Inc', 1, 0, NULL, 1, 510, NULL, 'sed', 'quaerat', 'mr', 0, 2, 1, '81.000', '0.000', NULL, '21ea63df6b724da0b2f95913fe790cf8', 1, 1, NULL, NULL, 1, 1, 0, 0, 0, 0, 0, NULL, NULL, NULL),
('31', 31, 31, 'dolores', 'reiciendis', NULL, NULL, NULL, NULL, 'Nesciunt aut consequuntur ut dolorem.', 'Dolor commodi saepe quisquam minima.', 'grady.biz', '51a620e5d08b5d268263dfb1391ebfd8', 1, 'uiAiNXR9gxH7LzPB2D4JJGVGAaQaoYE9.jpg', 'lG8AZqroaynHiHYSnMJ-ZZsF_bc0C6KY.jpg', 1, 1, '785.341.0777 x14844', 0, 'rick.bayer@aufderhar.net', '1990-06-12 04:09:09', '1985-01-01 15:18:09', '31', '31', '31', '31', '31', 'e201c0419573c371e9687c5668e5e7af', '5846295a9f58e64c1a98316a71b31cc5', NULL, 'corporis', 'in', 0, NULL, NULL, 'molestiae', NULL, '31', 'Authorized Signature', 'BH89TJCII2S3SYFBFO00TN', 'Cicero', 'Marvin', 'brennon.barrows@yahoo.com', '992.727.6842 x0288', 91, NULL, NULL, NULL, NULL, 'in', 'pariatur', 1, 'heidenreich.lacey@schiller.biz', 'f72b93d01d2232cd2a993a49122a22d2', 1, 'Carissa Island', NULL, 1, NULL, NULL, 'maiores', NULL, NULL, NULL, 940, '19923c2e3418fec15e558b108ca92389', '9f0319f9b6dbc6d4b6e3167f3cabdf2b', 1, 'http://weissnat.com/dolores-dolorem-dolor-veniam-velit-placeat-quo', 1, 1, '5bf55c084098303604308518df00d1c6', '394d35560392983fc704b547b5cc8d4f', '538b618cf999262ff5e5ba7443978dd2', '31', 'Jacobi Group', 1, 0, NULL, 1, 593, NULL, 'cupiditate', 'veniam', 'ss', 0, 2, 1, '33.000', '0.000', NULL, '9a722e789d6af6799ce27a929e1f7a93', 1, 1, NULL, NULL, 1, 1, 0, 0, 0, 0, 0, NULL, NULL, NULL),
('32', 32, 32, 'ut', 'quos', NULL, NULL, NULL, NULL, 'Officia aspernatur est.', 'Dolores aut voluptatem.', 'rodriguez.com', '21e71a58a0ab9274036f03484479925a', 1, 'uiAiNXR9gxH7LzPB2D4JJGVGAaQaoYE9.jpg', 'lG8AZqroaynHiHYSnMJ-ZZsF_bc0C6KY.jpg', 1, 1, '+18849901822', 0, 'xpagac@gmail.com', '2001-08-18 00:33:12', '1973-05-27 11:34:15', '32', '32', '32', '32', '32', '6865390a445c666ca6c448098af465c2', 'ea9759ad3da97e5accaa063871459a3f', NULL, 'dolores', 'dolor', 0, NULL, NULL, 'dolorem', NULL, '32', 'Authorized Signature', 'DO46760L30547227644223039459', 'Dagmar', 'Crist', 'tborer@spencer.com', '+1 (565) 559-9912', 91, NULL, NULL, NULL, NULL, 'sunt', 'rerum', 1, 'gaylord.chase@heaney.com', '1b1fb4bec6d5ecfd467f5e23052b5fc1', 1, 'Oswaldo Shoals', NULL, 1, NULL, NULL, 'perspiciatis', NULL, NULL, NULL, 838, '95d23da7bacffb92b28350bfe7828e1f', 'dbbca85ccc3b7528cab112c13e08a44b', 1, 'http://www.frami.biz/', 1, 1, '3cf80855642630db185e7ea8d16415f5', 'd5dfb566ec3a70f4fb43bdaf0e65ff5e', 'de225c5404fd702c053d81b544109f51', '32', 'Schuppe-Veum', 1, 0, NULL, 1, 408, NULL, 'explicabo', 'qui', 'om', 0, 2, 1, '89.000', '0.000', NULL, '4290d8f91c43c6c21745c8d3a99eb00b', 1, 1, NULL, NULL, 1, 1, 0, 0, 0, 0, 0, NULL, NULL, NULL),
('33', 33, 33, 'sit', 'minus', NULL, NULL, NULL, NULL, 'Consequatur omnis tempore soluta debitis sunt.', 'Voluptatem officia omnis earum qui asperiores.', 'spinka.com', 'b73e68ddac793b260a0e41688526c718', 1, 'uiAiNXR9gxH7LzPB2D4JJGVGAaQaoYE9.jpg', 'lG8AZqroaynHiHYSnMJ-ZZsF_bc0C6KY.jpg', 1, 1, '+1 (821) 616-5373', 0, 'uklocko@gmail.com', '1989-07-22 01:53:19', '2003-07-13 05:13:47', '33', '33', '33', '33', '33', '0ba1f28322d4dde7e397c2076ed95799', 'cf7fd6ccffe0e7499e3258ea1d71df74', NULL, 'labore', 'a', 0, NULL, NULL, 'veniam', NULL, '33', 'Authorized Signature', 'AL232401551346M46O3A052106N6', 'Haylee', 'Douglas', 'allene88@hotmail.com', '+1-397-532-7998', 91, NULL, NULL, NULL, NULL, 'eius', 'minus', 1, 'pauline89@dubuque.com', 'a28ade14b00fbb5e7edd928367a2f058', 1, 'Chyna Track', NULL, 1, NULL, NULL, 'quae', NULL, NULL, NULL, 681, 'b9340650dc528d5c915515c152f42f7d', 'a23b9663d97c4a2c50ec4aa99bf6e818', 1, 'http://www.lynch.com/nulla-ut-velit-aliquid-eos-doloremque-aliquid', 1, 1, 'fb4a979e393712250d9a877f487896ee', '8c48f78dacd164570a5745e7133ecbdc', '191657578e132de02fff28d460610b96', '33', 'Cremin, Stanton and Dare', 1, 0, NULL, 1, 463, NULL, 'assumenda', 'qui', 'lt', 0, 2, 1, '23.000', '0.000', NULL, '6d639a5ad4b573850b9f80f094c4feef', 1, 1, NULL, NULL, 1, 1, 0, 0, 0, 0, 0, NULL, NULL, NULL),
('34', 34, 34, 'aliquid', 'in', NULL, NULL, NULL, NULL, 'Nesciunt veritatis et dolor.', 'Molestiae aliquid iste culpa.', 'mraz.com', '7434b1c04bc9f27f9ffea1a41a385d1b', 1, 'uiAiNXR9gxH7LzPB2D4JJGVGAaQaoYE9.jpg', 'lG8AZqroaynHiHYSnMJ-ZZsF_bc0C6KY.jpg', 1, 1, '(821) 514-9970', 0, 'miller.osbaldo@hotmail.com', '1997-05-14 00:40:53', '1990-08-22 16:13:55', '34', '34', '34', '34', '34', '1574e5c1eb14a7655f69c5d7ec8da9f3', 'c44ff242349f2f7164b3b5001f6d2c8b', NULL, 'vel', 'tempore', 0, NULL, NULL, 'dolorem', NULL, '34', 'Authorized Signature', 'PL75708475332880379743404244', 'Daphney', 'Watsica', 'hilton.waelchi@gmail.com', '(643) 978-3791', 91, NULL, NULL, NULL, NULL, 'quod', 'at', 1, 'domingo.upton@morar.com', '03ccd3d3b0f3384c1f492405f5aa730a', 1, 'Omari Well', NULL, 1, NULL, NULL, 'dolores', NULL, NULL, NULL, 595, 'e491df5f08aa1a8550e132cb5fa7fe9a', '014d6bc82d6295da1adb68b80ae5d3e7', 1, 'http://www.funk.com/est-magni-fugiat-labore-pariatur-distinctio-porro-neque', 1, 1, 'd7301b6fadac9039f7ab1a389975a87b', '065584f194defc9498e2991750c14c11', '7ee08cd9565522de4efe94f79850e3b8', '34', 'Windler, Kuphal and Goldner', 1, 0, NULL, 1, 59, NULL, 'accusamus', 'qui', 'be', 0, 2, 1, '57.000', '0.000', NULL, '9ee1c04acf289d5b9a7af12011770fec', 1, 1, NULL, NULL, 1, 1, 0, 0, 0, 0, 0, NULL, NULL, NULL),
('35', 35, 35, 'doloribus', 'pariatur', NULL, NULL, NULL, NULL, 'Ut consequatur dolorem natus.', 'Accusantium et et nihil et consequatur.', 'bednar.com', 'c773721932446d6e193d2e6ded6f50c5', 1, 'uiAiNXR9gxH7LzPB2D4JJGVGAaQaoYE9.jpg', 'lG8AZqroaynHiHYSnMJ-ZZsF_bc0C6KY.jpg', 1, 1, '917-623-3668 x1164', 0, 'velma89@kuhic.com', '1979-07-23 11:06:17', '2001-01-25 10:45:07', '35', '35', '35', '35', '35', '09d1b7c356b3e4b9955ac6a68063cc0b', '752aab0a1f86d82f5ec49521efc760cb', NULL, 'molestiae', 'fuga', 0, NULL, NULL, 'autem', NULL, '35', 'Authorized Signature', 'BH45OGEDN21W9Q565J5876', 'Roger', 'Quigley', 'kuhlman.darrick@yahoo.com', '991.352.6342 x06717', 91, NULL, NULL, NULL, NULL, 'molestias', 'expedita', 1, 'ilang@pfannerstill.org', '5bd7b9a16755210a43cf564596069aa1', 1, 'Mayert Lodge', NULL, 1, NULL, NULL, 'sit', NULL, NULL, NULL, 210, '7619e255a54a099bb9aa0e29db794f18', '484ef5e75e7b43f732ee16d16ac2e94c', 1, 'http://klein.com/quae-rerum-neque-autem-animi', 1, 1, 'a7de08a8f9439786852ed8140fd1f276', '39feae4c8694886d04dd4476f4ce173b', '920a7b2f933ea431bdb6caa1d94ab3ba', '35', 'Murphy-Konopelski', 1, 0, NULL, 1, 6, NULL, 'possimus', 'sit', 'ja', 0, 2, 1, '62.000', '0.000', NULL, '70c76685eee9e3a96df4d1fef800572e', 1, 1, NULL, NULL, 1, 1, 0, 0, 0, 0, 0, NULL, NULL, NULL),
('36', 36, 36, 'itaque', 'eius', NULL, NULL, NULL, NULL, 'Itaque quam at.', 'Dolorem quaerat iure debitis odio sunt.', 'koelpin.com', 'b1a5340aa699fe0be9b743842705446c', 1, 'uiAiNXR9gxH7LzPB2D4JJGVGAaQaoYE9.jpg', 'lG8AZqroaynHiHYSnMJ-ZZsF_bc0C6KY.jpg', 1, 1, '1-731-231-4390 x97855', 0, 'mcclure.lambert@emmerich.net', '2001-12-28 00:50:39', '2012-07-02 13:12:29', '36', '36', '36', '36', '36', 'df170abe198019c652962197e6f201cc', 'c980907ea63f9454ed5a0426fb588f0e', NULL, 'perferendis', 'inventore', 0, NULL, NULL, 'voluptatem', NULL, '36', 'Authorized Signature', 'AL224331119103D6PG4O425H15NB', 'Lura', 'Kshlerin', 'odell69@hotmail.com', '846.883.0777 x902', 91, NULL, NULL, NULL, NULL, 'asperiores', 'ratione', 1, 'snader@kozey.info', '1c7b44c3d5ca42cc15b3067466793ca3', 1, 'Wisozk Motorway', NULL, 1, NULL, NULL, 'aliquid', NULL, NULL, NULL, 604, '12fda95beb4ea4f9d5ffa05abd6671e8', 'abfe1e7612d71cb29c2ca56b176fd7f6', 1, 'http://www.morar.net/natus-eius-facere-ipsum-ut-fuga.html', 1, 1, 'b678f5df47fa5f7c56686d2fbe2a9382', '3cded47fe25a5ccf776382fd1d1a3377', '4fc77d8e7a4fd4b055d27c2ad38b356d', '36', 'Runolfsdottir-Ferry', 1, 0, NULL, 1, 927, NULL, 'odio', 'soluta', 'ho', 0, 2, 1, '13.000', '0.000', NULL, '346711a641457e3cd4bdb31c9d1e9bbb', 1, 1, NULL, NULL, 1, 1, 0, 0, 0, 0, 0, NULL, NULL, NULL),
('37', 37, 37, 'officia', 'totam', NULL, NULL, NULL, NULL, 'Aliquam odit rem sed est.', 'Ipsam et et dicta ipsa.', 'kihn.com', '84db205e46d932847b160ca5f515714d', 1, 'uiAiNXR9gxH7LzPB2D4JJGVGAaQaoYE9.jpg', 'lG8AZqroaynHiHYSnMJ-ZZsF_bc0C6KY.jpg', 1, 1, '997.422.3096 x718', 0, 'tkeebler@oberbrunner.com', '1993-09-03 12:43:51', '1983-02-07 04:31:09', '37', '37', '37', '37', '37', '0716b2583c25fc1e570987ab33785f1b', '7245a48ca6709d94bb6cfc3757f1e6fd', NULL, 'nisi', 'blanditiis', 0, NULL, NULL, 'exercitationem', NULL, '37', 'Authorized Signature', 'IE96GDWY44405544260683', 'Kenna', 'Ledner', 'boyle.gaylord@gmail.com', '(630) 471-5549', 91, NULL, NULL, NULL, NULL, 'ad', 'suscipit', 1, 'mbecker@hotmail.com', '20a695e949212867e699e00b2750fa8d', 1, 'Pagac Lodge', NULL, 1, NULL, NULL, 'quis', NULL, NULL, NULL, 783, 'cf9edb11b23f33786533dc2459cd5509', '458f3602c2b08e2d555f61a8b965557c', 1, 'http://www.crona.com/aut-repudiandae-modi-porro-et-amet-eligendi-ea-aut', 1, 1, '5edc618c24a9790cd9640baadbba9bcd', 'dc9afc72eb9b3ae3cd3961d0338e43c2', '4af473af9dc619dbe3ad1b63665f1a84', '37', 'Greenfelder Inc', 1, 0, NULL, 1, 949, NULL, 'magni', 'in', 'mi', 0, 2, 1, '71.000', '0.000', NULL, '6e8891501f061574e701f47ecd47146b', 1, 1, NULL, NULL, 1, 1, 0, 0, 0, 0, 0, NULL, NULL, NULL),
('38', 38, 38, 'praesentium', 'voluptas', NULL, NULL, NULL, NULL, 'Sed et voluptate est expedita.', 'Voluptates incidunt voluptates maiores sed qui.', 'sawayn.info', 'b765f9cca9a7ad612d4a18a8b1d68433', 1, 'uiAiNXR9gxH7LzPB2D4JJGVGAaQaoYE9.jpg', 'lG8AZqroaynHiHYSnMJ-ZZsF_bc0C6KY.jpg', 1, 1, '919.524.4282 x0455', 0, 'wcummings@frami.info', '1988-03-13 04:41:02', '2010-03-21 11:51:35', '38', '38', '38', '38', '38', '781a56f821f03936daf342a807b157ad', '50e2a570e49f0cdd472083beabdb2555', NULL, 'recusandae', 'iure', 0, NULL, NULL, 'natus', NULL, '38', 'Authorized Signature', 'DE78684143754613110605', 'Mabel', 'Volkman', 'damon.runte@yahoo.com', '1-527-825-7010', 91, NULL, NULL, NULL, NULL, 'id', 'dolores', 1, 'roscoe67@gmail.com', '7d87251a977795ca35e0f9c50581d935', 1, 'Fay Track', NULL, 1, NULL, NULL, 'aut', NULL, NULL, NULL, 389, 'd5cd14cd143164bcc96dc6416a1a84c1', '9b956df205940d464138b542500a5a82', 1, 'http://rippin.com/ratione-dolorem-eum-ducimus-quo-veritatis-aliquid-commodi-quos.html', 1, 1, '20b2dd0c411097fa06acbb3d9918285e', '5c5008e5d5a2c598080c7324985e9b22', '1a774aeb9fbbd730bd6abb152dfed60c', '38', 'Yundt and Sons', 1, 0, NULL, 1, 781, NULL, 'at', 'possimus', 'ln', 0, 2, 1, '66.000', '0.000', NULL, '6ed97f563ab366a50cc00255d8464cef', 1, 1, NULL, NULL, 1, 1, 0, 0, 0, 0, 0, NULL, NULL, NULL),
('39', 39, 39, 'minus', 'iusto', NULL, NULL, NULL, NULL, 'Velit deleniti aut qui.', 'Modi quae molestiae repellendus vitae.', 'kertzmann.org', 'eb66cf588ab92885408649ffd5e2ec4b', 1, 'uiAiNXR9gxH7LzPB2D4JJGVGAaQaoYE9.jpg', 'lG8AZqroaynHiHYSnMJ-ZZsF_bc0C6KY.jpg', 1, 1, '856.849.0555', 0, 'zhauck@hotmail.com', '1999-01-14 11:35:43', '1970-08-25 12:00:38', '39', '39', '39', '39', '39', '4c31466c9a49918f2157a55f41a64e28', '4db41a0af9f450f63fc83e4274e2cebc', NULL, 'maiores', 'ut', 0, NULL, NULL, 'autem', NULL, '39', 'Authorized Signature', 'IT83P4340439047UK6TP9C09M33', 'Gia', 'Weissnat', 'hattie.rohan@gmail.com', '1-567-226-9642', 91, NULL, NULL, NULL, NULL, 'laboriosam', 'delectus', 1, 'reynold55@stokes.com', '505e085de70418bd6bc3329a4c740308', 1, 'Pouros Lake', NULL, 1, NULL, NULL, 'totam', NULL, NULL, NULL, 279, '2c05047d63ae3eed9dedea1b61aa9d78', '87ea8250dbeb38aa19f55367cba87ea9', 1, 'http://kassulke.org/', 1, 1, 'ae82260b39aee19b9ee86a58cf8b4bf2', '94026db9c40d2ae829ca87479f0749be', '90ca3ce25ec5c4478382ac840bcbf84c', '39', 'Bailey-Champlin', 1, 0, NULL, 1, 448, NULL, 'quis', 'non', 'an', 0, 2, 1, '77.000', '0.000', NULL, '53f3d8c2743d750e69263928745db10f', 1, 1, NULL, NULL, 1, 1, 0, 0, 0, 0, 0, NULL, NULL, NULL),
('4', 4, 4, 'officiis', 'sed', NULL, NULL, NULL, NULL, 'Odio nostrum perferendis.', 'Non est debitis dolores cum dolore.', 'breitenberg.com', '92a35ea3e47ea9d5c8fb7d0a803a8e36', 1, 'uiAiNXR9gxH7LzPB2D4JJGVGAaQaoYE9.jpg', 'lG8AZqroaynHiHYSnMJ-ZZsF_bc0C6KY.jpg', 1, 1, '(330) 231-5397', 0, 'mayert.lizzie@hotmail.com', '2016-10-07 20:23:49', '2005-06-03 21:31:48', '4', '4', '4', '4', '4', 'b5339c4bb828a0992f762aa9fb51c6f1', '56669528c42b143a5adc12a801d85a73', NULL, 'officia', 'et', 0, NULL, NULL, 'porro', NULL, '4', 'Authorized Signature', 'LI58787395Q7Y35UC7Z24', 'Florine', 'Schultz', 'rosalyn.schmitt@hotmail.com', '+18385485399', 91, NULL, NULL, NULL, NULL, 'possimus', 'odit', 1, 'orpha.schoen@homenick.com', '8a0f0aaeee08b79d821e1700b6d341c6', 1, 'Elmore Garden', NULL, 1, NULL, NULL, 'velit', NULL, NULL, NULL, 858, 'e84a254f253c507bc1148d173bcf2ad1', '24ac01c14d5317d897b52f570fdbf191', 1, 'http://www.smitham.net/eum-voluptates-exercitationem-magni-fugiat', 1, 1, 'c108f04e43f4ac696af76f5181f67bed', 'e3c80ef5a0a72af45a595df3281a94e7', 'ca0093995908cd6e368ffc2076def81f', '4', 'Casper LLC', 1, 0, NULL, 1, 587, NULL, 'corrupti', 'hic', 'hz', 0, 2, 1, '85.000', '0.000', NULL, '21f2ca44a82e5251a329c5fa1faf1b16', 1, 1, NULL, NULL, 1, 1, 0, 0, 0, 0, 0, NULL, NULL, NULL),
('40', 40, 40, 'omnis', 'blanditiis', NULL, NULL, NULL, NULL, 'Veniam et similique sed architecto.', 'Qui delectus quaerat quia id qui.', 'schmitt.com', 'fd3c26e59105186e0007788d563ecae3', 1, 'uiAiNXR9gxH7LzPB2D4JJGVGAaQaoYE9.jpg', 'lG8AZqroaynHiHYSnMJ-ZZsF_bc0C6KY.jpg', 1, 1, '672.783.4668', 0, 'tcassin@jast.biz', '1990-08-17 13:57:41', '1978-08-13 21:46:33', '40', '40', '40', '40', '40', 'bb6f3a3a264c13211ecb5e0ce8901f34', '16a433ddb85c201934d3309ee41dacf4', NULL, 'sed', 'vero', 0, NULL, NULL, 'quo', NULL, '40', 'Authorized Signature', 'BH47VSXJ6O2Q5FJ6Y1VGL3', 'Alverta', 'Wisozk', 'mccullough.larissa@hotmail.com', '+1.463.871.3906', 91, NULL, NULL, NULL, NULL, 'sequi', 'molestias', 1, 'cummerata.gillian@yahoo.com', '4185ba4fcc5b5f6b7ecf31f09e2354dd', 1, 'Fritsch Islands', NULL, 1, NULL, NULL, 'incidunt', NULL, NULL, NULL, 338, 'ca5220fc362f9c2f024ca882b98dd617', '1b08c2cd39aaafcb69a33bc7cd7fb45f', 1, 'http://stehr.com/laboriosam-tempora-aliquam-illo-ut', 1, 1, 'b74e6147a17850a71b48e6c76c3ce703', 'ff72b989313d8a2311d33c9ac60cf096', '658ee17f395852de3db4e8eec76613dc', '40', 'Feil Inc', 1, 0, NULL, 1, 293, NULL, 'doloribus', 'reiciendis', 'wo', 0, 2, 1, '44.000', '0.000', NULL, 'b7ee7d01e54b9fb11df4b3eeae536696', 1, 1, NULL, NULL, 1, 1, 0, 0, 0, 0, 0, NULL, NULL, NULL),
('41', 41, 41, 'itaque', 'perferendis', NULL, NULL, NULL, NULL, 'Corrupti facilis ut sit.', 'Ut consectetur culpa illum in labore.', 'bogisich.info', '8d2f0d29811b1bb277287a299723076b', 1, 'uiAiNXR9gxH7LzPB2D4JJGVGAaQaoYE9.jpg', 'lG8AZqroaynHiHYSnMJ-ZZsF_bc0C6KY.jpg', 1, 1, '1-271-720-8522 x099', 0, 'leta80@yahoo.com', '1997-06-28 04:03:23', '1971-07-10 08:20:05', '41', '41', '41', '41', '41', 'dd7bda280666c9bcbc73688ffa70c8b3', '3fa007672d781f6e8d8dd0f98ff05cc1', NULL, 'quis', 'nisi', 0, NULL, NULL, 'sapiente', NULL, '41', 'Authorized Signature', 'RO48ZOAKW3JFS90Y1P469WYD', 'Rowan', 'Stokes', 'kihn.celestino@halvorson.com', '(751) 350-6718', 91, NULL, NULL, NULL, NULL, 'numquam', 'explicabo', 1, 'darien.roberts@yahoo.com', '2e17782cf48d40960aa7882665dfd065', 1, 'Ray Valley', NULL, 1, NULL, NULL, 'qui', NULL, NULL, NULL, 587, '4aec6fdcbc2ebac425bbda9a5db6211f', '665dcd6ac20b186f179c15fee2273eb3', 1, 'http://herzog.biz/nobis-aliquam-sed-sint-autem', 1, 1, '2501c57c8817b00587d90d2d98eb882d', '34559a2ae686a4c9e4c8bcd1269fc003', '7574c360da53416948f32dd6d9289ee8', '41', 'Batz-Gusikowski', 1, 0, NULL, 1, 434, NULL, 'est', 'corrupti', 'cv', 0, 2, 1, '47.000', '0.000', NULL, '3778a185d896b9973a5f0566422cfea8', 1, 1, NULL, NULL, 1, 1, 0, 0, 0, 0, 0, NULL, NULL, NULL),
('42', 42, 42, 'pariatur', 'et', NULL, NULL, NULL, NULL, 'Qui veniam quisquam quisquam voluptatem.', 'Placeat provident nihil voluptatem aliquid.', 'littel.net', '2ecccd8646066c6f09e142c65be009f3', 1, 'uiAiNXR9gxH7LzPB2D4JJGVGAaQaoYE9.jpg', 'lG8AZqroaynHiHYSnMJ-ZZsF_bc0C6KY.jpg', 1, 1, '1-201-810-1352', 0, 'rosalinda03@yahoo.com', '1978-01-24 10:58:20', '1975-12-03 01:05:11', '42', '42', '42', '42', '42', 'c4bcc129a91fa2ecf6a35254ea6180ec', '27d348ad5567b4978e13d5230887496d', NULL, 'repellat', 'corporis', 0, NULL, NULL, 'eum', NULL, '42', 'Authorized Signature', 'SI63438074224481765', 'Federico', 'Daugherty', 'tcollins@donnelly.biz', '305.767.8524', 91, NULL, NULL, NULL, NULL, 'aspernatur', 'repellendus', 1, 'tlebsack@schroeder.com', '01084da01f45bd54cae92373db1cd877', 1, 'Drew Mill', NULL, 1, NULL, NULL, 'cum', NULL, NULL, NULL, 907, 'cdf588e6ba9bf27805738d9cf8b383a4', '9310f6ea231d963a6a6c89ff58c6fff2', 1, 'http://www.johnston.com/quos-aut-ex-optio', 1, 1, 'd338f7a9edf19975c3b74408c09f8046', '9d1e607c5aee8dcf384baa1fdb05cf49', 'f412320c3a561068bb726eef2a2e7f92', '42', 'Maggio, Oberbrunner and Jaskolski', 1, 0, NULL, 1, 192, NULL, 'aspernatur', 'maiores', 'fy', 0, 2, 1, '61.000', '0.000', NULL, '3e4a2755d80e4c79165d3eca6394791f', 1, 1, NULL, NULL, 1, 1, 0, 0, 0, 0, 0, NULL, NULL, NULL);
INSERT INTO `restaurant` (`restaurant_uuid`, `country_id`, `currency_id`, `name`, `name_ar`, `meta_title`, `meta_title_ar`, `meta_description`, `meta_description_ar`, `tagline`, `tagline_ar`, `restaurant_domain`, `app_id`, `restaurant_status`, `thumbnail_image`, `logo`, `support_delivery`, `support_pick_up`, `phone_number`, `phone_number_country_code`, `restaurant_email`, `restaurant_created_at`, `restaurant_updated_at`, `business_id`, `business_entity_id`, `wallet_id`, `merchant_id`, `operator_id`, `live_api_key`, `test_api_key`, `business_type`, `vendor_sector`, `license_number`, `not_for_profit`, `authorized_signature_issuing_date`, `authorized_signature_expiry_date`, `authorized_signature_title`, `authorized_signature_file`, `authorized_signature_file_id`, `authorized_signature_file_purpose`, `iban`, `owner_first_name`, `owner_last_name`, `owner_email`, `owner_number`, `owner_phone_country_code`, `identification_issuing_date`, `identification_expiry_date`, `identification_file_front_side`, `identification_file_id_front_side`, `identification_title`, `identification_file_purpose`, `restaurant_email_notification`, `developer_id`, `armada_api_key`, `phone_number_display`, `store_branch_name`, `custom_css`, `store_layout`, `commercial_license_issuing_date`, `commercial_license_expiry_date`, `commercial_license_title`, `commercial_license_file`, `commercial_license_file_id`, `commercial_license_file_purpose`, `platform_fee`, `google_analytics_id`, `facebook_pixil_id`, `show_opening_hours`, `instagram_url`, `schedule_order`, `schedule_interval`, `mashkor_branch_id`, `live_public_key`, `test_public_key`, `site_id`, `company_name`, `is_tap_enable`, `is_myfatoorah_enable`, `supplierCode`, `has_deployed`, `tap_queue_id`, `annual_revenue`, `identification_file_back_side`, `identification_file_id_back_side`, `default_language`, `hide_request_driver_button`, `version`, `sitemap_require_update`, `warehouse_fee`, `warehouse_delivery_charges`, `payment_gateway_queue_id`, `snapchat_pixil_id`, `retention_email_sent`, `enable_gift_message`, `referral_code`, `custom_subscription_price`, `demand_delivery`, `is_public`, `accept_order_247`, `is_sandbox`, `is_under_maintenance`, `is_deleted`, `enable_debugger`, `last_active_at`, `last_order_at`, `warned_delete_at`) VALUES
('43', 43, 43, 'ex', 'dolorum', NULL, NULL, NULL, NULL, 'Temporibus et omnis sunt ipsum nostrum.', 'Dolore fugit ea expedita.', 'hand.org', 'cb8a3583995e57c1c68940bbfe9c0d42', 1, 'uiAiNXR9gxH7LzPB2D4JJGVGAaQaoYE9.jpg', 'lG8AZqroaynHiHYSnMJ-ZZsF_bc0C6KY.jpg', 1, 1, '(509) 607-5557 x78172', 0, 'mabelle11@rogahn.net', '1989-02-20 22:14:15', '1979-04-05 07:37:11', '43', '43', '43', '43', '43', '3e5bfc959b696be71ee2ca38ab759d95', 'a4bd125aec6ff4d474ec62243e955ffb', NULL, 'eveniet', 'enim', 0, NULL, NULL, 'numquam', NULL, '43', 'Authorized Signature', 'HU16313340501235395837555772', 'Kenneth', 'Legros', 'ewell.lakin@gusikowski.com', '(682) 661-6835 x252', 91, NULL, NULL, NULL, NULL, 'consequatur', 'et', 1, 'alice.boyle@cormier.com', '5fc78f668dd3a96bc90fbc2bddd688d1', 1, 'Marcos Ramp', NULL, 1, NULL, NULL, 'sit', NULL, NULL, NULL, 593, '9ce51e6f0b2585df68bb40d4d20f2d58', '02e2cc1d68baf217c32bbd285f336427', 1, 'http://www.kunde.com/', 1, 1, 'ef9e1a047c2bf37e82242b29c568602b', 'dd6375ec60c056b7e2cbe3ed2f638e60', '1a47b75f9603547d32337d3d92d8669a', '43', 'Hammes-Johns', 1, 0, NULL, 1, 659, NULL, 'in', 'sequi', 'fa', 0, 2, 1, '49.000', '0.000', NULL, '616cf0fe0acafc0fa12a05d0b690e055', 1, 1, NULL, NULL, 1, 1, 0, 0, 0, 0, 0, NULL, NULL, NULL),
('44', 44, 44, 'ut', 'qui', NULL, NULL, NULL, NULL, 'Totam tenetur consequatur.', 'Qui adipisci nostrum aut iste sunt.', 'mraz.com', '67b413559cd8a051aa8a56a2a85ece7e', 1, 'uiAiNXR9gxH7LzPB2D4JJGVGAaQaoYE9.jpg', 'lG8AZqroaynHiHYSnMJ-ZZsF_bc0C6KY.jpg', 1, 1, '1-298-998-5520 x833', 0, 'rippin.lamont@hotmail.com', '2013-02-26 07:23:20', '1982-12-30 07:53:17', '44', '44', '44', '44', '44', 'cd15bccebc9116e510bfe436626bbf22', '6e15eb3aab891742c21494c943a7db6d', NULL, 'eos', 'voluptas', 0, NULL, NULL, 'et', NULL, '44', 'Authorized Signature', 'MK78216137M4B54RD30', 'Marilyne', 'Green', 'annabel12@witting.biz', '883.763.4433 x263', 91, NULL, NULL, NULL, NULL, 'nostrum', 'nesciunt', 1, 'vspinka@hotmail.com', 'd4e9af8c83773bc15d42e6cd4a7dfdf6', 1, 'Evert Creek', NULL, 1, NULL, NULL, 'est', NULL, NULL, NULL, 915, '7fada49445e6ccd79e4361d1497c30bf', 'ec55d32c6fecc57a84770de2171eaf02', 1, 'http://powlowski.org/autem-sed-dolorum-consequuntur-sunt-commodi-et-laborum', 1, 1, 'd92a37d80015fbb45a59452bb53744dc', '01fdd1aa2e9f1e17068fef8ba6b058f6', '2960ad1515ecd07b2d1d53163b80904b', '44', 'Thompson, Gutkowski and Monahan', 1, 0, NULL, 1, 702, NULL, 'nobis', 'eius', 'ka', 0, 2, 1, '81.000', '0.000', NULL, '8df4458d86d7e4779ccbd5ad81d14d89', 1, 1, NULL, NULL, 1, 1, 0, 0, 0, 0, 0, NULL, NULL, NULL),
('45', 45, 45, 'doloremque', 'aspernatur', NULL, NULL, NULL, NULL, 'Quidem molestias et.', 'Placeat eos sunt omnis omnis.', 'kub.com', 'bf4d724cd9805e5441662726516a7aba', 1, 'uiAiNXR9gxH7LzPB2D4JJGVGAaQaoYE9.jpg', 'lG8AZqroaynHiHYSnMJ-ZZsF_bc0C6KY.jpg', 1, 1, '(909) 803-3236 x61839', 0, 'lmckenzie@yahoo.com', '1972-05-06 17:02:59', '1988-04-10 12:00:00', '45', '45', '45', '45', '45', '9399255e9c63c8eccfcde05c7169fd73', 'f3d28cc506453e6ab447b418fc62fc3d', NULL, 'eligendi', 'aliquid', 0, NULL, NULL, 'natus', NULL, '45', 'Authorized Signature', 'MU49SWAH5367983921431347849PFG', 'Darren', 'Hahn', 'ostroman@prosacco.com', '+1.987.857.2742', 91, NULL, NULL, NULL, NULL, 'suscipit', 'culpa', 1, 'cdavis@klein.com', 'e0f3b3ac9da1573bf9eae60641d678cc', 1, 'Annabelle Ridges', NULL, 1, NULL, NULL, 'eum', NULL, NULL, NULL, 182, '4cf8fedbd709cce2adad50de0455a0a3', '26db8152c19be67f72d4d49e26b8683f', 1, 'http://www.grant.com/', 1, 1, '7fa24a2d1707297dd31f018055a7ec54', 'cda02f7add2ca47399645598b04ff929', 'f7fba3f1629b4a50bf77f2223df4bc2b', '45', 'Ruecker, Bode and Lowe', 1, 0, NULL, 1, 127, NULL, 'ea', 'illum', 'pi', 0, 2, 1, '92.000', '0.000', NULL, '600d8a6628bae05b0d2a2ae8635941de', 1, 1, NULL, NULL, 1, 1, 0, 0, 0, 0, 0, NULL, NULL, NULL),
('46', 46, 46, 'quam', 'placeat', NULL, NULL, NULL, NULL, 'Tempore possimus eligendi autem et.', 'Quisquam aut sit harum.', 'bergnaum.com', '80499341a4aec6263237dc8dd56c9b8e', 1, 'uiAiNXR9gxH7LzPB2D4JJGVGAaQaoYE9.jpg', 'lG8AZqroaynHiHYSnMJ-ZZsF_bc0C6KY.jpg', 1, 1, '1-523-590-1497 x83414', 0, 'charlotte.harber@gmail.com', '1986-05-16 13:58:47', '2013-12-25 13:37:36', '46', '46', '46', '46', '46', '630cec0e35dfae1891f1afb37747c859', '1973fc178723dbaf70fc984e7e091572', NULL, 'sint', 'ratione', 0, NULL, NULL, 'ea', NULL, '46', 'Authorized Signature', 'MC90420512837288TVY0OYE7D49', 'Emie', 'Nikolaus', 'leonora36@gmail.com', '514-358-6745 x5391', 91, NULL, NULL, NULL, NULL, 'consequatur', 'ut', 1, 'schmidt.marianne@schumm.org', '02561ae84818db002b1a74ab75a0c129', 1, 'Alfred Falls', NULL, 1, NULL, NULL, 'ea', NULL, NULL, NULL, 358, 'f99ebc9b766df460a24b034dd2b24845', '0c6da954634674cd8c78c09e4cf86fd4', 1, 'http://www.bahringer.com/', 1, 1, 'a461d9be7f0d86743541269a6b03ba51', 'b9617609b491c1ed5bee4437d89463da', '6246748fc18a2832ab7c9d11e8de127b', '46', 'Green-Johnson', 1, 0, NULL, 1, 755, NULL, 'possimus', 'est', 'su', 0, 2, 1, '62.000', '0.000', NULL, 'c767413c6013acb93fde0e9431e9cd60', 1, 1, NULL, NULL, 1, 1, 0, 0, 0, 0, 0, NULL, NULL, NULL),
('47', 47, 47, 'suscipit', 'sint', NULL, NULL, NULL, NULL, 'Velit et suscipit.', 'Eum culpa quia et exercitationem.', 'becker.com', 'dbf042de4baf5ce5d9b34fc7f2122000', 1, 'uiAiNXR9gxH7LzPB2D4JJGVGAaQaoYE9.jpg', 'lG8AZqroaynHiHYSnMJ-ZZsF_bc0C6KY.jpg', 1, 1, '(467) 440-9419 x069', 0, 'ikulas@lueilwitz.com', '2020-12-31 03:24:30', '2016-04-19 07:16:21', '47', '47', '47', '47', '47', '47d9ee50299aac86ae323cbfd934ae62', 'de2f83e2a82f18e2a3480a0d7330d644', NULL, 'fugit', 'sunt', 0, NULL, NULL, 'voluptatem', NULL, '47', 'Authorized Signature', 'TN3223500256786208368560', 'Elsa', 'Schaden', 'ojast@yahoo.com', '272-868-7244', 91, NULL, NULL, NULL, NULL, 'quo', 'repudiandae', 1, 'eprice@yahoo.com', '2f11bcfcb2ffda6aa8e9ff86459107b1', 1, 'Kris Cove', NULL, 1, NULL, NULL, 'ab', NULL, NULL, NULL, 883, 'bbce828b17086c1d9754c266ec4eb03f', '592c99df418050b948c6cd2c1dce79ce', 1, 'http://www.treutel.com/nemo-rerum-laboriosam-autem-vero-rem', 1, 1, '34d051360090833fea01a5366dfd58cf', '5c81dacb20e7fb57bd85818670378853', 'ab2f174174ff45dbe86124357ca23d44', '47', 'Ebert, Jacobs and Blick', 1, 0, NULL, 1, 452, NULL, 'harum', 'in', 'te', 0, 2, 1, '19.000', '0.000', NULL, '243e886461a15195f7c5c033d06bc2a6', 1, 1, NULL, NULL, 1, 1, 0, 0, 0, 0, 0, NULL, NULL, NULL),
('48', 48, 48, 'est', 'fugit', NULL, NULL, NULL, NULL, 'Non expedita in perferendis.', 'Itaque nulla voluptas libero ut.', 'rogahn.com', 'dc97b5c2b62dab09dcb300c6196328fa', 1, 'uiAiNXR9gxH7LzPB2D4JJGVGAaQaoYE9.jpg', 'lG8AZqroaynHiHYSnMJ-ZZsF_bc0C6KY.jpg', 1, 1, '1-314-313-6766', 0, 'wyman.upton@yahoo.com', '2020-06-25 16:58:57', '1993-05-28 13:18:50', '48', '48', '48', '48', '48', 'afb00db865881d88a6e05134bcc54342', '6ed4a3556530abab624d9aa3e05333b4', NULL, 'eaque', 'et', 0, NULL, NULL, 'enim', NULL, '48', 'Authorized Signature', 'GT6204VU6VR19QCSXS7597B2416X', 'Julia', 'Ruecker', 'rocio29@kuvalis.net', '(929) 368-4765 x829', 91, NULL, NULL, NULL, NULL, 'aspernatur', 'corrupti', 1, 'hickle.malinda@grant.org', 'c99bf33a74e3c89cf982691572d15299', 1, 'Harber Mountains', NULL, 1, NULL, NULL, 'natus', NULL, NULL, NULL, 463, '580904111afdb3bc0207b1284159d9d3', 'f66827b92e6cc910fdb7b71302a1a16b', 1, 'http://heathcote.com/qui-saepe-praesentium-et-odio', 1, 1, '504b1e1a853d8b45eec2096eeebe7271', '31cc5f6e82ad488961ce99820e25d955', '3f223b14fac966e96c735f92fadd3ca1', '48', 'Bosco LLC', 1, 0, NULL, 1, 724, NULL, 'sit', 'sit', 'ak', 0, 2, 1, '84.000', '0.000', NULL, '665fba4e1d011556a759def4fac4deab', 1, 1, NULL, NULL, 1, 1, 0, 0, 0, 0, 0, NULL, NULL, NULL),
('49', 49, 49, 'sit', 'qui', NULL, NULL, NULL, NULL, 'Labore dolor iste voluptatum.', 'Placeat totam consectetur sed.', 'schmitt.com', 'ef3e46125f2b7365a02bd74b331456ad', 1, 'uiAiNXR9gxH7LzPB2D4JJGVGAaQaoYE9.jpg', 'lG8AZqroaynHiHYSnMJ-ZZsF_bc0C6KY.jpg', 1, 1, '(251) 539-0536', 0, 'keebler.ben@gmail.com', '1981-03-18 18:41:13', '1994-01-10 22:31:34', '49', '49', '49', '49', '49', '61d714a2178e449bc98f4630d39ec0f6', '12b7a974acc34f84e3969ab5e6220491', NULL, 'repudiandae', 'tempora', 0, NULL, NULL, 'earum', NULL, '49', 'Authorized Signature', 'SM32C7295936304QG5G1455CY9W', 'Juliana', 'Renner', 'mtowne@yahoo.com', '(520) 238-9434', 91, NULL, NULL, NULL, NULL, 'exercitationem', 'ut', 1, 'lschoen@hotmail.com', '87a7d8d96587df92986bc8dbe11c6582', 1, 'Bergstrom Causeway', NULL, 1, NULL, NULL, 'enim', NULL, NULL, NULL, 257, 'd2d1bec9960e983bd7b0f9ce03f55653', 'b92843593874ee94a0bebebe33687dc4', 1, 'http://welch.org/', 1, 1, '65b4ef97ca7fd0cc175c45b82ae1d088', 'bf36815c353bb56e5e6e7667033db495', 'a8e15a934342f3918a1d4e0b5defaefc', '49', 'Bode, Kassulke and Harvey', 1, 0, NULL, 1, 868, NULL, 'animi', 'officiis', 'pl', 0, 2, 1, '15.000', '0.000', NULL, '3fe3ca784bf369471a8c8bdb6d8ee8ca', 1, 1, NULL, NULL, 1, 1, 0, 0, 0, 0, 0, NULL, NULL, NULL),
('5', 5, 5, 'numquam', 'et', NULL, NULL, NULL, NULL, 'In neque soluta natus magni.', 'Sed ipsa alias commodi.', 'schuster.com', 'ec9507e4179508de54dba317209dcf20', 1, 'uiAiNXR9gxH7LzPB2D4JJGVGAaQaoYE9.jpg', 'lG8AZqroaynHiHYSnMJ-ZZsF_bc0C6KY.jpg', 1, 1, '203.255.6354', 0, 'jacklyn.johns@walker.com', '1981-04-19 02:41:36', '1993-01-27 09:37:53', '5', '5', '5', '5', '5', 'dffa6d9a86f9777e3986a7e476ea13b8', 'ed9bf058389f53969e440a9cf135f49e', NULL, 'eveniet', 'quia', 0, NULL, NULL, 'a', NULL, '5', 'Authorized Signature', 'CH1000742392O3N3A1511', 'Jerry', 'Windler', 'myrtie60@sauer.biz', '+1 (247) 241-1237', 91, NULL, NULL, NULL, NULL, 'quia', 'architecto', 1, 'alexandrine24@yahoo.com', '8c046a7db96d4cab577c641919585422', 1, 'Harmony Lights', NULL, 1, NULL, NULL, 'est', NULL, NULL, NULL, 916, 'f872ec1b31a09205282c0d153781f9d9', 'eb0a1af6308bdd1b54642f845645119b', 1, 'https://schmitt.com/aliquam-omnis-in-asperiores.html', 1, 1, '19a9e5852548bf08e9f39c45ebc4cc9d', 'ab22118bf8dc95085b4ce2d2fcd2adf1', 'cb5e818169d41a7b88e85194da1aa2ca', '5', 'Ward, Robel and Ziemann', 1, 0, NULL, 1, 902, NULL, 'ut', 'adipisci', 'ar', 0, 2, 1, '40.000', '0.000', NULL, 'a7550833507849092d6f9cab18a9f7c6', 1, 1, NULL, NULL, 1, 1, 0, 0, 0, 0, 0, NULL, NULL, NULL),
('50', 50, 50, 'qui', 'tenetur', NULL, NULL, NULL, NULL, 'Distinctio molestiae possimus deserunt.', 'Nemo sint repudiandae.', 'cremin.org', '25c559530ff082ef424b88f47234691f', 1, 'uiAiNXR9gxH7LzPB2D4JJGVGAaQaoYE9.jpg', 'lG8AZqroaynHiHYSnMJ-ZZsF_bc0C6KY.jpg', 1, 1, '821.346.9349 x04525', 0, 'conroy.enrico@schuppe.com', '1972-01-16 16:36:02', '2009-06-24 18:57:19', '50', '50', '50', '50', '50', '35bfa19c7dfd04d8628f47a7ac7ba9d3', '46e355624c655fe36b63ad5adb3ecedf', NULL, 'eaque', 'nobis', 0, NULL, NULL, 'consequatur', NULL, '50', 'Authorized Signature', 'PT11811793048621564439783', 'Lesly', 'Goldner', 'hwill@hotmail.com', '1-923-898-7376', 91, NULL, NULL, NULL, NULL, 'laborum', 'laborum', 1, 'barry62@smith.org', 'b4bfbc77a15c472a2b85c3a0f1cc014d', 1, 'Schiller Causeway', NULL, 1, NULL, NULL, 'et', NULL, NULL, NULL, 143, '626dda3af6017718509eaa2ef69159fb', '3e90ebfaf2d9ff7ddda93a403dce21f3', 1, 'http://kertzmann.org/', 1, 1, '94e9ea4121a9f2704e7af373f0f4a9e7', 'a3634ff39974515f7a4226dd8c5cf47d', 'b98e4dad6d8607d90c7fbe38853ac567', '50', 'Spinka, Runolfsson and Metz', 1, 0, NULL, 1, 117, NULL, 'nam', 'labore', 'uz', 0, 2, 1, '63.000', '0.000', NULL, '1c72bb37333e7250246cb06736df4c72', 1, 1, NULL, NULL, 1, 1, 0, 0, 0, 0, 0, NULL, NULL, NULL),
('6', 6, 6, 'cumque', 'sunt', NULL, NULL, NULL, NULL, 'Occaecati aut aut ab.', 'Autem eos nobis rerum.', 'bartell.com', 'd9eb37d310925a280f1cb0a254d46aa1', 1, 'uiAiNXR9gxH7LzPB2D4JJGVGAaQaoYE9.jpg', 'lG8AZqroaynHiHYSnMJ-ZZsF_bc0C6KY.jpg', 1, 1, '1-681-441-0539', 0, 'stamm.kay@yahoo.com', '1998-03-10 15:00:32', '1995-10-16 17:09:54', '6', '6', '6', '6', '6', '2a5aeffb8263179e8041261d8670fe44', 'efb45ad843dd0844561fa6afc3ebe1ed', NULL, 'consequuntur', 'rerum', 0, NULL, NULL, 'maxime', NULL, '6', 'Authorized Signature', 'PL12643222066295616654921123', 'Kaia', 'Breitenberg', 'tom.keeling@yahoo.com', '730.219.6849', 91, NULL, NULL, NULL, NULL, 'voluptatibus', 'voluptas', 1, 'edgardo.wiza@gmail.com', 'e2badb988720ea109013ac0d23a1a287', 1, 'Bernhard Rue', NULL, 1, NULL, NULL, 'eum', NULL, NULL, NULL, 948, '590bf7bfb142ab552262482cb4f88bdb', '37b321977430fc981587ebad0fef3124', 1, 'http://bartell.org/ullam-placeat-qui-molestiae-at-dicta-assumenda-inventore.html', 1, 1, 'b0c31e1416cf52a470ebd22009fdf5de', 'f9c6d6227da5d4bfce7fbb75dc020073', 'db61b5e7c21211a08b69f7dd494e763c', '6', 'Nitzsche LLC', 1, 0, NULL, 1, 608, NULL, 'aut', 'enim', 'as', 0, 2, 1, '53.000', '0.000', NULL, 'ea3e7bd8715fe22759a7333eba374b9f', 1, 1, NULL, NULL, 1, 1, 0, 0, 0, 0, 0, NULL, NULL, NULL),
('7', 7, 7, 'esse', 'amet', NULL, NULL, NULL, NULL, 'Odio quia maxime.', 'Illo voluptas impedit.', 'wisoky.org', 'f14d7643d0258e5ab0bb788c8bb72c3c', 1, 'uiAiNXR9gxH7LzPB2D4JJGVGAaQaoYE9.jpg', 'lG8AZqroaynHiHYSnMJ-ZZsF_bc0C6KY.jpg', 1, 1, '(505) 668-4981', 0, 'kirsten57@senger.net', '1972-03-25 16:10:26', '1989-06-15 04:38:13', '7', '7', '7', '7', '7', 'b190f98743bf3f52af3950e102bf6908', '4262379c34b41c3565e07ea2ff7cdc4f', NULL, 'eaque', 'impedit', 0, NULL, NULL, 'non', NULL, '7', 'Authorized Signature', 'TR66063028FVJRG0L7D28F989A', 'Lewis', 'Parker', 'giuseppe33@yahoo.com', '1-563-486-3956', 91, NULL, NULL, NULL, NULL, 'repudiandae', 'porro', 1, 'jaron.volkman@zemlak.com', '765984f9c2315e8c406af28231e98481', 1, 'King Gardens', NULL, 1, NULL, NULL, 'cupiditate', NULL, NULL, NULL, 449, '3477f5da85190c87a259a2180a4e193b', '85f3b7c532a8f935a4b44a7fc7577da4', 1, 'http://www.russel.com/eum-non-aliquid-amet-deleniti-laborum-voluptas-eveniet', 1, 1, '24db9c8285cbf4e42a9a925c1f024fd8', '7f8bf5a912f7ddf5d0d8fc808e3dc41f', '09bfe12cf67358b7e4ec99dc50cd17e2', '7', 'Collins, Boyer and Bruen', 1, 0, NULL, 1, 123, NULL, 'iusto', 'voluptatem', 'ha', 0, 2, 1, '88.000', '0.000', NULL, '04d9b4040d24364cb7f85b9f3326cd60', 1, 1, NULL, NULL, 1, 1, 0, 0, 0, 0, 0, NULL, NULL, NULL),
('8', 8, 8, 'at', 'veniam', NULL, NULL, NULL, NULL, 'Molestiae ut quia quisquam enim eveniet.', 'Voluptates maiores et totam sunt reprehenderit.', 'murray.biz', '31d56aa0960d0e67af284f0b5469b453', 1, 'uiAiNXR9gxH7LzPB2D4JJGVGAaQaoYE9.jpg', 'lG8AZqroaynHiHYSnMJ-ZZsF_bc0C6KY.jpg', 1, 1, '+1-920-223-1646', 0, 'josefa.jerde@schmidt.net', '1984-02-28 14:00:21', '2014-12-08 05:33:24', '8', '8', '8', '8', '8', '32f493e77e3af236779bb3da71ec9fa7', 'd7ebe9fa362bd63db81850be77b0f44d', NULL, 'sequi', 'exercitationem', 0, NULL, NULL, 'eligendi', NULL, '8', 'Authorized Signature', 'BR8326165141101192313757257N5', 'Chasity', 'Schinner', 'hickle.jordi@yahoo.com', '725-495-2702 x5542', 91, NULL, NULL, NULL, NULL, 'quaerat', 'enim', 1, 'america48@yahoo.com', '1497cb5eb717079d72bd85b451864117', 1, 'Brandyn Vista', NULL, 1, NULL, NULL, 'magnam', NULL, NULL, NULL, 133, '0014a846f99df430048bcaf679b3da3d', '7037fd6d64deda16cd4c042f74838288', 1, 'http://www.marvin.biz/', 1, 1, '3f26938b8a9f6c55c6a10f4cf08a2454', '8266b8530d02c4d2e7f68da6c6d4abe8', 'a9adf11dba77cd861023b2a459e2663f', '8', 'Dach, Aufderhar and Mayer', 1, 0, NULL, 1, 332, NULL, 'earum', 'sequi', 'kk', 0, 2, 1, '56.000', '0.000', NULL, '917bbe2a562cdb3906c73b45532f7d92', 1, 1, NULL, NULL, 1, 1, 0, 0, 0, 0, 0, NULL, NULL, NULL),
('9', 9, 9, 'harum', 'eveniet', NULL, NULL, NULL, NULL, 'Consequuntur ipsa quia adipisci.', 'Dolorem et voluptatem repellat consequatur sapiente.', 'ratke.com', '9b880449878f89bcda1dcd7d97692525', 1, 'uiAiNXR9gxH7LzPB2D4JJGVGAaQaoYE9.jpg', 'lG8AZqroaynHiHYSnMJ-ZZsF_bc0C6KY.jpg', 1, 1, '293-698-5356', 0, 'dveum@koch.com', '1978-06-14 14:42:12', '2013-04-07 21:30:55', '9', '9', '9', '9', '9', '9eda8e7d272aac3b26a86308b975e0a0', '93601863c07b0d929d6457d3abbdb61d', NULL, 'et', 'tempore', 0, NULL, NULL, 'reiciendis', NULL, '9', 'Authorized Signature', 'IE02QKGF89468790387981', 'Aditya', 'Hermiston', 'pat.carter@gmail.com', '462-573-2536 x7660', 91, NULL, NULL, NULL, NULL, 'autem', 'ab', 1, 'wwilderman@zboncak.com', '0421727728b7d91c068d8bfd586c4605', 1, 'Noel Route', NULL, 1, NULL, NULL, 'quam', NULL, NULL, NULL, 782, '37a4d773ad53deea4b75e984f5e041b1', 'd2f3939e9533f5913f5d7cc505f522e3', 1, 'https://www.purdy.biz/vel-commodi-sint-fugiat-repellat', 1, 1, '6ad8890dff3ec1f2509ce39c6e6956ce', '40bc9d55255ed087c9cb8d841a527873', 'e759be91252ee552bf41b4485088e119', '9', 'Herman-Jacobi', 1, 0, NULL, 1, 151, NULL, 'odio', 'facere', 'lv', 0, 2, 1, '77.000', '0.000', NULL, '4667ff813e297b4cea575fb9aca39da1', 1, 1, NULL, NULL, 1, 1, 0, 0, 0, 0, 0, NULL, NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `restaurant_addon`
--

CREATE TABLE `restaurant_addon` (
  `ra_uuid` char(60) COLLATE utf8_unicode_ci NOT NULL,
  `addon_uuid` char(60) COLLATE utf8_unicode_ci DEFAULT NULL,
  `restaurant_uuid` char(60) COLLATE utf8_unicode_ci DEFAULT NULL,
  `created_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

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
(1, '1', 'Osborne Fords', 'Bode Roads', 6),
(2, '2', 'Langworth Pines', 'Runte River', 0),
(3, '3', 'Magnus Flat', 'Marks Light', 9),
(4, '4', 'Anderson Land', 'Walsh Park', 2),
(5, '5', 'Sonia Greens', 'Harvey Cliff', 12),
(6, '6', 'Karlee Ford', 'Ari Unions', 10),
(7, '7', 'Oma Row', 'Gabe Rapid', 2),
(8, '8', 'Rubye Cliffs', 'Carleton Mount', 9),
(9, '9', 'Swift Circles', 'Vladimir Road', 12),
(10, '10', 'Franecki Square', 'Izabella Views', 6),
(11, '11', 'Walker Key', 'Elenora Road', 7),
(12, '12', 'Cara Flats', 'Ziemann Mountains', 2),
(13, '13', 'Curtis Avenue', 'Dell Camp', 4),
(14, '14', 'Chaya Islands', 'Emie Forges', 2),
(15, '15', 'Dana Prairie', 'Susanna Via', 8),
(16, '16', 'Braun Canyon', 'Howe Orchard', 4),
(17, '17', 'Ratke Cliffs', 'Torphy Branch', 4),
(18, '18', 'Ferry Ford', 'Parker River', 8),
(19, '19', 'Ora Mountains', 'Yost Squares', 5),
(20, '20', 'Rohan Fork', 'Fisher Orchard', 5),
(21, '21', 'Altenwerth Tunnel', 'Jazmyne Way', 4),
(22, '22', 'Eveline Pine', 'Anibal Crescent', 2),
(23, '23', 'Gay Rue', 'Bernice Row', 2),
(24, '24', 'Farrell Orchard', 'Predovic Road', 8),
(25, '25', 'Schneider Brook', 'Johns Road', 8),
(26, '26', 'Crooks Ville', 'Eleanore Inlet', 1),
(27, '27', 'Pfannerstill Mews', 'Kendra Center', 11),
(28, '28', 'Schumm Mountains', 'Candace Dale', 2),
(29, '29', 'Hodkiewicz Wells', 'Fleta Hollow', 12),
(30, '30', 'Savannah Shoals', 'Denesik Shore', 0),
(31, '31', 'Donnelly Parkways', 'Dorothea Ranch', 11),
(32, '32', 'Crona Estate', 'Jerry Passage', 3),
(33, '33', 'Theron Circle', 'Adalberto Drives', 3),
(34, '34', 'Beatty Villages', 'Kiehn Brook', 6),
(35, '35', 'Schulist Extension', 'Treutel Trail', 7),
(36, '36', 'Bettye Stravenue', 'Stokes Dale', 6),
(37, '37', 'Heidenreich Grove', 'Garett Causeway', 8),
(38, '38', 'Cassin Ville', 'Nella Landing', 11),
(39, '39', 'Harvey Stream', 'Yasmine Vista', 10),
(40, '40', 'Nikolaus Drive', 'Rowland Route', 4),
(41, '41', 'Bahringer Stream', 'Corkery Locks', 5),
(42, '42', 'Lukas Groves', 'Nasir Road', 0),
(43, '43', 'Tamia Mountains', 'Rodriguez Heights', 6),
(44, '44', 'Kirlin Parkways', 'Cassin Islands', 6),
(45, '45', 'Charlotte Club', 'Leora Mountains', 6),
(46, '46', 'VonRueden Plains', 'Johnston Lakes', 7),
(47, '47', 'Pansy Loaf', 'Hills Forges', 0),
(48, '48', 'Emie Fort', 'Weimann Ports', 12),
(49, '49', 'Spencer Springs', 'Lonny Cove', 3),
(50, '50', 'Raynor Tunnel', 'Mraz Stravenue', 11);

-- --------------------------------------------------------

--
-- Table structure for table `restaurant_currency`
--

CREATE TABLE `restaurant_currency` (
  `restaurant_currency_uuid` char(60) COLLATE utf8_unicode_ci NOT NULL,
  `restaurant_uuid` char(60) COLLATE utf8_unicode_ci NOT NULL,
  `currency_id` int(11) DEFAULT NULL,
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `restaurant_currency`
--

INSERT INTO `restaurant_currency` (`restaurant_currency_uuid`, `restaurant_uuid`, `currency_id`, `created_at`, `updated_at`) VALUES
('1', '1', 1, '2007-07-26 02:22:25', '1983-03-15 01:41:23'),
('2', '2', 2, '1975-10-18 07:43:15', '2006-08-14 11:46:58'),
('3', '3', 3, '1991-09-28 18:23:11', '1995-11-19 10:06:19'),
('4', '4', 4, '1985-10-13 09:04:18', '2013-01-04 19:54:11'),
('5', '5', 5, '1985-04-30 16:41:13', '1975-08-08 01:50:58'),
('6', '6', 6, '1986-03-09 12:39:15', '1994-05-12 17:33:01'),
('7', '7', 7, '1997-02-14 10:19:54', '2005-11-05 10:21:44'),
('8', '8', 8, '2000-08-29 23:27:54', '1976-12-28 23:21:40'),
('9', '9', 9, '1982-11-28 22:29:52', '1982-05-09 17:22:27'),
('10', '10', 10, '1980-09-01 16:11:31', '1977-07-12 17:29:41');

-- --------------------------------------------------------

--
-- Table structure for table `restaurant_delivery`
--

CREATE TABLE `restaurant_delivery` (
  `restaurant_uuid` char(60) COLLATE utf8_unicode_ci NOT NULL,
  `area_id` int(11) NOT NULL,
  `delivery_time` int(11) UNSIGNED DEFAULT 60,
  `delivery_time_ar` int(11) UNSIGNED DEFAULT 60,
  `delivery_fee` float UNSIGNED DEFAULT 0,
  `min_charge` float UNSIGNED DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `restaurant_delivery`
--

INSERT INTO `restaurant_delivery` (`restaurant_uuid`, `area_id`, `delivery_time`, `delivery_time_ar`, `delivery_fee`, `min_charge`) VALUES
('', 2, 12, 1, 33, 0),
('', 5, 37, 44, 39, 9),
('', 6, 13, 11, 33, 51),
('', 8, 18, 16, 60, 34),
('', 9, 30, 16, 37, 36),
('', 10, 15, 0, 5, 54),
('', 12, 23, 46, 45, 56),
('', 13, 37, 42, 0, 45),
('', 14, 8, 8, 44, 28),
('', 16, 39, 2, 38, 25),
('', 19, 60, 21, 44, 17),
('', 21, 41, 2, 29, 23),
('', 22, 12, 5, 42, 13),
('', 27, 50, 0, 50, 4),
('', 29, 10, 50, 46, 15),
('', 33, 29, 59, 7, 39),
('', 34, 23, 9, 2, 31),
('', 35, 23, 9, 1, 47),
('', 36, 16, 20, 4, 39),
('', 38, 43, 27, 19, 23),
('', 39, 49, 12, 27, 37),
('', 40, 6, 10, 36, 23),
('', 41, 60, 1, 60, 55),
('', 42, 60, 45, 44, 20),
('', 43, 26, 12, 34, 3),
('', 44, 57, 57, 14, 2),
('', 47, 13, 0, 41, 25),
('', 48, 40, 52, 35, 50),
('', 50, 14, 48, 18, 31),
('', 52, 60, 60, 4, 28),
('', 53, 17, 27, 60, 0),
('', 55, 45, 20, 22, 1),
('', 59, 9, 22, 55, 15),
('', 61, 28, 35, 29, 0),
('', 69, 51, 3, 52, 16),
('', 76, 50, 15, 12, 5),
('', 79, 50, 20, 50, 21),
('', 85, 18, 30, 45, 23),
('', 86, 1, 51, 7, 5),
('', 87, 40, 4, 4, 24),
('', 97, 13, 53, 56, 11),
('', 114, 57, 12, 27, 11),
('', 116, 34, 9, 36, 17),
('', 123, 54, 23, 14, 26),
('', 125, 3, 48, 41, 4),
('', 126, 31, 56, 10, 8),
('', 131, 43, 57, 13, 59),
('', 146, 40, 24, 40, 60),
('', 151, 17, 5, 36, 54),
('1', 1, 49, 38, 12, 16);

-- --------------------------------------------------------

--
-- Table structure for table `restaurant_domain_request`
--

CREATE TABLE `restaurant_domain_request` (
  `request_uuid` char(60) COLLATE utf8_unicode_ci NOT NULL,
  `restaurant_uuid` char(60) COLLATE utf8_unicode_ci DEFAULT NULL,
  `domain` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `status` tinyint(4) DEFAULT 0,
  `created_by` bigint(20) DEFAULT NULL,
  `expire_at` date DEFAULT NULL,
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `restaurant_invoice`
--

CREATE TABLE `restaurant_invoice` (
  `invoice_uuid` varchar(60) COLLATE utf8_unicode_ci NOT NULL,
  `invoice_number` char(60) COLLATE utf8_unicode_ci NOT NULL,
  `restaurant_uuid` char(60) COLLATE utf8_unicode_ci NOT NULL,
  `payment_uuid` char(60) COLLATE utf8_unicode_ci DEFAULT NULL,
  `amount` decimal(10,3) NOT NULL,
  `currency_code` char(3) COLLATE utf8_unicode_ci DEFAULT 'KWD',
  `mail_sent` tinyint(1) DEFAULT 0,
  `invoice_status` tinyint(1) DEFAULT 0,
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `restaurant_payment_method`
--

CREATE TABLE `restaurant_payment_method` (
  `restaurant_uuid` char(60) COLLATE utf8_unicode_ci NOT NULL,
  `payment_method_id` int(11) NOT NULL,
  `status` tinyint(1) DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `restaurant_payment_method`
--

INSERT INTO `restaurant_payment_method` (`restaurant_uuid`, `payment_method_id`, `status`) VALUES
('1', 1, 1),
('10', 10, 1),
('11', 11, 1),
('12', 12, 1),
('13', 13, 1),
('14', 14, 1),
('15', 15, 1),
('16', 16, 1),
('17', 17, 1),
('18', 18, 1),
('19', 19, 1),
('2', 2, 1),
('20', 20, 1),
('21', 21, 1),
('22', 22, 1),
('23', 23, 1),
('24', 24, 1),
('25', 25, 1),
('26', 26, 1),
('27', 27, 1),
('28', 28, 1),
('29', 29, 1),
('3', 3, 1),
('30', 30, 1),
('31', 31, 1),
('32', 32, 1),
('33', 33, 1),
('34', 34, 1),
('35', 35, 1),
('36', 36, 1),
('37', 37, 1),
('38', 38, 1),
('39', 39, 1),
('4', 4, 1),
('40', 40, 1),
('41', 41, 1),
('42', 42, 1),
('43', 43, 1),
('44', 44, 1),
('45', 45, 1),
('46', 46, 1),
('47', 47, 1),
('48', 48, 1),
('49', 49, 1),
('5', 5, 1),
('50', 50, 1),
('6', 6, 1),
('7', 7, 1),
('8', 8, 1),
('9', 9, 1);

-- --------------------------------------------------------

--
-- Table structure for table `restaurant_theme`
--

CREATE TABLE `restaurant_theme` (
  `restaurant_uuid` char(60) COLLATE utf8_unicode_ci NOT NULL,
  `primary` char(60) COLLATE utf8_unicode_ci DEFAULT '#2B546A',
  `secondary` char(60) COLLATE utf8_unicode_ci DEFAULT '#3dc2ff',
  `tertiary` char(60) COLLATE utf8_unicode_ci DEFAULT '#5260ff',
  `light` char(60) COLLATE utf8_unicode_ci DEFAULT '#ffffff',
  `medium` char(60) COLLATE utf8_unicode_ci DEFAULT '#92949c',
  `dark` char(60) COLLATE utf8_unicode_ci DEFAULT '#222428'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `restaurant_theme`
--

INSERT INTO `restaurant_theme` (`restaurant_uuid`, `primary`, `secondary`, `tertiary`, `light`, `medium`, `dark`) VALUES
('1', 'blue', 'green', 'grey', '#ccc', 'violet', '#333'),
('10', 'blue', 'green', 'grey', '#ccc', 'violet', '#333'),
('11', 'blue', 'green', 'grey', '#ccc', 'violet', '#333'),
('12', 'blue', 'green', 'grey', '#ccc', 'violet', '#333'),
('13', 'blue', 'green', 'grey', '#ccc', 'violet', '#333'),
('14', 'blue', 'green', 'grey', '#ccc', 'violet', '#333'),
('15', 'blue', 'green', 'grey', '#ccc', 'violet', '#333'),
('16', 'blue', 'green', 'grey', '#ccc', 'violet', '#333'),
('17', 'blue', 'green', 'grey', '#ccc', 'violet', '#333'),
('18', 'blue', 'green', 'grey', '#ccc', 'violet', '#333'),
('19', 'blue', 'green', 'grey', '#ccc', 'violet', '#333'),
('2', 'blue', 'green', 'grey', '#ccc', 'violet', '#333'),
('20', 'blue', 'green', 'grey', '#ccc', 'violet', '#333'),
('21', 'blue', 'green', 'grey', '#ccc', 'violet', '#333'),
('22', 'blue', 'green', 'grey', '#ccc', 'violet', '#333'),
('23', 'blue', 'green', 'grey', '#ccc', 'violet', '#333'),
('24', 'blue', 'green', 'grey', '#ccc', 'violet', '#333'),
('25', 'blue', 'green', 'grey', '#ccc', 'violet', '#333'),
('26', 'blue', 'green', 'grey', '#ccc', 'violet', '#333'),
('27', 'blue', 'green', 'grey', '#ccc', 'violet', '#333'),
('28', 'blue', 'green', 'grey', '#ccc', 'violet', '#333'),
('29', 'blue', 'green', 'grey', '#ccc', 'violet', '#333'),
('3', 'blue', 'green', 'grey', '#ccc', 'violet', '#333'),
('30', 'blue', 'green', 'grey', '#ccc', 'violet', '#333'),
('31', 'blue', 'green', 'grey', '#ccc', 'violet', '#333'),
('32', 'blue', 'green', 'grey', '#ccc', 'violet', '#333'),
('33', 'blue', 'green', 'grey', '#ccc', 'violet', '#333'),
('34', 'blue', 'green', 'grey', '#ccc', 'violet', '#333'),
('35', 'blue', 'green', 'grey', '#ccc', 'violet', '#333'),
('36', 'blue', 'green', 'grey', '#ccc', 'violet', '#333'),
('37', 'blue', 'green', 'grey', '#ccc', 'violet', '#333'),
('38', 'blue', 'green', 'grey', '#ccc', 'violet', '#333'),
('39', 'blue', 'green', 'grey', '#ccc', 'violet', '#333'),
('4', 'blue', 'green', 'grey', '#ccc', 'violet', '#333'),
('40', 'blue', 'green', 'grey', '#ccc', 'violet', '#333'),
('41', 'blue', 'green', 'grey', '#ccc', 'violet', '#333'),
('42', 'blue', 'green', 'grey', '#ccc', 'violet', '#333'),
('43', 'blue', 'green', 'grey', '#ccc', 'violet', '#333'),
('44', 'blue', 'green', 'grey', '#ccc', 'violet', '#333'),
('45', 'blue', 'green', 'grey', '#ccc', 'violet', '#333'),
('46', 'blue', 'green', 'grey', '#ccc', 'violet', '#333'),
('47', 'blue', 'green', 'grey', '#ccc', 'violet', '#333'),
('48', 'blue', 'green', 'grey', '#ccc', 'violet', '#333'),
('49', 'blue', 'green', 'grey', '#ccc', 'violet', '#333'),
('5', 'blue', 'green', 'grey', '#ccc', 'violet', '#333'),
('50', 'blue', 'green', 'grey', '#ccc', 'violet', '#333'),
('6', 'blue', 'green', 'grey', '#ccc', 'violet', '#333'),
('7', 'blue', 'green', 'grey', '#ccc', 'violet', '#333'),
('8', 'blue', 'green', 'grey', '#ccc', 'violet', '#333'),
('9', 'blue', 'green', 'grey', '#ccc', 'violet', '#333');

-- --------------------------------------------------------

--
-- Table structure for table `restaurant_upload`
--

CREATE TABLE `restaurant_upload` (
  `upload_uuid` char(60) COLLATE utf8_unicode_ci NOT NULL,
  `restaurant_uuid` char(60) COLLATE utf8_unicode_ci NOT NULL,
  `path` varchar(255) COLLATE utf8_unicode_ci DEFAULT '' COMMENT 'path in store built/www',
  `filename` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `content` text COLLATE utf8_unicode_ci DEFAULT NULL,
  `created_by` bigint(20) DEFAULT NULL,
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `setting`
--

CREATE TABLE `setting` (
  `setting_uuid` char(60) COLLATE utf8_unicode_ci NOT NULL,
  `restaurant_uuid` char(60) COLLATE utf8_unicode_ci DEFAULT NULL,
  `code` varchar(128) COLLATE utf8_unicode_ci NOT NULL COMMENT 'module identifier',
  `key` varchar(128) COLLATE utf8_unicode_ci NOT NULL,
  `value` text COLLATE utf8_unicode_ci DEFAULT NULL,
  `serialized` tinyint(1) DEFAULT 0,
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `setting`
--

INSERT INTO `setting` (`setting_uuid`, `restaurant_uuid`, `code`, `key`, `value`, `serialized`, `created_at`, `updated_at`) VALUES
('setting_fd3f6480-c7e1-11ed-9265-d85ed3a264df', NULL, 'EventManager', 'Mixpanel-Status', 'enabled', 0, '2023-03-21 17:44:54', '2023-03-21 17:44:54'),
('setting_fd3f9431-c7e1-11ed-9265-d85ed3a264df', NULL, 'EventManager', 'Segment-Status', 'enabled', 0, '2023-03-21 17:44:54', '2023-03-21 17:44:54'),
('setting_fd400c93-c7e1-11ed-9265-d85ed3a264df', NULL, 'EventManager', 'Mixpanel-Key', 'ac62dbe81767f8871f754c7bdf6669d6', 0, '2023-03-21 17:44:54', '2023-03-21 17:44:54'),
('setting_fd40274b-c7e1-11ed-9265-d85ed3a264df', NULL, 'EventManager', 'Segment-Key', '7oEpdGxjwBMlwBQYuXD7NpYWp4HzDJWh', 0, '2023-03-21 17:44:54', '2023-03-21 17:44:54'),
('setting_fd4040f8-c7e1-11ed-9265-d85ed3a264df', NULL, 'EventManager', 'Segment-Key-Wallet', '7oEpdGxjwBMlwBQYuXD7NpYWp4HzDJWh', 0, '2023-03-21 17:44:54', '2023-03-21 17:44:54');

-- --------------------------------------------------------

--
-- Table structure for table `staff`
--

CREATE TABLE `staff` (
  `staff_id` bigint(20) NOT NULL,
  `staff_name` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `staff_email` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `staff_auth_key` varchar(32) COLLATE utf8_unicode_ci NOT NULL,
  `staff_password_hash` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `staff_password_reset_token` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `staff_status` smallint(6) NOT NULL DEFAULT 10,
  `staff_created_at` datetime NOT NULL,
  `staff_updated_at` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `staff`
--

INSERT INTO `staff` (`staff_id`, `staff_name`, `staff_email`, `staff_auth_key`, `staff_password_hash`, `staff_password_reset_token`, `staff_status`, `staff_created_at`, `staff_updated_at`) VALUES
(1, 'Krishna', 'kk@bawes.net', '', '$2y$13$c7V4VAhkeh7nZRlY6n0OqufqhTyaXpE0Ly761erkOKINMFhpmGQoG', NULL, 10, '2022-07-10 11:47:03', '2022-07-10 11:47:03'),
(2, 'Anil', 'anilkumar.dhiman1@gmail.com', '', '$2y$13$gvdm0i.f2mZg7Y05NzcJpessjt7oEGclyO0b50s/lr7XSRFS34FLe', NULL, 10, '2022-07-10 11:47:03', '2022-07-10 11:47:03'),
(3, 'Customer Service Agent #1', 'cs1@plugn.io', '', '$2y$13$k.9H1yftt04.LqigN4wEAeM6xovixxpD/eER75sIBe1NLtmSafxjm', NULL, 10, '2022-07-10 11:47:03', '2022-07-10 11:47:03'),
(4, 'Customer Service Agent #2', 'cs2@plugn.io', '', '$2y$13$1G4eOcULk/ETnM46pMm20OpCNi2F6NuS0uCJWrxj7veYtr.msNl1O', NULL, 10, '2022-07-10 11:47:03', '2022-07-10 11:47:03');

-- --------------------------------------------------------

--
-- Table structure for table `staff_token`
--

CREATE TABLE `staff_token` (
  `token_uuid` char(60) COLLATE utf8_unicode_ci NOT NULL,
  `staff_id` bigint(20) NOT NULL,
  `token_value` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `token_device` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `token_device_id` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `token_status` smallint(6) DEFAULT NULL,
  `token_last_used_datetime` datetime DEFAULT NULL,
  `token_expiry_datetime` datetime DEFAULT NULL,
  `token_created_datetime` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `store_updates`
--

CREATE TABLE `store_updates` (
  `store_update_uuid` varchar(60) COLLATE utf8_unicode_ci NOT NULL,
  `restaurant_uuid` char(60) COLLATE utf8_unicode_ci DEFAULT NULL,
  `title` varchar(100) COLLATE utf8_unicode_ci NOT NULL,
  `content` text COLLATE utf8_unicode_ci NOT NULL,
  `title_ar` varchar(100) COLLATE utf8_unicode_ci NOT NULL,
  `content_ar` text COLLATE utf8_unicode_ci NOT NULL,
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `store_web_link`
--

CREATE TABLE `store_web_link` (
  `web_link_id` bigint(20) DEFAULT NULL,
  `restaurant_uuid` char(60) COLLATE utf8_unicode_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `store_web_link`
--

INSERT INTO `store_web_link` (`web_link_id`, `restaurant_uuid`) VALUES
(1, '1'),
(2, '2'),
(3, '3'),
(4, '4'),
(5, '5'),
(6, '6'),
(7, '7'),
(8, '8'),
(9, '9'),
(10, '10'),
(11, '11'),
(12, '12'),
(13, '13'),
(14, '14'),
(15, '15'),
(16, '16'),
(17, '17'),
(18, '18'),
(19, '19'),
(20, '20'),
(21, '21'),
(22, '22'),
(23, '23'),
(24, '24'),
(25, '25'),
(26, '26'),
(27, '27'),
(28, '28'),
(29, '29'),
(30, '30'),
(31, '31'),
(32, '32'),
(33, '33'),
(34, '34'),
(35, '35'),
(36, '36'),
(37, '37'),
(38, '38'),
(39, '39'),
(40, '40'),
(41, '41'),
(42, '42'),
(43, '43'),
(44, '44'),
(45, '45'),
(46, '46'),
(47, '47'),
(48, '48'),
(49, '49'),
(50, '50');

-- --------------------------------------------------------

--
-- Table structure for table `subscription`
--

CREATE TABLE `subscription` (
  `subscription_uuid` char(60) COLLATE utf8_unicode_ci NOT NULL,
  `payment_method_id` int(11) DEFAULT NULL,
  `payment_uuid` char(36) COLLATE utf8_unicode_ci DEFAULT NULL,
  `restaurant_uuid` char(60) COLLATE utf8_unicode_ci DEFAULT NULL,
  `plan_id` int(11) NOT NULL,
  `subscription_status` tinyint(1) UNSIGNED DEFAULT 0,
  `notified_email` tinyint(1) UNSIGNED NOT NULL DEFAULT 0,
  `subscription_start_at` datetime NOT NULL,
  `subscription_end_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `subscription`
--

INSERT INTO `subscription` (`subscription_uuid`, `payment_method_id`, `payment_uuid`, `restaurant_uuid`, `plan_id`, `subscription_status`, `notified_email`, `subscription_start_at`, `subscription_end_at`) VALUES
('1', 1, '1', '1', 1, 10, 1, '1995-06-22 23:25:47', '2018-08-30 13:37:19'),
('10', 10, '10', '10', 10, 10, 1, '2021-04-22 12:34:59', '2002-09-17 16:28:36'),
('11', 11, '11', '11', 11, 10, 1, '1988-04-17 15:23:04', '2019-06-28 05:55:31'),
('12', 12, '12', '12', 12, 10, 1, '1985-09-30 02:14:15', '2017-10-18 10:30:53'),
('13', 13, '13', '13', 13, 10, 1, '2005-11-30 15:53:24', '2006-01-16 09:10:57'),
('14', 14, '14', '14', 14, 10, 1, '2012-03-01 01:36:34', '2010-01-25 20:27:46'),
('15', 15, '15', '15', 15, 10, 1, '2015-02-03 23:23:32', '1982-09-26 09:22:04'),
('16', 16, '16', '16', 16, 10, 1, '2019-04-08 02:00:02', '1980-02-06 07:14:19'),
('17', 17, '17', '17', 17, 10, 1, '1990-01-24 19:49:13', '1992-09-08 16:51:26'),
('18', 18, '18', '18', 18, 10, 1, '1998-12-10 05:02:12', '2009-01-11 08:36:41'),
('19', 19, '19', '19', 19, 10, 1, '1974-03-25 11:29:08', '1993-07-02 10:35:06'),
('2', 2, '2', '2', 2, 10, 1, '1975-11-21 03:24:41', '2001-08-20 17:45:00'),
('20', 20, '20', '20', 20, 10, 1, '1975-01-09 14:36:52', '1988-12-14 14:45:38'),
('21', 21, '21', '21', 21, 10, 1, '1979-11-28 00:12:22', '2009-07-12 21:54:39'),
('22', 22, '22', '22', 22, 10, 1, '1982-09-10 03:23:08', '1973-12-11 19:33:55'),
('23', 23, '23', '23', 23, 10, 1, '1998-09-09 04:55:20', '1998-08-27 16:49:48'),
('24', 24, '24', '24', 24, 10, 1, '1995-02-16 17:46:40', '2001-01-02 14:49:56'),
('25', 25, '25', '25', 25, 10, 1, '2020-03-04 11:46:11', '2001-12-24 13:06:54'),
('26', 26, '26', '26', 26, 10, 1, '2005-12-06 09:48:45', '2008-02-13 20:09:26'),
('27', 27, '27', '27', 27, 10, 1, '2000-09-25 18:37:02', '2004-06-14 18:42:21'),
('28', 28, '28', '28', 28, 10, 1, '1978-10-17 10:30:40', '1995-03-11 07:59:24'),
('29', 29, '29', '29', 29, 10, 1, '1970-05-25 18:41:29', '1973-04-30 20:35:26'),
('3', 3, '3', '3', 3, 10, 1, '2018-06-09 17:07:32', '1997-09-17 04:07:49'),
('30', 30, '30', '30', 30, 10, 1, '1991-09-28 04:48:45', '1975-05-08 14:42:49'),
('31', 31, '31', '31', 31, 10, 1, '2019-08-27 00:04:01', '1982-02-07 07:29:09'),
('32', 32, '32', '32', 32, 10, 1, '2020-09-13 21:43:03', '2001-10-11 16:19:54'),
('33', 33, '33', '33', 33, 10, 1, '1988-04-02 14:18:50', '1990-09-09 23:03:23'),
('34', 34, '34', '34', 34, 10, 1, '2003-06-15 16:04:02', '2011-01-19 08:28:10'),
('35', 35, '35', '35', 35, 10, 1, '1985-04-14 01:25:09', '1984-06-09 16:04:25'),
('36', 36, '36', '36', 36, 10, 1, '1992-10-24 05:31:36', '2017-02-10 04:43:55'),
('37', 37, '37', '37', 37, 10, 1, '1975-08-24 20:26:11', '2012-01-14 04:06:56'),
('38', 38, '38', '38', 38, 10, 1, '1993-07-25 17:21:52', '1992-04-09 11:39:42'),
('39', 39, '39', '39', 39, 10, 1, '1971-09-13 15:30:45', '1991-12-24 23:31:15'),
('4', 4, '4', '4', 4, 10, 1, '2005-06-30 07:13:45', '2001-11-28 14:42:30'),
('40', 40, '40', '40', 40, 10, 1, '1992-06-29 08:25:47', '2010-05-17 10:05:50'),
('41', 41, '41', '41', 41, 10, 1, '2011-09-21 05:17:05', '1983-07-26 01:59:44'),
('42', 42, '42', '42', 42, 10, 1, '2003-10-31 19:58:30', '1990-08-09 06:47:06'),
('43', 43, '43', '43', 43, 10, 1, '1997-06-29 18:13:47', '2002-04-19 05:43:19'),
('44', 44, '44', '44', 44, 10, 1, '1988-07-25 07:39:40', '1987-04-20 20:01:38'),
('45', 45, '45', '45', 45, 10, 1, '1994-07-16 17:29:19', '2006-12-29 01:38:15'),
('46', 46, '46', '46', 46, 10, 1, '1995-09-20 08:24:14', '1997-04-07 17:06:23'),
('47', 47, '47', '47', 47, 10, 1, '1990-11-23 06:48:01', '2001-12-12 01:19:04'),
('48', 48, '48', '48', 48, 10, 1, '1987-11-15 04:46:23', '1993-12-16 22:52:02'),
('49', 49, '49', '49', 49, 10, 1, '2017-03-10 16:27:56', '1992-07-17 06:14:32'),
('5', 5, '5', '5', 5, 10, 1, '1979-05-15 23:01:54', '2002-06-12 05:12:41'),
('50', 50, '50', '50', 50, 10, 1, '1990-07-22 04:53:28', '2012-07-25 09:17:39'),
('6', 6, '6', '6', 6, 10, 1, '1973-04-05 11:32:19', '2003-11-28 04:07:56'),
('7', 7, '7', '7', 7, 10, 1, '1994-03-27 06:06:35', '1986-02-21 15:21:53'),
('8', 8, '8', '8', 8, 10, 1, '2020-06-30 13:55:27', '2020-10-15 03:30:16'),
('9', 9, '9', '9', 9, 10, 1, '2010-06-13 13:38:59', '2008-07-18 04:31:51');

-- --------------------------------------------------------

--
-- Table structure for table `subscription_payment`
--

CREATE TABLE `subscription_payment` (
  `payment_uuid` char(36) COLLATE utf8_unicode_ci NOT NULL,
  `restaurant_uuid` char(60) COLLATE utf8_unicode_ci NOT NULL,
  `subscription_uuid` char(60) COLLATE utf8_unicode_ci NOT NULL,
  `payment_gateway_order_id` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `payment_gateway_transaction_id` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `payment_mode` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `payment_current_status` text COLLATE utf8_unicode_ci DEFAULT NULL,
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
  `received_callback` tinyint(1) NOT NULL DEFAULT 0,
  `response_message` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `payment_token` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `partner_fee` decimal(10,3) UNSIGNED DEFAULT 0.000,
  `payout_status` smallint(6) DEFAULT 0,
  `partner_payout_uuid` char(35) COLLATE utf8_unicode_ci DEFAULT NULL,
  `is_sandbox` tinyint(1) DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `subscription_payment`
--

INSERT INTO `subscription_payment` (`payment_uuid`, `restaurant_uuid`, `subscription_uuid`, `payment_gateway_order_id`, `payment_gateway_transaction_id`, `payment_mode`, `payment_current_status`, `payment_amount_charged`, `payment_net_amount`, `payment_gateway_fee`, `payment_udf1`, `payment_udf2`, `payment_udf3`, `payment_udf4`, `payment_udf5`, `payment_created_at`, `payment_updated_at`, `received_callback`, `response_message`, `payment_token`, `partner_fee`, `payout_status`, `partner_payout_uuid`, `is_sandbox`) VALUES
('1', '1', '1', '1', '1', 'KNET', 'captured', 5311, 5309, 2, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1, 'Harum qui et amet ex voluptatum.', 'animi', '0.000', 0, NULL, 0),
('10', '10', '10', '10', '10', 'KNET', 'captured', 3037, 3035, 2, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1, 'Corporis ea fugiat et.', 'necessitatibus', '0.000', 0, NULL, 0),
('11', '11', '11', '11', '11', 'KNET', 'captured', 7286, 7284, 2, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1, 'Nam quia distinctio repellat repellendus nesciunt omnis.', 'cupiditate', '0.000', 0, NULL, 0),
('12', '12', '12', '12', '12', 'KNET', 'captured', 1716, 1714, 2, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1, 'Quia veniam illo sit inventore.', 'ut', '0.000', 0, NULL, 0),
('13', '13', '13', '13', '13', 'KNET', 'captured', 6835, 6833, 2, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1, 'Qui quia ex et impedit.', 'magnam', '0.000', 0, NULL, 0),
('14', '14', '14', '14', '14', 'KNET', 'captured', 1609, 1607, 2, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1, 'Veniam rerum dolores deleniti dignissimos sit distinctio.', 'quos', '0.000', 0, NULL, 0),
('15', '15', '15', '15', '15', 'KNET', 'captured', 6211, 6209, 2, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1, 'Vero sunt exercitationem dolor id maiores ea occaecati.', 'ut', '0.000', 0, NULL, 0),
('16', '16', '16', '16', '16', 'KNET', 'captured', 2498, 2496, 2, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1, 'Tempore neque aperiam mollitia tenetur.', 'perferendis', '0.000', 0, NULL, 0),
('17', '17', '17', '17', '17', 'KNET', 'captured', 3705, 3703, 2, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1, 'Doloremque dolores debitis soluta tenetur odit facilis sunt.', 'accusamus', '0.000', 0, NULL, 0),
('18', '18', '18', '18', '18', 'KNET', 'captured', 7781, 7779, 2, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1, 'Labore nostrum distinctio ut est iste quas minus.', 'vel', '0.000', 0, NULL, 0),
('19', '19', '19', '19', '19', 'KNET', 'captured', 6860, 6858, 2, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1, 'Suscipit sed temporibus sapiente sed non.', 'veniam', '0.000', 0, NULL, 0),
('2', '2', '2', '2', '2', 'KNET', 'captured', 5929, 5927, 2, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1, 'Aperiam cum sit nesciunt cum neque quo explicabo.', 'dolorem', '0.000', 0, NULL, 0),
('20', '20', '20', '20', '20', 'KNET', 'captured', 5295, 5293, 2, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1, 'Ipsum qui nesciunt tempora iste.', 'distinctio', '0.000', 0, NULL, 0),
('21', '21', '21', '21', '21', 'KNET', 'captured', 4180, 4178, 2, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1, 'Doloribus et nemo eum rerum dignissimos sint voluptates et.', 'alias', '0.000', 0, NULL, 0),
('22', '22', '22', '22', '22', 'KNET', 'captured', 8177, 8175, 2, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1, 'Facilis ut ut odio.', 'voluptate', '0.000', 0, NULL, 0),
('23', '23', '23', '23', '23', 'KNET', 'captured', 7467, 7465, 2, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1, 'Est nesciunt consectetur tempora molestiae.', 'temporibus', '0.000', 0, NULL, 0),
('24', '24', '24', '24', '24', 'KNET', 'captured', 6935, 6933, 2, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1, 'Et enim libero architecto quidem.', 'earum', '0.000', 0, NULL, 0),
('25', '25', '25', '25', '25', 'KNET', 'captured', 8894, 8892, 2, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1, 'Occaecati enim aut illo sit vel aut.', 'aut', '0.000', 0, NULL, 0),
('26', '26', '26', '26', '26', 'KNET', 'captured', 8510, 8508, 2, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1, 'Delectus ut qui possimus odit similique possimus.', 'inventore', '0.000', 0, NULL, 0),
('27', '27', '27', '27', '27', 'KNET', 'captured', 6930, 6928, 2, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1, 'Vitae voluptas sunt magnam vel modi eum libero nisi.', 'aut', '0.000', 0, NULL, 0),
('28', '28', '28', '28', '28', 'KNET', 'captured', 7953, 7951, 2, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1, 'Rem cum voluptates voluptates molestiae provident commodi qui.', 'qui', '0.000', 0, NULL, 0),
('29', '29', '29', '29', '29', 'KNET', 'captured', 7553, 7551, 2, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1, 'Sunt et beatae voluptatum cum.', 'perspiciatis', '0.000', 0, NULL, 0),
('3', '3', '3', '3', '3', 'KNET', 'captured', 2027, 2025, 2, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1, 'Ab enim reiciendis dolorum recusandae cupiditate et repellendus.', 'fugiat', '0.000', 0, NULL, 0),
('30', '30', '30', '30', '30', 'KNET', 'captured', 5091, 5089, 2, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1, 'Deserunt dolores deserunt veniam.', 'iste', '0.000', 0, NULL, 0),
('31', '31', '31', '31', '31', 'KNET', 'captured', 2778, 2776, 2, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1, 'Ut dolor voluptates perferendis omnis porro nobis quas.', 'animi', '0.000', 0, NULL, 0),
('32', '32', '32', '32', '32', 'KNET', 'captured', 3638, 3636, 2, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1, 'Ea consequatur ab et voluptas iure voluptatem.', 'sed', '0.000', 0, NULL, 0),
('33', '33', '33', '33', '33', 'KNET', 'captured', 5396, 5394, 2, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1, 'Consectetur maxime ea voluptatum ut nam iusto deserunt ut.', 'illo', '0.000', 0, NULL, 0),
('34', '34', '34', '34', '34', 'KNET', 'captured', 5528, 5526, 2, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1, 'Voluptatem beatae est cumque nostrum et officiis.', 'in', '0.000', 0, NULL, 0),
('35', '35', '35', '35', '35', 'KNET', 'captured', 5998, 5996, 2, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1, 'Vel et veniam tempore maiores.', 'officiis', '0.000', 0, NULL, 0),
('36', '36', '36', '36', '36', 'KNET', 'captured', 6023, 6021, 2, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1, 'Vel quis eos est eos aspernatur.', 'molestias', '0.000', 0, NULL, 0),
('37', '37', '37', '37', '37', 'KNET', 'captured', 1526, 1524, 2, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1, 'Optio porro et sint ad.', 'eius', '0.000', 0, NULL, 0),
('38', '38', '38', '38', '38', 'KNET', 'captured', 7156, 7154, 2, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1, 'Ut eos necessitatibus consequatur odio.', 'distinctio', '0.000', 0, NULL, 0),
('39', '39', '39', '39', '39', 'KNET', 'captured', 8041, 8039, 2, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1, 'Et est molestiae nihil.', 'ratione', '0.000', 0, NULL, 0),
('4', '4', '4', '4', '4', 'KNET', 'captured', 5385, 5383, 2, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1, 'Et et ea sit voluptate mollitia.', 'hic', '0.000', 0, NULL, 0),
('40', '40', '40', '40', '40', 'KNET', 'captured', 2977, 2975, 2, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1, 'Aut aut nam quis sed expedita minima tenetur.', 'sunt', '0.000', 0, NULL, 0),
('41', '41', '41', '41', '41', 'KNET', 'captured', 5704, 5702, 2, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1, 'Perferendis eum quae nihil est ut facilis vitae.', 'sit', '0.000', 0, NULL, 0),
('42', '42', '42', '42', '42', 'KNET', 'captured', 5427, 5425, 2, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1, 'Assumenda dolorem aut corporis.', 'qui', '0.000', 0, NULL, 0),
('43', '43', '43', '43', '43', 'KNET', 'captured', 2421, 2419, 2, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1, 'Eos voluptatum sequi sequi et id.', 'inventore', '0.000', 0, NULL, 0),
('44', '44', '44', '44', '44', 'KNET', 'captured', 5998, 5996, 2, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1, 'Suscipit consectetur voluptates repellat sequi nam asperiores corrupti.', 'dolor', '0.000', 0, NULL, 0),
('45', '45', '45', '45', '45', 'KNET', 'captured', 5600, 5598, 2, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1, 'Quia et quia sunt.', 'ea', '0.000', 0, NULL, 0),
('46', '46', '46', '46', '46', 'KNET', 'captured', 3498, 3496, 2, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1, 'Omnis consequatur cum veniam voluptatem possimus pariatur voluptatem.', 'est', '0.000', 0, NULL, 0),
('47', '47', '47', '47', '47', 'KNET', 'captured', 5323, 5321, 2, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1, 'Est et dolorum nisi et rerum.', 'dolor', '0.000', 0, NULL, 0),
('48', '48', '48', '48', '48', 'KNET', 'captured', 3258, 3256, 2, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1, 'Eaque id dolores autem totam cum voluptatem.', 'maxime', '0.000', 0, NULL, 0),
('49', '49', '49', '49', '49', 'KNET', 'captured', 5069, 5067, 2, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1, 'Accusantium esse exercitationem minus sunt.', 'magnam', '0.000', 0, NULL, 0),
('5', '5', '5', '5', '5', 'KNET', 'captured', 3648, 3646, 2, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1, 'Facilis rerum quaerat quod aperiam quis in.', 'et', '0.000', 0, NULL, 0),
('50', '50', '50', '50', '50', 'KNET', 'captured', 5077, 5075, 2, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1, 'Impedit voluptatem dolorum nihil.', 'tempore', '0.000', 0, NULL, 0),
('6', '6', '6', '6', '6', 'KNET', 'captured', 7034, 7032, 2, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1, 'Minima iusto odit consequatur.', 'rerum', '0.000', 0, NULL, 0),
('7', '7', '7', '7', '7', 'KNET', 'captured', 5796, 5794, 2, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1, 'Vero maiores consequatur consectetur.', 'ipsum', '0.000', 0, NULL, 0),
('8', '8', '8', '8', '8', 'KNET', 'captured', 6391, 6389, 2, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1, 'Veritatis maxime itaque nihil.', 'possimus', '0.000', 0, NULL, 0),
('9', '9', '9', '9', '9', 'KNET', 'captured', 4258, 4256, 2, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1, 'Delectus laboriosam earum consequuntur rerum perspiciatis.', 'consequatur', '0.000', 0, NULL, 0);

-- --------------------------------------------------------

--
-- Table structure for table `tap_queue`
--

CREATE TABLE `tap_queue` (
  `tap_queue_id` int(11) NOT NULL,
  `restaurant_uuid` char(60) COLLATE utf8_unicode_ci NOT NULL,
  `queue_status` smallint(6) DEFAULT 1,
  `queue_created_at` datetime DEFAULT NULL,
  `queue_updated_at` datetime DEFAULT NULL,
  `queue_start_at` datetime DEFAULT NULL,
  `queue_end_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `tap_queue`
--

INSERT INTO `tap_queue` (`tap_queue_id`, `restaurant_uuid`, `queue_status`, `queue_created_at`, `queue_updated_at`, `queue_start_at`, `queue_end_at`) VALUES
(1, '1', 2, '1972-05-21 15:38:54', '1993-07-13 05:11:29', '1980-02-24 09:37:24', '2013-01-29 08:53:26'),
(2, '10', 2, '1974-08-10 17:22:15', '1995-05-16 09:29:10', '1983-05-11 22:26:26', '2005-10-03 15:09:25'),
(3, '11', 1, '2017-11-05 12:31:11', '2019-08-18 10:00:07', '2015-07-13 04:12:34', '1993-07-08 18:14:38'),
(4, '12', 2, '1998-02-19 06:10:58', '2015-08-03 06:33:12', '1981-07-14 22:45:30', '1995-11-17 10:19:41'),
(5, '13', 1, '1976-01-26 03:51:40', '2018-12-20 05:36:37', '1975-11-20 16:04:42', '2002-02-28 08:01:29'),
(6, '14', 1, '1978-05-14 11:10:04', '2003-08-02 01:12:05', '1976-07-14 17:25:49', '2005-02-21 06:38:50'),
(7, '15', 1, '2002-03-03 04:44:44', '1993-07-04 18:12:46', '2020-07-04 10:03:13', '2007-08-21 11:35:28'),
(8, '16', 3, '1972-06-03 09:31:15', '1973-01-14 04:28:21', '2014-09-16 00:54:25', '1983-07-15 08:29:15'),
(9, '17', 2, '1988-07-16 03:06:37', '2014-08-03 08:33:09', '2000-05-20 07:02:29', '1995-03-27 04:02:19'),
(10, '18', 3, '1972-11-25 21:00:03', '2007-07-05 17:17:42', '1981-12-31 12:22:20', '2006-02-05 08:28:16'),
(11, '19', 1, '2007-08-01 01:20:49', '2003-04-18 12:20:10', '1981-09-11 05:08:53', '2004-04-21 18:49:33'),
(12, '2', 1, '2019-05-07 10:18:47', '1988-02-28 20:16:24', '1990-07-02 06:06:08', '1990-07-21 14:02:30'),
(13, '20', 2, '1999-01-01 16:21:54', '2015-04-27 16:08:01', '1997-09-27 02:14:30', '2000-03-11 05:58:44'),
(14, '21', 3, '1984-09-07 20:21:04', '1985-11-29 04:27:49', '1985-06-03 18:28:52', '1994-01-19 23:56:07'),
(15, '22', 2, '2014-03-26 13:42:22', '1995-04-03 22:59:15', '1984-09-18 06:51:36', '1985-03-12 14:18:59'),
(16, '23', 3, '1999-05-20 14:30:30', '2017-05-11 10:42:51', '2013-06-15 09:22:54', '1991-05-02 02:44:01'),
(17, '24', 3, '1976-09-09 11:24:06', '1979-10-10 11:52:03', '1984-12-28 04:52:24', '2007-08-28 13:24:40'),
(18, '25', 2, '1985-12-11 21:31:03', '2009-07-05 22:37:57', '1997-07-16 04:29:21', '2000-03-18 08:11:00'),
(19, '26', 1, '1983-09-27 06:08:05', '1971-09-01 05:49:06', '1993-04-21 20:53:58', '2013-11-12 00:05:54'),
(20, '27', 1, '2001-03-02 11:51:04', '1977-06-26 13:57:43', '1987-11-05 22:37:16', '1985-05-31 12:40:00'),
(21, '28', 1, '2008-11-05 03:21:49', '1992-01-06 06:22:23', '1979-04-05 20:35:06', '1997-06-05 11:37:26'),
(22, '29', 3, '2006-05-14 13:06:52', '2020-09-08 15:51:05', '1981-02-24 02:02:16', '1973-09-19 14:52:34'),
(23, '3', 3, '2015-01-31 20:06:33', '2004-10-05 23:45:58', '1986-12-09 02:34:12', '1997-12-04 14:22:14'),
(24, '30', 1, '1989-08-25 07:12:25', '2001-04-22 06:53:20', '2004-09-12 10:48:29', '1974-10-13 13:41:14'),
(25, '31', 2, '1982-05-13 18:16:24', '2012-06-19 04:47:54', '2018-06-19 20:42:00', '1987-06-07 08:18:57'),
(26, '32', 1, '2000-10-27 19:02:25', '1995-06-22 03:36:25', '2019-07-22 18:45:12', '1976-02-20 01:08:24'),
(27, '33', 1, '2020-08-08 12:43:16', '1998-05-11 00:44:58', '2010-12-10 23:02:45', '1995-02-23 02:29:44'),
(28, '34', 2, '2005-07-01 15:44:45', '1991-02-16 22:58:38', '2018-02-19 16:10:54', '1981-09-10 06:39:14'),
(29, '35', 2, '1991-12-16 04:35:10', '1997-06-28 15:33:02', '1996-12-19 19:33:31', '1996-01-14 12:29:18'),
(30, '36', 3, '1972-09-04 04:36:07', '2001-11-10 07:18:38', '2003-06-12 14:38:00', '1979-03-12 20:25:14'),
(31, '37', 2, '2007-12-29 14:09:47', '1981-06-12 22:34:50', '1984-02-22 02:17:02', '1976-05-27 08:35:47'),
(32, '38', 3, '2018-03-31 22:29:05', '2002-09-22 20:49:19', '1985-07-29 01:23:40', '1971-12-11 10:02:10'),
(33, '39', 3, '1972-07-02 03:32:31', '1996-02-09 22:57:40', '1979-03-31 09:47:19', '1982-06-20 06:42:57'),
(34, '4', 2, '2012-08-11 02:48:33', '1989-05-12 02:57:19', '1997-12-28 00:46:59', '2018-07-20 08:24:33'),
(35, '40', 3, '1970-02-16 20:11:13', '1998-03-13 19:56:16', '1978-12-14 14:11:01', '1971-08-02 06:21:07'),
(36, '41', 2, '1982-11-16 01:30:07', '2011-10-14 00:47:12', '2008-05-29 15:55:21', '1978-06-13 10:01:07'),
(37, '42', 1, '1995-03-28 20:55:15', '1986-02-26 15:47:02', '1987-11-26 15:38:11', '2015-12-03 21:53:40'),
(38, '43', 3, '2015-08-29 02:00:45', '2005-01-15 19:43:25', '1984-10-18 17:37:47', '1972-04-14 14:45:35'),
(39, '44', 1, '2020-01-26 01:01:38', '1989-12-16 07:16:23', '2019-04-06 07:14:51', '1971-09-02 13:58:23'),
(40, '45', 3, '2007-03-06 15:56:18', '1970-11-10 00:30:52', '2019-06-11 23:10:14', '1987-11-04 12:20:55'),
(41, '46', 1, '2010-02-15 23:44:29', '1997-09-12 15:17:39', '2020-10-06 08:22:30', '1990-01-19 11:49:00'),
(42, '47', 3, '2011-10-01 13:55:38', '1993-04-06 23:34:20', '2017-05-15 06:27:02', '1983-07-26 00:58:32'),
(43, '48', 2, '2018-07-09 02:13:38', '1984-07-13 06:25:53', '1983-10-29 10:15:37', '2014-10-28 23:30:17'),
(44, '49', 2, '1984-11-14 04:22:34', '2008-12-03 10:16:28', '2005-10-29 22:46:07', '2007-10-07 17:56:25'),
(45, '5', 3, '2018-08-06 17:32:26', '2001-01-01 16:10:31', '1991-10-08 21:06:00', '1993-05-01 07:50:05'),
(46, '50', 1, '1981-02-02 02:02:08', '1982-03-13 08:12:20', '2011-12-14 14:01:18', '2001-08-25 00:00:51'),
(47, '6', 3, '2014-12-09 03:35:06', '1997-11-09 11:58:26', '1976-05-05 16:53:56', '1992-07-13 00:55:32'),
(48, '7', 3, '1985-10-06 06:05:41', '1973-11-18 19:34:19', '1970-12-17 13:32:41', '2013-10-20 10:15:16'),
(49, '8', 1, '1999-07-17 18:01:20', '1982-09-05 23:13:04', '1972-04-29 14:23:02', '1995-02-02 02:29:01'),
(50, '9', 3, '1983-01-23 09:47:36', '1979-08-18 11:47:55', '1989-11-08 14:56:22', '1979-12-16 20:18:49');

-- --------------------------------------------------------

--
-- Table structure for table `ticket`
--

CREATE TABLE `ticket` (
  `ticket_uuid` char(60) COLLATE utf8_unicode_ci NOT NULL,
  `restaurant_uuid` char(60) COLLATE utf8_unicode_ci DEFAULT NULL,
  `agent_id` bigint(20) DEFAULT NULL,
  `staff_id` bigint(20) DEFAULT NULL,
  `ticket_detail` text COLLATE utf8_unicode_ci DEFAULT NULL,
  `ticket_status` smallint(1) DEFAULT 0,
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `ticket_attachment`
--

CREATE TABLE `ticket_attachment` (
  `ticket_attachment_uuid` char(60) COLLATE utf8_unicode_ci NOT NULL,
  `ticket_uuid` char(60) COLLATE utf8_unicode_ci DEFAULT NULL,
  `attachment_uuid` char(60) COLLATE utf8_unicode_ci DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `ticket_comment`
--

CREATE TABLE `ticket_comment` (
  `ticket_comment_uuid` char(60) COLLATE utf8_unicode_ci NOT NULL,
  `ticket_uuid` char(60) COLLATE utf8_unicode_ci DEFAULT NULL,
  `agent_id` bigint(20) DEFAULT NULL,
  `staff_id` bigint(20) DEFAULT NULL,
  `ticket_comment_detail` text COLLATE utf8_unicode_ci DEFAULT NULL,
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `ticket_comment_attachment`
--

CREATE TABLE `ticket_comment_attachment` (
  `ticket_comment_attachment_uuid` char(60) COLLATE utf8_unicode_ci DEFAULT NULL,
  `ticket_comment_uuid` char(60) COLLATE utf8_unicode_ci NOT NULL,
  `attachment_uuid` char(60) COLLATE utf8_unicode_ci DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `vendor_campaign`
--

CREATE TABLE `vendor_campaign` (
  `campaign_uuid` char(60) COLLATE utf8_unicode_ci NOT NULL,
  `template_uuid` char(60) COLLATE utf8_unicode_ci NOT NULL,
  `progress` int(11) DEFAULT 0,
  `status` tinyint(1) DEFAULT 0,
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `vendor_email_template`
--

CREATE TABLE `vendor_email_template` (
  `template_uuid` char(60) COLLATE utf8_unicode_ci NOT NULL,
  `subject` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `message` text COLLATE utf8_unicode_ci DEFAULT NULL,
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `voucher`
--

CREATE TABLE `voucher` (
  `voucher_id` bigint(20) NOT NULL,
  `restaurant_uuid` char(60) COLLATE utf8_unicode_ci NOT NULL,
  `code` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `description` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `description_ar` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `discount_type` smallint(6) NOT NULL DEFAULT 0,
  `discount_amount` float UNSIGNED DEFAULT 0,
  `voucher_status` smallint(6) DEFAULT 1,
  `valid_from` datetime DEFAULT NULL,
  `valid_until` datetime DEFAULT NULL,
  `max_redemption` int(11) DEFAULT 0,
  `limit_per_customer` int(11) DEFAULT 0,
  `minimum_order_amount` int(11) DEFAULT 0,
  `is_deleted` tinyint(1) DEFAULT 0,
  `voucher_created_at` datetime DEFAULT NULL,
  `voucher_updated_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `voucher`
--

INSERT INTO `voucher` (`voucher_id`, `restaurant_uuid`, `code`, `description`, `description_ar`, `discount_type`, `discount_amount`, `voucher_status`, `valid_from`, `valid_until`, `max_redemption`, `limit_per_customer`, `minimum_order_amount`, `is_deleted`, `voucher_created_at`, `voucher_updated_at`) VALUES
(1, '1', 'nihil', 'Cum rem minus esse magni hic quasi.', 'Quis id atque quibusdam.', 1, 2871, 1, '1992-12-12 00:00:00', '2992-12-12 00:00:00', 100, 10, 3527, 0, '1976-09-02 23:52:17', '1996-06-19 14:50:13'),
(2, '2', 'velit', 'Sed ea veritatis est doloremque tempora ratione deleniti quisquam.', 'Aut iste et qui sed.', 2, 1429, 1, '1992-12-12 00:00:00', '2992-12-12 00:00:00', 100, 10, 5160, 0, '2015-05-29 11:57:14', '2013-12-04 03:09:02'),
(3, '3', 'rerum', 'Molestiae consequatur ullam adipisci autem aspernatur voluptates vel consequatur.', 'Qui adipisci minima corporis ullam nam.', 2, 2604, 1, '1992-12-12 00:00:00', '2992-12-12 00:00:00', 100, 10, 3325, 0, '1988-05-18 16:21:03', '1979-10-24 23:32:10'),
(4, '4', 'aperiam', 'Ut quia perferendis qui exercitationem.', 'Rerum consequuntur veniam voluptas expedita eligendi.', 1, 4348, 1, '1992-12-12 00:00:00', '2992-12-12 00:00:00', 100, 10, 4867, 0, '2019-11-28 11:06:40', '1974-01-03 04:10:42'),
(5, '5', 'eaque', 'Et enim molestiae beatae at vero itaque sit.', 'Ut velit ipsum voluptatem laudantium.', 2, 1318, 1, '1992-12-12 00:00:00', '2992-12-12 00:00:00', 100, 10, 6948, 0, '1973-08-24 02:34:49', '2005-08-28 17:23:35'),
(6, '6', 'autem', 'Iste hic eum perferendis dolorum et maxime.', 'Vel accusantium porro est quia veniam aliquam eum.', 1, 4990, 1, '1992-12-12 00:00:00', '2992-12-12 00:00:00', 100, 10, 3276, 0, '2020-12-21 15:49:01', '1997-04-12 04:41:17'),
(7, '7', 'doloremque', 'Minima est veniam perferendis.', 'Architecto natus consequuntur et alias.', 1, 3660, 1, '1992-12-12 00:00:00', '2992-12-12 00:00:00', 100, 10, 8763, 0, '1986-06-01 08:43:35', '2014-06-10 03:55:55'),
(8, '8', 'et', 'Expedita dolorum veniam esse labore unde repellendus.', 'Ut aspernatur numquam qui quibusdam.', 1, 3755, 1, '1992-12-12 00:00:00', '2992-12-12 00:00:00', 100, 10, 4119, 0, '1999-06-04 22:27:56', '1973-06-23 03:40:30'),
(9, '9', 'asperiores', 'Ullam est maiores ex voluptas amet dignissimos eaque.', 'Magni et quo repellat ad.', 2, 5240, 1, '1992-12-12 00:00:00', '2992-12-12 00:00:00', 100, 10, 6352, 0, '2005-12-04 09:08:14', '1985-05-09 10:59:19'),
(10, '10', 'laborum', 'Dolorem ducimus harum numquam quasi.', 'Facilis aut assumenda fuga quae nam architecto.', 2, 8600, 1, '1992-12-12 00:00:00', '2992-12-12 00:00:00', 100, 10, 1002, 0, '1974-08-07 23:50:44', '2018-09-03 08:32:08'),
(11, '11', 'alias', 'Cum officia velit vel sunt.', 'Minima voluptatum sint vel voluptatem quis cupiditate.', 1, 8017, 1, '1992-12-12 00:00:00', '2992-12-12 00:00:00', 100, 10, 5823, 0, '1990-03-23 17:06:10', '2019-04-11 13:02:25'),
(12, '12', 'neque', 'Corrupti molestiae neque debitis.', 'Voluptas explicabo ut quia.', 2, 8273, 1, '1992-12-12 00:00:00', '2992-12-12 00:00:00', 100, 10, 4724, 0, '1988-12-24 11:41:47', '1991-02-14 11:40:50'),
(13, '13', 'animi', 'Dolor nisi voluptatum necessitatibus quo deserunt eum.', 'Quia eaque sint reprehenderit quaerat eos consequatur est molestiae.', 3, 6062, 1, '1992-12-12 00:00:00', '2992-12-12 00:00:00', 100, 10, 7449, 0, '1971-08-24 04:05:25', '1984-06-29 03:06:25'),
(14, '14', 'labore', 'Fuga ex voluptatem qui sunt non aut ipsam veritatis.', 'Autem aliquam qui et impedit consequatur pariatur voluptate.', 1, 7238, 1, '1992-12-12 00:00:00', '2992-12-12 00:00:00', 100, 10, 5425, 0, '2012-09-19 15:11:30', '2015-07-04 04:49:44'),
(15, '15', 'qui', 'Velit deserunt architecto voluptatem autem autem quo quam.', 'Consequatur nulla voluptas sunt dolore.', 3, 2458, 1, '1992-12-12 00:00:00', '2992-12-12 00:00:00', 100, 10, 7595, 0, '1983-05-05 16:02:40', '1987-12-02 22:10:20'),
(16, '16', 'placeat', 'Quia quam voluptatem blanditiis nostrum repellat ex modi.', 'Quibusdam velit est aut sit ab rerum.', 1, 4750, 1, '1992-12-12 00:00:00', '2992-12-12 00:00:00', 100, 10, 1314, 0, '1999-07-08 20:24:59', '2019-07-04 21:11:38'),
(17, '17', 'assumenda', 'Hic vero consequatur quia enim.', 'Ut velit iste cupiditate.', 2, 2206, 1, '1992-12-12 00:00:00', '2992-12-12 00:00:00', 100, 10, 2173, 0, '1970-08-14 21:36:18', '1972-04-01 07:16:29'),
(18, '18', 'quia', 'Molestias quia a aliquid fuga fugit quo.', 'Mollitia vel vel repudiandae consequuntur id voluptatum nulla.', 1, 1995, 1, '1992-12-12 00:00:00', '2992-12-12 00:00:00', 100, 10, 1011, 0, '1980-06-12 11:39:59', '1980-05-04 10:43:32'),
(19, '19', 'dolor', 'Itaque sunt eum omnis aliquam.', 'Cumque mollitia sit officiis facere eum nihil autem.', 1, 1391, 1, '1992-12-12 00:00:00', '2992-12-12 00:00:00', 100, 10, 3724, 0, '1993-08-17 05:07:15', '1989-06-07 10:02:22'),
(20, '20', 'in', 'Sit accusantium nobis consequatur omnis nisi.', 'Reprehenderit id sapiente autem.', 2, 7227, 1, '1992-12-12 00:00:00', '2992-12-12 00:00:00', 100, 10, 8630, 0, '2012-10-27 14:55:31', '1974-06-04 06:17:37'),
(21, '21', 'dolores', 'Ducimus voluptatem quia consequatur perspiciatis.', 'Aliquam sapiente necessitatibus voluptas officiis occaecati dolore id.', 1, 2570, 1, '1992-12-12 00:00:00', '2992-12-12 00:00:00', 100, 10, 8112, 0, '1997-06-20 04:45:44', '1999-03-19 04:03:28'),
(22, '22', 'sint', 'Suscipit consequatur aut velit repudiandae.', 'Et est dolorum possimus occaecati consequuntur aut ut explicabo.', 2, 3402, 1, '1992-12-12 00:00:00', '2992-12-12 00:00:00', 100, 10, 8531, 0, '1980-08-06 10:20:49', '1971-11-16 01:08:04'),
(23, '23', 'qui', 'Ea non iusto saepe repellendus quisquam sunt eum.', 'Accusamus ullam inventore beatae.', 3, 2396, 1, '1992-12-12 00:00:00', '2992-12-12 00:00:00', 100, 10, 5107, 0, '1991-07-12 20:22:30', '1997-03-15 20:13:02'),
(24, '24', 'quia', 'Illum dolorem ratione et nihil adipisci beatae.', 'Praesentium rerum fuga eos suscipit.', 3, 8704, 1, '1992-12-12 00:00:00', '2992-12-12 00:00:00', 100, 10, 6597, 0, '2003-11-04 03:17:25', '1992-01-11 17:09:45'),
(25, '25', 'quasi', 'Quibusdam veritatis nobis in provident quae a.', 'Ut quo non ut sunt.', 3, 8402, 1, '1992-12-12 00:00:00', '2992-12-12 00:00:00', 100, 10, 3445, 0, '2013-09-27 23:00:34', '1994-03-24 07:06:58'),
(26, '26', 'eaque', 'Eum cumque ea ratione omnis quia et.', 'Dolores est voluptas tenetur voluptatibus.', 2, 5420, 1, '1992-12-12 00:00:00', '2992-12-12 00:00:00', 100, 10, 4431, 0, '1985-07-08 20:55:29', '2015-02-10 11:32:35'),
(27, '27', 'ut', 'Laboriosam ab quis quis sit.', 'Ipsum deleniti vero optio.', 1, 2555, 1, '1992-12-12 00:00:00', '2992-12-12 00:00:00', 100, 10, 4523, 0, '1973-01-29 23:21:50', '1996-02-26 06:25:12'),
(28, '28', 'eligendi', 'Minima in autem aperiam sint velit dignissimos.', 'Fugiat sit quaerat est et.', 3, 2573, 1, '1992-12-12 00:00:00', '2992-12-12 00:00:00', 100, 10, 7305, 0, '1997-01-30 19:31:25', '1991-08-19 12:06:56'),
(29, '29', 'recusandae', 'Eos suscipit accusantium rerum exercitationem et ut.', 'Dicta qui ea quam ut facere ullam neque.', 1, 1642, 1, '1992-12-12 00:00:00', '2992-12-12 00:00:00', 100, 10, 8795, 0, '1976-12-13 02:58:31', '2005-01-17 16:01:04'),
(30, '30', 'nemo', 'Libero assumenda pariatur repellat qui velit est et.', 'Sit voluptas ut consequatur in est.', 2, 8837, 1, '1992-12-12 00:00:00', '2992-12-12 00:00:00', 100, 10, 1412, 0, '2001-05-03 10:26:36', '1982-09-23 10:03:02'),
(31, '31', 'nesciunt', 'Fugiat officia aspernatur eaque similique vel.', 'Necessitatibus distinctio quisquam ea enim.', 3, 8182, 1, '1992-12-12 00:00:00', '2992-12-12 00:00:00', 100, 10, 7033, 0, '1993-04-05 14:42:55', '1985-07-09 19:44:40'),
(32, '32', 'excepturi', 'Quibusdam aut laborum eveniet repellat dolor voluptates vel occaecati.', 'Provident sed voluptatibus sit sed sit quidem quod.', 1, 2889, 1, '1992-12-12 00:00:00', '2992-12-12 00:00:00', 100, 10, 2150, 0, '1999-01-02 01:12:44', '2010-01-18 13:04:48'),
(33, '33', 'aliquam', 'Voluptas consequuntur in voluptatem possimus nisi ab.', 'Quas aut voluptas officiis quos culpa laboriosam.', 3, 6099, 1, '1992-12-12 00:00:00', '2992-12-12 00:00:00', 100, 10, 5796, 0, '1972-12-15 10:10:17', '1984-10-03 04:14:43'),
(34, '34', 'id', 'Laborum provident ex animi nam fugit asperiores fugit.', 'Cupiditate suscipit perferendis corporis facere tempora.', 3, 3051, 1, '1992-12-12 00:00:00', '2992-12-12 00:00:00', 100, 10, 5351, 0, '1984-10-23 12:07:53', '2014-07-10 14:13:05'),
(35, '35', 'expedita', 'In eveniet explicabo aliquid vel placeat commodi culpa.', 'Praesentium voluptate ducimus voluptatem quod voluptate qui cumque dolores.', 1, 4762, 1, '1992-12-12 00:00:00', '2992-12-12 00:00:00', 100, 10, 2404, 0, '2020-11-05 06:23:19', '1979-05-06 10:35:29'),
(36, '36', 'earum', 'Mollitia ullam fugiat quia saepe aperiam voluptas non.', 'Provident iusto ea dolorem dolorum voluptas distinctio.', 3, 7165, 1, '1992-12-12 00:00:00', '2992-12-12 00:00:00', 100, 10, 2771, 0, '2001-06-09 10:29:32', '1974-07-16 01:21:39'),
(37, '37', 'eaque', 'Sed autem eius illo quo accusamus.', 'Ut at nisi corporis hic rerum natus assumenda.', 3, 3918, 1, '1992-12-12 00:00:00', '2992-12-12 00:00:00', 100, 10, 8546, 0, '2019-07-27 12:24:25', '2020-07-05 19:09:33'),
(38, '38', 'eum', 'Neque tempora dicta dolor libero harum maiores consequatur eos.', 'Cumque enim quia enim.', 2, 6180, 1, '1992-12-12 00:00:00', '2992-12-12 00:00:00', 100, 10, 1042, 0, '1987-10-18 14:17:44', '2006-09-06 15:40:24'),
(39, '39', 'velit', 'Aspernatur quasi neque fugiat.', 'Ea qui nihil voluptas voluptas et consequuntur.', 3, 8950, 1, '1992-12-12 00:00:00', '2992-12-12 00:00:00', 100, 10, 5209, 0, '1984-01-09 07:57:00', '1970-01-13 02:10:22'),
(40, '40', 'sed', 'Ducimus et debitis quo modi.', 'Quia omnis vero dolor incidunt qui expedita labore.', 1, 3713, 1, '1992-12-12 00:00:00', '2992-12-12 00:00:00', 100, 10, 4660, 0, '1972-05-24 07:18:57', '2000-02-13 02:06:08'),
(41, '41', 'optio', 'Non laudantium rerum quas ex.', 'Exercitationem et et suscipit omnis harum.', 3, 6239, 1, '1992-12-12 00:00:00', '2992-12-12 00:00:00', 100, 10, 8209, 0, '1981-01-17 00:08:10', '1977-06-18 15:41:27'),
(42, '42', 'consequatur', 'Consectetur iste molestiae ipsam laboriosam.', 'Facilis beatae et et provident atque veniam.', 3, 8174, 1, '1992-12-12 00:00:00', '2992-12-12 00:00:00', 100, 10, 2824, 0, '2021-01-04 08:46:06', '1975-11-05 12:29:55'),
(43, '43', 'voluptatem', 'Cum repellat culpa fugit qui et non voluptas.', 'Sunt incidunt iste laboriosam incidunt.', 2, 1626, 1, '1992-12-12 00:00:00', '2992-12-12 00:00:00', 100, 10, 3598, 0, '1997-07-03 07:22:31', '1983-03-30 22:34:38'),
(44, '44', 'id', 'Nam autem error natus et officiis omnis.', 'Sunt et omnis autem dolore.', 3, 5997, 1, '1992-12-12 00:00:00', '2992-12-12 00:00:00', 100, 10, 2154, 0, '1973-12-19 19:23:49', '1976-01-16 22:07:53'),
(45, '45', 'reiciendis', 'Et aut nihil numquam doloribus non.', 'Facilis commodi voluptate adipisci eius.', 1, 5109, 1, '1992-12-12 00:00:00', '2992-12-12 00:00:00', 100, 10, 5643, 0, '1994-11-26 09:29:18', '1994-02-28 10:00:29'),
(46, '46', 'consequatur', 'Velit dolorem ipsam odio harum.', 'Neque a error est itaque rerum cupiditate.', 3, 6667, 1, '1992-12-12 00:00:00', '2992-12-12 00:00:00', 100, 10, 8856, 0, '1986-05-12 12:16:13', '1978-05-31 21:18:31'),
(47, '47', 'perferendis', 'Alias animi est minus.', 'Dolores eius aut ratione et vitae iusto officiis.', 3, 3064, 1, '1992-12-12 00:00:00', '2992-12-12 00:00:00', 100, 10, 5206, 0, '1994-05-18 21:20:27', '1992-01-12 17:00:07'),
(48, '48', 'assumenda', 'Velit eos voluptatem aliquam maxime.', 'Cumque eos ipsa sed cupiditate voluptatem et.', 3, 5248, 1, '1992-12-12 00:00:00', '2992-12-12 00:00:00', 100, 10, 2095, 0, '2014-11-26 14:19:37', '1979-12-19 23:49:50'),
(49, '49', 'rem', 'Aut quis necessitatibus molestiae amet quia necessitatibus.', 'Asperiores praesentium tenetur cumque eveniet rerum.', 2, 5456, 1, '1992-12-12 00:00:00', '2992-12-12 00:00:00', 100, 10, 3697, 0, '2013-08-26 13:09:04', '2005-05-28 16:40:16'),
(50, '50', 'laborum', 'Quis sint aperiam eveniet sed.', 'Molestiae id aperiam voluptatem.', 2, 2255, 1, '1992-12-12 00:00:00', '2992-12-12 00:00:00', 100, 10, 7577, 0, '2019-11-07 02:17:17', '1996-03-12 03:10:18');

-- --------------------------------------------------------

--
-- Table structure for table `web_link`
--

CREATE TABLE `web_link` (
  `web_link_id` bigint(20) NOT NULL,
  `restaurant_uuid` char(60) COLLATE utf8_unicode_ci NOT NULL,
  `web_link_type` smallint(6) NOT NULL DEFAULT 0,
  `url` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `web_link_title` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `web_link_title_ar` varchar(255) COLLATE utf8_unicode_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `web_link`
--

INSERT INTO `web_link` (`web_link_id`, `restaurant_uuid`, `web_link_type`, `url`, `web_link_title`, `web_link_title_ar`) VALUES
(1, '1', 1, 'http://beer.org/quasi-dolorem-in-voluptates-eum-est-adipisci.html', 'sint', 'itaque'),
(2, '2', 1, 'http://www.reynolds.com/nihil-magni-ut-magni-exercitationem-quis-ad-omnis', 'quibusdam', 'nihil'),
(3, '3', 1, 'http://keeling.info/', 'velit', 'quas'),
(4, '4', 1, 'http://dickinson.com/eligendi-in-doloribus-corrupti-voluptatem-dolorem-aspernatur', 'assumenda', 'officia'),
(5, '5', 1, 'http://upton.net/aut-repudiandae-corrupti-dolor-dolores-eum-eos-mollitia', 'facilis', 'in'),
(6, '6', 1, 'http://www.lubowitz.com/', 'autem', 'est'),
(7, '7', 1, 'http://rosenbaum.net/', 'sit', 'maiores'),
(8, '8', 1, 'https://rohan.com/libero-ullam-asperiores-ipsum-consequuntur.html', 'dolorum', 'quas'),
(9, '9', 1, 'http://will.info/excepturi-sed-fugit-ipsam-inventore-quae-laudantium', 'vitae', 'quam'),
(10, '10', 1, 'http://www.pacocha.biz/qui-magnam-amet-consequatur-eum-quis-velit-cupiditate', 'ut', 'aliquid'),
(11, '11', 1, 'https://rohan.org/doloremque-nihil-doloremque-et-et-et-delectus-corrupti.html', 'odio', 'sit'),
(12, '12', 1, 'https://ziemann.biz/dicta-nihil-eligendi-voluptatem-et-suscipit-quidem-quas-in.html', 'quas', 'labore'),
(13, '13', 1, 'http://runte.info/', 'esse', 'officia'),
(14, '14', 1, 'http://www.wilderman.net/voluptatum-ut-qui-porro-dicta-iure-itaque-vel.html', 'quam', 'nihil'),
(15, '15', 1, 'http://www.douglas.com/nesciunt-totam-placeat-eveniet-aut-quas-sed-modi', 'ex', 'quasi'),
(16, '16', 1, 'http://www.denesik.com/excepturi-facere-accusamus-eaque-facere-nam-reiciendis.html', 'nihil', 'distinctio'),
(17, '17', 1, 'http://mcdermott.info/hic-aut-laboriosam-aut-vel-aliquid', 'blanditiis', 'rerum'),
(18, '18', 1, 'https://quitzon.net/voluptatem-necessitatibus-incidunt-id-harum-nostrum-quis.html', 'exercitationem', 'ut'),
(19, '19', 1, 'http://www.wunsch.org/omnis-officia-dolor-ea-similique-delectus-aspernatur-cum-quo', 'dolores', 'nisi'),
(20, '20', 1, 'https://www.osinski.com/saepe-asperiores-cupiditate-deleniti-qui-molestiae-repellat-aliquid-ratione', 'blanditiis', 'ea'),
(21, '21', 1, 'http://schumm.info/beatae-ratione-amet-dolorum-quisquam', 'est', 'dolores'),
(22, '22', 1, 'https://abbott.net/eligendi-dolorem-quis-adipisci-aliquid-neque-maxime.html', 'recusandae', 'suscipit'),
(23, '23', 1, 'https://www.thompson.com/alias-quos-vitae-est-nihil', 'ea', 'ipsam'),
(24, '24', 1, 'http://luettgen.org/maiores-aut-architecto-officia-culpa-consectetur-placeat-ut-vero.html', 'totam', 'nobis'),
(25, '25', 1, 'http://boyle.com/veritatis-non-molestiae-perspiciatis-et-ut-delectus-maiores', 'sunt', 'similique'),
(26, '26', 1, 'http://www.hegmann.net/deserunt-in-ullam-cupiditate-autem-sapiente-excepturi.html', 'voluptatem', 'possimus'),
(27, '27', 1, 'http://www.cronin.com/recusandae-rerum-id-aut-amet', 'possimus', 'quasi'),
(28, '28', 1, 'http://www.stoltenberg.com/iusto-qui-nemo-rerum-non-minima', 'expedita', 'sunt'),
(29, '29', 1, 'http://www.christiansen.com/ut-et-accusantium-ut', 'consequatur', 'dolorem'),
(30, '30', 1, 'http://www.goldner.info/', 'veniam', 'aut'),
(31, '31', 1, 'https://www.sanford.com/ut-sunt-ex-veritatis-qui-ut', 'ullam', 'adipisci'),
(32, '32', 1, 'http://wilkinson.com/officia-nostrum-quam-libero-delectus', 'consequatur', 'dolore'),
(33, '33', 1, 'https://www.huel.com/voluptates-reprehenderit-aut-nostrum-ut-qui-adipisci-unde-ratione', 'repellat', 'quam'),
(34, '34', 1, 'https://www.gislason.biz/cum-magni-sit-accusamus-deleniti-beatae-quia', 'ad', 'ducimus'),
(35, '35', 1, 'http://wilderman.com/', 'quia', 'odio'),
(36, '36', 1, 'http://wiegand.net/et-architecto-sapiente-deserunt-sed-minima.html', 'voluptas', 'deleniti'),
(37, '37', 1, 'http://www.wolff.net/non-sit-necessitatibus-animi-cupiditate-illum-praesentium-facilis', 'a', 'harum'),
(38, '38', 1, 'http://sauer.com/tenetur-tempore-rerum-omnis', 'doloremque', 'doloribus'),
(39, '39', 1, 'https://www.mcdermott.org/illum-beatae-aperiam-officiis-perferendis-sequi', 'distinctio', 'facilis'),
(40, '40', 1, 'http://ohara.com/hic-dolor-consequuntur-eligendi-laboriosam.html', 'quaerat', 'tempora'),
(41, '41', 1, 'http://www.kunde.com/', 'natus', 'officiis'),
(42, '42', 1, 'http://www.schinner.org/', 'similique', 'quam'),
(43, '43', 1, 'http://windler.info/debitis-maxime-sequi-rerum-et-dolore-rem', 'sapiente', 'suscipit'),
(44, '44', 1, 'http://www.sporer.com/', 'amet', 'enim'),
(45, '45', 1, 'http://www.barton.com/quaerat-minima-id-sunt-facilis.html', 'omnis', 'ex'),
(46, '46', 1, 'http://www.nitzsche.com/commodi-dolorem-repellat-itaque-odio-quam', 'aut', 'minima'),
(47, '47', 1, 'http://www.hermiston.com/dolorem-labore-error-sed-ex-magnam', 'deserunt', 'expedita'),
(48, '48', 1, 'http://cronin.com/autem-nihil-quaerat-dolor-et-eos', 'harum', 'nihil'),
(49, '49', 1, 'https://tromp.com/odit-omnis-quia-omnis-et-omnis.html', 'cupiditate', 'dolorem'),
(50, '50', 1, 'http://www.lynch.info/et-dolore-illum-excepturi-accusantium.html', 'repellendus', 'et');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `addon`
--
ALTER TABLE `addon`
  ADD PRIMARY KEY (`addon_uuid`),
  ADD UNIQUE KEY `addon_uuid` (`addon_uuid`),
  ADD KEY `idx-addon-created_by` (`created_by`),
  ADD KEY `idx-addon-updated_by` (`updated_by`);

--
-- Indexes for table `addon_payment`
--
ALTER TABLE `addon_payment`
  ADD PRIMARY KEY (`payment_uuid`),
  ADD KEY `idx-addon_payment-restaurant_uuid` (`restaurant_uuid`),
  ADD KEY `idx-addon_payment-addon_uuid` (`addon_uuid`),
  ADD KEY `idx-addon_payment-payment_gateway_order_id` (`payment_gateway_order_id`);

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
  ADD KEY `idx-agent_assignment-agent_id` (`agent_id`),
  ADD KEY `idx-agent_assignment-business_location_id` (`business_location_id`);

--
-- Indexes for table `agent_email_verify_attempt`
--
ALTER TABLE `agent_email_verify_attempt`
  ADD PRIMARY KEY (`aeva_uuid`);

--
-- Indexes for table `agent_token`
--
ALTER TABLE `agent_token`
  ADD PRIMARY KEY (`token_uuid`),
  ADD KEY `idx-agent_token-agent_id` (`agent_id`);

--
-- Indexes for table `area`
--
ALTER TABLE `area`
  ADD PRIMARY KEY (`area_id`),
  ADD KEY `idx-area-city_id` (`city_id`);

--
-- Indexes for table `area_delivery_zone`
--
ALTER TABLE `area_delivery_zone`
  ADD PRIMARY KEY (`area_delivery_zone`),
  ADD KEY `idx-area_delivery_zone-country_id` (`country_id`),
  ADD KEY `idx-area_delivery_zone-city_id` (`city_id`),
  ADD KEY `idx-area_delivery_zone-restaurant_uuid` (`restaurant_uuid`),
  ADD KEY `idx-area_delivery_zone-area_id` (`area_id`),
  ADD KEY `idx-area_delivery_zone-delivery_zone_id` (`delivery_zone_id`);

--
-- Indexes for table `attachment`
--
ALTER TABLE `attachment`
  ADD PRIMARY KEY (`attachment_uuid`);

--
-- Indexes for table `bank`
--
ALTER TABLE `bank`
  ADD PRIMARY KEY (`bank_id`);

--
-- Indexes for table `bank_discount`
--
ALTER TABLE `bank_discount`
  ADD PRIMARY KEY (`bank_discount_id`),
  ADD KEY `idx-bank_discount-bank_id` (`bank_id`),
  ADD KEY `idx-bank_discount-restaurant_uuid` (`restaurant_uuid`);

--
-- Indexes for table `business_location`
--
ALTER TABLE `business_location`
  ADD PRIMARY KEY (`business_location_id`),
  ADD KEY `idx-business_location-restaurant_uuid` (`restaurant_uuid`),
  ADD KEY `idx-business_location-country_id` (`country_id`);

--
-- Indexes for table `campaign`
--
ALTER TABLE `campaign`
  ADD PRIMARY KEY (`utm_uuid`),
  ADD KEY `fk-campaign-restaurant_uuid` (`restaurant_uuid`);

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
  ADD PRIMARY KEY (`city_id`),
  ADD KEY `idx-city-country_id` (`country_id`);

--
-- Indexes for table `country`
--
ALTER TABLE `country`
  ADD PRIMARY KEY (`country_id`);

--
-- Indexes for table `country_payment_method`
--
ALTER TABLE `country_payment_method`
  ADD PRIMARY KEY (`payment_method_id`,`country_id`),
  ADD KEY `idx-country_payment_method-country_id` (`country_id`),
  ADD KEY `idx-country_payment_method-payment_method_id` (`payment_method_id`);

--
-- Indexes for table `currency`
--
ALTER TABLE `currency`
  ADD PRIMARY KEY (`currency_id`);

--
-- Indexes for table `customer`
--
ALTER TABLE `customer`
  ADD PRIMARY KEY (`customer_id`),
  ADD KEY `idx-customer-restaurant_uuid` (`restaurant_uuid`);

--
-- Indexes for table `customer_bank_discount`
--
ALTER TABLE `customer_bank_discount`
  ADD PRIMARY KEY (`customer_bank_discount_id`),
  ADD KEY `idx-customer_bank_discount-customer_id` (`customer_id`),
  ADD KEY `idx-customer_bank_discount-bank_discount_id` (`bank_discount_id`);

--
-- Indexes for table `customer_campaign`
--
ALTER TABLE `customer_campaign`
  ADD PRIMARY KEY (`campaign_uuid`),
  ADD KEY `idx-customer_campaign-restaurant_uuid` (`restaurant_uuid`),
  ADD KEY `idx-customer_campaign-template_uuid` (`template_uuid`);

--
-- Indexes for table `customer_email_template`
--
ALTER TABLE `customer_email_template`
  ADD PRIMARY KEY (`template_uuid`),
  ADD KEY `idx-customer_email_template-restaurant_uuid` (`restaurant_uuid`);

--
-- Indexes for table `customer_email_verify_attempt`
--
ALTER TABLE `customer_email_verify_attempt`
  ADD PRIMARY KEY (`ceva_uuid`);

--
-- Indexes for table `customer_voucher`
--
ALTER TABLE `customer_voucher`
  ADD PRIMARY KEY (`customer_voucher_id`),
  ADD KEY `idx-customer_voucher-customer_id` (`customer_id`),
  ADD KEY `idx-customer_voucher-voucher_id` (`voucher_id`);

--
-- Indexes for table `delivery_zone`
--
ALTER TABLE `delivery_zone`
  ADD PRIMARY KEY (`delivery_zone_id`),
  ADD KEY `idx-delivery_zone-restaurant_uuid` (`restaurant_uuid`),
  ADD KEY `idx-delivery_zone-country_id` (`country_id`),
  ADD KEY `idx-delivery_zone-business_location_id` (`business_location_id`);

--
-- Indexes for table `extra_option`
--
ALTER TABLE `extra_option`
  ADD PRIMARY KEY (`extra_option_id`),
  ADD KEY `idx-extra_option-option_id` (`option_id`);

--
-- Indexes for table `invoice_item`
--
ALTER TABLE `invoice_item`
  ADD PRIMARY KEY (`invoice_item_uuid`),
  ADD KEY `idx-invoice_item-plan_id` (`plan_id`),
  ADD KEY `idx-invoice_item-addon_uuid` (`addon_uuid`),
  ADD KEY `idx-invoice_item-order_uuid` (`order_uuid`),
  ADD KEY `idx-invoice_item-invoice_uuid` (`invoice_uuid`),
  ADD KEY `idx-invoice_item-restaurant_uuid` (`restaurant_uuid`);

--
-- Indexes for table `invoice_payment`
--
ALTER TABLE `invoice_payment`
  ADD PRIMARY KEY (`payment_uuid`),
  ADD KEY `idx-invoice_payment-restaurant_uuid` (`restaurant_uuid`),
  ADD KEY `idx-invoice_payment-invoice_uuid` (`invoice_uuid`);

--
-- Indexes for table `item`
--
ALTER TABLE `item`
  ADD PRIMARY KEY (`item_uuid`),
  ADD UNIQUE KEY `item_uuid` (`item_uuid`),
  ADD KEY `idx-item-restaurant_uuid` (`restaurant_uuid`);

--
-- Indexes for table `item_image`
--
ALTER TABLE `item_image`
  ADD PRIMARY KEY (`item_image_id`),
  ADD KEY `idx-item_image-item_uuid` (`item_uuid`);

--
-- Indexes for table `item_variant`
--
ALTER TABLE `item_variant`
  ADD PRIMARY KEY (`item_variant_uuid`),
  ADD KEY `idx-item_variant-item_uuid` (`item_uuid`);

--
-- Indexes for table `item_variant_image`
--
ALTER TABLE `item_variant_image`
  ADD PRIMARY KEY (`item_variant_image_uuid`),
  ADD KEY `idx-item_variant_image-item_uuid` (`item_uuid`),
  ADD KEY `idx-item_variant_image-item_variant_uuid` (`item_variant_uuid`);

--
-- Indexes for table `item_variant_option`
--
ALTER TABLE `item_variant_option`
  ADD PRIMARY KEY (`item_variant_option_uuid`),
  ADD KEY `idx-item_variant_option-item_uuid` (`item_uuid`),
  ADD KEY `idx-item_variant_option-item_variant_uuid` (`item_variant_uuid`);

--
-- Indexes for table `migration`
--
ALTER TABLE `migration`
  ADD PRIMARY KEY (`version`);

--
-- Indexes for table `opening_hour`
--
ALTER TABLE `opening_hour`
  ADD PRIMARY KEY (`opening_hour_id`),
  ADD KEY `idx-opening_hour-restaurant_uuid` (`restaurant_uuid`);

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
  ADD KEY `idx-order-payment_uuid` (`payment_uuid`),
  ADD KEY `idx-order-voucher_id` (`voucher_id`),
  ADD KEY `idx-order-bank_discount_id` (`bank_discount_id`),
  ADD KEY `idx-order-country_id` (`shipping_country_id`),
  ADD KEY `idx-order-delivery_zone_id` (`delivery_zone_id`),
  ADD KEY `idx-order-pickup_location_id` (`pickup_location_id`);

--
-- Indexes for table `order_item`
--
ALTER TABLE `order_item`
  ADD PRIMARY KEY (`order_item_id`),
  ADD KEY `idx-order_item-order_uuid` (`order_uuid`),
  ADD KEY `idx-order_item-item_uuid` (`item_uuid`),
  ADD KEY `idx-order_item-restaurant_uuid` (`restaurant_uuid`);

--
-- Indexes for table `order_item_extra_option`
--
ALTER TABLE `order_item_extra_option`
  ADD PRIMARY KEY (`order_item_extra_option_id`),
  ADD KEY `idx-order_item_extra_option-order_item_id` (`order_item_id`),
  ADD KEY `idx-order_item_extra_option-extra_option_id` (`extra_option_id`);

--
-- Indexes for table `partner`
--
ALTER TABLE `partner`
  ADD PRIMARY KEY (`partner_uuid`,`referral_code`),
  ADD UNIQUE KEY `username` (`username`),
  ADD UNIQUE KEY `partner_email` (`partner_email`),
  ADD UNIQUE KEY `partner_password_reset_token` (`partner_password_reset_token`),
  ADD KEY `idx-partner-partner_uuid` (`partner_uuid`),
  ADD KEY `idx-partner-referral_code` (`referral_code`),
  ADD KEY `idx-partner-bank_id` (`bank_id`);

--
-- Indexes for table `partner_payout`
--
ALTER TABLE `partner_payout`
  ADD PRIMARY KEY (`partner_payout_uuid`),
  ADD KEY `idx-partner_payout-partner_uuid` (`partner_uuid`),
  ADD KEY `idx-partner_payout-bank_id` (`bank_id`);

--
-- Indexes for table `partner_token`
--
ALTER TABLE `partner_token`
  ADD PRIMARY KEY (`token_uuid`),
  ADD KEY `idx-partner_token-partner_uuid` (`partner_uuid`);

--
-- Indexes for table `payment`
--
ALTER TABLE `payment`
  ADD PRIMARY KEY (`payment_uuid`),
  ADD KEY `idx-payment-payment_gateway_order_id` (`payment_gateway_order_id`),
  ADD KEY `idx-payment-payment_gateway_transaction_id` (`payment_gateway_transaction_id`),
  ADD KEY `idx-payment-customer_id` (`customer_id`),
  ADD KEY `idx-payment-order_uuid` (`order_uuid`),
  ADD KEY `idx-payment-restaurant_uuid` (`restaurant_uuid`),
  ADD KEY `idx-payment-partner_payout_uuid` (`partner_payout_uuid`);

--
-- Indexes for table `payment_failed`
--
ALTER TABLE `payment_failed`
  ADD PRIMARY KEY (`payment_failed_uuid`),
  ADD KEY `idx-payment_failed-order_uuid` (`order_uuid`),
  ADD KEY `idx-payment_failed-customer_id` (`customer_id`);

--
-- Indexes for table `payment_gateway_queue`
--
ALTER TABLE `payment_gateway_queue`
  ADD PRIMARY KEY (`payment_gateway_queue_id`),
  ADD KEY `idx-payment_gateway_queue-restaurant_uuid` (`restaurant_uuid`);

--
-- Indexes for table `payment_method`
--
ALTER TABLE `payment_method`
  ADD PRIMARY KEY (`payment_method_id`);

--
-- Indexes for table `payment_method_currency`
--
ALTER TABLE `payment_method_currency`
  ADD PRIMARY KEY (`pmc_id`),
  ADD KEY `idx-payment_method_currency-payment_method_id` (`payment_method_id`);

--
-- Indexes for table `plan`
--
ALTER TABLE `plan`
  ADD PRIMARY KEY (`plan_id`);

--
-- Indexes for table `plugn_updates`
--
ALTER TABLE `plugn_updates`
  ADD PRIMARY KEY (`update_uuid`);

--
-- Indexes for table `prebuilt_email_template`
--
ALTER TABLE `prebuilt_email_template`
  ADD PRIMARY KEY (`template_uuid`);

--
-- Indexes for table `queue`
--
ALTER TABLE `queue`
  ADD PRIMARY KEY (`queue_id`),
  ADD KEY `idx-queue-restaurant_uuid` (`restaurant_uuid`);

--
-- Indexes for table `refund`
--
ALTER TABLE `refund`
  ADD PRIMARY KEY (`refund_id`),
  ADD KEY `idx-refund-restaurant_uuid` (`restaurant_uuid`),
  ADD KEY `idx-refund-order_uuid` (`order_uuid`),
  ADD KEY `idx-refund-payment_uuid` (`payment_uuid`);

--
-- Indexes for table `refunded_item`
--
ALTER TABLE `refunded_item`
  ADD PRIMARY KEY (`refunded_item_id`),
  ADD KEY `idx-refund-order_item_id` (`order_item_id`),
  ADD KEY `idx-refund-refund_id` (`refund_id`),
  ADD KEY `idx-refunded_item-order_uuid` (`order_uuid`),
  ADD KEY `idx-refunded_item-item_uuid` (`item_uuid`);

--
-- Indexes for table `restaurant`
--
ALTER TABLE `restaurant`
  ADD PRIMARY KEY (`restaurant_uuid`),
  ADD KEY `idx-restaurant-tap_queue_id` (`tap_queue_id`),
  ADD KEY `idx-restaurant-country_id` (`country_id`),
  ADD KEY `idx-restaurant-currency_id` (`currency_id`),
  ADD KEY `idx-restaurant-payment_gateway_queue_id` (`payment_gateway_queue_id`),
  ADD KEY `idx-restaurant-referral_code` (`referral_code`),
  ADD KEY `idx-campaign-restaurant_uuid` (`restaurant_uuid`),
  ADD KEY `idx-setting-restaurant_uuid` (`restaurant_uuid`);

--
-- Indexes for table `restaurant_addon`
--
ALTER TABLE `restaurant_addon`
  ADD PRIMARY KEY (`ra_uuid`),
  ADD UNIQUE KEY `ra_uuid` (`ra_uuid`),
  ADD KEY `idx-restaurant_addon-restaurant_uuid` (`restaurant_uuid`),
  ADD KEY `idx-restaurant_addon-addon_uuid` (`addon_uuid`);

--
-- Indexes for table `restaurant_branch`
--
ALTER TABLE `restaurant_branch`
  ADD PRIMARY KEY (`restaurant_branch_id`),
  ADD KEY `idx-restaurant_branch-restaurant_uuid` (`restaurant_uuid`);

--
-- Indexes for table `restaurant_currency`
--
ALTER TABLE `restaurant_currency`
  ADD KEY `idx-restaurant_currency-restaurant_uuid` (`restaurant_uuid`),
  ADD KEY `idx-restaurant_currency-currency_id` (`currency_id`);

--
-- Indexes for table `restaurant_delivery`
--
ALTER TABLE `restaurant_delivery`
  ADD PRIMARY KEY (`restaurant_uuid`,`area_id`),
  ADD KEY `idx-restaurant_delivery-restaurant_uuid` (`restaurant_uuid`),
  ADD KEY `idx-restaurant_delivery-area_id` (`area_id`);

--
-- Indexes for table `restaurant_domain_request`
--
ALTER TABLE `restaurant_domain_request`
  ADD PRIMARY KEY (`request_uuid`),
  ADD KEY `idx-restaurant_domain_request-created_by` (`created_by`),
  ADD KEY `idx-restaurant_domain_request-restaurant_uuid` (`restaurant_uuid`);

--
-- Indexes for table `restaurant_invoice`
--
ALTER TABLE `restaurant_invoice`
  ADD PRIMARY KEY (`invoice_uuid`),
  ADD KEY `idx-restaurant_invoice-invoice_number` (`invoice_number`),
  ADD KEY `idx-restaurant_invoice-restaurant_uuid` (`restaurant_uuid`),
  ADD KEY `idx-restaurant_invoice-payment_uuid` (`payment_uuid`);

--
-- Indexes for table `restaurant_payment_method`
--
ALTER TABLE `restaurant_payment_method`
  ADD PRIMARY KEY (`restaurant_uuid`,`payment_method_id`),
  ADD KEY `idx-restaurant_payment_method-restaurant_uuid` (`restaurant_uuid`),
  ADD KEY `idx-restaurant_payment_method-payment_method_id` (`payment_method_id`);

--
-- Indexes for table `restaurant_theme`
--
ALTER TABLE `restaurant_theme`
  ADD PRIMARY KEY (`restaurant_uuid`),
  ADD KEY `idx-restaurant_theme-restaurant_uuid` (`restaurant_uuid`);

--
-- Indexes for table `restaurant_upload`
--
ALTER TABLE `restaurant_upload`
  ADD PRIMARY KEY (`upload_uuid`),
  ADD KEY `idx-restaurant_upload-restaurant_uuid` (`restaurant_uuid`),
  ADD KEY `idx-restaurant_upload-created_by` (`created_by`);

--
-- Indexes for table `setting`
--
ALTER TABLE `setting`
  ADD PRIMARY KEY (`setting_uuid`),
  ADD KEY `fk-setting-restaurant_uuid` (`restaurant_uuid`);

--
-- Indexes for table `staff`
--
ALTER TABLE `staff`
  ADD PRIMARY KEY (`staff_id`),
  ADD UNIQUE KEY `staff_email` (`staff_email`),
  ADD UNIQUE KEY `staff_password_reset_token` (`staff_password_reset_token`);

--
-- Indexes for table `staff_token`
--
ALTER TABLE `staff_token`
  ADD PRIMARY KEY (`token_uuid`),
  ADD KEY `idx-staff_token-staff_id` (`staff_id`);

--
-- Indexes for table `store_updates`
--
ALTER TABLE `store_updates`
  ADD PRIMARY KEY (`store_update_uuid`),
  ADD KEY `idx-store_updates-restaurant_uuid` (`restaurant_uuid`);

--
-- Indexes for table `store_web_link`
--
ALTER TABLE `store_web_link`
  ADD KEY `idx-store_web_link-web_link_id` (`web_link_id`),
  ADD KEY `idx-store_web_link-restaurant_uuid` (`restaurant_uuid`);

--
-- Indexes for table `subscription`
--
ALTER TABLE `subscription`
  ADD PRIMARY KEY (`subscription_uuid`),
  ADD UNIQUE KEY `subscription_uuid` (`subscription_uuid`),
  ADD KEY `idx-subscription-restaurant_uuid` (`restaurant_uuid`),
  ADD KEY `idx-subscription-plan_id` (`plan_id`),
  ADD KEY `idx-subscription-payment_uuid` (`payment_uuid`),
  ADD KEY `idx-subscription-subscription_uuid` (`payment_method_id`);

--
-- Indexes for table `subscription_payment`
--
ALTER TABLE `subscription_payment`
  ADD PRIMARY KEY (`payment_uuid`),
  ADD KEY `idx-subscription_payment-payment_gateway_order_id` (`payment_gateway_order_id`),
  ADD KEY `idx-subscription_payment-payment_gateway_transaction_id` (`payment_gateway_transaction_id`),
  ADD KEY `idx-subscription_payment-restaurant_uuid` (`restaurant_uuid`),
  ADD KEY `idx-subscription_payment-subscription_uuid` (`subscription_uuid`),
  ADD KEY `idx-subscription_payment-partner_payout_uuid` (`partner_payout_uuid`);

--
-- Indexes for table `tap_queue`
--
ALTER TABLE `tap_queue`
  ADD PRIMARY KEY (`tap_queue_id`),
  ADD KEY `idx-tap_queue-restaurant_uuid` (`restaurant_uuid`);

--
-- Indexes for table `ticket`
--
ALTER TABLE `ticket`
  ADD PRIMARY KEY (`ticket_uuid`),
  ADD KEY `idx-ticket-restaurant_uuid` (`restaurant_uuid`),
  ADD KEY `idx-ticket-agent_id` (`agent_id`),
  ADD KEY `idx-ticket-staff_id` (`staff_id`);

--
-- Indexes for table `ticket_attachment`
--
ALTER TABLE `ticket_attachment`
  ADD PRIMARY KEY (`ticket_attachment_uuid`),
  ADD KEY `idx-ticket_attachment-attachment_uuid` (`attachment_uuid`),
  ADD KEY `idx-ticket_attachment-agent_id` (`ticket_uuid`);

--
-- Indexes for table `ticket_comment`
--
ALTER TABLE `ticket_comment`
  ADD PRIMARY KEY (`ticket_comment_uuid`),
  ADD KEY `idx-ticket_comment-ticket_uuid` (`ticket_uuid`),
  ADD KEY `idx-ticket_comment-agent_id` (`agent_id`),
  ADD KEY `idx-ticket_comment-staff_id` (`staff_id`);

--
-- Indexes for table `ticket_comment_attachment`
--
ALTER TABLE `ticket_comment_attachment`
  ADD PRIMARY KEY (`ticket_comment_uuid`),
  ADD KEY `idx-ticket_comment_attachment-ticket_comment_uuid` (`ticket_comment_uuid`),
  ADD KEY `idx-ticket_comment_attachment-attachment_uuid` (`attachment_uuid`);

--
-- Indexes for table `vendor_campaign`
--
ALTER TABLE `vendor_campaign`
  ADD PRIMARY KEY (`campaign_uuid`),
  ADD KEY `idx-vendor_campaign-template_uuid` (`template_uuid`);

--
-- Indexes for table `vendor_email_template`
--
ALTER TABLE `vendor_email_template`
  ADD PRIMARY KEY (`template_uuid`);

--
-- Indexes for table `voucher`
--
ALTER TABLE `voucher`
  ADD PRIMARY KEY (`voucher_id`),
  ADD KEY `idx-voucher-restaurant_uuid` (`restaurant_uuid`);

--
-- Indexes for table `web_link`
--
ALTER TABLE `web_link`
  ADD PRIMARY KEY (`web_link_id`),
  ADD KEY `idx-web_link-restaurant_uuid` (`restaurant_uuid`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `admin`
--
ALTER TABLE `admin`
  MODIFY `admin_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT for table `agent`
--
ALTER TABLE `agent`
  MODIFY `agent_id` bigint(20) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=51;

--
-- AUTO_INCREMENT for table `agent_assignment`
--
ALTER TABLE `agent_assignment`
  MODIFY `assignment_id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=51;

--
-- AUTO_INCREMENT for table `area`
--
ALTER TABLE `area`
  MODIFY `area_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=51;

--
-- AUTO_INCREMENT for table `area_delivery_zone`
--
ALTER TABLE `area_delivery_zone`
  MODIFY `area_delivery_zone` bigint(20) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=51;

--
-- AUTO_INCREMENT for table `bank`
--
ALTER TABLE `bank`
  MODIFY `bank_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=51;

--
-- AUTO_INCREMENT for table `bank_discount`
--
ALTER TABLE `bank_discount`
  MODIFY `bank_discount_id` bigint(20) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=51;

--
-- AUTO_INCREMENT for table `business_location`
--
ALTER TABLE `business_location`
  MODIFY `business_location_id` bigint(20) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=51;

--
-- AUTO_INCREMENT for table `category`
--
ALTER TABLE `category`
  MODIFY `category_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=51;

--
-- AUTO_INCREMENT for table `category_item`
--
ALTER TABLE `category_item`
  MODIFY `category_item_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=51;

--
-- AUTO_INCREMENT for table `city`
--
ALTER TABLE `city`
  MODIFY `city_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=51;

--
-- AUTO_INCREMENT for table `country`
--
ALTER TABLE `country`
  MODIFY `country_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=51;

--
-- AUTO_INCREMENT for table `currency`
--
ALTER TABLE `currency`
  MODIFY `currency_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=170;

--
-- AUTO_INCREMENT for table `customer`
--
ALTER TABLE `customer`
  MODIFY `customer_id` bigint(20) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=51;

--
-- AUTO_INCREMENT for table `customer_bank_discount`
--
ALTER TABLE `customer_bank_discount`
  MODIFY `customer_bank_discount_id` bigint(20) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=51;

--
-- AUTO_INCREMENT for table `customer_voucher`
--
ALTER TABLE `customer_voucher`
  MODIFY `customer_voucher_id` bigint(20) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=51;

--
-- AUTO_INCREMENT for table `delivery_zone`
--
ALTER TABLE `delivery_zone`
  MODIFY `delivery_zone_id` bigint(20) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=51;

--
-- AUTO_INCREMENT for table `extra_option`
--
ALTER TABLE `extra_option`
  MODIFY `extra_option_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=95711;

--
-- AUTO_INCREMENT for table `item_image`
--
ALTER TABLE `item_image`
  MODIFY `item_image_id` bigint(20) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=51;

--
-- AUTO_INCREMENT for table `opening_hour`
--
ALTER TABLE `opening_hour`
  MODIFY `opening_hour_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=51;

--
-- AUTO_INCREMENT for table `option`
--
ALTER TABLE `option`
  MODIFY `option_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=51;

--
-- AUTO_INCREMENT for table `order_item`
--
ALTER TABLE `order_item`
  MODIFY `order_item_id` bigint(20) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=51;

--
-- AUTO_INCREMENT for table `order_item_extra_option`
--
ALTER TABLE `order_item_extra_option`
  MODIFY `order_item_extra_option_id` bigint(20) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=51;

--
-- AUTO_INCREMENT for table `payment_gateway_queue`
--
ALTER TABLE `payment_gateway_queue`
  MODIFY `payment_gateway_queue_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT for table `payment_method`
--
ALTER TABLE `payment_method`
  MODIFY `payment_method_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=51;

--
-- AUTO_INCREMENT for table `payment_method_currency`
--
ALTER TABLE `payment_method_currency`
  MODIFY `pmc_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `plan`
--
ALTER TABLE `plan`
  MODIFY `plan_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=51;

--
-- AUTO_INCREMENT for table `queue`
--
ALTER TABLE `queue`
  MODIFY `queue_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=51;

--
-- AUTO_INCREMENT for table `refunded_item`
--
ALTER TABLE `refunded_item`
  MODIFY `refunded_item_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=51;

--
-- AUTO_INCREMENT for table `restaurant_branch`
--
ALTER TABLE `restaurant_branch`
  MODIFY `restaurant_branch_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=51;

--
-- AUTO_INCREMENT for table `staff`
--
ALTER TABLE `staff`
  MODIFY `staff_id` bigint(20) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `tap_queue`
--
ALTER TABLE `tap_queue`
  MODIFY `tap_queue_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=51;

--
-- AUTO_INCREMENT for table `voucher`
--
ALTER TABLE `voucher`
  MODIFY `voucher_id` bigint(20) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=51;

--
-- AUTO_INCREMENT for table `web_link`
--
ALTER TABLE `web_link`
  MODIFY `web_link_id` bigint(20) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=51;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `addon`
--
ALTER TABLE `addon`
  ADD CONSTRAINT `fk-addon-created_by` FOREIGN KEY (`created_by`) REFERENCES `admin` (`admin_id`) ON DELETE SET NULL,
  ADD CONSTRAINT `fk-addon-updated_by` FOREIGN KEY (`updated_by`) REFERENCES `admin` (`admin_id`) ON DELETE SET NULL;

--
-- Constraints for table `addon_payment`
--
ALTER TABLE `addon_payment`
  ADD CONSTRAINT `fk-addon_payment-addon_uuid` FOREIGN KEY (`addon_uuid`) REFERENCES `addon` (`addon_uuid`) ON DELETE SET NULL,
  ADD CONSTRAINT `fk-addon_payment-restaurant_uuid` FOREIGN KEY (`restaurant_uuid`) REFERENCES `restaurant` (`restaurant_uuid`) ON DELETE SET NULL;

--
-- Constraints for table `agent_assignment`
--
ALTER TABLE `agent_assignment`
  ADD CONSTRAINT `fk-agent_assignment-agent_id` FOREIGN KEY (`agent_id`) REFERENCES `agent` (`agent_id`) ON DELETE CASCADE,
  ADD CONSTRAINT `fk-agent_assignment-business_location_id` FOREIGN KEY (`business_location_id`) REFERENCES `business_location` (`business_location_id`) ON DELETE NO ACTION,
  ADD CONSTRAINT `fk-agent_assignment-restaurant_uuid` FOREIGN KEY (`restaurant_uuid`) REFERENCES `restaurant` (`restaurant_uuid`) ON DELETE NO ACTION;

--
-- Constraints for table `agent_token`
--
ALTER TABLE `agent_token`
  ADD CONSTRAINT `fk-agent_token-agent_id` FOREIGN KEY (`agent_id`) REFERENCES `agent` (`agent_id`) ON DELETE CASCADE;

--
-- Constraints for table `area`
--
ALTER TABLE `area`
  ADD CONSTRAINT `fk-area-city_id` FOREIGN KEY (`city_id`) REFERENCES `city` (`city_id`) ON DELETE NO ACTION;

--
-- Constraints for table `area_delivery_zone`
--
ALTER TABLE `area_delivery_zone`
  ADD CONSTRAINT `fk-area_delivery_zone-area_id` FOREIGN KEY (`area_id`) REFERENCES `area` (`area_id`) ON DELETE NO ACTION,
  ADD CONSTRAINT `fk-area_delivery_zone-city_id` FOREIGN KEY (`city_id`) REFERENCES `city` (`city_id`) ON DELETE NO ACTION,
  ADD CONSTRAINT `fk-area_delivery_zone-country_id` FOREIGN KEY (`country_id`) REFERENCES `country` (`country_id`) ON DELETE NO ACTION,
  ADD CONSTRAINT `fk-area_delivery_zone-delivery_zone_id` FOREIGN KEY (`delivery_zone_id`) REFERENCES `delivery_zone` (`delivery_zone_id`) ON DELETE NO ACTION,
  ADD CONSTRAINT `fk-area_delivery_zone-restaurant_uuid` FOREIGN KEY (`restaurant_uuid`) REFERENCES `restaurant` (`restaurant_uuid`) ON DELETE NO ACTION;

--
-- Constraints for table `bank_discount`
--
ALTER TABLE `bank_discount`
  ADD CONSTRAINT `fk-bank_discount-bank_id` FOREIGN KEY (`bank_id`) REFERENCES `bank` (`bank_id`) ON DELETE CASCADE,
  ADD CONSTRAINT `fk-bank_discount-restaurant_uuid` FOREIGN KEY (`restaurant_uuid`) REFERENCES `restaurant` (`restaurant_uuid`) ON DELETE CASCADE;

--
-- Constraints for table `business_location`
--
ALTER TABLE `business_location`
  ADD CONSTRAINT `fk-business_location-country_id` FOREIGN KEY (`country_id`) REFERENCES `country` (`country_id`) ON DELETE CASCADE,
  ADD CONSTRAINT `fk-business_location-restaurant_uuid` FOREIGN KEY (`restaurant_uuid`) REFERENCES `restaurant` (`restaurant_uuid`) ON DELETE CASCADE;

--
-- Constraints for table `campaign`
--
ALTER TABLE `campaign`
  ADD CONSTRAINT `fk-campaign-restaurant_uuid` FOREIGN KEY (`restaurant_uuid`) REFERENCES `restaurant` (`restaurant_uuid`);

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
-- Constraints for table `city`
--
ALTER TABLE `city`
  ADD CONSTRAINT `fk-city-country_id` FOREIGN KEY (`country_id`) REFERENCES `country` (`country_id`) ON DELETE NO ACTION;

--
-- Constraints for table `country_payment_method`
--
ALTER TABLE `country_payment_method`
  ADD CONSTRAINT `fk-country_payment_method-country_id` FOREIGN KEY (`country_id`) REFERENCES `country` (`country_id`) ON DELETE CASCADE,
  ADD CONSTRAINT `fk-country_payment_method-payment_method_id` FOREIGN KEY (`payment_method_id`) REFERENCES `payment_method` (`payment_method_id`) ON DELETE CASCADE;

--
-- Constraints for table `customer`
--
ALTER TABLE `customer`
  ADD CONSTRAINT `fk-customer-restaurant_uuid` FOREIGN KEY (`restaurant_uuid`) REFERENCES `restaurant` (`restaurant_uuid`) ON DELETE NO ACTION;

--
-- Constraints for table `customer_bank_discount`
--
ALTER TABLE `customer_bank_discount`
  ADD CONSTRAINT `fk-customer_bank_discount-bank_discount_id` FOREIGN KEY (`bank_discount_id`) REFERENCES `bank_discount` (`bank_discount_id`),
  ADD CONSTRAINT `fk-customer_bank_discount-customer_id` FOREIGN KEY (`customer_id`) REFERENCES `customer` (`customer_id`);

--
-- Constraints for table `customer_campaign`
--
ALTER TABLE `customer_campaign`
  ADD CONSTRAINT `fk-customer_campaign-restaurant_uuid` FOREIGN KEY (`restaurant_uuid`) REFERENCES `restaurant` (`restaurant_uuid`) ON DELETE SET NULL,
  ADD CONSTRAINT `fk-customer_campaign-template_uuid` FOREIGN KEY (`template_uuid`) REFERENCES `vendor_email_template` (`template_uuid`);

--
-- Constraints for table `customer_email_template`
--
ALTER TABLE `customer_email_template`
  ADD CONSTRAINT `fk-customer_email_template-restaurant_uuid` FOREIGN KEY (`restaurant_uuid`) REFERENCES `restaurant` (`restaurant_uuid`) ON DELETE SET NULL;

--
-- Constraints for table `customer_voucher`
--
ALTER TABLE `customer_voucher`
  ADD CONSTRAINT `fk-customer_voucher-customer_id` FOREIGN KEY (`customer_id`) REFERENCES `customer` (`customer_id`) ON DELETE NO ACTION,
  ADD CONSTRAINT `fk-customer_voucher-voucher_id` FOREIGN KEY (`voucher_id`) REFERENCES `voucher` (`voucher_id`) ON DELETE NO ACTION;

--
-- Constraints for table `delivery_zone`
--
ALTER TABLE `delivery_zone`
  ADD CONSTRAINT `fk-delivery_zone-business_location_id` FOREIGN KEY (`business_location_id`) REFERENCES `business_location` (`business_location_id`) ON DELETE NO ACTION,
  ADD CONSTRAINT `fk-delivery_zone-country_id` FOREIGN KEY (`country_id`) REFERENCES `country` (`country_id`) ON DELETE NO ACTION,
  ADD CONSTRAINT `fk-delivery_zone-restaurant_uuid` FOREIGN KEY (`restaurant_uuid`) REFERENCES `restaurant` (`restaurant_uuid`) ON DELETE NO ACTION;

--
-- Constraints for table `extra_option`
--
ALTER TABLE `extra_option`
  ADD CONSTRAINT `fk-extra_option-option_id` FOREIGN KEY (`option_id`) REFERENCES `option` (`option_id`) ON DELETE CASCADE;

--
-- Constraints for table `invoice_item`
--
ALTER TABLE `invoice_item`
  ADD CONSTRAINT `fk-invoice_item-addon_uuid` FOREIGN KEY (`addon_uuid`) REFERENCES `addon` (`addon_uuid`) ON DELETE SET NULL,
  ADD CONSTRAINT `fk-invoice_item-invoice_uuid` FOREIGN KEY (`invoice_uuid`) REFERENCES `restaurant_invoice` (`invoice_uuid`) ON DELETE SET NULL,
  ADD CONSTRAINT `fk-invoice_item-order_uuid` FOREIGN KEY (`order_uuid`) REFERENCES `order` (`order_uuid`) ON DELETE SET NULL,
  ADD CONSTRAINT `fk-invoice_item-plan_id` FOREIGN KEY (`plan_id`) REFERENCES `plan` (`plan_id`) ON DELETE SET NULL,
  ADD CONSTRAINT `fk-invoice_item-restaurant_uuid` FOREIGN KEY (`restaurant_uuid`) REFERENCES `restaurant` (`restaurant_uuid`) ON DELETE SET NULL;

--
-- Constraints for table `invoice_payment`
--
ALTER TABLE `invoice_payment`
  ADD CONSTRAINT `fk-invoice_payment-invoice_uuid` FOREIGN KEY (`invoice_uuid`) REFERENCES `restaurant_invoice` (`invoice_uuid`) ON DELETE SET NULL,
  ADD CONSTRAINT `fk-invoice_payment-restaurant_uuid` FOREIGN KEY (`restaurant_uuid`) REFERENCES `restaurant` (`restaurant_uuid`) ON DELETE SET NULL;

--
-- Constraints for table `item`
--
ALTER TABLE `item`
  ADD CONSTRAINT `fk-item-restaurant_uuid` FOREIGN KEY (`restaurant_uuid`) REFERENCES `restaurant` (`restaurant_uuid`) ON DELETE CASCADE;

--
-- Constraints for table `item_image`
--
ALTER TABLE `item_image`
  ADD CONSTRAINT `fk-item_image-item_uuid` FOREIGN KEY (`item_uuid`) REFERENCES `item` (`item_uuid`) ON DELETE CASCADE;

--
-- Constraints for table `item_variant`
--
ALTER TABLE `item_variant`
  ADD CONSTRAINT `fk-item_variant-item_uuid` FOREIGN KEY (`item_uuid`) REFERENCES `item` (`item_uuid`) ON DELETE CASCADE;

--
-- Constraints for table `item_variant_image`
--
ALTER TABLE `item_variant_image`
  ADD CONSTRAINT `fk-item_variant_image-item_uuid` FOREIGN KEY (`item_uuid`) REFERENCES `item` (`item_uuid`) ON DELETE CASCADE,
  ADD CONSTRAINT `fk-item_variant_image-item_variant_uuid` FOREIGN KEY (`item_variant_uuid`) REFERENCES `item_variant` (`item_variant_uuid`) ON DELETE CASCADE;

--
-- Constraints for table `item_variant_option`
--
ALTER TABLE `item_variant_option`
  ADD CONSTRAINT `fk-item_variant_option-item_uuid` FOREIGN KEY (`item_uuid`) REFERENCES `item` (`item_uuid`) ON DELETE CASCADE,
  ADD CONSTRAINT `fk-item_variant_option-item_variant_uuid` FOREIGN KEY (`item_variant_uuid`) REFERENCES `item_variant` (`item_variant_uuid`) ON DELETE CASCADE;

--
-- Constraints for table `opening_hour`
--
ALTER TABLE `opening_hour`
  ADD CONSTRAINT `fk-opening_hour-restaurant_uuid` FOREIGN KEY (`restaurant_uuid`) REFERENCES `restaurant` (`restaurant_uuid`) ON DELETE CASCADE;

--
-- Constraints for table `option`
--
ALTER TABLE `option`
  ADD CONSTRAINT `fk-option-item_uuid` FOREIGN KEY (`item_uuid`) REFERENCES `item` (`item_uuid`) ON DELETE CASCADE;

--
-- Constraints for table `order`
--
ALTER TABLE `order`
  ADD CONSTRAINT `fk-order-area_id` FOREIGN KEY (`area_id`) REFERENCES `area` (`area_id`) ON DELETE SET NULL ON UPDATE CASCADE,
  ADD CONSTRAINT `fk-order-bank_discount_id` FOREIGN KEY (`bank_discount_id`) REFERENCES `bank_discount` (`bank_discount_id`) ON DELETE SET NULL ON UPDATE CASCADE,
  ADD CONSTRAINT `fk-order-country_id` FOREIGN KEY (`shipping_country_id`) REFERENCES `country` (`country_id`) ON DELETE NO ACTION,
  ADD CONSTRAINT `fk-order-customer_id` FOREIGN KEY (`customer_id`) REFERENCES `customer` (`customer_id`) ON DELETE NO ACTION,
  ADD CONSTRAINT `fk-order-delivery_zone_id` FOREIGN KEY (`delivery_zone_id`) REFERENCES `delivery_zone` (`delivery_zone_id`) ON DELETE SET NULL ON UPDATE CASCADE,
  ADD CONSTRAINT `fk-order-payment_method_id` FOREIGN KEY (`payment_method_id`) REFERENCES `payment_method` (`payment_method_id`) ON DELETE NO ACTION,
  ADD CONSTRAINT `fk-order-payment_uuid` FOREIGN KEY (`payment_uuid`) REFERENCES `payment` (`payment_uuid`) ON DELETE NO ACTION,
  ADD CONSTRAINT `fk-order-pickup_location_id` FOREIGN KEY (`pickup_location_id`) REFERENCES `business_location` (`business_location_id`) ON DELETE SET NULL ON UPDATE CASCADE,
  ADD CONSTRAINT `fk-order-restaurant_uuid` FOREIGN KEY (`restaurant_uuid`) REFERENCES `restaurant` (`restaurant_uuid`) ON DELETE NO ACTION,
  ADD CONSTRAINT `fk-order-voucher_id` FOREIGN KEY (`voucher_id`) REFERENCES `voucher` (`voucher_id`) ON DELETE SET NULL ON UPDATE CASCADE;

--
-- Constraints for table `order_item`
--
ALTER TABLE `order_item`
  ADD CONSTRAINT `fk-order_item-item_uuid` FOREIGN KEY (`item_uuid`) REFERENCES `item` (`item_uuid`) ON DELETE SET NULL ON UPDATE SET NULL,
  ADD CONSTRAINT `fk-order_item-order_uuid` FOREIGN KEY (`order_uuid`) REFERENCES `order` (`order_uuid`) ON DELETE CASCADE,
  ADD CONSTRAINT `fk-order_item-restaurant_uuid` FOREIGN KEY (`restaurant_uuid`) REFERENCES `restaurant` (`restaurant_uuid`) ON DELETE NO ACTION;

--
-- Constraints for table `order_item_extra_option`
--
ALTER TABLE `order_item_extra_option`
  ADD CONSTRAINT `fk-order_item_extra_option-extra_option_id` FOREIGN KEY (`extra_option_id`) REFERENCES `extra_option` (`extra_option_id`) ON DELETE SET NULL ON UPDATE SET NULL,
  ADD CONSTRAINT `fk-order_item_extra_option-order_item_id` FOREIGN KEY (`order_item_id`) REFERENCES `order_item` (`order_item_id`) ON DELETE CASCADE;

--
-- Constraints for table `partner`
--
ALTER TABLE `partner`
  ADD CONSTRAINT `fk-partner-bank_id` FOREIGN KEY (`bank_id`) REFERENCES `bank` (`bank_id`) ON DELETE SET NULL ON UPDATE SET NULL;

--
-- Constraints for table `partner_payout`
--
ALTER TABLE `partner_payout`
  ADD CONSTRAINT `fk-partner_payout-bank_id` FOREIGN KEY (`bank_id`) REFERENCES `bank` (`bank_id`) ON DELETE SET NULL ON UPDATE SET NULL,
  ADD CONSTRAINT `fk-partner_payout-partner_uuid` FOREIGN KEY (`partner_uuid`) REFERENCES `partner` (`partner_uuid`) ON DELETE NO ACTION;

--
-- Constraints for table `partner_token`
--
ALTER TABLE `partner_token`
  ADD CONSTRAINT `fk-partner_token-partner_uuid` FOREIGN KEY (`partner_uuid`) REFERENCES `partner` (`partner_uuid`) ON DELETE CASCADE;

--
-- Constraints for table `payment`
--
ALTER TABLE `payment`
  ADD CONSTRAINT `fk-payment-customer_id` FOREIGN KEY (`customer_id`) REFERENCES `customer` (`customer_id`) ON DELETE NO ACTION,
  ADD CONSTRAINT `fk-payment-order_uuid` FOREIGN KEY (`order_uuid`) REFERENCES `order` (`order_uuid`) ON DELETE NO ACTION,
  ADD CONSTRAINT `fk-payment-partner_payout_uuid` FOREIGN KEY (`partner_payout_uuid`) REFERENCES `partner_payout` (`partner_payout_uuid`) ON DELETE SET NULL ON UPDATE SET NULL,
  ADD CONSTRAINT `fk-payment-restaurant_uuid` FOREIGN KEY (`restaurant_uuid`) REFERENCES `restaurant` (`restaurant_uuid`) ON DELETE CASCADE;

--
-- Constraints for table `payment_failed`
--
ALTER TABLE `payment_failed`
  ADD CONSTRAINT `fk-payment_failed-customer_id` FOREIGN KEY (`customer_id`) REFERENCES `customer` (`customer_id`) ON DELETE SET NULL,
  ADD CONSTRAINT `fk-payment_failed-order_uuid` FOREIGN KEY (`order_uuid`) REFERENCES `order` (`order_uuid`) ON DELETE SET NULL;

--
-- Constraints for table `payment_gateway_queue`
--
ALTER TABLE `payment_gateway_queue`
  ADD CONSTRAINT `fk-payment_gateway_queue-restaurant_uuid` FOREIGN KEY (`restaurant_uuid`) REFERENCES `restaurant` (`restaurant_uuid`) ON DELETE NO ACTION;

--
-- Constraints for table `payment_method_currency`
--
ALTER TABLE `payment_method_currency`
  ADD CONSTRAINT `fk-payment_method_currency-payment_method_id` FOREIGN KEY (`payment_method_id`) REFERENCES `payment_method` (`payment_method_id`);

--
-- Constraints for table `queue`
--
ALTER TABLE `queue`
  ADD CONSTRAINT `fk-queue-restaurant_uuid` FOREIGN KEY (`restaurant_uuid`) REFERENCES `restaurant` (`restaurant_uuid`) ON DELETE CASCADE;

--
-- Constraints for table `refund`
--
ALTER TABLE `refund`
  ADD CONSTRAINT `fk-refund-order_uuid` FOREIGN KEY (`order_uuid`) REFERENCES `order` (`order_uuid`) ON DELETE SET NULL ON UPDATE CASCADE,
  ADD CONSTRAINT `fk-refund-payment_uuid` FOREIGN KEY (`payment_uuid`) REFERENCES `payment` (`payment_uuid`) ON DELETE SET NULL ON UPDATE CASCADE,
  ADD CONSTRAINT `fk-refund-restaurant_uuid` FOREIGN KEY (`restaurant_uuid`) REFERENCES `restaurant` (`restaurant_uuid`) ON DELETE NO ACTION;

--
-- Constraints for table `refunded_item`
--
ALTER TABLE `refunded_item`
  ADD CONSTRAINT `fk-refunded_item-item_uuid` FOREIGN KEY (`item_uuid`) REFERENCES `item` (`item_uuid`) ON DELETE SET NULL ON UPDATE SET NULL,
  ADD CONSTRAINT `fk-refunded_item-order_item_id` FOREIGN KEY (`order_item_id`) REFERENCES `order_item` (`order_item_id`) ON DELETE SET NULL ON UPDATE CASCADE,
  ADD CONSTRAINT `fk-refunded_item-order_uuid` FOREIGN KEY (`order_uuid`) REFERENCES `order` (`order_uuid`) ON DELETE SET NULL ON UPDATE CASCADE,
  ADD CONSTRAINT `fk-refunded_item-refund_id` FOREIGN KEY (`refund_id`) REFERENCES `refund` (`refund_id`) ON DELETE CASCADE;

--
-- Constraints for table `restaurant`
--
ALTER TABLE `restaurant`
  ADD CONSTRAINT `fk-restaurant-country_id` FOREIGN KEY (`country_id`) REFERENCES `country` (`country_id`) ON DELETE NO ACTION,
  ADD CONSTRAINT `fk-restaurant-currency_id` FOREIGN KEY (`currency_id`) REFERENCES `currency` (`currency_id`) ON DELETE NO ACTION,
  ADD CONSTRAINT `fk-restaurant-payment_gateway_queue_id` FOREIGN KEY (`payment_gateway_queue_id`) REFERENCES `payment_gateway_queue` (`payment_gateway_queue_id`) ON DELETE SET NULL,
  ADD CONSTRAINT `fk-restaurant-referral_code` FOREIGN KEY (`referral_code`) REFERENCES `partner` (`referral_code`) ON DELETE SET NULL ON UPDATE SET NULL,
  ADD CONSTRAINT `fk-restaurant-tap_queue_id` FOREIGN KEY (`tap_queue_id`) REFERENCES `tap_queue` (`tap_queue_id`) ON DELETE SET NULL;

--
-- Constraints for table `restaurant_addon`
--
ALTER TABLE `restaurant_addon`
  ADD CONSTRAINT `fk-restaurant_addon-addon_uuid` FOREIGN KEY (`addon_uuid`) REFERENCES `addon` (`addon_uuid`) ON DELETE SET NULL,
  ADD CONSTRAINT `fk-restaurant_addon-restaurant_uuid` FOREIGN KEY (`restaurant_uuid`) REFERENCES `restaurant` (`restaurant_uuid`) ON DELETE SET NULL;

--
-- Constraints for table `restaurant_branch`
--
ALTER TABLE `restaurant_branch`
  ADD CONSTRAINT `fk-restaurant_branch-restaurant_uuid` FOREIGN KEY (`restaurant_uuid`) REFERENCES `restaurant` (`restaurant_uuid`) ON DELETE CASCADE;

--
-- Constraints for table `restaurant_currency`
--
ALTER TABLE `restaurant_currency`
  ADD CONSTRAINT `fk-restaurant_currency-currency_id` FOREIGN KEY (`currency_id`) REFERENCES `currency` (`currency_id`) ON DELETE NO ACTION,
  ADD CONSTRAINT `fk-restaurant_currency-restaurant_uuid` FOREIGN KEY (`restaurant_uuid`) REFERENCES `restaurant` (`restaurant_uuid`) ON DELETE NO ACTION;

--
-- Constraints for table `restaurant_delivery`
--
ALTER TABLE `restaurant_delivery`
  ADD CONSTRAINT `fk-restaurant_delivery-area_id` FOREIGN KEY (`area_id`) REFERENCES `area` (`area_id`) ON DELETE CASCADE,
  ADD CONSTRAINT `fk-restaurant_delivery-restaurant_uuid` FOREIGN KEY (`restaurant_uuid`) REFERENCES `restaurant` (`restaurant_uuid`) ON DELETE CASCADE;

--
-- Constraints for table `restaurant_domain_request`
--
ALTER TABLE `restaurant_domain_request`
  ADD CONSTRAINT `fk-restaurant_domain_request-created_by` FOREIGN KEY (`created_by`) REFERENCES `agent` (`agent_id`),
  ADD CONSTRAINT `fk-restaurant_domain_request-restaurant_uuid` FOREIGN KEY (`restaurant_uuid`) REFERENCES `restaurant` (`restaurant_uuid`);

--
-- Constraints for table `restaurant_invoice`
--
ALTER TABLE `restaurant_invoice`
  ADD CONSTRAINT `fk-restaurant_invoice-payment_uuid` FOREIGN KEY (`payment_uuid`) REFERENCES `payment` (`payment_uuid`),
  ADD CONSTRAINT `fk-restaurant_invoice-restaurant_uuid` FOREIGN KEY (`restaurant_uuid`) REFERENCES `restaurant` (`restaurant_uuid`);

--
-- Constraints for table `restaurant_payment_method`
--
ALTER TABLE `restaurant_payment_method`
  ADD CONSTRAINT `fk-restaurant_payment_method-payment_method_id` FOREIGN KEY (`payment_method_id`) REFERENCES `payment_method` (`payment_method_id`) ON DELETE CASCADE,
  ADD CONSTRAINT `fk-restaurant_payment_method-restaurant_uuid` FOREIGN KEY (`restaurant_uuid`) REFERENCES `restaurant` (`restaurant_uuid`) ON DELETE CASCADE;

--
-- Constraints for table `restaurant_theme`
--
ALTER TABLE `restaurant_theme`
  ADD CONSTRAINT `fk-restaurant_theme-restaurant_uuid` FOREIGN KEY (`restaurant_uuid`) REFERENCES `restaurant` (`restaurant_uuid`) ON DELETE CASCADE;

--
-- Constraints for table `restaurant_upload`
--
ALTER TABLE `restaurant_upload`
  ADD CONSTRAINT `fk-restaurant_upload-created_by` FOREIGN KEY (`created_by`) REFERENCES `agent` (`agent_id`),
  ADD CONSTRAINT `fk-restaurant_upload-restaurant_uuid` FOREIGN KEY (`restaurant_uuid`) REFERENCES `restaurant` (`restaurant_uuid`);

--
-- Constraints for table `setting`
--
ALTER TABLE `setting`
  ADD CONSTRAINT `fk-setting-restaurant_uuid` FOREIGN KEY (`restaurant_uuid`) REFERENCES `restaurant` (`restaurant_uuid`);

--
-- Constraints for table `staff_token`
--
ALTER TABLE `staff_token`
  ADD CONSTRAINT `fk-staff_token-staff_id` FOREIGN KEY (`staff_id`) REFERENCES `staff` (`staff_id`) ON DELETE CASCADE;

--
-- Constraints for table `store_updates`
--
ALTER TABLE `store_updates`
  ADD CONSTRAINT `fk-store_updates-restaurant_uuid` FOREIGN KEY (`restaurant_uuid`) REFERENCES `restaurant` (`restaurant_uuid`);

--
-- Constraints for table `store_web_link`
--
ALTER TABLE `store_web_link`
  ADD CONSTRAINT `fk-store_web_link-restaurant_uuid` FOREIGN KEY (`restaurant_uuid`) REFERENCES `restaurant` (`restaurant_uuid`) ON DELETE CASCADE,
  ADD CONSTRAINT `fk-store_web_link-web_link_id` FOREIGN KEY (`web_link_id`) REFERENCES `web_link` (`web_link_id`) ON DELETE CASCADE;

--
-- Constraints for table `subscription`
--
ALTER TABLE `subscription`
  ADD CONSTRAINT `fk-subscription-payment_method_id` FOREIGN KEY (`payment_method_id`) REFERENCES `payment_method` (`payment_method_id`) ON DELETE NO ACTION,
  ADD CONSTRAINT `fk-subscription-payment_uuid` FOREIGN KEY (`payment_uuid`) REFERENCES `subscription_payment` (`payment_uuid`) ON DELETE NO ACTION,
  ADD CONSTRAINT `fk-subscription-plan_id` FOREIGN KEY (`plan_id`) REFERENCES `plan` (`plan_id`) ON DELETE NO ACTION,
  ADD CONSTRAINT `fk-subscription-restaurant_uuid` FOREIGN KEY (`restaurant_uuid`) REFERENCES `restaurant` (`restaurant_uuid`) ON DELETE NO ACTION;

--
-- Constraints for table `subscription_payment`
--
ALTER TABLE `subscription_payment`
  ADD CONSTRAINT `fk-subscription_payment-partner_payout_uuid` FOREIGN KEY (`partner_payout_uuid`) REFERENCES `partner_payout` (`partner_payout_uuid`) ON DELETE SET NULL ON UPDATE SET NULL,
  ADD CONSTRAINT `fk-subscription_payment-restaurant_uuid` FOREIGN KEY (`restaurant_uuid`) REFERENCES `restaurant` (`restaurant_uuid`) ON DELETE CASCADE,
  ADD CONSTRAINT `fk-subscription_payment-subscription_uuid` FOREIGN KEY (`subscription_uuid`) REFERENCES `subscription` (`subscription_uuid`) ON DELETE CASCADE;

--
-- Constraints for table `tap_queue`
--
ALTER TABLE `tap_queue`
  ADD CONSTRAINT `fk-tap_queue-restaurant_uuid` FOREIGN KEY (`restaurant_uuid`) REFERENCES `restaurant` (`restaurant_uuid`) ON DELETE NO ACTION;

--
-- Constraints for table `ticket`
--
ALTER TABLE `ticket`
  ADD CONSTRAINT `fk-ticket-agent_id` FOREIGN KEY (`agent_id`) REFERENCES `agent` (`agent_id`),
  ADD CONSTRAINT `fk-ticket-restaurant_uuid` FOREIGN KEY (`restaurant_uuid`) REFERENCES `restaurant` (`restaurant_uuid`) ON DELETE CASCADE,
  ADD CONSTRAINT `fk-ticket-staff_id` FOREIGN KEY (`staff_id`) REFERENCES `staff` (`staff_id`);

--
-- Constraints for table `ticket_attachment`
--
ALTER TABLE `ticket_attachment`
  ADD CONSTRAINT `fk-ticket_attachment-agent_id` FOREIGN KEY (`ticket_uuid`) REFERENCES `ticket` (`ticket_uuid`),
  ADD CONSTRAINT `fk-ticket_attachment-attachment_uuid` FOREIGN KEY (`attachment_uuid`) REFERENCES `attachment` (`attachment_uuid`);

--
-- Constraints for table `ticket_comment`
--
ALTER TABLE `ticket_comment`
  ADD CONSTRAINT `fk-ticket_comment-agent_id` FOREIGN KEY (`agent_id`) REFERENCES `agent` (`agent_id`),
  ADD CONSTRAINT `fk-ticket_comment-staff_id` FOREIGN KEY (`staff_id`) REFERENCES `staff` (`staff_id`),
  ADD CONSTRAINT `fk-ticket_comment-ticket_uuid` FOREIGN KEY (`ticket_uuid`) REFERENCES `ticket` (`ticket_uuid`);

--
-- Constraints for table `ticket_comment_attachment`
--
ALTER TABLE `ticket_comment_attachment`
  ADD CONSTRAINT `fk-ticket_comment_attachment-attachment_uuid` FOREIGN KEY (`attachment_uuid`) REFERENCES `attachment` (`attachment_uuid`),
  ADD CONSTRAINT `fk-ticket_comment_attachment-ticket_comment_uuid` FOREIGN KEY (`ticket_comment_uuid`) REFERENCES `ticket_comment` (`ticket_comment_uuid`);

--
-- Constraints for table `vendor_campaign`
--
ALTER TABLE `vendor_campaign`
  ADD CONSTRAINT `fk-vendor_campaign-template_uuid` FOREIGN KEY (`template_uuid`) REFERENCES `vendor_email_template` (`template_uuid`);

--
-- Constraints for table `voucher`
--
ALTER TABLE `voucher`
  ADD CONSTRAINT `fk-voucher-restaurant_uuid` FOREIGN KEY (`restaurant_uuid`) REFERENCES `restaurant` (`restaurant_uuid`) ON DELETE CASCADE;

--
-- Constraints for table `web_link`
--
ALTER TABLE `web_link`
  ADD CONSTRAINT `fk-web_link-restaurant_uuid` FOREIGN KEY (`restaurant_uuid`) REFERENCES `restaurant` (`restaurant_uuid`) ON DELETE CASCADE;
COMMIT;


CREATE DATABASE IF NOT EXISTS plugn_test;


/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
