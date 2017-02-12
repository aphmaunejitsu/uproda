$(document).on("ajaxSend", function(e,jqXHR,obj){
	var $loading = $(".loading");
	$loading.removeClass("is-hide");
	setTimeout(function(){
		$.when(jqXHR).done(function(data){
			$loading.addClass("is-hide");
			obj.loadingHide(data);
		});
	},400);
});

$(function() {
  var url = '/image/list/' + $('#image-list').data('page');
  console.log(url);
	$.ajax({
		url: url,
		dataType:"html",
		loadingHide: function(data){
			var list = $(data);
			$("#image-list").html(list);
			list.ready(function () {
				$("img.lazy").lazyload();
			});
		}
	});
});
