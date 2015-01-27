-- phpMyAdmin SQL Dump
-- version 4.2.8
-- http://www.phpmyadmin.net
--
-- Хост: localhost
-- Время создания: Янв 24 2015 г., 23:12
-- Версия сервера: 5.5.38-0ubuntu0.14.04.1
-- Версия PHP: 5.5.9-1ubuntu4.3

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- База данных: `dev-site`
--

-- --------------------------------------------------------

--
-- Структура таблицы `acl_groups`
--

CREATE TABLE IF NOT EXISTS `acl_groups` (
`id` int(11) NOT NULL,
  `name` varchar(32) NOT NULL
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8;

--
-- Дамп данных таблицы `acl_groups`
--

INSERT INTO `acl_groups` (`id`, `name`) VALUES
(1, 'Системные'),
(2, 'Дополнительные'),
(3, 'DezAdmin');

-- --------------------------------------------------------

--
-- Структура таблицы `acl_permissions`
--

CREATE TABLE IF NOT EXISTS `acl_permissions` (
`id` int(11) NOT NULL,
  `name` varchar(32) NOT NULL,
  `system_key` varchar(32) NOT NULL,
  `group_id` int(11) NOT NULL
) ENGINE=InnoDB AUTO_INCREMENT=10 DEFAULT CHARSET=utf8;

--
-- Дамп данных таблицы `acl_permissions`
--

INSERT INTO `acl_permissions` (`id`, `name`, `system_key`, `group_id`) VALUES
(1, 'Админ панель', 'ADMIN', 1),
(2, 'Управление пользователями', 'ACCESS_USER', 2),
(3, 'Управление ACL', 'ACL_EDIT', 1),
(4, 'Управление модулями', 'ACCESS_MODULE', 1),
(6, 'Удаление прав доступа', 'ACL_DELETE_PERMISSION', 1),
(7, 'Доступ к панели', 'DEZ_ADMIN_PANEL', 3),
(8, 'Удаление администраторов', 'DELETE_ADMIN', 3),
(9, 'Авто-авторизация', 'AUTH_FROM_PANEL', 3);

-- --------------------------------------------------------

--
-- Структура таблицы `acl_roles`
--

CREATE TABLE IF NOT EXISTS `acl_roles` (
`id` int(11) NOT NULL,
  `name` varchar(32) NOT NULL,
  `level` int(10) unsigned NOT NULL
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8;

--
-- Дамп данных таблицы `acl_roles`
--

INSERT INTO `acl_roles` (`id`, `name`, `level`) VALUES
(1, 'Admin', 990),
(2, 'Moderator', 222),
(3, 'Developer (back-end)', 10),
(4, 'Developer (front-end)', 0),
(5, 'Registered', 66);

-- --------------------------------------------------------

--
-- Структура таблицы `system_auth`
--

CREATE TABLE IF NOT EXISTS `system_auth` (
`id` int(8) NOT NULL,
  `login` varchar(64) NOT NULL,
  `email` varchar(64) NOT NULL DEFAULT '',
  `acl_role_id` int(11) NOT NULL,
  `password` varchar(32) NOT NULL,
  `added_at` datetime NOT NULL
) ENGINE=MyISAM AUTO_INCREMENT=31 DEFAULT CHARSET=utf8;

--
-- Дамп данных таблицы `system_auth`
--

INSERT INTO `system_auth` (`id`, `login`, `email`, `acl_role_id`, `password`, `added_at`) VALUES
(1, 'admin', 'vania.gontarenko@gmail.com', 2, '8e2b0e6d2c6d4bfe4912343ef563346e', '2014-03-02 14:00:29'),
(17, 'dez', '000.stewie@gmail.com', 2, '8e2b0e6d2c6d4bfe4912343ef563346e', '2014-10-16 12:28:34');

-- --------------------------------------------------------

--
-- Структура таблицы `system_sessions`
--

CREATE TABLE IF NOT EXISTS `system_sessions` (
`id` int(11) NOT NULL,
  `user_id` int(8) NOT NULL,
  `uni_key` varchar(32) NOT NULL,
  `token_key` char(32) NOT NULL,
  `user_agent` varchar(128) NOT NULL DEFAULT '',
  `user_ip` int(10) NOT NULL DEFAULT '0',
  `expired_date` datetime NOT NULL,
  `last_date` datetime DEFAULT '0000-00-00 00:00:00'
) ENGINE=MyISAM AUTO_INCREMENT=121 DEFAULT CHARSET=utf8;

--
-- Дамп данных таблицы `system_sessions`
--

INSERT INTO `system_sessions` (`id`, `user_id`, `uni_key`, `token_key`, `user_agent`, `user_ip`, `expired_date`, `last_date`) VALUES
(120, 17, '26300100c0a5b8ac7f40298c431c3dbf', '86bbd9638399e8f09d7bdb6c13e4d4b0', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/37.0.2062.94 Safari/537.36', 2130706433, '2015-02-22 20:38:02', '2015-01-24 23:10:32');

--
-- Индексы сохранённых таблиц
--

--
-- Индексы таблицы `acl_groups`
--
ALTER TABLE `acl_groups`
 ADD PRIMARY KEY (`id`);

--
-- Индексы таблицы `acl_permissions`
--
ALTER TABLE `acl_permissions`
 ADD PRIMARY KEY (`id`);

--
-- Индексы таблицы `acl_roles`
--
ALTER TABLE `acl_roles`
 ADD PRIMARY KEY (`id`);

--
-- Индексы таблицы `system_auth`
--
ALTER TABLE `system_auth`
 ADD PRIMARY KEY (`id`), ADD KEY `login` (`login`,`password`);

--
-- Индексы таблицы `system_sessions`
--
ALTER TABLE `system_sessions`
 ADD PRIMARY KEY (`id`), ADD UNIQUE KEY `user_id` (`user_id`,`uni_key`);

--
-- AUTO_INCREMENT для сохранённых таблиц
--

--
-- AUTO_INCREMENT для таблицы `acl_groups`
--
ALTER TABLE `acl_groups`
MODIFY `id` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=4;
--
-- AUTO_INCREMENT для таблицы `acl_permissions`
--
ALTER TABLE `acl_permissions`
MODIFY `id` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=10;
--
-- AUTO_INCREMENT для таблицы `acl_roles`
--
ALTER TABLE `acl_roles`
MODIFY `id` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=6;
--
-- AUTO_INCREMENT для таблицы `system_auth`
--
ALTER TABLE `system_auth`
MODIFY `id` int(8) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=31;
--
-- AUTO_INCREMENT для таблицы `system_sessions`
--
ALTER TABLE `system_sessions`
MODIFY `id` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=121;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
