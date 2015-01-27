-- phpMyAdmin SQL Dump
-- version 4.2.8
-- http://www.phpmyadmin.net
--
-- Хост: localhost
-- Время создания: Янв 24 2015 г., 23:30
-- Версия сервера: 5.5.38-0ubuntu0.14.04.1
-- Версия PHP: 5.5.9-1ubuntu4.3

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- База данных: `dev-tasker`
--

-- --------------------------------------------------------

--
-- Структура таблицы `attached_files`
--

CREATE TABLE IF NOT EXISTS `dev_attached_files` (
`id` int(11) NOT NULL,
  `task_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `file_path` varchar(64) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Структура таблицы `checklists`
--

CREATE TABLE IF NOT EXISTS `dev_checklists` (
`id` int(8) unsigned NOT NULL,
  `task_id` int(8) unsigned NOT NULL,
  `name` varchar(32) NOT NULL,
  `position` int(3) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Структура таблицы `checklist_items`
--

CREATE TABLE IF NOT EXISTS `dev_checklist_items` (
`id` int(8) unsigned NOT NULL,
  `checklist_id` int(8) unsigned NOT NULL,
  `name` varchar(128) NOT NULL DEFAULT '0',
  `made` tinyint(1) unsigned NOT NULL DEFAULT '0',
  `position` int(3) NOT NULL DEFAULT '0'
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Структура таблицы `comments`
--

CREATE TABLE IF NOT EXISTS `dev_comments` (
`id` int(11) NOT NULL,
  `user_id` int(6) NOT NULL,
  `task_id` int(11) NOT NULL,
  `comment` text NOT NULL,
  `created` datetime NOT NULL,
  `updated` datetime NOT NULL
) ENGINE=MyISAM AUTO_INCREMENT=10 DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Структура таблицы `labels`
--

CREATE TABLE IF NOT EXISTS `dev_labels` (
`id` int(10) unsigned NOT NULL,
  `project_id` int(8) NOT NULL DEFAULT '-1',
  `name` varchar(32) CHARACTER SET utf32 NOT NULL,
  `color` char(6) CHARACTER SET utf32 NOT NULL DEFAULT 'F0257E'
) ENGINE=MyISAM AUTO_INCREMENT=30 DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Структура таблицы `projects`
--

CREATE TABLE IF NOT EXISTS `dev_projects` (
`id` int(11) NOT NULL,
  `name` varchar(64) NOT NULL,
  `short_code` char(4) NOT NULL,
  `description` text NOT NULL,
  `created` datetime NOT NULL
) ENGINE=MyISAM AUTO_INCREMENT=16 DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Структура таблицы `tasks`
--

CREATE TABLE IF NOT EXISTS `dev_tasks` (
`id` int(11) NOT NULL,
  `project_id` int(6) NOT NULL,
  `author_id` int(6) NOT NULL,
  `name` varchar(32) NOT NULL,
  `description` text NOT NULL,
  `status` enum('new','reopen','wait','close') NOT NULL DEFAULT 'new',
  `priority` enum('0','1','2','3','4','5') NOT NULL DEFAULT '0',
  `notify_date` datetime DEFAULT '0000-00-00 00:00:00',
  `redline_date` datetime DEFAULT '0000-00-00 00:00:00',
  `deadline_date` datetime DEFAULT '0000-00-00 00:00:00',
  `created` datetime NOT NULL,
  `updated` datetime NOT NULL,
  `update_by` int(6) NOT NULL DEFAULT '0',
  `responsible_id` int(6) NOT NULL DEFAULT '0'
) ENGINE=MyISAM AUTO_INCREMENT=19 DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Структура таблицы `tasks_work_sessions`
--

CREATE TABLE IF NOT EXISTS `dev_tasks_work_sessions` (
  `user_id` int(6) NOT NULL,
  `task_id` int(6) NOT NULL,
  `date_start` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `date_finish` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `comment` text NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Структура таблицы `users`
--

CREATE TABLE IF NOT EXISTS `dev_users` (
  `auth_id` int(6) unsigned NOT NULL,
  `group_id` int(8) unsigned NOT NULL DEFAULT '0',
  `gender` enum('1','2') DEFAULT '1',
  `first_name` varchar(16) NOT NULL,
  `last_name` varchar(16) DEFAULT NULL,
  `avatar` varchar(128) DEFAULT NULL,
  `phones` varchar(255) DEFAULT NULL,
  `skype` varchar(32) DEFAULT NULL,
  `status` enum('registered','activated','banned') NOT NULL DEFAULT 'registered'
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Структура таблицы `user_groups`
--

CREATE TABLE IF NOT EXISTS `dev_user_groups` (
`id` int(8) unsigned NOT NULL,
  `parent_id` int(8) NOT NULL DEFAULT '-1',
  `name` varchar(32) NOT NULL,
  `alias` varchar(32) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Структура таблицы `xref_labels`
--

CREATE TABLE IF NOT EXISTS `dev_xref_labels` (
  `task_id` int(10) unsigned NOT NULL,
  `label_id` int(10) unsigned NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Структура таблицы `xref_task_followers`
--

CREATE TABLE IF NOT EXISTS `dev_xref_task_followers` (
  `user_id` int(8) unsigned NOT NULL,
  `task_id` int(8) unsigned NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Структура таблицы `xref_task_performers`
--

CREATE TABLE IF NOT EXISTS `dev_xref_task_performers` (
  `task_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

--
-- Индексы сохранённых таблиц
--

--
-- Индексы таблицы `attached_files`
--
ALTER TABLE `dev_attached_files`
 ADD PRIMARY KEY (`id`);

--
-- Индексы таблицы `checklists`
--
ALTER TABLE `dev_checklists`
 ADD PRIMARY KEY (`id`);

--
-- Индексы таблицы `checklist_items`
--
ALTER TABLE `dev_checklist_items`
 ADD PRIMARY KEY (`id`);

--
-- Индексы таблицы `comments`
--
ALTER TABLE `dev_comments`
 ADD PRIMARY KEY (`id`);

--
-- Индексы таблицы `labels`
--
ALTER TABLE `dev_labels`
 ADD PRIMARY KEY (`id`), ADD UNIQUE KEY `name` (`name`);

--
-- Индексы таблицы `projects`
--
ALTER TABLE `dev_projects`
 ADD PRIMARY KEY (`id`), ADD UNIQUE KEY `short_code` (`short_code`);

--
-- Индексы таблицы `tasks`
--
ALTER TABLE `dev_tasks`
 ADD PRIMARY KEY (`id`);

--
-- Индексы таблицы `users`
--
ALTER TABLE `dev_users`
 ADD UNIQUE KEY `auth_id` (`auth_id`);

--
-- Индексы таблицы `user_groups`
--
ALTER TABLE `dev_user_groups`
 ADD PRIMARY KEY (`id`);

--
-- Индексы таблицы `xref_labels`
--
ALTER TABLE `dev_xref_labels`
 ADD UNIQUE KEY `task_tags` (`task_id`,`label_id`);

--
-- Индексы таблицы `xref_task_performers`
--
ALTER TABLE `dev_xref_task_performers`
 ADD UNIQUE KEY `member_tasks` (`task_id`,`user_id`);

--
-- AUTO_INCREMENT для сохранённых таблиц
--

--
-- AUTO_INCREMENT для таблицы `attached_files`
--
ALTER TABLE `dev_attached_files`
MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT для таблицы `checklists`
--
ALTER TABLE `dev_checklists`
MODIFY `id` int(8) unsigned NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT для таблицы `checklist_items`
--
ALTER TABLE `dev_checklist_items`
MODIFY `id` int(8) unsigned NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT для таблицы `comments`
--
ALTER TABLE `dev_comments`
MODIFY `id` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=1;
--
-- AUTO_INCREMENT для таблицы `labels`
--
ALTER TABLE `dev_labels`
MODIFY `id` int(10) unsigned NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=1;
--
-- AUTO_INCREMENT для таблицы `projects`
--
ALTER TABLE `dev_projects`
MODIFY `id` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=1;
--
-- AUTO_INCREMENT для таблицы `tasks`
--
ALTER TABLE `dev_tasks`
MODIFY `id` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=1;
--
-- AUTO_INCREMENT для таблицы `user_groups`
--
ALTER TABLE `dev_user_groups`
MODIFY `id` int(8) unsigned NOT NULL AUTO_INCREMENT;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
