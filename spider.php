<?php
defined('VALIDATED') or	exit('请勿非法调用！');

/**
 * 按日期时间创建导出文件夹
 */
$root = dirname(__FILE__) . '\gpx';
$folder = $root . '\\' . date('ymd_his');
if (!is_dir($root))
	mkdir($root);
is_dir($folder) ? exit('<script>msg("警告", "任务队列已满，请刷新后再试。")</script>') : mkdir($folder);

/**
 * 循环爬取
 */
$failCount = $successCount = 0;
foreach ($trackId as $i => $id) {
	// 无法获取数据时报错并跳过
	if (getGPX($id) == false) {
		echo '<p>' . ++$i . '：无法获取ID为' . $id . '的GPX数据，请检查轨迹ID正确性，并确保轨迹未设置隐藏。</p>';
		$failCount++;
	} else {
		file_put_contents(getGPX($id), $folder . '\\' . $id . '.gpx');
		echo '<p>' . ++$i . '：轨迹' . $id . ' 导出成功。</p>';
		$successCount++;
	}
}
echo '<p>成功' . $successCount . '个，失败' . $failCount . '个。</p>';
// 无文件则删除文件夹
if ($successCount == 0)
	rmdir($folder);
?>

