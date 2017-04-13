JD Flight
===============
项目基于 tp5，不含框架源码，cron 15min 执行一次```php think cron```，cron 文件在`application/console/Cron.php`，含机票经销商 filter 说明。
数据库结构：
```
-- phpMyAdmin SQL Dump
-- version 4.4.15.6
-- http://www.phpmyadmin.net
--
-- Host: localhost
-- Generation Time: 2017-04-13 21:22:43
-- 服务器版本： 10.0.23-MariaDB-log
-- PHP Version: 7.0.7

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";

--
-- Database: `flight`
--

-- --------------------------------------------------------

--
-- 表的结构 `flight`
--

CREATE TABLE IF NOT EXISTS `flight` (
  `id` int(10) unsigned NOT NULL,
  `flight_no` varchar(6) NOT NULL,
  `dep_date` date NOT NULL,
  `price` int(4) unsigned NOT NULL,
  `create_at` int(10) unsigned NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- 表的结构 `flight_info`
--

CREATE TABLE IF NOT EXISTS `flight_info` (
  `id` int(10) unsigned NOT NULL,
  `flight_no` varchar(6) NOT NULL,
  `dep_date` date NOT NULL,
  `dep_time` varchar(4) NOT NULL,
  `is_stop` tinyint(1) unsigned NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `flight`
--
ALTER TABLE `flight`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `flight_info`
--
ALTER TABLE `flight_info`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `flight`
--
ALTER TABLE `flight`
  MODIFY `id` int(10) unsigned NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `flight_info`
--
ALTER TABLE `flight_info`
  MODIFY `id` int(10) unsigned NOT NULL AUTO_INCREMENT;
```