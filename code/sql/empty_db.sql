# ************************************************************
# Sequel Pro SQL dump
# Version 4096
#
# http://www.sequelpro.com/
# http://code.google.com/p/sequel-pro/
#
# Host: localhost (MySQL 5.1.44)
# Database: pennyturk
# Generation Time: 2013-10-10 19:16:30 +0000
# ************************************************************


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;


# Dump of table tbl_answer
# ------------------------------------------------------------

DROP TABLE IF EXISTS `tbl_answer`;

CREATE TABLE `tbl_answer` (
  `answer_id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `question_id` int(11) unsigned NOT NULL,
  `user_id` int(11) unsigned NOT NULL,
  `entry_time` datetime DEFAULT NULL,
  `answer_text` text,
  `answer_text_cipher` text,
  `in_short` varchar(100) DEFAULT '',
  `in_short_cipher` varchar(100) DEFAULT '',
  `award_total` decimal(12,2) DEFAULT '0.00',
  PRIMARY KEY (`answer_id`),
  KEY `user_id` (`user_id`),
  KEY `question_id` (`question_id`),
  CONSTRAINT `tbl_answer_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `tbl_user` (`user_id`) ON UPDATE CASCADE,
  CONSTRAINT `tbl_answer_ibfk_2` FOREIGN KEY (`question_id`) REFERENCES `tbl_question` (`question_id`) ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=latin1;


DELIMITER ;;
/*!50003 SET SESSION SQL_MODE="" */;;
/*!50003 CREATE */ /*!50017 DEFINER=`root`@`localhost` */ /*!50003 TRIGGER `q_rollup_insert` AFTER INSERT ON `tbl_answer` FOR EACH ROW UPDATE 	tbl_question
SET		answer_count = answer_count + 1,
		last_update_time = NEW.entry_time
WHERE	question_id = NEW.question_id; */;;
DELIMITER ;
/*!50003 SET SESSION SQL_MODE=@OLD_SQL_MODE */;


# Dump of table tbl_award
# ------------------------------------------------------------

DROP TABLE IF EXISTS `tbl_award`;

CREATE TABLE `tbl_award` (
  `award_id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `awarding_user_id` int(11) unsigned NOT NULL,
  `question_id` int(11) unsigned NOT NULL,
  `answer_id` int(11) unsigned DEFAULT NULL,
  `award_amount` decimal(12,2) NOT NULL,
  PRIMARY KEY (`award_id`),
  KEY `awarding_user_id` (`awarding_user_id`),
  KEY `question_id` (`question_id`),
  KEY `answer_id` (`answer_id`),
  CONSTRAINT `tbl_award_ibfk_1` FOREIGN KEY (`awarding_user_id`) REFERENCES `tbl_user` (`user_id`) ON UPDATE CASCADE,
  CONSTRAINT `tbl_award_ibfk_2` FOREIGN KEY (`question_id`) REFERENCES `tbl_question` (`question_id`) ON UPDATE CASCADE,
  CONSTRAINT `tbl_award_ibfk_3` FOREIGN KEY (`answer_id`) REFERENCES `tbl_answer` (`answer_id`) ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=latin1;


DELIMITER ;;
/*!50003 SET SESSION SQL_MODE="" */;;
/*!50003 CREATE */ /*!50017 DEFINER=`root`@`localhost` */ /*!50003 TRIGGER `rollup_total_insert` AFTER INSERT ON `tbl_award` FOR EACH ROW UPDATE 	tbl_answer 
SET 	award_total = award_total + NEW.award_amount 
WHERE answer_id = NEW.answer_id; */;;
/*!50003 SET SESSION SQL_MODE="" */;;
/*!50003 CREATE */ /*!50017 DEFINER=`root`@`localhost` */ /*!50003 TRIGGER `rollup_total_update` AFTER UPDATE ON `tbl_award` FOR EACH ROW UPDATE 	tbl_answer 
SET 	award_total = award_total + (NEW.award_amount - OLD.award_amount) 
WHERE answer_id = NEW.answer_id; */;;
/*!50003 SET SESSION SQL_MODE="" */;;
/*!50003 CREATE */ /*!50017 DEFINER=`root`@`localhost` */ /*!50003 TRIGGER `rollup_total_delete` AFTER DELETE ON `tbl_award` FOR EACH ROW UPDATE 	tbl_answer 
SET 	award_total = award_total -OLD.award_amount
WHERE answer_id = OLD.answer_id; */;;
DELIMITER ;
/*!50003 SET SESSION SQL_MODE=@OLD_SQL_MODE */;


# Dump of table tbl_demo_answer
# ------------------------------------------------------------

DROP TABLE IF EXISTS `tbl_demo_answer`;

CREATE TABLE `tbl_demo_answer` (
  `answer_id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `user_name` varchar(100) NOT NULL DEFAULT '',
  `entry_time` datetime DEFAULT NULL,
  `answer_text` text,
  `answer_text_cipher` text,
  `in_short` varchar(100) DEFAULT '',
  `in_short_cipher` varchar(100) DEFAULT '',
  `award_total` decimal(12,2) DEFAULT '0.00',
  PRIMARY KEY (`answer_id`),
  KEY `user_id` (`user_name`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;



# Dump of table tbl_myisam_question
# ------------------------------------------------------------

DROP TABLE IF EXISTS `tbl_myisam_question`;

CREATE TABLE `tbl_myisam_question` (
  `question_id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `question_text` varchar(200) DEFAULT '',
  `answer_count` int(11) NOT NULL DEFAULT '0',
  `capitalization` decimal(12,2) NOT NULL DEFAULT '0.00',
  `last_update_time` datetime DEFAULT NULL,
  `entry_time` datetime DEFAULT NULL,
  PRIMARY KEY (`question_id`),
  FULLTEXT KEY `question_text` (`question_text`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;



# Dump of table tbl_question
# ------------------------------------------------------------

DROP TABLE IF EXISTS `tbl_question`;

CREATE TABLE `tbl_question` (
  `question_id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `question_text` varchar(200) NOT NULL,
  `answer_count` int(11) NOT NULL DEFAULT '0',
  `capitalization` decimal(12,2) NOT NULL DEFAULT '0.00',
  `last_update_time` datetime DEFAULT NULL,
  `entry_time` datetime DEFAULT NULL,
  PRIMARY KEY (`question_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;


DELIMITER ;;
/*!50003 SET SESSION SQL_MODE="" */;;
/*!50003 CREATE */ /*!50017 DEFINER=`root`@`localhost` */ /*!50003 TRIGGER `replicate_to_isam_table` AFTER INSERT ON `tbl_question` FOR EACH ROW INSERT INTO tbl_myisam_question
SET 	question_id = NEW.question_id, 
		question_text = NEW.question_text,
		answer_count = NEW.answer_count,
		last_update_time = NEW.last_update_time,
		entry_time = NEW.entry_time; */;;
/*!50003 SET SESSION SQL_MODE="" */;;
/*!50003 CREATE */ /*!50017 DEFINER=`root`@`localhost` */ /*!50003 TRIGGER `replicate_to_isam_table_update` AFTER UPDATE ON `tbl_question` FOR EACH ROW UPDATE tbl_myisam_question
SET question_text = NEW.question_text, 
	answer_count = NEW.answer_count,
	capitalization = NEW.capitalization,
	last_update_time = NEW.last_update_time
WHERE question_id = NEW.question_id; */;;
/*!50003 SET SESSION SQL_MODE="" */;;
/*!50003 CREATE */ /*!50017 DEFINER=`root`@`localhost` */ /*!50003 TRIGGER `replicate_to_isam_table_delete` AFTER DELETE ON `tbl_question` FOR EACH ROW DELETE FROM tbl_myisam_question
WHERE question_id = OLD.question_id; */;;
DELIMITER ;
/*!50003 SET SESSION SQL_MODE=@OLD_SQL_MODE */;


# Dump of table tbl_question_access
# ------------------------------------------------------------

DROP TABLE IF EXISTS `tbl_question_access`;

CREATE TABLE `tbl_question_access` (
  `question_id` int(11) unsigned NOT NULL,
  `user_id` int(11) unsigned NOT NULL,
  `access_time` datetime DEFAULT NULL,
  `access_id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  PRIMARY KEY (`question_id`,`user_id`),
  KEY `user_id` (`user_id`),
  KEY `access_id` (`access_id`),
  CONSTRAINT `tbl_question_access_ibfk_1` FOREIGN KEY (`question_id`) REFERENCES `tbl_question` (`question_id`) ON UPDATE CASCADE,
  CONSTRAINT `tbl_question_access_ibfk_2` FOREIGN KEY (`user_id`) REFERENCES `tbl_user` (`user_id`) ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=latin1;


DELIMITER ;;
/*!50003 SET SESSION SQL_MODE="" */;;
/*!50003 CREATE */ /*!50017 DEFINER=`root`@`localhost` */ /*!50003 TRIGGER `rollup_insert` AFTER INSERT ON `tbl_question_access` FOR EACH ROW UPDATE 	tbl_question 
SET 	capitalization = capitalization + 1.00
WHERE	question_id = NEW.question_id; */;;
DELIMITER ;
/*!50003 SET SESSION SQL_MODE=@OLD_SQL_MODE */;


# Dump of table tbl_question_watch
# ------------------------------------------------------------

DROP TABLE IF EXISTS `tbl_question_watch`;

CREATE TABLE `tbl_question_watch` (
  `question_id` int(11) unsigned NOT NULL,
  `user_id` int(11) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`question_id`,`user_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;



# Dump of table tbl_system_ledger
# ------------------------------------------------------------

DROP TABLE IF EXISTS `tbl_system_ledger`;

CREATE TABLE `tbl_system_ledger` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `parent_id` int(11) DEFAULT NULL,
  `transaction_time` datetime NOT NULL,
  `beginning_balance` decimal(12,2) NOT NULL,
  `transaction_type` varchar(11) NOT NULL DEFAULT '',
  `transaction_amount` decimal(12,2) NOT NULL,
  `ending_balance` decimal(12,2) unsigned NOT NULL,
  `active` int(11) NOT NULL,
  `reference` varchar(100) DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `parent_id` (`parent_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;



# Dump of table tbl_user
# ------------------------------------------------------------

DROP TABLE IF EXISTS `tbl_user`;

CREATE TABLE `tbl_user` (
  `user_id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `user_name` varchar(50) DEFAULT '',
  `email` varchar(100) NOT NULL DEFAULT '',
  `password` varchar(60) DEFAULT '',
  `registration_time` datetime DEFAULT NULL,
  `stripe_recipient_id` varchar(60) DEFAULT NULL,
  `admin` tinyint(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`user_id`),
  UNIQUE KEY `email` (`email`),
  UNIQUE KEY `user_id` (`user_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;



# Dump of table tbl_user_ledger
# ------------------------------------------------------------

DROP TABLE IF EXISTS `tbl_user_ledger`;

CREATE TABLE `tbl_user_ledger` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `parent_id` int(11) unsigned DEFAULT NULL,
  `user_id` int(11) unsigned NOT NULL,
  `transaction_time` datetime NOT NULL,
  `beginning_balance` decimal(12,2) NOT NULL,
  `beginning_credits` decimal(12,2) NOT NULL,
  `transaction_type` varchar(11) NOT NULL DEFAULT '',
  `transaction_amount` decimal(12,2) NOT NULL,
  `ending_balance` decimal(12,2) NOT NULL,
  `ending_credits` decimal(12,2) NOT NULL,
  `active` int(11) NOT NULL,
  `reference` varchar(100) DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `parent_id` (`parent_id`),
  KEY `user_id` (`user_id`),
  CONSTRAINT `tbl_user_ledger_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `tbl_user` (`user_id`) ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=latin1;




/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;
/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
