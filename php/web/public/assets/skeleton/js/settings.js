$(function() {
	$('#list-mode').on('click', function() {
		var mode = $.cookie('binjou') == 0?1:0;
		$(this).toggleClass('button-primary');
		$.cookie('binjou', mode, {expire: 30});
	});
})

