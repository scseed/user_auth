<?php defined('SYSPATH') or die('No direct access allowed.');?>
<div class="row-fluid" style="margin: 5em auto">
	<div class="span3">&nbsp;</div>
	<div class="span5">
		<?php echo Form::open(Request::current(), array('class' => 'form-horizontal'))?>
			<fieldset>
				<legend><?php echo __('Восстановление пароля');?></legend>
				<?php if (!empty($errors)): ?>
					<div class="alert alert-error">
						<?php foreach($errors as $error):?>
							<?php echo $error ?>
						<?php endforeach;?>
					</div>
				<?php endif ?>
				<div class="control-group">
					<?php echo Form::label('remind_email', __('Email'), array('class' => 'control-label'))?>
					<div class="controls">
						<div class="input-prepend">
							<span class="add-on"><i class="icon-envelope"></i></span>
							<?php echo Form::input(
								'email',
								Arr::get($post, 'email'),
								array('type' => 'text', 'id' => 'remind_email', 'class' => 'input-xlarge')
							);?>
						</div>
					</div>
				</div>
				<div class="form-actions">
					<div class="btn-toolbar">
						<div class="btn-group">
							<?php echo Form::button(NULL, __('Выслать пароль на email'), array ('type' => 'submit', 'class' => 'btn btn-primary'));?>
						</div>
						<div class="btn-group">
							<?php echo HTML::anchor(
								Route::url('auth', array('action' => 'login', 'lang' => $lang)),
								__('Отмена'),
								array('class' => 'btn')
							)?>
						</div>
					</div>
				</div>
		<?php echo Form::close()?>
	</div>
</div>