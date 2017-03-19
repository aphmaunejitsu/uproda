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

	$(".hash_event").on('click', function() {
		var $b = $(this).button('loading');
		$f = $('form[name="change-hash-ng-state"]');
		$.ajax({
			url: $b.data('action'),
			type: 'POST',
			data: $f.serialize(),
			timeout: 30000,
			dataType: 'json'
		})
		.done(function(data) {
			console.log(data);
		})
		.fail(function(d) {
			alert('処理に失敗しました。時間をおいて実行してください。');
		})
		.always(function(data) {
			$b.button('reset');
		});
	});
});
