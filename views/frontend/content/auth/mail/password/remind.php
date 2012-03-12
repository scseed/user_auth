<?php defined('SYSPATH') or die('No direct access allowed.');?>
<h3>Здравствуйте!</h3>
<p>Вами была подана заявка на смену пароля. Если заявку отправили не Вы, просьба не обращать на письмо внимание.</p>
<p><?php echo HTML::anchor(
	Route::url('auth', array('lang' => $lang, 'action' => 'hash_login', 'hash' => $hash)),
	__('Войти для смены пароля'),
	NULL,
	TRUE
)?></p>