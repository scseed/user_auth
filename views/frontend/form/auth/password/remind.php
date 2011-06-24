<?php defined('SYSPATH') or die('No direct access allowed.');?>
<div id="remind_password">
	<?php if($errors): foreach($errors as $error):?>
	<div class="error"><?php echo $error?></div>
	<?php endforeach; endif;?>
	<?php echo Form::open(Request::current())?>
		<div class="form-item">
			<?php echo Form::label('remind_email', __('Эл. адрес'))?>
			<?php echo Form::input('email', $post['email'], array('id' => 'remind_email'))?>
		</div>
		<div class="form-item">
			<?php echo Form::button(NULL, __('Выслать пароль на email'))?>
		</div>
	<?php echo Form::close()?>
</div>