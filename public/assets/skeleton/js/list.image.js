
$(function() {
  var page = $('#image-list').data('page');
  if (page === undefined) {
	  page = 1;
  }

  var url = '/api/v1/image/list/' + page;
  $.ajax({
  	url: url,
  	dataType:"html",
  	loadingHide: function(data){
  	   var list = $(data);
  	   $("#image-list").html(list);
  	   list.ready(function () {
  	     $("img.lazy").lazyload({effect : "fadeIn"});
  	     $(".pager").fadeIn();
  	   });
  	}
  });
});
