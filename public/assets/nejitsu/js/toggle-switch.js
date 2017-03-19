$(document).ready(function(){
	$("input[name='image-ng']").bootstrapSwitch();
	$(".hash_event").on('click', function() {
		var $b = $(this).button('loading');
		$f = $('form[name="change-hash-ng-state"]');
		console.log($b.data('action'));
		$.ajax({
			url: $b.data('action'),
			type: post,
			data: $f.serialize(),
			timeout: 30000,
			dataType: 'json'
		})
		.done(function(data) {
			alert(data);
		}).
		.fail(function(data) {
		}).
		.always(function(data) {
			$b.button('reset');
		});
	});
});

