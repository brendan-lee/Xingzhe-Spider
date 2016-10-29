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
$trackList = json_decode(file_get_contents($taskFile));
$trackId = $trackList[$time]['id'];

/**
 * 爬取GPX
 */
// 无法获取数据时报错并跳过
if (getGPX($sessionId, $trackId) == false) {
	echo '<p>' . date('y-m-d h:i:s') . '：无法获取ID为' . $trackId . '的GPX数据，请确保轨迹未设置隐藏。</p>';
} else {
	file_put_contents(getGPX($trackId), $folder . '\\' . $trackId . '.gpx');
	echo '<p>' . date('y-m-d h:i:s') . '：轨迹 ' . $trackId . ' 导出成功。</p>';
}
?>

