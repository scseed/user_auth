$(document).ready(function(){
	var loginBlock = $('.form-signin');
	loginBlock.ajaxForm({
		dataType: 'json',
		beforeSend: function(){
			loginBlock.find('.errors').remove();
		},
		success: function(response, status){
			if(response.status == 1)
			{
				window.location.reload();
			}
			else
			{
				var alert_div = '<div class="errors alert alert-error">'
				+ response.message
				+ '</div>';
				loginBlock.find('.modal-body').prepend(alert_div);
			}
		}
	});
});