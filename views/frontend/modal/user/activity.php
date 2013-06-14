<?php defined('SYSPATH') or die('No direct access allowed.');?>
<div id="userActivity" class="modal hide fade" xmlns="http://www.w3.org/1999/html">
	<div class="modal-header">
		<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
		<h3><?php echo __('Изменение активности пользователя')?></h3>
	</div>
	<?php echo Form::open(Route::url('ajax', array('lang' => I18n::$lang, 'controller' => 'user', 'action' => 'activity')))?>
	<div class="modal-body"></div>
	<div class="modal-footer">
		<button class="btn btn-danger btn-loading" data-loading-text="<?php echo __('Отправление...')?>"><?php echo __('Изменить')?></button>
		<a href="#" data-dismiss="modal" class="btn"><?php echo __('Отмена')?></a>
	</div>
	<?php echo Form::close();?>
</div>