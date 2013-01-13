<?php defined('SYSPATH') or die('No direct access allowed.');?>
<div id="remindPassword" class="modal hide fade">
	<div class="modal-header">
		<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
		<h3>Восстановление пароля</h3>
	</div>
	<div class="modal-body">
		<p><?php echo Kohana::message('auth', 'pass_remind')?></p>
		<?php echo Form::open(Route::url('auth_ajax', array('lang' => I18n::$lang, 'action' => 'remember')))?>
			<input type="text" class="input-block-level" placeholder="your@email.com">
		<?php echo Form::close()?>
	</div>
	<div class="modal-footer">
		<a href="#" data-dismiss="modal" class="btn btn-success">Восстановить пароль</a>
		<a href="#" data-dismiss="modal" class="btn">Отмена</a>
	</div>
</div>