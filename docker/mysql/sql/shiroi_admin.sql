/*
 Navicat Premium Data Transfer

 Source Server         : localhost_3306
 Source Server Type    : MySQL
 Source Server Version : 50726
 Source Host           : 127.0.0.1:3306
 Source Schema         : shiroi_admin

 Target Server Type    : MySQL
 Target Server Version : 50726
 File Encoding         : 65001

 Date: 05/08/2023 17:14:44
*/

SET NAMES utf8mb4;
SET FOREIGN_KEY_CHECKS = 0;

-- ----------------------------
-- DataBase shiroi_admin
-- ----------------------------
create database if not exists `shiroi_admin` default character set utf8mb4 collate utf8mb4_unicode_ci;

-- ----------------------------
-- Table structure for admin_log
-- ----------------------------
CREATE TABLE IF NOT EXISTS `admin_log`  (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `admin_user_id` int(10) UNSIGNED NOT NULL COMMENT '用户',
  `name` varchar(30) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '' COMMENT '操作',
  `url` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '' COMMENT 'URL',
  `log_method` varchar(8) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '不记录' COMMENT '记录日志方法',
  `log_ip` varchar(20) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '' COMMENT '操作IP',
  `create_time` int(10) UNSIGNED NOT NULL DEFAULT 0 COMMENT '创建时间',
  `update_time` int(10) UNSIGNED NOT NULL DEFAULT 0 COMMENT '更新时间',
  `delete_time` int(10) UNSIGNED NOT NULL DEFAULT 0 COMMENT '删除时间',
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 1 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci COMMENT = '后台操作日志' ROW_FORMAT = Dynamic;

-- ----------------------------
-- Records of admin_log
-- ----------------------------

-- ----------------------------
-- Table structure for admin_log_data
-- ----------------------------
CREATE TABLE IF NOT EXISTS `admin_log_data`  (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `admin_log_id` int(10) UNSIGNED NOT NULL COMMENT '日志ID',
  `data` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL COMMENT '日志内容',
  `create_time` int(10) UNSIGNED NOT NULL DEFAULT 0 COMMENT '创建时间',
  `update_time` int(10) UNSIGNED NOT NULL DEFAULT 0 COMMENT '更新时间',
  `delete_time` int(10) UNSIGNED NOT NULL DEFAULT 0 COMMENT '删除时间',
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 1 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci COMMENT = '后台操作日志数据' ROW_FORMAT = Dynamic;

-- ----------------------------
-- Records of admin_log_data
-- ----------------------------

-- ----------------------------
-- Table structure for admin_menu
-- ----------------------------
CREATE TABLE IF NOT EXISTS `admin_menu`  (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `hash` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '' COMMENT '唯一标识',
  `parent_id` int(10) UNSIGNED NOT NULL DEFAULT 0 COMMENT '父级菜单',
  `name` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '' COMMENT '名称',
  `url` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '' COMMENT 'url',
  `icon` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'fas fa-list' COMMENT '图标',
  `is_show` tinyint(1) UNSIGNED NOT NULL DEFAULT 1 COMMENT '是否显示',
  `is_top` tinyint(1) UNSIGNED NOT NULL DEFAULT 0 COMMENT '是否为顶部菜单',
  `sort_number` int(10) UNSIGNED NOT NULL DEFAULT 1000 COMMENT '排序号',
  `log_method` varchar(8) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '不记录' COMMENT '记录日志方法',
  `create_time` int(10) UNSIGNED NOT NULL DEFAULT 0 COMMENT '创建时间',
  `update_time` int(10) UNSIGNED NOT NULL DEFAULT 0 COMMENT '更新时间',
  `delete_time` int(10) UNSIGNED NOT NULL DEFAULT 0 COMMENT '删除时间',
  PRIMARY KEY (`id`) USING BTREE,
  INDEX `index_url`(`url`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 68 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci COMMENT = '后台菜单' ROW_FORMAT = Dynamic;

-- ----------------------------
-- Records of admin_menu
-- ----------------------------
INSERT INTO `admin_menu` VALUES (1, '2bdc352555de1f79b63fb3deeaf59364', 0, '后台首页', 'admin/index/index', 'fas fa-home', 1, 1, 1, '不记录', 1691226861, 1691226861, 0);
INSERT INTO `admin_menu` VALUES (2, '5d7748f7c87b8d1f255b3f19aa305616', 0, '系统管理', 'admin/system/manage', 'fas fa-desktop', 1, 1, 2, '不记录', 1691226861, 1691226861, 0);
INSERT INTO `admin_menu` VALUES (3, '6917812d7be49b6a8b0016d1ae055717', 2, '用户管理', 'admin/admin_user/index', 'fas fa-user', 1, 0, 3, '不记录', 1691226861, 1691226861, 0);
INSERT INTO `admin_menu` VALUES (4, '142bf4c96973d197fc74c20ec8467d64', 3, '添加用户', 'admin/admin_user/add', 'fas fa-plus', 0, 0, 4, 'POST', 1691226861, 1691226861, 0);
INSERT INTO `admin_menu` VALUES (5, 'b841fd5835d55b02ca371dac2f3cd307', 3, '修改用户', 'admin/admin_user/edit', 'fas fa-edit', 0, 0, 5, 'POST', 1691226861, 1691226861, 0);
INSERT INTO `admin_menu` VALUES (6, '858e464d6e6ef68b201043b5a9a570bc', 3, '删除用户', 'admin/admin_user/del', 'fas fa-close', 0, 0, 6, 'POST', 1691226861, 1691226861, 0);
INSERT INTO `admin_menu` VALUES (7, '0c4c70a18743ddeb48ff3a8519a86004', 2, '角色管理', 'admin/admin_role/index', 'fas fa-user-friends', 1, 0, 7, '不记录', 1691226861, 1691226861, 0);
INSERT INTO `admin_menu` VALUES (8, 'c669425ed1b1afc0f83fcf431ea017b7', 7, '添加角色', 'admin/admin_role/add', 'fas fa-plus', 0, 0, 8, 'POST', 1691226861, 1691226861, 0);
INSERT INTO `admin_menu` VALUES (9, '8b805c75cddcb84f74eb6cadf84a52c3', 7, '修改角色', 'admin/admin_role/edit', 'fas fa-edit', 0, 0, 9, 'POST', 1691226861, 1691226861, 0);
INSERT INTO `admin_menu` VALUES (10, 'ded49e882d6f5ffebbab14e1260fa0c7', 7, '删除角色', 'admin/admin_role/del', 'fas fa-close', 0, 0, 10, 'POST', 1691226861, 1691226861, 0);
INSERT INTO `admin_menu` VALUES (11, '243ddec28fd0d5a0a7a36b33975cb13b', 7, '角色授权', 'admin/admin_role/access', 'fas fa-key', 0, 0, 11, 'POST', 1691226861, 1691226861, 0);
INSERT INTO `admin_menu` VALUES (12, '5127f643ea3865a2b7f7dec39e594b81', 2, '菜单管理', 'admin/admin_menu/index', 'fas fa-align-justify', 1, 0, 12, '不记录', 1691226861, 1691226861, 0);
INSERT INTO `admin_menu` VALUES (13, '373672f8a6252a201463467221f0fa45', 12, '添加菜单', 'admin/admin_menu/add', 'fas fa-plus', 0, 0, 13, 'POST', 1691226861, 1691226861, 0);
INSERT INTO `admin_menu` VALUES (14, 'd8ba3666bfb5375d0c7e597c9c54eeb1', 12, '修改菜单', 'admin/admin_menu/edit', 'fas fa-edit', 0, 0, 14, 'POST', 1691226861, 1691226861, 0);
INSERT INTO `admin_menu` VALUES (15, '8ed4301aa0ba437d6fb1f83f7d131252', 12, '删除菜单', 'admin/admin_menu/del', 'fas fa-close', 0, 0, 15, 'POST', 1691226861, 1691226861, 0);
INSERT INTO `admin_menu` VALUES (16, '3430eac7d2f1944fc86a8136bed06498', 2, '操作日志', 'admin/admin_log/index', 'fas fa-keyboard', 1, 0, 16, '不记录', 1691226861, 1691226861, 0);
INSERT INTO `admin_menu` VALUES (17, '801c38276dc01383e42014e81102978c', 16, '查看操作日志详情', 'admin/admin_log/view', 'fas fa-search-plus', 0, 0, 17, '不记录', 1691226861, 1691226861, 0);
INSERT INTO `admin_menu` VALUES (18, 'cd04f7ed665c54943022890976f6b049', 2, '个人资料', 'admin/admin_user/profile', 'fas fa-smile', 1, 0, 18, 'POST', 1691226861, 1691226861, 0);
INSERT INTO `admin_menu` VALUES (19, 'bc4b17293d0f71a9ae7854dec9151456', 2, '开发管理', 'admin/develop/manager', 'fas fa-code', 1, 0, 32, '不记录', 1691226861, 1691226861, 0);
INSERT INTO `admin_menu` VALUES (20, '7471730ae47ed85278b99c74ba46af09', 19, '代码生成', 'admin/generate/index', 'fas fa-file-code', 1, 0, 33, '不记录', 1691226861, 1691226861, 0);
INSERT INTO `admin_menu` VALUES (21, '955e1b95497a1ad613c95fe316aa8ece', 19, '设置配置', 'admin/develop/setting', 'fas fa-cogs', 1, 0, 34, '不记录', 1691226861, 1691226861, 0);
INSERT INTO `admin_menu` VALUES (22, '4f87cb5f31a72392730144f2f917bb47', 21, '设置管理', 'admin/setting/index', 'fas fa-cog', 1, 0, 35, '不记录', 1691226861, 1691226861, 0);
INSERT INTO `admin_menu` VALUES (23, '404d51cb6332bf25f8fa87c3a906871d', 22, '添加设置', 'admin/setting/add', 'fas fa-plus', 0, 0, 36, 'POST', 1691226861, 1691226861, 0);
INSERT INTO `admin_menu` VALUES (24, '1d4638fb966c3b6115a769832ce0da3e', 22, '修改设置', 'admin/setting/edit', 'fas fa-pencil', 0, 0, 37, 'POST', 1691226861, 1691226861, 0);
INSERT INTO `admin_menu` VALUES (25, '48cf4299ada39b97b247714bbf1cee01', 22, '删除设置', 'admin/setting/del', 'fas fa-trash', 0, 0, 38, 'POST', 1691226861, 1691226861, 0);
INSERT INTO `admin_menu` VALUES (26, 'de5a2d57136fea1fe79f0c303cb15687', 21, '设置分组管理', 'admin/setting_group/index', 'fas fa-list', 1, 0, 39, '不记录', 1691226861, 1691226861, 0);
INSERT INTO `admin_menu` VALUES (27, '31b4635f28e072bcd3d1bc4681ed2558', 26, '添加设置分组', 'admin/setting_group/add', 'fas fa-plus', 0, 0, 40, 'POST', 1691226861, 1691226861, 0);
INSERT INTO `admin_menu` VALUES (28, '0a49b62b71fc342a9dfd43e8a129c6a5', 26, '修改设置分组', 'admin/setting_group/edit', 'fas fa-pencil', 0, 0, 41, 'POST', 1691226861, 1691226861, 0);
INSERT INTO `admin_menu` VALUES (29, 'bd66d7868f663ece4a8a17e109fdcd1d', 26, '删除设置分组', 'admin/setting_group/del', 'fas fa-trash', 0, 0, 42, 'POST', 1691226861, 1691226861, 0);
INSERT INTO `admin_menu` VALUES (30, '49ea0b23b38f72030860bd2f54879c3d', 19, '数据维护', 'admin/database/table', 'fas fa-database', 1, 0, 49, '不记录', 1691226861, 1691226861, 0);
INSERT INTO `admin_menu` VALUES (31, '478acde145fa45266afe9f2108f1cc40', 30, '查看表详情', 'admin/database/view', 'fas fa-eye', 0, 0, 50, '不记录', 1691226861, 1691226861, 0);
INSERT INTO `admin_menu` VALUES (32, '4cc96ac1de1196f78a1a4d8dce00af45', 30, '优化表', 'admin/database/optimize', 'fas fa-refresh', 0, 0, 51, 'POST', 1691226861, 1691226861, 0);
INSERT INTO `admin_menu` VALUES (33, 'a274e7f296c3fc4095607f82e97fda3d', 30, '修复表', 'admin/database/repair', 'fas fa-circle-o-notch', 0, 0, 52, 'POST', 1691226861, 1691226861, 0);
INSERT INTO `admin_menu` VALUES (34, 'ef00ddf9220bffe2d265e8b7d7f89655', 0, '用户管理', 'admin/user/manage', 'fas fa-users', 1, 0, 19, '不记录', 1691226861, 1691226861, 0);
INSERT INTO `admin_menu` VALUES (35, '632dc8b225070beba5d1f3f5c1d27f84', 34, '用户管理', 'admin/user/index', 'fas fa-user', 1, 0, 20, '不记录', 1691226861, 1691226861, 0);
INSERT INTO `admin_menu` VALUES (36, '5ef00dbcb0b831f13dfe4cbe5a23f2b8', 35, '添加用户', 'admin/user/add', 'fas fa-plus', 0, 0, 21, 'POST', 1691226861, 1691226861, 0);
INSERT INTO `admin_menu` VALUES (37, '6a8a61e862251b74665d9ad44e47f19d', 35, '修改用户', 'admin/user/edit', 'fas fa-pencil', 0, 0, 22, 'POST', 1691226861, 1691226861, 0);
INSERT INTO `admin_menu` VALUES (38, '27d99f3f509b07c0de6438394db5f4b9', 35, '删除用户', 'admin/user/del', 'fas fa-trash', 0, 0, 23, 'POST', 1691226861, 1691226861, 0);
INSERT INTO `admin_menu` VALUES (39, 'cde742fa779d8b19bf7b37b23d26e507', 35, '启用用户', 'admin/user/enable', 'fas fa-circle', 0, 0, 24, 'POST', 1691226861, 1691226861, 0);
INSERT INTO `admin_menu` VALUES (40, 'befa3cfd961faaab2d66261cf3876a14', 35, '禁用用户', 'admin/user/disable', 'fas fa-circle', 0, 0, 25, 'POST', 1691226861, 1691226861, 0);
INSERT INTO `admin_menu` VALUES (41, '4195e837120f302e887e0f8b524d8926', 34, '用户等级管理', 'admin/user_level/index', 'fas fa-th-list', 1, 0, 26, '不记录', 1691226861, 1691226861, 0);
INSERT INTO `admin_menu` VALUES (42, '9c2809cb12871eddee1f2459ab01c40e', 41, '添加用户等级', 'admin/user_level/add', 'fas fa-plus', 0, 0, 27, 'POST', 1691226861, 1691226861, 0);
INSERT INTO `admin_menu` VALUES (43, 'edbed8be8c5accac618feff257879295', 41, '修改用户等级', 'admin/user_level/edit', 'fas fa-pencil', 0, 0, 28, 'POST', 1691226861, 1691226861, 0);
INSERT INTO `admin_menu` VALUES (44, 'e45efee22659e4e153e7e51e5a165771', 41, '删除用户等级', 'admin/user_level/del', 'fas fa-trash', 0, 0, 29, 'POST', 1691226861, 1691226861, 0);
INSERT INTO `admin_menu` VALUES (45, 'b1659dd053e84179c953e2a385202468', 41, '启用用户等级', 'admin/user_level/enable', 'fas fa-circle', 0, 0, 30, 'POST', 1691226861, 1691226861, 0);
INSERT INTO `admin_menu` VALUES (46, '5ad612c6f5278b43b08ec350b090a775', 41, '禁用用户等级', 'admin/user_level/disable', 'fas fa-circle', 0, 0, 31, 'POST', 1691226861, 1691226861, 0);
INSERT INTO `admin_menu` VALUES (47, '7223559326415427ac5f82b9cf3293cb', 0, '设置中心', 'admin/setting/center', 'fas fa-cogs', 1, 0, 43, '不记录', 1691226861, 1691226861, 0);
INSERT INTO `admin_menu` VALUES (48, '6730dcd3ef0a2d53ad6d7d8a02eee95a', 47, '所有配置', 'admin/setting/all', 'fas fa-list', 1, 0, 44, '不记录', 1691226861, 1691226861, 0);
INSERT INTO `admin_menu` VALUES (49, '484a9864028fd6c63728853ca5ea3c03', 47, '后台设置', 'admin/setting/admin', 'fas fa-adjust', 1, 0, 45, '不记录', 1691226861, 1691226861, 0);
INSERT INTO `admin_menu` VALUES (50, 'b3e243f6222d2afd2a822fe873208a17', 47, '前台设置', 'admin/setting/api', 'fas fa-user', 1, 0, 45, '不记录', 1691226861, 1691226861, 0);
INSERT INTO `admin_menu` VALUES (51, 'd12fc5597092044afd9d8c5200c13e11', 47, '更新设置', 'admin/setting/update', 'fas fa-pencil', 0, 0, 46, 'POST', 1691226861, 1691226861, 0);
INSERT INTO `admin_menu` VALUES (52, 'b54d56991fbb6f85749ad93a4c96af13', 0, '通用操作', 'admin/common/option', 'fas fa-list', 0, 0, 53, '不记录', 1691226861, 1691226861, 0);
INSERT INTO `admin_menu` VALUES (53, 'd33f670c0c61632a4d0e18fefd7d9414', 52, '表单上传文件', 'admin/file/upload', 'fas fa-cloud-upload-alt', 0, 0, 54, '不记录', 1691226861, 1691226861, 0);
INSERT INTO `admin_menu` VALUES (54, 'fa7cd7a748ed06c8740f53a6f001090c', 52, '编辑器上传文件', 'admin/file/editor', 'fas fa-upload', 0, 0, 55, '不记录', 1691226861, 1691226861, 0);
INSERT INTO `admin_menu` VALUES (55, '74a776b3cc7eff052c6efb75ac6e2e7f', 0, '测试案例', 'admin/common/option', 'fas fa-list', 1, 0, 1000, '不记录', 1691226861, 1691226861, 0);
INSERT INTO `admin_menu` VALUES (56, 'aeb5a7a8dce390032e602493c7e69c53', 55, '测试案例（一）', 'admin/test/index', 'fas fa-list', 1, 0, 1000, '不记录', 1691226861, 1691226861, 0);
INSERT INTO `admin_menu` VALUES (57, '7836746fdd50bd2f6d7dc6fe9e6f0514', 56, '添加测试', 'admin/test/add', 'fas fa-plus', 0, 0, 1000, 'POST', 1691226861, 1691226861, 0);
INSERT INTO `admin_menu` VALUES (58, 'c50412c1e45a95c1eacc78bc113a39c7', 56, '修改测试', 'admin/test/edit', 'fas fa-pencil', 0, 0, 1000, 'POST', 1691226861, 1691226861, 0);
INSERT INTO `admin_menu` VALUES (59, '772290fe9011b5759615b971ed73ee25', 56, '删除测试', 'admin/test/del', 'fas fa-trash', 0, 0, 1000, 'POST', 1691226861, 1691226861, 0);
INSERT INTO `admin_menu` VALUES (60, '2860f507f08c33d1e8e878a5d20be722', 56, '启用测试', 'admin/test/enable', 'fas fa-circle', 0, 0, 1000, 'POST', 1691226861, 1691226861, 0);
INSERT INTO `admin_menu` VALUES (61, '6564d7c65ee599df4f9829d4ac340e16', 56, '禁用测试', 'admin/test/disable', 'fas fa-circle', 0, 0, 1000, 'POST', 1691226861, 1691226861, 0);
INSERT INTO `admin_menu` VALUES (62, '6aef96bcb7d4eca7262b03311fa5c69b', 55, '测试案例（二）', 'admin/test1/index', 'fas fa-list', 1, 0, 1000, '不记录', 1691226861, 1691226861, 0);
INSERT INTO `admin_menu` VALUES (63, '3028506aa38d1bbdc60889c6792994e3', 62, '添加测试', 'admin/test1/add', 'fas fa-plus', 0, 0, 1000, 'POST', 1691226861, 1691226861, 0);
INSERT INTO `admin_menu` VALUES (64, '0ca081d589f922266bbe8997fbaecd96', 62, '修改测试', 'admin/test1/edit', 'fas fa-pencil', 0, 0, 1000, 'POST', 1691226861, 1691226861, 0);
INSERT INTO `admin_menu` VALUES (65, '2f080985c5e33ded7b45e4015481efb2', 62, '删除测试', 'admin/test1/del', 'fas fa-trash', 0, 0, 1000, 'POST', 1691226861, 1691226861, 0);
INSERT INTO `admin_menu` VALUES (66, '289b26ef53874fededf7ac7a41f3dcdb', 62, '启用测试', 'admin/test1/enable', 'fas fa-circle', 0, 0, 1000, 'POST', 1691226861, 1691226861, 0);
INSERT INTO `admin_menu` VALUES (67, '02fc5c7f3bf8f7c63368dd080487acff', 62, '禁用测试', 'admin/test1/disable', 'fas fa-circle', 0, 0, 1000, 'POST', 1691226861, 1691226861, 0);

-- ----------------------------
-- Table structure for admin_role
-- ----------------------------
CREATE TABLE IF NOT EXISTS `admin_role`  (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '' COMMENT '名称',
  `description` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '' COMMENT '简介',
  `url` varchar(3000) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '' COMMENT '权限',
  `status` tinyint(1) UNSIGNED NOT NULL DEFAULT 1 COMMENT '是否启用',
  `create_time` int(10) UNSIGNED NOT NULL DEFAULT 0 COMMENT '创建时间',
  `update_time` int(10) UNSIGNED NOT NULL DEFAULT 0 COMMENT '更新时间',
  `delete_time` int(10) UNSIGNED NOT NULL DEFAULT 0 COMMENT '删除时间',
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 2 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci COMMENT = '后台角色' ROW_FORMAT = Dynamic;

-- ----------------------------
-- Records of admin_role
-- ----------------------------
INSERT INTO `admin_role` VALUES (1, '管理员', '后台管理员角色', '16,17,13,15,14,12,11,8,10,9,7,4,6,5,3,18,52,55,32,33,30,31,19,21,54,53,20,1,27,29,28,26,23,49,48,50,47,25,24,22,51,2,57,59,61,58,60,56,63,65,67,64,66,62,42,44,46,43,45,41,36,38,40,37,39,35,34', 1, 1691226855, 1691226855, 0);

-- ----------------------------
-- Table structure for admin_user
-- ----------------------------
CREATE TABLE IF NOT EXISTS `admin_user`  (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `username` varchar(30) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '' COMMENT '用户名',
  `password` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'JDJ5JDEwJHVPVDZXN0NQcTVIemkyd3BHUHNSZGVWR2V1NlVaMk5CYTVETVdkMjF4blFuYXNOaWU0RXFT' COMMENT '密码',
  `nickname` varchar(30) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '' COMMENT '昵称',
  `avatar` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '/static/admin/images/avatar.png' COMMENT '头像',
  `role` varchar(3210) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '' COMMENT '角色',
  `status` tinyint(1) UNSIGNED NOT NULL DEFAULT 1 COMMENT '是否启用',
  `create_time` int(10) UNSIGNED NOT NULL DEFAULT 0 COMMENT '创建时间',
  `update_time` int(10) UNSIGNED NOT NULL DEFAULT 0 COMMENT '更新时间',
  `delete_time` int(10) UNSIGNED NOT NULL DEFAULT 0 COMMENT '删除时间',
  PRIMARY KEY (`id`) USING BTREE,
  INDEX `index_username`(`username`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 3 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci COMMENT = '后台用户' ROW_FORMAT = Dynamic;

-- ----------------------------
-- Records of admin_user
-- ----------------------------
INSERT INTO `admin_user` VALUES (1, 'develop_admin', 'JDJ5JDEwJDNVeGRoZWRhLk5sZnZJODJxcVRVd3VTaVRaNWdLWWZhN3l4WjZBa0pYR1MxWUJOQzc5ZU0u', '开发管理员', '/static/admin/images/avatar.png', '1', 1, 1691226854, 1691226854, 0);
INSERT INTO `admin_user` VALUES (2, 'super_admin', 'JDJ5JDEwJEwuYVlzSHhhdTU1Qm9VVGN3QzNRSk8zaU1UTi5sQUNac1l2OWNpbi5WU3Iza2RPQk1WQ0JH', '超级管理员', '/static/admin/images/avatar.png', '1', 1, 1691226854, 1691226854, 0);

-- ----------------------------
-- Table structure for migrations
-- ----------------------------
CREATE TABLE IF NOT EXISTS `migrations`  (
  `version` bigint(20) NOT NULL,
  `migration_name` varchar(100) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL,
  `start_time` timestamp(0) NOT NULL DEFAULT CURRENT_TIMESTAMP(0),
  `end_time` timestamp(0) NOT NULL DEFAULT CURRENT_TIMESTAMP(0),
  `breakpoint` tinyint(1) NOT NULL DEFAULT 0,
  PRIMARY KEY (`version`) USING BTREE
) ENGINE = InnoDB CHARACTER SET = utf8 COLLATE = utf8_general_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Records of migrations
-- ----------------------------
INSERT INTO `migrations` VALUES (20191001081329, 'Setting', '2023-08-05 17:14:14', '2023-08-05 17:14:14', 0);
INSERT INTO `migrations` VALUES (20191001081340, 'SettingGroup', '2023-08-05 17:14:14', '2023-08-05 17:14:14', 0);
INSERT INTO `migrations` VALUES (20200804023050, 'AdminUser', '2023-08-05 17:14:14', '2023-08-05 17:14:14', 0);
INSERT INTO `migrations` VALUES (20200806095505, 'AdminMenu', '2023-08-05 17:14:14', '2023-08-05 17:14:15', 0);
INSERT INTO `migrations` VALUES (20200806100423, 'AdminRole', '2023-08-05 17:14:15', '2023-08-05 17:14:15', 0);
INSERT INTO `migrations` VALUES (20200806100513, 'AdminLog', '2023-08-05 17:14:15', '2023-08-05 17:14:15', 0);
INSERT INTO `migrations` VALUES (20200806100516, 'AdminLogData', '2023-08-05 17:14:15', '2023-08-05 17:14:15', 0);
INSERT INTO `migrations` VALUES (20200827064827, 'User', '2023-08-05 17:14:15', '2023-08-05 17:14:16', 0);
INSERT INTO `migrations` VALUES (20210219080541, 'UserLevel', '2023-08-05 17:14:16', '2023-08-05 17:14:16', 0);
INSERT INTO `migrations` VALUES (20210908031808, 'Test', '2023-08-05 17:14:16', '2023-08-05 17:14:16', 0);

-- ----------------------------
-- Table structure for setting
-- ----------------------------
CREATE TABLE IF NOT EXISTS `setting`  (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `setting_group_id` int(10) NOT NULL DEFAULT 0 COMMENT '所属分组',
  `name` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '' COMMENT '名称',
  `description` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '' COMMENT '描述',
  `code` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '' COMMENT '代码',
  `content` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL COMMENT '设置配置及内容',
  `sort_number` int(10) NOT NULL DEFAULT 1000 COMMENT '排序',
  `create_time` int(10) NOT NULL DEFAULT 0 COMMENT '创建时间',
  `update_time` int(10) NOT NULL DEFAULT 0 COMMENT '更新时间',
  `delete_time` int(10) NOT NULL DEFAULT 0 COMMENT '删除时间',
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 7 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci COMMENT = '设置' ROW_FORMAT = Dynamic;

-- ----------------------------
-- Records of setting
-- ----------------------------
INSERT INTO `setting` VALUES (1, 1, '基本设置', '后台的基本信息设置', 'base', '[{\"name\":\"\\u540e\\u53f0\\u540d\\u79f0\",\"field\":\"name\",\"type\":\"text\",\"content\":\"shiroiAdmin\\u540e\\u53f0\\u7cfb\\u7edf\",\"option\":\"\"},{\"name\":\"\\u540e\\u53f0\\u7b80\\u79f0\",\"field\":\"short_name\",\"type\":\"text\",\"content\":\"shiroiAdmin\",\"option\":\"\"},{\"name\":\"\\u540e\\u53f0\\u4f5c\\u8005\",\"field\":\"author\",\"type\":\"text\",\"content\":\"shiroi\",\"option\":\"\"},{\"name\":\"\\u4f5c\\u8005\\u7f51\\u7ad9\",\"field\":\"website\",\"type\":\"text\",\"content\":\"https:\\/\\/hcr707305003.github.io\\/\",\"option\":\"\"},{\"name\":\"\\u540e\\u53f0\\u7248\\u672c\",\"field\":\"version\",\"type\":\"text\",\"content\":\"0.1\",\"option\":\"\"},{\"name\":\"\\u540e\\u53f0LOGO\",\"field\":\"logo\",\"type\":\"image\",\"content\":\"\\/static\\/admin\\/images\\/logo.png\",\"option\":\"\"}]', 1000, 1691226854, 1691226854, 0);
INSERT INTO `setting` VALUES (2, 1, '登录设置', '后台登录相关设置', 'login', '[{\"name\":\"\\u767b\\u5f55token\\u9a8c\\u8bc1\",\"field\":\"token\",\"type\":\"switch\",\"content\":\"1\",\"option\":\"\"},{\"name\":\"\\u9a8c\\u8bc1\\u7801\",\"field\":\"captcha\",\"type\":\"select\",\"content\":\"1\",\"option\":\"0||\\u4e0d\\u5f00\\u542f\\r\\n1||\\u56fe\\u5f62\\u9a8c\\u8bc1\\u7801\\r\\n2||\\u6ed1\\u52a8\\u9a8c\\u8bc1\"},{\"name\":\"\\u767b\\u5f55\\u80cc\\u666f\",\"field\":\"background\",\"type\":\"image\",\"content\":\"\\/static\\/admin\\/images\\/login-default-bg.jpg\",\"option\":\"\"},{\"name\":\"\\u6781\\u9a8cID\",\"field\":\"geetest_id\",\"type\":\"text\",\"content\":\"66cfc0f309e368364b753dad7d2f67f2\",\"option\":\"\"},{\"name\":\"\\u6781\\u9a8cKEY\",\"field\":\"geetest_key\",\"type\":\"text\",\"content\":\"99750f86ec232c997efaff56c7b30cd3\",\"option\":\"\"},{\"name\":\"\\u767b\\u5f55\\u91cd\\u8bd5\\u9650\\u5236\",\"field\":\"login_limit\",\"type\":\"switch\",\"content\":\"0\",\"option\":\"0||\\u5426\\r\\n1||\\u662f\"},{\"name\":\"\\u9650\\u5236\\u6700\\u5927\\u6b21\\u6570\",\"field\":\"login_max_count\",\"type\":\"number\",\"content\":\"5\",\"option\":\"\"},{\"name\":\"\\u7981\\u6b62\\u767b\\u5f55\\u65f6\\u957f(\\u5c0f\\u65f6)\",\"field\":\"login_limit_hour\",\"type\":\"number\",\"content\":\"2\",\"option\":\"\"}]', 1000, 1691226854, 1691226854, 0);
INSERT INTO `setting` VALUES (3, 1, '安全设置', '安全相关配置', 'safe', '[{\"name\":\"\\u52a0\\u5bc6key\",\"field\":\"admin_key\",\"type\":\"text\",\"content\":\"89ce3272dc949fc3698fe7108d1dbe37\",\"option\":\"\"},{\"name\":\"SessionKeyUid\",\"field\":\"store_uid_key\",\"type\":\"text\",\"content\":\"admin_user_id\",\"option\":\"\"},{\"name\":\"SessionKeySign\",\"field\":\"store_sign_key\",\"type\":\"text\",\"content\":\"admin_user_sign\",\"option\":\"\"},{\"name\":\"\\u540e\\u53f0\\u7528\\u6237\\u5bc6\\u7801\\u5f3a\\u5ea6\\u68c0\\u6d4b\",\"field\":\"password_check\",\"type\":\"switch\",\"content\":\"0\",\"option\":\"0||\\u5173\\u95ed\\r\\n1||\\u5f00\\u542f\"},{\"name\":\"\\u5bc6\\u7801\\u5b89\\u5168\\u5f3a\\u5ea6\\u7b49\\u7ea7\",\"field\":\"password_level\",\"type\":\"select\",\"content\":\"2\",\"option\":\"1||\\u7b80\\u5355\\u5bc6\\u7801\\r\\n2||\\u4e2d\\u7b49\\u5bc6\\u7801\\r\\n3||\\u590d\\u6742\\u5bc6\\u7801\"},{\"name\":\"\\u5355\\u8bbe\\u5907\\u767b\\u5f55\",\"field\":\"one_device_login\",\"type\":\"switch\",\"content\":\"0\",\"option\":\"0||\\u5173\\u95ed\\r\\n1||\\u5f00\\u542f\"},{\"name\":\"CSRFToken\\u68c0\\u6d4b\",\"field\":\"check_token\",\"type\":\"switch\",\"content\":\"1\",\"option\":\"\"},{\"name\":\"CSRFToken\\u9a8c\\u8bc1\\u65b9\\u6cd5\",\"field\":\"check_token_action_list\",\"type\":\"multi_select\",\"content\":\"add,edit,del,import,profile,update\",\"option\":\"add||\\u6dfb\\u52a0\\r\\nedit||\\u4fee\\u6539\\r\\ndel||\\u5220\\u9664\\r\\nimport||\\u5bfc\\u5165\\r\\nprofile||\\u4fee\\u6539\\u8d44\\u6599\\r\\nupdate||\\u66f4\\u65b0\"}]', 1000, 1691226854, 1691226854, 0);
INSERT INTO `setting` VALUES (4, 2, '阿里云OSS', '阿里云OSS配置', 'aliyun_oss', '[{\"name\":\"appId\",\"field\":\"appId\",\"type\":\"text\",\"content\":\"\",\"option\":\"appId\"},{\"name\":\"appKey\",\"field\":\"appKey\",\"type\":\"text\",\"content\":\"\",\"option\":\"appKey\"},{\"name\":\"region\",\"field\":\"region\",\"type\":\"text\",\"content\":\"\",\"option\":\"region\\u5730\\u533a\\uff0c\\u4f8b\\uff1ahttp:\\/\\/oss-cn-shenzhen.aliyuncs.com\"}]', 1000, 1691226861, 1691226861, 0);
INSERT INTO `setting` VALUES (5, 2, '腾讯云cos', '腾讯云cos配置', 'tencent_cos', '[{\"name\":\"appId\",\"field\":\"appId\",\"type\":\"text\",\"content\":\"\",\"option\":\"appId\"},{\"name\":\"appKey\",\"field\":\"appKey\",\"type\":\"text\",\"content\":\"\",\"option\":\"appKey\"},{\"name\":\"region\",\"field\":\"region\",\"type\":\"text\",\"content\":\"\",\"option\":\"region\\u5730\\u533a(\\u53ef\\u9009),\\u4f8b\\uff1axxx.cos.ap-guangzhou.myqcloud.com\"}]', 1000, 1691226861, 1691226861, 0);
INSERT INTO `setting` VALUES (6, 2, '七牛云', '七牛云配置', 'qiniuyun', '[{\"name\":\"appId\",\"field\":\"appId\",\"type\":\"text\",\"content\":\"\",\"option\":\"appId\"},{\"name\":\"appKey\",\"field\":\"appKey\",\"type\":\"text\",\"content\":\"\",\"option\":\"appKey\"}]', 1000, 1691226861, 1691226861, 0);

-- ----------------------------
-- Table structure for setting_group
-- ----------------------------
CREATE TABLE IF NOT EXISTS `setting_group`  (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `module` varchar(30) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '' COMMENT '作用模块',
  `name` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '' COMMENT '名称',
  `description` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '' COMMENT '描述',
  `code` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '' COMMENT '代码',
  `sort_number` int(10) NOT NULL DEFAULT 1000 COMMENT '排序',
  `auto_create_menu` tinyint(1) NOT NULL DEFAULT 0 COMMENT '自动生成菜单',
  `auto_create_file` tinyint(1) NOT NULL DEFAULT 0 COMMENT '自动生成配置文件',
  `icon` varchar(30) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'fa-list' COMMENT '图标',
  `create_time` int(10) NOT NULL DEFAULT 0 COMMENT '创建时间',
  `update_time` int(10) NOT NULL DEFAULT 0 COMMENT '更新时间',
  `delete_time` int(10) NOT NULL DEFAULT 0 COMMENT '删除时间',
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 3 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci COMMENT = '设置分组' ROW_FORMAT = Dynamic;

-- ----------------------------
-- Records of setting_group
-- ----------------------------
INSERT INTO `setting_group` VALUES (1, 'admin', '后台设置', '后台管理方面的设置', 'admin', 1000, 0, 0, 'fa-adjust', 1691226854, 1691226854, 0);
INSERT INTO `setting_group` VALUES (2, 'api', '前台设置', '前台管理方面的设置', 'api', 1000, 0, 0, 'fa-user', 1691226854, 1691226854, 0);

-- ----------------------------
-- Table structure for test
-- ----------------------------
CREATE TABLE IF NOT EXISTS `test`  (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `avatar` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '/static/index/images/avatar.png' COMMENT '头像',
  `username` varchar(30) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '' COMMENT '用户名',
  `nickname` varchar(30) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '' COMMENT '昵称',
  `mobile` varchar(11) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '' COMMENT '手机号',
  `user_level_id` int(10) NOT NULL DEFAULT 1 COMMENT '用户等级',
  `password` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'JDJ5JDEwJFdwRmhNc0VhLk9acnlLeVhNWGpuMS5iODVkMm9XN0tlRG5PWDlNbTcyZnJOckVxcFdYUjRD' COMMENT '密码',
  `status` tinyint(1) NOT NULL DEFAULT 1 COMMENT '是否启用',
  `lng` decimal(14, 8) NOT NULL DEFAULT 116.00000000 COMMENT '经度',
  `lat` decimal(14, 8) NOT NULL DEFAULT 37.00000000 COMMENT '纬度',
  `slide` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL COMMENT '相册',
  `content` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL COMMENT '内容',
  `create_time` int(10) NOT NULL DEFAULT 0 COMMENT '创建时间',
  `update_time` int(10) NOT NULL DEFAULT 0 COMMENT '更新时间',
  `delete_time` int(10) NOT NULL DEFAULT 0 COMMENT '删除时间',
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 1 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci COMMENT = '测试' ROW_FORMAT = Dynamic;

-- ----------------------------
-- Records of test
-- ----------------------------

-- ----------------------------
-- Table structure for user
-- ----------------------------
CREATE TABLE IF NOT EXISTS `user`  (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_level_id` int(10) UNSIGNED NOT NULL DEFAULT 1 COMMENT '用户等级',
  `username` varchar(30) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '' COMMENT '账号',
  `password` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '' COMMENT '密码',
  `mobile` varchar(11) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '' COMMENT '手机号',
  `nickname` varchar(20) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '' COMMENT '昵称',
  `avatar` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '/static/index/images/avatar.png' COMMENT '头像',
  `status` tinyint(1) UNSIGNED NOT NULL DEFAULT 1 COMMENT '是否启用',
  `create_time` int(10) UNSIGNED NOT NULL DEFAULT 0 COMMENT '创建时间',
  `update_time` int(10) UNSIGNED NOT NULL DEFAULT 0 COMMENT '更新时间',
  `delete_time` int(10) UNSIGNED NOT NULL DEFAULT 0 COMMENT '删除时间',
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 11 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci COMMENT = '用户' ROW_FORMAT = Dynamic;

-- ----------------------------
-- Records of user
-- ----------------------------
INSERT INTO `user` VALUES (1, 1, 'putong1', 'JDJ5JDEwJHhpNkx3NG1BMWNudkJMVDAxbnkvNHVndEJ1Lm82QTRwTVlDNC4uN3BHbjhvajhOZE1tRTV1', '18899990000', 'putong1', '/uploads/image/user.png', 1, 1691226855, 1691226855, 0);
INSERT INTO `user` VALUES (2, 1, 'putong2', 'JDJ5JDEwJGtqODVwTHUxbWppZzBKak5xVktXN09mY2k5aWYzRC4uZnZZdkxoOWZ1WU5YZGQ0RElvUGU2', '18333333333', 'putong2', '/uploads/image/user.png', 1, 1691226855, 1691226855, 0);
INSERT INTO `user` VALUES (3, 2, 'baiyin1', 'JDJ5JDEwJHhoMDNFL1Y0alhncWVNSC84QWdmYi41bjVBck1lRC9YTTlwdVRkaTB2aGptRUIxbnRXTmJt', '13200001111', 'baiyin1', '/uploads/image/user.png', 0, 1691226855, 1691226855, 0);
INSERT INTO `user` VALUES (4, 2, 'baiyin2', 'JDJ5JDEwJDgwcmwwa2dQN09ZRlh3ZXRUaGZkUy5KdXp0Y3VDUU5FQ0h4UXVSazNPNUZSN2FiYVNDZkxp', 'admin', 'baiyin2', '/uploads/image/user.png', 1, 1691226855, 1691226855, 0);
INSERT INTO `user` VALUES (5, 3, '黄金1', 'JDJ5JDEwJHFjZGZBaFUwZTI4U3hLVXN4Zmk3RXVhZnpMSEhWamJWR0ZkU1VBTDhVbjZkTGlJaG5WZU1t', '黄金1', '黄金1', '/uploads/image/user.png', 1, 1691226855, 1691226855, 0);
INSERT INTO `user` VALUES (6, 1, '10001', 'JDJ5JDEwJHBPVGZEV1J4Q083UlhFcTB5UUI4dXVaZUpDNm8xZmJRaEkxL3pER0Ivd2xjY1JOTDJ4ZkVX', '13200000000', '10001', '/uploads/image/user.png', 1, 1691226856, 1691226856, 0);
INSERT INTO `user` VALUES (7, 2, '10002', 'JDJ5JDEwJFFpazhOWXRMVEJWclV4STZSRi9vTWV1YkdtTkJCVkZZcUU2Q1M3MklhNlFzSlRNZUl0Z3Ft', '13200000001', '10002', '/uploads/image/user.png', 1, 1691226856, 1691226856, 0);
INSERT INTO `user` VALUES (8, 3, '10003', 'JDJ5JDEwJFVrY2xoMElucWhRRnVSYlBqeFV4WC5FMFB0SFVlRFY2bVZ0cHB1TDJWQXVjLndCejJ6Uy9l', '13200000002', '10003', '/uploads/image/user.png', 0, 1691226856, 1691226856, 0);
INSERT INTO `user` VALUES (9, 2, '10004', 'JDJ5JDEwJDNCYS5sc3prOVpBLzRlYmNBcUZKZnVoV2xKbVhQSHVFS291Ri9NeW9GM1QzTTFsQTQuNFFX', '13200000003', '10004', '/uploads/image/user.png', 0, 1691226856, 1691226856, 0);
INSERT INTO `user` VALUES (10, 1, '10005', 'JDJ5JDEwJHBiRFJiN3BJbE9UWi5wZWJRYWxSQnVqcUJkc3FiZ1FNRFk1NTBjQlcxRG1uYWR4dmlJYU9h', '13200000004', '10005', '/uploads/image/user.png', 0, 1691226856, 1691226856, 0);

-- ----------------------------
-- Table structure for user_level
-- ----------------------------
CREATE TABLE IF NOT EXISTS `user_level`  (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(20) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '' COMMENT '名称',
  `description` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '' COMMENT '简介',
  `img` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '/static/index/images/user_level_default.png' COMMENT '图片',
  `status` tinyint(1) NOT NULL DEFAULT 1 COMMENT '是否启用',
  `create_time` int(10) UNSIGNED NOT NULL DEFAULT 0 COMMENT '创建时间',
  `update_time` int(10) UNSIGNED NOT NULL DEFAULT 0 COMMENT '更新时间',
  `delete_time` int(10) UNSIGNED NOT NULL DEFAULT 0 COMMENT '删除时间',
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 4 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci COMMENT = '用户等级' ROW_FORMAT = Dynamic;

-- ----------------------------
-- Records of user_level
-- ----------------------------
INSERT INTO `user_level` VALUES (1, '普通用户', '普通用户', '/uploads/image/user_level_1.png', 1, 1691226856, 1691226856, 0);
INSERT INTO `user_level` VALUES (2, '白银会员', '白银会员', '/uploads/image/user_level_2.png', 1, 1691226856, 1691226856, 0);
INSERT INTO `user_level` VALUES (3, '黄金会员', '黄金会员', '/uploads/image/user_level_3.png', 1, 1691226856, 1691226856, 0);

SET FOREIGN_KEY_CHECKS = 1;
