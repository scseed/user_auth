<?php defined('SYSPATH') or die('No direct access allowed.'); ?>
<div id="inputError" class="modal hide fade" data-backdrop="static">
	<div class="modal-header">
		<h3><?php echo __('Ошибка')?></h3>
	</div>
	<div class="modal-body"></div>
	<div class="modal-footer">
		<?php echo HTML::anchor('#', __('Пробовать ещё'), array('class' => 'btn btn-small', 'data-dismiss' => 'modal'))?>
	</div>
</div>