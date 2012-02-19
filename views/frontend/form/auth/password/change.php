<?php defined('SYSPATH') or die('No direct access allowed.');?>
<?php defined('SYSPATH') OR die('No direct access allowed.');?>
<div class="row-fluid" style="margin: 5em auto">
	<div class="span3">&nbsp;</div>
	<div class="span5">
	<?php echo Form::open(Request::current(), array('class' => 'form-horizontal')); ?>
		<fieldset>
			<legend><?php echo __('Установите новый пароль');?></legend>
			<?php if (!empty($errors)): ?>
				<div class="alert alert-error">
					<?php echo $errors ?>
				</div>
			<?php endif ?>
			<?php if (!empty($message)): ?>
				<div class="alert alert-success">
					<?php echo $message ?>
				</div>
			<?php endif ?>
			<div class="control-group">
				<?php echo Form::label('cp_password', __('Новый пароль'), array('class' => 'control-label'))?>
				<div class="controls">
					<div class="input-prepend">
						<span class="add-on"><i class="icon-asterisk"></i></span>
						<?php echo Form::input(
							'password',
							NULL,
							array('type' => 'password', 'id' => 'cp_password', 'class' => 'input-xlarge')
						);?>
					</div>
				</div>
			</div>
			<div class="control-group">
				<?php echo Form::label('cp_password_confirm', __('Подтверждение пароля'), array('class' => 'control-label'))?>
				<div class="controls">
					<div class="input-prepend">
						<span class="add-on"><i class="icon-asterisk"></i></span>
						<?php echo Form::input(
							'password_confirm',
							NULL,
							array('type' => 'password', 'id' => 'cp_password_confirm', 'class' => 'input-xlarge')
						);?>
					</div>
				</div>
			</div>
			<div class="form-actions">
				<div class="btn-toolbar">
					<div class="btn-group">
						<?php echo Form::button(NULL, __('Сменить пароль'), array ('type' => 'submit', 'class' => 'btn btn-primary'));?>
					</div>
					<div class="btn-group">
						<?php echo HTML::anchor(
							Route::url('default', array('action' => '', 'controller' => '')),
							__('Отмена'),
							array('class' => 'btn')
						)?>
					</div>
				</div>
			</div>
		</fieldset>
	<?php echo Form::close(); ?>
	</div>
</div>