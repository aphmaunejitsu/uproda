$(function() {
	new Clipboard('.btn');
	$("form[name='image-delete']").submit(function() {
		if (!confirm("削除します")) return false;
	});
})
