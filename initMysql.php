<?php
/**
 * 1分钟计划任务
 * 初始化mysql数据库
 */
@define('ROOT', getcwd());
@define('NAME_PHP_SAPI', "cli");
if(NAME_PHP_SAPI == "cli" && substr(php_sapi_name(), 0, 3) != 'cli'){
	//exit("403");
}
header("Content-type: text/html;charset=UTF-8");
date_default_timezone_set('PRC');
echo "into file:\t".__FILE__."\t".date('Y-m-d H:i:s')."\n";
require(ROOT.'/writable/config.inc.php');

    
$mysqli = new mysqli(MYSQLHOST, MYSQLUSER, MYSQLPASS);
if(mysqli_connect_errno()){
    exit("mysql连接错误");
}

if(@$mysqli->select_db(DB_NAME)){
    echo "已创建数据库".DB_NAME."\n";
}else{
	echo "开始创建数据库".DB_NAME."\n";
	$sql = "CREATE DATABASE `".DB_NAME."` CHARACTER SET utf8 COLLATE utf8_general_ci;";
	$mysqli->query($sql);
}

$mysqli->select_db(DB_NAME);
$mysqli->set_charset("utf8");


echo "开始创建表bills\n";
$sql = "
CREATE TABLE IF NOT EXISTS ".TABLE_PREFIX."bills (
`bill_id` INT(10) unsigned NOT NULL auto_increment COMMENT '账单id',
`date` INT(10) NOT NULL DEFAULT 0 COMMENT '日期',
`title` VARCHAR(200) NOT NULL DEFAULT '' COMMENT '款项名称',
`type` TINYINT(4) NOT NULL DEFAULT 0 COMMENT '类型：1为收入，2为支出',
`money` DOUBLE NOT NULL DEFAULT 0 COMMENT '发生金额',
`remain_money` DOUBLE NOT NULL DEFAULT 0 COMMENT '余额',
`file` VARCHAR(200) NOT NULL DEFAULT '' COMMENT '凭证文件',
`remark` VARCHAR(1000) NOT NULL DEFAULT '' COMMENT '备注',
`status` TINYINT(4) NOT NULL DEFAULT 0 COMMENT '状态：1为待确认、2为确认',
`data` TEXT COMMENT '数据',
`add_user` VARCHAR(50) NOT NULL DEFAULT '' COMMENT '添加人员',
`comfirm_user` VARCHAR(50) NOT NULL DEFAULT '' COMMENT '确认人员',
`created` INT(10) NOT NULL DEFAULT 0 COMMENT '添加时间',
PRIMARY KEY `bill_id` (`bill_id`),
INDEX `add_user` (`add_user`),
INDEX `status` (`status`),
INDEX `created` (`created`)
)ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='流水账表';
";
$mysqli->query($sql);


echo "开始创建表bills\n";
$sql = "
CREATE TABLE IF NOT EXISTS ".TABLE_PREFIX."business (
`bid` INT(10) unsigned NOT NULL auto_increment COMMENT '业务id',
`company` VARCHAR(200) NOT NULL DEFAULT '' COMMENT '公司名称',
`boss` VARCHAR(20) NOT NULL DEFAULT '' COMMENT '法人',
`phone` VARCHAR(20) NOT NULL DEFAULT '' COMMENT '电话',
`qq` VARCHAR(20) NOT NULL DEFAULT '' COMMENT 'QQ',
`funds` INT(10) NOT NULL DEFAULT 0 COMMENT '注册资金（万元）',
`address` VARCHAR(200) NOT NULL DEFAULT '' COMMENT '注册地址',

`jiedan` VARCHAR(20) NOT NULL DEFAULT '' COMMENT '接单员',
`kefu` VARCHAR(20) NOT NULL DEFAULT '' COMMENT '客服',

`start_time` TIMESTAMP NOT NULL DEFAULT 0 COMMENT '代理记账开始日期',
`end_time` TIMESTAMP NOT NULL DEFAULT 0 COMMENT '到期日期',
`cost` INT(10) NOT NULL DEFAULT 0 COMMENT '收费标准',

`cost_type` TINYINT(4) NOT NULL DEFAULT 0 COMMENT '付款类型',
`tax_type` TINYINT(4) NOT NULL DEFAULT 0 COMMENT '缴税类别',
`bill_status` INT(10) NOT NULL DEFAULT 0 COMMENT '订单状态',

`remark` VARCHAR(1000) NOT NULL DEFAULT '' COMMENT '申报情况',
`add_user` VARCHAR(50) NOT NULL DEFAULT '' COMMENT '添加人员',
`created` INT(10) NOT NULL DEFAULT 0 COMMENT '添加时间',
PRIMARY KEY `bid` (`bid`),
INDEX `company` (`company`),
INDEX `boss` (`boss`),
INDEX `bill_status` (`bill_status`),
INDEX `end_time` (`end_time`),
INDEX `created` (`created`)
)ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='业务表';
";
$mysqli->query($sql);


echo "开始创建表user\n";
$sql = "
CREATE TABLE IF NOT EXISTS ".TABLE_PREFIX."user (
`uid` INT(10) unsigned NOT NULL auto_increment COMMENT '用户自增id',
`username` VARCHAR(128) NOT NULL DEFAULT '' COMMENT '用户名',
`password` VARCHAR(128) NOT NULL DEFAULT '' COMMENT '加密密码',
`groupid` INT(10) NOT NULL DEFAULT 0 COMMENT '用户组id',
`name` VARCHAR(128) NOT NULL DEFAULT '' COMMENT '姓名',
`created` INT(10) NOT NULL DEFAULT 0 COMMENT '创建用户时间戳',
`last_login_time` INT(10) NOT NULL DEFAULT 0 COMMENT '最后登录时间',
`last_login_ip` VARCHAR(128) NOT NULL DEFAULT '' COMMENT '最后登录ip',
`data` TEXT COMMENT '其他数据',
PRIMARY KEY `uid` (`uid`),
INDEX `username` (`username`)
)ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='用户表';
";
$mysqli->query($sql);



$sql = "INSERT INTO ".TABLE_PREFIX."user (`username`, `password`, `groupid`, `name`, `created`) 
VALUES ('admin', MD5('admin@bills#20130220'), 1, '管理员', UNIX_TIMESTAMP());";
$mysqli->query($sql);

$sql = "INSERT INTO ".TABLE_PREFIX."user (`username`, `password`, `groupid`, `name`, `created`) 
VALUES ('记账员', MD5('editor@bills#20130220'), 2, '记账员', UNIX_TIMESTAMP());";
$mysqli->query($sql);

echo "end file:\t".__FILE__."\t".date('Y-m-d H:i:s')."\n";