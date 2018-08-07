-- phpMyAdmin SQL Dump
-- version 4.8.2
-- https://www.phpmyadmin.net/
--
-- Host: localhost
-- Generation Time: Aug 07, 2018 at 11:47 AM
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
  `thread_created_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `thread_is_filed` enum('0','1') NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `threads`
--

INSERT INTO `threads` (`id`, `forum_id`, `thread_title`, `thread_body`, `thread_head`, `thread_created_at`, `updated_at`, `thread_is_filed`) VALUES
(46, 1, '反对是否但是', '<p>范德萨发</p>', 'admin', '2018-08-06 13:54:53', '2018-08-06 13:54:53', '0'),
(47, 1, 'vcva', '<p>反对是否</p>', 'admin', '2018-08-06 13:55:14', '2018-08-06 13:55:14', '0'),
(48, 9, 'sfsdfsdf', '<p>规范大概地方规范的</p>', 'admin', '2018-08-06 14:40:18', '2018-08-06 14:40:18', '0'),
(49, 6, '不的大股东风格', '<p>风格的风格的</p>', 'admin', '2018-08-06 14:45:31', '2018-08-06 14:45:31', '0'),
(50, 3, 'fdsfd', '<p>dsfsfdsffsfsfs</p>', 'admin', '2018-08-07 13:21:34', '2018-08-07 13:21:34', '0'),
(51, 3, 'fdsbvc', '<p>fdsfdsbvc</p>', 'admin', '2018-08-07 13:38:54', '2018-08-07 13:38:54', '0'),
(52, 3, '反对是否', '<p>fdsfsfs</p>', 'admin', '2018-08-07 15:19:28', '2018-08-07 15:19:28', '0'),
(53, 7, '反对是否', '<p>fdsfsfsvxvcx</p>', 'admin', '2018-08-07 15:20:38', '2018-08-07 15:20:38', '0'),
(54, 7, 'tertert', '<p>tetertetet</p>', 'admin', '2018-08-07 17:04:04', '2018-08-07 17:04:04', '0');

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
(51, 1),
(51, 3),
(52, 2),
(52, 5),
(53, 4),
(54, 4);

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(10) UNSIGNED NOT NULL,
  `username` varchar(50) NOT NULL,
  `password` varchar(150) NOT NULL,
  `name` varchar(50) NOT NULL,
  `created_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `username`, `password`, `name`, `created_at`) VALUES
(1, 'helloworld', '123456', 'david', '2018-08-02 17:00:07');

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
  ADD KEY `thread_forum_fk` (`forum_id`);

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
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=55;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

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
  ADD CONSTRAINT `thread_forum_fk` FOREIGN KEY (`forum_id`) REFERENCES `forums` (`id`);

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
