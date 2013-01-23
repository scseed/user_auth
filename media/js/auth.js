$(document).ready(function(){

	if(window.location.hash == '#remindPassword')
		$('#remindPassword').modal('show');

	var loginBlock = $('.form-signin');
	loginBlock.ajaxForm({
		dataType: 'json',
		beforeSend: function(){
			loginBlock.find('.errors').remove();
		},
		success: function(response, status){
			if(response.status == 1)
			{
				window.location.href = response.referrer;
			}
			else
			{
				var alert_div = '<div class="errors alert alert-error">'
				+ response.message
				+ '</div>';
				loginBlock.prepend(alert_div);
			}
		}
	});

	var passRemind = $('#remindPassword');
	passRemind.find('form').ajaxForm({
		dataType: 'json',
		beforeSend: function(){
			passRemind.find('.errors').remove();
		},
		success: function(response, status){
			if(response.status == 1)
			{
				passRemind.modal('hide');
				passRemind.on('hidden', function(){
					$('#passEmailSend').modal('show');
				});

			}
			else
			{
				var alert_div = '<div class="errors alert alert-error">'
				+ response.message
				+ '</div>';
				passRemind.find('.modal-body').prepend(alert_div);
			}
		}
	});
});