<?php defined('SYSPATH') OR die('No direct access allowed.');?>
<?php echo Form::open(Route::url('auth', array('action' => 'login', 'lang' => I18n::lang()))); ?>
	<?php if (!empty($errors) AND ! $is_ajax): ?>
		<h4 class="title"><div class="form-error"><?php echo $errors ?></div></h4>
	<?php endif ?>
	<div class="form-item">
		<?php echo Form::label('login_email', __('Эл. адрес'));?>
		<?php echo Form::input('email', $userdata['email'], array('type' => 'text', 'id' => 'login_email'));?>
	</div>
	<div class="form-item">
		<?php echo Form::label('login_password', __('Пароль'));	?>
		<?php echo Form::input('password', NULL, array('type' => 'password', 'id' => 'login_password'));?>
	</div>
	<div class="form-item">
		<?php echo Form::button(NULL, __('Войти'), array ('type' => 'submit'));?>
	</div>
	<?php if( ! $is_ajax):?>
	<div style="margin: 1em 0">
		<div class="form-item">
			<?php echo HTML::anchor(
				Route::url('auth', array('action' => 'pass_remind', 'lang' => I18n::lang())),
				__('Напомнить пароль'),
				array('class' => 'button')
			)?>
		</div>
	<?php endif;?>
		<div class="form-item">
			<?php echo ($is_ajax) ? '&nbsp;|&nbsp;' : NULL?>
			<?php echo HTML::anchor(
				Route::url('auth', array('action' => 'registration', 'lang' => I18n::lang())),
				__('Регистрация'),
				array('class' => 'button')
			)?>
		</div>
	<?php echo ( ! $is_ajax) ? '</div>' : NULL?>
<?php echo Form::close(); ?>