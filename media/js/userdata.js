$(document).ready(function(){
	var saveBtn  = $('#userDataSaveBtn')
	  , userdata = $('#userdata, #passwordChange')
	  , phone    = $('#phone')
	  , birthday = $('#birthdate')
	;

	if(phone.length)
	{
		phone.mask('9 999 999-99-99');
	}
	if(birthday.length)
	{
		birthday.mask('99.99.9999');
	}

	userdata.find('input').on('keydown', function(e){
		var control = $(this)
		  , loading = control.parent().find('.loading').find('.icon-refresh')
		;

		saveBtn.button('save');
		loading.removeClass('hide').css({cursor:'pointer'});
		if (e.keyCode == 13) {
			e.preventDefault();
			control.focusout();
			control.trigger('change');
			control.parent().parent().parent().next().find('input').focus();
			return;
		}
	});

	userdata.find('input[type!=checkbox]').on('change', function(){
		var control = $(this)
		  , loading = control.parent().find('.loading').find('.icon-refresh')
		  , group   = control.parent().parent()
		  , name     = $(this).prop('name')
		  , value    = $(this).val()
		  , oldvalue = null
		;

		loading.removeClass('hide').addClass('rotate');
		control.attr('disabled', 'disabled');

		if(name == 'new_password')
			oldvalue = group.prev().find('input[type=password]').val();

		$.ajax({
			url: '/'+lang+'/ajax/user/update',
			type:'post',
			dataType:'json',
			data: {name: name, value: value, oldvalue: oldvalue},
			beforeSend: function(){
				saveBtn.button('loading');
			},
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
					saveBtn.button('saved');

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
					saveBtn.button('reset');
				}
				setTimeout(function() {
					saveBtn.prop("disabled", true);
				}, 0);
			}
		});
	});
});

function checkDataFillness(redirect)
{
	var errors = $('#userdata').find('.error');
	if(!errors.length)
		window.location.href = redirect;
}
