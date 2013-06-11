$(document).ready(function(){

	if(window.location.hash == '#remindPassword')
		$('#remindPassword').modal('show');

	var loginBlock = $('.form-signin');
	loginBlock.ajaxForm({
		dataType: 'json',
		beforeSend: function(){
			loginBlock.find('.errors').remove();
			loginBlock.find('button').button('loading');
		},
		success: function(response, status){
			if (navigator.appName == "Netscape"){
				history.pushState();
			}

			if(response.status == 1)
			{
				window.location.href = response.redirect;
			}
			else
			{
				var alert_div = '<div class="errors alert alert-error">'
				+ response.message
				+ '</div>';
				loginBlock.prepend(alert_div);
				loginBlock.find('button').button('reset');
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

	var userAdd = $('#userAdd');
	userAdd.find('form').ajaxForm({
		dataType: 'json',
		beforeSend: function(){
			userAdd.find('.errors').remove();
		},
		success: function(response, status){
			if(response.status == 1)
			{
				userAdd.modal('hide');
				userAdd.on('hidden', function(){
					$('#registrationEmailSend').modal('show');
				});

			}
			else
			{
				var alert_div = '<div class="errors alert alert-error">'
				+ response.message
				+ '</div>';
				userAdd.find('.modal-body').prepend(alert_div);
			}
		}
	});
});