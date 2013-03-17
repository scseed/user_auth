<?php defined('SYSPATH') or die('No direct access allowed.');?>
<div id="userAdd" class="modal hide fade" xmlns="http://www.w3.org/1999/html">
	<?php echo Form::open(Route::url('auth_ajax', array('lang' => I18n::$lang, 'action' => 'register')))?>
		<div class="modal-header">
			<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
			<h3><?php echo __('Создание пользователя')?></h3>
		</div>
		<div class="modal-body">
			<p><?php echo __('Укажите email нового пользователя. На этот ящик электронной почты будет отправлено приглашение и инструкции по регистрации.')?></p>
			<?php echo Form::input('email', NULL, array('class' => 'input-block-level', 'required' => 'required', 'placeholder' => __('Укажите email нового пользователя')))?>
		</div>
		<div class="modal-footer">
			<?php echo Form::button(NULL, __('Отправить'), array('class' => 'btn btn-success'))?>
			<?php echo HTML::anchor('#', __('Отмена'), array('data-dismiss' => 'modal', 'class' => 'btn'))?>
		</div>
	<?php echo Form::close();?>
</div>