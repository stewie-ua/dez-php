-- phpMyAdmin SQL Dump
-- version 3.5.8.1deb1
-- http://www.phpmyadmin.net
--
-- Хост: localhost
-- Время создания: Май 26 2014 г., 12:46
-- Версия сервера: 5.5.32-0ubuntu0.13.04.1
-- Версия PHP: 5.4.9-4ubuntu2.4

SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- База данных: `tasker`
--

-- --------------------------------------------------------

--
-- Структура таблицы `attached_files`
--

CREATE TABLE IF NOT EXISTS `attached_files` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `task_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `file_path` varchar(64) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Структура таблицы `checklists`
--

CREATE TABLE IF NOT EXISTS `checklists` (
  `id` int(8) unsigned NOT NULL AUTO_INCREMENT,
  `task_id` int(8) unsigned NOT NULL,
  `name` varchar(32) NOT NULL,
  `position` int(3) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Структура таблицы `checklist_items`
--

CREATE TABLE IF NOT EXISTS `checklist_items` (
  `id` int(8) unsigned NOT NULL AUTO_INCREMENT,
  `checklist_id` int(8) unsigned NOT NULL,
  `name` varchar(128) NOT NULL DEFAULT '0',
  `made` tinyint(1) unsigned NOT NULL DEFAULT '0',
  `position` int(3) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Структура таблицы `comments`
--

CREATE TABLE IF NOT EXISTS `comments` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(6) NOT NULL,
  `task_id` int(11) NOT NULL,
  `comment` text NOT NULL,
  `created` datetime NOT NULL,
  `updated` datetime NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=10 ;

-- --------------------------------------------------------

--
-- Структура таблицы `labels`
--

CREATE TABLE IF NOT EXISTS `labels` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `project_id` int(8) NOT NULL DEFAULT '-1',
  `name` varchar(32) CHARACTER SET utf32 NOT NULL,
  `color` char(6) CHARACTER SET utf32 NOT NULL DEFAULT 'F0257E',
  PRIMARY KEY (`id`),
  UNIQUE KEY `name` (`name`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=30 ;

-- --------------------------------------------------------

--
-- Структура таблицы `projects`
--

CREATE TABLE IF NOT EXISTS `projects` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(64) NOT NULL,
  `short_code` char(4) NOT NULL,
  `description` text NOT NULL,
  `created` datetime NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `short_code` (`short_code`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=16 ;

-- --------------------------------------------------------

--
-- Структура таблицы `system_auth`
--

CREATE TABLE IF NOT EXISTS `system_auth` (
  `id` int(8) NOT NULL AUTO_INCREMENT,
  `login` varchar(64) NOT NULL,
  `email` varchar(64) NOT NULL DEFAULT '',
  `level_access` varchar(128) NOT NULL DEFAULT '-1',
  `password` varchar(32) NOT NULL,
  `added_at` datetime NOT NULL,
  PRIMARY KEY (`id`),
  KEY `login` (`login`,`password`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=16 ;

-- --------------------------------------------------------

--
-- Структура таблицы `system_sessions`
--

CREATE TABLE IF NOT EXISTS `system_sessions` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(8) NOT NULL,
  `uni_key` varchar(32) NOT NULL,
  `token_key` char(32) NOT NULL,
  `user_agent` varchar(128) NOT NULL DEFAULT '',
  `user_ip` int(10) NOT NULL DEFAULT '0',
  `expired_date` datetime NOT NULL,
  `last_date` datetime DEFAULT '0000-00-00 00:00:00',
  PRIMARY KEY (`id`),
  UNIQUE KEY `user_id` (`user_id`,`uni_key`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=11 ;

-- --------------------------------------------------------

--
-- Структура таблицы `tasks`
--

CREATE TABLE IF NOT EXISTS `tasks` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
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
  `responsible_id` int(6) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=19 ;

-- --------------------------------------------------------

--
-- Структура таблицы `tasks_work_sessions`
--

CREATE TABLE IF NOT EXISTS `tasks_work_sessions` (
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

CREATE TABLE IF NOT EXISTS `users` (
  `auth_id` int(6) unsigned NOT NULL,
  `group_id` int(8) unsigned NOT NULL DEFAULT '0',
  `gender` enum('1','2') DEFAULT '1',
  `first_name` varchar(16) NOT NULL,
  `last_name` varchar(16) DEFAULT NULL,
  `avatar` varchar(128) DEFAULT NULL,
  `phones` varchar(255) DEFAULT NULL,
  `skype` varchar(32) DEFAULT NULL,
  `status` enum('registered','activated','banned') NOT NULL DEFAULT 'registered',
  UNIQUE KEY `auth_id` (`auth_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Структура таблицы `user_groups`
--

CREATE TABLE IF NOT EXISTS `user_groups` (
  `id` int(8) unsigned NOT NULL AUTO_INCREMENT,
  `parent_id` int(8) NOT NULL DEFAULT '-1',
  `name` varchar(32) NOT NULL,
  `alias` varchar(32) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=2 ;

-- --------------------------------------------------------

--
-- Структура таблицы `xref_labels`
--

CREATE TABLE IF NOT EXISTS `xref_labels` (
  `task_id` int(10) unsigned NOT NULL,
  `label_id` int(10) unsigned NOT NULL,
  UNIQUE KEY `task_tags` (`task_id`,`label_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Структура таблицы `xref_task_followers`
--

CREATE TABLE IF NOT EXISTS `xref_task_followers` (
  `user_id` int(8) unsigned NOT NULL,
  `task_id` int(8) unsigned NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Структура таблицы `xref_task_performers`
--

CREATE TABLE IF NOT EXISTS `xref_task_performers` (
  `task_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  UNIQUE KEY `member_tasks` (`task_id`,`user_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
