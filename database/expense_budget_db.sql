-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Apr 19, 2025 at 08:47 PM
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
-- Database: `expense_budget_db`
--

-- --------------------------------------------------------

--
-- Table structure for table `categories`
--

CREATE TABLE `categories` (
  `id` int(30) NOT NULL,
  `category` varchar(250) NOT NULL,
  `description` text DEFAULT NULL,
  `status` tinyint(1) NOT NULL DEFAULT 1,
  `balance` float NOT NULL DEFAULT 0,
  `date_created` datetime NOT NULL DEFAULT current_timestamp(),
  `date_updated` datetime DEFAULT NULL ON UPDATE current_timestamp(),
  `user_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `categories`
--

INSERT INTO `categories` (`id`, `category`, `description`, `status`, `balance`, `date_created`, `date_updated`, `user_id`) VALUES
(1, 'Food', '&lt;p data-start=&quot;0&quot; data-end=&quot;216&quot; class=&quot;&quot;&gt;The &quot;Food&quot; budget category includes expenses for groceries, dining out, takeout, snacks, and beverages. It&rsquo;s important to track both home meals and meals outside to manage spending. This category can be divided into:&lt;/p&gt;&lt;p&gt;\r\n&lt;/p&gt;&lt;ul data-start=&quot;218&quot; data-end=&quot;379&quot;&gt;\r\n&lt;li data-start=&quot;218&quot; data-end=&quot;254&quot; class=&quot;&quot;&gt;\r\n&lt;p data-start=&quot;220&quot; data-end=&quot;254&quot; class=&quot;&quot;&gt;&lt;strong data-start=&quot;220&quot; data-end=&quot;234&quot;&gt;Groceries:&lt;/strong&gt; Regular food items.&lt;/p&gt;\r\n&lt;/li&gt;\r\n&lt;li data-start=&quot;255&quot; data-end=&quot;294&quot; class=&quot;&quot;&gt;\r\n&lt;p data-start=&quot;257&quot; data-end=&quot;294&quot; class=&quot;&quot;&gt;&lt;strong data-start=&quot;257&quot; data-end=&quot;272&quot;&gt;Dining Out:&lt;/strong&gt; Meals at restaurants.&lt;/p&gt;\r\n&lt;/li&gt;\r\n&lt;li data-start=&quot;295&quot; data-end=&quot;334&quot; class=&quot;&quot;&gt;\r\n&lt;p data-start=&quot;297&quot; data-end=&quot;334&quot; class=&quot;&quot;&gt;&lt;strong data-start=&quot;297&quot; data-end=&quot;318&quot;&gt;Takeout/Delivery:&lt;/strong&gt; Delivered food.&lt;/p&gt;\r\n&lt;/li&gt;\r\n&lt;li data-start=&quot;335&quot; data-end=&quot;379&quot; class=&quot;&quot;&gt;\r\n&lt;p data-start=&quot;337&quot; data-end=&quot;379&quot; class=&quot;&quot;&gt;&lt;strong data-start=&quot;337&quot; data-end=&quot;360&quot;&gt;Snacks &amp;amp; Beverages:&lt;/strong&gt; Drinks and snacks.&lt;/p&gt;&lt;/li&gt;&lt;/ul&gt;', 1, 27470, '2021-07-30 09:21:36', '2025-04-17 09:20:54', 1),
(3, 'Transport', '&lt;p data-start=&quot;0&quot; data-end=&quot;240&quot; class=&quot;&quot;&gt;The &quot;Transport&quot; budget category covers all expenses related to getting from one place to another. This includes costs for owning and operating a vehicle, public transportation, and other travel-related expenses. Subcategories might include:&lt;/p&gt;&lt;p&gt;\r\n&lt;/p&gt;&lt;ul data-start=&quot;242&quot; data-end=&quot;480&quot;&gt;\r\n&lt;li data-start=&quot;242&quot; data-end=&quot;305&quot; class=&quot;&quot;&gt;\r\n&lt;p data-start=&quot;244&quot; data-end=&quot;305&quot; class=&quot;&quot;&gt;&lt;strong data-start=&quot;244&quot; data-end=&quot;262&quot;&gt;Vehicle Costs:&lt;/strong&gt; Fuel, insurance, maintenance, and repairs.&lt;/p&gt;\r\n&lt;/li&gt;\r\n&lt;li data-start=&quot;306&quot; data-end=&quot;358&quot; class=&quot;&quot;&gt;\r\n&lt;p data-start=&quot;308&quot; data-end=&quot;358&quot; class=&quot;&quot;&gt;&lt;strong data-start=&quot;308&quot; data-end=&quot;329&quot;&gt;Public Transport:&lt;/strong&gt; Bus, subway, or train fares.&lt;/p&gt;\r\n&lt;/li&gt;\r\n&lt;li data-start=&quot;359&quot; data-end=&quot;411&quot; class=&quot;&quot;&gt;\r\n&lt;p data-start=&quot;361&quot; data-end=&quot;411&quot; class=&quot;&quot;&gt;&lt;strong data-start=&quot;361&quot; data-end=&quot;379&quot;&gt;Parking/Tolls:&lt;/strong&gt; Fees for parking or road tolls.&lt;/p&gt;\r\n&lt;/li&gt;\r\n&lt;li data-start=&quot;412&quot; data-end=&quot;480&quot; class=&quot;&quot;&gt;\r\n&lt;p data-start=&quot;414&quot; data-end=&quot;480&quot; class=&quot;&quot;&gt;&lt;strong data-start=&quot;414&quot; data-end=&quot;431&quot;&gt;Other Travel:&lt;/strong&gt; Expenses for taxis, ridesharing, or car rentals.&lt;/p&gt;&lt;/li&gt;&lt;/ul&gt;', 1, 0, '2021-07-30 09:22:22', '2025-04-16 23:46:48', 1),
(4, 'Entertainment', '&lt;p data-start=&quot;0&quot; data-end=&quot;201&quot; class=&quot;&quot;&gt;The &quot;Entertainment&quot; budget category includes expenses related to leisure and recreational activities. This can cover a variety of things that provide enjoyment or relaxation. Subcategories may include:&lt;/p&gt;&lt;p&gt;\r\n&lt;/p&gt;&lt;ul data-start=&quot;203&quot; data-end=&quot;458&quot;&gt;\r\n&lt;li data-start=&quot;203&quot; data-end=&quot;267&quot; class=&quot;&quot;&gt;\r\n&lt;p data-start=&quot;205&quot; data-end=&quot;267&quot; class=&quot;&quot;&gt;&lt;strong data-start=&quot;205&quot; data-end=&quot;225&quot;&gt;Movies/Concerts:&lt;/strong&gt; Tickets for films, shows, or live events.&lt;/p&gt;\r\n&lt;/li&gt;\r\n&lt;li data-start=&quot;268&quot; data-end=&quot;335&quot; class=&quot;&quot;&gt;\r\n&lt;p data-start=&quot;270&quot; data-end=&quot;335&quot; class=&quot;&quot;&gt;&lt;strong data-start=&quot;270&quot; data-end=&quot;282&quot;&gt;Hobbies:&lt;/strong&gt; Costs for activities like sports, gaming, or crafts.&lt;/p&gt;\r\n&lt;/li&gt;\r\n&lt;li data-start=&quot;336&quot; data-end=&quot;407&quot; class=&quot;&quot;&gt;\r\n&lt;p data-start=&quot;338&quot; data-end=&quot;407&quot; class=&quot;&quot;&gt;&lt;strong data-start=&quot;338&quot; data-end=&quot;356&quot;&gt;Subscriptions:&lt;/strong&gt; Streaming services, magazines, or membership fees.&lt;/p&gt;\r\n&lt;/li&gt;\r\n&lt;li data-start=&quot;408&quot; data-end=&quot;458&quot; class=&quot;&quot;&gt;\r\n&lt;p data-start=&quot;410&quot; data-end=&quot;458&quot; class=&quot;&quot;&gt;&lt;strong data-start=&quot;410&quot; data-end=&quot;425&quot;&gt;Dining Out:&lt;/strong&gt; Social meals or outings for fun.&lt;/p&gt;&lt;/li&gt;&lt;/ul&gt;', 1, 32870, '2021-07-30 09:23:22', '2025-04-17 09:19:34', 1),
(5, 'Others', '&lt;p data-start=&quot;0&quot; data-end=&quot;179&quot; class=&quot;&quot;&gt;The &quot;Others&quot; budget category covers miscellaneous expenses that don\'t fit into specific categories. These can vary widely depending on individual needs. Subcategories may include:&lt;/p&gt;&lt;p style=&quot;margin-right: 0px; margin-bottom: 15px; margin-left: 0px; padding: 0px; text-align: justify; color: rgb(0, 0, 0); font-family: &amp;quot;Open Sans&amp;quot;, Arial, sans-serif; font-size: 14px;&quot;&gt;\r\n&lt;/p&gt;&lt;ul data-start=&quot;181&quot; data-end=&quot;441&quot;&gt;\r\n&lt;li data-start=&quot;181&quot; data-end=&quot;249&quot; class=&quot;&quot;&gt;\r\n&lt;p data-start=&quot;183&quot; data-end=&quot;249&quot; class=&quot;&quot;&gt;&lt;strong data-start=&quot;183&quot; data-end=&quot;193&quot;&gt;Gifts:&lt;/strong&gt; Presents for birthdays, holidays, or special occasions.&lt;/p&gt;\r\n&lt;/li&gt;\r\n&lt;li data-start=&quot;250&quot; data-end=&quot;316&quot; class=&quot;&quot;&gt;\r\n&lt;p data-start=&quot;252&quot; data-end=&quot;316&quot; class=&quot;&quot;&gt;&lt;strong data-start=&quot;252&quot; data-end=&quot;270&quot;&gt;Personal Care:&lt;/strong&gt; Items like toiletries, skincare, or haircuts.&lt;/p&gt;\r\n&lt;/li&gt;\r\n&lt;li data-start=&quot;317&quot; data-end=&quot;396&quot; class=&quot;&quot;&gt;\r\n&lt;p data-start=&quot;319&quot; data-end=&quot;396&quot; class=&quot;&quot;&gt;&lt;strong data-start=&quot;319&quot; data-end=&quot;331&quot;&gt;Medical:&lt;/strong&gt; Out-of-pocket health expenses, like prescriptions or treatments.&lt;/p&gt;\r\n&lt;/li&gt;\r\n&lt;li data-start=&quot;397&quot; data-end=&quot;441&quot; class=&quot;&quot;&gt;\r\n&lt;p data-start=&quot;399&quot; data-end=&quot;441&quot; class=&quot;&quot;&gt;&lt;strong data-start=&quot;399&quot; data-end=&quot;413&quot;&gt;Education:&lt;/strong&gt; Tuition, books, or courses.&lt;/p&gt;&lt;/li&gt;&lt;/ul&gt;', 0, 1500, '2021-07-30 09:23:53', '2025-04-16 23:46:57', 1),
(7, 'Utility Bills', '&lt;p data-start=&quot;0&quot; data-end=&quot;189&quot; class=&quot;&quot;&gt;The &quot;Utility Bills&quot; budget category includes expenses for essential services that keep your home functioning. These bills are typically recurring monthly costs. Subcategories might include:&lt;/p&gt;&lt;p&gt;\r\n&lt;/p&gt;&lt;ul data-start=&quot;191&quot; data-end=&quot;452&quot;&gt;\r\n&lt;li data-start=&quot;191&quot; data-end=&quot;240&quot; class=&quot;&quot;&gt;\r\n&lt;p data-start=&quot;193&quot; data-end=&quot;240&quot; class=&quot;&quot;&gt;&lt;strong data-start=&quot;193&quot; data-end=&quot;209&quot;&gt;Electricity:&lt;/strong&gt; Charges for power consumption.&lt;/p&gt;\r\n&lt;/li&gt;\r\n&lt;li data-start=&quot;241&quot; data-end=&quot;304&quot; class=&quot;&quot;&gt;\r\n&lt;p data-start=&quot;243&quot; data-end=&quot;304&quot; class=&quot;&quot;&gt;&lt;strong data-start=&quot;243&quot; data-end=&quot;262&quot;&gt;Water &amp;amp; Sewage:&lt;/strong&gt; Costs for water usage and waste disposal.&lt;/p&gt;\r\n&lt;/li&gt;\r\n&lt;li data-start=&quot;305&quot; data-end=&quot;340&quot; class=&quot;&quot;&gt;\r\n&lt;p data-start=&quot;307&quot; data-end=&quot;340&quot; class=&quot;&quot;&gt;&lt;strong data-start=&quot;307&quot; data-end=&quot;315&quot;&gt;Gas:&lt;/strong&gt; Heating or cooking fuel.&lt;/p&gt;\r\n&lt;/li&gt;\r\n&lt;li data-start=&quot;341&quot; data-end=&quot;403&quot; class=&quot;&quot;&gt;\r\n&lt;p data-start=&quot;343&quot; data-end=&quot;403&quot; class=&quot;&quot;&gt;&lt;strong data-start=&quot;343&quot; data-end=&quot;362&quot;&gt;Internet/Phone:&lt;/strong&gt; Monthly fees for communication services.&lt;/p&gt;\r\n&lt;/li&gt;\r\n&lt;li data-start=&quot;404&quot; data-end=&quot;452&quot; class=&quot;&quot;&gt;\r\n&lt;p data-start=&quot;406&quot; data-end=&quot;452&quot; class=&quot;&quot;&gt;&lt;strong data-start=&quot;406&quot; data-end=&quot;427&quot;&gt;Trash Collection:&lt;/strong&gt; Fees for waste disposal.&lt;/p&gt;&lt;/li&gt;&lt;/ul&gt;', 1, 0, '2025-04-07 16:31:41', '2025-04-16 23:47:02', 1),
(10, 'Food1', '&lt;p&gt;Food&lt;/p&gt;', 1, 45400, '2025-04-17 10:32:47', '2025-04-17 10:54:32', 7),
(11, 'transport', '&lt;p&gt;eree&lt;/p&gt;', 1, 0, '2025-04-17 10:43:41', NULL, 7),
(12, 'Food', '', 1, 0, '2025-04-17 10:44:08', NULL, 7),
(13, 'Food', '&lt;p&gt;Food&lt;/p&gt;', 1, 29705, '2025-04-17 11:14:33', '2025-04-17 11:30:53', 10),
(14, 'FOOD', '&lt;p&gt;FOOD&lt;/p&gt;', 1, 5430, '2025-04-18 22:14:07', '2025-04-19 21:28:41', 15),
(15, 'ENTERTAINMENT', '&lt;p&gt;ENTE&lt;/p&gt;', 1, 5150, '2025-04-18 22:14:42', '2025-04-19 21:34:53', 15),
(16, 'SCHOOL FEES', '&lt;p&gt;SCHOOL&lt;/p&gt;', 0, 0, '2025-04-19 21:24:12', '2025-04-19 21:24:23', 15);

-- --------------------------------------------------------

--
-- Table structure for table `goals`
--

CREATE TABLE `goals` (
  `id` int(11) NOT NULL,
  `goal_name` varchar(255) NOT NULL,
  `goal_description` text DEFAULT NULL,
  `target_amount` decimal(15,2) NOT NULL,
  `expected_completion_date` date DEFAULT NULL,
  `starting_capital` decimal(15,2) DEFAULT 0.00,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `user_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `goals`
--

INSERT INTO `goals` (`id`, `goal_name`, `goal_description`, `target_amount`, `expected_completion_date`, `starting_capital`, `created_at`, `user_id`) VALUES
(1, 'Build Emergency Fund', 'Set aside an emergency fund for unexpected expenses.', 5000.00, '2025-12-31', 150.00, '2025-04-15 23:10:23', 1),
(3, 'Vacation Fund', 'Save for a vacation to Europe for a month.', 10000.00, '2026-08-15', 2000.00, '2025-04-15 23:10:23', 1),
(15, 'car', '45', 24354.00, '2025-04-11', 454.00, '2025-04-17 01:12:32', 1),
(16, 'Rental building', 'for rentals', 40000.00, '2025-04-30', 15000.00, '2025-04-17 12:32:13', 7),
(17, 'Rental building', '45', 56000.00, '2025-05-10', 3000.00, '2025-04-17 13:39:14', 10),
(18, 'Rental building', 'Rental building', 50000.00, '2025-04-30', 3400.00, '2025-04-19 00:22:08', 15),
(19, 'TV PURCHASE', 'TV', 60000.00, '2025-07-31', 15000.00, '2025-04-19 23:37:43', 15);

-- --------------------------------------------------------

--
-- Table structure for table `goal_contributions`
--

CREATE TABLE `goal_contributions` (
  `id` int(11) NOT NULL,
  `goal_id` int(11) NOT NULL,
  `transaction_code` varchar(100) NOT NULL,
  `amount` decimal(12,2) NOT NULL,
  `date_added` datetime DEFAULT current_timestamp(),
  `user_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `goal_contributions`
--

INSERT INTO `goal_contributions` (`id`, `goal_id`, `transaction_code`, `amount`, `date_added`, `user_id`) VALUES
(1, 1, 'ererrrtrytu', 2000.00, '2025-04-16 10:55:56', 1),
(2, 3, 'ererrrtrytu', 566.00, '2025-04-16 11:02:33', 1),
(3, 3, 'ererrrtrytu', 56.00, '2025-04-16 11:04:01', 1),
(4, 1, 'ererrrtrytu', 1500.00, '2025-04-16 11:08:57', 1),
(5, 1, 'ererrrtrytu', 4.00, '2025-04-16 12:39:09', 1),
(6, 16, 'ererrrtrytu', 3000.00, '2025-04-17 10:37:05', 1),
(7, 17, 'ererrrtrytu', 567.00, '2025-04-17 12:53:01', 0),
(8, 17, 'ererrrtrytu', 600.00, '2025-04-17 12:57:01', 0),
(9, 18, 'TWERERE', 5600.00, '2025-04-18 22:22:54', 0),
(10, 19, 'TWEREREGRF', 7000.00, '2025-04-19 21:38:50', 0);

-- --------------------------------------------------------

--
-- Table structure for table `mpesa_balance`
--

CREATE TABLE `mpesa_balance` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `balance` decimal(12,2) NOT NULL DEFAULT 0.00,
  `last_updated` datetime DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `phone` varchar(20) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `mpesa_balance`
--

INSERT INTO `mpesa_balance` (`id`, `user_id`, `balance`, `last_updated`, `phone`) VALUES
(1, 1, 7387.00, '2025-04-17 16:33:29', '078654654556'),
(2, 7, 50030.00, '2025-04-17 10:34:12', NULL),
(4, 10, 22705.00, '2025-04-17 11:30:53', NULL),
(5, 12, 0.00, '2025-04-17 13:55:58', '1234567890'),
(6, 13, 0.00, '2025-04-17 13:58:54', '987654321'),
(7, 14, 0.00, '2025-04-17 14:22:48', '0756555170'),
(8, 15, 6341.00, '2025-04-19 21:34:53', '9876543211');

-- --------------------------------------------------------

--
-- Table structure for table `mpesa_topups`
--

CREATE TABLE `mpesa_topups` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `amount` double NOT NULL,
  `remarks` text DEFAULT NULL,
  `date_created` datetime DEFAULT current_timestamp(),
  `type` enum('credit','debit') DEFAULT 'credit',
  `transaction` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `mpesa_topups`
--

INSERT INTO `mpesa_topups` (`id`, `user_id`, `amount`, `remarks`, `date_created`, `type`, `transaction`) VALUES
(5, 1, 56, '4', '2025-04-17 08:09:26', 'credit', 'TXN20250417130926'),
(6, 1, 6700, 'for renttt', '2025-04-17 08:10:43', 'debit', 'TXN20250417131043'),
(7, 1, 6000, '56', '2025-04-17 08:16:08', 'credit', 'TXN20250417131608'),
(8, 1, 76, '56', '2025-04-17 08:18:17', 'credit', 'TXN20250417131817'),
(10, 1, 56, 'goods', '2025-04-17 08:22:14', 'credit', 'TXN20250417132214'),
(11, 1, 1000, 'red', '2025-04-17 08:26:23', 'credit', 'TXN20250417132623'),
(12, 1, 40000, '54', '2025-04-17 08:29:18', 'credit', 'TXN20250417132918'),
(13, 1, 4000, '54', '2025-04-17 08:29:19', 'credit', 'TXN20250417132919'),
(17, 1, 486, '54', '2025-04-17 08:42:14', 'credit', 'TXN20250417134214'),
(19, 1, 4000, 'rent', '2025-04-17 08:49:16', 'debit', 'TXN20250417134916'),
(20, 1, 4000, 'rent', '2025-04-17 08:51:04', 'debit', 'TXN20250417135104'),
(21, 7, 30, '54', '2025-04-17 10:09:30', 'credit', 'TXN20250417150930'),
(22, 7, 50000, 'credit', '2025-04-17 10:34:12', 'credit', 'TXN20250417153412'),
(23, 10, 43000, 'deposit', '2025-04-17 11:18:06', 'credit', 'TXN20250417161806'),
(24, 10, 670, 'Expense for category ID: 13', '2025-04-17 11:25:57', 'debit', 'EXP6800bb1569ac1'),
(25, 10, 560, 'Expense for category ID: 13', '2025-04-17 11:28:46', 'debit', 'EXP6800bbbeadb85'),
(26, 10, 5000, 'Expense for category Name: ', '2025-04-17 11:30:01', 'debit', 'EXP6800bc09c1362'),
(27, 10, 5000, 'Expense for category Name: ', '2025-04-17 11:30:06', 'debit', 'EXP6800bc0e47a23'),
(28, 10, 5000, 'Expense for category ID: 13', '2025-04-17 11:30:53', 'debit', 'EXP6800bc3d4eff4'),
(29, 15, 5000, 'INCOME', '2025-04-18 22:13:05', 'credit', 'TXN20250419031305'),
(30, 15, 560, 'Expense for category ID: 15', '2025-04-19 11:49:14', 'debit', 'EXP6803638ae9b1d'),
(34, 15, 1200, 'Expense for category ID: 15', '2025-04-19 13:29:34', 'debit', 'EXP68037b0e12e7f'),
(35, 15, 1100, 'Expense for category ID: 15', '2025-04-19 13:32:24', 'debit', 'EXP68037bb8316a3'),
(36, 15, 100, 'Expense for category ID: 15', '2025-04-19 13:33:52', 'debit', 'EXP68037c10c89af'),
(37, 15, 100, 'Expense for category ID: 15', '2025-04-19 13:34:59', 'debit', 'EXP68037c53ea7be'),
(38, 15, 100, 'Expense for category ID: 15', '2025-04-19 13:35:02', 'debit', 'EXP68037c5668f39'),
(39, 15, 100, 'Expense for category ID: 15', '2025-04-19 13:35:08', 'debit', 'EXP68037c5c61005'),
(40, 15, 100, 'Expense for category ID: 15', '2025-04-19 13:35:28', 'debit', 'EXP68037c708c420'),
(41, 15, 100, 'Expense for category ID: 15', '2025-04-19 13:36:11', 'debit', 'EXP68037c9b39123'),
(42, 15, -90, 'Expense adjustment for category ID: 15', '2025-04-19 13:40:29', 'debit', 'EXP68037d9d90ce1'),
(43, 15, 56, 'Expense adjustment for category ID: 15', '2025-04-19 13:40:53', 'debit', 'EXP68037db532c66'),
(44, 15, -51, 'Expense adjustment for category ID: 15', '2025-04-19 13:41:09', 'debit', 'EXP68037dc5d1e9a'),
(45, 15, 56, 'ggggh', '2025-04-19 13:41:47', 'credit', 'TXN20250419184147'),
(46, 15, 6789, '6', '2025-04-19 13:51:35', 'credit', 'TXN20250419185134'),
(47, 15, 789, 'Expense adjustment for category ID: 14', '2025-04-19 13:51:53', 'debit', 'EXP68038049d8a80'),
(48, 15, 50, 'Expense adjustment for category ID: 15', '2025-04-19 14:04:03', 'debit', 'EXP6803832300208'),
(49, 15, 450, 'Expense adjustment for category ID: 15', '2025-04-19 14:44:08', 'debit', 'EXP68038c88be31f'),
(50, 15, 50, 'Expense adjustment for category ID: 15', '2025-04-19 14:44:48', 'debit', 'EXP68038cb05c8e5'),
(51, 15, 500, 'Expense adjustment for category ID: 14', '2025-04-19 21:27:51', 'debit', 'EXP6803eb2739c58'),
(52, 15, 70, 'Expense adjustment for category ID: 14', '2025-04-19 21:28:41', 'debit', 'EXP6803eb59195fc'),
(53, 15, 7000, 'TOPUP', '2025-04-19 21:33:42', 'credit', 'TXN20250420023342'),
(54, 15, 6000, 'Expense adjustment for category ID: 15', '2025-04-19 21:34:53', 'debit', 'EXP6803eccdeb9b6');

-- --------------------------------------------------------

--
-- Table structure for table `reminders`
--

CREATE TABLE `reminders` (
  `id` int(11) NOT NULL,
  `title` varchar(255) NOT NULL,
  `description` text DEFAULT NULL,
  `reminder_date` date NOT NULL,
  `status` enum('Pending','Completed') DEFAULT 'Pending',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `user_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `reminders`
--

INSERT INTO `reminders` (`id`, `title`, `description`, `reminder_date`, `status`, `created_at`, `user_id`) VALUES
(1, 'Car goal deadline', 'Payment of 2000', '2025-04-19', 'Pending', '2025-04-15 22:56:56', 1),
(2, 'errrrrrr', 'rere', '2025-04-16', 'Completed', '2025-04-16 20:24:59', 1),
(3, 'Loan repayment', 'rtt', '2025-04-18', 'Pending', '2025-04-17 10:11:01', 7),
(4, 'LOAN REPAYMENT', 'LOAN', '2025-04-22', 'Pending', '2025-04-18 19:24:09', 15);

-- --------------------------------------------------------

--
-- Table structure for table `running_balance`
--

CREATE TABLE `running_balance` (
  `id` int(30) NOT NULL,
  `balance_type` tinyint(1) NOT NULL COMMENT '1=budget, 2=expense',
  `category_id` int(30) NOT NULL,
  `amount` float NOT NULL,
  `remarks` text NOT NULL,
  `user_id` text NOT NULL,
  `date_created` datetime NOT NULL DEFAULT current_timestamp(),
  `date_updated` datetime DEFAULT NULL ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `running_balance`
--

INSERT INTO `running_balance` (`id`, `balance_type`, `category_id`, `amount`, `remarks`, `user_id`, `date_created`, `date_updated`) VALUES
(6, 1, 5, 1500, '&lt;p&gt;JAN&lt;/p&gt;', '1', '2021-07-30 11:34:44', '2025-04-07 16:34:10'),
(15, 1, 3, 0, '&lt;p&gt;JAN&lt;/p&gt;', '1', '2021-07-30 14:47:13', '2025-04-13 22:36:04'),
(16, 1, 4, 43000, '&lt;p&gt;JAN&lt;/p&gt;', '1', '2021-07-30 14:47:28', '2025-04-11 21:30:07'),
(25, 2, 4, 4400, '&lt;p&gt;rere&lt;/p&gt;', '1', '2025-04-11 21:30:53', NULL),
(26, 2, 4, 5700, '&lt;p&gt;drinking&lt;/p&gt;', '1', '2025-04-11 21:31:25', '2025-04-17 00:13:26'),
(27, 1, 1, 46700, '&lt;p&gt;jan&lt;/p&gt;', '1', '2025-04-13 21:56:16', '2025-04-17 10:31:00'),
(28, 2, 1, 4700, '&lt;p&gt;kids lunches&lt;/p&gt;', '1', '2025-04-13 21:57:08', '2025-04-17 10:30:56'),
(29, 2, 1, 5000, '&lt;p&gt;erg&lt;/p&gt;', '1', '2025-04-13 21:57:42', NULL),
(30, 1, 1, 5670, '&lt;p&gt;33545&lt;/p&gt;', '1', '2025-04-17 00:07:47', NULL),
(32, 2, 1, 50, '&lt;p&gt;56&lt;/p&gt;', '1', '2025-04-17 00:32:36', NULL),
(33, 2, 4, 30, '&lt;p&gt;4343&lt;/p&gt;', '1', '2025-04-17 09:19:34', NULL),
(34, 2, 5, 1500, '&lt;p&gt;4560&lt;/p&gt;', '1', '2025-04-17 09:20:03', NULL),
(35, 2, 1, 15000, '&lt;p&gt;565&lt;/p&gt;', '1', '2025-04-17 09:20:24', NULL),
(36, 2, 1, 150, '', '1', '2025-04-17 09:20:54', NULL),
(37, 1, 10, 50000, '', '7', '2025-04-17 10:33:16', NULL),
(38, 2, 10, 4600, '&lt;p&gt;food&lt;/p&gt;', '7', '2025-04-17 10:34:36', NULL),
(41, 1, 13, 50000, '&lt;p&gt;food&lt;/p&gt;', '10', '2025-04-17 11:14:47', NULL),
(42, 2, 13, 4000, '&lt;p&gt;tr&lt;/p&gt;', '10', '2025-04-17 11:18:19', NULL),
(43, 2, 13, 65, '&lt;p&gt;tg&lt;/p&gt;', '10', '2025-04-17 11:20:08', NULL),
(44, 2, 13, 670, '&lt;p&gt;hh&lt;/p&gt;', '10', '2025-04-17 11:25:57', NULL),
(45, 2, 13, 560, '&lt;p&gt;65&lt;/p&gt;', '10', '2025-04-17 11:28:46', NULL),
(46, 2, 13, 5000, '&lt;p&gt;56&lt;/p&gt;', '10', '2025-04-17 11:30:01', NULL),
(47, 2, 13, 5000, '&lt;p&gt;56&lt;/p&gt;', '10', '2025-04-17 11:30:06', NULL),
(48, 2, 13, 5000, '&lt;p&gt;56&lt;/p&gt;', '10', '2025-04-17 11:30:53', NULL),
(61, 1, 15, 500, 'club', '15', '2025-04-19 14:03:41', NULL),
(62, 2, 15, 500, '54', '15', '2025-04-19 14:04:02', '2025-04-19 14:44:08'),
(63, 1, 15, 500, '67', '15', '2025-04-19 14:09:08', '2025-04-19 14:12:24'),
(64, 1, 15, 500, '3', '15', '2025-04-19 14:13:10', '2025-04-19 14:23:00'),
(65, 1, 15, 10000, '5', '15', '2025-04-19 14:13:59', '2025-04-19 21:25:59'),
(66, 1, 15, 200, '45', '15', '2025-04-19 14:42:30', '2025-04-19 14:43:35'),
(67, 2, 15, 50, 'ty', '15', '2025-04-19 14:44:48', NULL),
(68, 1, 14, 6000, 'HNHY', '15', '2025-04-19 21:25:09', NULL),
(69, 2, 14, 500, 'CHILDRENS BREAKFAST', '15', '2025-04-19 21:27:51', NULL),
(70, 2, 14, 70, 'GHH', '15', '2025-04-19 21:28:41', NULL),
(71, 2, 15, 6000, 'TRTGTR', '15', '2025-04-19 21:34:53', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `system_info`
--

CREATE TABLE `system_info` (
  `id` int(30) NOT NULL,
  `meta_field` text NOT NULL,
  `meta_value` text NOT NULL,
  `user_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `system_info`
--

INSERT INTO `system_info` (`id`, `meta_field`, `meta_value`, `user_id`) VALUES
(1, 'name', 'Budget and Expense system', 0),
(6, 'short_name', 'B&E Tracker sys', 0),
(11, 'logo', 'uploads/1744570800_bedsitter.jpeg', 0);

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(50) NOT NULL,
  `firstname` varchar(250) NOT NULL,
  `lastname` varchar(250) NOT NULL,
  `username` text NOT NULL,
  `password` text NOT NULL,
  `avatar` text DEFAULT NULL,
  `last_login` datetime DEFAULT NULL,
  `type` tinyint(1) NOT NULL DEFAULT 0,
  `date_added` datetime NOT NULL DEFAULT current_timestamp(),
  `date_updated` datetime DEFAULT NULL ON UPDATE current_timestamp(),
  `phone` varchar(20) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `firstname`, `lastname`, `username`, `password`, `avatar`, `last_login`, `type`, `date_added`, `date_updated`, `phone`) VALUES
(1, 'Adminstrator', 'Admin', 'admin', '$2y$10$qfPt5CXrihcA9lV7DnTQm.qcofJYf2GhR5SOTp88iHMuAb/2oWdD2', 'uploads/1744033320_bedsitter.jpeg', NULL, 1, '2021-01-20 14:02:37', '2025-04-17 16:33:29', '078654654556'),
(4, 'John', 'Smith', 'jsmith', '1234', NULL, NULL, 0, '2021-06-19 08:36:09', '2025-04-07 16:48:33', NULL),
(5, 'Claire', 'Blake', 'cblake', '4744ddea876b11dcb1d169fadf494418', NULL, NULL, 0, '2021-06-19 10:01:51', '2021-06-19 12:03:23', NULL),
(7, 'riziki', 'riziki', 'riziki', '$2y$10$B5wJb301jB.bwmYBeIUKUOXa9wx6f9zbV0D9ovwEaBXQJ71hulfF6', 'uploads/1744886580_download (1).jpeg', NULL, 3, '2025-04-17 15:06:24', '2025-04-17 13:43:24', NULL),
(8, 'Edward', 'Kariuki', 'eduu', '$2y$10$D6oAVnks1i4LnD2pCytc0.2xh3zT79Zf91pxoh/ECHai8ZWdTf6/2', '', NULL, 3, '2025-04-17 15:13:22', '2025-04-17 15:13:22', NULL),
(10, 'kiki', 'kiki', 'kiki', '$2y$10$/sF47iW2fXOIUDhxo0S6c.jRns8XIGNkr8f2Fy/UXh1jCZ4MYk8ge', '', NULL, 3, '2025-04-17 16:07:34', '2025-04-17 16:07:34', NULL),
(12, 'kaka', 'kaka', 'kaka', '$2y$10$OpsdlEFsWMgZN3Oo/VGBNOEkh9Oa6klF4J2ozP7rjusynvq8VNsbu', '', NULL, 3, '2025-04-17 18:55:58', '2025-04-17 18:55:58', '1234567890'),
(13, 'kikii', 'kikii', 'kikii', '$2y$10$0SyX9Co.js4VmHf7Dkq8OOLZ2gOesLuKA1RUAL16Twvqwv75/EC4m', '', NULL, 3, '2025-04-17 18:58:54', '2025-04-17 18:58:54', '0987654321'),
(14, 'fg', 'fg', 'fg', '827ccb0eea8a706c4c34a16891f84e7b', '', NULL, 3, '2025-04-17 19:01:08', '2025-04-17 14:22:48', '0756555170'),
(15, 'KOKO', 'KOKO', 'KOKO', '$2y$10$cxtQGCUpeGvs/Yajr4bjfO3ZHNi/XFRXvxEMVOskRE6lQPxYJS.5S', 'uploads/1745003460_download.jpeg', NULL, 3, '2025-04-19 03:08:19', '2025-04-18 22:11:58', '9876543211');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `categories`
--
ALTER TABLE `categories`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `goals`
--
ALTER TABLE `goals`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `id` (`id`),
  ADD KEY `fk_user` (`user_id`);

--
-- Indexes for table `goal_contributions`
--
ALTER TABLE `goal_contributions`
  ADD PRIMARY KEY (`id`),
  ADD KEY `goal_id` (`goal_id`);

--
-- Indexes for table `mpesa_balance`
--
ALTER TABLE `mpesa_balance`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `mpesa_topups`
--
ALTER TABLE `mpesa_topups`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `reminders`
--
ALTER TABLE `reminders`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `running_balance`
--
ALTER TABLE `running_balance`
  ADD PRIMARY KEY (`id`),
  ADD KEY `category_id` (`category_id`);

--
-- Indexes for table `system_info`
--
ALTER TABLE `system_info`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `categories`
--
ALTER TABLE `categories`
  MODIFY `id` int(30) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=17;

--
-- AUTO_INCREMENT for table `goals`
--
ALTER TABLE `goals`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=20;

--
-- AUTO_INCREMENT for table `goal_contributions`
--
ALTER TABLE `goal_contributions`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT for table `mpesa_balance`
--
ALTER TABLE `mpesa_balance`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT for table `mpesa_topups`
--
ALTER TABLE `mpesa_topups`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=55;

--
-- AUTO_INCREMENT for table `reminders`
--
ALTER TABLE `reminders`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `running_balance`
--
ALTER TABLE `running_balance`
  MODIFY `id` int(30) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=72;

--
-- AUTO_INCREMENT for table `system_info`
--
ALTER TABLE `system_info`
  MODIFY `id` int(30) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=15;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(50) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=16;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `goals`
--
ALTER TABLE `goals`
  ADD CONSTRAINT `fk_user` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `goal_contributions`
--
ALTER TABLE `goal_contributions`
  ADD CONSTRAINT `goal_contributions_ibfk_1` FOREIGN KEY (`goal_id`) REFERENCES `goals` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `mpesa_balance`
--
ALTER TABLE `mpesa_balance`
  ADD CONSTRAINT `mpesa_balance_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `running_balance`
--
ALTER TABLE `running_balance`
  ADD CONSTRAINT `running_balance_ibfk_1` FOREIGN KEY (`category_id`) REFERENCES `categories` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
