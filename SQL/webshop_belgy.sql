-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Dec 07, 2024 at 06:49 PM
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
-- Database: `webshop_belgy`
--

-- --------------------------------------------------------

--
-- Table structure for table `accounts`
--

CREATE TABLE `accounts` (
  `id` int(11) NOT NULL,
  `email_address` varchar(255) NOT NULL,
  `password` varchar(300) NOT NULL,
  `Firstname` varchar(255) NOT NULL,
  `Lastname` varchar(255) NOT NULL,
  `is_admin` tinyint(1) DEFAULT 0,
  `currency` decimal(10,2) NOT NULL,
  `admin` tinyint(1) DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `accounts`
--

INSERT INTO `accounts` (`id`, `email_address`, `password`, `Firstname`, `Lastname`, `is_admin`, `currency`, `admin`) VALUES
(10, 'user@user.com', '$2y$10$IBttat8wyFeBpz2H23l/VuvXZCmAOSuSTWev6RNX594HxQ3hBcfC.', 'Test', 'User', 0, 1000.00, 0),
(13, 'admin@admin.com', '$2y$12$SYk2Hi0Dsd5suYoC4rd7G.JAuFquGMpoYgqgyO50zZz1oHcxpao5S', 'admin', 'user', 0, 1000.00, 1),
(14, 'john@doe.com', '$2y$10$icVfGhdFtA64o2J5p2hdCuMPZixBngSXQtqBeH5ynpzFKTHoW1idq', 'John', 'Doe', 0, 1000.00, 0),
(15, 'mike.crack@gmail.com', '$2y$10$rcJD6B1ctKgRzH9utEvhneAAGSB.763SGwFKm5zdBj/U8J2JVkPzy', 'Mike', 'Crack', 0, 70.00, 0),
(16, 'Don\'t@mail.me', '$2y$12$6TOaNMBMp4vxi9e8VhxWC.vNeD4drecKTt7a1Y1Gj.fZPdXe9a8We', 'Don\'t', 'Mail', 0, 56.00, 0);

--
-- Triggers `accounts`
--
DELIMITER $$
CREATE TRIGGER `prevent_negative_currency` BEFORE UPDATE ON `accounts` FOR EACH ROW BEGIN
    IF NEW.currency < 0 THEN
        SIGNAL SQLSTATE '45000'
        SET MESSAGE_TEXT = 'Currency cannot be negative';
    END IF;
END
$$
DELIMITER ;

-- --------------------------------------------------------

--
-- Table structure for table `cart`
--

CREATE TABLE `cart` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `product_id` int(11) NOT NULL,
  `product_name` varchar(255) NOT NULL,
  `price` int(11) NOT NULL,
  `quantity` int(11) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `variant_id` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `categories`
--

CREATE TABLE `categories` (
  `id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `categories`
--

INSERT INTO `categories` (`id`, `name`) VALUES
(1, 'Laptops'),
(2, 'Monitors'),
(3, 'SSDs'),
(4, 'Keyboards'),
(5, 'Mice');

-- --------------------------------------------------------

--
-- Table structure for table `orders`
--

CREATE TABLE `orders` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `total_price` decimal(10,2) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `orders`
--

INSERT INTO `orders` (`id`, `user_id`, `total_price`, `created_at`) VALUES
(1, 10, 22.00, '2024-11-27 14:58:12'),
(2, 10, 22.00, '2024-11-27 14:58:40'),
(3, 10, 22.00, '2024-11-27 15:08:41'),
(4, 10, 22.00, '2024-11-27 15:13:54'),
(5, 10, 22.00, '2024-11-27 15:16:38'),
(6, 10, 22.00, '2024-11-27 15:18:28'),
(7, 10, 22.00, '2024-11-27 15:20:08'),
(8, 10, 22.00, '2024-11-27 15:20:14'),
(9, 10, 22.00, '2024-11-27 15:48:22'),
(10, 10, 34.00, '2024-11-27 16:04:46'),
(11, 10, 92.00, '2024-11-27 16:08:10'),
(12, 10, 46.00, '2024-11-27 16:08:55'),
(13, 10, 312.00, '2024-11-28 19:13:57'),
(15, 10, 50.00, '2024-11-28 19:23:32'),
(16, 10, 100.00, '2024-11-28 19:24:23'),
(17, 14, 324.00, '2024-11-28 19:30:56'),
(18, 14, 200.00, '2024-11-29 11:58:11'),
(19, 14, 400.00, '2024-11-29 11:58:32'),
(20, 14, 75.00, '2024-11-29 13:56:17'),
(21, 10, 105.00, '2024-12-05 01:37:39'),
(22, 10, 150.00, '2024-12-07 13:43:56'),
(23, 10, 440.00, '2024-12-07 13:44:41'),
(24, 10, 735.00, '2024-12-07 13:53:34'),
(25, 10, 895.00, '2024-12-07 14:01:23'),
(26, 10, 75.00, '2024-12-07 14:04:24'),
(27, 15, 960.00, '2024-12-07 14:10:50'),
(28, 15, 1000.00, '2024-12-07 14:41:00'),
(29, 15, 0.00, '2024-12-07 14:41:11'),
(30, 15, 0.00, '2024-12-07 14:49:40'),
(31, 15, 0.00, '2024-12-07 14:49:58'),
(32, 15, 0.00, '2024-12-07 14:50:41'),
(33, 16, 200.00, '2024-12-07 15:12:47'),
(34, 16, 400.00, '2024-12-07 15:13:05'),
(35, 16, 400.00, '2024-12-07 15:13:44'),
(36, 15, 620.00, '2024-12-07 15:16:49'),
(37, 10, 200.00, '2024-12-07 15:30:17'),
(38, 10, 70.00, '2024-12-07 15:39:37'),
(39, 10, 567.00, '2024-12-07 15:40:32'),
(40, 10, 160.00, '2024-12-07 15:44:28'),
(41, 15, 350.00, '2024-12-07 15:45:56'),
(42, 15, 24.00, '2024-12-07 15:46:47'),
(43, 15, 977.00, '2024-12-07 15:50:40'),
(44, 15, 977.00, '2024-12-07 15:54:51'),
(45, 15, 6.00, '2024-12-07 15:59:42'),
(46, 15, 6.00, '2024-12-07 16:02:11'),
(47, 15, 6.00, '2024-12-07 16:02:23'),
(48, 15, 1.00, '2024-12-07 16:02:41'),
(49, 15, 4.00, '2024-12-07 16:05:18'),
(50, 10, 400.00, '2024-12-07 16:05:58'),
(51, 10, 600.00, '2024-12-07 16:06:38'),
(52, 16, 584.00, '2024-12-07 16:11:02'),
(53, 16, 210.00, '2024-12-07 16:22:48'),
(54, 16, 150.00, '2024-12-07 16:23:33'),
(55, 15, 230.00, '2024-12-07 16:30:28'),
(56, 15, 150.00, '2024-12-07 16:31:53'),
(57, 15, 550.00, '2024-12-07 16:35:16');

-- --------------------------------------------------------

--
-- Table structure for table `order_items`
--

CREATE TABLE `order_items` (
  `id` int(11) NOT NULL,
  `order_id` int(11) NOT NULL,
  `product_id` int(11) NOT NULL,
  `product_name` varchar(255) NOT NULL,
  `quantity` int(11) NOT NULL,
  `price` decimal(10,2) NOT NULL,
  `main_image_url` varchar(255) DEFAULT NULL,
  `variant_id` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `order_items`
--

INSERT INTO `order_items` (`id`, `order_id`, `product_id`, `product_name`, `quantity`, `price`, `main_image_url`, `variant_id`) VALUES
(1, 1, 1, 'Logitech G PRO X 60 Mechanical Gaming Keyboard White AZERTY', 1, 10.00, 'assets/keyboards/logitech_G_Pro/logitech_G_Pro_white_main.avif', NULL),
(2, 1, 3, 'Razer \'Huntsman V3 Pro Gaming Keyboard Azerty\'', 1, 12.00, 'assets/keyboards/razer_huntsman/razer_huntsman_V3_main.avif', NULL),
(3, 2, 1, 'Logitech G PRO X 60 Mechanical Gaming Keyboard White AZERTY', 1, 10.00, 'assets/keyboards/logitech_G_Pro/logitech_G_Pro_white_main.avif', NULL),
(4, 2, 3, 'Razer \'Huntsman V3 Pro Gaming Keyboard Azerty\'', 1, 12.00, 'assets/keyboards/razer_huntsman/razer_huntsman_V3_main.avif', NULL),
(5, 3, 1, 'Logitech G PRO X 60 Mechanical Gaming Keyboard White AZERTY', 1, 10.00, 'assets/keyboards/logitech_G_Pro/logitech_G_Pro_white_main.avif', NULL),
(6, 3, 3, 'Razer \'Huntsman V3 Pro Gaming Keyboard Azerty\'', 1, 12.00, 'assets/keyboards/razer_huntsman/razer_huntsman_V3_main.avif', NULL),
(7, 4, 1, 'Logitech G PRO X 60 Mechanical Gaming Keyboard White AZERTY', 1, 10.00, 'assets/keyboards/logitech_G_Pro/logitech_G_Pro_white_main.avif', NULL),
(8, 4, 3, 'Razer \'Huntsman V3 Pro Gaming Keyboard Azerty\'', 1, 12.00, 'assets/keyboards/razer_huntsman/razer_huntsman_V3_main.avif', NULL),
(9, 5, 1, 'Logitech G PRO X 60 Mechanical Gaming Keyboard White AZERTY', 1, 10.00, 'assets/keyboards/logitech_G_Pro/logitech_G_Pro_white_main.avif', NULL),
(10, 5, 3, 'Razer \'Huntsman V3 Pro Gaming Keyboard Azerty\'', 1, 12.00, 'assets/keyboards/razer_huntsman/razer_huntsman_V3_main.avif', NULL),
(11, 6, 1, 'Logitech G PRO X 60 Mechanical Gaming Keyboard White AZERTY', 1, 10.00, 'assets/keyboards/logitech_G_Pro/logitech_G_Pro_white_main.avif', NULL),
(12, 6, 3, 'Razer \'Huntsman V3 Pro Gaming Keyboard Azerty\'', 1, 12.00, 'assets/keyboards/razer_huntsman/razer_huntsman_V3_main.avif', NULL),
(13, 7, 1, 'Logitech G PRO X 60 Mechanical Gaming Keyboard White AZERTY', 1, 10.00, 'assets/keyboards/logitech_G_Pro/logitech_G_Pro_white_main.avif', NULL),
(14, 7, 3, 'Razer \'Huntsman V3 Pro Gaming Keyboard Azerty\'', 1, 12.00, 'assets/keyboards/razer_huntsman/razer_huntsman_V3_main.avif', NULL),
(15, 8, 1, 'Logitech G PRO X 60 Mechanical Gaming Keyboard White AZERTY', 1, 10.00, 'assets/keyboards/logitech_G_Pro/logitech_G_Pro_white_main.avif', NULL),
(16, 8, 3, 'Razer \'Huntsman V3 Pro Gaming Keyboard Azerty\'', 1, 12.00, 'assets/keyboards/razer_huntsman/razer_huntsman_V3_main.avif', NULL),
(17, 9, 1, 'Logitech G PRO X 60 Mechanical Gaming Keyboard White AZERTY', 1, 10.00, 'assets/keyboards/logitech_G_Pro/logitech_G_Pro_white_main.avif', NULL),
(18, 9, 3, 'Razer \'Huntsman V3 Pro Gaming Keyboard Azerty\'', 1, 12.00, 'assets/keyboards/razer_huntsman/razer_huntsman_V3_main.avif', NULL),
(19, 10, 1, 'Logitech G PRO X 60 Mechanical Gaming Keyboard White AZERTY', 2, 10.00, 'assets/keyboards/logitech_G_Pro/logitech_G_Pro_white_main.avif', NULL),
(20, 10, 4, 'SteelSeries Apex Pro TKL Wireless 2023 Azerty', 2, 12.00, 'assets/keyboards/steelseries_apex_wireless/steelseries_apex_pro_TKL_wireless_main.avif', NULL),
(21, 10, 3, 'Razer \'Huntsman V3 Pro Gaming Keyboard Azerty\'', 4, 12.00, 'assets/keyboards/razer_huntsman/razer_huntsman_V3_main.avif', NULL),
(22, 11, 1, 'Logitech G PRO X 60 Mechanical Gaming Keyboard White AZERTY', 2, 10.00, 'assets/keyboards/logitech_G_Pro/logitech_G_Pro_white_main.avif', NULL),
(23, 11, 4, 'SteelSeries Apex Pro TKL Wireless 2023 Azerty', 2, 12.00, 'assets/keyboards/steelseries_apex_wireless/steelseries_apex_pro_TKL_wireless_main.avif', NULL),
(24, 11, 3, 'Razer \'Huntsman V3 Pro Gaming Keyboard Azerty\'', 4, 12.00, 'assets/keyboards/razer_huntsman/razer_huntsman_V3_main.avif', NULL),
(25, 12, 1, 'Logitech G PRO X 60 Mechanical Gaming Keyboard White AZERTY', 1, 10.00, 'assets/keyboards/logitech_G_Pro/logitech_G_Pro_white_main.avif', NULL),
(26, 12, 3, 'Razer \'Huntsman V3 Pro Gaming Keyboard Azerty\'', 1, 12.00, 'assets/keyboards/razer_huntsman/razer_huntsman_V3_main.avif', NULL),
(27, 12, 4, 'SteelSeries Apex Pro TKL Wireless 2023 Azerty', 1, 12.00, 'assets/keyboards/steelseries_apex_wireless/steelseries_apex_pro_TKL_wireless_main.avif', NULL),
(28, 12, 5, 'Corsair K70 RGB Core Gaming Keyboard Azerty Black', 1, 12.00, 'assets/keyboards/corsair_K70/Corsair_K70_main.avif', NULL),
(29, 13, 1, 'Logitech G PRO X 60 Mechanical Gaming Keyboard White AZERTY', 2, 50.00, 'assets/keyboards/logitech_G_Pro/logitech_G_Pro_white_main.avif', NULL),
(30, 13, 1, 'Logitech G PRO X 60 Mechanical Gaming Keyboard Black AZERTY', 1, 50.00, 'assets/keyboards/logitech_G_Pro/logitech_G_Pro_white_main.avif', NULL),
(31, 13, 4, 'SteelSeries Apex Pro TKL Wireless 2023 Azerty', 1, 12.00, 'assets/keyboards/steelseries_apex_wireless/steelseries_apex_pro_TKL_wireless_main.avif', NULL),
(32, 15, 1, 'Logitech G PRO X 60 Mechanical Gaming Keyboard Black AZERTY', 1, 50.00, 'assets/keyboards/logitech_G_pro/logitech_G_Pro_black_main.avif', 1),
(33, 16, 1, 'Logitech G PRO X 60 Mechanical Gaming Keyboard White AZERTY', 1, 50.00, 'assets/keyboards/logitech_G_Pro/logitech_G_Pro_white_main.avif', NULL),
(34, 16, 1, 'Logitech G PRO X 60 Mechanical Gaming Keyboard Black AZERTY', 1, 50.00, 'assets/keyboards/logitech_G_pro/logitech_G_Pro_black_main.avif', 1),
(35, 17, 1, 'Logitech G PRO X 60 Mechanical Gaming Keyboard White AZERTY', 2, 50.00, 'assets/keyboards/logitech_G_Pro/logitech_G_Pro_white_main.avif', NULL),
(36, 17, 1, 'Logitech G PRO X 60 Mechanical Gaming Keyboard Black AZERTY', 1, 50.00, 'assets/keyboards/logitech_G_pro/logitech_G_Pro_black_main.avif', 1),
(37, 17, 4, 'SteelSeries Apex Pro TKL Wireless 2023 Azerty', 1, 12.00, 'assets/keyboards/steelseries_apex_wireless/steelseries_apex_pro_TKL_wireless_main.avif', NULL),
(38, 17, 5, 'Corsair K70 RGB Core Gaming Keyboard Azerty Black', 1, 12.00, 'assets/keyboards/corsair_K70/Corsair_K70_main.avif', NULL),
(39, 17, 6, 'MSI Katana A15 AI B8VG-486BE', 1, 200.00, 'assets/laptops/MSI_katana_A15/MSI_Katana_A15_main.avif', NULL),
(40, 18, 6, 'MSI Katana A15 AI B8VG-486BE', 4, 200.00, 'assets/laptops/MSI_katana_A15/MSI_Katana_A15_main.avif', NULL),
(41, 19, 6, 'MSI Katana A15 AI B8VG-486BE', 3, 200.00, 'assets/laptops/MSI_katana_A15/MSI_Katana_A15_main.avif', NULL),
(42, 20, 3, 'Razer \'Huntsman V3 Pro Gaming Keyboard Azerty\'', 1, 75.00, 'assets/keyboards/razer_huntsman/razer_huntsman_V3_main.avif', NULL),
(43, 21, 1, 'Logitech G PRO X 60 Mechanical Gaming Keyboard White AZERTY', 2, 50.00, 'assets/keyboards/logitech_G_pro/logitech_G_Pro_white_main.avif', NULL),
(44, 21, 1, 'Logitech G PRO X 60 Mechanical Gaming Keyboard Black AZERTY', 2, 55.00, 'assets/keyboards/logitech_G_pro/logitech_G_Pro_black_main.avif', 1),
(45, 22, 1, 'Logitech G PRO X 60 Mechanical Gaming Keyboard Black AZERTY', 1, 80.00, 'assets/keyboards/logitech_G_pro/logitech_G_Pro_black_main.avif', 1),
(46, 22, 1, 'Logitech G PRO X 60 Mechanical Gaming Keyboard White AZERTY', 2, 70.00, 'assets/keyboards/logitech_G_pro/logitech_G_Pro_white_main.avif', NULL),
(47, 23, 17, 'ASUS TUF Gaming F17 FX707VV-HX208W Azerty', 1, 200.00, 'assets/laptops/ASUS_TUF_Gaming/ASUS_TUF_Gaming_F17_main.avif', NULL),
(48, 23, 26, 'LG UltraGear 27GS95QE-B', 1, 165.00, 'assets/monitors/LG_UltraGear/LG_UltraGear_main.avif', NULL),
(49, 23, 27, 'Samsung T9 Portable SSD 4TB Black', 2, 75.00, 'assets/SSDs/Samsung_T9/Samsung_T9_main.avif', NULL),
(50, 24, 1, 'Logitech G PRO X 60 Mechanical Gaming Keyboard Black AZERTY', 1, 80.00, 'assets/keyboards/logitech_G_pro/logitech_G_Pro_black_main.avif', 1),
(51, 24, 16, 'Lenovo Yoga Pro 9 14IRP8 83BU006EMB Azerty', 1, 200.00, 'assets/laptops/Lenovo_Yoga_Pro_9/Lenovo_Yoga_Pro_9_main.avif', NULL),
(52, 24, 23, 'Corsair Nightsabre Wireless RGB Gaming Mouse', 1, 30.00, 'assets/mice/Corsair_Nightsabre/Corsair_Nightsabre_main.avif', NULL),
(53, 24, 25, 'Samsung LS32DG802SUXEN', 2, 175.00, 'assets/monitors/Samsung_LS32DG802SUXEN/Samsung_LS32DG802SUXEN_main.avif', NULL),
(54, 24, 27, 'Samsung T9 Portable SSD 4TB Black', 1, 75.00, 'assets/SSDs/Samsung_T9/Samsung_T9_main.avif', NULL),
(55, 25, 5, 'Corsair K70 RGB Core Gaming Keyboard Azerty Black', 1, 25.00, 'assets/keyboards/corsair_K70/Corsair_K70_main.avif', NULL),
(56, 25, 16, 'Lenovo Yoga Pro 9 14IRP8 83BU006EMB Azerty', 1, 200.00, 'assets/laptops/Lenovo_Yoga_Pro_9/Lenovo_Yoga_Pro_9_main.avif', NULL),
(57, 25, 21, 'Logitech G Pro X Superlight Wireless Gaming Mouse Black', 1, 20.00, 'assets/mice/Logitech_G_Pro_X_Superlight/Logitech_G_Pro_X_Superlight_main.avif', NULL),
(58, 25, 25, 'Samsung LS32DG802SUXEN', 2, 175.00, 'assets/monitors/Samsung_LS32DG802SUXEN/Samsung_LS32DG802SUXEN_main.avif', NULL),
(59, 25, 27, 'Samsung T9 Portable SSD 4TB Black', 4, 75.00, 'assets/SSDs/Samsung_T9/Samsung_T9_main.avif', NULL),
(60, 26, 27, 'Samsung T9 Portable SSD 4TB Black', 1, 75.00, 'assets/SSDs/Samsung_T9/Samsung_T9_main.avif', NULL),
(61, 27, 1, 'Logitech G PRO X 60 Mechanical Gaming Keyboard Black AZERTY', 1, 80.00, 'assets/keyboards/logitech_G_pro/logitech_G_Pro_black_main.avif', 1),
(62, 27, 16, 'Lenovo Yoga Pro 9 14IRP8 83BU006EMB Azerty', 1, 200.00, 'assets/laptops/Lenovo_Yoga_Pro_9/Lenovo_Yoga_Pro_9_main.avif', NULL),
(63, 27, 23, 'Corsair Nightsabre Wireless RGB Gaming Mouse', 1, 30.00, 'assets/mice/Corsair_Nightsabre/Corsair_Nightsabre_main.avif', NULL),
(64, 27, 25, 'Samsung LS32DG802SUXEN', 2, 175.00, 'assets/monitors/Samsung_LS32DG802SUXEN/Samsung_LS32DG802SUXEN_main.avif', NULL),
(65, 27, 27, 'Samsung T9 Portable SSD 4TB Black', 4, 75.00, 'assets/SSDs/Samsung_T9/Samsung_T9_main.avif', NULL),
(66, 28, 17, 'ASUS TUF Gaming F17 FX707VV-HX208W Azerty', 5, 200.00, 'assets/laptops/ASUS_TUF_Gaming/ASUS_TUF_Gaming_F17_main.avif', NULL),
(67, 30, 16, 'Lenovo Yoga Pro 9 14IRP8 83BU006EMB Azerty', 1, 200.00, 'assets/laptops/Lenovo_Yoga_Pro_9/Lenovo_Yoga_Pro_9_main.avif', NULL),
(68, 31, 16, 'Lenovo Yoga Pro 9 14IRP8 83BU006EMB Azerty', 5, 200.00, 'assets/laptops/Lenovo_Yoga_Pro_9/Lenovo_Yoga_Pro_9_main.avif', NULL),
(69, 32, 6, 'MSI Katana A15 AI B8VG-486BE', 6, 220.00, 'assets/laptops/MSI_katana_A15/MSI_Katana_A15_main.avif', NULL),
(70, 33, 16, '', 1, 200.00, NULL, NULL),
(71, 34, 16, '', 4, 200.00, NULL, NULL),
(72, 35, 16, '', 1, 200.00, NULL, NULL),
(73, 36, 17, '', 2, 200.00, NULL, NULL),
(74, 36, 18, '', 2, 200.00, NULL, NULL),
(75, 36, 16, '', 1, 200.00, NULL, NULL),
(76, 36, 21, '', 1, 20.00, NULL, NULL),
(77, 37, 16, '', 1, 200.00, NULL, NULL),
(78, 38, 1, '', 1, 70.00, NULL, NULL),
(79, 39, 1, '', 1, 70.00, NULL, NULL),
(80, 39, 6, '', 1, 220.00, NULL, NULL),
(81, 39, 4, '', 1, 12.00, NULL, NULL),
(82, 39, 22, '', 1, 25.00, NULL, NULL),
(83, 39, 26, '', 1, 165.00, NULL, NULL),
(84, 39, 27, '', 1, 75.00, NULL, NULL),
(85, 40, 20, '', 5, 15.00, NULL, NULL),
(86, 40, 21, '', 3, 20.00, NULL, NULL),
(87, 40, 22, '', 1, 25.00, NULL, NULL),
(88, 41, 1, '', 1, 80.00, NULL, 1),
(89, 41, 18, '', 1, 200.00, NULL, NULL),
(90, 41, 21, '', 2, 20.00, NULL, NULL),
(91, 41, 23, '', 2, 30.00, NULL, NULL),
(92, 42, 4, '', 2, 12.00, NULL, NULL),
(93, 43, 4, '', 3, 12.00, NULL, NULL),
(94, 43, 3, '', 3, 60.00, NULL, NULL),
(95, 43, 5, '', 3, 25.00, NULL, NULL),
(96, 43, 16, '', 1, 200.00, NULL, NULL),
(97, 43, 6, '', 1, 220.00, NULL, NULL),
(98, 43, 21, '', 3, 20.00, NULL, NULL),
(99, 43, 23, '', 3, 30.00, NULL, NULL),
(100, 44, 4, '', 3, 12.00, NULL, NULL),
(101, 44, 3, '', 3, 60.00, NULL, NULL),
(102, 44, 5, '', 3, 25.00, NULL, NULL),
(103, 44, 16, '', 1, 200.00, NULL, NULL),
(104, 44, 6, '', 1, 220.00, NULL, NULL),
(105, 44, 21, '', 3, 20.00, NULL, NULL),
(106, 44, 23, '', 3, 30.00, NULL, NULL),
(107, 45, 27, '', 6, 1.00, NULL, NULL),
(108, 46, 27, '', 6, 1.00, NULL, NULL),
(109, 47, 27, '', 6, 1.00, NULL, NULL),
(110, 48, 27, '', 5, 1.00, NULL, NULL),
(111, 49, 27, '', 4, 1.00, NULL, NULL),
(112, 50, 19, '', 2, 200.00, NULL, NULL),
(113, 51, 19, '', 1, 200.00, NULL, NULL),
(114, 51, 18, '', 1, 200.00, NULL, NULL),
(115, 51, 17, '', 1, 200.00, NULL, NULL),
(116, 52, 16, '', 1, 200.00, NULL, NULL),
(117, 52, 23, '', 1, 30.00, NULL, NULL),
(118, 52, 25, '', 2, 175.00, NULL, NULL),
(119, 52, 27, '', 4, 1.00, NULL, NULL),
(120, 53, 3, '', 1, 60.00, NULL, NULL),
(121, 53, 1, '', 1, 70.00, NULL, NULL),
(122, 53, 1, '', 1, 80.00, NULL, 1),
(123, 54, 1, '', 1, 70.00, NULL, NULL),
(124, 54, 1, '', 1, 80.00, NULL, 1),
(125, 55, 1, '', 2, 80.00, NULL, 1),
(126, 55, 1, '', 1, 70.00, NULL, NULL),
(127, 56, 1, '', 1, 80.00, NULL, 1),
(128, 56, 1, '', 1, 70.00, NULL, NULL),
(129, 57, 1, '', 1, 80.00, NULL, 1),
(130, 57, 1, '', 1, 70.00, NULL, NULL),
(131, 57, 17, '', 2, 200.00, NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `products`
--

CREATE TABLE `products` (
  `id` int(11) NOT NULL,
  `category_id` int(11) DEFAULT NULL,
  `name` varchar(255) NOT NULL,
  `description` text DEFAULT NULL,
  `price` decimal(10,2) DEFAULT NULL,
  `main_image_url` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `products`
--

INSERT INTO `products` (`id`, `category_id`, `name`, `description`, `price`, `main_image_url`) VALUES
(1, 4, 'Logitech G PRO X 60 Mechanical Gaming Keyboard White AZERTY', 'Outsmart your opponents with the Logitech G Pro X 60 mechanical gaming keyboard. This wireless keyboard features brown GX Tactile mechanical switches, allowing you to feel feedback when you press a key without hearing it. It\'s a 60 percent gaming keyboard, making it easy to take to a LAN party. Connect your wireless gaming keyboard with the included USB adapter and game for up to 50 hours without recharging. Customize the lighting of your keyboard via the Logitech G HUB with 16.8 million different colors, turning your setup into a real rainbow.', 70.00, 'assets/keyboards/logitech_G_pro/logitech_G_Pro_white_main.avif'),
(3, 4, 'Razer \'Huntsman V3 Pro Gaming Keyboard Azerty\'', 'Always play your games with precise keystrokes using the Razer Huntsman V3 Pro Gaming Keyboard. This wired gaming keyboard features Razer Gen-2 optical switches, ensuring you game comfortably and accurately. With an adjustable actuation point between 0.1 and 4 millimeters, you can set exactly when your key registers. Control your volume via the dedicated media buttons on your gaming keyboard and quickly switch to your favorite track on Spotify. This is a full gaming keyboard with a numeric pad, allowing you to use your gaming keyboard for more than just gaming. Connect this gaming keyboard with the USB-C to USB-A cable. This makes the keyboard plug and play, allowing you to use it immediately. Customize the RGB lighting per key via the Razer Synapse software and choose from 16.8 million colors. This way, your gaming keyboard matches the rest of your setup.', 60.00, 'assets/keyboards/razer_huntsman/razer_huntsman_V3_main.avif'),
(4, 4, 'SteelSeries Apex Pro TKL Wireless 2023 Azerty', 'Outsmart your opponents even faster with the SteelSeries Apex Pro TKL Wireless 2023. This wireless gaming keyboard is opto-mechanical and features OmniPoint 2.0 switches with an adjustable actuation point between 0.2 and 3.8 millimeters. This allows you to determine how far you need to press a key before it registers. You can also program two actions under one key on the Apex Pro TKL Wireless. For example, you can walk when pressing the key halfway and run when pressing it fully. Additionally, you can set up to 5 different profiles, enabling you to use the right key combinations for each game. Switch between your profiles and adjust settings such as volume via the OLED smart display. Use this gaming keyboard wirelessly via Bluetooth 5.0 or the wireless dongle. Customize the Apex Pro TKL via the SteelSeries Engine software, so the keyboard does exactly what you want. Easily take this compact TKL gaming keyboard to your friends or a LAN party and game comfortably with the included wrist rest.', 12.00, 'assets/keyboards/steelseries_apex_wireless/steelseries_apex_pro_TKL_wireless_main.avif'),
(5, 4, 'Corsair K70 RGB Core Gaming Keyboard Azerty Black', 'Boost your gaming setup with the Corsair K70 RGB Core Gaming Keyboard Black. This wired keyboard features red MLX Linear mechanical switches, so you won\'t feel or hear feedback when you press a key. You can also set two actions under one key. For example, walk when you press the key halfway and run when you press it fully. This is a full gaming keyboard with a numeric pad, allowing you to use it for more than just gaming. Connect this gaming keyboard with the USB A cable, making it plug and play so you can use it immediately. Customize the RGB lighting via the iCUE software, choosing from 16.8 million colors to match your gaming keyboard with the rest of your setup.', 25.00, 'assets/keyboards/corsair_K70/Corsair_K70_main.avif'),
(6, 1, 'MSI Katana A15 AI B8VG-486BE', 'Play and stream your gameplay on the 15-inch MSI Katana A15 AI B8VG-486BE gaming laptop. This gaming laptop has an 8000 series AMD Ryzen 7 processor and 16 gigabytes of DDR5 RAM, allowing you to edit your gameplay videos without any problems. Additionally, enjoy extra AI features, thanks to the NPU in the AMD processor. This NPU core works well with the RTX 4070 graphics card, ensuring you always have the right settings while gaming. With the NVIDIA GeForce RTX 4070 graphics card, you can smoothly play heavy games like Elden Ring and Starfield at 60 frames per second in full HD. You can also engage in QHD or 4K gaming with less demanding games, enjoying razor-sharp graphics in GTA V. This powerful combination allows you to run a stream or editing program in the background without any delays. Moreover, enjoy a light show while gaming thanks to the RGB lighting in the keyboard. Want to take your gaming a step further? Easily expand the memory and storage capacity.', 220.00, 'assets/laptops/MSI_katana_A15/MSI_Katana_A15_main.avif'),
(16, 1, 'Lenovo Yoga Pro 9 14IRP8 83BU006EMB Azerty', 'You create game designs and 3D animations with the powerful 14-inch Lenovo Yoga Pro 9 14IRP8 83BU006EMB. Use the touchscreen to make notes, edits, and annotations on the screen. You\'ll see a lot of details and enjoy lifelike colors on the 3K mini LED display with a brightness of 1200 nits. You can multitask between several demanding programs and create the most intensive graphic edits thanks to the 13th generation Intel Core i9 processor, 64 gigabytes of DDR5 RAM, and the NVIDIA GeForce RTX 4070 graphics card. You can also play heavy games on this laptop. The 165-hertz refresh rate ensures you see your animations and videos without stuttering. The sound is pure and clear thanks to the Dolby Atmos speakers. Working late into the night? You can easily see your keys in the dark with the backlit keyboard. Note: Always connect the charger while gaming; otherwise, you will get lower performance and the battery will be drained within a few hours.', 200.00, 'assets/laptops/Lenovo_Yoga_Pro_9/Lenovo_Yoga_Pro_9_main.avif'),
(17, 1, 'ASUS TUF Gaming F17 FX707VV-HX208W Azerty', 'Work smoothly in heavier programs and play mid-range games with the ASUS TUF Gaming F17 FX707VV-HX208W 17-inch gaming laptop. Thanks to the 13th generation Intel Core i7 processor and 16 gigabytes of DDR5 RAM, this laptop is powerful enough for these tasks. You can seamlessly edit your gameplay videos in Premiere Pro and stream in full HD on Twitch. With the NVIDIA GeForce RTX 4060, you can effortlessly play mid-range games. Think of games like Hogwarts Legacy at 60 frames per second in full HD. The 144Hz display ensures fast-moving images, so you can spot your opponents in Call of Duty before they see you. Want to run even more demanding programs or stream in 4K? Expand the RAM to 32 gigabytes to be prepared for new games and updates. Note: Always connect the charger while gaming; otherwise, you will get lower performance and the battery will be drained within a few hours.', 200.00, 'assets/laptops/ASUS_TUF_Gaming/ASUS_TUF_Gaming_F17_main.avif'),
(18, 1, 'MSI Thin 15 B13VE-2052BE Azerty', 'Play light games like Call of Duty Modern Warfare III smoothly on the MSI Thin 15 B13VE-2052BE 15-inch gaming laptop. With the NVIDIA GeForce RTX 4050 graphics card, you can run these games, including Fortnite and Minecraft, without any lag. Additionally, you can smoothly play older major games like GTA V with this card. This laptop features a 13th generation Intel Core i7 processor and 16 gigabytes of DDR4 RAM. This allows you to run multiple programs simultaneously, making it also suitable for study or work. For example, you can start a small stream via Twitch. Enjoy fast visuals on your screen thanks to the 144Hz refresh rate, which is useful for fast-paced games. This way, you can spot opponents in shooters earlier. Do you like gaming in the evening? Turn on your keyboard lighting so you can see all your moves even in the dark. Note: Always connect the charger while gaming; otherwise, you will get lower performance and the battery will be drained within a few hours.', 200.00, 'assets/laptops/MSI_Thin_15/MSI_Thin_15_main.avif'),
(19, 1, 'HP VICTUS 16-r1054nb Azerty', 'Enjoy smooth visuals while playing your heavy games on the HP VICTUS 16-r1054nb 16-inch gaming laptop. This is made possible by the combination of the 14th generation Intel Core i7 processor, 16 gigabytes of DDR5 RAM, and the NVIDIA GeForce RTX 4070 graphics card. Games like Elden Ring, Starfield, and Flight Simulator run without any lag in QHD. Additionally, you can stream your gameplay smoothly in full HD via Twitch, so your viewers can enjoy your gameplay. If you play fast-paced games like F1 or Call of Duty, you will appreciate the 144Hz refresh rate, which ensures fast-moving images on your screen. This helps you spot opponents earlier in shooters and take smooth sharp turns. You can store everything on the 1 terabyte SSD, which allows you to start your games very quickly. Do you like gaming in the evening? Turn on your keyboard backlighting, so you can see all your moves in the dark. Note: Always connect the charger while gaming; otherwise, you will get lower performance and the battery will drain within a few hours.', 200.00, 'assets/laptops/HP_VICTUS_16/HP_VICTUS_16_main.avif'),
(20, 5, 'SteelSeries Aerox 9 Wireless Gaming Mouse Black', 'The SteelSeries Aerox 9 Wireless Gaming Mouse Black is the ideal lightweight mouse for games with lots of in-game actions. With 12 thumb buttons, you can assign every important action of your game to a button, allowing you to win every build battle in Fortnite. You can connect this wireless gaming mouse via the included USB receiver or Bluetooth. Prefer wired gaming? You can also game wired with the included USB cable. Set the optical sensor up to a maximum of 18,000 DPI, allowing you to move the cursor quickly and accurately across your screen. With its honeycomb design, the Aerox 9 weighs only 89 grams, making it one of the lightest wireless gaming mice. You can also customize the mouse lighting, choosing from one of the 16.8 million colors to match your setup.', 15.00, 'assets/mice/SteelSeries_Aerox_9/SteelSeries_Aerox_9_main.avif'),
(21, 5, 'Logitech G Pro X Superlight Wireless Gaming Mouse Black', 'With the Logitech G Pro X Superlight Wireless Gaming Mouse Black, you get a fast and lightweight Pro mouse. Thanks to the optical sensor with 25,600 DPI, you can quickly move the wireless gaming mouse across your desk during fast-paced games. This ensures you always respond with precision while gaming. The 2 extra thumb buttons can be customized, allowing you to react faster than your opponent when switching weapons or performing a special attack. You connect this gaming mouse wirelessly via the USB-A dongle, giving you plenty of freedom of movement while gaming. This wireless gaming mouse has a battery life of 70 hours, allowing you to game for long sessions without needing to recharge.', 20.00, 'assets/mice/Logitech_G_Pro_X_Superlight/Logitech_G_Pro_X_Superlight_main.avif'),
(22, 5, 'SteelSeries Aerox 5 Wireless Gaming Mouse Black', 'The SteelSeries Aerox 5 Wireless Gaming Mouse Black combines lightweight design with precision and freedom of movement. You can connect this wireless gaming mouse via the included USB receiver or Bluetooth. Prefer wired gaming? You can also game wired with the included USB cable. Set the optical sensor up to a maximum of 18,000 DPI, allowing you to move the cursor quickly and accurately across your screen. Thanks to its honeycomb design, the Aerox 5 weighs only 74 grams, making it one of the lightest wireless gaming mice available. With 5 programmable buttons, you can control functions such as weapon switching in Call of Duty faster than your opponent. You can also customize the mouse lighting, choosing from one of the 16.8 million colors to match your setup.', 25.00, 'assets/mice/SteelSeries_Aerox_5/SteelSeries_Aerox_5_main.avif'),
(23, 5, 'Corsair Nightsabre Wireless RGB Gaming Mouse', 'Game comfortably with the Corsair Nightsabre Wireless RGB Gaming Mouse. With the optical sensor at 26,000 DPI, you can quickly move the wireless gaming mouse across your desk during fast-paced games. This ensures you always respond with precision while gaming. You can also easily change the DPI while gaming. With 11 buttons, you can assign all your shortcuts to a separate button, allowing you to react even faster during gameplay. Set up to 5 different profiles so you can use the right shortcuts for each game. Switch between profiles effortlessly so you always have the right shortcuts at hand. Connect this gaming mouse wirelessly via the USB-A dongle or Bluetooth, giving you plenty of freedom of movement while gaming. This wireless gaming mouse has a battery life of 65 hours, allowing you to game for long sessions without needing to recharge. Customize the RGB lighting via the Corsair iCUE software and choose from 16.8 million colors to match your setup.', 30.00, 'assets/mice/Corsair_Nightsabre/Corsair_Nightsabre_main.avif'),
(24, 2, 'MSI G2712F', 'Play all your games with smooth visuals on the MSI G2712F. This 27-inch full HD gaming monitor has a refresh rate of 180 hertz and a 1ms response time, allowing you to play your games without stuttering. The colors pop on your screen thanks to a high color coverage and depth of 17 million colors. You also see clear images from every viewing angle thanks to the IPS panel. If you have an AMD graphics card, the FreeSync technology ensures that your monitor and graphics card are in sync. Tilt your gaming monitor to always game ergonomically. Note: The maximum refresh rate via HDMI is 144 hertz. For 180 hertz gaming, connect the monitor via DisplayPort.', 150.00, 'assets/monitors/MSI_G2712F/MSI_G2712F_main.avif'),
(25, 2, 'Samsung LS32DG802SUXEN', 'See all your in-game opponents on the 32-inch 4K Samsung LS32DG802SUXEN gaming monitor and secure your victory. The high refresh rate of 240 hertz and 0.03 ms response time ensure smooth movements and transitions during gaming, so you won\'t experience any stuttering. This gaming monitor features an OLED panel, enhancing colors, shadows, and graphics. Additionally, all gameplay and cutscenes look even better on the sharp 4K screen with HDR10 support. If you have an AMD or NVIDIA graphics card, it will always sync with the monitor thanks to FreeSync Premium PRO or G-Sync Compatible adaptive sync. Furthermore, you can adjust the height of this gaming monitor or tilt it as desired for a comfortable seating position during long gaming marathons. Note: The maximum refresh rate via HDMI is 144 hertz. For 240 hertz gaming, connect the monitor via DisplayPort.', 175.00, 'assets/monitors/Samsung_LS32DG802SUXEN/Samsung_LS32DG802SUXEN_main.avif'),
(26, 2, 'LG UltraGear 27GS95QE-B', 'Play your favorite games on a bright 26.5-inch QHD LG UltraGear 27GS95QE-B gaming monitor. The high refresh rate of 240 hertz and 0.03 ms response time ensure smooth movements and transitions during gaming, so you won\'t experience any stuttering. All gameplay and cutscenes look even better on the sharp QHD screen with DisplayHDR 400 and HDR10 support. If you have an AMD or NVIDIA graphics card, it will always sync with the monitor thanks to FreeSync and G-Sync. Additionally, you can adjust the height of this gaming monitor or tilt it as desired for a comfortable seating position during long gaming marathons. Note: The maximum refresh rate via HDMI is 144 hertz. For 240 hertz gaming, connect the monitor via DisplayPort.', 165.00, 'assets/monitors/LG_UltraGear/LG_UltraGear_main.avif'),
(27, 3, 'Samsung T9 Portable SSD 4TB Black', 'Transfer files quickly and take them anywhere with the Samsung T9 Portable SSD 4TB Black external SSD. The high read and write speeds of 2,000 megabytes per second make the T9 nearly twice as fast as the T7. This allows you to transfer 50 gigabytes of 4K video material in just half a minute. Thanks to its compact design and light weight, you can easily slip the SSD into your bag or jacket pocket, so you always have access to your files. If you accidentally drop it, don\'t worry. The SSD is protected against falls up to 3 meters and can withstand a bump. You can connect the T9 Portable to almost all your Android, iOS, macOS, and Windows devices with a USB-C or USB-A port. It is also compatible with iPhone 15, PS4, PS5, Xbox Series X|S, and Xbox One. Samsung provides a 5-year warranty on your SSD.', 1.00, 'assets/SSDs/Samsung_T9/Samsung_T9_main.avif'),
(33, 1, 'test product the best', 'egvwsd ', 1.00, 'assets/peter dance vibe.jpg');

-- --------------------------------------------------------

--
-- Table structure for table `product_images`
--

CREATE TABLE `product_images` (
  `id` int(11) NOT NULL,
  `product_id` int(11) DEFAULT NULL,
  `image_url` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `product_images`
--

INSERT INTO `product_images` (`id`, `product_id`, `image_url`) VALUES
(1, 1, 'assets/keyboards/logitech_G_pro/logitech_G_Pro_white_main.avif'),
(2, 1, 'assets/keyboards/logitech_G_pro/logitech_G_Pro_white_1.avif'),
(3, 1, 'assets/keyboards/logitech_G_pro/logitech_G_Pro_white_2.avif'),
(4, 1, 'assets/keyboards/logitech_G_pro/logitech_G_Pro_white_3.avif'),
(5, 1, 'assets/keyboards/logitech_G_pro/logitech_G_Pro_white_4.avif'),
(6, 1, 'assets/keyboards/logitech_G_pro/logitech_G_Pro_white_5.avif'),
(7, 1, 'assets/keyboards/logitech_G_pro/logitech_G_Pro_white_6.avif'),
(8, 1, 'assets/keyboards/logitech_G_pro/logitech_G_Pro_white_7.avif'),
(9, 3, 'assets/keyboards/razer_huntsman/razer_huntsman_V3_main.avif'),
(10, 3, 'assets/keyboards/razer_huntsman/razer_huntsman_V3_1.avif'),
(11, 3, 'assets/keyboards/razer_huntsman/razer_huntsman_V3_2.avif'),
(12, 3, 'assets/keyboards/razer_huntsman/razer_huntsman_V3_3.avif'),
(13, 3, 'assets/keyboards/razer_huntsman/razer_huntsman_V3_4.avif'),
(14, 3, 'assets/keyboards/razer_huntsman/razer_huntsman_V3_5.avif'),
(15, 3, 'assets/keyboards/razer_huntsman/razer_huntsman_V3_6.avif'),
(16, 4, 'assets/keyboards/steelseries_apex_wireless/steelseries_apex_pro_TKL_wireless_main.avif'),
(17, 4, 'assets/keyboards/steelseries_apex_wireless/steelseries_apex_pro_TKL_wireless_1.avif'),
(18, 4, 'assets/keyboards/steelseries_apex_wireless/steelseries_apex_pro_TKL_wireless_2.avif'),
(19, 4, 'assets/keyboards/steelseries_apex_wireless/steelseries_apex_pro_TKL_wireless_3.avif'),
(20, 4, 'assets/keyboards/steelseries_apex_wireless/steelseries_apex_pro_TKL_wireless_4.avif'),
(21, 4, 'assets/keyboards/steelseries_apex_wireless/steelseries_apex_pro_TKL_wireless_5.avif'),
(22, 4, 'assets/keyboards/steelseries_apex_wireless/steelseries_apex_pro_TKL_wireless_6.avif'),
(23, 4, 'assets/keyboards/steelseries_apex_wireless/steelseries_apex_pro_TKL_wireless_7.avif'),
(24, 4, 'assets/keyboards/steelseries_apex_wireless/steelseries_apex_pro_TKL_wireless_8.avif'),
(25, 5, 'assets/keyboards/corsair_K70/Corsair_K70_main.avif'),
(26, 5, 'assets/keyboards/corsair_K70/Corsair_K70_1.avif'),
(27, 5, 'assets/keyboards/corsair_K70/Corsair_K70_2.avif'),
(28, 5, 'assets/keyboards/corsair_K70/Corsair_K70_3.avif'),
(29, 5, 'assets/keyboards/corsair_K70/Corsair_K70_4.avif'),
(30, 5, 'assets/keyboards/corsair_K70/Corsair_K70_5.avif'),
(31, 6, 'assets/laptops/MSI_katana_A15/MSI_Katana_A15_main.avif'),
(32, 6, 'assets/laptops/MSI_katana_A15/MSI_Katana_A15_1.avif'),
(33, 6, 'assets/laptops/MSI_katana_A15/MSI_Katana_A15_2.avif'),
(34, 6, 'assets/laptops/MSI_katana_A15/MSI_Katana_A15_3.avif'),
(35, 6, 'assets/laptops/MSI_katana_A15/MSI_Katana_A15_4.avif'),
(36, 6, 'assets/laptops/MSI_katana_A15/MSI_Katana_A15_5.avif'),
(37, 6, 'assets/laptops/MSI_katana_A15/MSI_Katana_A15_6.avif'),
(38, 6, 'assets/laptops/MSI_katana_A15/MSI_Katana_A15_7.avif'),
(39, 6, 'assets/laptops/MSI_katana_A15/MSI_Katana_A15_8.avif'),
(40, 6, 'assets/laptops/MSI_katana_A15/MSI_Katana_A15_9.avif'),
(41, 6, 'assets/laptops/MSI_katana_A15/MSI_Katana_A15_10.avif'),
(52, 16, 'assets/laptops/Lenovo_Yoga_Pro_9/Lenovo_Yoga_Pro_9_main.avif'),
(53, 16, 'assets/laptops/Lenovo_Yoga_Pro_9/Lenovo_Yoga_Pro_9_1.avif'),
(54, 16, 'assets/laptops/Lenovo_Yoga_Pro_9/Lenovo_Yoga_Pro_9_2.avif'),
(55, 16, 'assets/laptops/Lenovo_Yoga_Pro_9/Lenovo_Yoga_Pro_9_3.avif'),
(56, 16, 'assets/laptops/Lenovo_Yoga_Pro_9/Lenovo_Yoga_Pro_9_4.avif'),
(57, 16, 'assets/laptops/Lenovo_Yoga_Pro_9/Lenovo_Yoga_Pro_9_5.avif'),
(58, 16, 'assets/laptops/Lenovo_Yoga_Pro_9/Lenovo_Yoga_Pro_9_6.avif'),
(59, 17, 'assets/laptops/ASUS_TUF_Gaming/ASUS_TUF_Gaming_F17_main.avif'),
(60, 17, 'assets/laptops/ASUS_TUF_Gaming/ASUS_TUF_Gaming_F17_1.avif'),
(61, 17, 'assets/laptops/ASUS_TUF_Gaming/ASUS_TUF_Gaming_F17_2.avif'),
(62, 17, 'assets/laptops/ASUS_TUF_Gaming/ASUS_TUF_Gaming_F17_3.avif'),
(63, 17, 'assets/laptops/ASUS_TUF_Gaming/ASUS_TUF_Gaming_F17_4.avif'),
(64, 17, 'assets/laptops/ASUS_TUF_Gaming/ASUS_TUF_Gaming_F17_5.avif'),
(65, 17, 'assets/laptops/ASUS_TUF_Gaming/ASUS_TUF_Gaming_F17_6.avif'),
(66, 17, 'assets/laptops/ASUS_TUF_Gaming/ASUS_TUF_Gaming_F17_7.avif'),
(67, 18, 'assets/laptops/MSI_Thin_15/MSI_Thin_15_main.avif'),
(68, 18, 'assets/laptops/MSI_Thin_15/MSI_Thin_15_1.avif'),
(69, 18, 'assets/laptops/MSI_Thin_15/MSI_Thin_15_2.avif'),
(70, 18, 'assets/laptops/MSI_Thin_15/MSI_Thin_15_3.avif'),
(71, 18, 'assets/laptops/MSI_Thin_15/MSI_Thin_15_4.avif'),
(72, 18, 'assets/laptops/MSI_Thin_15/MSI_Thin_15_5.avif'),
(73, 18, 'assets/laptops/MSI_Thin_15/MSI_Thin_15_6.avif'),
(74, 18, 'assets/laptops/MSI_Thin_15/MSI_Thin_15_7.avif'),
(75, 18, 'assets/laptops/MSI_Thin_15/MSI_Thin_15_8.avif'),
(76, 18, 'assets/laptops/MSI_Thin_15/MSI_Thin_15_9.avif'),
(77, 18, 'assets/laptops/MSI_Thin_15/MSI_Thin_15_10.avif'),
(78, 18, 'assets/laptops/MSI_Thin_15/MSI_Thin_15_11.avif'),
(79, 19, 'assets/laptops/HP_VICTUS_16/HP_VICTUS_16_main.avif'),
(80, 19, 'assets/laptops/HP_VICTUS_16/HP_VICTUS_16_1.avif'),
(81, 19, 'assets/laptops/HP_VICTUS_16/HP_VICTUS_16_2.avif'),
(82, 19, 'assets/laptops/HP_VICTUS_16/HP_VICTUS_16_3.avif'),
(83, 19, 'assets/laptops/HP_VICTUS_16/HP_VICTUS_16_4.avif'),
(84, 19, 'assets/laptops/HP_VICTUS_16/HP_VICTUS_16_5.avif'),
(85, 20, 'assets/mice/SteelSeries_Aerox_9/SteelSeries_Aerox_9_main.avif'),
(86, 20, 'assets/mice/SteelSeries_Aerox_9/SteelSeries_Aerox_9_1.avif'),
(87, 20, 'assets/mice/SteelSeries_Aerox_9/SteelSeries_Aerox_9_2.avif'),
(88, 20, 'assets/mice/SteelSeries_Aerox_9/SteelSeries_Aerox_9_3.avif'),
(89, 20, 'assets/mice/SteelSeries_Aerox_9/SteelSeries_Aerox_9_4.avif'),
(90, 20, 'assets/mice/SteelSeries_Aerox_9/SteelSeries_Aerox_9_5.avif'),
(91, 20, 'assets/mice/SteelSeries_Aerox_9/SteelSeries_Aerox_9_6.avif'),
(92, 20, 'assets/mice/SteelSeries_Aerox_9/SteelSeries_Aerox_9_7.avif'),
(93, 21, 'assets/mice/Logitech_G_Pro_X_Superlight/Logitech_G_Pro_X_Superlight_main.avif'),
(94, 21, 'assets/mice/Logitech_G_Pro_X_Superlight/Logitech_G_Pro_X_Superlight_1.avif'),
(95, 21, 'assets/mice/Logitech_G_Pro_X_Superlight/Logitech_G_Pro_X_Superlight_2.avif'),
(96, 21, 'assets/mice/Logitech_G_Pro_X_Superlight/Logitech_G_Pro_X_Superlight_3.avif'),
(97, 21, 'assets/mice/Logitech_G_Pro_X_Superlight/Logitech_G_Pro_X_Superlight_4.avif'),
(98, 21, 'assets/mice/Logitech_G_Pro_X_Superlight/Logitech_G_Pro_X_Superlight_5.avif'),
(99, 21, 'assets/mice/Logitech_G_Pro_X_Superlight/Logitech_G_Pro_X_Superlight_6.avif'),
(100, 21, 'assets/mice/Logitech_G_Pro_X_Superlight/Logitech_G_Pro_X_Superlight_7.avif'),
(101, 21, 'assets/mice/Logitech_G_Pro_X_Superlight/Logitech_G_Pro_X_Superlight_8.avif'),
(102, 21, 'assets/mice/Logitech_G_Pro_X_Superlight/Logitech_G_Pro_X_Superlight_9.avif'),
(103, 21, 'assets/mice/Logitech_G_Pro_X_Superlight/Logitech_G_Pro_X_Superlight_10.avif'),
(104, 22, 'assets/mice/SteelSeries_Aerox_5/SteelSeries_Aerox_5_main.avif'),
(105, 22, 'assets/mice/SteelSeries_Aerox_5/SteelSeries_Aerox_5_1.avif'),
(106, 22, 'assets/mice/SteelSeries_Aerox_5/SteelSeries_Aerox_5_2.avif'),
(107, 22, 'assets/mice/SteelSeries_Aerox_5/SteelSeries_Aerox_5_3.avif'),
(108, 22, 'assets/mice/SteelSeries_Aerox_5/SteelSeries_Aerox_5_4.avif'),
(109, 22, 'assets/mice/SteelSeries_Aerox_5/SteelSeries_Aerox_5_5.avif'),
(110, 22, 'assets/mice/SteelSeries_Aerox_5/SteelSeries_Aerox_5_6.avif'),
(111, 23, 'assets/mice/Corsair_Nightsabre/Corsair_Nightsabre_main.avif'),
(112, 23, 'assets/mice/Corsair_Nightsabre/Corsair_Nightsabre_1.avif'),
(113, 23, 'assets/mice/Corsair_Nightsabre/Corsair_Nightsabre_2.avif'),
(114, 23, 'assets/mice/Corsair_Nightsabre/Corsair_Nightsabre_3.avif'),
(115, 23, 'assets/mice/Corsair_Nightsabre/Corsair_Nightsabre_4.avif'),
(116, 23, 'assets/mice/Corsair_Nightsabre/Corsair_Nightsabre_5.avif'),
(117, 23, 'assets/mice/Corsair_Nightsabre/Corsair_Nightsabre_6.avif'),
(118, 23, 'assets/mice/Corsair_Nightsabre/Corsair_Nightsabre_7.avif'),
(119, 23, 'assets/mice/Corsair_Nightsabre/Corsair_Nightsabre_8.avif'),
(120, 23, 'assets/mice/Corsair_Nightsabre/Corsair_Nightsabre_9.avif'),
(121, 23, 'assets/mice/Corsair_Nightsabre/Corsair_Nightsabre_10.avif'),
(122, 23, 'assets/mice/Corsair_Nightsabre/Corsair_Nightsabre_11.avif'),
(123, 23, 'assets/mice/Corsair_Nightsabre/Corsair_Nightsabre_12.avif'),
(124, 24, 'assets/monitors/MSI_G2712F/MSI_G2712F_main.avif'),
(125, 24, 'assets/monitors/MSI_G2712F/MSI_G2712F_1.avif'),
(126, 24, 'assets/monitors/MSI_G2712F/MSI_G2712F_2.avif'),
(127, 24, 'assets/monitors/MSI_G2712F/MSI_G2712F_3.avif'),
(128, 24, 'assets/monitors/MSI_G2712F/MSI_G2712F_4.avif'),
(129, 24, 'assets/monitors/MSI_G2712F/MSI_G2712F_5.avif'),
(130, 24, 'assets/monitors/MSI_G2712F/MSI_G2712F_6.avif'),
(131, 24, 'assets/monitors/MSI_G2712F/MSI_G2712F_7.avif'),
(132, 24, 'assets/monitors/MSI_G2712F/MSI_G2712F_8.avif'),
(133, 24, 'assets/monitors/MSI_G2712F/MSI_G2712F_9.avif'),
(134, 25, 'assets/monitors/Samsung_LS32DG802SUXEN/Samsung_LS32DG802SUXEN_main.avif'),
(135, 25, 'assets/monitors/Samsung_LS32DG802SUXEN/Samsung_LS32DG802SUXEN_1.avif'),
(136, 25, 'assets/monitors/Samsung_LS32DG802SUXEN/Samsung_LS32DG802SUXEN_2.avif'),
(137, 25, 'assets/monitors/Samsung_LS32DG802SUXEN/Samsung_LS32DG802SUXEN_3.avif'),
(138, 25, 'assets/monitors/Samsung_LS32DG802SUXEN/Samsung_LS32DG802SUXEN_4.avif'),
(139, 25, 'assets/monitors/Samsung_LS32DG802SUXEN/Samsung_LS32DG802SUXEN_5.avif'),
(140, 25, 'assets/monitors/Samsung_LS32DG802SUXEN/Samsung_LS32DG802SUXEN_6.avif'),
(141, 25, 'assets/monitors/Samsung_LS32DG802SUXEN/Samsung_LS32DG802SUXEN_7.avif'),
(142, 26, 'assets/monitors/LG_UltraGear/LG_UltraGear_main.avif'),
(143, 26, 'assets/monitors/LG_UltraGear/LG_UltraGear_1.avif'),
(144, 26, 'assets/monitors/LG_UltraGear/LG_UltraGear_2.avif'),
(145, 26, 'assets/monitors/LG_UltraGear/LG_UltraGear_3.avif'),
(146, 26, 'assets/monitors/LG_UltraGear/LG_UltraGear_4.avif'),
(147, 26, 'assets/monitors/LG_UltraGear/LG_UltraGear_5.avif'),
(148, 26, 'assets/monitors/LG_UltraGear/LG_UltraGear_6.avif'),
(149, 27, 'assets/SSDs/Samsung_T9/Samsung_T9_main.avif'),
(150, 27, 'assets/SSDs/Samsung_T9/Samsung_T9_1.avif'),
(151, 27, 'assets/SSDs/Samsung_T9/Samsung_T9_2.avif'),
(152, 27, 'assets/SSDs/Samsung_T9/Samsung_T9_3.avif'),
(153, 27, 'assets/SSDs/Samsung_T9/Samsung_T9_4.avif'),
(154, 27, 'assets/SSDs/Samsung_T9/Samsung_T9_5.avif'),
(155, 27, 'assets/SSDs/Samsung_T9/Samsung_T9_6.avif'),
(224, 33, 'assets/Logo-Magic-The-Gathering.jpg'),
(225, 33, 'assets/webshop background 1.jpg'),
(226, 33, 'assets/4k-spring-girl-sword-japan-flag-j0cx5n7y2s381pyq.jpg'),
(238, 33, 'assets/484-4849193_mtg-primary-ll-2c-black-lg-v12-magic.png'),
(240, 33, 'assets/peter dance vibe.jpg');

-- --------------------------------------------------------

--
-- Table structure for table `product_variants`
--

CREATE TABLE `product_variants` (
  `id` int(11) NOT NULL,
  `product_id` int(11) DEFAULT NULL,
  `variant_name` varchar(255) NOT NULL,
  `price` decimal(10,2) NOT NULL DEFAULT 0.00
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `product_variants`
--

INSERT INTO `product_variants` (`id`, `product_id`, `variant_name`, `price`) VALUES
(1, 1, 'Logitech G PRO X 60 Mechanical Gaming Keyboard Black AZERTY', 80.00),
(9, 33, 'test product PETER GRIFFIN the best', 800.00);

-- --------------------------------------------------------

--
-- Table structure for table `ratings`
--

CREATE TABLE `ratings` (
  `id` int(11) NOT NULL,
  `product_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `rating` tinyint(4) NOT NULL CHECK (`rating` between 1 and 5),
  `review` text DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `ratings`
--

INSERT INTO `ratings` (`id`, `product_id`, `user_id`, `rating`, `review`, `created_at`) VALUES
(3, 1, 10, 3, 'qgsgqq', '2024-11-28 14:50:05'),
(4, 1, 10, 1, 'qvgqsqb', '2024-11-28 14:50:09');

-- --------------------------------------------------------

--
-- Table structure for table `variant_images`
--

CREATE TABLE `variant_images` (
  `id` int(11) NOT NULL,
  `variant_id` int(11) NOT NULL,
  `image_url` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `variant_images`
--

INSERT INTO `variant_images` (`id`, `variant_id`, `image_url`) VALUES
(1, 1, 'assets/keyboards/logitech_G_pro/logitech_G_Pro_black_main.avif'),
(2, 1, 'assets/keyboards/logitech_G_pro/logitech_G_Pro_black_1.avif'),
(3, 1, 'assets/keyboards/logitech_G_pro/logitech_G_Pro_black_2.avif'),
(4, 1, 'assets/keyboards/logitech_G_pro/logitech_G_Pro_black_3.avif'),
(5, 1, 'assets/keyboards/logitech_G_pro/logitech_G_Pro_black_4.avif'),
(6, 1, 'assets/keyboards/logitech_G_pro/logitech_G_Pro_black_5.avif'),
(7, 1, 'assets/keyboards/logitech_G_pro/logitech_G_Pro_black_6.avif'),
(8, 1, 'assets/keyboards/logitech_G_pro/logitech_G_Pro_black_7.avif'),
(74, 9, 'assets/webshop background 1.jpg'),
(75, 9, 'assets/Logo-Magic-The-Gathering.jpg'),
(76, 9, 'assets/4k-spring-girl-sword-japan-flag-j0cx5n7y2s381pyq.jpg'),
(77, 9, 'assets/car-khyzyl-saleem-mazda-rx-7-simple-background-wallpaper-preview.jpg');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `accounts`
--
ALTER TABLE `accounts`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `cart`
--
ALTER TABLE `cart`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`),
  ADD KEY `product_id` (`product_id`);

--
-- Indexes for table `categories`
--
ALTER TABLE `categories`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `orders`
--
ALTER TABLE `orders`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `order_items`
--
ALTER TABLE `order_items`
  ADD PRIMARY KEY (`id`),
  ADD KEY `order_id` (`order_id`),
  ADD KEY `product_id` (`product_id`);

--
-- Indexes for table `products`
--
ALTER TABLE `products`
  ADD PRIMARY KEY (`id`),
  ADD KEY `category_id` (`category_id`);

--
-- Indexes for table `product_images`
--
ALTER TABLE `product_images`
  ADD PRIMARY KEY (`id`),
  ADD KEY `product_id` (`product_id`);

--
-- Indexes for table `product_variants`
--
ALTER TABLE `product_variants`
  ADD PRIMARY KEY (`id`),
  ADD KEY `product_id` (`product_id`);

--
-- Indexes for table `ratings`
--
ALTER TABLE `ratings`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `variant_images`
--
ALTER TABLE `variant_images`
  ADD PRIMARY KEY (`id`),
  ADD KEY `variant_id` (`variant_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `accounts`
--
ALTER TABLE `accounts`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=17;

--
-- AUTO_INCREMENT for table `cart`
--
ALTER TABLE `cart`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `categories`
--
ALTER TABLE `categories`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `orders`
--
ALTER TABLE `orders`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=58;

--
-- AUTO_INCREMENT for table `order_items`
--
ALTER TABLE `order_items`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=132;

--
-- AUTO_INCREMENT for table `products`
--
ALTER TABLE `products`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=35;

--
-- AUTO_INCREMENT for table `product_images`
--
ALTER TABLE `product_images`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=241;

--
-- AUTO_INCREMENT for table `product_variants`
--
ALTER TABLE `product_variants`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT for table `ratings`
--
ALTER TABLE `ratings`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT for table `variant_images`
--
ALTER TABLE `variant_images`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=83;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `cart`
--
ALTER TABLE `cart`
  ADD CONSTRAINT `cart_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `accounts` (`id`),
  ADD CONSTRAINT `cart_ibfk_2` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`);

--
-- Constraints for table `orders`
--
ALTER TABLE `orders`
  ADD CONSTRAINT `orders_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `accounts` (`id`);

--
-- Constraints for table `order_items`
--
ALTER TABLE `order_items`
  ADD CONSTRAINT `order_items_ibfk_1` FOREIGN KEY (`order_id`) REFERENCES `orders` (`id`),
  ADD CONSTRAINT `order_items_ibfk_2` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`);

--
-- Constraints for table `products`
--
ALTER TABLE `products`
  ADD CONSTRAINT `products_ibfk_1` FOREIGN KEY (`category_id`) REFERENCES `categories` (`id`);

--
-- Constraints for table `product_images`
--
ALTER TABLE `product_images`
  ADD CONSTRAINT `product_images_ibfk_1` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`);

--
-- Constraints for table `product_variants`
--
ALTER TABLE `product_variants`
  ADD CONSTRAINT `product_variants_ibfk_1` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`);

--
-- Constraints for table `ratings`
--
ALTER TABLE `ratings`
  ADD CONSTRAINT `ratings_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `accounts` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `variant_images`
--
ALTER TABLE `variant_images`
  ADD CONSTRAINT `variant_images_ibfk_1` FOREIGN KEY (`variant_id`) REFERENCES `product_variants` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
