<!DOCTYPE HTML>
<html>
<head>
<title>行者GPX爬虫</title>
<link rel='stylesheet' href='main.css'>
</head>

<body>
<?php
// $jsonString = file_get_contents ( 'http://www.imxingzhe.com/api/v3/user_month_info?user_id=137311&year=2016&month=3' );
$jsonString = file_get_contents ( 'example.json' );
$allTracksArr = json_decode ( $jsonString, true ) ['data'] ['wo_info'];

$sessionId = '';

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
	
	return $result == NULL ? 0 : $result;
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
 * 爬取到的数据写入gpx文件
 */
// 创建文件夹
date_default_timezone_set('Asia/Shanghai');
$root = dirname ( __FILE__ ) . '\\gpx\\' . date('ymd_his');
for ($i = 0; is_dir($root); $i++) {
	$root = dirname ( __FILE__ ) . '\\gpx\\' . date('ymd_his');
	$root .= '_' . rand(0, 4); // 若文件夹已存在，在文件夹名后加随机数
	if ($i == 5) exit('任务队列已满，请刷新后再试。');
}
mkdir ( $root );



foreach ($trackId as $i => $id) {
// 	if ($id)
}

?>

<form action='submit.php'>
		<div>
			<span>行者sessionid： </span> <input id='sessionid' type='text' /> <input
				type='submit' />
		</div>
		<div>
			<span>日期范围</span>
		</div>
	</form>

</body>
</html>