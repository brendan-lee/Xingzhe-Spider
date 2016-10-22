function submit() {
	//$('body').html('<p>正在爬取GPX数据，过程视数据量可能持续数秒至数分钟，请不要关闭页面。</p>');
	//$('title').html('正在爬取GPX数据……');

	$.post('validate.php', {
		sessionid: $('#sessionid').val()
	}, function(data) {
		$('body').append(data);
	})
}