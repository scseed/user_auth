var thumb_width = 1;
var thumb_height = 1;
var image_handling_path = '/ajax/image/';

//create a preview of the selection
function preview(img, selection) {
	//get width and height of the uploaded image.
	var current_width = $('#uploaded_image').find('#thumbnail').width();
	var current_height = $('#uploaded_image').find('#thumbnail').height();

	var scaleX = thumb_width / selection.width;
	var scaleY = thumb_height / selection.height;

	$('#x1').val(selection.x1);
	$('#y1').val(selection.y1);
	$('#x2').val(selection.x2);
	$('#y2').val(selection.y2);
	$('#w').val(selection.width);
	$('#h').val(selection.height);
}

//show and hide the loading message
function loadingmessage(msg, show_hide){
	if(show_hide=="show"){
		$('#loader').show();
		$('#progress').show().text(msg);
		$('#uploaded_image').html('');
	}else if(show_hide=="hide"){
		$('#loader').hide();
		$('#progress').text('').hide();
	}else{
		$('#loader').hide();
		$('#progress').text('').hide();
		$('#uploaded_image').html('');
	}
}

$(document).ready(function () {

	$('#profile_edit').click(function(){
		$.ajax({
			url: profile_edit_ajax_link,
			type: 'GET',
			success: function(data) {
				var textareas = '';
				for(var i = 0; i < data.length; i++)
				{
					textareas += '<div class="form-item"><label>Язык описания: '+data[i].lang_name+'</label><textarea name="'+data[i].lang_abbr+'" rel="description" rows="15" class="uniform">' + data[i].text + '</textarea></div><div class="form-item"><br />';
				}
				var edit_profile_form = '<div id="edit_profile_description">' + textareas + '<button class="ui-button ui-widget ui-state-default ui-corner-all ui-button-text-only" role="button" aria-disabled="false"><span class="ui-button-text">Сохранить</span></button></div></div>';

				$('#description *').remove();
				$('#description').append(edit_profile_form);
				$('#profile_edit').hide();

				$('#edit_profile_description button').click(function() {
					var descriptions = $('textarea[rel=description]');
					var new_descriptions = '', description_object = null;
					for(i=0; i < descriptions.length; i++)
					{
						description_object = $(descriptions[i]);
						new_descriptions  += description_object.attr('name')+'='+description_object.val();
						if(i < (descriptions.length - 1)) {new_descriptions  += '&';}
					}
					$.ajax({
						url: profile_edit_ajax_link,
						type: 'POST',
						data: new_descriptions,
						success: function(data){
							$('#description *').remove();
							$('#description').append(data.new_description);
							$('#profile_edit').show();
						}
					});
					return false;
				})
			}
		});
		return false;
	});

	$('#roles_edit').click(function(){
		$.ajax({
			url: profile_roles_ajax_link,
			type: 'GET',
			success: function(data) {
				var roles_checkboxes = '';
				for(var i = 0; i < data.length; i++)
				{
					roles_checkboxes += '<div class="form-item">'
					+ '<input type="checkbox" id="role_'+data[i].role_id+'" name="roles['+data[i].role_id+']" value="'+data[i].is_checked+'" '+data[i].checked+'>'
					+ '<label for="role_'+data[i].role_id+'">'+data[i].role_name+'</label></div>';
				}
				roles_checkboxes += '<button class="ui-button ui-widget ui-state-default ui-corner-all ui-button-text-only" role="button" aria-disabled="false"><span class="ui-button-text">Сохранить</span></button>';

				$('#roles').empty();
				$('#roles').append(roles_checkboxes);
				$('#roles_edit').hide();

				$('input[type=checkbox]').change(function(){
					var prev_val = $(this).val();
					var new_val = (prev_val == '0') ? 1 : 0;
					$(this).val(new_val);
				});

				$('#roles button').click(function(){
					var roles = $('input[name*=roles]');
					var new_roles = '', role_object = null;
					for(i=0; i < roles.length; i++)
					{
						role_object = $(roles[i]);
						new_roles  += role_object.attr('name')+'='+role_object.val();
						if(i < (roles.length - 1)) {new_roles  += '&';}
					}
					$.ajax({
						url: profile_roles_ajax_link,
						type: 'POST',
						data: new_roles,
						success: function(data) {
							$('#roles').empty();
							$('#roles').append(data.new_roles);
							$('#roles_edit').show();
						}
				    })
				})
			}
		});
		return false;
	})

	$('#events_edit').click(function(){
		$.ajax({
			url: profile_events_ajax_link,
			type: 'GET',
			success: function(data) {
				var events_checkboxes = '';
				for(var i = 0; i < data.length; i++)
				{
					events_checkboxes += '<div class="form-item">'
					+ '<input type="checkbox" id="event_'+data[i].event_id+'" name="events['+data[i].event_id+']" value="'+data[i].is_checked+'" '+data[i].checked+'>'
					+ '<label for="event_'+data[i].event_id+'">'+data[i].event_name+'</label></div>';
				}
				events_checkboxes += '<button class="ui-button ui-widget ui-state-default ui-corner-all ui-button-text-only" role="button" aria-disabled="false"><span class="ui-button-text">Сохранить</span></button>';

				$('#events').empty();
				$('#events').append(events_checkboxes);
				$('#events_edit').hide();

				$('input[type=checkbox]').change(function(){
					var prev_val = $(this).val();
					var new_val = (prev_val == '0') ? 1 : 0;
					$(this).val(new_val);
				});

				$('#events button').click(function(){
					var events = $('input[name*=events]');
					var new_events = '', event_object = null;
					for(i=0; i < events.length; i++)
					{
						event_object = $(events[i]);
						new_events  += event_object.attr('name')+'='+event_object.val();
						if(i < (events.length - 1)) {new_events  += '&';}
					}
					$.ajax({
						url: profile_events_ajax_link,
						type: 'POST',
						data: new_events,
						success: function(data) {
							$('#events').empty();
							$('#events').append(data.new_events);
							$('#events_edit').show();
						}
				    })
				})
			}
		});
		return false;
	})

	$('#loader').hide();
	$('#progress').hide();

	var upload_action = image_handling_path+'upload';
	if(typeof profile_id != 'undefined')
	{
		upload_action += '/'+profile_id;
	}


	if($('#change_avatar').length)
	{
		var myUpload = $('#change_avatar').upload({
			name: 'image',
			action: upload_action,
			enctype: 'multipart/form-data',
			params: {is_ajax: 'true'},
			autoSubmit: true,
			onSubmit: function() {
				$('#upload_status').html('').hide();
				loadingmessage(uploading_image, 'show');
			},
			onComplete: function(json_response) {
				loadingmessage('', 'hide');
				var response = jQuery.parseJSON(json_response);
				var response = response[0];
				var responseType = response.status;
				var responseMsg = response.message;
				if(responseType=="success"){
					var current_width = response.message.width;
					var current_height = response.message.height;
					//put the image in the appropriate div
					$('#uploaded_image').html('<img src="'+response.message.thumb_src+'" style="float: left; margin-right: 10px;" id="thumbnail" alt="Create Thumbnail" />')
					//find the image inserted above, and allow it to be cropped
					$('#uploaded_image').find('#thumbnail').imgAreaSelect({ aspectRatio: '1:'+thumb_height/thumb_width, onSelectChange: preview });
					//display the hidden form
					$('#thumbnail_form').show();
					$('#avatar').hide();
				}else if(responseType=="error"){
					$('#upload_status').show().html(response.message);
					$('#uploaded_image').html('');
					$('#thumbnail_form').hide();
					$('#avatar').show();
				}else{
					$('#upload_status').show().html('Unexpected Error! Please try again. '+response.message);
					$('#uploaded_image').html('');
					$('#thumbnail_form').hide();
					$('#avatar').show();
				}
			}
		});
	}

	//create the thumbnail
	$('#save_thumb').click(function() {
		var x1 = $('#x1').val();
		var y1 = $('#y1').val();
		var x2 = $('#x2').val();
		var y2 = $('#y2').val();
		var w = $('#w').val();
		var h = $('#h').val();
		var profile_id = $('#profile_id').val();

		if(profile_id == 'undefined')
			profile_id = null;

		if(x1=="" || y1=="" || x2=="" || y2=="" || w=="" || h==""){
			alert(make_a_selection);
			return false;
		}else{
			//hide the selection and disable the imgareaselect plugin
			$('#uploaded_image').find('#thumbnail').imgAreaSelect({ disable: true, hide: true });
			loadingmessage(saving_thumbnail, 'show');
			$.ajax({
				type: 'POST',
				url: image_handling_path+'save',
				data: {'x1':x1,'y1':y1,'x2':x2,'y2':y2,'w':w,'h':h, 'profile_id':profile_id},
				cache: false,
				success: function(json_response){
					loadingmessage('', 'hide');
					var response = json_response;

					var responseType = response.status;
					var responseLargeImage = response.message.large_image_src;
					if(responseType=="success"){
						//load the new images
						$('#avatar').attr('src', responseLargeImage);
						//hide the thumbnail form
						$('#thumbnail_form').hide();
						$('#avatar').show();
					}else{
						$('#upload_status').show().html('Unexpected Error! Please try again'+response);
						//reactivate the imgareaselect plugin to allow another attempt.
						$('#uploaded_image').find('#thumbnail').imgAreaSelect({ aspectRatio: '1:'+thumb_height/thumb_width, onSelectChange: preview });
						$('#thumbnail_form').show();
						$('#avatar').show();
					}
				}
			});

			return false;
		}
	});
})