<!DOCTYPE HTML>
<html>
<head>
<title></title>
</head>

<body>

<?php
if (@$_POST ['sessionid'] == NULL)
	wrong ( 'sessionid为空，请返回首页重新执行。' );
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


?>

</body>
</html>