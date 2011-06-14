<?php defined('SYSPATH') or die('No direct access allowed.');?>
	<div class="form-item"><?php echo __('Привет')?>, <?php echo $user->user_data->first_name?>!</div>
	<div class="form-item">
		&nbsp;&nbsp;<?php echo HTML::anchor(Route::url('auth', array('lang' => I18n::lang(), 'action' => 'user')), __('Личный кабинет'), array('class' => 'button'))?>
	</div>
	<div class="form-item">
		<?php echo HTML::anchor(Route::url('auth', array('lang' => I18n::lang(), 'action' => 'logout')), __('Выход'), array('class' => 'button'))?>
	</div>