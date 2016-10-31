/**
 * ajax提交验证
 */
function validate() {
	$.post('validate.php', {
		sessionid: $('#sessionid').val(),
		uid: $('#uid').val(),
		fromY: $('#from_year option:selected').val(),
		fromM: $('#from_month option:selected').val(),
		toY: $('#to_year option:selected').val(),
		toM: $('#to_month option:selected').val()
	}, function(data) {
		$('#wrapper').append(data);
	})
}

/**
 * ajax提交爬取请求
 * @param String taskId 任务ID
 */
var folderName = '';
function grab(taskId, uid, submitTimes) {
	// 爬取每月轨迹清单
	$.post('grab-track-list.php', {
		taskId: taskId,
		uid: uid
	}, function(data) {
		$('#wrapper').append(data);
		gpx();
	});
	
	// 爬取GPX数据
	function gpx() {
		for (var i = 0; i < submitTimes; i++) {
			$.post('spider.php', {
				taskId: taskId,
				sessionId: sessionid,
				time: i,
				folder: folderName
			}, function(data) {
				$('#wrapper').append(data);
			});
		}
	}
}

/**
 * 显示信息窗口
 * @param {Object} title 标题
 * @param {Object} cont 内容
 */
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

/**
 * 关闭信息窗口
 * @param {Object} obj 调用该方法的object
 */
function closeMsg(obj) {
	if(event.target != obj) return;

	var msg = $('#pop_msg');
	var bg = $('#pop_back');

	bg.css('opacity', 0);
	bg.one('transitionend', function clr() {
		bg.css('visibility', 'hidden');
	})
}

/**
 * 动态生成年份/月份
 */
(function() {
	var fromY = $('#from_year'),
		toY = $('#to_year');
	var fromM = $('#from_month'),
		toM = $('#to_month');

	var date = new Date();
	var curY = date.getFullYear();
	var curM = date.getMonth() + 1;

	// 写入年份
	for(var i = 2012; i <= curY; i++) {
		fromY.append('<option>' + i + '</option>');
		toY.append('<option>' + i + '</option>');
		month(fromY, fromM);
		month(toY, toM);
	}

	// 年份改变时动态生成月份
	fromY.on('change', function() {
		month(fromY, fromM);
	});
	toY.on('change', function() {
		month(toY, toM);
	});

	/**
	 * 根据年份生成月份
	 */
	function month(y, m) {
		m.html('');
		var selY = y.find('option:selected').val();
		var mLim = selY == curY ? curM : 12;
		for(var i = 1; i <= mLim; i++) {
			m.append('<option>' + i + '</option>');
		}
	}
}())