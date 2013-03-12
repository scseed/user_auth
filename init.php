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
	'controller' => 'Auth',
	'action'     => 'user',
	'hash' => '',
));
Route::set('auth_ajax', '(<lang>/)auth_ajax/<action>', array(
	'lang'       => $allowedLangs,
))
->defaults(array(
	'lang'       => $defaultLang,
	'directory'  => 'ajax',
	'controller' => 'Auth',
));

Route::set('user', '(<lang>/)user(/<action>(/<id>))', array(
	'lang'       => $allowedLangs,
))
->defaults(array(
	'lang'       => $defaultLang,
	'controller' => 'User',
	'action'     => 'cabinet',
	'id' => '',
	'path' => '',
));