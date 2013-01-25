<?php defined('SYSPATH') or die('No direct access allowed.');?>
<div class="container">
	<h3>Здравствуйте.</h3>
	<p><?php echo Kohana::message('auth', 'registration.invitation')?></p>
		<p><?php echo HTML::anchor(
			Route::url('auth', array('lang' => $lang, 'action' => 'confirmation', 'hash' => $hash)),
			__('Войти и зарегистрироваться'),
			NULL,
			TRUE
		)?></p>
	<p><strong><?php echo __('Внимание')?>!</strong> <?php echo Kohana::message('auth', 'registration.session_end')?></p>
		<p><?php echo HTML::anchor(
			Route::url('auth', array('lang' => $lang, 'action' => 'update_hash', 'hash' => $hash)),
			__('Обновить сессию'),
			NULL,
			TRUE
			)?></p>
</div>