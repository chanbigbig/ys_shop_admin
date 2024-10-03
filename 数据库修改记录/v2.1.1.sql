-- ----------------------------
-- 用于记录开发阶段的数据库变更
-- ----------------------------

UPDATE `yoshop_store_api` SET `sort`='200' WHERE (`api_id`='11017');
UPDATE `yoshop_store_api` SET `sort`='115' WHERE (`api_id`='11316');
UPDATE `yoshop_store_api` SET `sort`='100', `parent_id`='11352' WHERE (`api_id`='11015'); 

INSERT INTO `yoshop_store_api` VALUES ('11352', '文件分组管理', '-', '11008', '115', '1614556800', '1614556800');
INSERT INTO `yoshop_store_api` VALUES ('11351', '新增文件分组', '/files.group/add', '11352', '105', '1614556800', '1614556800');
INSERT INTO `yoshop_store_api` VALUES ('11353', '删除文件', '/files/delete', '11008', '105', '1614556800', '1614556800');
INSERT INTO `yoshop_store_api` VALUES ('11354', '移动文件', '/files/moveGroup', '11008', '110', '1614556800', '1614556800');

UPDATE `yoshop_store_api` SET `sort`='120' WHERE (`api_id`='11016');
UPDATE `yoshop_store_api` SET `sort`='125' WHERE (`api_id`='11316');

# 优化后台订单列表加载缓慢
ALTER TABLE `yoshop_order_address` ADD INDEX (`order_id`);

# 订单记录表 - 订单结算时间
ALTER TABLE `yoshop_order`
ADD COLUMN `settled_time` int UNSIGNED NOT NULL DEFAULT 0 COMMENT '订单结算时间' AFTER `is_settled`;
