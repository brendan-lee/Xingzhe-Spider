<?php
include 'functions.php';

/**
 * 验证
 */

// 验证sessionid是否为空
if ($_POST['sessionid'] == NULL)
	exit('<script>msg("错误", "sessionid不能为空，请正确填写后执行。")</script>');
$sessionId = $_POST['sessionid'];

// 验证uid是否为空
if ($_POST['uid'] == NULL)
	exit('<script>msg("错误", "uid不能为空，请正确填写后执行。")</script>');
$uid = $_POST['uid'];

// 验证uid是否为纯数字
preg_match('/^\d*$/', $uid, $match);
if ($match[0] != $uid) {
	exit('<script>msg("错误", "uid必须为纯数字。")</script>');
}

// 验证日期范围
$fromY = $_POST['fromY'];
$fromM = $_POST['fromM'];
$toY = $_POST['toY'];
$toM = $_POST['toM'];
if ($toY < $fromY || ($toY <= $fromY && $toM < $fromM)) {
	exit('<script>msg("错误", "日期范围有误，请正确选择。")</script>');
}

// 验证sessionid
if (getGPX($sessionId, 1) == '登录以后才能导出')
	exit('<script>msg("错误", "sessionid不正确，无法登录到行者。")</script>');


/**
 * 分配task id
 */
date_default_timezone_set('Asia/Shanghai');
$taskId = md5(microtime(true) . rand(0, 100));

// 计算需要爬取的年份&月份
$dateList = array();
$monthsCount = 0;
for ($y = $fromY, $i = 0; $y <= $toY; $y++, $i++) {
	$dateList[$y] = array();
	for ($m = ($y == $fromY ? $fromM : 1); $m <= ($y == $toY ? $toM : 12); $m++) {
		$dateList[$y][] = (int)$m;
		$monthsCount++;
	}
}

// 需要爬取的量过多时报错退出
if ($monthsCount > 24) {
	exit('<script>msg("警告", "为保证爬取稳定性，一次性只允许导出不超过24个月的数据。")</script>');
}

// 写入年份&月份到任务清单
$taskRoot = dirname(__FILE__) . '\task';
if (!is_dir($taskRoot))
	mkdir($taskRoot);
file_put_contents($taskRoot . '\\' . $taskId, json_encode($dateList));

// 开始爬取
echo '<p>' . date('y-m-d h:i:s') . '：开始爬取任务，全过程视数据量和网络状况可能持续数秒至十数分钟，在完成前请不要关闭页面。</p>';
echo '<script>grab("' . $taskId . '", ' . $uid . ', ' . $monthsCount . ');</script>';









// $jsonString = file_get_contents ( 'http://www.imxingzhe.com/api/v3/user_month_info?user_id=137311&year=2016&month=3' );
$jsonString = file_get_contents('example.json');
//$allTracksArr = json_decode ( $jsonString, true )['data']['wo_info'];

// 所有要抓取的轨迹
$trackId = array(15376320, 15252281, 15244912, 15066892, 14941162);

//include 'spider.php';
?>