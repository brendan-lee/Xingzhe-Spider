<?php
include 'functions.php';
$taskId = $_POST['taskId'];
$sessionId = $_POST['sessionId'];
$folder = $_POST['folder'];
$time = $_POST['time'];

// 验证task id合法性
if (strlen($taskId) != 32 || !file_exists(dirname(__FILE__) . '\task\\' . $taskId))
	exit('请勿非法调用！');

/**
 * 获取轨迹id
 */
$taskFile = dirname(__FILE__) . '\task\\' . $taskId;
$trackList = json_decode(file_get_contents($taskFile), true);
$trackId = $trackList[$time]['id'];

/**
 * 爬取GPX
 */
// 无法获取数据时报错并跳过
if (getGPX($sessionId, $trackId) == false) {
	eLog('无法获取ID为' . $trackId . '的GPX数据，请确保轨迹未设置隐藏。');
} else {
	$content = getGPX($sessionId, $trackId);
	file_put_contents($folder . '\\' . $trackId . '.gpx', $content);
	eLog('轨迹 ' . $trackId . '（' . $trackList[$time]['title'] . '） 导出成功。');
	
	if ($time == count($trackList) - 1)
		eLog('任务完成，共导出' . count($trackList) . '条轨迹。');
}
?>

