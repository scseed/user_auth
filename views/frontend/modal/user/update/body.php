<?php defined('SYSPATH') or die('No direct access allowed.');?>
<?php echo Form::hidden('id', $user->id)?>
<div class="row-fluid">
	<div class="span4">
		<div class="control-group">
			<label class="control-label" for="lastName">Фамилия</label>
			<div class="controls">
				<input type="text" name="last_name" value="<?php echo $user->last_name?>" id="lastName" required="required" class="input-large" placeholder="Введите Вашу фамилию">
				<span class="loading"><i class="icon-refresh hide"></i></span>
			</div>
		</div>
	</div>
	<div class="span4">
		<div class="control-group">
			<label class="control-label" for="firstName">Имя</label>
			<div class="controls">
				<input type="text" name="first_name" value="<?php echo $user->first_name?>" id="firstName" required="required" class="input-large" placeholder="Введите Ваше имя">
				<span class="loading"><i class="icon-refresh hide"></i></span>
			</div>
		</div>
	</div>
	<div class="span4">
		<div class="control-group">
			<label class="control-label" for="patronymic">Отчество</label>
			<div class="controls">
				<input type="text" name="patronymic" value="<?php echo $user->patronymic?>" id="patronymic" class="input-large" placeholder="Введите Ваше отчество">
				<span class="loading"><i class="icon-refresh hide"></i></span>
			</div>
		</div>
	</div>
</div>
<div class="row-fluid">
	<div class="span4">
		<div class="control-group">
			<label class="control-label" for="phone">Контактный телефон</label>
			<div class="controls">
				<div class="input-prepend">
					<span class="add-on">+</span>
					<input type="text" name="phone" value="<?php echo $user->phone?>" id="phone" required="required" class="input-large" placeholder="7 987 654-32-10">
				</div>
				<span class="loading"><i class="icon-refresh hide"></i></span>
			</div>
		</div>
	</div>
	<div class="span4">
		<div class="control-group">
			<label class="control-label" for="email">Контактный email</label>
			<div class="controls">
				<input type="text" name="email" value="<?php echo $user->email?>" id="email" required="required" class="input-large" placeholder="your@email.com" >
				<span class="loading"><i class="icon-refresh hide"></i></span>
			</div>
		</div>
	</div>
	<div class="span4" style="padding-top: 30px;">
		<div class="control-group">
			<label class="checkbox">
				<input type="checkbox" name="is_active" value="1" <?php echo ($user->is_active) ? 'checked' : NULL ?>> Активен
			</label>
		</div>
	</div>
</div>
<div class="row-fluid">
	<div>
		<h4>Настройка оповещений</h4>
		<div class="control-group">
			<label class="checkbox">
				<input type="checkbox" name="notification_email" value="1" <?php echo ($user->notification_email) ? 'checked' : NULL ?>> email оповещения
			</label>
		</div>
		<div class="control-group">
			<label class="checkbox">
				<input type="checkbox" name="notification_sms" value="1" <?php echo ($user->notification_sms) ? 'checked' : NULL ?>> sms оповещения
			</label>
		</div>
	</div>
</div>