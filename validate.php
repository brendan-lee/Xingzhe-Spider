<?php
include 'functions.php';

/**
 * 验证
 */

// sessionid为空
if (@$_POST ['sessionid'] == NULL)
	exit ( '<script>alert("sessionid不能为空，请正确填写后执行。")</script>' );
$sessionId = $_POST ['sessionid'];


// sessionid有误
if (getGPX(1) == '登录以后才能导出')
	exit('<script>alert("sessionid不正确，无法登录到行者。")</script>');









// $jsonString = file_get_contents ( 'http://www.imxingzhe.com/api/v3/user_month_info?user_id=137311&year=2016&month=3' );
$jsonString = file_get_contents ( 'example.json' );
$allTracksArr = json_decode ( $jsonString, true ) ['data'] ['wo_info'];

// 所有要抓取的轨迹
$trackId = array (
		15376320,
		15252281,
		15244912,
		15066892,
		14941162 
);

include 'spider.php';
?>