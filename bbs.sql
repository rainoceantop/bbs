-- phpMyAdmin SQL Dump
-- version 4.8.1
-- https://www.phpmyadmin.net/
--
-- Host: localhost
-- Generation Time: 2018-08-12 19:36:39
-- 服务器版本： 10.1.33-MariaDB
-- PHP Version: 7.2.6

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `bbs`
--

-- --------------------------------------------------------

--
-- 表的结构 `forums`
--

CREATE TABLE `forums` (
  `id` int(10) UNSIGNED NOT NULL,
  `forum_name` varchar(30) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- 转存表中的数据 `forums`
--

INSERT INTO `forums` (`id`, `forum_name`) VALUES
(7, 'BUG'),
(8, '哈哈');

-- --------------------------------------------------------

--
-- 表的结构 `replies`
--

CREATE TABLE `replies` (
  `id` int(10) UNSIGNED NOT NULL,
  `thread_id` int(10) UNSIGNED NOT NULL,
  `replied_body` varchar(5000) NOT NULL,
  `replied_index` int(10) UNSIGNED NOT NULL DEFAULT '0',
  `from_user_id` int(10) UNSIGNED NOT NULL,
  `to_user_id` int(10) UNSIGNED NOT NULL,
  `replied_time` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- 转存表中的数据 `replies`
--

INSERT INTO `replies` (`id`, `thread_id`, `replied_body`, `replied_index`, `from_user_id`, `to_user_id`, `replied_time`) VALUES
(25, 5, 'fdsfsdf', 0, 4, 4, '2018-08-12 04:54:39'),
(26, 5, 'fsdfsdfsdf', 25, 4, 4, '2018-08-12 04:54:46'),
(32, 5, 'jghjghj', 0, 4, 4, '2018-08-12 23:52:35'),
(33, 5, 'yreyetyery', 0, 5, 4, '2018-08-13 00:08:16'),
(34, 5, 'khjkhjk', 0, 5, 4, '2018-08-13 00:09:20');

-- --------------------------------------------------------

--
-- 表的结构 `tags`
--

CREATE TABLE `tags` (
  `id` int(10) UNSIGNED NOT NULL,
  `forum_id` int(10) UNSIGNED NOT NULL,
  `tag_group_id` int(10) UNSIGNED NOT NULL,
  `tag_name` varchar(30) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- 转存表中的数据 `tags`
--

INSERT INTO `tags` (`id`, `forum_id`, `tag_group_id`, `tag_name`) VALUES
(1, 7, 1, 'bug1'),
(2, 7, 1, 'bug2'),
(3, 8, 2, '哈哈1'),
(4, 8, 2, '哈哈2'),
(5, 7, 3, 'test');

-- --------------------------------------------------------

--
-- 表的结构 `tag_groups`
--

CREATE TABLE `tag_groups` (
  `id` int(10) UNSIGNED NOT NULL,
  `forum_id` int(10) UNSIGNED NOT NULL,
  `tag_group_name` varchar(30) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- 转存表中的数据 `tag_groups`
--

INSERT INTO `tag_groups` (`id`, `forum_id`, `tag_group_name`) VALUES
(1, 7, 'PHP'),
(2, 8, '哈哈哈'),
(3, 7, 'JAVA');

-- --------------------------------------------------------

--
-- 表的结构 `threads`
--

CREATE TABLE `threads` (
  `id` int(10) UNSIGNED NOT NULL,
  `forum_id` int(10) UNSIGNED NOT NULL,
  `thread_title` varchar(150) NOT NULL,
  `thread_body` text NOT NULL,
  `thread_head` varchar(50) NOT NULL,
  `head_id` int(10) UNSIGNED NOT NULL,
  `thread_created_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `last_replied_user` int(10) UNSIGNED NOT NULL DEFAULT '0',
  `thread_is_filed` enum('0','1') NOT NULL DEFAULT '0',
  `updated_reason` varchar(100) NOT NULL DEFAULT 'none',
  `last_replied_time` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- 转存表中的数据 `threads`
--

INSERT INTO `threads` (`id`, `forum_id`, `thread_title`, `thread_body`, `thread_head`, `head_id`, `thread_created_at`, `updated_at`, `last_replied_user`, `thread_is_filed`, `updated_reason`, `last_replied_time`) VALUES
(5, 7, '价格和价格和', '<p>fsdfsdfsdfsdf</p>', '卡西莫多', 4, '2018-08-12 04:54:33', '2018-08-12 04:54:33', 5, '0', 'none', '2018-08-13 00:09:20'),
(6, 7, ';jhjgjg', '<p>hjgjjhgj</p>', '不蓝等', 5, '2018-08-13 00:10:43', '2018-08-13 00:10:43', 0, '0', 'none', '2018-08-13 00:10:43'),
(7, 7, 'u有图', '<p>u有图图</p>', '不蓝等', 5, '2018-08-13 00:21:21', '2018-08-13 00:21:21', 0, '0', 'none', '2018-08-13 00:21:21'),
(8, 7, 'ouioui', '<p>ouiouio</p>', '不蓝等', 5, '2018-08-13 00:46:55', '2018-08-13 00:46:55', 0, '0', 'none', '2018-08-13 00:46:55'),
(9, 7, 'u有突然他们', '<p>u他与人</p>', '不蓝等', 5, '2018-08-13 01:04:54', '2018-08-13 01:04:54', 0, '0', 'none', '2018-08-13 01:04:54');

-- --------------------------------------------------------

--
-- 表的结构 `thread_tag_ref`
--

CREATE TABLE `thread_tag_ref` (
  `thread_id` int(10) UNSIGNED NOT NULL,
  `tag_id` int(10) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- 转存表中的数据 `thread_tag_ref`
--

INSERT INTO `thread_tag_ref` (`thread_id`, `tag_id`) VALUES
(7, 2),
(8, 1),
(8, 5),
(9, 1),
(9, 5);

-- --------------------------------------------------------

--
-- 表的结构 `users`
--

CREATE TABLE `users` (
  `id` int(10) UNSIGNED NOT NULL,
  `username` varchar(50) NOT NULL,
  `password` varchar(150) NOT NULL,
  `name` varchar(50) NOT NULL,
  `avatar` varchar(300) NOT NULL DEFAULT 'imgs/1.jpg',
  `created_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `last_online` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- 转存表中的数据 `users`
--

INSERT INTO `users` (`id`, `username`, `password`, `name`, `avatar`, `created_at`, `last_online`) VALUES
(4, 'helloworld', '123456', '卡西莫多', 'imgs/2.jpg', '2018-08-10 12:02:33', '2018-08-12 23:42:36'),
(5, 'admin', '123456', '不蓝等', 'imgs/1.jpg', '2018-08-13 00:07:58', '2018-08-13 00:08:11');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `forums`
--
ALTER TABLE `forums`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `replies`
--
ALTER TABLE `replies`
  ADD PRIMARY KEY (`id`),
  ADD KEY `thread_id_ref` (`thread_id`),
  ADD KEY `from_user_id` (`from_user_id`),
  ADD KEY `to_user_id` (`to_user_id`);

--
-- Indexes for table `tags`
--
ALTER TABLE `tags`
  ADD PRIMARY KEY (`id`),
  ADD KEY `ft_fk` (`forum_id`),
  ADD KEY `ttg_fk` (`tag_group_id`);

--
-- Indexes for table `tag_groups`
--
ALTER TABLE `tag_groups`
  ADD PRIMARY KEY (`id`),
  ADD KEY `forum_tag_group_fk` (`forum_id`);

--
-- Indexes for table `threads`
--
ALTER TABLE `threads`
  ADD PRIMARY KEY (`id`),
  ADD KEY `thread_forum_fk` (`forum_id`),
  ADD KEY `thread_user_fk` (`head_id`);

--
-- Indexes for table `thread_tag_ref`
--
ALTER TABLE `thread_tag_ref`
  ADD KEY `tag_id_fk` (`tag_id`),
  ADD KEY `t_id_fk` (`thread_id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `username` (`username`),
  ADD UNIQUE KEY `name` (`name`);

--
-- 在导出的表使用AUTO_INCREMENT
--

--
-- 使用表AUTO_INCREMENT `forums`
--
ALTER TABLE `forums`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- 使用表AUTO_INCREMENT `replies`
--
ALTER TABLE `replies`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=35;

--
-- 使用表AUTO_INCREMENT `tags`
--
ALTER TABLE `tags`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- 使用表AUTO_INCREMENT `tag_groups`
--
ALTER TABLE `tag_groups`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- 使用表AUTO_INCREMENT `threads`
--
ALTER TABLE `threads`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- 使用表AUTO_INCREMENT `users`
--
ALTER TABLE `users`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- 限制导出的表
--

--
-- 限制表 `replies`
--
ALTER TABLE `replies`
  ADD CONSTRAINT `replies_ibfk_1` FOREIGN KEY (`from_user_id`) REFERENCES `users` (`id`),
  ADD CONSTRAINT `replies_ibfk_2` FOREIGN KEY (`to_user_id`) REFERENCES `users` (`id`),
  ADD CONSTRAINT `thread_id_ref` FOREIGN KEY (`thread_id`) REFERENCES `threads` (`id`) ON DELETE CASCADE;

--
-- 限制表 `tags`
--
ALTER TABLE `tags`
  ADD CONSTRAINT `ft_fk` FOREIGN KEY (`forum_id`) REFERENCES `tag_groups` (`forum_id`) ON DELETE CASCADE,
  ADD CONSTRAINT `tags_ibfk_2` FOREIGN KEY (`tag_group_id`) REFERENCES `tag_groups` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `ttg_fk` FOREIGN KEY (`tag_group_id`) REFERENCES `tag_groups` (`id`) ON DELETE CASCADE;

--
-- 限制表 `tag_groups`
--
ALTER TABLE `tag_groups`
  ADD CONSTRAINT `forum_tag_group_fk` FOREIGN KEY (`forum_id`) REFERENCES `forums` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- 限制表 `threads`
--
ALTER TABLE `threads`
  ADD CONSTRAINT `thread_forum_fk` FOREIGN KEY (`forum_id`) REFERENCES `forums` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `thread_user_fk` FOREIGN KEY (`head_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- 限制表 `thread_tag_ref`
--
ALTER TABLE `thread_tag_ref`
  ADD CONSTRAINT `t_id_fk` FOREIGN KEY (`thread_id`) REFERENCES `threads` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
