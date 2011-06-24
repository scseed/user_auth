<?php defined('SYSPATH') or die('No direct script access.');

// Load language conf
$langs = Page::instance()->system_langs();

Route::set('auth', '(<lang>/)auth(/<action>(/<is_partner>)(/<hash>))', array(
	'lang'       => $langs,
	'is_partner' => '(partner)'
))
->defaults(array(
	'lang'      => I18n::lang(),
	'controller' => 'auth',
	'action'     => 'user',
	'is_partner' => NULL
));

Route::set('user', '(<lang>/)user(/<action>)', array(
	'lang'       => $langs,
))
->defaults(array(
	'lang'      => I18n::lang(),
	'controller' => 'user',
	'action'     => 'cabinet',
));