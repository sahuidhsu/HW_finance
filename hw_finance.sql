-- phpMyAdmin SQL Dump
-- version 5.1.1
-- https://www.phpmyadmin.net/
--
-- 主机： localhost
-- 生成日期： 2023-08-14 21:45:39
-- 服务器版本： 5.7.39-log
-- PHP 版本： 7.4.30

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- 数据库： `hw_finance`
--

-- --------------------------------------------------------

--
-- 表的结构 `department`
--

CREATE TABLE `department` (
  `id` int(11) NOT NULL,
  `name` varchar(128) NOT NULL,
  `sum` tinyint(4) NOT NULL DEFAULT '1'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- 转存表中的数据 `department`
--

INSERT INTO `department` (`id`, `name`, `sum`) VALUES
(1, '采购部', 1),
(2, '项目部', 1),
(3, '车间', 0),
(4, '公司管理部', 1),
(5, '成本部', 0);

-- --------------------------------------------------------

--
-- 表的结构 `fee`
--

CREATE TABLE `fee` (
  `id` int(11) NOT NULL,
  `name` varchar(128) NOT NULL,
  `department_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- 转存表中的数据 `fee`
--

INSERT INTO `fee` (`id`, `name`, `department_id`) VALUES
(1, '骨架材料', 1),
(2, '覆盖材料', 1),
(3, '传动材料', 1),
(4, '种植系统', 1),
(5, '控制系统', 1),
(6, '运输费', 1),
(7, '土建费', 2),
(8, '安装费', 2),
(9, '业务费', 2),
(10, '其他费', 2),
(11, '管理费用', 4),
(12, '材料费', 3),
(13, '人工费', 3),
(14, '管理费', 3),
(15, '其他费用', 3),
(16, '材料采购', 5),
(18, '其他费', 1);

-- --------------------------------------------------------

--
-- 表的结构 `in_fee`
--

CREATE TABLE `in_fee` (
  `id` int(11) NOT NULL,
  `amount` decimal(9,2) NOT NULL,
  `user_id` int(11) NOT NULL,
  `add_time` datetime NOT NULL,
  `date` date NOT NULL,
  `valid` tinyint(4) NOT NULL DEFAULT '0',
  `project_id` int(11) NOT NULL,
  `comment` varchar(128) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- 表的结构 `out_fee`
--

CREATE TABLE `out_fee` (
  `id` int(11) NOT NULL,
  `amount` decimal(9,2) NOT NULL,
  `fee_id` int(11) NOT NULL,
  `sum` tinyint(4) NOT NULL,
  `user_id` int(11) NOT NULL,
  `add_time` datetime NOT NULL,
  `date` date NOT NULL,
  `valid` tinyint(4) NOT NULL DEFAULT '0',
  `project_id` int(11) NOT NULL,
  `comment` varchar(128) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- 表的结构 `project`
--

CREATE TABLE `project` (
  `id` int(11) NOT NULL,
  `name` varchar(128) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- 转存表中的数据 `project`
--

INSERT INTO `project` (`id`, `name`) VALUES
(1, '初始项目');

-- --------------------------------------------------------

--
-- 表的结构 `setting`
--

CREATE TABLE `setting` (
  `id` int(11) NOT NULL,
  `name` varchar(128) NOT NULL,
  `value` varchar(128) NOT NULL,
  `comment` varchar(128) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- 转存表中的数据 `setting`
--

INSERT INTO `setting` (`id`, `name`, `value`, `comment`) VALUES
(1, '年利率', '9.6', '%');

-- --------------------------------------------------------

--
-- 表的结构 `user`
--

CREATE TABLE `user` (
  `id` int(11) NOT NULL,
  `username` varchar(128) NOT NULL,
  `password` varchar(128) NOT NULL,
  `admin` tinyint(4) NOT NULL DEFAULT '0',
  `department_id` int(11) NOT NULL,
  `valid` tinyint(4) NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- 转存表中的数据 `user`
--

INSERT INTO `user` (`id`, `username`, `password`, `admin`, `department_id`, `valid`) VALUES
(1, 'admin', '$2y$10$bOqr4W49Cb5MlJ9t9y1VFeHmp5rGYFPiI3Wu6LIoFvR2tH5Lc81kq', 1, 4, 1);

--
-- 转储表的索引
--

--
-- 表的索引 `department`
--
ALTER TABLE `department`
  ADD PRIMARY KEY (`id`);

--
-- 表的索引 `fee`
--
ALTER TABLE `fee`
  ADD PRIMARY KEY (`id`);

--
-- 表的索引 `in_fee`
--
ALTER TABLE `in_fee`
  ADD PRIMARY KEY (`id`);

--
-- 表的索引 `out_fee`
--
ALTER TABLE `out_fee`
  ADD PRIMARY KEY (`id`);

--
-- 表的索引 `project`
--
ALTER TABLE `project`
  ADD PRIMARY KEY (`id`);

--
-- 表的索引 `setting`
--
ALTER TABLE `setting`
  ADD PRIMARY KEY (`id`);

--
-- 表的索引 `user`
--
ALTER TABLE `user`
  ADD PRIMARY KEY (`id`);

--
-- 在导出的表使用AUTO_INCREMENT
--

--
-- 使用表AUTO_INCREMENT `department`
--
ALTER TABLE `department`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- 使用表AUTO_INCREMENT `fee`
--
ALTER TABLE `fee`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=19;

--
-- 使用表AUTO_INCREMENT `in_fee`
--
ALTER TABLE `in_fee`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- 使用表AUTO_INCREMENT `out_fee`
--
ALTER TABLE `out_fee`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- 使用表AUTO_INCREMENT `project`
--
ALTER TABLE `project`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- 使用表AUTO_INCREMENT `setting`
--
ALTER TABLE `setting`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- 使用表AUTO_INCREMENT `user`
--
ALTER TABLE `user`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
