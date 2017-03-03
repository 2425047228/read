/*
Navicat MySQL Data Transfer

Source Server         : read
Source Server Version : 50505
Source Host           : localhost:3306
Source Database       : read

Target Server Type    : MYSQL
Target Server Version : 50505
File Encoding         : 65001

Date: 2017-03-03 09:39:49
*/

SET FOREIGN_KEY_CHECKS=0;

-- ----------------------------
-- Table structure for rd_author
-- ----------------------------
DROP TABLE IF EXISTS `rd_author`;
CREATE TABLE `rd_author` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `author` char(10) NOT NULL COMMENT '作者姓名',
  `author_synopsis` text NOT NULL COMMENT '作者简介',
  PRIMARY KEY (`id`),
  KEY `author` (`author`)
) ENGINE=InnoDB AUTO_INCREMENT=9 DEFAULT CHARSET=utf8 COMMENT='作者表';

-- ----------------------------
-- Table structure for rd_banner
-- ----------------------------
DROP TABLE IF EXISTS `rd_banner`;
CREATE TABLE `rd_banner` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `book_id` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '点击banner跳转到书籍的界面,默认0，不跳转',
  `banner_file` varchar(200) NOT NULL COMMENT 'banner图片文件',
  `banner_state` tinyint(4) NOT NULL DEFAULT '0' COMMENT '默认0未上架，1已上架',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=8 DEFAULT CHARSET=utf8 COMMENT='banner表';

-- ----------------------------
-- Table structure for rd_book
-- ----------------------------
DROP TABLE IF EXISTS `rd_book`;
CREATE TABLE `rd_book` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `book_name` varchar(30) NOT NULL COMMENT '书名',
  `author_id` int(10) unsigned NOT NULL COMMENT '作者id,rd_author表id',
  `book_synopsis` text NOT NULL COMMENT '图书简介',
  `book_cover` varchar(200) NOT NULL COMMENT '图书封面',
  `category_ids` varchar(30) NOT NULL COMMENT '图书分类',
  `is_hot` tinyint(3) unsigned NOT NULL DEFAULT '0' COMMENT '是否为热门图书，默认0代表否，1代表是',
  `reading_number` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '当前阅读人数默认0',
  `book_file` varchar(200) NOT NULL,
  `book_state` tinyint(3) unsigned NOT NULL DEFAULT '1' COMMENT '图书状态，默认1代表上架，0下架，其他状态待补充',
  `number_of_words` char(10) NOT NULL DEFAULT '1万' COMMENT '图书字数',
  `shelves_time` int(10) unsigned NOT NULL COMMENT '图书上架时间',
  PRIMARY KEY (`id`),
  UNIQUE KEY `book_name` (`book_name`),
  KEY `author_id` (`author_id`),
  KEY `category_ids` (`category_ids`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='图书信息表';

-- ----------------------------
-- Table structure for rd_category
-- ----------------------------
DROP TABLE IF EXISTS `rd_category`;
CREATE TABLE `rd_category` (
  `id` tinyint(3) unsigned NOT NULL AUTO_INCREMENT,
  `category` char(10) NOT NULL DEFAULT '' COMMENT '图书类别',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='图书分类表';

-- ----------------------------
-- Table structure for rd_chapter
-- ----------------------------
DROP TABLE IF EXISTS `rd_chapter`;
CREATE TABLE `rd_chapter` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `chapter` varchar(30) NOT NULL COMMENT '图书篇章名',
  `book_id` int(10) unsigned NOT NULL COMMENT '所属图书id，rd_book表id',
  `chapter_content` text NOT NULL COMMENT '篇章内容',
  `chapter_sort` smallint(5) unsigned NOT NULL COMMENT '篇章序列，由小到大',
  `parent_id` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '所属父级id',
  PRIMARY KEY (`id`),
  KEY `book_id` (`book_id`),
  KEY `chapter_sort` (`chapter_sort`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='图书篇章表';

-- ----------------------------
-- Table structure for rd_hobby
-- ----------------------------
DROP TABLE IF EXISTS `rd_hobby`;
CREATE TABLE `rd_hobby` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `hobby` char(10) NOT NULL COMMENT '兴趣标签',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=2 DEFAULT CHARSET=utf8 COMMENT='会员兴趣表';

-- ----------------------------
-- Table structure for rd_user
-- ----------------------------
DROP TABLE IF EXISTS `rd_user`;
CREATE TABLE `rd_user` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `avatar_file` varchar(200) NOT NULL DEFAULT '' COMMENT '用户头像',
  `openid` char(32) NOT NULL COMMENT '微信用户openid',
  `mobile_number` char(11) NOT NULL DEFAULT '' COMMENT '用户手机号',
  `nick_name` char(11) NOT NULL DEFAULT '' COMMENT '用户昵称',
  `sex` enum('未填写','男','女') NOT NULL DEFAULT '未填写' COMMENT '用户性别',
  `hobbys` varchar(30) NOT NULL DEFAULT '' COMMENT '用户兴趣,一对多,对应rd_hobby表id',
  `register_time` int(11) NOT NULL COMMENT '用户注册时间',
  PRIMARY KEY (`id`),
  UNIQUE KEY `openid` (`openid`),
  KEY `mobile_number` (`mobile_number`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='图书项目会员表';
