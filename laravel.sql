/*
Navicat MySQL Data Transfer

Source Server         : 本地数据
Source Server Version : 80025
Source Host           : 127.0.0.1:3306
Source Database       : laravel

Target Server Type    : MYSQL
Target Server Version : 80025
File Encoding         : 65001

Date: 2022-05-31 00:32:59
*/

SET FOREIGN_KEY_CHECKS=0;

-- ----------------------------
-- Table structure for t_failed_jobs
-- ----------------------------
DROP TABLE IF EXISTS `t_failed_jobs`;
CREATE TABLE `t_failed_jobs` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `uuid` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `connection` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `queue` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `payload` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `exception` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `failed_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `t_failed_jobs_uuid_unique` (`uuid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ----------------------------
-- Records of t_failed_jobs
-- ----------------------------

-- ----------------------------
-- Table structure for t_migrations
-- ----------------------------
DROP TABLE IF EXISTS `t_migrations`;
CREATE TABLE `t_migrations` (
  `id` int unsigned NOT NULL AUTO_INCREMENT,
  `migration` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `batch` int NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ----------------------------
-- Records of t_migrations
-- ----------------------------
INSERT INTO `t_migrations` VALUES ('1', '2014_10_12_000000_create_users_table', '1');
INSERT INTO `t_migrations` VALUES ('2', '2014_10_12_100000_create_password_resets_table', '1');
INSERT INTO `t_migrations` VALUES ('3', '2019_08_19_000000_create_failed_jobs_table', '1');
INSERT INTO `t_migrations` VALUES ('4', '2019_12_14_000001_create_personal_access_tokens_table', '1');

-- ----------------------------
-- Table structure for t_news
-- ----------------------------
DROP TABLE IF EXISTS `t_news`;
CREATE TABLE `t_news` (
  `id` int NOT NULL AUTO_INCREMENT COMMENT '编号',
  `uid` int NOT NULL COMMENT '用户id',
  `add_time` int DEFAULT NULL COMMENT '新增时间',
  `new_title` varchar(255) DEFAULT NULL COMMENT '新闻标题',
  `new_type` int DEFAULT NULL COMMENT '新闻类别',
  `new_top` int NOT NULL DEFAULT '0' COMMENT '是否置顶',
  `new_con` longtext COMMENT '新闻内容',
  `new_user` varchar(255) DEFAULT NULL,
  `status` int NOT NULL DEFAULT '0' COMMENT '-1回退修改0 保存中1流程中 2通过',
  `uptime` int DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=24 DEFAULT CHARSET=utf8mb3 COMMENT='[work]新闻表';

-- ----------------------------
-- Records of t_news
-- ----------------------------
INSERT INTO `t_news` VALUES ('18', '1', null, '123', '1', '1', '123123123123123123', null, '0', null);
INSERT INTO `t_news` VALUES ('19', '1', null, '123', '1', '1', '123123123123', null, '0', null);
INSERT INTO `t_news` VALUES ('20', '1', null, '123', '1', '1', '123123', null, '0', null);
INSERT INTO `t_news` VALUES ('21', '1', null, '123', '1', '1', '123', null, '0', null);
INSERT INTO `t_news` VALUES ('22', '1', null, '123', '1', '1', '123', null, '0', null);
INSERT INTO `t_news` VALUES ('23', '1', null, '123', '1', '1', '123123', null, '0', null);

-- ----------------------------
-- Table structure for t_password_resets
-- ----------------------------
DROP TABLE IF EXISTS `t_password_resets`;
CREATE TABLE `t_password_resets` (
  `email` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `token` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  KEY `t_password_resets_email_index` (`email`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ----------------------------
-- Records of t_password_resets
-- ----------------------------

-- ----------------------------
-- Table structure for t_personal_access_tokens
-- ----------------------------
DROP TABLE IF EXISTS `t_personal_access_tokens`;
CREATE TABLE `t_personal_access_tokens` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `tokenable_type` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `tokenable_id` bigint unsigned NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `token` varchar(64) COLLATE utf8mb4_unicode_ci NOT NULL,
  `abilities` text COLLATE utf8mb4_unicode_ci,
  `last_used_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `t_personal_access_tokens_token_unique` (`token`),
  KEY `t_personal_access_tokens_tokenable_type_tokenable_id_index` (`tokenable_type`,`tokenable_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ----------------------------
-- Records of t_personal_access_tokens
-- ----------------------------

-- ----------------------------
-- Table structure for t_role
-- ----------------------------
DROP TABLE IF EXISTS `t_role`;
CREATE TABLE `t_role` (
  `id` smallint unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(20) NOT NULL COMMENT '后台组名',
  `pid` smallint unsigned NOT NULL DEFAULT '0' COMMENT '父ID',
  `status` tinyint unsigned DEFAULT '0' COMMENT '是否激活 1：是 0：否',
  `sort` smallint unsigned NOT NULL DEFAULT '0' COMMENT '排序权重',
  `remark` varchar(255) DEFAULT NULL COMMENT '备注说明',
  PRIMARY KEY (`id`),
  KEY `pid` (`pid`),
  KEY `status` (`status`)
) ENGINE=MyISAM AUTO_INCREMENT=22 DEFAULT CHARSET=utf8mb3 COMMENT='模拟用户角色表';

-- ----------------------------
-- Records of t_role
-- ----------------------------
INSERT INTO `t_role` VALUES ('1', '员工部', '0', '1', '0', '');
INSERT INTO `t_role` VALUES ('2', '经理部', '0', '1', '0', '');
INSERT INTO `t_role` VALUES ('3', '主管部', '0', '1', '0', '');
INSERT INTO `t_role` VALUES ('4', '主任部', '0', '1', '0', '');
INSERT INTO `t_role` VALUES ('5', '副总', '0', '1', '0', '');
INSERT INTO `t_role` VALUES ('6', '总经理', '0', '1', '0', '');
INSERT INTO `t_role` VALUES ('7', '董事长', '0', '1', '0', '');

-- ----------------------------
-- Table structure for t_role_user
-- ----------------------------
DROP TABLE IF EXISTS `t_role_user`;
CREATE TABLE `t_role_user` (
  `user_id` int unsigned NOT NULL,
  `role_id` smallint unsigned NOT NULL,
  KEY `group_id` (`role_id`),
  KEY `user_id` (`user_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb3;

-- ----------------------------
-- Records of t_role_user
-- ----------------------------
INSERT INTO `t_role_user` VALUES ('1', '1');
INSERT INTO `t_role_user` VALUES ('2', '2');
INSERT INTO `t_role_user` VALUES ('3', '3');
INSERT INTO `t_role_user` VALUES ('4', '4');
INSERT INTO `t_role_user` VALUES ('5', '5');
INSERT INTO `t_role_user` VALUES ('6', '6');
INSERT INTO `t_role_user` VALUES ('7', '7');

-- ----------------------------
-- Table structure for t_user
-- ----------------------------
DROP TABLE IF EXISTS `t_user`;
CREATE TABLE `t_user` (
  `id` int unsigned NOT NULL AUTO_INCREMENT,
  `username` varchar(50) NOT NULL,
  `password` char(32) NOT NULL,
  `tel` varchar(255) DEFAULT NULL,
  `mail` varchar(255) DEFAULT NULL,
  `role` smallint unsigned NOT NULL COMMENT '组ID',
  `status` tinyint unsigned NOT NULL DEFAULT '0' COMMENT '状态 1:启用 0:禁止',
  `remark` varchar(255) DEFAULT NULL COMMENT '备注说明',
  `last_login_time` int unsigned NOT NULL COMMENT '最后登录时间',
  `last_login_ip` varchar(15) DEFAULT NULL COMMENT '最后登录IP',
  `login_count` int DEFAULT '0',
  `last_location` varchar(100) DEFAULT NULL COMMENT '最后登录位置',
  `add_time` int DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `username` (`username`)
) ENGINE=MyISAM AUTO_INCREMENT=14 DEFAULT CHARSET=utf8mb3 COMMENT='用户表';

-- ----------------------------
-- Records of t_user
-- ----------------------------
INSERT INTO `t_user` VALUES ('1', '员工', 'c4ca4238a0b923820dcc509a6f75849b', '1', '1', '1', '0', '1', '1522372036', '127.0.0.1', '0', '新建用户', '1522372036');
INSERT INTO `t_user` VALUES ('2', '经理', 'c4ca4238a0b923820dcc509a6f75849b', '1', '1', '2', '0', '1', '1522372556', '127.0.0.1', '0', '新建用户', '1522372556');
INSERT INTO `t_user` VALUES ('3', '主管', 'c4ca4238a0b923820dcc509a6f75849b', '1', '1', '3', '0', '1', '1522376353', '127.0.0.1', '0', '新建用户', '1522376353');
INSERT INTO `t_user` VALUES ('4', '主任', 'c4ca4238a0b923820dcc509a6f75849b', '1', '1', '4', '0', '1', '1522376372', '127.0.0.1', '0', '新建用户', '1522376372');
INSERT INTO `t_user` VALUES ('5', '副总', 'c4ca4238a0b923820dcc509a6f75849b', '1', '1', '5', '0', '1', '1522376385', '127.0.0.1', '0', '新建用户', '1522376385');
INSERT INTO `t_user` VALUES ('6', '总经理', 'c4ca4238a0b923820dcc509a6f75849b', '1', '1', '6', '0', '1', '1522376401', '127.0.0.1', '0', '新建用户', '1522376401');
INSERT INTO `t_user` VALUES ('7', '董事长', 'c4ca4238a0b923820dcc509a6f75849b', '1', '1', '7', '0', '1', '1522376413', '127.0.0.1', '0', '新建用户', '1522376413');

-- ----------------------------
-- Table structure for t_users
-- ----------------------------
DROP TABLE IF EXISTS `t_users`;
CREATE TABLE `t_users` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `email` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `email_verified_at` timestamp NULL DEFAULT NULL,
  `password` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `remember_token` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `t_users_email_unique` (`email`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ----------------------------
-- Records of t_users
-- ----------------------------

-- ----------------------------
-- Table structure for t_wf_entrust
-- ----------------------------
DROP TABLE IF EXISTS `t_wf_entrust`;
CREATE TABLE `t_wf_entrust` (
  `id` int NOT NULL AUTO_INCREMENT,
  `flow_id` int NOT NULL COMMENT '运行id',
  `flow_process` int NOT NULL COMMENT '运行步骤id',
  `entrust_title` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci DEFAULT NULL COMMENT '标题',
  `entrust_user` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL COMMENT '被授权人',
  `entrust_name` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL COMMENT '被授权人名称',
  `entrust_stime` int NOT NULL COMMENT '授权开始时间',
  `entrust_etime` int NOT NULL COMMENT '授权结束时间',
  `entrust_con` longtext CHARACTER SET utf8 COLLATE utf8_general_ci COMMENT '授权备注',
  `add_time` int DEFAULT NULL COMMENT '添加时间',
  `old_user` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL COMMENT '授权人',
  `old_name` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL COMMENT '授权人名称',
  PRIMARY KEY (`id`) USING BTREE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 ROW_FORMAT=DYNAMIC COMMENT='委托授权表';

-- ----------------------------
-- Records of t_wf_entrust
-- ----------------------------

-- ----------------------------
-- Table structure for t_wf_entrust_rel
-- ----------------------------
DROP TABLE IF EXISTS `t_wf_entrust_rel`;
CREATE TABLE `t_wf_entrust_rel` (
  `id` int NOT NULL AUTO_INCREMENT COMMENT '主键',
  `entrust_id` int NOT NULL COMMENT '授权id',
  `process_id` int NOT NULL COMMENT '步骤id',
  `status` int NOT NULL DEFAULT '0' COMMENT '状态0为新增，2为办结',
  `add_time` datetime DEFAULT NULL COMMENT '添加日期',
  PRIMARY KEY (`id`) USING BTREE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 ROW_FORMAT=DYNAMIC COMMENT='流程授权关系表';

-- ----------------------------
-- Records of t_wf_entrust_rel
-- ----------------------------

-- ----------------------------
-- Table structure for t_wf_event
-- ----------------------------
DROP TABLE IF EXISTS `t_wf_event`;
CREATE TABLE `t_wf_event` (
  `id` int NOT NULL AUTO_INCREMENT COMMENT '主键',
  `type` varchar(50) CHARACTER SET utf8 COLLATE utf8_general_ci DEFAULT NULL,
  `act` varchar(60) CHARACTER SET utf8 COLLATE utf8_general_ci DEFAULT NULL,
  `code` longtext CHARACTER SET utf8 COLLATE utf8_general_ci COMMENT '代码',
  `uid` int DEFAULT NULL COMMENT ' 用户id',
  `uptime` int DEFAULT NULL COMMENT '更新时间',
  PRIMARY KEY (`id`) USING BTREE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 ROW_FORMAT=DYNAMIC;

-- ----------------------------
-- Records of t_wf_event
-- ----------------------------

-- ----------------------------
-- Table structure for t_wf_flow
-- ----------------------------
DROP TABLE IF EXISTS `t_wf_flow`;
CREATE TABLE `t_wf_flow` (
  `id` int unsigned NOT NULL AUTO_INCREMENT,
  `type` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci DEFAULT NULL COMMENT '流程类别',
  `flow_name` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT '' COMMENT '流程名称',
  `flow_desc` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT '' COMMENT '描述',
  `sort_order` mediumint unsigned NOT NULL DEFAULT '0' COMMENT '排序',
  `status` tinyint unsigned NOT NULL DEFAULT '0' COMMENT '0不可用1正常',
  `is_del` tinyint unsigned NOT NULL DEFAULT '0',
  `uid` int DEFAULT NULL COMMENT '添加用户',
  `add_time` int DEFAULT NULL COMMENT '添加时间',
  `is_field` int DEFAULT '0' COMMENT '是否开启过滤',
  `field_name` varchar(50) CHARACTER SET utf8 COLLATE utf8_unicode_ci DEFAULT NULL COMMENT '字段名',
  `field_value` int DEFAULT '0' COMMENT '字段值',
  `tmp` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci DEFAULT '' COMMENT '模板字段',
  PRIMARY KEY (`id`) USING BTREE
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8mb3 ROW_FORMAT=DYNAMIC COMMENT='*工作流表';

-- ----------------------------
-- Records of t_wf_flow
-- ----------------------------
INSERT INTO `t_wf_flow` VALUES ('1', 'yuyuekongzhi', '预约审批流', '0', '0', '0', '0', '1', '1644110496', '0', '0', '0', '0');
INSERT INTO `t_wf_flow` VALUES ('2', 'cnt', '合同审批流', '1', '1', '0', '0', '1', '1645099841', '0', '', '0', '1');
INSERT INTO `t_wf_flow` VALUES ('3', 'oa_seal', '请假审批流', '1', '1', '0', '0', '1', '1646740539', '0', '', '0', '1');
INSERT INTO `t_wf_flow` VALUES ('4', 'cnt_gl', '合同管理222', '1', '1', '0', '0', '1', '1648114215', '0', '', '0', '【id】【name】');

-- ----------------------------
-- Table structure for t_wf_flow_process
-- ----------------------------
DROP TABLE IF EXISTS `t_wf_flow_process`;
CREATE TABLE `t_wf_flow_process` (
  `id` int NOT NULL AUTO_INCREMENT,
  `flow_id` int unsigned NOT NULL DEFAULT '0' COMMENT '流程ID',
  `process_name` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT '步骤' COMMENT '步骤名称',
  `process_type` char(50) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL DEFAULT '' COMMENT '步骤类型',
  `process_to` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT '' COMMENT '转交下一步骤号',
  `auto_person` tinyint unsigned NOT NULL DEFAULT '4' COMMENT '3自由选择|4指定人员|5指定角色|6事务接受',
  `auto_sponsor_ids` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT '' COMMENT '4指定步骤主办人ids',
  `auto_sponsor_text` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT '' COMMENT '4指定步骤主办人text',
  `work_ids` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT '' COMMENT '6事务接受',
  `work_text` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT '' COMMENT '6事务接受',
  `work_auto` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci DEFAULT NULL,
  `work_condition` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci DEFAULT NULL,
  `work_val` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci DEFAULT NULL,
  `auto_role_ids` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT '' COMMENT '5角色ids',
  `auto_role_text` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT '' COMMENT '5角色 text',
  `range_user_ids` text CHARACTER SET utf8 COLLATE utf8_general_ci COMMENT '3自由选择IDS',
  `range_user_text` text CHARACTER SET utf8 COLLATE utf8_general_ci COMMENT '3自由选择用户ID',
  `is_sing` tinyint unsigned NOT NULL DEFAULT '1' COMMENT '1允许|2不允许',
  `is_back` tinyint unsigned NOT NULL DEFAULT '1' COMMENT '1允许|2不允许',
  `out_condition` text CHARACTER SET utf8 COLLATE utf8_general_ci COMMENT '转出条件',
  `setleft` varchar(100) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT '100' COMMENT '左 坐标',
  `settop` varchar(100) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT '100' COMMENT '上 坐标',
  `style` text CHARACTER SET utf8 COLLATE utf8_general_ci COMMENT '样式 序列化',
  `is_del` tinyint unsigned NOT NULL DEFAULT '0',
  `uptime` int unsigned NOT NULL DEFAULT '0' COMMENT '更新时间',
  `dateline` int unsigned NOT NULL DEFAULT '0',
  `wf_mode` int unsigned NOT NULL DEFAULT '0' COMMENT '0 单一线性，1，转出条件 2，同步模式',
  `wf_action` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT 'view' COMMENT '对应方法',
  `work_sql` longtext CHARACTER SET utf8 COLLATE utf8_general_ci,
  `work_msg` longtext CHARACTER SET utf8 COLLATE utf8_general_ci,
  `auto_xt_text` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci DEFAULT '' COMMENT '2协同字段',
  `auto_xt_ids` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci DEFAULT '' COMMENT '2协同字段',
  PRIMARY KEY (`id`) USING BTREE
) ENGINE=InnoDB AUTO_INCREMENT=18 DEFAULT CHARSET=utf8mb3 ROW_FORMAT=DYNAMIC COMMENT='工作流设计主表';

-- ----------------------------
-- Records of t_wf_flow_process
-- ----------------------------
INSERT INTO `t_wf_flow_process` VALUES ('1', '2', '开始', 'node-start', '2', '4', '1', 'admin', '1', '', '', '=', '', '', '', '', '', '1', '1', '[]', '-160', '-80', '{\"width\":55,\"height\":35,\"color\":\"#2d6dcc\"}', '0', '1645102663', '0', '0', 'sfdp@view@sid@1', null, null, '', '');
INSERT INTO `t_wf_flow_process` VALUES ('2', '2', '步骤', 'node-flow', '3', '5', '', '', '1', '', '', '=', '', '1', '系统管理员', '', '', '1', '1', '[]', '-60', '-10', '{\"width\":60,\"height\":40,\"color\":\"#2d6dcc\"}', '0', '1645102663', '0', '0', 'sfdp@add@sid@1', null, null, '', '');
INSERT INTO `t_wf_flow_process` VALUES ('3', '2', '结束', 'node-end', '', '4', '', '', '', '', null, null, null, '', '', null, null, '1', '1', null, '120', '190', '{\"width\":60,\"height\":60,\"color\":\"#2d6dcc\"}', '0', '1645102663', '0', '0', 'view', null, null, '', '');
INSERT INTO `t_wf_flow_process` VALUES ('6', '1', '开始', 'node-start', '', '4', '', '', '', '', null, null, null, '', '', null, null, '1', '1', null, '-230', '-210', '{\"width\":55,\"height\":35,\"color\":\"#2d6dcc\"}', '0', '0', '0', '0', 'view', null, null, '', '');
INSERT INTO `t_wf_flow_process` VALUES ('7', '3', '开始', 'node-start', '8', '4', '1', 'admin', '1', '', '', '=', '', '', '', '', '', '1', '1', '[]', '-230', '-40', '{\"width\":55,\"height\":35,\"color\":\"#2d6dcc\"}', '0', '1646740567', '0', '0', 'sfdp@view@sid@9', null, null, '', '');
INSERT INTO `t_wf_flow_process` VALUES ('8', '3', '步骤', 'node-flow', '9', '4', '1', 'admin', '1', '', '', '=', '', '', '', '', '', '1', '1', '[]', '-150', '70', '{\"width\":60,\"height\":40,\"color\":\"#2d6dcc\"}', '0', '1646740567', '0', '0', 'sfdp@wfedit@sid@9', null, null, '', '');
INSERT INTO `t_wf_flow_process` VALUES ('9', '3', '结束', 'node-end', '', '4', '', '', '', '', null, null, null, '', '', null, null, '1', '1', null, '-30', '100', '{\"width\":60,\"height\":60,\"color\":\"#2d6dcc\"}', '0', '1646740567', '0', '0', 'view', null, null, '', '');
INSERT INTO `t_wf_flow_process` VALUES ('10', '4', '开始', 'node-start', '11', '4', '1', 'admin', '1', '', '', '=', '', '', '', '', '', '1', '1', '[]', '-290', '-50', '{\"width\":55,\"height\":35,\"color\":\"#2d6dcc\"}', '0', '1648094889', '0', '0', 'sfdp@view@sid@14', null, null, '', '');
INSERT INTO `t_wf_flow_process` VALUES ('11', '4', '步骤', 'node-flow', '16,12', '4', '1', 'admin', '1', '', '', '=', '', '', '', '', '', '1', '1', '[]', '-120', '-20', '{\"width\":60,\"height\":40,\"color\":\"#2d6dcc\"}', '0', '1648114134', '0', '0', 'sfdp@view@sid@14', null, null, '', '');
INSERT INTO `t_wf_flow_process` VALUES ('12', '4', '结束', 'node-end', '', '4', '', '', '', '', null, null, null, '', '', null, null, '1', '1', null, '70', '190', '{\"width\":60,\"height\":60,\"color\":\"#2d6dcc\"}', '0', '1648094889', '0', '0', 'view', null, null, '', '');
INSERT INTO `t_wf_flow_process` VALUES ('16', '4', '抄送', 'node-cc', '', '5', '3,1', '11,admin', '1', 'uid', '', '=', '', '1', '系统管理员', '', '', '1', '1', '[]', '-122', '190', '{\"width\":45,\"height\":45,\"color\":\"#2d6dcc\"}', '0', '1648094889', '0', '0', 'view', null, null, '', '');

-- ----------------------------
-- Table structure for t_wf_kpi_data
-- ----------------------------
DROP TABLE IF EXISTS `t_wf_kpi_data`;
CREATE TABLE `t_wf_kpi_data` (
  `id` int NOT NULL AUTO_INCREMENT,
  `k_node` varchar(50) CHARACTER SET utf8 COLLATE utf8_unicode_ci DEFAULT NULL,
  `k_uid` int NOT NULL COMMENT '用户id',
  `k_role` varchar(50) CHARACTER SET utf8 COLLATE utf8_general_ci DEFAULT NULL COMMENT '角色id',
  `k_type` varchar(50) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL COMMENT '单据类别',
  `k_type_id` int NOT NULL COMMENT '单据id',
  `k_describe` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci DEFAULT NULL COMMENT '描述',
  `k_mark` tinyint NOT NULL DEFAULT '1' COMMENT '绩效总分',
  `k_base` tinyint NOT NULL DEFAULT '1' COMMENT '基础分',
  `k_isout` tinyint NOT NULL DEFAULT '0' COMMENT '是否超时 0=未超时 1=超时',
  `k_year` varchar(10) CHARACTER SET utf8 COLLATE utf8_general_ci DEFAULT NULL COMMENT '添加年',
  `k_month` varchar(10) CHARACTER SET utf8 COLLATE utf8_general_ci DEFAULT NULL COMMENT '添加月',
  `k_date` date DEFAULT NULL COMMENT '添加日期',
  `k_create_time` int DEFAULT NULL COMMENT '添加时间',
  PRIMARY KEY (`id`) USING BTREE
) ENGINE=InnoDB AUTO_INCREMENT=9 DEFAULT CHARSET=utf8mb3 COLLATE=utf8_unicode_ci ROW_FORMAT=DYNAMIC COMMENT='工作流用户绩效明细表';

-- ----------------------------
-- Records of t_wf_kpi_data
-- ----------------------------
INSERT INTO `t_wf_kpi_data` VALUES ('1', 'node-start', '1', '1', 'cnt_gl', '15', '合同管理', '0', '1', '0', '2022', '04', '2022-04-20', '1650464159');
INSERT INTO `t_wf_kpi_data` VALUES ('2', 'node-flow', '1', '1', 'cnt_gl', '15', '合同管理', '0', '1', '0', '2022', '04', '2022-04-20', '1650464167');
INSERT INTO `t_wf_kpi_data` VALUES ('3', 'node-flow', '1', '1', 'cnt_gl', '15', '合同管理', '0', '1', '0', '2022', '04', '2022-04-20', '1650464177');
INSERT INTO `t_wf_kpi_data` VALUES ('4', 'node-start', '1', '1', 'cnt_gl', '18', '合同管理', '0', '1', '0', '2022', '04', '2022-04-23', '1650710574');
INSERT INTO `t_wf_kpi_data` VALUES ('5', 'node-flow', '1', '1', 'cnt_gl', '18', '合同管理', '0', '1', '0', '2022', '04', '2022-04-23', '1650710580');
INSERT INTO `t_wf_kpi_data` VALUES ('6', 'node-flow', '1', '1', 'cnt_gl', '18', '合同管理', '0', '1', '0', '2022', '04', '2022-04-23', '1650710594');
INSERT INTO `t_wf_kpi_data` VALUES ('7', 'node-flow', '1', '1', 'cnt', '2', '合同管理', '0', '1', '0', '2022', '05', '2022-05-27', '1653656216');
INSERT INTO `t_wf_kpi_data` VALUES ('8', 'node-start', '1', '1', 'cnt', '5', '合同管理', '0', '1', '0', '2022', '05', '2022-05-27', '1653658102');

-- ----------------------------
-- Table structure for t_wf_kpi_month
-- ----------------------------
DROP TABLE IF EXISTS `t_wf_kpi_month`;
CREATE TABLE `t_wf_kpi_month` (
  `id` int NOT NULL AUTO_INCREMENT,
  `k_uid` int NOT NULL COMMENT '用户id',
  `k_role` varchar(50) CHARACTER SET utf8 COLLATE utf8_general_ci DEFAULT NULL COMMENT '角色id',
  `k_mark` bigint NOT NULL DEFAULT '1' COMMENT '绩效总分',
  `k_time` int NOT NULL DEFAULT '1' COMMENT '基础分',
  `k_year` varchar(10) CHARACTER SET utf8 COLLATE utf8_general_ci DEFAULT NULL COMMENT '添加年',
  `k_month` varchar(10) CHARACTER SET utf8 COLLATE utf8_general_ci DEFAULT NULL COMMENT '添加月',
  `k_create_time` int DEFAULT NULL COMMENT '添加时间',
  PRIMARY KEY (`id`) USING BTREE
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb3 COLLATE=utf8_unicode_ci ROW_FORMAT=DYNAMIC COMMENT='用户绩效月度绩效';

-- ----------------------------
-- Records of t_wf_kpi_month
-- ----------------------------
INSERT INTO `t_wf_kpi_month` VALUES ('2', '1', '1', '0', '6', '2022', '04', '1650464159');
INSERT INTO `t_wf_kpi_month` VALUES ('3', '1', '1', '0', '2', '2022', '05', '1653656216');

-- ----------------------------
-- Table structure for t_wf_kpi_year
-- ----------------------------
DROP TABLE IF EXISTS `t_wf_kpi_year`;
CREATE TABLE `t_wf_kpi_year` (
  `id` int NOT NULL AUTO_INCREMENT,
  `k_uid` int NOT NULL COMMENT '用户id',
  `k_role` varchar(50) CHARACTER SET utf8 COLLATE utf8_general_ci DEFAULT NULL COMMENT '角色id',
  `k_mark` bigint NOT NULL DEFAULT '1' COMMENT '绩效总分',
  `k_time` int NOT NULL DEFAULT '1' COMMENT '总次数',
  `k_year` varchar(10) CHARACTER SET utf8 COLLATE utf8_general_ci DEFAULT NULL COMMENT '添加年',
  `k_create_time` int DEFAULT NULL COMMENT '添加时间',
  PRIMARY KEY (`id`) USING BTREE
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb3 COLLATE=utf8_unicode_ci ROW_FORMAT=DYNAMIC COMMENT='工作流绩效年度总表';

-- ----------------------------
-- Records of t_wf_kpi_year
-- ----------------------------
INSERT INTO `t_wf_kpi_year` VALUES ('1', '1', '1', '0', '8', '2022', '1650464159');

-- ----------------------------
-- Table structure for t_wf_run
-- ----------------------------
DROP TABLE IF EXISTS `t_wf_run`;
CREATE TABLE `t_wf_run` (
  `id` int unsigned NOT NULL AUTO_INCREMENT,
  `from_table` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci DEFAULT NULL COMMENT '单据表，不带前缀',
  `from_id` int DEFAULT NULL,
  `uid` int unsigned NOT NULL DEFAULT '0',
  `flow_id` int unsigned NOT NULL DEFAULT '0' COMMENT '流程id 正常流程',
  `run_flow_id` int unsigned NOT NULL DEFAULT '0' COMMENT '流转到什么ID',
  `run_flow_process` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci DEFAULT NULL COMMENT '流转到第几步',
  `endtime` int unsigned NOT NULL DEFAULT '0' COMMENT '结束时间',
  `status` int unsigned NOT NULL DEFAULT '0' COMMENT '状态，0流程中，1通过',
  `is_del` tinyint unsigned NOT NULL DEFAULT '0',
  `uptime` int unsigned NOT NULL DEFAULT '0',
  `dateline` int unsigned NOT NULL DEFAULT '0',
  `is_sing` int NOT NULL DEFAULT '0',
  `sing_id` int DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE,
  KEY `uid` (`uid`) USING BTREE,
  KEY `is_del` (`is_del`) USING BTREE
) ENGINE=InnoDB AUTO_INCREMENT=17 DEFAULT CHARSET=utf8mb3 ROW_FORMAT=DYNAMIC COMMENT='工作流运行主表';

-- ----------------------------
-- Records of t_wf_run
-- ----------------------------
INSERT INTO `t_wf_run` VALUES ('1', 'cnt', '1', '1', '2', '2', '2', '1645100021', '1', '0', '0', '1645099928', '0', null);
INSERT INTO `t_wf_run` VALUES ('2', 'cnt', '3', '1', '2', '2', '2', '1645102706', '1', '0', '0', '1645102685', '0', null);
INSERT INTO `t_wf_run` VALUES ('5', 'cnt', '2', '1', '2', '2', '2', '1653656216', '1', '0', '0', '1647352136', '0', null);
INSERT INTO `t_wf_run` VALUES ('7', 'cnt', '4', '1', '2', '2', '2', '1648114196', '1', '0', '0', '1648114183', '0', null);
INSERT INTO `t_wf_run` VALUES ('15', 'cnt_gl', '18', '1', '4', '4', '11', '1650710594', '1', '0', '0', '1650710574', '0', null);
INSERT INTO `t_wf_run` VALUES ('16', 'cnt', '5', '1', '2', '2', '1', '0', '0', '0', '0', '1653658102', '0', null);

-- ----------------------------
-- Table structure for t_wf_run_log
-- ----------------------------
DROP TABLE IF EXISTS `t_wf_run_log`;
CREATE TABLE `t_wf_run_log` (
  `id` int unsigned NOT NULL AUTO_INCREMENT,
  `uid` int unsigned NOT NULL DEFAULT '0' COMMENT '用户ID',
  `from_id` int DEFAULT NULL COMMENT '单据ID',
  `from_table` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci DEFAULT NULL COMMENT '单据表',
  `run_id` int unsigned NOT NULL DEFAULT '0' COMMENT '流转id',
  `run_flow` int unsigned NOT NULL DEFAULT '0' COMMENT '流程ID',
  `content` text CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL COMMENT '日志内容',
  `dateline` int unsigned NOT NULL DEFAULT '0' COMMENT '添加时间',
  `btn` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci DEFAULT NULL COMMENT '提交操作信息',
  `art` longtext CHARACTER SET utf8 COLLATE utf8_general_ci COMMENT '附件日志',
  `work_info` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci DEFAULT NULL COMMENT '事务日志',
  PRIMARY KEY (`id`) USING BTREE,
  KEY `uid` (`uid`) USING BTREE,
  KEY `run_id` (`run_id`) USING BTREE
) ENGINE=InnoDB AUTO_INCREMENT=46 DEFAULT CHARSET=utf8mb3 ROW_FORMAT=DYNAMIC COMMENT='工作流日志表';

-- ----------------------------
-- Records of t_wf_run_log
-- ----------------------------
INSERT INTO `t_wf_run_log` VALUES ('1', '1', '1', 'cnt', '1', '0', '123', '1645099928', 'Send', '', '');
INSERT INTO `t_wf_run_log` VALUES ('2', '1', '1', 'cnt', '1', '0', '123123', '1645100003', 'ok', '', 'work_sql:null|work_msg:null');
INSERT INTO `t_wf_run_log` VALUES ('3', '1', '1', 'cnt', '1', '0', '123', '1645100021', 'ok', '', 'work_sql:null|work_msg:null');
INSERT INTO `t_wf_run_log` VALUES ('4', '1', '3', 'cnt', '2', '0', '保存后-发起', '1645102685', 'Send', '', '');
INSERT INTO `t_wf_run_log` VALUES ('5', '1', '3', 'cnt', '2', '0', '123', '1645102691', 'ok', '', 'work_sql:null|work_msg:null');
INSERT INTO `t_wf_run_log` VALUES ('6', '1', '3', 'cnt', '2', '0', '123', '1645102706', 'ok', '', 'work_sql:null|work_msg:null');
INSERT INTO `t_wf_run_log` VALUES ('13', '1', '2', 'cnt', '5', '0', '12', '1647352136', 'Send', '', '');
INSERT INTO `t_wf_run_log` VALUES ('17', '1', '4', 'cnt', '7', '0', '123', '1648114183', 'Send', '', '');
INSERT INTO `t_wf_run_log` VALUES ('18', '1', '4', 'cnt', '7', '0', '12', '1648114189', 'ok', '', 'work_sql:null|work_msg:null');
INSERT INTO `t_wf_run_log` VALUES ('19', '1', '4', 'cnt', '7', '0', '123', '1648114196', 'ok', '', 'work_sql:null|work_msg:null');
INSERT INTO `t_wf_run_log` VALUES ('39', '1', '18', 'cnt_gl', '15', '0', '123', '1650710574', 'Send', '', '');
INSERT INTO `t_wf_run_log` VALUES ('40', '1', '18', 'cnt_gl', '15', '0', '1', '1650710580', 'ok', '', 'work_sql:null|work_msg:null');
INSERT INTO `t_wf_run_log` VALUES ('41', '1', '18', 'cnt_gl', '15', '0', '签阅成功', '1650710588', 'CC', '', '');
INSERT INTO `t_wf_run_log` VALUES ('42', '1', '18', 'cnt_gl', '15', '0', '1', '1650710594', 'ok', '', 'work_sql:null|work_msg:null');
INSERT INTO `t_wf_run_log` VALUES ('43', '1', '2', 'cnt', '5', '0', '123', '1653656210', 'ok', '', 'work_sql:null|work_msg:null');
INSERT INTO `t_wf_run_log` VALUES ('44', '1', '2', 'cnt', '5', '0', '123', '1653656216', 'ok', '', 'work_sql:null|work_msg:null');
INSERT INTO `t_wf_run_log` VALUES ('45', '1', '5', 'cnt', '16', '0', '1', '1653658102', 'Send', '', '');

-- ----------------------------
-- Table structure for t_wf_run_process
-- ----------------------------
DROP TABLE IF EXISTS `t_wf_run_process`;
CREATE TABLE `t_wf_run_process` (
  `id` int unsigned NOT NULL AUTO_INCREMENT,
  `uid` int unsigned NOT NULL DEFAULT '0',
  `run_id` int unsigned NOT NULL DEFAULT '0' COMMENT '当前流转id',
  `run_flow` int unsigned NOT NULL DEFAULT '0' COMMENT '属于那个流程的id',
  `run_flow_process` smallint unsigned NOT NULL DEFAULT '0' COMMENT '当前步骤编号',
  `remark` text CHARACTER SET utf8 COLLATE utf8_general_ci COMMENT '备注',
  `auto_person` tinyint DEFAULT NULL,
  `sponsor_text` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci DEFAULT NULL,
  `sponsor_ids` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci DEFAULT NULL,
  `is_sing` tinyint unsigned NOT NULL DEFAULT '0' COMMENT '是否已会签过',
  `is_back` tinyint unsigned NOT NULL DEFAULT '0' COMMENT '被退回的 0否(默认) 1是',
  `status` tinyint unsigned NOT NULL DEFAULT '0' COMMENT '状态 0为未接收（默认），1为办理中 ,2为已转交,3为已结束4为已打回',
  `js_time` int unsigned NOT NULL DEFAULT '0' COMMENT '接收时间',
  `bl_time` int unsigned NOT NULL DEFAULT '0' COMMENT '办理时间',
  `is_del` tinyint unsigned NOT NULL DEFAULT '0',
  `updatetime` int unsigned NOT NULL DEFAULT '0',
  `dateline` int unsigned NOT NULL DEFAULT '0' COMMENT '添加时间',
  `wf_mode` int DEFAULT NULL,
  `wf_action` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE,
  KEY `uid` (`uid`) USING BTREE,
  KEY `run_id` (`run_id`) USING BTREE,
  KEY `status` (`status`) USING BTREE,
  KEY `is_del` (`is_del`) USING BTREE
) ENGINE=InnoDB AUTO_INCREMENT=31 DEFAULT CHARSET=utf8mb3 ROW_FORMAT=DYNAMIC COMMENT='工作流运行步骤表';

-- ----------------------------
-- Records of t_wf_run_process
-- ----------------------------
INSERT INTO `t_wf_run_process` VALUES ('1', '1', '1', '2', '1', '123123', '4', 'admin', '1', '1', '1', '2', '1645099928', '1645100003', '0', '0', '1645099928', '0', 'sfdp@view@sid@1');
INSERT INTO `t_wf_run_process` VALUES ('2', '1', '1', '2', '2', '123', '5', '系统管理员', '1', '1', '1', '2', '1645100003', '1645100021', '0', '0', '1645100003', '0', 'sfdp@view@sid@1');
INSERT INTO `t_wf_run_process` VALUES ('3', '1', '2', '2', '1', '123', '4', 'admin', '1', '1', '1', '2', '1645102685', '1645102691', '0', '0', '1645102685', '0', 'sfdp@view@sid@1');
INSERT INTO `t_wf_run_process` VALUES ('4', '1', '2', '2', '2', '123', '5', '系统管理员', '1', '1', '1', '2', '1645102691', '1645102706', '0', '0', '1645102691', '0', 'sfdp@wfedit@sid@1');
INSERT INTO `t_wf_run_process` VALUES ('9', '1', '5', '2', '1', '123', '4', 'admin', '1', '1', '1', '2', '1647352136', '1653656210', '0', '0', '1647352136', '0', 'sfdp@view@sid@1');
INSERT INTO `t_wf_run_process` VALUES ('12', '1', '7', '2', '1', '12', '4', 'admin', '1', '1', '1', '2', '1648114183', '1648114189', '0', '0', '1648114183', '0', 'sfdp@view@sid@1');
INSERT INTO `t_wf_run_process` VALUES ('13', '1', '7', '2', '2', '123', '5', '系统管理员', '1', '1', '1', '2', '1648114189', '1648114196', '0', '0', '1648114189', '0', 'sfdp@add@sid@1');
INSERT INTO `t_wf_run_process` VALUES ('27', '1', '15', '4', '10', '1', '4', 'admin', '1', '1', '1', '2', '1650710574', '1650710580', '0', '0', '1650710574', '0', 'sfdp@view@sid@14');
INSERT INTO `t_wf_run_process` VALUES ('28', '1', '15', '4', '11', '1', '4', 'admin', '1', '1', '1', '2', '1650710580', '1650710594', '0', '0', '1650710580', '0', 'sfdp@view@sid@14');
INSERT INTO `t_wf_run_process` VALUES ('29', '1', '5', '2', '2', '123', '5', '系统管理员', '1', '1', '1', '2', '1653656210', '1653656216', '0', '0', '1653656210', '0', 'sfdp@add@sid@1');
INSERT INTO `t_wf_run_process` VALUES ('30', '1', '16', '2', '1', '', '4', 'admin', '1', '1', '1', '0', '1653658102', '0', '0', '0', '1653658102', '0', 'sfdp@view@sid@1');

-- ----------------------------
-- Table structure for t_wf_run_process_cc
-- ----------------------------
DROP TABLE IF EXISTS `t_wf_run_process_cc`;
CREATE TABLE `t_wf_run_process_cc` (
  `id` int NOT NULL AUTO_INCREMENT,
  `from_id` int NOT NULL COMMENT '关联id',
  `from_table` varchar(255) DEFAULT NULL COMMENT '关联表',
  `uid` int DEFAULT NULL COMMENT '用户id',
  `run_id` varchar(255) DEFAULT NULL COMMENT '运行run 表id',
  `process_id` int DEFAULT NULL COMMENT '关联步骤id',
  `process_ccid` int DEFAULT NULL COMMENT '消息步骤id',
  `add_time` int DEFAULT NULL COMMENT '添加时间',
  `uptime` int DEFAULT NULL COMMENT '执行时间',
  `status` smallint NOT NULL DEFAULT '0' COMMENT '0 待确认 1，已确认',
  `auto_person` int DEFAULT NULL COMMENT '办理类别',
  `auto_ids` varchar(255) DEFAULT NULL COMMENT '办理ids',
  `user_ids` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci COMMENT='流程抄送表';

-- ----------------------------
-- Records of t_wf_run_process_cc
-- ----------------------------
INSERT INTO `t_wf_run_process_cc` VALUES ('3', '18', 'cnt_gl', '1', '15', '11', '16', '1650710582', '1650710588', '2', '5', '', '1');

-- ----------------------------
-- Table structure for t_wf_run_process_msg
-- ----------------------------
DROP TABLE IF EXISTS `t_wf_run_process_msg`;
CREATE TABLE `t_wf_run_process_msg` (
  `id` int NOT NULL AUTO_INCREMENT COMMENT '主键',
  `uid` int DEFAULT NULL COMMENT '用户id',
  `run_id` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci DEFAULT NULL COMMENT '运行run 表id',
  `process_id` int DEFAULT NULL COMMENT '关联步骤id',
  `process_msgid` int DEFAULT NULL COMMENT '消息步骤id',
  `add_time` int DEFAULT NULL COMMENT '添加时间',
  `uptime` int DEFAULT NULL COMMENT '执行时间',
  `status` smallint NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`) USING BTREE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 ROW_FORMAT=DYNAMIC;

-- ----------------------------
-- Records of t_wf_run_process_msg
-- ----------------------------

-- ----------------------------
-- Table structure for t_wf_run_sign
-- ----------------------------
DROP TABLE IF EXISTS `t_wf_run_sign`;
CREATE TABLE `t_wf_run_sign` (
  `id` int unsigned NOT NULL AUTO_INCREMENT,
  `uid` int unsigned NOT NULL DEFAULT '0',
  `run_id` int unsigned NOT NULL DEFAULT '0',
  `run_flow` int unsigned NOT NULL DEFAULT '0' COMMENT '流程ID,子流程时区分run step',
  `run_flow_process` smallint unsigned NOT NULL DEFAULT '0' COMMENT '当前步骤编号',
  `content` text CHARACTER SET utf8 COLLATE utf8_general_ci COMMENT '会签内容',
  `is_agree` tinyint unsigned NOT NULL DEFAULT '0' COMMENT '审核意见：1同意；2不同意',
  `sign_att_id` int unsigned NOT NULL DEFAULT '0',
  `dateline` int unsigned NOT NULL DEFAULT '0' COMMENT '添加时间',
  PRIMARY KEY (`id`) USING BTREE,
  KEY `uid` (`uid`) USING BTREE,
  KEY `run_id` (`run_id`) USING BTREE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 ROW_FORMAT=DYNAMIC COMMENT='工作流会签记录表';

-- ----------------------------
-- Records of t_wf_run_sign
-- ----------------------------

-- ----------------------------
-- Table structure for t_wf_workinfo
-- ----------------------------
DROP TABLE IF EXISTS `t_wf_workinfo`;
CREATE TABLE `t_wf_workinfo` (
  `id` int NOT NULL AUTO_INCREMENT,
  `bill_info` longtext CHARACTER SET utf8 COLLATE utf8_general_ci COMMENT '单据JSON',
  `data` longtext CHARACTER SET utf8 COLLATE utf8_general_ci COMMENT '处理数据',
  `info` longtext CHARACTER SET utf8 COLLATE utf8_general_ci COMMENT '处理结果',
  `datetime` datetime DEFAULT NULL,
  `type` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci DEFAULT NULL COMMENT '类型',
  PRIMARY KEY (`id`) USING BTREE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 ROW_FORMAT=DYNAMIC COMMENT='工作流实务信息表';

-- ----------------------------
-- Records of t_wf_workinfo
-- ----------------------------
