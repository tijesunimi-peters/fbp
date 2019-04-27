-- phpMyAdmin SQL Dump
-- version 4.0.4
-- http://www.phpmyadmin.net
--
-- Host: localhost
-- Generation Time: Nov 27, 2014 at 05:05 PM
-- Server version: 5.6.12-log
-- PHP Version: 5.4.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Database: `laravel`
--

-- --------------------------------------------------------

--
-- Table structure for table `faceliftbudgetcategories`
--

CREATE TABLE IF NOT EXISTS `faceliftbudgetcategories` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `category_id` int(11) NOT NULL,
  `category` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `updated_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=36 ;

--
-- Dumping data for table `faceliftbudgetcategories`
--

INSERT INTO `faceliftbudgetcategories` (`id`, `category_id`, `category`, `created_at`, `updated_at`) VALUES
(1, 1, 'Salaries & Wages', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(2, 2, 'Staff Training Fund', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(3, 3, 'Staff Incentives', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(4, 4, 'Medical Expenses', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(5, 5, 'Staff Award Expenses', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(6, 6, 'TGIF', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(7, 7, 'Electricity', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(8, 8, 'Fueling of Generators', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(9, 9, 'Generator Repairs', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(10, 10, 'Advertisements', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(11, 11, 'Biz Promotion', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(12, 12, 'Printing & Stationaries', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(13, 13, 'PR & Entertainment', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(14, 14, 'Corp. Social Responsibility', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(15, 15, 'Salon Supplies', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(16, 16, 'Cost of Goods Sold', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(17, 17, 'Telephone Bills', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(18, 18, 'Office Maintenance', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(19, 19, 'Supplies[toiletries,CWay etc]', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(20, 20, 'Satelite Tv Subscription', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(21, 21, 'Cleaning Expenses', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(22, 22, 'Internet/IT Expenses', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(23, 23, 'Newspapers & Magazines', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(24, 24, 'Taxes', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(25, 25, 'Depreciation', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(26, 26, 'Training Materials', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(27, 27, 'Insurance', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(28, 28, 'Transport & Travelling', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(29, 29, 'Security Expenses', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(30, 30, 'Bank Charges', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(31, 31, 'Industry Association Fee', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(32, 32, 'Others[Miscellaneous]', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(33, 33, 'Director''s WithDrawal', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(34, 34, 'Rent', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(35, 35, 'Loan Repayment', '0000-00-00 00:00:00', '0000-00-00 00:00:00');

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
