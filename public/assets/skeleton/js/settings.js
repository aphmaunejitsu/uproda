$(function() {
	$('input[name="listmode"]').on('change', function() {
		var checked = 0;
		if (!$(this).prop('checked')) checked = 1;
		$.cookie('binjou', checked, {expire: 30});
	});
})

