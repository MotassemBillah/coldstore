-- phpMyAdmin SQL Dump
-- version 4.5.0.2
-- http://www.phpmyadmin.net
--
-- Host: localhost
-- Generation Time: Sep 25, 2017 at 03:54 PM
-- Server version: 10.0.17-MariaDB
-- PHP Version: 5.6.14

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `sabeyagroup_nncs`
--

-- --------------------------------------------------------

--
-- Table structure for table `accounts`
--

CREATE TABLE `accounts` (
  `id` int(10) UNSIGNED NOT NULL,
  `bank_id` int(10) UNSIGNED NOT NULL,
  `account_name` varchar(100) NOT NULL,
  `account_number` varchar(100) NOT NULL,
  `account_type` varchar(50) NOT NULL,
  `address` varchar(255) DEFAULT NULL,
  `created` datetime NOT NULL,
  `created_by` int(10) NOT NULL,
  `modified` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `modified_by` int(10) DEFAULT NULL,
  `_key` varchar(32) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='bank accounts information';

-- --------------------------------------------------------

--
-- Table structure for table `account_balance`
--

CREATE TABLE `account_balance` (
  `id` int(10) UNSIGNED NOT NULL,
  `account_id` int(10) UNSIGNED NOT NULL,
  `payment_id` int(10) UNSIGNED DEFAULT NULL,
  `category` varchar(50) DEFAULT NULL,
  `purpose` varchar(100) DEFAULT NULL,
  `by_whom` varchar(100) DEFAULT NULL,
  `amount` float DEFAULT NULL,
  `debit` float DEFAULT NULL,
  `credit` float DEFAULT NULL,
  `balance` float DEFAULT NULL,
  `last_update` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `agents`
--

CREATE TABLE `agents` (
  `id` int(10) UNSIGNED NOT NULL,
  `name` varchar(100) DEFAULT NULL,
  `father_name` varchar(100) DEFAULT NULL,
  `code` int(10) DEFAULT NULL,
  `mobile` varchar(15) DEFAULT NULL,
  `zila` varchar(50) DEFAULT NULL,
  `upozila` varchar(50) DEFAULT NULL,
  `post` varchar(50) DEFAULT NULL,
  `village` varchar(50) DEFAULT NULL,
  `address` text,
  `created` datetime DEFAULT NULL,
  `created_by` int(11) DEFAULT NULL,
  `modified` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `modified_by` int(11) DEFAULT NULL,
  `_key` varchar(32) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `balancesheet`
--

CREATE TABLE `balancesheet` (
  `id` int(10) UNSIGNED NOT NULL,
  `customer_payment_id` int(10) UNSIGNED DEFAULT NULL,
  `payment_id` int(10) UNSIGNED DEFAULT NULL,
  `expense_id` int(10) UNSIGNED DEFAULT NULL,
  `pay_date` date DEFAULT NULL,
  `debit` float DEFAULT NULL,
  `credit` float DEFAULT NULL,
  `balance` float DEFAULT NULL,
  `last_update` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `banks`
--

CREATE TABLE `banks` (
  `id` int(10) UNSIGNED NOT NULL,
  `name` varchar(100) DEFAULT NULL,
  `last_modified` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `is_deleted` tinyint(4) DEFAULT '0',
  `_key` varchar(32) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `cash_account`
--

CREATE TABLE `cash_account` (
  `id` int(10) UNSIGNED NOT NULL,
  `product_in_payment_id` int(11) DEFAULT NULL,
  `product_out_payment_id` int(11) DEFAULT NULL,
  `adv_loan_payment_id` int(11) DEFAULT NULL,
  `loan_payment_id` int(11) DEFAULT NULL,
  `loan_receive_id` int(11) DEFAULT NULL,
  `expense_id` int(11) DEFAULT NULL,
  `payment_load_unload_id` int(11) DEFAULT NULL,
  `bank_id` int(11) DEFAULT NULL,
  `account_id` int(10) UNSIGNED DEFAULT NULL,
  `check_no` varchar(30) DEFAULT NULL,
  `ledger_head_id` int(11) DEFAULT NULL,
  `purpose` varchar(100) DEFAULT NULL,
  `by_whom` varchar(100) DEFAULT NULL,
  `transaction_type` varchar(10) DEFAULT NULL,
  `pay_date` date DEFAULT NULL,
  `opening_balance` float DEFAULT NULL,
  `closing_balance` float DEFAULT NULL,
  `debit` double DEFAULT NULL,
  `credit` double DEFAULT NULL,
  `balance` double DEFAULT NULL,
  `note` text,
  `type` varchar(5) DEFAULT NULL,
  `created` date DEFAULT NULL,
  `created_by` int(11) DEFAULT NULL,
  `modified` datetime DEFAULT NULL,
  `modified_by` int(11) DEFAULT NULL,
  `is_editable` tinyint(2) NOT NULL DEFAULT '0',
  `_key` varchar(32) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `customers`
--

CREATE TABLE `customers` (
  `id` int(10) UNSIGNED NOT NULL,
  `type` varchar(20) DEFAULT NULL,
  `name` varchar(100) DEFAULT NULL,
  `father_name` varchar(100) DEFAULT NULL,
  `mobile` varchar(15) DEFAULT NULL,
  `district` varchar(50) DEFAULT NULL,
  `thana` varchar(50) DEFAULT NULL,
  `village` varchar(50) DEFAULT NULL,
  `has_loan` varchar(5) NOT NULL DEFAULT 'No',
  `create_date` datetime DEFAULT NULL,
  `created_by` int(10) UNSIGNED DEFAULT NULL,
  `last_update` datetime DEFAULT NULL,
  `update_by` int(10) UNSIGNED DEFAULT NULL,
  `_key` varchar(32) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `customer_balance`
--

CREATE TABLE `customer_balance` (
  `id` int(10) UNSIGNED NOT NULL,
  `customer_id` int(10) UNSIGNED NOT NULL,
  `payment_id` int(11) DEFAULT NULL,
  `invoice_no` varchar(32) DEFAULT NULL,
  `created` date DEFAULT NULL,
  `amount` float DEFAULT NULL,
  `debit` float DEFAULT NULL,
  `credit` float DEFAULT NULL,
  `balance` float DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `customer_payments`
--

CREATE TABLE `customer_payments` (
  `id` int(10) UNSIGNED NOT NULL,
  `customer_id` int(10) UNSIGNED NOT NULL,
  `customer_mobile` varchar(15) DEFAULT NULL,
  `pout_id` int(10) UNSIGNED DEFAULT NULL,
  `sr_no` varchar(10) DEFAULT NULL,
  `delivery_sr_no` varchar(15) DEFAULT NULL,
  `pay_date` date DEFAULT NULL,
  `payment_type` varchar(20) DEFAULT NULL,
  `bank_name` varchar(100) DEFAULT NULL,
  `check_no` varchar(32) DEFAULT NULL,
  `loan_bag` int(11) DEFAULT NULL,
  `loan_bag_cost` float DEFAULT NULL,
  `loan_bag_amount` float DEFAULT NULL,
  `delivered_qty` int(11) DEFAULT NULL,
  `delivered_cost` float DEFAULT NULL,
  `delivered_cost_amount` float DEFAULT NULL,
  `advance_paid` float DEFAULT NULL,
  `carrying_cost` float DEFAULT NULL,
  `labor_cost` float DEFAULT NULL,
  `other_cost` float DEFAULT NULL,
  `due_paid` float DEFAULT NULL,
  `net_amount` float DEFAULT NULL,
  `paid_amount` varchar(20) DEFAULT NULL,
  `due_amount` float DEFAULT NULL,
  `status` varchar(20) NOT NULL DEFAULT 'Pending',
  `created` datetime DEFAULT NULL,
  `created_by` int(11) DEFAULT NULL,
  `modified` datetime DEFAULT NULL,
  `modified_by` int(10) UNSIGNED DEFAULT NULL,
  `_key` varchar(32) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `damaged_products`
--

CREATE TABLE `damaged_products` (
  `id` int(10) UNSIGNED NOT NULL,
  `product_id` int(10) UNSIGNED NOT NULL,
  `company_id` int(10) UNSIGNED NOT NULL,
  `category_id` int(10) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `delivery`
--

CREATE TABLE `delivery` (
  `id` int(11) NOT NULL,
  `sr_no` int(11) DEFAULT NULL,
  `delivery_number` int(11) DEFAULT NULL,
  `person` varchar(100) DEFAULT NULL,
  `delivery_date` date DEFAULT NULL,
  `created` datetime DEFAULT NULL,
  `created_by` int(11) DEFAULT NULL,
  `modified` datetime DEFAULT NULL,
  `modified_by` int(11) DEFAULT NULL,
  `_key` varchar(16) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `delivery_items`
--

CREATE TABLE `delivery_items` (
  `id` int(10) UNSIGNED NOT NULL,
  `delivery_id` int(11) DEFAULT NULL,
  `delivery_number` int(11) DEFAULT NULL,
  `customer_id` int(10) UNSIGNED DEFAULT NULL,
  `sr_no` int(11) DEFAULT NULL,
  `type` int(11) DEFAULT NULL,
  `agent_code` int(11) DEFAULT NULL,
  `lot_no` varchar(20) DEFAULT NULL,
  `loan_bag` int(11) DEFAULT NULL,
  `loan_bag_price` double DEFAULT NULL,
  `loan_bag_price_total` double DEFAULT NULL,
  `carrying` double DEFAULT NULL,
  `quantity` int(11) DEFAULT NULL,
  `rent` double DEFAULT NULL,
  `rent_total` double DEFAULT NULL,
  `fan_charge` double DEFAULT NULL,
  `fan_charge_qty` int(11) DEFAULT NULL,
  `fan_charge_total` double DEFAULT NULL,
  `delivery_total` double DEFAULT NULL,
  `discount` double DEFAULT NULL,
  `net_total` double DEFAULT NULL,
  `cur_qty` int(11) DEFAULT NULL,
  `delivery_date` date DEFAULT NULL,
  `created` datetime DEFAULT NULL,
  `created_by` int(10) UNSIGNED DEFAULT NULL,
  `modified` datetime DEFAULT NULL,
  `modified_by` int(10) UNSIGNED DEFAULT NULL,
  `_key` varchar(32) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `expenses`
--

CREATE TABLE `expenses` (
  `id` int(10) UNSIGNED NOT NULL,
  `ledger_head_id` int(11) DEFAULT NULL,
  `purpose` text,
  `amount` double DEFAULT NULL,
  `by_whom` varchar(100) DEFAULT NULL,
  `pay_date` date DEFAULT NULL,
  `created` datetime DEFAULT NULL,
  `created_by` int(11) DEFAULT NULL,
  `modified` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `modified_by` int(11) DEFAULT NULL,
  `_key` varchar(32) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `history`
--

CREATE TABLE `history` (
  `id` int(11) NOT NULL,
  `user_id` int(11) DEFAULT NULL,
  `url` text,
  `controller` varchar(50) DEFAULT NULL,
  `action` varchar(50) DEFAULT NULL,
  `note` text,
  `date_time` datetime DEFAULT NULL,
  `_key` varchar(16) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `invoices`
--

CREATE TABLE `invoices` (
  `id` int(10) UNSIGNED NOT NULL,
  `sale_id` int(10) UNSIGNED NOT NULL,
  `invoice_no` varchar(32) DEFAULT NULL,
  `invoice_date` date DEFAULT NULL,
  `invoice_amount` float DEFAULT NULL,
  `purchase_amount` float DEFAULT NULL,
  `profit` float DEFAULT NULL,
  `discount_amount` float DEFAULT NULL,
  `net_profit` float DEFAULT NULL,
  `_key` varchar(32) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `ledger_heads`
--

CREATE TABLE `ledger_heads` (
  `id` int(10) UNSIGNED NOT NULL,
  `type` varchar(20) DEFAULT NULL,
  `name` varchar(100) DEFAULT NULL,
  `code` int(10) UNSIGNED DEFAULT NULL,
  `is_fixed` tinyint(2) NOT NULL DEFAULT '0',
  `_key` varchar(32) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `loan`
--

CREATE TABLE `loan` (
  `id` int(10) UNSIGNED NOT NULL,
  `case_no` int(11) DEFAULT NULL,
  `type` varchar(20) DEFAULT NULL,
  `advance` float DEFAULT NULL,
  `cash` float DEFAULT NULL,
  `loan_bag_qty` int(11) DEFAULT NULL,
  `loan_bag_price` float DEFAULT NULL,
  `loan_bag_price_total` float DEFAULT NULL,
  `carrying_total` float DEFAULT NULL,
  `qty_total` int(11) DEFAULT NULL,
  `qty_price` float DEFAULT NULL,
  `total_loan_amount` float DEFAULT NULL,
  `taken_person` varchar(100) DEFAULT NULL,
  `created` date DEFAULT NULL,
  `created_by` int(10) UNSIGNED DEFAULT NULL,
  `modified` datetime DEFAULT NULL,
  `modified_by` int(10) UNSIGNED DEFAULT NULL,
  `_key` varchar(32) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `loan_items`
--

CREATE TABLE `loan_items` (
  `id` int(10) UNSIGNED NOT NULL,
  `loan_id` int(10) UNSIGNED NOT NULL,
  `type` varchar(20) DEFAULT NULL,
  `agent_code` int(10) DEFAULT NULL,
  `customer_id` int(10) UNSIGNED NOT NULL,
  `sr_no` int(10) DEFAULT NULL,
  `qty` int(10) DEFAULT NULL,
  `qty_cost` float DEFAULT NULL,
  `qty_cost_total` float DEFAULT NULL,
  `loanbag` int(10) DEFAULT NULL,
  `loanbag_cost` float DEFAULT NULL,
  `loanbag_cost_total` float DEFAULT NULL,
  `carrying_cost` float DEFAULT NULL,
  `net_amount` float DEFAULT NULL,
  `interest_rate` int(10) DEFAULT NULL,
  `interest_amount` float DEFAULT NULL,
  `total_amount` float DEFAULT NULL,
  `per_day_interest` float DEFAULT NULL,
  `min_day` int(11) DEFAULT NULL,
  `loan_period` int(11) DEFAULT NULL,
  `min_payable` float DEFAULT NULL,
  `status` varchar(20) DEFAULT NULL,
  `create_date` date DEFAULT NULL,
  `created` datetime DEFAULT NULL,
  `created_by` int(10) UNSIGNED DEFAULT NULL,
  `modified` datetime DEFAULT NULL,
  `modified_by` int(10) UNSIGNED DEFAULT NULL,
  `_key` varchar(32) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `loan_payment`
--

CREATE TABLE `loan_payment` (
  `id` int(10) UNSIGNED NOT NULL,
  `customer_id` int(10) UNSIGNED NOT NULL,
  `customer_mobile` varchar(15) DEFAULT NULL,
  `sr_no` varchar(15) DEFAULT NULL,
  `payment_type` varchar(20) DEFAULT NULL,
  `quantity` int(11) DEFAULT NULL,
  `cost_per_qty` float DEFAULT NULL,
  `loan_amount` float DEFAULT NULL,
  `interest_rate` int(11) DEFAULT NULL,
  `interest_amount` float DEFAULT NULL,
  `total_loan_amount` float DEFAULT NULL,
  `per_day_interest` float DEFAULT NULL,
  `min_day` int(11) DEFAULT NULL,
  `loan_period` int(11) DEFAULT NULL,
  `min_payable` float DEFAULT NULL,
  `status` varchar(20) DEFAULT NULL,
  `create_date` datetime DEFAULT NULL,
  `created_by` int(10) UNSIGNED DEFAULT NULL,
  `modified` datetime DEFAULT NULL,
  `modified_by` int(10) UNSIGNED DEFAULT NULL,
  `_key` varchar(32) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `loan_payment_adv`
--

CREATE TABLE `loan_payment_adv` (
  `id` int(10) UNSIGNED NOT NULL,
  `case_no` int(10) UNSIGNED DEFAULT NULL,
  `customer_id` int(10) UNSIGNED DEFAULT NULL,
  `customer_mobile` varchar(15) DEFAULT NULL,
  `agent_code` int(11) DEFAULT NULL,
  `empty_bag` int(11) DEFAULT NULL,
  `empty_bag_price` float DEFAULT NULL,
  `empty_bag_price_total` float DEFAULT NULL,
  `carrying_cost` float DEFAULT NULL,
  `loan_amount` float DEFAULT NULL,
  `total_loan_amount` float DEFAULT NULL,
  `debit` float DEFAULT NULL,
  `credit` float DEFAULT NULL,
  `balance` float DEFAULT NULL,
  `note` text,
  `created` date DEFAULT NULL,
  `created_by` int(10) UNSIGNED DEFAULT NULL,
  `modified` datetime DEFAULT NULL,
  `modified_by` int(10) UNSIGNED DEFAULT NULL,
  `_key` varchar(32) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `loan_pending`
--

CREATE TABLE `loan_pending` (
  `id` int(10) UNSIGNED NOT NULL,
  `lp_id` int(10) UNSIGNED DEFAULT NULL,
  `customer_id` int(10) UNSIGNED NOT NULL,
  `customer_mobile` varchar(15) DEFAULT NULL,
  `sr_no` varchar(15) DEFAULT NULL,
  `payment_type` varchar(20) DEFAULT NULL,
  `quantity` int(11) DEFAULT NULL,
  `cost_per_qty` float DEFAULT NULL,
  `loan_amount` float DEFAULT NULL,
  `interest_rate` int(11) DEFAULT NULL,
  `interest_amount` float DEFAULT NULL,
  `total_loan_amount` float DEFAULT NULL,
  `per_day_interest` float DEFAULT NULL,
  `min_day` int(11) DEFAULT NULL,
  `loan_period` int(11) DEFAULT NULL,
  `min_payable` float DEFAULT NULL,
  `status` varchar(20) DEFAULT NULL,
  `create_date` datetime DEFAULT NULL,
  `created_by` int(10) UNSIGNED DEFAULT NULL,
  `modified` datetime DEFAULT NULL,
  `modified_by` int(10) UNSIGNED DEFAULT NULL,
  `_key` varchar(32) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `loan_receive`
--

CREATE TABLE `loan_receive` (
  `id` int(11) NOT NULL,
  `sr_no` int(11) DEFAULT NULL,
  `receive_number` int(11) DEFAULT NULL,
  `receive_date` date DEFAULT NULL,
  `received_by` varchar(100) DEFAULT NULL,
  `created` datetime DEFAULT NULL,
  `created_by` int(11) DEFAULT NULL,
  `modified` datetime DEFAULT NULL,
  `modified_by` int(11) DEFAULT NULL,
  `_key` varchar(16) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `loan_receive_items`
--

CREATE TABLE `loan_receive_items` (
  `id` int(10) UNSIGNED NOT NULL,
  `receive_id` int(11) DEFAULT NULL,
  `delivery_number` int(11) DEFAULT NULL,
  `sr_no` int(11) DEFAULT NULL,
  `customer_id` int(10) UNSIGNED NOT NULL,
  `customer_mobile` varchar(15) DEFAULT NULL,
  `agent_code` int(11) DEFAULT NULL,
  `lot_no` varchar(20) DEFAULT NULL,
  `qty` int(11) DEFAULT NULL,
  `cost_per_qty` double DEFAULT NULL,
  `loan_amount` double DEFAULT NULL,
  `loan_days` int(11) DEFAULT NULL,
  `per_day_interest` double DEFAULT NULL,
  `interest_amount` double DEFAULT NULL,
  `total_amount` double DEFAULT NULL,
  `discount` double DEFAULT NULL,
  `net_amount` double DEFAULT NULL,
  `paid_amount` double DEFAULT NULL,
  `interest_rate` int(11) DEFAULT NULL,
  `cur_qty` int(11) DEFAULT NULL,
  `status` varchar(20) DEFAULT NULL,
  `receive_date` date DEFAULT NULL,
  `created` datetime DEFAULT NULL,
  `created_by` int(10) UNSIGNED DEFAULT NULL,
  `modified` datetime DEFAULT NULL,
  `modified_by` int(10) UNSIGNED DEFAULT NULL,
  `_key` varchar(32) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `loan_setting`
--

CREATE TABLE `loan_setting` (
  `id` int(10) UNSIGNED NOT NULL,
  `interest_rate` int(10) UNSIGNED DEFAULT NULL,
  `period` int(10) UNSIGNED DEFAULT NULL,
  `min_day` int(10) DEFAULT NULL,
  `empty_bag_price` float DEFAULT NULL,
  `max_loan_per_qty` float DEFAULT NULL,
  `max_rent_per_qty` float DEFAULT NULL,
  `fan_charge` int(11) NOT NULL,
  `_key` varchar(32) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `location_floor`
--

CREATE TABLE `location_floor` (
  `id` int(10) UNSIGNED NOT NULL,
  `room_id` int(11) DEFAULT NULL,
  `name` varchar(15) DEFAULT NULL,
  `_key` varchar(32) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `location_pocket`
--

CREATE TABLE `location_pocket` (
  `id` int(10) UNSIGNED NOT NULL,
  `room_id` int(10) DEFAULT NULL,
  `floor_id` int(10) UNSIGNED DEFAULT NULL,
  `name` varchar(20) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `location_room`
--

CREATE TABLE `location_room` (
  `id` int(10) UNSIGNED NOT NULL,
  `name` varchar(15) DEFAULT NULL,
  `_key` varchar(32) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `pallot`
--

CREATE TABLE `pallot` (
  `id` int(11) NOT NULL,
  `pallot_date` date DEFAULT NULL,
  `pallot_number` int(11) DEFAULT NULL,
  `sr_no` int(11) DEFAULT NULL,
  `sum_qty` int(11) DEFAULT NULL,
  `created` datetime DEFAULT NULL,
  `created_by` int(11) DEFAULT NULL,
  `modified` datetime DEFAULT NULL,
  `modified_by` int(11) DEFAULT NULL,
  `_key` varchar(16) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `pallot_items`
--

CREATE TABLE `pallot_items` (
  `id` int(11) NOT NULL,
  `pallot_id` int(11) DEFAULT NULL,
  `pallot_date` date DEFAULT NULL,
  `sr_no` int(11) DEFAULT NULL,
  `room` int(11) DEFAULT NULL,
  `floor` int(11) DEFAULT NULL,
  `pocket` int(11) DEFAULT NULL,
  `quantity` int(11) DEFAULT NULL,
  `created` datetime DEFAULT NULL,
  `created_by` int(11) DEFAULT NULL,
  `modified` datetime DEFAULT NULL,
  `modified_by` int(11) DEFAULT NULL,
  `_key` varchar(16) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `payments`
--

CREATE TABLE `payments` (
  `id` int(10) UNSIGNED NOT NULL,
  `customer_id` int(10) UNSIGNED DEFAULT NULL,
  `product_in_id` int(10) UNSIGNED NOT NULL,
  `sr_no` varchar(10) DEFAULT NULL,
  `payment_type` varchar(20) DEFAULT NULL,
  `pay_date` date DEFAULT NULL,
  `payment_mode` varchar(20) DEFAULT NULL,
  `account_id` int(10) UNSIGNED DEFAULT NULL,
  `bank_name` varchar(100) DEFAULT NULL,
  `check_no` varchar(32) DEFAULT NULL,
  `advance_amount` float DEFAULT NULL,
  `carrying_cost` float DEFAULT NULL,
  `labor_cost` float DEFAULT NULL,
  `other_cost` float DEFAULT NULL,
  `total_cost` float DEFAULT NULL,
  `paid_amount` float DEFAULT NULL,
  `due_amount` float DEFAULT NULL,
  `status` varchar(20) DEFAULT NULL,
  `create_date` datetime DEFAULT NULL,
  `created_by` int(11) DEFAULT NULL,
  `last_update` datetime DEFAULT NULL,
  `update_by` int(11) DEFAULT NULL,
  `_key` varchar(32) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `payment_in`
--

CREATE TABLE `payment_in` (
  `id` int(10) UNSIGNED NOT NULL,
  `pin_number` int(11) DEFAULT NULL,
  `pin_id` int(10) UNSIGNED NOT NULL,
  `account_id` int(10) UNSIGNED DEFAULT NULL,
  `payment_type` varchar(20) DEFAULT NULL,
  `pay_date` date DEFAULT NULL,
  `carrying_cost` float DEFAULT NULL,
  `labor_cost` float DEFAULT NULL,
  `other_cost` float DEFAULT NULL,
  `advance_amount` float DEFAULT NULL,
  `net_amount` float DEFAULT NULL,
  `paid_amount` float DEFAULT NULL,
  `due_amount` float DEFAULT NULL,
  `status` varchar(20) DEFAULT 'Pending',
  `created` datetime DEFAULT NULL,
  `created_by` int(11) DEFAULT NULL,
  `modified` datetime DEFAULT NULL,
  `modified_by` int(11) DEFAULT NULL,
  `_key` varchar(32) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `payment_load_unload`
--

CREATE TABLE `payment_load_unload` (
  `id` int(11) NOT NULL,
  `type` varchar(30) DEFAULT NULL,
  `pament_for` varchar(30) DEFAULT NULL,
  `sr_no` int(11) DEFAULT NULL,
  `quantity` int(11) DEFAULT NULL,
  `quantity_price` float DEFAULT NULL,
  `price_total` float DEFAULT NULL,
  `current_location` text,
  `new_location` text,
  `created` date DEFAULT NULL,
  `created_by` int(11) DEFAULT NULL,
  `modified` datetime DEFAULT NULL,
  `modified_by` int(11) DEFAULT NULL,
  `_key` varchar(32) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `payment_out`
--

CREATE TABLE `payment_out` (
  `id` int(10) UNSIGNED NOT NULL,
  `pout_id` int(10) UNSIGNED DEFAULT NULL,
  `payment_type` varchar(20) DEFAULT NULL,
  `loan_bag` int(11) DEFAULT NULL,
  `loan_bag_cost` float DEFAULT NULL,
  `loan_bag_amount` float DEFAULT NULL,
  `qty` int(11) DEFAULT NULL,
  `qty_cost` float DEFAULT NULL,
  `qty_cost_amount` float DEFAULT NULL,
  `net_amount` float DEFAULT NULL,
  `paid_amount` float DEFAULT NULL,
  `due_amount` float DEFAULT NULL,
  `status` varchar(20) DEFAULT 'Pending',
  `created` datetime DEFAULT NULL,
  `created_by` int(11) DEFAULT NULL,
  `modified` datetime DEFAULT NULL,
  `modified_by` int(11) DEFAULT NULL,
  `_key` varchar(32) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `payment_pending`
--

CREATE TABLE `payment_pending` (
  `id` int(10) UNSIGNED NOT NULL,
  `payment_id` int(11) DEFAULT NULL,
  `customer_id` int(10) UNSIGNED NOT NULL,
  `product_in_id` int(10) UNSIGNED DEFAULT NULL,
  `sr_no` varchar(10) DEFAULT NULL,
  `delivery_sr_no` varchar(15) DEFAULT NULL,
  `pay_date` date DEFAULT NULL,
  `carrying_cost` float DEFAULT NULL,
  `labor_cost` float DEFAULT NULL,
  `other_cost` float DEFAULT NULL,
  `total_cost` float DEFAULT NULL,
  `net_total_cost` float DEFAULT NULL,
  `paid_amount` float DEFAULT NULL,
  `status` varchar(20) DEFAULT NULL,
  `create_date` datetime DEFAULT NULL,
  `created_by` int(11) DEFAULT NULL,
  `last_update` datetime DEFAULT NULL,
  `update_by` int(11) DEFAULT NULL,
  `_key` varchar(32) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `permissions`
--

CREATE TABLE `permissions` (
  `id` int(10) UNSIGNED NOT NULL,
  `user_id` int(10) UNSIGNED NOT NULL,
  `items` text
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `product_in`
--

CREATE TABLE `product_in` (
  `id` int(10) NOT NULL,
  `answer` enum('Yes','No') DEFAULT 'No',
  `type` int(11) DEFAULT NULL,
  `customer_id` int(10) UNSIGNED DEFAULT NULL,
  `customer_mobile` varchar(15) DEFAULT NULL,
  `sr_no` int(11) NOT NULL,
  `quantity` int(11) DEFAULT NULL,
  `lot_no` varchar(20) DEFAULT NULL,
  `agent_code` int(11) DEFAULT NULL,
  `loan_pack` int(11) DEFAULT NULL,
  `carrying_cost` float DEFAULT NULL,
  `advance_booking_no` varchar(10) DEFAULT NULL,
  `advance_booking_amount` float DEFAULT NULL,
  `status` tinyint(2) NOT NULL DEFAULT '0',
  `create_date` date DEFAULT NULL,
  `created_by` int(10) UNSIGNED DEFAULT NULL,
  `modified` datetime DEFAULT NULL,
  `modified_by` int(10) UNSIGNED DEFAULT NULL,
  `_key` varchar(32) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `product_out`
--

CREATE TABLE `product_out` (
  `id` int(10) UNSIGNED NOT NULL,
  `customer_id` int(10) UNSIGNED DEFAULT NULL,
  `customer_mobile` varchar(15) DEFAULT NULL,
  `sr_no` int(11) DEFAULT NULL,
  `delivery_sr_no` int(11) DEFAULT NULL,
  `quantity` int(11) DEFAULT NULL,
  `loan_pack` int(11) DEFAULT NULL,
  `lot_no` varchar(20) DEFAULT NULL,
  `agent_code` int(11) DEFAULT NULL,
  `advance_booking_no` varchar(10) DEFAULT NULL,
  `delivery_date` date DEFAULT NULL,
  `created` datetime DEFAULT NULL,
  `created_by` int(10) UNSIGNED DEFAULT NULL,
  `modified` datetime DEFAULT NULL,
  `modified_by` int(10) UNSIGNED DEFAULT NULL,
  `_key` varchar(32) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `product_type`
--

CREATE TABLE `product_type` (
  `id` int(10) UNSIGNED NOT NULL,
  `name` varchar(50) DEFAULT NULL,
  `created` datetime DEFAULT NULL,
  `created_by` int(11) DEFAULT NULL,
  `modified` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `modified_by` int(11) DEFAULT NULL,
  `_key` varchar(32) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `roles`
--

CREATE TABLE `roles` (
  `id` int(10) UNSIGNED NOT NULL,
  `name` varchar(50) DEFAULT NULL,
  `is_deleted` tinyint(4) NOT NULL DEFAULT '0',
  `_key` varchar(32) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `settings`
--

CREATE TABLE `settings` (
  `id` int(10) UNSIGNED NOT NULL,
  `site_name` varchar(100) DEFAULT NULL,
  `title` varchar(100) DEFAULT NULL,
  `description` text,
  `author` varchar(50) DEFAULT NULL,
  `author_email` varchar(100) DEFAULT NULL,
  `author_phone` varchar(15) DEFAULT NULL,
  `author_mobile` varchar(15) DEFAULT NULL,
  `other_contacts` text,
  `author_address` text,
  `auto_pricing` tinyint(2) NOT NULL DEFAULT '0',
  `sendmail` tinyint(2) NOT NULL DEFAULT '0',
  `sendsms` tinyint(2) NOT NULL DEFAULT '0',
  `payment_modes` varchar(255) DEFAULT NULL,
  `vat` int(10) DEFAULT NULL,
  `profit_count` int(10) DEFAULT NULL,
  `page_size` int(10) DEFAULT NULL,
  `currency` varchar(10) DEFAULT NULL,
  `theme` varchar(20) DEFAULT NULL,
  `language` varchar(10) DEFAULT NULL,
  `timezone` varchar(30) DEFAULT NULL,
  `datetime_format` varchar(50) DEFAULT NULL,
  `favicon` varchar(32) DEFAULT NULL,
  `logo` varchar(32) DEFAULT NULL,
  `_key` varchar(32) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `stocks`
--

CREATE TABLE `stocks` (
  `id` int(10) UNSIGNED NOT NULL,
  `customer_id` int(10) UNSIGNED DEFAULT NULL,
  `customer_mobile` varchar(15) DEFAULT NULL,
  `product_in_id` int(10) UNSIGNED DEFAULT NULL,
  `product_out_id` int(10) UNSIGNED DEFAULT NULL,
  `location` varchar(255) DEFAULT NULL,
  `sr_no` int(11) DEFAULT NULL,
  `delivery_sr_no` int(11) DEFAULT NULL,
  `agent_code` int(11) DEFAULT NULL,
  `type` varchar(30) DEFAULT NULL,
  `lp_given` int(11) DEFAULT NULL,
  `lp_taken` int(11) DEFAULT NULL,
  `lp_due` int(11) DEFAULT NULL,
  `qty_in` int(11) DEFAULT NULL,
  `qty_out` int(11) DEFAULT NULL,
  `qty_total` int(11) DEFAULT NULL,
  `create_date` date DEFAULT NULL,
  `created_by` int(11) DEFAULT NULL,
  `_key` varchar(32) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `stock_location`
--

CREATE TABLE `stock_location` (
  `id` int(11) NOT NULL,
  `stock_id` int(11) DEFAULT NULL,
  `stock_srno` varchar(10) DEFAULT NULL,
  `room_id` int(11) DEFAULT NULL,
  `floor_id` int(11) DEFAULT NULL,
  `pockets` text,
  `created` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `created_by` int(11) DEFAULT NULL,
  `_key` varchar(32) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(11) UNSIGNED NOT NULL,
  `display_name` varchar(30) DEFAULT NULL,
  `email` varchar(100) DEFAULT NULL,
  `password` varchar(255) DEFAULT NULL,
  `ip` varchar(30) DEFAULT NULL,
  `role` tinyint(4) DEFAULT NULL,
  `lastlogin` datetime DEFAULT NULL,
  `is_loggedin` tinyint(4) DEFAULT NULL,
  `activation_token` varchar(64) DEFAULT NULL,
  `activation_ip` varchar(30) DEFAULT NULL,
  `activation_time` datetime DEFAULT NULL,
  `password_token` varchar(64) DEFAULT NULL,
  `password_request_ip` varchar(30) DEFAULT NULL,
  `password_request_time` datetime DEFAULT NULL,
  `password_reset_ip` varchar(30) DEFAULT NULL,
  `password_reset_time` datetime DEFAULT NULL,
  `status` tinyint(4) NOT NULL,
  `created` datetime DEFAULT NULL,
  `created_by` int(11) DEFAULT NULL,
  `modified` datetime DEFAULT NULL,
  `modified_by` int(11) DEFAULT NULL,
  `deletable` tinyint(2) NOT NULL DEFAULT '1',
  `_key` varchar(32) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `user_profiles`
--

CREATE TABLE `user_profiles` (
  `id` int(10) UNSIGNED NOT NULL,
  `user_id` int(11) UNSIGNED DEFAULT NULL,
  `firstname` varchar(50) DEFAULT NULL,
  `lastname` varchar(50) DEFAULT NULL,
  `gender` varchar(20) DEFAULT NULL,
  `dob` varchar(20) DEFAULT NULL,
  `phone` varchar(15) DEFAULT NULL,
  `city` varchar(50) DEFAULT NULL,
  `address` varchar(255) DEFAULT NULL,
  `avatar` varchar(32) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `accounts`
--
ALTER TABLE `accounts`
  ADD PRIMARY KEY (`id`),
  ADD KEY `number` (`account_number`),
  ADD KEY `type` (`account_type`),
  ADD KEY `bank_id` (`bank_id`);

--
-- Indexes for table `account_balance`
--
ALTER TABLE `account_balance`
  ADD PRIMARY KEY (`id`),
  ADD KEY `account_id` (`account_id`),
  ADD KEY `payment_id` (`payment_id`);

--
-- Indexes for table `agents`
--
ALTER TABLE `agents`
  ADD PRIMARY KEY (`id`),
  ADD KEY `code` (`code`),
  ADD KEY `mobile` (`mobile`),
  ADD KEY `_key` (`_key`);

--
-- Indexes for table `balancesheet`
--
ALTER TABLE `balancesheet`
  ADD PRIMARY KEY (`id`),
  ADD KEY `last_update` (`last_update`),
  ADD KEY `customer_payment_id` (`customer_payment_id`),
  ADD KEY `expense_id` (`expense_id`),
  ADD KEY `payment_id` (`payment_id`);

--
-- Indexes for table `banks`
--
ALTER TABLE `banks`
  ADD PRIMARY KEY (`id`),
  ADD KEY `is_deleted` (`is_deleted`);

--
-- Indexes for table `cash_account`
--
ALTER TABLE `cash_account`
  ADD PRIMARY KEY (`id`),
  ADD KEY `account_id` (`account_id`),
  ADD KEY `type` (`type`),
  ADD KEY `product_in_payment_id` (`product_in_payment_id`),
  ADD KEY `product_out_payment_id` (`product_out_payment_id`),
  ADD KEY `loan_payment_id` (`loan_payment_id`),
  ADD KEY `loan_receive_id` (`loan_receive_id`),
  ADD KEY `expense_id` (`expense_id`),
  ADD KEY `adv_loan_payment_id` (`adv_loan_payment_id`),
  ADD KEY `payment_load_unload_id` (`payment_load_unload_id`),
  ADD KEY `ledger_head_id` (`ledger_head_id`);

--
-- Indexes for table `customers`
--
ALTER TABLE `customers`
  ADD PRIMARY KEY (`id`),
  ADD KEY `mobile` (`mobile`),
  ADD KEY `name` (`name`),
  ADD KEY `type` (`type`),
  ADD KEY `has_loan` (`has_loan`);

--
-- Indexes for table `customer_balance`
--
ALTER TABLE `customer_balance`
  ADD PRIMARY KEY (`id`),
  ADD KEY `customer_id` (`customer_id`),
  ADD KEY `debit` (`debit`),
  ADD KEY `credit` (`credit`),
  ADD KEY `balance` (`balance`),
  ADD KEY `invoice_no` (`invoice_no`),
  ADD KEY `amount` (`amount`),
  ADD KEY `payment_id` (`payment_id`),
  ADD KEY `created` (`created`);

--
-- Indexes for table `customer_payments`
--
ALTER TABLE `customer_payments`
  ADD PRIMARY KEY (`id`),
  ADD KEY `customer_id` (`customer_id`),
  ADD KEY `sale_id` (`pout_id`),
  ADD KEY `sr_no` (`sr_no`),
  ADD KEY `delivery_sr_no` (`delivery_sr_no`),
  ADD KEY `customer_mobile` (`customer_mobile`);

--
-- Indexes for table `damaged_products`
--
ALTER TABLE `damaged_products`
  ADD PRIMARY KEY (`id`),
  ADD KEY `product_id` (`product_id`),
  ADD KEY `company_id` (`company_id`),
  ADD KEY `category_id` (`category_id`);

--
-- Indexes for table `delivery`
--
ALTER TABLE `delivery`
  ADD PRIMARY KEY (`id`),
  ADD KEY `_key` (`_key`);

--
-- Indexes for table `delivery_items`
--
ALTER TABLE `delivery_items`
  ADD PRIMARY KEY (`id`),
  ADD KEY `customer_id` (`customer_id`),
  ADD KEY `sr_no` (`sr_no`),
  ADD KEY `_key` (`_key`),
  ADD KEY `agent_code` (`agent_code`),
  ADD KEY `delivery_id` (`delivery_id`),
  ADD KEY `delivery_number` (`delivery_number`);

--
-- Indexes for table `expenses`
--
ALTER TABLE `expenses`
  ADD PRIMARY KEY (`id`),
  ADD KEY `pay_date` (`pay_date`),
  ADD KEY `_key` (`_key`),
  ADD KEY `created_by` (`created_by`),
  ADD KEY `ledger_head_id` (`ledger_head_id`);

--
-- Indexes for table `history`
--
ALTER TABLE `history`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`),
  ADD KEY `date_time` (`date_time`);

--
-- Indexes for table `invoices`
--
ALTER TABLE `invoices`
  ADD PRIMARY KEY (`id`),
  ADD KEY `sale_id` (`sale_id`),
  ADD KEY `invoice_no` (`invoice_no`);

--
-- Indexes for table `ledger_heads`
--
ALTER TABLE `ledger_heads`
  ADD PRIMARY KEY (`id`),
  ADD KEY `code` (`code`),
  ADD KEY `is_fixed` (`is_fixed`);

--
-- Indexes for table `loan`
--
ALTER TABLE `loan`
  ADD PRIMARY KEY (`id`),
  ADD KEY `created_by` (`created_by`),
  ADD KEY `modified_by` (`modified_by`),
  ADD KEY `_key` (`_key`),
  ADD KEY `case_no` (`case_no`);

--
-- Indexes for table `loan_items`
--
ALTER TABLE `loan_items`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `sr_no` (`sr_no`),
  ADD KEY `loan_id` (`loan_id`),
  ADD KEY `agent_code` (`agent_code`),
  ADD KEY `customer_id` (`customer_id`),
  ADD KEY `created_by` (`created_by`),
  ADD KEY `modified_by` (`modified_by`),
  ADD KEY `_key` (`_key`);

--
-- Indexes for table `loan_payment`
--
ALTER TABLE `loan_payment`
  ADD PRIMARY KEY (`id`),
  ADD KEY `customer_id` (`customer_id`),
  ADD KEY `_key` (`_key`),
  ADD KEY `created_by` (`created_by`),
  ADD KEY `modified_by` (`modified_by`),
  ADD KEY `customer_mobile` (`customer_mobile`),
  ADD KEY `payment_type` (`payment_type`);

--
-- Indexes for table `loan_payment_adv`
--
ALTER TABLE `loan_payment_adv`
  ADD PRIMARY KEY (`id`),
  ADD KEY `customer_id` (`customer_id`),
  ADD KEY `_key` (`_key`),
  ADD KEY `created_by` (`created_by`),
  ADD KEY `modified_by` (`modified_by`),
  ADD KEY `customer_mobile` (`customer_mobile`),
  ADD KEY `case_no` (`case_no`),
  ADD KEY `balance` (`balance`);

--
-- Indexes for table `loan_pending`
--
ALTER TABLE `loan_pending`
  ADD PRIMARY KEY (`id`),
  ADD KEY `customer_id` (`customer_id`),
  ADD KEY `_key` (`_key`),
  ADD KEY `created_by` (`created_by`),
  ADD KEY `modified_by` (`modified_by`),
  ADD KEY `customer_mobile` (`customer_mobile`),
  ADD KEY `payment_type` (`payment_type`);

--
-- Indexes for table `loan_receive`
--
ALTER TABLE `loan_receive`
  ADD PRIMARY KEY (`id`),
  ADD KEY `receive_number` (`receive_number`),
  ADD KEY `_key` (`_key`);

--
-- Indexes for table `loan_receive_items`
--
ALTER TABLE `loan_receive_items`
  ADD PRIMARY KEY (`id`),
  ADD KEY `customer_id` (`customer_id`),
  ADD KEY `_key` (`_key`),
  ADD KEY `receive_id` (`receive_id`),
  ADD KEY `sr_no` (`sr_no`),
  ADD KEY `delivery_number` (`delivery_number`);

--
-- Indexes for table `loan_setting`
--
ALTER TABLE `loan_setting`
  ADD PRIMARY KEY (`id`),
  ADD KEY `interest_rate` (`interest_rate`,`period`,`min_day`,`_key`);

--
-- Indexes for table `location_floor`
--
ALTER TABLE `location_floor`
  ADD PRIMARY KEY (`id`),
  ADD KEY `_key` (`_key`);

--
-- Indexes for table `location_pocket`
--
ALTER TABLE `location_pocket`
  ADD PRIMARY KEY (`id`),
  ADD KEY `location_id` (`floor_id`),
  ADD KEY `room_id` (`room_id`);

--
-- Indexes for table `location_room`
--
ALTER TABLE `location_room`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `pallot`
--
ALTER TABLE `pallot`
  ADD PRIMARY KEY (`id`),
  ADD KEY `pallot_number` (`pallot_number`),
  ADD KEY `_key` (`_key`);

--
-- Indexes for table `pallot_items`
--
ALTER TABLE `pallot_items`
  ADD PRIMARY KEY (`id`),
  ADD KEY `pallot_number` (`pallot_id`),
  ADD KEY `sr_no` (`sr_no`),
  ADD KEY `room` (`room`),
  ADD KEY `floor` (`floor`);

--
-- Indexes for table `payments`
--
ALTER TABLE `payments`
  ADD PRIMARY KEY (`id`),
  ADD KEY `purchase_id` (`product_in_id`),
  ADD KEY `company_id` (`customer_id`),
  ADD KEY `account_id` (`account_id`),
  ADD KEY `sr_no` (`sr_no`),
  ADD KEY `type` (`payment_type`);

--
-- Indexes for table `payment_in`
--
ALTER TABLE `payment_in`
  ADD PRIMARY KEY (`id`),
  ADD KEY `pin_id` (`pin_id`),
  ADD KEY `account_id` (`account_id`),
  ADD KEY `payment_type` (`payment_type`),
  ADD KEY `pin_number` (`pin_number`);

--
-- Indexes for table `payment_load_unload`
--
ALTER TABLE `payment_load_unload`
  ADD PRIMARY KEY (`id`),
  ADD KEY `created_by` (`created_by`),
  ADD KEY `modified_by` (`modified_by`),
  ADD KEY `_key` (`_key`);

--
-- Indexes for table `payment_out`
--
ALTER TABLE `payment_out`
  ADD PRIMARY KEY (`id`),
  ADD KEY `pout_id` (`pout_id`),
  ADD KEY `payment_type` (`payment_type`);

--
-- Indexes for table `payment_pending`
--
ALTER TABLE `payment_pending`
  ADD PRIMARY KEY (`id`),
  ADD KEY `customer_id` (`customer_id`),
  ADD KEY `sale_id` (`product_in_id`),
  ADD KEY `sr_no` (`sr_no`),
  ADD KEY `delivery_sr_no` (`delivery_sr_no`);

--
-- Indexes for table `permissions`
--
ALTER TABLE `permissions`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `product_in`
--
ALTER TABLE `product_in`
  ADD PRIMARY KEY (`id`),
  ADD KEY `customer_id` (`customer_id`),
  ADD KEY `created_by` (`created_by`),
  ADD KEY `quantity` (`quantity`),
  ADD KEY `_key` (`_key`),
  ADD KEY `status` (`status`),
  ADD KEY `customer_mobile` (`customer_mobile`),
  ADD KEY `agent_code` (`agent_code`),
  ADD KEY `type_id` (`type`),
  ADD KEY `sr_no` (`sr_no`);

--
-- Indexes for table `product_out`
--
ALTER TABLE `product_out`
  ADD PRIMARY KEY (`id`),
  ADD KEY `customer_id` (`customer_id`),
  ADD KEY `sr_no` (`sr_no`),
  ADD KEY `quantity` (`quantity`),
  ADD KEY `_key` (`_key`),
  ADD KEY `customer_mobile` (`customer_mobile`),
  ADD KEY `agent_code` (`agent_code`);

--
-- Indexes for table `product_type`
--
ALTER TABLE `product_type`
  ADD PRIMARY KEY (`id`),
  ADD KEY `_key` (`_key`);

--
-- Indexes for table `roles`
--
ALTER TABLE `roles`
  ADD PRIMARY KEY (`id`),
  ADD KEY `is_deleted` (`is_deleted`);

--
-- Indexes for table `settings`
--
ALTER TABLE `settings`
  ADD PRIMARY KEY (`id`),
  ADD KEY `auto_pricing` (`auto_pricing`),
  ADD KEY `vat` (`vat`),
  ADD KEY `profit_count` (`profit_count`);

--
-- Indexes for table `stocks`
--
ALTER TABLE `stocks`
  ADD PRIMARY KEY (`id`),
  ADD KEY `purchase_id` (`product_in_id`),
  ADD KEY `sale_id` (`product_out_id`),
  ADD KEY `location_id` (`location`),
  ADD KEY `type` (`type`),
  ADD KEY `quantity` (`qty_in`),
  ADD KEY `customer_id` (`customer_id`),
  ADD KEY `qty_out` (`qty_out`),
  ADD KEY `total_qty` (`qty_total`),
  ADD KEY `customer_mobile` (`customer_mobile`),
  ADD KEY `agent_code` (`agent_code`),
  ADD KEY `created_by` (`created_by`);

--
-- Indexes for table `stock_location`
--
ALTER TABLE `stock_location`
  ADD PRIMARY KEY (`id`),
  ADD KEY `stock_id` (`stock_id`),
  ADD KEY `room_id` (`room_id`),
  ADD KEY `floor_id` (`floor_id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `user_profiles`
--
ALTER TABLE `user_profiles`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `accounts`
--
ALTER TABLE `accounts`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;
--
-- AUTO_INCREMENT for table `account_balance`
--
ALTER TABLE `account_balance`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=28;
--
-- AUTO_INCREMENT for table `agents`
--
ALTER TABLE `agents`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=118;
--
-- AUTO_INCREMENT for table `balancesheet`
--
ALTER TABLE `balancesheet`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=1131;
--
-- AUTO_INCREMENT for table `banks`
--
ALTER TABLE `banks`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=53;
--
-- AUTO_INCREMENT for table `cash_account`
--
ALTER TABLE `cash_account`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=40716;
--
-- AUTO_INCREMENT for table `customers`
--
ALTER TABLE `customers`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11591;
--
-- AUTO_INCREMENT for table `customer_balance`
--
ALTER TABLE `customer_balance`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `customer_payments`
--
ALTER TABLE `customer_payments`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `damaged_products`
--
ALTER TABLE `damaged_products`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `delivery`
--
ALTER TABLE `delivery`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=672;
--
-- AUTO_INCREMENT for table `delivery_items`
--
ALTER TABLE `delivery_items`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=672;
--
-- AUTO_INCREMENT for table `expenses`
--
ALTER TABLE `expenses`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=1131;
--
-- AUTO_INCREMENT for table `history`
--
ALTER TABLE `history`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=23664;
--
-- AUTO_INCREMENT for table `invoices`
--
ALTER TABLE `invoices`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `ledger_heads`
--
ALTER TABLE `ledger_heads`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=88;
--
-- AUTO_INCREMENT for table `loan`
--
ALTER TABLE `loan`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=885;
--
-- AUTO_INCREMENT for table `loan_items`
--
ALTER TABLE `loan_items`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8898;
--
-- AUTO_INCREMENT for table `loan_payment`
--
ALTER TABLE `loan_payment`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `loan_payment_adv`
--
ALTER TABLE `loan_payment_adv`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `loan_pending`
--
ALTER TABLE `loan_pending`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `loan_receive`
--
ALTER TABLE `loan_receive`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=672;
--
-- AUTO_INCREMENT for table `loan_receive_items`
--
ALTER TABLE `loan_receive_items`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=672;
--
-- AUTO_INCREMENT for table `loan_setting`
--
ALTER TABLE `loan_setting`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;
--
-- AUTO_INCREMENT for table `location_floor`
--
ALTER TABLE `location_floor`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=21;
--
-- AUTO_INCREMENT for table `location_pocket`
--
ALTER TABLE `location_pocket`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=100;
--
-- AUTO_INCREMENT for table `location_room`
--
ALTER TABLE `location_room`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;
--
-- AUTO_INCREMENT for table `pallot`
--
ALTER TABLE `pallot`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;
--
-- AUTO_INCREMENT for table `pallot_items`
--
ALTER TABLE `pallot_items`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;
--
-- AUTO_INCREMENT for table `payments`
--
ALTER TABLE `payments`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `payment_in`
--
ALTER TABLE `payment_in`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10170;
--
-- AUTO_INCREMENT for table `payment_load_unload`
--
ALTER TABLE `payment_load_unload`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `payment_out`
--
ALTER TABLE `payment_out`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `payment_pending`
--
ALTER TABLE `payment_pending`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `permissions`
--
ALTER TABLE `permissions`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=14;
--
-- AUTO_INCREMENT for table `product_in`
--
ALTER TABLE `product_in`
  MODIFY `id` int(10) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10173;
--
-- AUTO_INCREMENT for table `product_out`
--
ALTER TABLE `product_out`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;
--
-- AUTO_INCREMENT for table `product_type`
--
ALTER TABLE `product_type`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=49;
--
-- AUTO_INCREMENT for table `roles`
--
ALTER TABLE `roles`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;
--
-- AUTO_INCREMENT for table `settings`
--
ALTER TABLE `settings`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;
--
-- AUTO_INCREMENT for table `stocks`
--
ALTER TABLE `stocks`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10183;
--
-- AUTO_INCREMENT for table `stock_location`
--
ALTER TABLE `stock_location`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=14;
--
-- AUTO_INCREMENT for table `user_profiles`
--
ALTER TABLE `user_profiles`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=14;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
