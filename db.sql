-- phpMyAdmin SQL Dump
-- version 4.0.10deb1
-- http://www.phpmyadmin.net
--
-- Хост: localhost
-- Время создания: Янв 23 2017 г., 11:12
-- Версия сервера: 5.5.47-0ubuntu0.14.04.1
-- Версия PHP: 5.5.9-1ubuntu4.14

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- База данных: `sample_cms`
--

-- --------------------------------------------------------

--
-- Структура таблицы `blogs`
--

CREATE TABLE IF NOT EXISTS `blogs` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `uid` int(11) NOT NULL,
  `title_kz` varchar(32) NOT NULL,
  `title_ru` varchar(32) NOT NULL,
  `title_en` varchar(32) NOT NULL,
  `state` tinyint(1) unsigned NOT NULL DEFAULT '1',
  PRIMARY KEY (`id`),
  KEY `uid` (`uid`),
  KEY `state` (`state`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=2 ;

--
-- Дамп данных таблицы `blogs`
--

INSERT INTO `blogs` (`id`, `uid`, `title_kz`, `title_ru`, `title_en`, `state`) VALUES
(1, 1, 'Сайт жасау', 'Сайтостроение', 'Sitemaking', 1);

-- --------------------------------------------------------

--
-- Структура таблицы `blog_comments`
--

CREATE TABLE IF NOT EXISTS `blog_comments` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `blogpost_id` int(11) NOT NULL,
  `title` varchar(100) NOT NULL,
  `username` varchar(32) NOT NULL,
  `email` varchar(32) NOT NULL,
  `comment` text NOT NULL,
  `comment_date` date NOT NULL,
  `answer` text NOT NULL,
  `answer_date` date NOT NULL,
  `state` tinyint(1) unsigned NOT NULL DEFAULT '1',
  PRIMARY KEY (`id`),
  KEY `state` (`state`),
  KEY `blogpost_id` (`blogpost_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=3 ;

--
-- Дамп данных таблицы `blog_comments`
--

INSERT INTO `blog_comments` (`id`, `blogpost_id`, `title`, `username`, `email`, `comment`, `comment_date`, `answer`, `answer_date`, `state`) VALUES
(1, 1, 'test', 'Icon', '', 'test', '2016-10-28', 'ok', '2016-10-28', 1),
(2, 1, 'Test', 'sdf', '', 'sdf', '2016-10-28', '', '0000-00-00', 1);

-- --------------------------------------------------------

--
-- Структура таблицы `blog_posts`
--

CREATE TABLE IF NOT EXISTS `blog_posts` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `blog_id` int(11) NOT NULL,
  `title_kz` varchar(128) NOT NULL,
  `title_ru` varchar(128) NOT NULL,
  `title_en` varchar(128) NOT NULL,
  `content_kz` text NOT NULL,
  `content_ru` text NOT NULL,
  `content_en` text NOT NULL,
  `created` date NOT NULL,
  `state` tinyint(1) unsigned NOT NULL DEFAULT '1',
  PRIMARY KEY (`id`),
  KEY `state` (`state`),
  KEY `blog_id` (`blog_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=2 ;

--
-- Дамп данных таблицы `blog_posts`
--

INSERT INTO `blog_posts` (`id`, `blog_id`, `title_kz`, `title_ru`, `title_en`, `content_kz`, `content_ru`, `content_en`, `created`, `state`) VALUES
(1, 1, 'Кіріспе', 'Введение', 'Intro', 'Lorem ipsum', 'Dolor', 'Sit Amet', '2016-10-04', 1);

-- --------------------------------------------------------

--
-- Структура таблицы `categories`
--

CREATE TABLE IF NOT EXISTS `categories` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `title_kz` varchar(255) NOT NULL,
  `title_ru` varchar(255) NOT NULL,
  `title_en` varchar(255) NOT NULL,
  `parent_id` int(11) NOT NULL,
  `state` tinyint(1) unsigned NOT NULL DEFAULT '1',
  `params` text NOT NULL,
  `sort_order` int(11) NOT NULL,
  `image_id` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `parent_id` (`parent_id`),
  KEY `state` (`state`),
  KEY `sort_order` (`sort_order`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=3 ;

--
-- Дамп данных таблицы `categories`
--

INSERT INTO `categories` (`id`, `title_kz`, `title_ru`, `title_en`, `parent_id`, `state`, `params`, `sort_order`, `image_id`) VALUES
(2, ' Категориясыз', 'Без категории', ' Uncategorised', 1, 1, '', 0, 0);

-- --------------------------------------------------------

--
-- Структура таблицы `contents`
--

CREATE TABLE IF NOT EXISTS `contents` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `title_kz` varchar(255) NOT NULL,
  `title_ru` varchar(255) NOT NULL,
  `title_en` varchar(255) NOT NULL,
  `intro_kz` mediumtext NOT NULL,
  `intro_ru` mediumtext NOT NULL,
  `intro_en` mediumtext NOT NULL,
  `fulltext_kz` mediumtext NOT NULL,
  `fulltext_ru` mediumtext NOT NULL,
  `fulltext_en` mediumtext NOT NULL,
  `state` tinyint(1) unsigned NOT NULL DEFAULT '1',
  `created_date` date NOT NULL,
  `category_id` int(10) unsigned NOT NULL,
  `image_id` int(11) NOT NULL,
  `sort_order` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `state` (`state`),
  KEY `category_id` (`category_id`),
  KEY `image_id` (`image_id`),
  KEY `sort_order` (`sort_order`),
  KEY `category_id_2` (`category_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Структура таблицы `galleries`
--

CREATE TABLE IF NOT EXISTS `galleries` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `title_kz` varchar(255) NOT NULL,
  `title_ru` varchar(255) NOT NULL,
  `title_en` varchar(255) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

--
-- Дамп данных таблицы `galleries`
--

INSERT INTO `galleries` (`id`, `title_kz`, `title_ru`, `title_en`) VALUES
(0, 'Суретсіз', 'Без рисунка', 'No images');

-- --------------------------------------------------------

--
-- Структура таблицы `images`
--

CREATE TABLE IF NOT EXISTS `images` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `url` varchar(255) NOT NULL,
  `preview` varchar(255) NOT NULL,
  `gallery_id` int(11) NOT NULL,
  `caption_kz` varchar(255) NOT NULL,
  `caption_ru` varchar(255) NOT NULL,
  `caption_en` varchar(255) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `gallery_id` (`gallery_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Структура таблицы `menus`
--

CREATE TABLE IF NOT EXISTS `menus` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `menu_type` varchar(24) NOT NULL,
  `title_kz` varchar(255) NOT NULL,
  `title_ru` varchar(255) NOT NULL,
  `title_en` varchar(255) NOT NULL,
  `item_type` int(11) NOT NULL,
  `parent_id` int(11) NOT NULL,
  `link` varchar(255) NOT NULL,
  `image_id` int(11) NOT NULL,
  `sort_order` int(11) NOT NULL,
  `state` tinyint(1) unsigned NOT NULL DEFAULT '1',
  PRIMARY KEY (`id`),
  KEY `sort_order` (`sort_order`),
  KEY `state` (`state`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=109 ;

--
-- Дамп данных таблицы `menus`
--

INSERT INTO `menus` (`id`, `menu_type`, `title_kz`, `title_ru`, `title_en`, `item_type`, `parent_id`, `link`, `image_id`, `sort_order`, `state`) VALUES
(108, 'topmenu', 'О компании', 'Главная страница', 'Main Page', 2, 1, 'article/1', 0, 1, 1);

-- --------------------------------------------------------

--
-- Структура таблицы `menutypes`
--

CREATE TABLE IF NOT EXISTS `menutypes` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `type_name` varchar(20) NOT NULL,
  `type_title` varchar(20) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `type_name` (`type_name`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=3 ;

--
-- Дамп данных таблицы `menutypes`
--

INSERT INTO `menutypes` (`id`, `type_name`, `type_title`) VALUES
(2, 'topmenu', 'Верхнее меню');

-- --------------------------------------------------------

--
-- Структура таблицы `states`
--

CREATE TABLE IF NOT EXISTS `states` (
  `id` tinyint(1) unsigned NOT NULL AUTO_INCREMENT,
  `title` varchar(255) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=2 ;

--
-- Дамп данных таблицы `states`
--

INSERT INTO `states` (`id`, `title`) VALUES
(0, '<span class=''red''>Отключен</span>'),
(1, '<span class=''green''>Включен</span>');

-- --------------------------------------------------------

--
-- Структура таблицы `users`
--

CREATE TABLE IF NOT EXISTS `users` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `login` varchar(20) NOT NULL,
  `password` varchar(20) NOT NULL,
  `salt` varchar(20) NOT NULL,
  `hash` varchar(20) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `login` (`login`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=2 ;

--
-- Дамп данных таблицы `users`
--

INSERT INTO `users` (`id`, `login`, `password`, `salt`, `hash`) VALUES
(1, 'admin', 'KsIeBhClKzr0c', 'KsddGkjsfus35', 'khrwsi7gyO');

--
-- Ограничения внешнего ключа сохраненных таблиц
--

--
-- Ограничения внешнего ключа таблицы `blogs`
--
ALTER TABLE `blogs`
  ADD CONSTRAINT `blogs_ibfk_2` FOREIGN KEY (`state`) REFERENCES `states` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Ограничения внешнего ключа таблицы `blog_comments`
--
ALTER TABLE `blog_comments`
  ADD CONSTRAINT `blog_comments_ibfk_1` FOREIGN KEY (`blogpost_id`) REFERENCES `blog_posts` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `blog_comments_ibfk_2` FOREIGN KEY (`state`) REFERENCES `states` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Ограничения внешнего ключа таблицы `blog_posts`
--
ALTER TABLE `blog_posts`
  ADD CONSTRAINT `blog_posts_ibfk_1` FOREIGN KEY (`blog_id`) REFERENCES `blogs` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `blog_posts_ibfk_2` FOREIGN KEY (`state`) REFERENCES `states` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Ограничения внешнего ключа таблицы `categories`
--
ALTER TABLE `categories`
  ADD CONSTRAINT `categories_ibfk_1` FOREIGN KEY (`state`) REFERENCES `states` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Ограничения внешнего ключа таблицы `contents`
--
ALTER TABLE `contents`
  ADD CONSTRAINT `contents_ibfk_3` FOREIGN KEY (`image_id`) REFERENCES `images` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `contents_ibfk_1` FOREIGN KEY (`state`) REFERENCES `states` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `contents_ibfk_2` FOREIGN KEY (`category_id`) REFERENCES `categories` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Ограничения внешнего ключа таблицы `images`
--
ALTER TABLE `images`
  ADD CONSTRAINT `images_ibfk_1` FOREIGN KEY (`gallery_id`) REFERENCES `galleries` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Ограничения внешнего ключа таблицы `menus`
--
ALTER TABLE `menus`
  ADD CONSTRAINT `menus_ibfk_1` FOREIGN KEY (`state`) REFERENCES `states` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
