<?php defined('SYSPATH') or die('No direct access allowed.');?>
<p><?php echo __('register.password_change')?></p>
<div id="change_password">
<?php if($errors): foreach($errors as $error): if(is_string($error)):?>
<div class="error"><?php echo $error?></div>
<?php endif; endforeach; endif;?>
<?php if($message):?>
<div class="notice"><?php echo $message?></div>
<?php endif;?>
<?php echo Form::open(Request::current())?>
	<div class="form-item">
		<?php echo Form::label('cp_password', __('Новый пароль'))?>
		<?php echo Form::password('password', NULL, array('id' => 'cp_password'))?>
	</div>
	<div class="form-item">
		<?php echo Form::label('cp_password_confirm', __('Подтверждение пароля'))?>
		<?php echo Form::password('password_confirm', NULL, array('id' => 'cp_password_confirm'))?>
	</div>
	<div class="form-item"><?php echo Form::button(NULL, __('Сменить пароль'))?></div>

<?php echo Form::close()?>
</div>