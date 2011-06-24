<?php defined('SYSPATH') or die('No direct access allowed.');?>
<div class="container">
	<p><?php echo __('register.confirm.link')?></p>
		<p><?php echo HTML::anchor(
			Route::url('auth', array('lang' => $lang, 'action' => 'confirmation', 'hash' => $hash)),
			__('Подтверждение регистрации'),
			NULL,
			TRUE
		)?></p>
	<p><strong><?php echo __('Внимание')?>!</strong> <?php echo __('register.confirm.session')?></p>
		<p><?php echo HTML::anchor(
			Route::url('auth', array('lang' => $lang, 'action' => 'update_hash', 'hash' => $hash)),
			__('Обновить сессию'),
			NULL,
			TRUE
			)?></p>
</div>