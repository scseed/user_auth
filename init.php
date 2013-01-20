<?php defined('SYSPATH') or die('No direct script access.');

$c_i18n = Kohana::$config->load('i18n');

$allowedLangs = implode('|', array_keys((array)$c_i18n->allowedLangs));
$allowedLangs = "(?i:$allowedLangs)";
$defaultLang = $c_i18n->defaultLang;

Route::set('auth', '(<lang>/)auth(/<action>(/<hash>))', array(
	'lang'       => $allowedLangs,
	'hash'       => '[a-zA-Z0-9_]+'
))
->defaults(array(
	'lang'       => $defaultLang,
	'controller' => 'auth',
	'action'     => 'user',
	'is_partner' => NULL
));
Route::set('auth_ajax', '(<lang>/)auth_ajax/<action>', array(
	'lang'       => $allowedLangs,
))
->defaults(array(
	'lang'       => $defaultLang,
	'directory'  => 'ajax',
	'controller' => 'auth',
));

Route::set('user', '(<lang>/)user(/<action>(/<id>))', array(
	'lang'       => $allowedLangs,
))
->defaults(array(
	'lang'       => $defaultLang,
	'controller' => 'user',
	'action'     => 'cabinet',
));