/*
Navicat MySQL Data Transfer

Source Server         : MyDB
Source Server Version : 50545
Source Host           : localhost:3306
Source Database       : derzki

Target Server Type    : MYSQL
Target Server Version : 50545
File Encoding         : 65001

Date: 2015-10-01 22:44:53
*/

SET FOREIGN_KEY_CHECKS=0;

-- ----------------------------
-- Table structure for cats
-- ----------------------------
DROP TABLE IF EXISTS `cats`;
CREATE TABLE `cats` (
  `login` varchar(20) NOT NULL,
  `pass` varchar(80) NOT NULL,
  `salt` varchar(10) NOT NULL,
  PRIMARY KEY (`login`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of cats
-- ----------------------------
INSERT INTO `cats` VALUES ('mackaway', 'f0f391655f031d26ed5876d4889ecf45', '4rfc#gf22h');

-- ----------------------------
-- Table structure for data
-- ----------------------------
DROP TABLE IF EXISTS `data`;
CREATE TABLE `data` (
  `id` int(11) NOT NULL,
  `first_name` varchar(20) NOT NULL,
  `second_name` varchar(20) NOT NULL,
  `last_name` varchar(40) NOT NULL,
  `phone` varchar(25) DEFAULT NULL,
  `index_num` varchar(6) DEFAULT NULL,
  `country` varchar(100) NOT NULL,
  `state` varchar(100) NOT NULL,
  `city` varchar(100) NOT NULL,
  `address` varchar(255) NOT NULL,
  `address_info` varchar(255) DEFAULT NULL,
  `prize_info` varchar(255) DEFAULT NULL,
  KEY `data_id` (`id`),
  CONSTRAINT `data_winner` FOREIGN KEY (`id`) REFERENCES `winner` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of data
-- ----------------------------

-- ----------------------------
-- Table structure for winner
-- ----------------------------
DROP TABLE IF EXISTS `winner`;
CREATE TABLE `winner` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `prize_link` varchar(100) NOT NULL,
  `photo_link` varchar(100) DEFAULT NULL,
  `description` text,
  `winner_page` varchar(100) NOT NULL,
  `winner_name` varchar(100) DEFAULT NULL,
  `comp_date` date NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `winner_id` (`id`),
  UNIQUE KEY `winner_post` (`prize_link`)
) ENGINE=InnoDB AUTO_INCREMENT=302 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of winner
-- ----------------------------
