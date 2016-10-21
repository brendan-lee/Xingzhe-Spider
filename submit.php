<?php
/*
 * 返回信息等级
 * 9：错误
 */

if (@$_POST ['sessionid'] == NULL)
	exit ( '9:sessionid不能为空，请正确填写后执行。' );
$sessionId = $_POST ['sessionid'];

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