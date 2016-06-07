-- phpMyAdmin SQL Dump
-- version 4.0.10deb1
-- http://www.phpmyadmin.net
--
-- 主机: localhost
-- 生成日期: 2016-06-07 16:06:20
-- 服务器版本: 5.5.49-0ubuntu0.14.04.1
-- PHP 版本: 5.5.9-1ubuntu4.18

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- 数据库: `virjudge`
--
CREATE DATABASE IF NOT EXISTS `virjudge` DEFAULT CHARACTER SET utf8 COLLATE utf8_general_ci;
USE `virjudge`;

-- --------------------------------------------------------

--
-- 表的结构 `hduResult`
--

CREATE TABLE IF NOT EXISTS `hduResult` (
  `PE` text NOT NULL,
  `WA` text NOT NULL,
  `RE` text NOT NULL,
  `RE_AV` text NOT NULL,
  `RE_ABE` text NOT NULL,
  `RE_FDO` text NOT NULL,
  `RE_FDBZ` text NOT NULL,
  `RE_FO` text NOT NULL,
  `RE_FU` text NOT NULL,
  `RE_IDBZ` text NOT NULL,
  `RE_IO` text NOT NULL,
  `RE_SO` text NOT NULL,
  `TLE` text NOT NULL,
  `MLE` text NOT NULL,
  `OLE` text NOT NULL,
  `SE` text NOT NULL,
  `OOCT` text NOT NULL,
  `Q` text NOT NULL,
  `C` text NOT NULL,
  `R` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- 表的结构 `message`
--

CREATE TABLE IF NOT EXISTS `message` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `item_id` int(10) NOT NULL COMMENT '题目id',
  `user_id` varchar(30) NOT NULL COMMENT '用户id',
  `content` text NOT NULL COMMENT '留言内容',
  `time` varchar(30) NOT NULL COMMENT '留言时间',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COMMENT='留言表' AUTO_INCREMENT=6 ;

-- --------------------------------------------------------

--
-- 表的结构 `nyistResult`
--

CREATE TABLE IF NOT EXISTS `nyistResult` (
  `WA` text NOT NULL,
  `RE` text NOT NULL,
  `TLE` text NOT NULL,
  `MLE` text NOT NULL,
  `OLE` text NOT NULL,
  `SE` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- 表的结构 `nyproblem`
--

CREATE TABLE IF NOT EXISTS `nyproblem` (
  `oj` varchar(10) NOT NULL,
  `problemid` int(10) NOT NULL,
  `problename` text NOT NULL,
  `time_limit` varchar(30) NOT NULL,
  `memory_limit` varchar(30) NOT NULL,
  `description` text NOT NULL,
  `pro_input` text NOT NULL,
  `pro_output` text NOT NULL,
  `simple_input` text NOT NULL,
  `simple_output` text NOT NULL,
  `source` text,
  `hint` text,
  PRIMARY KEY (`oj`,`problemid`),
  KEY `title_index` (`problename`(200))
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- 表的结构 `proxy`
--

CREATE TABLE IF NOT EXISTS `proxy` (
  `ip` varchar(30) NOT NULL,
  PRIMARY KEY (`ip`),
  UNIQUE KEY `ip` (`ip`),
  KEY `ip_2` (`ip`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- 表的结构 `reply`
--

CREATE TABLE IF NOT EXISTS `reply` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `pid` int(10) NOT NULL COMMENT '父id',
  `message_id` int(10) NOT NULL COMMENT '消息id',
  `reply_id` varchar(30) NOT NULL COMMENT '用户id',
  `content` text NOT NULL COMMENT '回复内容',
  `time` varchar(30) NOT NULL COMMENT '回复时间',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COMMENT='回复表' AUTO_INCREMENT=11 ;

-- --------------------------------------------------------

--
-- 表的结构 `run`
--

CREATE TABLE IF NOT EXISTS `run` (
  `runid` int(11) NOT NULL AUTO_INCREMENT,
  `oj` varchar(10) NOT NULL,
  `problemid` int(10) NOT NULL,
  `problename` text NOT NULL,
  `result` text,
  `time` varchar(30) DEFAULT NULL,
  `memory` varchar(30) DEFAULT NULL,
  `language` varchar(30) NOT NULL,
  `submit_time` varchar(40) NOT NULL,
  `submit_code` text NOT NULL,
  `userid` varchar(40) NOT NULL,
  `ceMessage` text,
  PRIMARY KEY (`runid`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=397 ;

-- --------------------------------------------------------

--
-- 表的结构 `test`
--

CREATE TABLE IF NOT EXISTS `test` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `str` text NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=10 ;

-- --------------------------------------------------------

--
-- 表的结构 `user`
--

CREATE TABLE IF NOT EXISTS `user` (
  `userid` varchar(40) NOT NULL,
  `password` text NOT NULL,
  `email` varchar(40) NOT NULL,
  `email_tag` tinyint(1) NOT NULL DEFAULT '0' COMMENT '邮箱验证',
  `login_time` varchar(30) NOT NULL COMMENT '登录时间',
  `register_time` varchar(30) NOT NULL COMMENT '注册时间',
  PRIMARY KEY (`userid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
