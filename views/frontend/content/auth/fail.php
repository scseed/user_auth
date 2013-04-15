<?php defined('SYSPATH') or die('No direct access allowed.');?>
<div class="row-fluid" style="margin: 5em auto">
	<div class="span4">&nbsp;</div>
	<div class="span4">
		<h3><?php echo __('Ошибка!')?></h3>
		<div class="alert alert-danger">
			<p>Номер сессии не найден, либо период ожидания подтверждения истёк.</p>
		</div><div class="alert alert-info">
			<p>Необходимо зарегистрироваться повторно, либо обновить сессию, пройдя по соответствующей ссылке в регистрационном письме.</p>
		</div>
		<?php echo HTML::anchor('/', 'Вернуться')?>
	</div>
</div>