/*
Navicat MySQL Data Transfer

Source Server         : read
Source Server Version : 50505
Source Host           : localhost:3306
Source Database       : read

Target Server Type    : MYSQL
Target Server Version : 50505
File Encoding         : 65001

Date: 2017-03-08 11:48:55
*/

SET FOREIGN_KEY_CHECKS=0;

-- ----------------------------
-- Table structure for rd_bookshelf
-- ----------------------------
DROP TABLE IF EXISTS `rd_bookshelf`;
CREATE TABLE `rd_bookshelf` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `book_id` int(10) unsigned NOT NULL COMMENT '图书id,book表id',
  `user_id` int(10) unsigned NOT NULL COMMENT '用户id,user表id',
  `join_time` int(10) unsigned NOT NULL COMMENT '加入时间',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='书架';

-- ----------------------------
-- Records of rd_bookshelf
-- ----------------------------
