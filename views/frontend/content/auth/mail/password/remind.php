<?php defined('SYSPATH') or die('No direct access allowed.');?>
<p><?php echo __('register.password.remind')?></p>
<p><?php echo HTML::anchor(
	Route::url('auth', array('lang' => $lang, 'action' => 'hash_login', 'hash' => $hash)),
	__('Войти для смены пароля'),
	NULL,
	TRUE
)?></p>