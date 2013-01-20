$(document).ready(function(){
	if($('#phone').length)
	{
		$('#phone').mask('9 999 999-99-99');
	}
	if($('#birthdate').length)
	{
		$('#birthdate').mask('99.99.9999');
	}

	$('#userdata, #passwordChange').find('input').on('keydown', function(e){
		var control = $(this);
		var loading = control.parent().find('.loading').find('.icon-refresh');
		loading.removeClass('hide').css({cursor:'pointer'});
		if (e.keyCode == 13) {
			e.preventDefault();
			control.focusout();
			control.trigger('change');
			control.parent().parent().parent().next().find('input').focus();
			return;
		}
	});

	$('#userdata, #passwordChange').find('input').on('change', function(){
		var control = $(this);
		var loading = control.parent().find('.loading').find('.icon-refresh');
		var group   = control.parent().parent();

		loading.removeClass('hide').addClass('rotate');
		control.attr('disabled', 'disabled');

		var name  = $(this).prop('name');
		var value = $(this).val();
		$.ajax({
			url: '/'+lang+'/ajax/user/update',
			type:'post',
			dataType:'json',
			data: {name: name, value: value},
			success: function(response){
				loading.addClass('hide').removeClass('rotate');
				control.removeAttr('disabled');
				if(response.status == true)
				{
					group.removeClass('error').addClass('success');
					if(response.redirect)
						checkDataFillness(response.redirect);
				}
				else
				{
					group.addClass('error').removeClass('success');
					var inputError = $('#inputError');
					var errorBody = inputError.find('.modal-body');
					inputError.find('.modal-body').empty();
					$.each(response.errors, function(idx, error){
						$.each(error, function(param, val){errorBody.append('<p>'+val+'</p>')});
					});

					inputError.modal('show');
				}
			}
		})
	});
});

function checkDataFillness(redirect)
{
	var errors = $('#userdata').find('.error');
	if(!errors.length)
		window.location.href = redirect;
}
