<?php
$sessionId = $_GET ['sessionid'];

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

/**
 * 爬取行者的GPX数据。
 *
 * @param int $id
 *        	行者轨迹ID
 * @return GPX数据
 */
function getGPX($id) {
	global $sessionId;
	$url = 'http://www.imxingzhe.com/xing/' . $id . '/gpx/'; // 欲爬取的url
	
	$ch = curl_init ( $url );
	curl_setopt ( $ch, CURLOPT_COOKIE, 'sessionid=' . $sessionId ); // 模拟登录
	curl_setopt ( $ch, CURLOPT_RETURNTRANSFER, true ); // 存入$result而不是直接输出
	$result = curl_exec ( $ch );
	curl_close ( $ch );
	
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
foreach ( $trackId as $i => $id ) {
	// 无法获取数据时报错并跳过
	if (getGPX ( $id ) == false) {
		echo '<p>' . ++ $i . '：无法获取ID为' . $id . '的GPX数据，请检查轨迹ID正确性，并确保轨迹未设置隐藏。</p>';
	} else {
		write2File ( getGPX ( $id ), $folder . '\\' . $id . '.gpx' );
		echo '<p>' . ++ $i . '轨迹' . $id . ' 导出成功。</p>';
	}
}

// 无文件则删除文件夹
if (count ( scandir ( $folder ) ) <= 2) {
	rmdir ( $folder );
}

?>