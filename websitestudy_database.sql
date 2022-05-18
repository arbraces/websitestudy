-- phpMyAdmin SQL Dump
-- version 5.1.1
-- https://www.phpmyadmin.net/
--
-- 主机： 127.0.0.1
-- 生成日期： 2022-05-18 02:40:29
-- 服务器版本： 10.4.21-MariaDB
-- PHP 版本： 7.4.25

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- 数据库： `add`
--

-- --------------------------------------------------------

--
-- 表的结构 `study_sort`
--

CREATE TABLE `study_sort` (
  `id` int(11) NOT NULL,
  `sort` char(255) NOT NULL,
  `img` char(255) NOT NULL,
  `name` char(255) NOT NULL,
  `ctetime` char(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- 表的结构 `study_user`
--

CREATE TABLE `study_user` (
  `id` int(11) NOT NULL,
  `username` char(18) NOT NULL COMMENT '账号(最长18位)',
  `password` char(50) NOT NULL COMMENT '密码',
  `is_admin` tinyint(2) NOT NULL COMMENT '是否是管理员',
  `token` char(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- 表的结构 `study_video`
--

CREATE TABLE `study_video` (
  `id` int(11) NOT NULL,
  `sort_id` int(11) NOT NULL,
  `name` char(255) NOT NULL,
  `url` char(255) NOT NULL,
  `is_finish` char(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- 转储表的索引
--

--
-- 表的索引 `study_sort`
--
ALTER TABLE `study_sort`
  ADD PRIMARY KEY (`id`);

--
-- 表的索引 `study_user`
--
ALTER TABLE `study_user`
  ADD PRIMARY KEY (`id`);

--
-- 表的索引 `study_video`
--
ALTER TABLE `study_video`
  ADD PRIMARY KEY (`id`);

--
-- 在导出的表使用AUTO_INCREMENT
--

--
-- 使用表AUTO_INCREMENT `study_sort`
--
ALTER TABLE `study_sort`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- 使用表AUTO_INCREMENT `study_user`
--
ALTER TABLE `study_user`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- 使用表AUTO_INCREMENT `study_video`
--
ALTER TABLE `study_video`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
