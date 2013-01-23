<?php defined('SYSPATH') or die('No direct access allowed.');?>
<div id="registrationEmailSend" class="modal hide fade">
	<div class="modal-header">
		<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
		<h3><?php echo __('Инструкции отправлены')?></h3>
	</div>
	<div class="modal-body">
		<p><?php echo Kohana::message('auth', 'registration.send')?></p>
	</div>
	<div class="modal-footer">
		<?php echo HTML::anchor('#', __('Спасибо'), array('class' => 'btn btn-small', 'data-dismiss' => 'modal'))?>
	</div>
	<?php echo Form::close()?>
</div>