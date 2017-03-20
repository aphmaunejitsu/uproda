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

	$('form[name="change-hash-ng-state"]').submit(function() {
		return false;
	});

	$(".hash_event").on('click', function() {
		var $a = $(this).data('alert-text');
		if ($a !== false)
		{
			if (!confirm($a)) return false;
		}

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
			alert(data.message);
			console.log(data);
			if (data.reload)
			{
				location.reload();
			}
		})
		.fail(function(d) {
			alert('処理に失敗しました。リロードしてから実行してください');
		})
		.always(function(data) {
			$b.button('reset');
		});
	});
});
