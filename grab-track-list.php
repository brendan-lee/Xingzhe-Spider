<?php
include 'functions.php';

$taskId = $_POST['taskId'];
$uid = $_POST['uid'];
$taskRoot = dirname(__FILE__) . '\task\\';


// 验证task id合法性
if (strlen($taskId) != 32 || !file_exists($taskRoot . $taskId))
	exit('请勿非法调用！');
$taskFile = $taskRoot . $taskId;

// 读取需要爬取的年月份
$dateList = json_decode(file_get_contents($taskFile));

// 爬取所需的轨迹清单
$trackList = array();
foreach ($dateList as $y => $yArr) {
	foreach ($yArr as $m) {
		$tmpArr = json_decode(file_get_contents ( 'http://www.imxingzhe.com/api/v3/user_month_info?user_id=' . $uid . '&year=' . $y . '&month=' . $m), true);
		$trackList = array_merge($trackList, $tmpArr['data']['wo_info']);
	}
}
// 日期范围内没有运动轨迹，删除任务&退出
if (count($trackList) == 0) {
	unlink($taskFile);
	eLog('任务结束。');
	exit('<script>msg("警告", "所选日期范围内没有轨迹，请重新设置日期范围。")</script>');
}

file_put_contents($taskFile, json_encode($trackList, JSON_UNESCAPED_UNICODE));

/**
 * 按日期时间创建导出文件夹
 */
$root = dirname(__FILE__) . '\gpx';
$folder = $root . '\\' . date('ymd_his');
if (!is_dir($root))
	mkdir($root);
is_dir($folder) ? exit('<script>msg("警告", "任务队列已满，请刷新后再试。")</script>') : mkdir($folder);


echo '<script>folderName = "' . str_replace('\\', '\\\\', $folder) . '";</script>';
echo '<script>submitTime = ' . count($trackList) . ';</script>';
eLog('已获取轨迹清单，开始爬取GPX数据。');
?>