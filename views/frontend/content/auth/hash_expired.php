<?php defined('SYSPATH') or die('No direct access allowed.'); ?>
<div class="row-fluid" style="margin: 5em auto">
	<div class="span4">&nbsp;</div>
	<div class="span4">
		<h3><?php echo __('Внимание!')?></h3>
		<div class="alert alert-danger">
			<p>Время сессии истекло! Для получения или смены пароля, воспользуйтесь <?php echo HTML::anchor(Route::url('auth', array('action' => 'pass_remind')), 'интерфейсом напоминания пароля')?>.</p>
			<p>Спасибо.</p>
		</div>
	</div>
</div>