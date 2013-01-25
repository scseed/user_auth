<?php defined('SYSPATH') or die('No direct access allowed.');?>
<div id="userEdit" class="modal hide fade" xmlns="http://www.w3.org/1999/html">
	<div class="modal-header">
		<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
		<h3>Карточка пользователя</h3>
	</div>
	<?php echo Form::open(Route::url('ajax', array('lang' => I18n::$lang, 'controller' => 'user', 'action' => 'edit')))?>
	<div class="modal-body"></div>
	<div class="modal-footer">
		<button class="btn btn-success">Сохранить</button>
		<a href="#" data-dismiss="modal" class="btn">Отмена</a>
	</div>
	<?php echo Form::close();?>
</div>