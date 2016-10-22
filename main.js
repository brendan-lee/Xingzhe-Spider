function submit() {
	//$('body').html('<p>正在爬取GPX数据，过程视数据量可能持续数秒至数分钟，请不要关闭页面。</p>');
	//$('title').html('正在爬取GPX数据……');

	$.post('validate.php', {
		sessionid: $('#sessionid').val()
	}, function(data) {
		setTimeout(function() {
			$('body').append(data);
		}, 500);
	})
}

function msg(title, cont) {
	var msg = $('#pop_msg');
	var bg = $('#pop_back');

	msg.html('');
	msg.append('<div id="msg_title">' + title + '</div>');
	msg.append('<div id="msg_cont">' + cont + '</div>')

	bg.css({
		'visibility': 'visible',
		'opacity': '1'
	});

}

function closeMsg(obj) {
	if(event.target != obj) return;

	var msg = $('#pop_msg');
	var bg = $('#pop_back');

	bg.css('opacity', 0);
	bg.one('transitionend', function clr() {
		bg.css('visibility', 'hidden');
	})
}