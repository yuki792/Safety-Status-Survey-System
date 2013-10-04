-- phpMyAdmin SQL Dump
-- version 4.0.4.1
-- http://www.phpmyadmin.net
--
-- ホスト: 127.0.0.1
-- 生成日時: 2013 年 9 月 30 日 03:13
-- サーバのバージョン: 5.5.32
-- PHP のバージョン: 5.4.19

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- データベース: `enpit_anpi`
--
CREATE DATABASE IF NOT EXISTS `enpit_anpi` DEFAULT CHARACTER SET utf8 COLLATE utf8_general_ci;
USE `enpit_anpi`;

-- --------------------------------------------------------

--
-- テーブルの構造 `administrator`
--

CREATE TABLE IF NOT EXISTS `administrator` (
  `administrator_id` varchar(20) NOT NULL,
  `password` varchar(128) NOT NULL,
  `authority_id` tinyint(3) unsigned NOT NULL,
  `mail` varchar(100) NOT NULL,
  `last_name` varchar(30) NOT NULL,
  `first_name` varchar(30) NOT NULL,
  `labo_id` int(10) unsigned NOT NULL,
  PRIMARY KEY (`administrator_id`),
  KEY `fk_labo_idx` (`labo_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- テーブルのデータのダンプ `administrator`
--

INSERT INTO `administrator` (`administrator_id`, `password`, `authority_id`, `mail`, `last_name`, `first_name`, `labo_id`) VALUES
('test', 'ee26b0dd4af7e749aa1a8ee3c10ae9923f618980772e473f8819a5d4940e0db27ac185f8a0e1d5f84f88bc887fd67b143732c304cc5fa9ad8e6f57f50028a8ff', 1, 'a@b.com', '山田', '三郎', 1);

-- --------------------------------------------------------

--
-- テーブルの構造 `course`
--

CREATE TABLE IF NOT EXISTS `course` (
  `course_id` tinyint(3) unsigned NOT NULL AUTO_INCREMENT,
  `faculty_id` tinyint(3) unsigned NOT NULL,
  `course_name` varchar(50) NOT NULL,
  PRIMARY KEY (`course_id`),
  UNIQUE KEY `index3` (`course_id`,`faculty_id`),
  KEY `fk_faculty_id_idx` (`faculty_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=40 ;

--
-- テーブルのデータのダンプ `course`
--

INSERT INTO `course` (`course_id`, `faculty_id`, `course_name`) VALUES
(1, 1, '人文コミュニケーション学科'),
(2, 1, '社会科学科'),
(3, 2, '学校教育教員養成課程'),
(4, 2, '養護教諭養成課程'),
(5, 2, '情報文化課程'),
(6, 2, '人間環境教育課程'),
(7, 3, '理学科'),
(8, 4, '機械工学科'),
(9, 4, '生体分子機能工学科'),
(10, 4, 'マテリアル工学科'),
(11, 4, '電気電子工学科'),
(12, 4, 'メディア通信工学科'),
(13, 4, '情報工学科'),
(14, 4, '都市システム工学科'),
(15, 4, '知能システム工学科 A(昼間)コース'),
(16, 4, '知能システム工学科 B(夜間)コース'),
(17, 5, '生物生産科学科'),
(18, 5, '資源生物科学科'),
(19, 5, '地域環境科学科'),
(20, 6, '文化科学専攻'),
(21, 6, '地域政策専攻'),
(22, 7, '学校教育専攻'),
(23, 7, '障害児教育専攻'),
(24, 7, '教科教育専攻'),
(25, 7, '養護教育専攻'),
(26, 7, '学校臨床心理専攻'),
(27, 8, '理学専攻'),
(28, 8, '機械工学専攻'),
(29, 8, '物質工学専攻'),
(30, 8, '電気電子工学専攻'),
(31, 8, 'メディア通信工学専攻'),
(32, 8, '都市システム工学専攻'),
(33, 8, '情報工学専攻'),
(34, 8, '知能システム工学専攻'),
(35, 8, '応用粒子線科学専攻'),
(36, 9, '生物生産科学専攻'),
(37, 9, '資源生物科学専攻'),
(38, 9, '地域環境科学専攻'),
(39, 10, '特別支援教育特別専攻科');

-- --------------------------------------------------------

--
-- テーブルの構造 `cross_finder`
--

CREATE TABLE IF NOT EXISTS `cross_finder` (
  `finder_id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `member_id` varchar(20) NOT NULL,
  `survey_id` smallint(5) unsigned NOT NULL,
  `register_date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`finder_id`),
  UNIQUE KEY `index6` (`member_id`,`survey_id`),
  KEY `fk_memberid_idx` (`member_id`),
  KEY `fk_surveyid_idx` (`survey_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- テーブルの構造 `faculty`
--

CREATE TABLE IF NOT EXISTS `faculty` (
  `faculty_id` tinyint(3) unsigned NOT NULL AUTO_INCREMENT,
  `faculty_name` varchar(50) NOT NULL,
  PRIMARY KEY (`faculty_id`),
  UNIQUE KEY `faculty_name_UNIQUE` (`faculty_name`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=11 ;

--
-- テーブルのデータのダンプ `faculty`
--

INSERT INTO `faculty` (`faculty_id`, `faculty_name`) VALUES
(1, '人文学部'),
(6, '大学院人文学研究科'),
(7, '大学院教育学研究科'),
(8, '大学院理工学研究科'),
(9, '大学院農学研究科'),
(4, '工学部'),
(2, '教育学部'),
(10, '特別支援教育特別専攻科'),
(3, '理学部'),
(5, '農学部');

-- --------------------------------------------------------

--
-- テーブルの構造 `flink`
--

CREATE TABLE IF NOT EXISTS `flink` (
  `flink_id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `member_id` varchar(20) NOT NULL,
  `mail` varchar(100) NOT NULL,
  `survey_id` smallint(5) unsigned NOT NULL,
  `flink_mail` varchar(100) NOT NULL,
  PRIMARY KEY (`flink_id`),
  KEY `fk_mem_id_idx` (`member_id`),
  KEY `fk_sur_id_idx` (`survey_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- テーブルの構造 `laboratory`
--

CREATE TABLE IF NOT EXISTS `laboratory` (
  `labo_id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `labo_name` varchar(45) DEFAULT NULL,
  `labo_password` varchar(128) DEFAULT NULL,
  PRIMARY KEY (`labo_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=4 ;

--
-- テーブルのデータのダンプ `laboratory`
--

INSERT INTO `laboratory` (`labo_id`, `labo_name`, `labo_password`) VALUES
(1, 'かまだ研究室', 'ee26b0dd4af7e749aa1a8ee3c10ae9923f618980772e473f8819a5d4940e0db27ac185f8a0e1d5f84f88bc887fd67b143732c304cc5fa9ad8e6f57f50028a8ff'),
(2, 'うえだ研究室', 'ee26b0dd4af7e749aa1a8ee3c10ae9923f618980772e473f8819a5d4940e0db27ac185f8a0e1d5f84f88bc887fd67b143732c304cc5fa9ad8e6f57f50028a8ff'),
(3, 'テスト研究室', 'ee26b0dd4af7e749aa1a8ee3c10ae9923f618980772e473f8819a5d4940e0db27ac185f8a0e1d5f84f88bc887fd67b143732c304cc5fa9ad8e6f57f50028a8ff');

-- --------------------------------------------------------

--
-- テーブルの構造 `member`
--

CREATE TABLE IF NOT EXISTS `member` (
  `member_id` varchar(20) NOT NULL,
  `password` varchar(128) NOT NULL,
  `last_name` varchar(30) NOT NULL,
  `first_name` varchar(30) NOT NULL,
  `phone_mail` varchar(256) NOT NULL,
  `phone_number` varchar(256) DEFAULT NULL,
  `other_mail1` varchar(256) DEFAULT NULL,
  `other_mail2` varchar(256) DEFAULT NULL,
  `other_mail3` varchar(256) DEFAULT NULL,
  `flink_mail1` varchar(256) DEFAULT NULL,
  `flink_mail2` varchar(256) DEFAULT NULL,
  `flink_mail3` varchar(256) DEFAULT NULL,
  `flink_mail4` varchar(256) DEFAULT NULL,
  `flink_mail5` varchar(256) DEFAULT NULL,
  `faculty_id` tinyint(3) unsigned NOT NULL,
  `course_id` tinyint(3) unsigned NOT NULL,
  `labo_id` int(10) unsigned NOT NULL,
  `twitter_id` varchar(256) DEFAULT NULL,
  PRIMARY KEY (`member_id`),
  KEY `fk_faculty_idx` (`faculty_id`),
  KEY `fk_course_idx` (`course_id`),
  KEY `fk_labo_idx` (`labo_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- テーブルの構造 `safety_status`
--

CREATE TABLE IF NOT EXISTS `safety_status` (
  `safety_status_id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `survey_id` smallint(5) unsigned NOT NULL,
  `member_id` varchar(20) NOT NULL,
  `safety_status` tinyint(3) unsigned NOT NULL,
  `location` tinyint(4) NOT NULL,
  `attend_school` tinyint(4) NOT NULL,
  `comment` varchar(1000) DEFAULT NULL,
  `register_date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`safety_status_id`),
  UNIQUE KEY `index4` (`survey_id`,`member_id`),
  KEY `fk_survey_id_idx` (`survey_id`),
  KEY `fk_member_id_idx` (`member_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=11 ;

-- --------------------------------------------------------

--
-- テーブルの構造 `safety_survey`
--

CREATE TABLE IF NOT EXISTS `safety_survey` (
  `survey_id` smallint(5) unsigned NOT NULL AUTO_INCREMENT,
  `title` varchar(100) NOT NULL DEFAULT '無題',
  `administrator_id` varchar(20) NOT NULL,
  `target_faculty` tinyint(3) unsigned DEFAULT NULL,
  `target_course` tinyint(3) unsigned DEFAULT NULL,
  `target_member` varchar(20) DEFAULT NULL,
  `register_date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`survey_id`),
  KEY `fk_administrator_id_idx` (`administrator_id`),
  KEY `fk_target_faculty_idx` (`target_faculty`),
  KEY `fk_target_course_idx` (`target_course`),
  KEY `fk_target_member_idx` (`target_member`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=57 ;

-- --------------------------------------------------------

--
-- テーブルの構造 `twitter_observe`
--

CREATE TABLE IF NOT EXISTS `twitter_observe` (
  `twitter_observe_id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `member_id` varchar(20) NOT NULL,
  `survey_id` smallint(5) unsigned NOT NULL,
  `register_date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`twitter_observe_id`),
  KEY `fk_twitter_observe_member1_idx` (`member_id`),
  KEY `fk_twitter_observe_safety_survey1_idx` (`survey_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=29 ;

-- --------------------------------------------------------

--
-- テーブルの構造 `url_key`
--

CREATE TABLE IF NOT EXISTS `url_key` (
  `key_id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `key` varchar(16) NOT NULL,
  `member_id` varchar(20) NOT NULL,
  `survey_id` smallint(5) unsigned NOT NULL,
  `isable` tinyint(3) unsigned NOT NULL DEFAULT '1',
  `type` tinyint(3) unsigned NOT NULL,
  PRIMARY KEY (`key_id`),
  UNIQUE KEY `key_UNIQUE` (`key`),
  UNIQUE KEY `index5` (`member_id`,`survey_id`,`type`),
  KEY `fk_memid_idx` (`member_id`),
  KEY `fk_survey_idx` (`survey_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=34 ;

--
-- ダンプしたテーブルの制約
--

--
-- テーブルの制約 `administrator`
--
ALTER TABLE `administrator`
  ADD CONSTRAINT `fk_labo2` FOREIGN KEY (`labo_id`) REFERENCES `laboratory` (`labo_id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- テーブルの制約 `course`
--
ALTER TABLE `course`
  ADD CONSTRAINT `fk_faculty_id` FOREIGN KEY (`faculty_id`) REFERENCES `faculty` (`faculty_id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- テーブルの制約 `cross_finder`
--
ALTER TABLE `cross_finder`
  ADD CONSTRAINT `fk_memberid` FOREIGN KEY (`member_id`) REFERENCES `member` (`member_id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_surveyid` FOREIGN KEY (`survey_id`) REFERENCES `safety_survey` (`survey_id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- テーブルの制約 `flink`
--
ALTER TABLE `flink`
  ADD CONSTRAINT `fk_mem_id` FOREIGN KEY (`member_id`) REFERENCES `member` (`member_id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_sur_id` FOREIGN KEY (`survey_id`) REFERENCES `safety_survey` (`survey_id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- テーブルの制約 `member`
--
ALTER TABLE `member`
  ADD CONSTRAINT `fk_course` FOREIGN KEY (`course_id`) REFERENCES `course` (`course_id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_faculty` FOREIGN KEY (`faculty_id`) REFERENCES `faculty` (`faculty_id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_labo` FOREIGN KEY (`labo_id`) REFERENCES `laboratory` (`labo_id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- テーブルの制約 `safety_status`
--
ALTER TABLE `safety_status`
  ADD CONSTRAINT `fk_member_id` FOREIGN KEY (`member_id`) REFERENCES `member` (`member_id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_survey_id` FOREIGN KEY (`survey_id`) REFERENCES `safety_survey` (`survey_id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- テーブルの制約 `safety_survey`
--
ALTER TABLE `safety_survey`
  ADD CONSTRAINT `fk_administrator_id` FOREIGN KEY (`administrator_id`) REFERENCES `administrator` (`administrator_id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_target_course` FOREIGN KEY (`target_course`) REFERENCES `course` (`course_id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_target_faculty` FOREIGN KEY (`target_faculty`) REFERENCES `faculty` (`faculty_id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_target_member` FOREIGN KEY (`target_member`) REFERENCES `member` (`member_id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- テーブルの制約 `twitter_observe`
--
ALTER TABLE `twitter_observe`
  ADD CONSTRAINT `fk_twitter_observe_member1` FOREIGN KEY (`member_id`) REFERENCES `member` (`member_id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_twitter_observe_safety_survey1` FOREIGN KEY (`survey_id`) REFERENCES `safety_survey` (`survey_id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- テーブルの制約 `url_key`
--
ALTER TABLE `url_key`
  ADD CONSTRAINT `fk_memid` FOREIGN KEY (`member_id`) REFERENCES `member` (`member_id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_survey` FOREIGN KEY (`survey_id`) REFERENCES `safety_survey` (`survey_id`) ON DELETE CASCADE ON UPDATE CASCADE;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
