$(document).on("ajaxSend", function(e,jqXHR,obj){
	var $loading = $(".loading");
	$loading.removeClass("is-hide");
	setTimeout(function() {
		$.when(jqXHR)
		.done(function(data) {
			$loading.addClass("is-hide");
			obj.loadingHide(data);
		})
		.fail(function(data, text) {
			$loading.addClass("is-hide");
			obj.loadingError(data, text);
		});
	}, 400);
});

$(function() {
	$('#form-uproda').on('submit', function() {
		$(".mfp-close").click();
		$('input[type="submit"]').prop('disabled', true);
		var data = new FormData($('#form-uproda').get(0));
		var action = $('#form-uproda').attr('action');
		$.ajax({
			url: action,
			type: 'POST',
			data: data,
			processData: false,
			contentType: false,
			timeout: 30000,
			dataType: 'json',
			loadingHide: function(xhr) {
				location.href = xhr.image;
			},
			loadingError: function(xhr, text) {
				$('input[type="submit"]').prop('disabled', false);
				alert(xhr.responseJSON.message);
			}
		});
		return false;
	});

	$('.warecoli').stickMe({topOffset:100, shadow:true});
	$('.open-popup-form').magnificPopup({
		type:'inline', midClick: true,
		callbacks: {
			open: function() {
				$('.uproda-captcha').attr('src','/captcha/image?' + Math.random());
				$('#form_simplecaptcha').val('');
			},
			close: function() {}
		}
	});
})
