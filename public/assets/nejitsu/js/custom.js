$(document).ready(function(){
	$('span.image-delete').hover(function() {
		$(this).css('cursor', 'pointer');
	},
	function() {
		$(this).css('cursor', 'default');
	});

	$('span.image-delete').on('click', function() {
		if (!confirm("削除します")) return false;
		$(this).children('form[name="image-delete"]').submit();
	});
});
