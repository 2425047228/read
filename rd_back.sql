-- phpMyAdmin SQL Dump
-- version 4.6.5.2
-- https://www.phpmyadmin.net/
--
-- Host: rm-2ze1lk7efc953vviw.mysql.rds.aliyuncs.com
-- Generation Time: 2017-03-09 06:56:49
-- 服务器版本： 5.6.16-log
-- PHP Version: 5.6.22

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `dev_read`
--

-- --------------------------------------------------------

--
-- 表的结构 `rd_back`
--

CREATE TABLE `rd_back` (
  `id` int(5) NOT NULL COMMENT '编号',
  `u_id` int(5) NOT NULL COMMENT '用户id(user里的id)',
  `content` varchar(255) NOT NULL COMMENT '反馈内容',
  `time` int(11) NOT NULL COMMENT '反馈时间',
  `state` int(1) NOT NULL DEFAULT '0' COMMENT '是否查看（1，是。0，否。默认0）'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='用户反馈表';

--
-- 转存表中的数据 `rd_back`
--

INSERT INTO `rd_back` (`id`, `u_id`, `content`, `time`, `state`) VALUES
(1, 4, '你好', 1489029692, 0);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `rd_back`
--
ALTER TABLE `rd_back`
  ADD PRIMARY KEY (`id`);

--
-- 在导出的表使用AUTO_INCREMENT
--

--
-- 使用表AUTO_INCREMENT `rd_back`
--
ALTER TABLE `rd_back`
  MODIFY `id` int(5) NOT NULL AUTO_INCREMENT COMMENT '编号', AUTO_INCREMENT=2;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
