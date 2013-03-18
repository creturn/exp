/*
Navicat MySQL Data Transfer

Source Server         : 本地数据库
Source Server Version : 50153
Source Host           : localhost:3306
Source Database       : pwd

Target Server Type    : MYSQL
Target Server Version : 50153
File Encoding         : 65001

Date: 2012-07-03 17:28:30
*/

SET FOREIGN_KEY_CHECKS=0;
-- ----------------------------
-- Table structure for `adminlist`
-- ----------------------------
DROP TABLE IF EXISTS `adminlist`;
CREATE TABLE `adminlist` (
  `id` mediumint(8) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(30) DEFAULT NULL,
  `password` varchar(50) DEFAULT NULL,
  `site` varchar(50) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=9 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of adminlist
-- ----------------------------
