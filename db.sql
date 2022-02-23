-- phpMyAdmin SQL Dump
-- version 4.9.7
-- https://www.phpmyadmin.net/
--
-- Host: localhost:3306
-- Generation Time: Feb 23, 2022 at 05:45 PM
-- Server version: 5.7.23-23
-- PHP Version: 7.3.32

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `grocekmu_grocerbee_prod`
--

-- --------------------------------------------------------

--
-- Table structure for table `admin`
--

CREATE TABLE `admin` (
  `id` int(11) NOT NULL,
  `name` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `email` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `mobile_number` bigint(20) DEFAULT NULL,
  `password` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `added_by` int(11) DEFAULT NULL,
  `is_deleted` int(11) DEFAULT NULL,
  `admin_type_id` int(11) DEFAULT NULL,
  `created_at` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `admin_notification`
--

CREATE TABLE `admin_notification` (
  `id` int(11) NOT NULL,
  `admin_id` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `details` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `admin_type`
--

CREATE TABLE `admin_type` (
  `id` int(11) NOT NULL,
  `name` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `is_deleted` int(11) DEFAULT NULL,
  `created_at` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `android_view_flipper`
--

CREATE TABLE `android_view_flipper` (
  `id` int(11) NOT NULL,
  `upload_id` int(11) DEFAULT NULL,
  `action` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `action_keyword` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `brands`
--

CREATE TABLE `brands` (
  `id` int(11) NOT NULL,
  `name` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `is_active` int(11) DEFAULT NULL,
  `added_by` int(11) NOT NULL DEFAULT '1',
  `upload_id` int(11) DEFAULT NULL,
  `added_on` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `cancellation_reason`
--

CREATE TABLE `cancellation_reason` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `reason` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `cancellation_reason`
--

INSERT INTO `cancellation_reason` (`id`, `reason`) VALUES
(1, 'The product is unavailable at the current moment. We will notify you when it is available. Sorry for your inconvenience.');

-- --------------------------------------------------------

--
-- Table structure for table `cart`
--

CREATE TABLE `cart` (
  `id` int(11) NOT NULL,
  `customer_id` int(11) DEFAULT NULL,
  `is_active` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `cart_products`
--

CREATE TABLE `cart_products` (
  `id` int(11) NOT NULL,
  `cart_id` int(11) DEFAULT NULL,
  `product_id` int(11) DEFAULT NULL,
  `quantity` int(11) DEFAULT NULL,
  `is_active` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `clicked_product_details`
--

CREATE TABLE `clicked_product_details` (
  `id` bigint(20) NOT NULL,
  `user_id` bigint(20) DEFAULT NULL,
  `product_id` bigint(20) NOT NULL,
  `timestamp` bigint(20) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `customers`
--

CREATE TABLE `customers` (
  `id` int(11) NOT NULL,
  `name` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `email` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `mobile_number` bigint(20) DEFAULT NULL,
  `password` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `referral_code` varchar(15) COLLATE utf8_unicode_ci NOT NULL,
  `applied_referal` varchar(15) COLLATE utf8_unicode_ci DEFAULT NULL,
  `profile_image_upload_id` int(11) DEFAULT NULL,
  `is_deleted` int(11) DEFAULT NULL,
  `created_at` int(11) DEFAULT NULL,
  `last_active` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `customer_address`
--

CREATE TABLE `customer_address` (
  `id` int(11) NOT NULL,
  `customer_id` int(11) DEFAULT NULL,
  `city` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `village_id` int(11) DEFAULT NULL,
  `pin_code` int(11) DEFAULT NULL,
  `house_no` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `area` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `landmark` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `state` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `country` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `customer_feedback`
--

CREATE TABLE `customer_feedback` (
  `id` int(11) NOT NULL,
  `customer_id` int(11) DEFAULT NULL,
  `subject` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `details` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `reply_message` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `is_replied` int(11) DEFAULT NULL,
  `submitted_on` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `customer_search_history`
--

CREATE TABLE `customer_search_history` (
  `id` int(11) NOT NULL,
  `customer_id` int(11) DEFAULT NULL,
  `search_keyword` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `timestamp` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `delivery_charge`
--

CREATE TABLE `delivery_charge` (
  `id` bigint(20) NOT NULL,
  `charge` int(11) NOT NULL,
  `up_to` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `delivery_charge`
--

INSERT INTO `delivery_charge` (`id`, `charge`, `up_to`) VALUES
(1, 25, 399);

-- --------------------------------------------------------

--
-- Table structure for table `delivery_partner`
--

CREATE TABLE `delivery_partner` (
  `id` int(11) NOT NULL,
  `name` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `email` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `mobile_number` bigint(20) DEFAULT NULL,
  `password` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `added_by` int(11) DEFAULT NULL,
  `is_deleted` int(11) DEFAULT NULL,
  `created_at` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `delivery_partner`
--

INSERT INTO `delivery_partner` (`id`, `name`, `email`, `mobile_number`, `password`, `added_by`, `is_deleted`, `created_at`) VALUES
(6, 'Soumya Ghosh', 'soumyaghosh171297@gmail.com', 9933169269, '051b1fee2e7693174e7cf277615840c1280a431970420dcde4549c8969c43ab7', 1, 0, 1630897596),
(7, 'Shovan ojha', 'shovanojha123@gmail.com', 8240200125, '1257889177421ce932f66e6097ede9dd0b0c848edb38f527997d76acb1a97001', 1, 0, 1630950142),
(9, 'Samrat Si', 'samrat.si@grocerbee.co.in', 8768722378, 'f08439efd6138d67effcf15a7df10d45f252680b886e0f305b08e96e44a88655', 1, 0, 1631507940);

-- --------------------------------------------------------

--
-- Table structure for table `delivery_partner_notification`
--

CREATE TABLE `delivery_partner_notification` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `aggregator_id` int(11) NOT NULL,
  `details` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `action` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `action_keyword` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `timestamp` bigint(20) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `discount_coupon`
--

CREATE TABLE `discount_coupon` (
  `id` int(11) NOT NULL,
  `name` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `desc` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `order_price` float DEFAULT NULL,
  `discount_amount` float DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `failed_jobs`
--

CREATE TABLE `failed_jobs` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `uuid` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `connection` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `queue` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `payload` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `exception` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `failed_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `fcm_token`
--

CREATE TABLE `fcm_token` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `user_id` int(11) NOT NULL,
  `user_type` int(11) NOT NULL,
  `token` varchar(512) COLLATE utf8mb4_unicode_ci NOT NULL,
  `last_active` bigint(20) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `migrations`
--

CREATE TABLE `migrations` (
  `id` int(10) UNSIGNED NOT NULL,
  `migration` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `batch` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `minimum_order`
--

CREATE TABLE `minimum_order` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `minimum_order_amount` int(11) NOT NULL,
  `last_updated_on` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `minimum_order`
--

INSERT INTO `minimum_order` (`id`, `minimum_order_amount`, `last_updated_on`) VALUES
(1, 0, 1632065501);

-- --------------------------------------------------------

--
-- Table structure for table `orders`
--

CREATE TABLE `orders` (
  `id` int(11) NOT NULL,
  `assigned_delevery_partner_id` int(11) DEFAULT NULL,
  `ordered_by` int(11) DEFAULT NULL,
  `status_id` int(11) DEFAULT NULL,
  `total_mrp` float DEFAULT NULL,
  `total_cgst` float DEFAULT NULL,
  `total_sgst` float DEFAULT NULL,
  `total_igst` float DEFAULT NULL,
  `total_discount` float DEFAULT NULL,
  `order_address_id` int(11) NOT NULL,
  `total_sell_price` double(8,2) DEFAULT NULL,
  `applied_coupon_amount` double(8,2) NOT NULL DEFAULT '0.00',
  `total_payable_amount` double(8,2) DEFAULT NULL,
  `is_referral` int(11) NOT NULL DEFAULT '0',
  `invoice_number` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `cancellation_reason_id` int(11) DEFAULT NULL,
  `delivery_charge` double(8,2) NOT NULL,
  `last_status_timestamp` int(11) DEFAULT NULL,
  `customer_name` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `otp` int(11) DEFAULT NULL,
  `ordered_on` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `orders_details`
--

CREATE TABLE `orders_details` (
  `id` int(11) NOT NULL,
  `name` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `description` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `product_id` int(11) DEFAULT NULL,
  `quantity` int(11) DEFAULT NULL,
  `per_qty_mrp` float DEFAULT NULL,
  `per_qty_cgst` float DEFAULT NULL,
  `per_qty_sgst` float DEFAULT NULL,
  `per_qty_igst` float DEFAULT NULL,
  `per_qty_discount` float DEFAULT NULL,
  `order_id` int(11) NOT NULL,
  `per_qty_sell_price` double(8,2) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `order_address`
--

CREATE TABLE `order_address` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `customer_id` int(11) NOT NULL,
  `city` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `village_id` int(11) NOT NULL,
  `house_no` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `area` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `landmark` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `state` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `country` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `order_status_details`
--

CREATE TABLE `order_status_details` (
  `id` int(11) NOT NULL,
  `name` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `added_by` int(11) DEFAULT NULL,
  `is_active` int(11) DEFAULT NULL,
  `added_on` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `order_status_details`
--

INSERT INTO `order_status_details` (`id`, `name`, `added_by`, `is_active`, `added_on`) VALUES
(1, 'Active', 1, 1, 1625170529),
(2, 'Ready to deliver', 1, 1, 1625170529),
(3, 'Out for Delivery', 1, 1, 1625170529),
(4, 'Delivered', 1, 1, 1625170529),
(5, 'Canceled', 1, 1, 1625170529),
(6, 'Rejected', 1, 1, 1625170529);

-- --------------------------------------------------------

--
-- Table structure for table `password_resets`
--

CREATE TABLE `password_resets` (
  `email` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `token` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `pin_code`
--

CREATE TABLE `pin_code` (
  `id` int(10) UNSIGNED NOT NULL,
  `pincode` int(11) NOT NULL,
  `is_active` int(11) NOT NULL DEFAULT '1'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `pin_wise_village`
--

CREATE TABLE `pin_wise_village` (
  `id` int(11) NOT NULL,
  `village_name` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `pin_code` int(11) DEFAULT NULL,
  `is_active` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `product`
--

CREATE TABLE `product` (
  `id` int(11) NOT NULL,
  `name` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `desc` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `upload_ids` int(11) DEFAULT NULL,
  `unit_type_id` int(11) DEFAULT NULL,
  `quantity` int(11) DEFAULT NULL,
  `mrp` float DEFAULT NULL,
  `sell_price` float DEFAULT NULL,
  `brand_id` int(11) DEFAULT NULL,
  `category_id` int(11) DEFAULT NULL,
  `subcategory_id` int(11) DEFAULT NULL,
  `added_by` int(11) DEFAULT NULL,
  `cgst` float DEFAULT NULL,
  `sgst` float DEFAULT NULL,
  `igst` float DEFAULT NULL,
  `discount` float DEFAULT NULL,
  `min_qty` int(11) DEFAULT NULL,
  `max_qty` int(11) DEFAULT NULL,
  `is_in_stock` int(11) DEFAULT NULL,
  `ext_field` int(11) DEFAULT NULL,
  `created_on` int(11) DEFAULT NULL,
  `is_active` int(11) NOT NULL DEFAULT '1'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `product_category`
--

CREATE TABLE `product_category` (
  `id` int(11) NOT NULL,
  `name` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `added_by` int(11) DEFAULT NULL,
  `is_active` int(11) DEFAULT NULL,
  `upload_id` int(11) DEFAULT NULL,
  `added_on` int(11) DEFAULT NULL,
  `ordering` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `product_images`
--

CREATE TABLE `product_images` (
  `id` int(11) NOT NULL,
  `product_id` int(11) DEFAULT NULL,
  `upload_id` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `product_sub_category`
--

CREATE TABLE `product_sub_category` (
  `id` int(11) NOT NULL,
  `category_id` int(11) DEFAULT NULL,
  `name` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `added_by` int(11) DEFAULT NULL,
  `is_active` int(11) DEFAULT NULL,
  `upload_id` int(11) DEFAULT NULL,
  `added_on` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `product_tag`
--

CREATE TABLE `product_tag` (
  `id` bigint(20) NOT NULL,
  `name` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `product_id` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `referal_details`
--

CREATE TABLE `referal_details` (
  `id` bigint(20) NOT NULL,
  `referal_to` bigint(20) NOT NULL,
  `referal_for` bigint(20) NOT NULL,
  `valid_till` bigint(20) NOT NULL,
  `is_eligeble` int(11) NOT NULL,
  `is_used` int(11) NOT NULL,
  `used_on` bigint(20) DEFAULT NULL,
  `amount` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `referal_settings`
--

CREATE TABLE `referal_settings` (
  `id` int(11) NOT NULL,
  `is_active` int(11) NOT NULL,
  `referal_amount` int(11) NOT NULL,
  `validity` bigint(20) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `referal_settings`
--

INSERT INTO `referal_settings` (`id`, `is_active`, `referal_amount`, `validity`) VALUES
(1, 0, 10, 7776000);

-- --------------------------------------------------------

--
-- Table structure for table `search_history`
--

CREATE TABLE `search_history` (
  `id` bigint(20) NOT NULL,
  `user_id` bigint(20) DEFAULT NULL,
  `search_key` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `timestamp` bigint(20) NOT NULL,
  `search_result_no` bigint(20) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `unit_type`
--

CREATE TABLE `unit_type` (
  `id` int(11) NOT NULL,
  `name` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `is_deleted` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `unit_type`
--

INSERT INTO `unit_type` (`id`, `name`, `is_deleted`) VALUES
(1, 'Kg', 0),
(2, 'L', 0),
(3, 'gm', 0),
(4, 'ml', 0),
(5, 'Pc', 0);

-- --------------------------------------------------------

--
-- Table structure for table `uploads`
--

CREATE TABLE `uploads` (
  `id` int(11) NOT NULL,
  `file_name` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `url` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `uploaded_by` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `is_deleted` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `email` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `email_verified_at` timestamp NULL DEFAULT NULL,
  `password` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `remember_token` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `user_notification`
--

CREATE TABLE `user_notification` (
  `id` int(11) NOT NULL,
  `user_id` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `details` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `action` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `action_keyword` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `timestamp` bigint(20) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `wish_list`
--

CREATE TABLE `wish_list` (
  `id` int(11) NOT NULL,
  `customer_id` int(11) DEFAULT NULL,
  `is_active` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `wish_list_products`
--

CREATE TABLE `wish_list_products` (
  `id` int(11) NOT NULL,
  `wish_list_id` int(11) DEFAULT NULL,
  `product_id` int(11) DEFAULT NULL,
  `is_active` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `admin`
--
ALTER TABLE `admin`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `admin_notification`
--
ALTER TABLE `admin_notification`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `admin_type`
--
ALTER TABLE `admin_type`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `android_view_flipper`
--
ALTER TABLE `android_view_flipper`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `brands`
--
ALTER TABLE `brands`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `cancellation_reason`
--
ALTER TABLE `cancellation_reason`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `cart`
--
ALTER TABLE `cart`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `cart_products`
--
ALTER TABLE `cart_products`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `clicked_product_details`
--
ALTER TABLE `clicked_product_details`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `customers`
--
ALTER TABLE `customers`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `customer_address`
--
ALTER TABLE `customer_address`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `customer_feedback`
--
ALTER TABLE `customer_feedback`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `customer_search_history`
--
ALTER TABLE `customer_search_history`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `delivery_charge`
--
ALTER TABLE `delivery_charge`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `delivery_partner`
--
ALTER TABLE `delivery_partner`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `delivery_partner_notification`
--
ALTER TABLE `delivery_partner_notification`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `discount_coupon`
--
ALTER TABLE `discount_coupon`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `failed_jobs`
--
ALTER TABLE `failed_jobs`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `failed_jobs_uuid_unique` (`uuid`);

--
-- Indexes for table `fcm_token`
--
ALTER TABLE `fcm_token`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `migrations`
--
ALTER TABLE `migrations`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `minimum_order`
--
ALTER TABLE `minimum_order`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `orders`
--
ALTER TABLE `orders`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `orders_details`
--
ALTER TABLE `orders_details`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `order_address`
--
ALTER TABLE `order_address`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `order_status_details`
--
ALTER TABLE `order_status_details`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `password_resets`
--
ALTER TABLE `password_resets`
  ADD KEY `password_resets_email_index` (`email`);

--
-- Indexes for table `pin_code`
--
ALTER TABLE `pin_code`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `pin_wise_village`
--
ALTER TABLE `pin_wise_village`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `product`
--
ALTER TABLE `product`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `product_category`
--
ALTER TABLE `product_category`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `product_images`
--
ALTER TABLE `product_images`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `product_sub_category`
--
ALTER TABLE `product_sub_category`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `product_tag`
--
ALTER TABLE `product_tag`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `referal_details`
--
ALTER TABLE `referal_details`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `referal_settings`
--
ALTER TABLE `referal_settings`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `search_history`
--
ALTER TABLE `search_history`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `unit_type`
--
ALTER TABLE `unit_type`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `uploads`
--
ALTER TABLE `uploads`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `users_email_unique` (`email`);

--
-- Indexes for table `user_notification`
--
ALTER TABLE `user_notification`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `wish_list`
--
ALTER TABLE `wish_list`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `wish_list_products`
--
ALTER TABLE `wish_list_products`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `admin`
--
ALTER TABLE `admin`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `admin_notification`
--
ALTER TABLE `admin_notification`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `admin_type`
--
ALTER TABLE `admin_type`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `android_view_flipper`
--
ALTER TABLE `android_view_flipper`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `brands`
--
ALTER TABLE `brands`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `cancellation_reason`
--
ALTER TABLE `cancellation_reason`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `cart`
--
ALTER TABLE `cart`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `cart_products`
--
ALTER TABLE `cart_products`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `clicked_product_details`
--
ALTER TABLE `clicked_product_details`
  MODIFY `id` bigint(20) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `customers`
--
ALTER TABLE `customers`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `customer_address`
--
ALTER TABLE `customer_address`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `customer_feedback`
--
ALTER TABLE `customer_feedback`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `customer_search_history`
--
ALTER TABLE `customer_search_history`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `delivery_charge`
--
ALTER TABLE `delivery_charge`
  MODIFY `id` bigint(20) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `delivery_partner`
--
ALTER TABLE `delivery_partner`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT for table `delivery_partner_notification`
--
ALTER TABLE `delivery_partner_notification`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `discount_coupon`
--
ALTER TABLE `discount_coupon`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `failed_jobs`
--
ALTER TABLE `failed_jobs`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `fcm_token`
--
ALTER TABLE `fcm_token`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `migrations`
--
ALTER TABLE `migrations`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `minimum_order`
--
ALTER TABLE `minimum_order`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `orders`
--
ALTER TABLE `orders`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `orders_details`
--
ALTER TABLE `orders_details`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `order_address`
--
ALTER TABLE `order_address`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `order_status_details`
--
ALTER TABLE `order_status_details`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `pin_code`
--
ALTER TABLE `pin_code`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `pin_wise_village`
--
ALTER TABLE `pin_wise_village`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `product`
--
ALTER TABLE `product`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `product_category`
--
ALTER TABLE `product_category`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `product_images`
--
ALTER TABLE `product_images`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `product_sub_category`
--
ALTER TABLE `product_sub_category`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `product_tag`
--
ALTER TABLE `product_tag`
  MODIFY `id` bigint(20) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `referal_details`
--
ALTER TABLE `referal_details`
  MODIFY `id` bigint(20) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `referal_settings`
--
ALTER TABLE `referal_settings`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `search_history`
--
ALTER TABLE `search_history`
  MODIFY `id` bigint(20) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `unit_type`
--
ALTER TABLE `unit_type`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `uploads`
--
ALTER TABLE `uploads`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `user_notification`
--
ALTER TABLE `user_notification`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `wish_list`
--
ALTER TABLE `wish_list`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `wish_list_products`
--
ALTER TABLE `wish_list_products`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
