<?php defined('SYSPATH') or die('No direct script access.');

$langs = NULL;
if(class_exists('Page'))
{
	/**
	 * Load language conf
	 */
	$langs = Page::instance()->system_langs();
}

Route::set('auth', '(<lang>/)auth(/<action>(/<hash>))', array(
	'lang'       => $langs,
	'hash'       => '[0-9a-zA-Z_]+'
))
->defaults(array(
	'lang'       => ($langs) ? I18n::lang() : '',
	'controller' => 'auth',
	'action'     => 'user',
	'is_partner' => NULL
));

Route::set('user', '(<lang>/)user(/<action>)(/<id>)', array(
	'lang'       => $langs,
))
->defaults(array(
	'lang'       => ($langs) ? I18n::lang() : '',
	'controller' => 'user',
	'action'     => 'cabinet',
));