<?php
/**
 * 爬取行者的GPX数据。
 *
 * @param int $id
 *        	行者轨迹ID
 * @return GPX数据
 */
function getGPX($sid, $id) {
	global $folder;
	// 欲爬取的url
	$url = 'http://www.imxingzhe.com/xing/' . $id . '/gpx/';

	$ch = curl_init($url);
	// 模拟登录
	curl_setopt($ch, CURLOPT_COOKIE, 'sessionid=' . $sid);
	// 存入$result而不是直接输出
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
	$result = curl_exec($ch);
	curl_close($ch);
	
	return ($result == NULL || $result == '') ? false : $result;
}

// TODO zip打包gpx
?>