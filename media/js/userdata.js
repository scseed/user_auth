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

	$('#userdata, #passwordChange').find('input[type!=checkbox]').on('change', function(){
		var control = $(this);
		var loading = control.parent().find('.loading').find('.icon-refresh');
		var group   = control.parent().parent();

		loading.removeClass('hide').addClass('rotate');
		control.attr('disabled', 'disabled');

		var name     = $(this).prop('name')
		  , value    = $(this).val()
		  , oldvalue = null
		  ;

		if(name == 'new_password')
			oldvalue = group.prev().find('input[type=password]').val();

		$.ajax({
			url: '/'+lang+'/ajax/user/update',
			type:'post',
			dataType:'json',
			data: {name: name, value: value, oldvalue: oldvalue},
			success: function(response){
				loading.addClass('hide').removeClass('rotate');
				control.removeAttr('disabled');
				var inputError  = $('#inputError')
				  , errorHeader = inputError.find('.modal-header')
				  , errorBody   = inputError.find('.modal-body')
				  , errorFooter = inputError.find('.modal-footer')
				;

				if(response.status == true)
				{
					group.removeClass('error').addClass('success');
					if(response.redirect && response.message == '')
						checkDataFillness(response.redirect);

					if(response.redirect && response.message)
					{
						inputError.find('.modal-body').empty();
						errorBody.append('<p>'+response.message.text+'</p>');
						errorHeader.find('h3').text(response.message.head);
						errorFooter.find('a')
							.attr('href', response.redirect)
							.text(response.message.button)
							.addClass('btn-success')
							.removeAttr('data-dismiss')
						;

						inputError.modal('show');
					}
				}
				else
				{
					group.addClass('error').removeClass('success');
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
