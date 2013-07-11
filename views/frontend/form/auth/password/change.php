<?php defined('SYSPATH') or die('No direct access allowed.');?>
<h1><?php echo ($force_login) ? __('Установка пароля') : __('Смена пароля')?></h1>
<form id="passwordChange" class="form-horizontal">
	<div class="row-fluid">
		<div class="offset2 span6">
			<?php if( ! $force_login):?>
			<div class="control-group">
				<label class="control-label" for="passCurrent">Ваш текущий пароль</label>
				<div class="controls">
					<input type="text" name="old_password" id="passCurrent" required="required" class="input-xlarge">
					<input type="password" name="old_password" required="required" class="input-xlarge hide">
					<span class="loading"><i class="icon-refresh hide"></i></span>
				</div>
			</div>
			<?php endif;?>
			<div class="control-group">
				<label class="control-label" for="passNew"><?php echo ($force_login) ? __('Ваш пароль') : __('Новый пароль')?></label>
				<div class="controls">
					<input type="text" name="new_password" id="passNew" required="required" class="input-xlarge">
					<input type="password" name="new_password" required="required" class="input-xlarge hide">
					<span class="loading"><i class="icon-refresh hide"></i></span>
				</div>
			</div>
			<div class="control-group">
				<label class="checkbox">
					<div class="controls">
						<input type="checkbox" checked>
						Отображать вводимые символы
					</div>
				</label>
			</div>
			<div class="control-group">
				<div class="controls">
					<?php echo Form::button(NULL, __('Сохранить'), array('id' => 'userDataSaveBtn', 'class' => 'btn disabled', 'data-loading-text' => __('Сохранение...'), 'data-save-text' => __('Сохранить'), 'data-saved-text' => __('Сохранено'), 'disabled' => 'disabled'))?>
				</div>
			</div>
		</div>
	</div>
</form>