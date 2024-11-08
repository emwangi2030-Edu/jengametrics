-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Nov 08, 2024 at 02:26 PM
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
-- Database: `metrics`
--

-- --------------------------------------------------------

--
-- Table structure for table `boms`
--

CREATE TABLE `boms` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `bq_document_id` bigint(20) UNSIGNED NOT NULL,
  `bom_name` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `bom_items`
--

CREATE TABLE `bom_items` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `section_id` bigint(20) UNSIGNED NOT NULL,
  `item_id` bigint(20) UNSIGNED NOT NULL,
  `item_material_id` bigint(20) UNSIGNED NOT NULL,
  `bom_id` bigint(20) UNSIGNED DEFAULT NULL,
  `bq_section_id` bigint(20) UNSIGNED DEFAULT NULL,
  `bq_item_id` bigint(20) UNSIGNED DEFAULT NULL,
  `item_description` varchar(255) DEFAULT NULL,
  `quantity` double DEFAULT NULL,
  `unit` varchar(255) DEFAULT NULL,
  `rate` double DEFAULT NULL,
  `amount` double DEFAULT NULL,
  `project_id` bigint(20) UNSIGNED DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `bom_items`
--

INSERT INTO `bom_items` (`id`, `section_id`, `item_id`, `item_material_id`, `bom_id`, `bq_section_id`, `bq_item_id`, `item_description`, `quantity`, `unit`, `rate`, `amount`, `project_id`, `created_at`, `updated_at`) VALUES
(1, 2, 3, 5, NULL, NULL, NULL, NULL, 4, NULL, NULL, 0, 1, '2024-10-30 10:46:18', '2024-10-30 10:46:18'),
(2, 2, 3, 6, NULL, NULL, NULL, NULL, 4, NULL, NULL, 0, 1, '2024-10-30 10:46:18', '2024-10-30 10:46:18'),
(3, 2, 2, 2, NULL, NULL, NULL, NULL, 12, NULL, NULL, 0, 1, '2024-10-31 03:55:21', '2024-10-31 03:55:21'),
(4, 2, 2, 3, NULL, NULL, NULL, NULL, 12, NULL, NULL, 0, 1, '2024-10-31 03:55:21', '2024-10-31 03:55:21'),
(5, 2, 2, 4, NULL, NULL, NULL, NULL, 12, NULL, NULL, 0, 1, '2024-10-31 03:55:21', '2024-10-31 03:55:21'),
(6, 2, 9, 11, NULL, NULL, NULL, NULL, 7, NULL, NULL, 0, 1, '2024-10-31 04:37:54', '2024-10-31 04:37:54'),
(7, 2, 9, 12, NULL, NULL, NULL, NULL, 7, NULL, NULL, 0, 1, '2024-10-31 04:37:54', '2024-10-31 04:37:54'),
(8, 2, 9, 13, NULL, NULL, NULL, NULL, 7, NULL, NULL, 0, 1, '2024-10-31 04:37:54', '2024-10-31 04:37:54'),
(9, 2, 11, 16, NULL, NULL, NULL, NULL, 43, NULL, NULL, 0, 1, '2024-10-31 05:27:27', '2024-10-31 05:27:27'),
(10, 2, 11, 17, NULL, NULL, NULL, NULL, 43, NULL, NULL, 0, 1, '2024-10-31 05:27:27', '2024-10-31 05:27:27'),
(11, 2, 17, 27, NULL, NULL, NULL, NULL, 20, NULL, NULL, 0, 1, '2024-10-31 13:51:35', '2024-10-31 13:51:35'),
(12, 2, 17, 28, NULL, NULL, NULL, NULL, 20, NULL, NULL, 0, 1, '2024-10-31 13:51:35', '2024-10-31 13:51:35'),
(13, 4, 27, 44, NULL, NULL, NULL, NULL, 80, NULL, NULL, 0, 1, '2024-10-31 14:28:49', '2024-10-31 14:28:49'),
(14, 4, 27, 45, NULL, NULL, NULL, NULL, 13, NULL, NULL, 0, 1, '2024-10-31 14:28:49', '2024-10-31 14:28:49'),
(15, 4, 27, 46, NULL, NULL, NULL, NULL, 6.5, NULL, NULL, 0, 1, '2024-10-31 14:28:49', '2024-10-31 14:28:49'),
(16, 4, 27, 44, NULL, NULL, NULL, NULL, 160, NULL, NULL, 0, 1, '2024-10-31 14:33:55', '2024-10-31 14:33:55'),
(17, 4, 27, 45, NULL, NULL, NULL, NULL, 26, NULL, NULL, 0, 1, '2024-10-31 14:33:55', '2024-10-31 14:33:55'),
(18, 4, 27, 46, NULL, NULL, NULL, NULL, 13, NULL, NULL, 0, 1, '2024-10-31 14:33:55', '2024-10-31 14:33:55'),
(19, 2, 21, 35, NULL, NULL, NULL, NULL, 45, NULL, NULL, 0, 1, '2024-11-07 13:06:17', '2024-11-07 13:06:17'),
(20, 2, 21, 36, NULL, NULL, NULL, NULL, 45, NULL, NULL, 0, 1, '2024-11-07 13:06:17', '2024-11-07 13:06:17');

-- --------------------------------------------------------

--
-- Table structure for table `bq_documents`
--

CREATE TABLE `bq_documents` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `title` varchar(255) NOT NULL,
  `description` text DEFAULT NULL,
  `user_id` bigint(20) UNSIGNED NOT NULL,
  `project_id` bigint(20) UNSIGNED NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `bq_items`
--

CREATE TABLE `bq_items` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `bq_document_id` bigint(20) UNSIGNED NOT NULL,
  `bq_section_id` bigint(20) UNSIGNED NOT NULL,
  `item_description` varchar(255) NOT NULL,
  `quantity` int(11) NOT NULL,
  `unit` varchar(255) NOT NULL,
  `rate` decimal(15,2) NOT NULL,
  `amount` decimal(15,2) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `bq_sections`
--

CREATE TABLE `bq_sections` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `section_id` bigint(20) UNSIGNED DEFAULT NULL,
  `element_id` bigint(20) UNSIGNED DEFAULT NULL,
  `sub_element_id` bigint(20) UNSIGNED DEFAULT NULL,
  `item_id` bigint(20) UNSIGNED DEFAULT NULL,
  `rate` decimal(10,2) DEFAULT NULL,
  `quantity` int(11) NOT NULL DEFAULT 0,
  `amount` decimal(15,2) NOT NULL DEFAULT 0.00,
  `project_id` varchar(255) DEFAULT NULL,
  `bq_document_id` bigint(20) UNSIGNED DEFAULT NULL,
  `section_name` varchar(255) DEFAULT NULL,
  `details` text DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `bq_sections`
--

INSERT INTO `bq_sections` (`id`, `section_id`, `element_id`, `sub_element_id`, `item_id`, `rate`, `quantity`, `amount`, `project_id`, `bq_document_id`, `section_name`, `details`, `created_at`, `updated_at`) VALUES
(10, 2, 2, 2, 3, 20000.00, 4, 80000.00, '1', NULL, NULL, NULL, '2024-10-30 10:45:36', '2024-10-30 10:45:36'),
(12, 2, 2, 2, 2, 1000.00, 12, 12000.00, '1', NULL, NULL, NULL, '2024-10-31 03:55:21', '2024-10-31 03:55:21'),
(13, 2, 3, 4, 9, 24500.00, 7, 171500.00, '1', NULL, NULL, NULL, '2024-10-31 04:37:54', '2024-10-31 04:37:54'),
(14, 2, 3, 5, 11, 12345.00, 43, 530835.00, '1', NULL, NULL, NULL, '2024-10-31 05:27:27', '2024-10-31 05:27:27'),
(15, 3, 8, 13, 25, 4000.00, 30, 120000.00, '1', NULL, NULL, NULL, '2024-10-31 08:55:33', '2024-10-31 08:55:33'),
(16, 2, 5, 8, 17, 10000.00, 20, 200000.00, '1', NULL, NULL, NULL, '2024-10-31 13:51:35', '2024-10-31 13:51:35'),
(17, 4, 9, 14, 27, 15000.00, 10, 150000.00, '1', NULL, NULL, NULL, '2024-10-31 14:28:49', '2024-10-31 14:28:49'),
(18, 4, 9, 14, 27, 15000.00, 20, 300000.00, '1', NULL, NULL, NULL, '2024-10-31 14:33:55', '2024-10-31 14:33:55'),
(19, 3, 8, 13, 25, 24000.00, 12, 288000.00, '1', NULL, NULL, NULL, '2024-11-07 06:23:41', '2024-11-07 06:23:41'),
(20, 2, 6, 10, 21, 3500.00, 45, 157500.00, '1', NULL, NULL, NULL, '2024-11-07 13:06:17', '2024-11-07 13:06:17');

-- --------------------------------------------------------

--
-- Table structure for table `branches`
--

CREATE TABLE `branches` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `cache`
--

CREATE TABLE `cache` (
  `key` varchar(255) NOT NULL,
  `value` mediumtext NOT NULL,
  `expiration` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `cache_locks`
--

CREATE TABLE `cache_locks` (
  `key` varchar(255) NOT NULL,
  `owner` varchar(255) NOT NULL,
  `expiration` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `documents`
--

CREATE TABLE `documents` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `path` varchar(255) NOT NULL,
  `user_id` bigint(20) UNSIGNED NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `elements`
--

CREATE TABLE `elements` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `section_id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `description` text DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `elements`
--

INSERT INTO `elements` (`id`, `section_id`, `name`, `description`, `created_at`, `updated_at`) VALUES
(2, 2, 'Vegetation Removal', 'Involves clearing trees, shrubs, and grass from the construction area', '2024-10-29 06:41:50', '2024-10-29 06:41:50'),
(3, 2, 'Demolition of Existing Structures', 'Involves tearing down old buildings, walls, or other structures on-site', '2024-10-29 10:21:26', '2024-10-29 10:21:26'),
(4, 2, 'Clearing Debris and Waste', 'Involves removing waste and debris from the site after vegetation and structure removal', '2024-10-30 03:37:25', '2024-10-30 03:37:25'),
(5, 2, 'Site Leveling and Grading', 'Involves leveling the site to ensure a flat, even surface for construction', '2024-10-30 04:23:12', '2024-10-30 04:23:12'),
(6, 2, 'Erosion and Drainage Control', 'Involves preventing soil erosion and managing water runoff during and after site preparation', '2024-10-30 05:11:14', '2024-10-30 05:11:14'),
(7, 2, 'Hazardous Material Removal', 'Involves identifying and safely removing hazardous materials such as asbestos or contaminated soil', '2024-10-30 05:45:53', '2024-10-30 05:45:53'),
(8, 3, 'Foundations', 'Involves excavation, compacting the base, installing formwork, placing reinforcement bars, pouring concrete, and curing', '2024-10-31 05:43:53', '2024-10-31 05:43:53'),
(9, 4, 'VRC Class 25', NULL, '2024-10-31 14:01:36', '2024-10-31 14:01:36');

-- --------------------------------------------------------

--
-- Table structure for table `failed_jobs`
--

CREATE TABLE `failed_jobs` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `uuid` varchar(255) NOT NULL,
  `connection` text NOT NULL,
  `queue` text NOT NULL,
  `payload` longtext NOT NULL,
  `exception` longtext NOT NULL,
  `failed_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `items`
--

CREATE TABLE `items` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `sub_element_id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `description` text DEFAULT NULL,
  `unit_of_measurement` varchar(255) DEFAULT NULL,
  `abbrev` varchar(10) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `items`
--

INSERT INTO `items` (`id`, `sub_element_id`, `name`, `description`, `unit_of_measurement`, `abbrev`, `created_at`, `updated_at`) VALUES
(3, 2, 'Stump Removal', NULL, 'per stump', NULL, '2024-10-29 09:46:51', '2024-10-29 09:46:51'),
(4, 3, 'Manual Clearing', NULL, 'square meter', NULL, '2024-10-29 10:05:13', '2024-10-29 10:05:13'),
(5, 3, 'Mechanical Clearing', NULL, 'hectare', NULL, '2024-10-29 10:17:18', '2024-10-29 10:17:18'),
(9, 4, 'Concrete Structure Demolition', NULL, 'cubic meter', NULL, '2024-10-30 02:47:18', '2024-10-30 02:47:18'),
(10, 4, 'Timber Structure Demolition', NULL, 'cubic meter', NULL, '2024-10-30 02:56:30', '2024-10-30 02:56:30'),
(11, 5, 'Asphalt Removal', NULL, 'square meter', NULL, '2024-10-30 03:02:53', '2024-10-30 03:09:30'),
(12, 5, 'Brick Walkway Removal', NULL, 'square meter', NULL, '2024-10-30 03:10:39', '2024-10-30 03:10:39'),
(13, 6, 'Manual Collection', NULL, 'kilogram', NULL, '2024-10-30 03:39:00', '2024-10-30 03:39:00'),
(14, 6, 'Mechanical Collection', NULL, 'kilogram', NULL, '2024-10-30 03:40:47', '2024-10-30 03:40:47'),
(15, 7, 'Transport to Landfill', NULL, 'kilometer', NULL, '2024-10-30 03:47:26', '2024-10-30 03:47:26'),
(16, 7, 'Recyclable Waste Sorting', NULL, 'kilogram', NULL, '2024-10-30 03:50:06', '2024-10-30 03:50:06'),
(17, 8, 'Topsoil Removal', NULL, 'cubic meter', NULL, '2024-10-30 04:26:55', '2024-10-30 04:26:55'),
(18, 8, 'Earth Filling', NULL, 'cubic meter', NULL, '2024-10-30 04:31:13', '2024-10-30 04:31:13'),
(19, 9, 'Rough Grading', NULL, 'square meter', NULL, '2024-10-30 04:39:18', '2024-10-30 04:39:18'),
(20, 9, 'Fine Grading and Compaction', NULL, 'square meter', NULL, '2024-10-30 04:47:36', '2024-10-30 04:47:36'),
(21, 10, 'Temporary Silt Fence', NULL, 'meter', NULL, '2024-10-30 05:13:53', '2024-10-30 05:13:53'),
(22, 10, 'Rock Berms', NULL, 'meter', NULL, '2024-10-30 05:22:48', '2024-10-30 05:22:48'),
(23, 11, 'Ditches', NULL, 'meter', NULL, '2024-10-30 05:31:42', '2024-10-30 05:31:42'),
(24, 11, 'Culverts', NULL, 'meter', NULL, '2024-10-30 05:36:28', '2024-10-30 05:36:28'),
(25, 13, 'Clearing of Base Area', NULL, 'square meter', NULL, '2024-10-31 06:17:21', '2024-10-31 06:17:21'),
(26, 13, 'Excavation for foundation trenches', NULL, 'cubic meter', NULL, '2024-10-31 06:18:26', '2024-10-31 06:18:26'),
(27, 14, 'Column', NULL, 'kilogram', NULL, '2024-10-31 14:03:34', '2024-11-07 05:47:45');

-- --------------------------------------------------------

--
-- Table structure for table `item_materials`
--

CREATE TABLE `item_materials` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `item_id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `unit_of_measurement` varchar(255) NOT NULL,
  `conversion_factor` decimal(8,2) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `item_materials`
--

INSERT INTO `item_materials` (`id`, `item_id`, `name`, `unit_of_measurement`, `conversion_factor`, `created_at`, `updated_at`) VALUES
(5, 3, 'Stump Grinder', 'Hour', 1.00, '2024-10-29 09:57:31', '2024-10-29 09:57:31'),
(6, 3, 'Excavator', 'Hour', 1.00, '2024-10-29 09:58:32', '2024-10-29 09:58:32'),
(7, 4, 'Machetes', 'Each', 1.00, '2024-10-29 10:06:10', '2024-10-29 10:06:10'),
(8, 4, 'Rakes', 'Each', 1.00, '2024-10-29 10:06:31', '2024-10-29 10:06:31'),
(9, 5, 'Brush Cutters', 'Hour', 1.00, '2024-10-29 10:18:10', '2024-10-29 10:18:10'),
(10, 5, 'Tractor', 'Hour', 1.00, '2024-10-29 10:18:40', '2024-10-29 10:18:40'),
(11, 9, 'Jackhammers', 'Each', 1.00, '2024-10-30 02:47:51', '2024-10-30 02:47:51'),
(12, 9, 'Explosives', 'Kilogram', 1.00, '2024-10-30 02:48:08', '2024-10-30 02:48:08'),
(13, 9, 'Waste containers', 'Each', 1.00, '2024-10-30 02:48:40', '2024-10-30 02:48:40'),
(14, 10, 'Crowbars', 'Each', 1.00, '2024-10-30 02:57:30', '2024-10-30 02:57:30'),
(15, 10, 'Sledgehammers', 'Each', 1.00, '2024-10-30 02:58:02', '2024-10-30 02:58:02'),
(16, 11, 'Asphalt cutter', 'Hour', 1.00, '2024-10-30 03:04:58', '2024-10-30 03:04:58'),
(17, 11, 'Loader', 'Hour', 1.00, '2024-10-30 03:06:58', '2024-10-30 03:06:58'),
(18, 12, 'Brick Remover', 'Each', 1.00, '2024-10-30 03:11:09', '2024-10-30 03:11:09'),
(19, 12, 'Shovels', 'Each', 1.00, '2024-10-30 03:11:26', '2024-10-30 03:11:26'),
(20, 13, 'Wheelbarrows', 'Each', 1.00, '2024-10-30 03:39:30', '2024-10-30 03:39:30'),
(21, 13, 'Garbage Bags', 'Each', 1.00, '2024-10-30 03:39:51', '2024-10-30 03:39:51'),
(22, 14, 'Loaders', 'Hour', 1.00, '2024-10-30 03:41:50', '2024-10-30 03:41:50'),
(23, 14, 'Skip bins', 'Each', 1.00, '2024-10-30 03:43:40', '2024-10-30 03:43:40'),
(24, 15, 'Trucks', 'Hour', 1.00, '2024-10-30 03:48:12', '2024-10-30 03:48:12'),
(25, 16, 'Sorting Bins', 'Each', 1.00, '2024-10-30 03:51:51', '2024-10-30 03:51:51'),
(26, 16, 'Gloves', 'Set', 1.00, '2024-10-30 03:52:04', '2024-10-30 03:52:04'),
(27, 17, 'Excavators', 'Hour', 1.00, '2024-10-30 04:27:31', '2024-10-30 04:27:31'),
(28, 17, 'Trucks', 'Hour', 1.00, '2024-10-30 04:27:57', '2024-10-30 04:27:57'),
(29, 18, 'Gravel', 'Metric Ton', 1.65, '2024-10-30 04:35:38', '2024-10-30 04:35:38'),
(30, 18, 'Compactor', 'Hour', 1.00, '2024-10-30 04:36:12', '2024-10-30 04:36:12'),
(31, 19, 'Bulldozers', 'Hour', 1.00, '2024-10-30 04:41:30', '2024-10-30 04:41:30'),
(32, 19, 'Graders', 'Hour', 1.00, '2024-10-30 04:43:21', '2024-10-30 04:43:21'),
(33, 20, 'Rollers', 'Hour', 1.00, '2024-10-30 04:48:00', '2024-10-30 04:48:00'),
(34, 20, 'Watertanks', 'Liter', 1.00, '2024-10-30 04:48:50', '2024-10-30 04:48:50'),
(35, 21, 'Geotextile fabric', 'Roll', 1.00, '2024-10-30 05:16:56', '2024-10-30 05:16:56'),
(36, 21, 'Wooden Stakes', 'Each', 1.00, '2024-10-30 05:17:35', '2024-10-30 05:17:35'),
(37, 22, 'Stones', 'Metric Ton', 1.00, '2024-10-30 05:27:07', '2024-10-30 05:27:07'),
(38, 22, 'Wire mesh', 'Meter', 1.00, '2024-10-30 05:27:35', '2024-10-30 05:27:35'),
(39, 23, 'Drainage pipes', 'Each', 1.00, '2024-10-30 05:32:47', '2024-10-30 05:32:47'),
(40, 23, 'Shovels', 'Each', 1.00, '2024-10-30 05:33:46', '2024-10-30 05:33:46'),
(41, 24, 'Concrete pipes', 'Each', 1.00, '2024-10-30 05:41:30', '2024-10-30 05:41:30'),
(42, 24, 'Support Braces', 'Each', 1.00, '2024-10-30 05:43:06', '2024-10-30 05:43:06'),
(44, 27, 'Cement', 'Kilogram', 8.00, '2024-10-31 14:05:31', '2024-10-31 14:05:31'),
(45, 27, 'Ballast', 'Metric Ton', 1.30, '2024-10-31 14:06:19', '2024-10-31 14:06:19'),
(46, 27, 'Sand', 'Metric Ton', 0.65, '2024-10-31 14:06:56', '2024-10-31 14:06:56'),
(47, 27, 'Test Material', 'Millimeter', 1.30, '2024-11-07 03:54:00', '2024-11-07 03:54:00');

-- --------------------------------------------------------

--
-- Table structure for table `item_unit_of_measurements`
--

CREATE TABLE `item_unit_of_measurements` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `abbrev` varchar(255) DEFAULT NULL,
  `category` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `item_unit_of_measurements`
--

INSERT INTO `item_unit_of_measurements` (`id`, `name`, `abbrev`, `category`, `created_at`, `updated_at`) VALUES
(22, 'millimeter', 'mm', 'length', '2024-11-07 02:50:05', '2024-11-07 02:50:05'),
(23, 'centimeter', 'cm', 'length', '2024-11-07 02:50:05', '2024-11-07 02:50:05'),
(24, 'meter', 'm', 'length', '2024-11-07 02:50:05', '2024-11-07 02:50:05'),
(25, 'kilometer', 'km', 'length', '2024-11-07 02:50:05', '2024-11-07 02:50:05'),
(26, 'square millimeter', 'mm²', 'area', '2024-11-07 02:50:05', '2024-11-07 02:50:05'),
(27, 'square centimeter', 'cm²', 'area', '2024-11-07 02:50:05', '2024-11-07 02:50:05'),
(28, 'square meter', 'm²', 'area', '2024-11-07 02:50:05', '2024-11-07 02:50:05'),
(29, 'hectare', 'ha', 'area', '2024-11-07 02:50:05', '2024-11-07 02:50:05'),
(30, 'cubic millimeter', 'mm³', 'volume', '2024-11-07 02:50:05', '2024-11-07 02:50:05'),
(31, 'cubic centimeter', 'cm³', 'volume', '2024-11-07 02:50:05', '2024-11-07 02:50:05'),
(32, 'cubic meter', 'm³', 'volume', '2024-11-07 02:50:05', '2024-11-07 02:50:05'),
(33, 'liter', 'L', 'volume', '2024-11-07 02:50:05', '2024-11-07 02:50:05'),
(34, 'milligram', 'mg', 'weight', '2024-11-07 02:50:05', '2024-11-07 02:50:05'),
(35, 'gram', 'g', 'weight', '2024-11-07 02:50:05', '2024-11-07 02:50:05'),
(36, 'kilogram', 'kg', 'weight', '2024-11-07 02:50:05', '2024-11-07 02:50:05'),
(37, 'ton', 't', 'weight', '2024-11-07 02:50:05', '2024-11-07 02:50:05'),
(38, 'piece', 'pc', 'quantity', '2024-11-07 02:50:05', '2024-11-07 02:50:05'),
(39, 'per tree', 'tree', 'quantity', '2024-11-07 02:50:05', '2024-11-07 02:50:05'),
(40, 'per stump', 'stump', 'quantity', '2024-11-07 02:50:05', '2024-11-07 02:50:05'),
(41, 'each', 'ea', 'quantity', '2024-11-07 02:50:05', '2024-11-07 02:50:05');

-- --------------------------------------------------------

--
-- Table structure for table `jobs`
--

CREATE TABLE `jobs` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `queue` varchar(255) NOT NULL,
  `payload` longtext NOT NULL,
  `attempts` tinyint(3) UNSIGNED NOT NULL,
  `reserved_at` int(10) UNSIGNED DEFAULT NULL,
  `available_at` int(10) UNSIGNED NOT NULL,
  `created_at` int(10) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `job_batches`
--

CREATE TABLE `job_batches` (
  `id` varchar(255) NOT NULL,
  `name` varchar(255) NOT NULL,
  `total_jobs` int(11) NOT NULL,
  `pending_jobs` int(11) NOT NULL,
  `failed_jobs` int(11) NOT NULL,
  `failed_job_ids` longtext NOT NULL,
  `options` mediumtext DEFAULT NULL,
  `cancelled_at` int(11) DEFAULT NULL,
  `created_at` int(11) NOT NULL,
  `finished_at` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `lessons`
--

CREATE TABLE `lessons` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `materials`
--

CREATE TABLE `materials` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `description` text DEFAULT NULL,
  `unit_price` decimal(8,2) NOT NULL,
  `unit_of_measure` varchar(255) NOT NULL,
  `quantity_in_stock` int(11) NOT NULL DEFAULT 0,
  `supplier_id` bigint(20) UNSIGNED DEFAULT NULL,
  `supplier_contact` varchar(255) DEFAULT NULL,
  `document` varchar(255) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `project_id` bigint(20) UNSIGNED DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `migrations`
--

CREATE TABLE `migrations` (
  `id` int(10) UNSIGNED NOT NULL,
  `migration` varchar(255) NOT NULL,
  `batch` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `migrations`
--

INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES
(1, '0001_01_01_000000_create_users_table', 1),
(2, '0001_01_01_000001_create_cache_table', 1),
(3, '0001_01_01_000002_create_jobs_table', 1),
(4, '2024_08_07_110041_create_bq_documents_table', 1),
(5, '2024_08_07_110201_create_bq_sections_table', 1),
(6, '2024_08_07_123137_create_bq_items_table', 1),
(7, '2024_08_07_151730_create_boms_table', 1),
(8, '2024_08_07_151731_create_bom_items_table', 1),
(9, '2024_08_12_104630_create_documents_table', 1),
(10, '2024_08_15_130142_create_workers_table', 1),
(11, '2024_08_17_100121_create_branches_table', 1),
(12, '2024_08_19_125107_create_projects_table', 1),
(13, '2024_08_20_124726_create_suppliers_table', 1),
(14, '2024_08_20_124728_create_materials_table', 1),
(15, '2024_08_22_125316_update_suppliers_table', 1),
(16, '2024_08_22_132637_update_materials_table', 1),
(17, '2024_08_23_075132_add_material_supplied_to_suppliers_table', 1),
(18, '2024_08_23_084240_alter_columns_in_materials_table', 1),
(19, '2024_08_27_090606_add_document_to_materials_table', 1),
(20, '2024_09_18_105813_add_project_id_to_materials_table', 1),
(21, '2024_09_18_132844_add_project_id_to_workers_table', 1),
(22, '2024_09_18_150632_create_lessons_table', 1),
(23, '2024_09_20_114023_create_sections_table', 1),
(24, '2024_09_24_074300_create_elements_table', 1),
(25, '2024_09_24_091149_create_sub_elements_table', 1),
(26, '2024_09_24_123726_create_items_table', 1),
(27, '2024_09_26_162540_add_fields_to_bq_sections_table', 1),
(28, '2024_09_26_162748_modify_section_name_in_bq_sections_table', 1),
(29, '2024_09_26_162807_modify_section_name_in_bq_sections_table', 1),
(30, '2024_09_29_164852_create_item_materials_table', 1),
(31, '2024_09_30_123456_create_units_of_measurement_table', 1),
(32, '2024_10_01_090317_create_item_unit_of_measurements_table', 1),
(33, '2024_10_01_101852_add_unit_of_measurement_to_items_table', 1),
(34, '2024_10_14_084122_add_project_id_to_bq_documents_table', 2),
(35, '2024_10_04_104636_add_project_id_to_bq_documents_table', 3),
(36, '2024_10_30_131445_add_item_id_to_bq_sections_table', 4),
(37, '2024_10_30_132005_add_rate_to_bq_sections_table', 5),
(38, '2024_10_30_132241_add_quantity_to_bq_sections_table', 6),
(39, '2024_10_30_132632_add_amount_to_bq_sections_table', 7),
(40, '2024_10_30_132953_add_section_id_to_bom_items_table', 8),
(41, '2024_10_30_133404_add_item_id_to_bom_items_table', 9),
(42, '2024_10_30_133725_add_item_material_id_to_bom_items_table', 10),
(43, '2024_10_30_134114_add_project_id_to_bom_items_table', 11),
(45, '2024_11_07_043409_add_abbrev_to_units_of_measurement_table', 12),
(46, '2024_11_07_045358_add_abbrev_to_item_unit_of_measurements_table', 13),
(47, '2024_11_07_054132_add_abbrev_to_item_unit_of_measurements_table', 14),
(48, '2024_11_07_132332_add_abbrev_to_items_table', 15);

-- --------------------------------------------------------

--
-- Table structure for table `password_reset_tokens`
--

CREATE TABLE `password_reset_tokens` (
  `email` varchar(255) NOT NULL,
  `token` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `projects`
--

CREATE TABLE `projects` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `description` text DEFAULT NULL,
  `account_status` text DEFAULT NULL,
  `package` text DEFAULT NULL,
  `budget` decimal(15,2) DEFAULT NULL,
  `subscribe_start` date DEFAULT NULL,
  `subscribe_end` date DEFAULT NULL,
  `status` enum('pending','in_progress','completed','on_hold') NOT NULL DEFAULT 'pending',
  `user_id` bigint(20) UNSIGNED NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `projects`
--

INSERT INTO `projects` (`id`, `name`, `description`, `account_status`, `package`, `budget`, `subscribe_start`, `subscribe_end`, `status`, `user_id`, `created_at`, `updated_at`) VALUES
(1, 'Roasters Apartments', 'Test Project Description', NULL, NULL, 20000000.00, NULL, NULL, 'pending', 1, '2024-10-11 10:37:58', '2024-10-11 10:37:58');

-- --------------------------------------------------------

--
-- Table structure for table `sections`
--

CREATE TABLE `sections` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `description` text DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `sections`
--

INSERT INTO `sections` (`id`, `name`, `description`, `created_at`, `updated_at`) VALUES
(2, 'SITE CLEARANCE', 'Preparatory activities required to make the site ready for construction', '2024-10-29 06:40:21', '2024-10-29 06:40:21'),
(3, 'CONCRETE WORKS', 'Covers all activities related to concrete production, placement, and curing in a construction project', '2024-10-31 05:36:14', '2024-10-31 05:36:14'),
(4, 'REINFORCED CONCRETE', 'Test', '2024-10-31 14:00:59', '2024-10-31 14:00:59');

-- --------------------------------------------------------

--
-- Table structure for table `sessions`
--

CREATE TABLE `sessions` (
  `id` varchar(255) NOT NULL,
  `user_id` bigint(20) UNSIGNED DEFAULT NULL,
  `ip_address` varchar(45) DEFAULT NULL,
  `user_agent` text DEFAULT NULL,
  `payload` longtext NOT NULL,
  `last_activity` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `sessions`
--

INSERT INTO `sessions` (`id`, `user_id`, `ip_address`, `user_agent`, `payload`, `last_activity`) VALUES
('OgMZ86iNTHUohviqfKjiaoYBL9qcpczaZi9J13BG', 1, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:132.0) Gecko/20100101 Firefox/132.0', 'YTo0OntzOjY6Il90b2tlbiI7czo0MDoiNldDczBqbnhTa0hnc2t4N1EwbjFmOW9tNTYybHhuYXdwSExqSlNVcCI7czo2OiJfZmxhc2giO2E6Mjp7czozOiJvbGQiO2E6MDp7fXM6MzoibmV3IjthOjA6e319czo5OiJfcHJldmlvdXMiO2E6MTp7czozOiJ1cmwiO3M6Mjg6Imh0dHA6Ly8xMjcuMC4wLjE6ODAwMC9ib21zLzIiO31zOjUwOiJsb2dpbl93ZWJfNTliYTM2YWRkYzJiMmY5NDAxNTgwZjAxNGM3ZjU4ZWE0ZTMwOTg5ZCI7aToxO30=', 1730995601);

-- --------------------------------------------------------

--
-- Table structure for table `sub_elements`
--

CREATE TABLE `sub_elements` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `description` text DEFAULT NULL,
  `element_id` bigint(20) UNSIGNED NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `sub_elements`
--

INSERT INTO `sub_elements` (`id`, `name`, `description`, `element_id`, `created_at`, `updated_at`) VALUES
(2, 'Tree Cutting', NULL, 2, '2024-10-29 06:43:24', '2024-10-29 06:43:24'),
(3, 'Grass and Bush Clearing', NULL, 2, '2024-10-29 10:02:51', '2024-10-29 10:02:51'),
(4, 'Building Demolition', NULL, 3, '2024-10-30 02:37:39', '2024-10-30 02:37:39'),
(5, 'Pavement and Walkway Removal', NULL, 3, '2024-10-30 03:00:20', '2024-10-30 03:00:20'),
(6, 'Debris Collection', NULL, 4, '2024-10-30 03:38:17', '2024-10-30 03:38:17'),
(7, 'Waste Disposal', NULL, 4, '2024-10-30 03:44:45', '2024-10-30 03:44:45'),
(8, 'Excavation and Earthworks', NULL, 5, '2024-10-30 04:26:00', '2024-10-30 04:26:00'),
(9, 'Grading and Compaction', NULL, 5, '2024-10-30 04:38:20', '2024-10-30 04:38:20'),
(10, 'Silt Fencing Installation', NULL, 6, '2024-10-30 05:12:29', '2024-10-30 05:12:29'),
(11, 'Temporary Drainage System', NULL, 6, '2024-10-30 05:30:56', '2024-10-30 05:30:56'),
(12, 'Asbestos Removal', NULL, 7, '2024-10-30 05:47:05', '2024-10-30 05:47:05'),
(13, 'Excavation and Base Preparation', NULL, 8, '2024-10-31 06:16:35', '2024-10-31 06:16:35'),
(14, 'Concrete Frames', NULL, 9, '2024-10-31 14:02:17', '2024-10-31 14:02:17');

-- --------------------------------------------------------

--
-- Table structure for table `suppliers`
--

CREATE TABLE `suppliers` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `contact_info` varchar(255) DEFAULT NULL,
  `material_supplied` varchar(255) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `units_of_measurement`
--

CREATE TABLE `units_of_measurement` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `abbrev` varchar(255) DEFAULT NULL,
  `type` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `units_of_measurement`
--

INSERT INTO `units_of_measurement` (`id`, `name`, `abbrev`, `type`, `created_at`, `updated_at`) VALUES
(23, 'Millimeter', 'mm', 'length', NULL, NULL),
(24, 'Centimeter', 'cm', 'length', NULL, NULL),
(25, 'Meter', 'm', 'length', NULL, NULL),
(26, 'Kilometer', 'km', 'length', NULL, NULL),
(27, 'Roll', 'roll', 'length', NULL, NULL),
(28, 'Square Centimeter', 'cm²', 'area', NULL, NULL),
(29, 'Square Meter', 'm²', 'area', NULL, NULL),
(30, 'Hectare', 'ha', 'area', NULL, NULL),
(31, 'Square Kilometer', 'km²', 'area', NULL, NULL),
(32, 'Cubic Centimeter', 'cm³', 'volume', NULL, NULL),
(33, 'Cubic Meter', 'm³', 'volume', NULL, NULL),
(34, 'Liter', 'L', 'volume', NULL, NULL),
(35, 'Milliliter', 'mL', 'volume', NULL, NULL),
(36, 'Gram', 'g', 'weight', NULL, NULL),
(37, 'Kilogram', 'kg', 'weight', NULL, NULL),
(38, 'Metric Ton', 't', 'weight', NULL, NULL),
(39, 'Milligram', 'mg', 'weight', NULL, NULL),
(40, 'Each', 'ea', 'quantity', NULL, NULL),
(41, 'Set', 'set', 'quantity', NULL, NULL),
(42, 'Hour', 'hr', 'time', NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `user_type` varchar(255) DEFAULT NULL,
  `project_id` varchar(255) DEFAULT NULL,
  `has_project` varchar(255) DEFAULT NULL,
  `email` varchar(255) NOT NULL,
  `email_verified_at` timestamp NULL DEFAULT NULL,
  `password` varchar(255) NOT NULL,
  `remember_token` varchar(100) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `name`, `user_type`, `project_id`, `has_project`, `email`, `email_verified_at`, `password`, `remember_token`, `created_at`, `updated_at`) VALUES
(1, 'Ezekiel Muki', 'user', '1', '1', 'user@demo.com', NULL, '$2y$12$BjLjLuk8XcLW9x6C/Ox4ceWeh5piKOEomIKnFoIXVYNGIFpAIs2T6', 'Y4E1hVXAEKeRsSLZgfO7aQ0Za46Upe7X7hOt21AVl3tEB7Iw5iCbjHTDFmx8', '2024-10-11 08:51:33', '2024-10-11 10:37:58'),
(2, 'Ezekiel Muki', 'admin', NULL, NULL, 'admin@demo.com', NULL, '$2y$12$BjLjLuk8XcLW9x6C/Ox4ceWeh5piKOEomIKnFoIXVYNGIFpAIs2T6', '7jnzOlmiIsWK3UiaxzzhadLCpDmPWwQdxdVlUyQhcz4Ji4yHojTslC4o3nTF', '2024-10-11 08:51:33', '2024-10-11 08:51:33');

-- --------------------------------------------------------

--
-- Table structure for table `workers`
--

CREATE TABLE `workers` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `full_name` varchar(255) NOT NULL,
  `id_number` varchar(255) NOT NULL,
  `job_category` enum('Mason','Site Manager','Quantity Surveyor','Carpenter','Plumber','Helper/Casual','Painter','Sub Contractor','Electrician','Supervisor','Assistant Supervisor') NOT NULL,
  `work_type` enum('Under Contract','Casual') NOT NULL,
  `phone` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `details` varchar(255) NOT NULL DEFAULT '>',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `project_id` bigint(20) UNSIGNED DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `boms`
--
ALTER TABLE `boms`
  ADD PRIMARY KEY (`id`),
  ADD KEY `boms_bq_document_id_foreign` (`bq_document_id`);

--
-- Indexes for table `bom_items`
--
ALTER TABLE `bom_items`
  ADD PRIMARY KEY (`id`),
  ADD KEY `bom_items_bom_id_foreign` (`bom_id`),
  ADD KEY `bom_items_bq_section_id_foreign` (`bq_section_id`),
  ADD KEY `bom_items_bq_item_id_foreign` (`bq_item_id`);

--
-- Indexes for table `bq_documents`
--
ALTER TABLE `bq_documents`
  ADD PRIMARY KEY (`id`),
  ADD KEY `bq_documents_user_id_foreign` (`user_id`),
  ADD KEY `bq_documents_project_id_foreign` (`project_id`);

--
-- Indexes for table `bq_items`
--
ALTER TABLE `bq_items`
  ADD PRIMARY KEY (`id`),
  ADD KEY `bq_items_bq_document_id_foreign` (`bq_document_id`),
  ADD KEY `bq_items_bq_section_id_foreign` (`bq_section_id`);

--
-- Indexes for table `bq_sections`
--
ALTER TABLE `bq_sections`
  ADD PRIMARY KEY (`id`),
  ADD KEY `bq_sections_bq_document_id_foreign` (`bq_document_id`);

--
-- Indexes for table `branches`
--
ALTER TABLE `branches`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `cache`
--
ALTER TABLE `cache`
  ADD PRIMARY KEY (`key`);

--
-- Indexes for table `cache_locks`
--
ALTER TABLE `cache_locks`
  ADD PRIMARY KEY (`key`);

--
-- Indexes for table `documents`
--
ALTER TABLE `documents`
  ADD PRIMARY KEY (`id`),
  ADD KEY `documents_user_id_foreign` (`user_id`);

--
-- Indexes for table `elements`
--
ALTER TABLE `elements`
  ADD PRIMARY KEY (`id`),
  ADD KEY `elements_section_id_foreign` (`section_id`);

--
-- Indexes for table `failed_jobs`
--
ALTER TABLE `failed_jobs`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `failed_jobs_uuid_unique` (`uuid`);

--
-- Indexes for table `items`
--
ALTER TABLE `items`
  ADD PRIMARY KEY (`id`),
  ADD KEY `items_sub_element_id_foreign` (`sub_element_id`);

--
-- Indexes for table `item_materials`
--
ALTER TABLE `item_materials`
  ADD PRIMARY KEY (`id`),
  ADD KEY `item_materials_item_id_foreign` (`item_id`);

--
-- Indexes for table `item_unit_of_measurements`
--
ALTER TABLE `item_unit_of_measurements`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `jobs`
--
ALTER TABLE `jobs`
  ADD PRIMARY KEY (`id`),
  ADD KEY `jobs_queue_index` (`queue`);

--
-- Indexes for table `job_batches`
--
ALTER TABLE `job_batches`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `lessons`
--
ALTER TABLE `lessons`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `materials`
--
ALTER TABLE `materials`
  ADD PRIMARY KEY (`id`),
  ADD KEY `materials_supplier_id_foreign` (`supplier_id`),
  ADD KEY `materials_project_id_foreign` (`project_id`);

--
-- Indexes for table `migrations`
--
ALTER TABLE `migrations`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `password_reset_tokens`
--
ALTER TABLE `password_reset_tokens`
  ADD PRIMARY KEY (`email`);

--
-- Indexes for table `projects`
--
ALTER TABLE `projects`
  ADD PRIMARY KEY (`id`),
  ADD KEY `projects_user_id_foreign` (`user_id`);

--
-- Indexes for table `sections`
--
ALTER TABLE `sections`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `sessions`
--
ALTER TABLE `sessions`
  ADD PRIMARY KEY (`id`),
  ADD KEY `sessions_user_id_index` (`user_id`),
  ADD KEY `sessions_last_activity_index` (`last_activity`);

--
-- Indexes for table `sub_elements`
--
ALTER TABLE `sub_elements`
  ADD PRIMARY KEY (`id`),
  ADD KEY `sub_elements_element_id_foreign` (`element_id`);

--
-- Indexes for table `suppliers`
--
ALTER TABLE `suppliers`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `units_of_measurement`
--
ALTER TABLE `units_of_measurement`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `users_email_unique` (`email`);

--
-- Indexes for table `workers`
--
ALTER TABLE `workers`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `workers_id_number_unique` (`id_number`),
  ADD UNIQUE KEY `workers_phone_unique` (`phone`),
  ADD UNIQUE KEY `workers_email_unique` (`email`),
  ADD KEY `workers_project_id_foreign` (`project_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `boms`
--
ALTER TABLE `boms`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `bom_items`
--
ALTER TABLE `bom_items`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=21;

--
-- AUTO_INCREMENT for table `bq_documents`
--
ALTER TABLE `bq_documents`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `bq_items`
--
ALTER TABLE `bq_items`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `bq_sections`
--
ALTER TABLE `bq_sections`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=21;

--
-- AUTO_INCREMENT for table `branches`
--
ALTER TABLE `branches`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `documents`
--
ALTER TABLE `documents`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `elements`
--
ALTER TABLE `elements`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT for table `failed_jobs`
--
ALTER TABLE `failed_jobs`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `items`
--
ALTER TABLE `items`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=28;

--
-- AUTO_INCREMENT for table `item_materials`
--
ALTER TABLE `item_materials`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=48;

--
-- AUTO_INCREMENT for table `item_unit_of_measurements`
--
ALTER TABLE `item_unit_of_measurements`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=42;

--
-- AUTO_INCREMENT for table `jobs`
--
ALTER TABLE `jobs`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `lessons`
--
ALTER TABLE `lessons`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `materials`
--
ALTER TABLE `materials`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `migrations`
--
ALTER TABLE `migrations`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=49;

--
-- AUTO_INCREMENT for table `projects`
--
ALTER TABLE `projects`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `sections`
--
ALTER TABLE `sections`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `sub_elements`
--
ALTER TABLE `sub_elements`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=15;

--
-- AUTO_INCREMENT for table `suppliers`
--
ALTER TABLE `suppliers`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `units_of_measurement`
--
ALTER TABLE `units_of_measurement`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=43;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `workers`
--
ALTER TABLE `workers`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `boms`
--
ALTER TABLE `boms`
  ADD CONSTRAINT `boms_bq_document_id_foreign` FOREIGN KEY (`bq_document_id`) REFERENCES `bq_documents` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `bom_items`
--
ALTER TABLE `bom_items`
  ADD CONSTRAINT `bom_items_bom_id_foreign` FOREIGN KEY (`bom_id`) REFERENCES `boms` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `bom_items_bq_item_id_foreign` FOREIGN KEY (`bq_item_id`) REFERENCES `bq_items` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `bom_items_bq_section_id_foreign` FOREIGN KEY (`bq_section_id`) REFERENCES `bq_sections` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `bq_documents`
--
ALTER TABLE `bq_documents`
  ADD CONSTRAINT `bq_documents_project_id_foreign` FOREIGN KEY (`project_id`) REFERENCES `projects` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `bq_documents_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `bq_items`
--
ALTER TABLE `bq_items`
  ADD CONSTRAINT `bq_items_bq_document_id_foreign` FOREIGN KEY (`bq_document_id`) REFERENCES `bq_documents` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `bq_items_bq_section_id_foreign` FOREIGN KEY (`bq_section_id`) REFERENCES `bq_sections` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `bq_sections`
--
ALTER TABLE `bq_sections`
  ADD CONSTRAINT `bq_sections_bq_document_id_foreign` FOREIGN KEY (`bq_document_id`) REFERENCES `bq_documents` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `documents`
--
ALTER TABLE `documents`
  ADD CONSTRAINT `documents_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`);

--
-- Constraints for table `elements`
--
ALTER TABLE `elements`
  ADD CONSTRAINT `elements_section_id_foreign` FOREIGN KEY (`section_id`) REFERENCES `sections` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `items`
--
ALTER TABLE `items`
  ADD CONSTRAINT `items_sub_element_id_foreign` FOREIGN KEY (`sub_element_id`) REFERENCES `sub_elements` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `item_materials`
--
ALTER TABLE `item_materials`
  ADD CONSTRAINT `item_materials_item_id_foreign` FOREIGN KEY (`item_id`) REFERENCES `items` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `materials`
--
ALTER TABLE `materials`
  ADD CONSTRAINT `materials_project_id_foreign` FOREIGN KEY (`project_id`) REFERENCES `projects` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `materials_supplier_id_foreign` FOREIGN KEY (`supplier_id`) REFERENCES `suppliers` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `projects`
--
ALTER TABLE `projects`
  ADD CONSTRAINT `projects_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `sub_elements`
--
ALTER TABLE `sub_elements`
  ADD CONSTRAINT `sub_elements_element_id_foreign` FOREIGN KEY (`element_id`) REFERENCES `elements` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `workers`
--
ALTER TABLE `workers`
  ADD CONSTRAINT `workers_project_id_foreign` FOREIGN KEY (`project_id`) REFERENCES `projects` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
