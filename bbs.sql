-- phpMyAdmin SQL Dump
-- version 4.8.2
-- https://www.phpmyadmin.net/
--
-- Host: localhost
-- Generation Time: Aug 08, 2018 at 11:24 AM
-- Server version: 10.1.34-MariaDB
-- PHP Version: 7.2.7

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
-- Table structure for table `forums`
--

CREATE TABLE `forums` (
  `id` int(10) UNSIGNED NOT NULL,
  `forum_name` varchar(30) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `forums`
--

INSERT INTO `forums` (`id`, `forum_name`) VALUES
(1, '程序发布'),
(2, '安装'),
(3, 'BUG'),
(4, '插件'),
(5, '模板'),
(6, 'WEB开发'),
(7, 'PHP'),
(8, '测试'),
(9, '灌水'),
(10, 'IP查询');

-- --------------------------------------------------------

--
-- Table structure for table `replies`
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

-- --------------------------------------------------------

--
-- Table structure for table `tags`
--

CREATE TABLE `tags` (
  `id` int(10) UNSIGNED NOT NULL,
  `forum_id` int(10) UNSIGNED NOT NULL,
  `tag_group_id` int(10) UNSIGNED NOT NULL,
  `tag_name` varchar(30) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `tags`
--

INSERT INTO `tags` (`id`, `forum_id`, `tag_group_id`, `tag_name`) VALUES
(1, 3, 1, 'nerver'),
(2, 3, 1, 'give up'),
(3, 3, 2, '侠盗勇士'),
(4, 7, 4, 'QQ飞车'),
(5, 3, 2, 'hello'),
(6, 3, 2, 'world');

-- --------------------------------------------------------

--
-- Table structure for table `tag_groups`
--

CREATE TABLE `tag_groups` (
  `id` int(10) UNSIGNED NOT NULL,
  `forum_id` int(10) UNSIGNED NOT NULL,
  `tag_group_name` varchar(30) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `tag_groups`
--

INSERT INTO `tag_groups` (`id`, `forum_id`, `tag_group_name`) VALUES
(1, 3, '高山上'),
(2, 3, '雨林里'),
(3, 5, '屋檐下'),
(4, 7, '天际边'),
(5, 2, '追风少年'),
(6, 1, 'test');

-- --------------------------------------------------------

--
-- Table structure for table `threads`
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
  `thread_is_filed` enum('0','1') NOT NULL DEFAULT '0',
  `updated_reason` varchar(100) NOT NULL DEFAULT 'none'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `threads`
--

INSERT INTO `threads` (`id`, `forum_id`, `thread_title`, `thread_body`, `thread_head`, `head_id`, `thread_created_at`, `updated_at`, `thread_is_filed`, `updated_reason`) VALUES
(56, 1, 'Hello world', 'foo bar foo bar little star', '乌托邦', 3, '2018-08-08 14:39:01', '2018-08-08 16:59:23', '0', 'test this update'),
(57, 1, '反对是否', '<p>fsdfsf</p>', '乌托邦', 3, '2018-08-08 14:49:55', '2018-08-08 14:49:55', '0', 'none'),
(58, 1, 'gfdgfdgdf', '<p>gdfgdgfdg</p>', '乌托邦', 3, '2018-08-08 15:16:23', '2018-08-08 15:16:23', '0', 'none'),
(59, 3, 'fdsfdsf', '<p>fdsfsdf</p>', '你好', 4, '2018-08-08 15:25:14', '2018-08-08 15:25:14', '0', 'none');

-- --------------------------------------------------------

--
-- Table structure for table `thread_tag_ref`
--

CREATE TABLE `thread_tag_ref` (
  `thread_id` int(10) UNSIGNED NOT NULL,
  `tag_id` int(10) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `thread_tag_ref`
--

INSERT INTO `thread_tag_ref` (`thread_id`, `tag_id`) VALUES
(59, 1),
(59, 3);

-- --------------------------------------------------------

--
-- Table structure for table `users`
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
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `username`, `password`, `name`, `avatar`, `created_at`, `last_online`) VALUES
(3, 'superAdmin', '123456', '乌托邦', 'imgs/3.jpg', '2018-08-08 13:20:13', '2018-08-08 16:04:48'),
(4, 'hello', '123456', '你好', 'imgs/1.jpg', '2018-08-08 15:24:44', '2018-08-08 10:01:02');

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
  ADD KEY `thread_id_ref` (`thread_id`);

--
-- Indexes for table `tags`
--
ALTER TABLE `tags`
  ADD PRIMARY KEY (`id`),
  ADD KEY `tag_forum_fk` (`forum_id`),
  ADD KEY `tag_group_fk` (`tag_group_id`);

--
-- Indexes for table `tag_groups`
--
ALTER TABLE `tag_groups`
  ADD PRIMARY KEY (`id`);

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
  ADD KEY `t_id_fk` (`thread_id`),
  ADD KEY `tag_id_fk` (`tag_id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `username` (`username`),
  ADD UNIQUE KEY `name` (`name`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `forums`
--
ALTER TABLE `forums`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT for table `replies`
--
ALTER TABLE `replies`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `tags`
--
ALTER TABLE `tags`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `tag_groups`
--
ALTER TABLE `tag_groups`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `threads`
--
ALTER TABLE `threads`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=60;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `replies`
--
ALTER TABLE `replies`
  ADD CONSTRAINT `thread_id_ref` FOREIGN KEY (`thread_id`) REFERENCES `threads` (`id`);

--
-- Constraints for table `tags`
--
ALTER TABLE `tags`
  ADD CONSTRAINT `tag_forum_fk` FOREIGN KEY (`forum_id`) REFERENCES `forums` (`id`),
  ADD CONSTRAINT `tag_group_fk` FOREIGN KEY (`tag_group_id`) REFERENCES `tag_groups` (`id`);

--
-- Constraints for table `tag_groups`
--
ALTER TABLE `tag_groups`
  ADD CONSTRAINT `tag_groups_forum_fk` FOREIGN KEY (`id`) REFERENCES `forums` (`id`);

--
-- Constraints for table `threads`
--
ALTER TABLE `threads`
  ADD CONSTRAINT `thread_forum_fk` FOREIGN KEY (`forum_id`) REFERENCES `forums` (`id`),
  ADD CONSTRAINT `thread_user_fk` FOREIGN KEY (`head_id`) REFERENCES `users` (`id`);

--
-- Constraints for table `thread_tag_ref`
--
ALTER TABLE `thread_tag_ref`
  ADD CONSTRAINT `t_id_fk` FOREIGN KEY (`thread_id`) REFERENCES `threads` (`id`),
  ADD CONSTRAINT `tag_id_fk` FOREIGN KEY (`tag_id`) REFERENCES `tags` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
