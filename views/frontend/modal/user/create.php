<?php defined('SYSPATH') or die('No direct access allowed.');?>
<div id="userAdd" class="modal hide fade" xmlns="http://www.w3.org/1999/html">
	<div class="modal-header">
		<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
		<h3>Создание пользователя</h3>
	</div>
	<div class="modal-body">
		<p>Укажите email нового пользователя. На этот ящик электронной почты будет отправлено приглашение и инструкции по регистрации в Buildox.</p>
		<?php echo Form::open(Route::url('auth_ajax', array('lang' => I18n::$lang, 'action' => 'register')))?>
		<input type="text" name="email" class="input-block-level" required="required" placeholder="Укажите email нового пользователя">
	</div>
	<div class="modal-footer">
		<button class="btn btn-success">Отправить</button>
		<a href="#" data-dismiss="modal" class="btn">Отмена</a>
		<?php echo Form::close();?>
	</div>
</div>