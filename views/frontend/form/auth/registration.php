<?php defined('SYSPATH') or die('No direct access allowed.');?>
<h4><?php echo __('Внимание! Все поля обязательны к заполнению!')?></h4>
<?php if($errors): foreach($errors as $position => $suberrors): foreach($suberrors as $error):?>
<div class="error"><?php echo $error?></div>
<?php endforeach; endforeach; endif;?>
<div id="registration">
	<?php echo Form::open(Request::current())?>
	<?php echo Form::hidden('id', $post['id'])?>

	<div class="form-item" <?php echo (Arr::path($errors, 'user_data.last_name')) ? 'class="error"' : NULL?>>
		<?php echo Form::label('last_name', __('Фамилия'))?>
		<?php echo Form::input('user_data[last_name]', Arr::path($post, 'user_data.last_name'), array('id' => 'last_name'))?>
	</div>
	<div class="form-item" <?php echo (Arr::path($errors, 'user_data.first_name')) ? 'class="error"' : NULL?>>
		<?php echo Form::label('first_name', __('Имя'))?>
		<?php echo Form::input('user_data[first_name]', Arr::path($post, 'user_data.first_name'), array('id' => 'first_name'))?>
	</div>
	<div class="form-item" <?php echo (Arr::path($errors, 'user_data.patronymic')) ? 'class="error"' : NULL?>>
		<?php echo Form::label('patronymic', __('Отчество'))?>
		<?php echo Form::input('user_data[patronymic]', Arr::path($post, 'user_data.patronymic'), array('id' => 'patronymic'))?>
	</div>
	<div class="form-item" <?php echo (Arr::path($errors, 'user.email')) ? 'class="error"' : NULL?>>
		<?php echo Form::label('email', __('E-mail'))?>
		<?php echo Form::input('user[email]', Arr::path($post, 'user.email'), array('id' => 'email', 'title' => __('email_tooltip')))?>
	</div>
	<div class="form-item">
		<?php echo Form::button(NULL, __('Регистрация'))?>
	</div>
	<?php echo Form::close()?>
</div>