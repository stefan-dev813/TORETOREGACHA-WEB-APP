-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Dec 16, 2023 at 01:57 PM
-- Server version: 10.4.28-MariaDB
-- PHP Version: 8.0.28

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `toretore`
--

-- --------------------------------------------------------

--
-- Table structure for table `categories`
--

CREATE TABLE `categories` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `title` varchar(255) NOT NULL,
  `order` int(11) NOT NULL DEFAULT 100,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `categories`
--

INSERT INTO `categories` (`id`, `title`, `order`, `created_at`, `updated_at`) VALUES
(1, 'ポケモン', 0, '2023-05-01 09:52:18', '2023-05-01 09:52:18'),
(4, 'その他', 3, '2023-05-01 09:52:18', '2023-05-01 09:52:18');

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
-- Table structure for table `favorites`
--

CREATE TABLE `favorites` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `user_id` bigint(20) NOT NULL,
  `product_id` bigint(20) NOT NULL,
  `status` tinyint(4) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `gachas`
--

CREATE TABLE `gachas` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `point` int(11) NOT NULL,
  `count_card` int(11) NOT NULL,
  `count` int(11) NOT NULL DEFAULT 0,
  `lost_product_type` varchar(255) DEFAULT NULL,
  `thumbnail` varchar(255) NOT NULL,
  `image` varchar(255) NOT NULL,
  `category_id` int(11) NOT NULL,
  `order_level` int(11) NOT NULL DEFAULT 100000,
  `status` tinyint(4) NOT NULL DEFAULT 0,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `gachas`
--

INSERT INTO `gachas` (`id`, `point`, `count_card`, `count`, `lost_product_type`, `thumbnail`, `image`, `category_id`, `order_level`, `status`, `created_at`, `updated_at`) VALUES
(8, 100, 10, 10, NULL, '6579989b01336.jpg', '6579989b005a8.jpg', 1, 100000, 1, '2023-12-13 11:42:19', '2023-12-13 11:43:24'),
(9, 100, 500, 4, NULL, '657998c085ee1.jpg', '657998c085618.jpg', 1, 100000, 1, '2023-12-13 11:42:56', '2023-12-14 15:21:22');

-- --------------------------------------------------------

--
-- Table structure for table `gacha_lost_products`
--

CREATE TABLE `gacha_lost_products` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `gacha_id` bigint(20) NOT NULL,
  `point` int(11) NOT NULL,
  `count` int(11) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `gacha_lost_products`
--

INSERT INTO `gacha_lost_products` (`id`, `gacha_id`, `point`, `count`, `created_at`, `updated_at`) VALUES
(78, 8, 100, 0, '2023-12-13 11:42:26', '2023-12-13 11:43:24'),
(79, 9, 100, 496, '2023-12-13 11:43:05', '2023-12-14 15:21:22');

-- --------------------------------------------------------

--
-- Table structure for table `gacha_records`
--

CREATE TABLE `gacha_records` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `user_id` bigint(20) NOT NULL,
  `gacha_id` bigint(20) NOT NULL,
  `type` tinyint(4) NOT NULL DEFAULT 0,
  `status` tinyint(4) NOT NULL DEFAULT 0,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `gacha_records`
--

INSERT INTO `gacha_records` (`id`, `user_id`, `gacha_id`, `type`, `status`, `created_at`, `updated_at`) VALUES
(75, 2, 8, 10, 1, '2023-12-13 11:43:24', '2023-12-13 11:43:24'),
(76, 2, 9, 1, 1, '2023-12-14 08:05:29', '2023-12-14 08:05:29'),
(77, 2, 9, 1, 1, '2023-12-14 15:19:27', '2023-12-14 15:19:27'),
(78, 2, 9, 1, 1, '2023-12-14 15:20:52', '2023-12-14 15:20:52'),
(79, 2, 9, 1, 1, '2023-12-14 15:21:22', '2023-12-14 15:21:22');

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
(1, '2014_10_12_000000_create_users_table', 1),
(2, '2014_10_12_100000_create_password_resets_table', 1),
(3, '2019_08_19_000000_create_failed_jobs_table', 1),
(4, '2019_12_14_000001_create_personal_access_tokens_table', 1),
(5, '2022_11_09_124954_create_points_table', 1),
(6, '2022_11_09_131244_create_categories_table', 1),
(7, '2022_11_09_232603_create_verifies_table', 1),
(8, '2022_11_22_002837_create_gachas_table', 1),
(9, '2022_11_22_111643_create_products_table', 1),
(10, '2022_11_23_231437_create_favorites_table', 1),
(11, '2022_11_24_163846_create_gacha_records_table', 1),
(12, '2022_11_24_233835_create_options_table', 1),
(13, '2022_11_25_203515_create_profiles_table', 1),
(14, '2022_11_29_015757_create_payments_table', 1),
(15, '2022_12_18_015532_create_gacha_lost_products_table', 1);

-- --------------------------------------------------------

--
-- Table structure for table `options`
--

CREATE TABLE `options` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `value` text DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `options`
--

INSERT INTO `options` (`id`, `name`, `value`, `created_at`, `updated_at`) VALUES
(1, 'is_busy', '0', '2023-05-04 10:38:44', '2023-12-14 15:21:22'),
(2, 'testOrLive', 'test', '2023-05-05 15:44:00', '2023-12-14 15:22:07'),
(3, 'is3DSecure', '0', '2023-05-05 15:46:31', '2023-12-14 15:22:07'),
(4, 'has3DChallenge', '1', '2023-05-05 15:46:31', '2023-06-23 03:36:29');

-- --------------------------------------------------------

--
-- Table structure for table `password_resets`
--

CREATE TABLE `password_resets` (
  `email` varchar(255) NOT NULL,
  `token` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `password_resets`
--

INSERT INTO `password_resets` (`email`, `token`, `created_at`) VALUES
('ewqqwe223@yandex.com', '$2y$10$QHkz1VfqaU/VGUUJtLp7l.S3BAoow62tB29TojRNPkNjUF35zSqta', '2023-05-06 10:22:10'),
('user@example.com', '$2y$10$5bk6bV0jpJlzcUqmH.mZTeatSYZRq5VSoNtWy0trhXebTl3X2BWYC', '2023-05-12 15:07:16');

-- --------------------------------------------------------

--
-- Table structure for table `payments`
--

CREATE TABLE `payments` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `user_id` bigint(20) NOT NULL,
  `point_id` bigint(20) NOT NULL,
  `order_id` varchar(255) NOT NULL,
  `access_id` varchar(255) NOT NULL,
  `status` tinyint(4) NOT NULL DEFAULT 0,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `payments`
--

INSERT INTO `payments` (`id`, `user_id`, `point_id`, `order_id`, `access_id`, `status`, `created_at`, `updated_at`) VALUES
(36, 2, 1, 'o_Cnavi49nQLGSHHhiPwl1lg', 'a_S_obkvWfS1-F-uwRgQsNfw', 1, '2023-12-14 15:22:15', '2023-12-14 15:22:50');

-- --------------------------------------------------------

--
-- Table structure for table `personal_access_tokens`
--

CREATE TABLE `personal_access_tokens` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `tokenable_type` varchar(255) NOT NULL,
  `tokenable_id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `token` varchar(64) NOT NULL,
  `abilities` text DEFAULT NULL,
  `last_used_at` timestamp NULL DEFAULT NULL,
  `expires_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `points`
--

CREATE TABLE `points` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `title` varchar(255) NOT NULL,
  `point` int(11) NOT NULL DEFAULT 0,
  `amount` int(11) NOT NULL,
  `image` text NOT NULL,
  `category_id` bigint(20) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `points`
--

INSERT INTO `points` (`id`, `title`, `point`, `amount`, `image`, `category_id`, `created_at`, `updated_at`) VALUES
(1, '500 points', 500, 500, '657ab6e7a41ef.jpg', 1, '2023-05-05 02:21:42', '2023-12-14 08:03:51'),
(2, '1,000 points', 1000, 1000, '657ab6ec93fe2.jpg', 1, '2023-05-05 02:34:53', '2023-12-14 08:03:56'),
(3, '5,000 points', 5000, 5000, '657ab6f1ee667.jpg', 1, '2023-05-05 02:35:46', '2023-12-14 08:04:01'),
(4, '10,000 points', 10000, 10000, '657ab6f6ea515.jpg', 1, '2023-05-05 02:36:15', '2023-12-14 08:04:06'),
(5, '50,000 points', 50000, 50000, '657ab6fbe66a6.jpg', 1, '2023-05-05 02:36:48', '2023-12-14 08:04:11'),
(6, '100,000 points', 100000, 100000, '657ab700a7b3e.jpg', 1, '2023-05-05 02:37:15', '2023-12-14 08:04:16');

-- --------------------------------------------------------

--
-- Table structure for table `products`
--

CREATE TABLE `products` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `point` int(11) NOT NULL DEFAULT 0,
  `dp` int(11) DEFAULT NULL,
  `rare` varchar(255) DEFAULT NULL,
  `image` varchar(255) NOT NULL,
  `marks` int(11) NOT NULL DEFAULT 0,
  `is_last` tinyint(4) NOT NULL DEFAULT 0,
  `lost_type` varchar(255) DEFAULT NULL,
  `is_lost_product` tinyint(4) NOT NULL DEFAULT 0,
  `gacha_id` bigint(20) NOT NULL DEFAULT 0,
  `category_id` int(11) NOT NULL DEFAULT 0,
  `status_product` varchar(255) DEFAULT NULL,
  `product_type` varchar(255) DEFAULT NULL,
  `gacha_record_id` int(11) NOT NULL DEFAULT 0,
  `user_id` int(11) NOT NULL DEFAULT 0,
  `status` tinyint(4) NOT NULL DEFAULT 0,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `products`
--

INSERT INTO `products` (`id`, `name`, `point`, `dp`, `rare`, `image`, `marks`, `is_last`, `lost_type`, `is_lost_product`, `gacha_id`, `category_id`, `status_product`, `product_type`, `gacha_record_id`, `user_id`, `status`, `created_at`, `updated_at`) VALUES
(141, 'リーリエ', 100, NULL, 'HR', '65799884dab44.jpeg', 986, 0, NULL, 1, 0, 1, NULL, NULL, 0, 0, 0, '2023-12-13 11:41:56', '2023-12-14 15:21:22'),
(142, 'リーリエ', 100, NULL, 'HR', '65799884dab44.jpeg', 1, 0, NULL, 1, 8, 1, NULL, NULL, 75, 2, 1, '2023-12-13 11:43:24', '2023-12-13 11:43:24'),
(143, 'リーリエ', 100, NULL, 'HR', '65799884dab44.jpeg', 1, 0, NULL, 1, 8, 1, NULL, NULL, 75, 2, 1, '2023-12-13 11:43:24', '2023-12-13 11:43:24'),
(144, 'リーリエ', 100, NULL, 'HR', '65799884dab44.jpeg', 1, 0, NULL, 1, 8, 1, NULL, NULL, 75, 2, 1, '2023-12-13 11:43:24', '2023-12-13 11:43:24'),
(145, 'リーリエ', 100, NULL, 'HR', '65799884dab44.jpeg', 1, 0, NULL, 1, 8, 1, NULL, NULL, 75, 2, 1, '2023-12-13 11:43:24', '2023-12-13 11:43:24'),
(146, 'リーリエ', 100, NULL, 'HR', '65799884dab44.jpeg', 1, 0, NULL, 1, 8, 1, NULL, NULL, 75, 2, 1, '2023-12-13 11:43:24', '2023-12-13 11:43:24'),
(147, 'リーリエ', 100, NULL, 'HR', '65799884dab44.jpeg', 1, 0, NULL, 1, 8, 1, NULL, NULL, 75, 2, 1, '2023-12-13 11:43:24', '2023-12-13 11:43:24'),
(148, 'リーリエ', 100, NULL, 'HR', '65799884dab44.jpeg', 1, 0, NULL, 1, 8, 1, NULL, NULL, 75, 2, 1, '2023-12-13 11:43:24', '2023-12-13 11:43:24'),
(149, 'リーリエ', 100, NULL, 'HR', '65799884dab44.jpeg', 1, 0, NULL, 1, 8, 1, NULL, NULL, 75, 2, 1, '2023-12-13 11:43:24', '2023-12-13 11:43:24'),
(150, 'リーリエ', 100, NULL, 'HR', '65799884dab44.jpeg', 1, 0, NULL, 1, 8, 1, NULL, NULL, 75, 2, 1, '2023-12-13 11:43:24', '2023-12-13 11:43:24'),
(151, 'リーリエ', 100, NULL, 'HR', '65799884dab44.jpeg', 1, 0, NULL, 1, 8, 1, NULL, NULL, 75, 2, 3, '2023-12-13 11:43:24', '2023-12-14 08:07:16'),
(152, 'リーリエ', 100, NULL, 'HR', '65799884dab44.jpeg', 1, 0, NULL, 1, 9, 1, NULL, NULL, 76, 2, 2, '2023-12-14 08:05:29', '2023-12-14 08:05:48'),
(153, 'リーリエ', 100, NULL, 'HR', '65799884dab44.jpeg', 1, 0, NULL, 1, 9, 1, NULL, NULL, 77, 2, 2, '2023-12-14 15:19:27', '2023-12-14 15:20:45'),
(154, 'リーリエ', 100, NULL, 'HR', '65799884dab44.jpeg', 1, 0, NULL, 1, 9, 1, NULL, NULL, 78, 2, 2, '2023-12-14 15:20:52', '2023-12-14 15:21:21'),
(155, 'リーリエ', 100, NULL, 'HR', '65799884dab44.jpeg', 1, 0, NULL, 1, 9, 1, NULL, NULL, 79, 2, 1, '2023-12-14 15:21:22', '2023-12-14 15:21:22');

-- --------------------------------------------------------

--
-- Table structure for table `profiles`
--

CREATE TABLE `profiles` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `user_id` bigint(20) NOT NULL,
  `first_name` varchar(255) NOT NULL,
  `last_name` varchar(255) NOT NULL,
  `first_name_gana` varchar(255) NOT NULL,
  `last_name_gana` varchar(255) NOT NULL,
  `postal_code` varchar(255) NOT NULL,
  `prefecture` varchar(255) NOT NULL,
  `address` text NOT NULL,
  `phone` varchar(255) NOT NULL,
  `status` tinyint(4) NOT NULL DEFAULT 0,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `profiles`
--

INSERT INTO `profiles` (`id`, `user_id`, `first_name`, `last_name`, `first_name_gana`, `last_name_gana`, `postal_code`, `prefecture`, `address`, `phone`, `status`, `created_at`, `updated_at`) VALUES
(1, 2, '田口', '輝政', 'タグチ', 'テルマサ', '519-2911', '三重県', '度会郡大紀町錦2-7-3', '07060306818', 0, '2023-05-04 03:32:55', '2023-05-04 03:32:55');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `phone` varchar(255) NOT NULL,
  `email_verified_at` timestamp NULL DEFAULT NULL,
  `password` varchar(255) NOT NULL,
  `remember_token` varchar(100) DEFAULT NULL,
  `type` tinyint(4) NOT NULL DEFAULT 0,
  `dp` int(11) NOT NULL DEFAULT 0,
  `point` int(11) NOT NULL DEFAULT 0,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `name`, `email`, `phone`, `email_verified_at`, `password`, `remember_token`, `type`, `dp`, `point`, `created_at`, `updated_at`) VALUES
(1, '管理者', 'admin@example.com', '12345678', NULL, '$2y$10$hP6Y7IgyA0fHzrYpvT2TGOKEgSp1Rb.l05jisz6tEhU/10eVGv1h2', NULL, 1, 0, 0, '2023-05-01 09:52:18', '2023-05-01 09:52:18'),
(2, 'ユーザー', 'test@user.com', '11111111', NULL, '$2y$10$ZztIFuGRAXblREih99e2SevNr7JEkqPXsv8jQa5eD8buTzgoWOxfi', NULL, 0, 98221, 15000, '2023-05-01 09:52:18', '2023-12-14 15:22:50');

-- --------------------------------------------------------

--
-- Table structure for table `verifies`
--

CREATE TABLE `verifies` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `to` varchar(255) NOT NULL,
  `code` varchar(255) NOT NULL,
  `status` tinyint(4) NOT NULL DEFAULT 0,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `verifies`
--

INSERT INTO `verifies` (`id`, `to`, `code`, `status`, `created_at`, `updated_at`) VALUES
(11, '07060306818', '1111', 2, '2023-05-13 13:46:32', '2023-05-13 13:46:38'),
(12, 'ewqqwe223@yandex.com', '9113', 1, '2023-06-23 14:21:39', '2023-12-13 11:43:50'),
(13, 'test@test.com', '5434', 1, '2023-06-23 15:14:02', '2023-06-23 15:15:20'),
(14, 'test@test.com', '4130', 0, '2023-06-23 15:15:20', '2023-06-23 15:15:20'),
(15, 'ewqqwe223@yandex.com', '1441', 0, '2023-12-13 11:43:50', '2023-12-13 11:43:50');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `categories`
--
ALTER TABLE `categories`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `categories_title_unique` (`title`);

--
-- Indexes for table `failed_jobs`
--
ALTER TABLE `failed_jobs`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `failed_jobs_uuid_unique` (`uuid`);

--
-- Indexes for table `favorites`
--
ALTER TABLE `favorites`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `gachas`
--
ALTER TABLE `gachas`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `gacha_lost_products`
--
ALTER TABLE `gacha_lost_products`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `gacha_records`
--
ALTER TABLE `gacha_records`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `migrations`
--
ALTER TABLE `migrations`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `options`
--
ALTER TABLE `options`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `password_resets`
--
ALTER TABLE `password_resets`
  ADD KEY `password_resets_email_index` (`email`);

--
-- Indexes for table `payments`
--
ALTER TABLE `payments`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `personal_access_tokens`
--
ALTER TABLE `personal_access_tokens`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `personal_access_tokens_token_unique` (`token`),
  ADD KEY `personal_access_tokens_tokenable_type_tokenable_id_index` (`tokenable_type`,`tokenable_id`);

--
-- Indexes for table `points`
--
ALTER TABLE `points`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `products`
--
ALTER TABLE `products`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `profiles`
--
ALTER TABLE `profiles`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `users_email_unique` (`email`),
  ADD UNIQUE KEY `users_phone_unique` (`phone`);

--
-- Indexes for table `verifies`
--
ALTER TABLE `verifies`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `categories`
--
ALTER TABLE `categories`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `failed_jobs`
--
ALTER TABLE `failed_jobs`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `favorites`
--
ALTER TABLE `favorites`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `gachas`
--
ALTER TABLE `gachas`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT for table `gacha_lost_products`
--
ALTER TABLE `gacha_lost_products`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=80;

--
-- AUTO_INCREMENT for table `gacha_records`
--
ALTER TABLE `gacha_records`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=80;

--
-- AUTO_INCREMENT for table `migrations`
--
ALTER TABLE `migrations`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=16;

--
-- AUTO_INCREMENT for table `options`
--
ALTER TABLE `options`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `payments`
--
ALTER TABLE `payments`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=37;

--
-- AUTO_INCREMENT for table `personal_access_tokens`
--
ALTER TABLE `personal_access_tokens`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `points`
--
ALTER TABLE `points`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `products`
--
ALTER TABLE `products`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=156;

--
-- AUTO_INCREMENT for table `profiles`
--
ALTER TABLE `profiles`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT for table `verifies`
--
ALTER TABLE `verifies`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=16;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
