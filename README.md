JD Flight
===============

数据库结构：
```
-- phpMyAdmin SQL Dump
-- version 4.4.7
-- http://www.phpmyadmin.net
--
-- Host: localhost
-- Generation Time: 2017-02-23 16:23:28
-- 服务器版本： 10.0.17-MariaDB-log
-- PHP Version: 5.6.9

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
  `arr_date` date NOT NULL,
  `price` int(4) unsigned NOT NULL,
  `create_at` int(10) unsigned NOT NULL
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
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `flight`
--
ALTER TABLE `flight`
  MODIFY `id` int(10) unsigned NOT NULL AUTO_INCREMENT;
```
