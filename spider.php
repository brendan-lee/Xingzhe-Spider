<?php
/**
 * 中止运行并返回错误文本。
 *
 * @param String $str
 */
function wrong($str) {
	exit ( '<p>' . $str . '</p><p><a href="index.php">返回首页</a></p>' );
}

/**
 * 爬取行者的GPX数据。
 *
 * @param int $id
 *        	行者轨迹ID
 * @return GPX数据
 */
function getGPX($id) {
	global $sessionId, $folder;
	$url = 'http://www.imxingzhe.com/xing/' . $id . '/gpx/'; // 欲爬取的url
	
	$ch = curl_init ( $url );
	curl_setopt ( $ch, CURLOPT_COOKIE, 'sessionid=' . $sessionId ); // 模拟登录
	curl_setopt ( $ch, CURLOPT_RETURNTRANSFER, true ); // 存入$result而不是直接输出
	$result = curl_exec ( $ch );
	curl_close ( $ch );
	// sessionid有误时，删文件夹&退出
	if ($result == '登录以后才能导出') {
		rmdir ( $folder );
		wrong ( 'sessionid不正确，无法登录到行者。' );
	}
	
	return $result == NULL ? false : $result;
}

/**
 * 将指定内容写入文件。
 *
 * @param String $content
 *        	欲写入的内容
 * @param String $filename
 *        	欲存入的文件名
 */
function write2File($content, $filename) {
	$file = fopen ( $filename, 'a' );
	fwrite ( $file, $content );
	fclose ( $file );
}

/**
 * 按日期时间创建导出文件夹
 */
date_default_timezone_set ( 'Asia/Shanghai' );
$root = dirname ( __FILE__ ) . '\gpx';
$folder = $root . '\\' . date ( 'ymd_his' );
if (! is_dir ( $root ))
	mkdir ( $root );
is_dir ( $folder ) ? exit ( '任务队列已满，请刷新后再试。' ) : mkdir ( $folder );

/**
 * 循环爬取
 */
$failCount = $successCount = 0;
foreach ( $trackId as $i => $id ) {
	// 无法获取数据时报错并跳过
	if (getGPX ( $id ) == false) {
		echo '<p>' . ++ $i . '：无法获取ID为' . $id . '的GPX数据，请检查轨迹ID正确性，并确保轨迹未设置隐藏。</p>';
		$failCount ++;
	} else {
		write2File ( getGPX ( $id ), $folder . '\\' . $id . '.gpx' );
		echo '<p>' . ++ $i . '：轨迹' . $id . ' 导出成功。</p>';
		$successCount ++;
	}
}
echo '<p>成功' . $successCount . '个，失败' . $failCount . '个。</p>';
// 无文件则删除文件夹
if ($successCount == 0)
	rmdir ( $folder );

// TODO zip打包gpx

?>