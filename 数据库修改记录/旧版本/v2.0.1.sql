
## 本文件是v2.0.1版本的数据库修改记录，通过查看version.json文件确定当前系统版本号
## 说明：如果你当前的版本号小于v2.0.1，那么在升级时需要执行本文件的sql内容


# v2.0.1
# 修改时间：2021-3-26
ALTER TABLE `yoshop_user_oauth` ADD INDEX `oauth_type_2` (`oauth_type`, `oauth_id`) USING BTREE ;

# v2.0.1
# 修改时间：2021-3-26
ALTER TABLE `yoshop_user_oauth`
ADD COLUMN `is_delete`  tinyint UNSIGNED NOT NULL DEFAULT 0 COMMENT '是否删除' AFTER `store_id` ;

# v2.0.1
# 修改时间：2021-6-2
INSERT INTO `yoshop_region` VALUES ('3620', '东城街道', '2051', '﻿44190', '3');
INSERT INTO `yoshop_region` VALUES ('3621', '南城街道', '2051', '441900', '3');
INSERT INTO `yoshop_region` VALUES ('3622', '万江街道', '2051', '441900', '3');
INSERT INTO `yoshop_region` VALUES ('3623', '莞城街道', '2051', '441900', '3');
INSERT INTO `yoshop_region` VALUES ('3624', '石碣镇', '2051', '441900', '3');
INSERT INTO `yoshop_region` VALUES ('3625', '石龙镇', '2051', '441900', '3');
INSERT INTO `yoshop_region` VALUES ('3626', '茶山镇', '2051', '441900', '3');
INSERT INTO `yoshop_region` VALUES ('3627', '石排镇', '2051', '441900', '3');
INSERT INTO `yoshop_region` VALUES ('3628', '企石镇', '2051', '441900', '3');
INSERT INTO `yoshop_region` VALUES ('3629', '横沥镇', '2051', '441900', '3');
INSERT INTO `yoshop_region` VALUES ('3630', '桥头镇', '2051', '441900', '3');
INSERT INTO `yoshop_region` VALUES ('3631', '谢岗镇', '2051', '441900', '3');
INSERT INTO `yoshop_region` VALUES ('3632', '东坑镇', '2051', '441900', '3');
INSERT INTO `yoshop_region` VALUES ('3633', '常平镇', '2051', '441900', '3');
INSERT INTO `yoshop_region` VALUES ('3634', '寮步镇', '2051', '441900', '3');
INSERT INTO `yoshop_region` VALUES ('3635', '樟木头镇', '2051', '441900', '3');
INSERT INTO `yoshop_region` VALUES ('3636', '大朗镇', '2051', '441900', '3');
INSERT INTO `yoshop_region` VALUES ('3637', '黄江镇', '2051', '441900', '3');
INSERT INTO `yoshop_region` VALUES ('3638', '清溪镇', '2051', '441900', '3');
INSERT INTO `yoshop_region` VALUES ('3639', '塘厦镇', '2051', '441900', '3');
INSERT INTO `yoshop_region` VALUES ('3640', '凤岗镇', '2051', '441900', '3');
INSERT INTO `yoshop_region` VALUES ('3641', '大岭山镇', '2051', '441900', '3');
INSERT INTO `yoshop_region` VALUES ('3642', '长安镇', '2051', '441900', '3');
INSERT INTO `yoshop_region` VALUES ('3643', '虎门镇', '2051', '441900', '3');
INSERT INTO `yoshop_region` VALUES ('3644', '厚街镇', '2051', '441900', '3');
INSERT INTO `yoshop_region` VALUES ('3645', '沙田镇', '2051', '441900', '3');
INSERT INTO `yoshop_region` VALUES ('3646', '道滘镇', '2051', '441900', '3');
INSERT INTO `yoshop_region` VALUES ('3647', '洪梅镇', '2051', '441900', '3');
INSERT INTO `yoshop_region` VALUES ('3648', '麻涌镇', '2051', '441900', '3');
INSERT INTO `yoshop_region` VALUES ('3649', '望牛墩镇', '2051', '441900', '3');
INSERT INTO `yoshop_region` VALUES ('3650', '中堂镇', '2051', '441900', '3');
INSERT INTO `yoshop_region` VALUES ('3651', '高埗镇', '2051', '441900', '3');
INSERT INTO `yoshop_region` VALUES ('3652', '松山湖', '2051', '441900', '3');
INSERT INTO `yoshop_region` VALUES ('3653', '东莞港', '2051', '441900', '3');
INSERT INTO `yoshop_region` VALUES ('3654', '东莞生态园', '2051', '441900', '3');
INSERT INTO `yoshop_region` VALUES ('3655', '石岐街道', '2052', '﻿44200', '3');
INSERT INTO `yoshop_region` VALUES ('3656', '东区街道', '2052', '442000', '3');
INSERT INTO `yoshop_region` VALUES ('3657', '中山港街道', '2052', '442000', '3');
INSERT INTO `yoshop_region` VALUES ('3658', '西区街道', '2052', '442000', '3');
INSERT INTO `yoshop_region` VALUES ('3659', '南区街道', '2052', '442000', '3');
INSERT INTO `yoshop_region` VALUES ('3660', '五桂山街道', '2052', '442000', '3');
INSERT INTO `yoshop_region` VALUES ('3661', '小榄镇', '2052', '442000', '3');
INSERT INTO `yoshop_region` VALUES ('3662', '黄圃镇', '2052', '442000', '3');
INSERT INTO `yoshop_region` VALUES ('3663', '民众镇', '2052', '442000', '3');
INSERT INTO `yoshop_region` VALUES ('3664', '东凤镇', '2052', '442000', '3');
INSERT INTO `yoshop_region` VALUES ('3665', '东升镇', '2052', '442000', '3');
INSERT INTO `yoshop_region` VALUES ('3666', '古镇镇', '2052', '442000', '3');
INSERT INTO `yoshop_region` VALUES ('3667', '沙溪镇', '2052', '442000', '3');
INSERT INTO `yoshop_region` VALUES ('3668', '坦洲镇', '2052', '442000', '3');
INSERT INTO `yoshop_region` VALUES ('3669', '港口镇', '2052', '442000', '3');
INSERT INTO `yoshop_region` VALUES ('3670', '三角镇', '2052', '442000', '3');
INSERT INTO `yoshop_region` VALUES ('3671', '横栏镇', '2052', '442000', '3');
INSERT INTO `yoshop_region` VALUES ('3672', '南头镇', '2052', '442000', '3');
INSERT INTO `yoshop_region` VALUES ('3673', '阜沙镇', '2052', '442000', '3');
INSERT INTO `yoshop_region` VALUES ('3674', '南朗镇', '2052', '442000', '3');
INSERT INTO `yoshop_region` VALUES ('3675', '三乡镇', '2052', '442000', '3');
INSERT INTO `yoshop_region` VALUES ('3676', '板芙镇', '2052', '442000', '3');
INSERT INTO `yoshop_region` VALUES ('3677', '大涌镇', '2052', '442000', '3');
INSERT INTO `yoshop_region` VALUES ('3678', '神湾镇', '2052', '442000', '3');
INSERT INTO `yoshop_region` VALUES ('3679', '西沙群岛', '2206', '﻿46032', '3');
INSERT INTO `yoshop_region` VALUES ('3680', '南沙群岛', '2206', '460322', '3');
INSERT INTO `yoshop_region` VALUES ('3681', '中沙群岛的岛礁及其海域', '2206', '460323', '3');
INSERT INTO `yoshop_region` VALUES ('3682', '那大镇', '2207', '﻿46040', '3');
INSERT INTO `yoshop_region` VALUES ('3683', '和庆镇', '2207', '460400', '3');
INSERT INTO `yoshop_region` VALUES ('3684', '南丰镇', '2207', '460400', '3');
INSERT INTO `yoshop_region` VALUES ('3685', '大成镇', '2207', '460400', '3');
INSERT INTO `yoshop_region` VALUES ('3686', '雅星镇', '2207', '460400', '3');
INSERT INTO `yoshop_region` VALUES ('3687', '兰洋镇', '2207', '460400', '3');
INSERT INTO `yoshop_region` VALUES ('3688', '光村镇', '2207', '460400', '3');
INSERT INTO `yoshop_region` VALUES ('3689', '木棠镇', '2207', '460400', '3');
INSERT INTO `yoshop_region` VALUES ('3690', '海头镇', '2207', '460400', '3');
INSERT INTO `yoshop_region` VALUES ('3691', '峨蔓镇', '2207', '460400', '3');
INSERT INTO `yoshop_region` VALUES ('3692', '王五镇', '2207', '460400', '3');
INSERT INTO `yoshop_region` VALUES ('3693', '白马井镇', '2207', '460400', '3');
INSERT INTO `yoshop_region` VALUES ('3694', '中和镇', '2207', '460400', '3');
INSERT INTO `yoshop_region` VALUES ('3695', '排浦镇', '2207', '460400', '3');
INSERT INTO `yoshop_region` VALUES ('3696', '东成镇', '2207', '460400', '3');
INSERT INTO `yoshop_region` VALUES ('3697', '新州镇', '2207', '460400', '3');
INSERT INTO `yoshop_region` VALUES ('3698', '洋浦经济开发区', '2207', '460400', '3');
INSERT INTO `yoshop_region` VALUES ('3699', '华南热作学院', '2207', '460400', '3');
INSERT INTO `yoshop_region` VALUES ('3700', '雄关街道', '2922', '﻿62020', '3');
INSERT INTO `yoshop_region` VALUES ('3701', '钢城街道', '2922', '620201', '3');
INSERT INTO `yoshop_region` VALUES ('3702', '新城镇', '2922', '620201', '3');
INSERT INTO `yoshop_region` VALUES ('3703', '峪泉镇', '2922', '620201', '3');
INSERT INTO `yoshop_region` VALUES ('3704', '文殊镇', '2922', '620201', '3');
