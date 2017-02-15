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
  var page = $('#image-list').data('page');
  if (page === undefined) {
	  page = 1;
  }

  var url = '/image/list/' + page;
	$.ajax({
		url: url,
		dataType:"html",
		loadingHide: function(data){
			var list = $(data);
			$("#image-list").html(list);
			list.ready(function () {
				//$(".images").fadeIn(function() {
				  $("img.lazy").lazyload({effect : "fadeIn"});
				  $(".pager").fadeIn();
				//});
			});
		}
	});
});
