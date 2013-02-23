<?php
/**
 * 1分钟计划任务
 * 初始化mysql数据库
 */
@define('ROOT', getcwd());
@define('NAME_PHP_SAPI', "cli");
if(NAME_PHP_SAPI == "cli" && substr(php_sapi_name(), 0, 3) != 'cli'){
	exit("403");
}

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



$sql = "INSERT INTO ".TABLE_PREFIX."user (`username`, `password`, `groupid`, `name`, `created`) VALUES ('admin', MD5('admin@bills#20130220'), 1, '管理员', UNIX_TIMESTAMP());";
$mysqli->query($sql);

$sql = "INSERT INTO ".TABLE_PREFIX."user (`username`, `password`, `groupid`, `name`, `created`) VALUES ('记账员', MD5('editor@bills#20130220'), 2, '记账员', UNIX_TIMESTAMP());";
$mysqli->query($sql);

echo "end file:\t".__FILE__."\t".date('Y-m-d H:i:s')."\n";