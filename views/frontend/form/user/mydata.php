<?php defined('SYSPATH') or die('No direct access allowed.');?>
<h1><?php echo __('Персональные настройки')?></h1>
<form id="userdata" class="form">
	<div class="row-fluid">
		<div class="span3">
			<div class="control-group">
				<label class="control-label" for="lastName"><?php echo __('Фамилия')?></label>
				<div class="controls">
					<input type="text" name="last_name" value="<?php echo $user->last_name?>" id="lastName" required="required" class="input-xlarge" placeholder="<?php echo __('Введите Вашу фамилию')?>">
					<span class="loading"><i class="icon-refresh hide"></i></span>
				</div>
			</div>
		</div>
		<div class="span3">
			<div class="control-group">
				<label class="control-label" for="firstName"><?php echo __('Имя')?></label>
				<div class="controls">
					<input type="text" name="first_name" value="<?php echo $user->first_name?>" id="firstName" required="required" class="input-xlarge" placeholder="<?php echo __('Введите Ваше имя')?>">
					<span class="loading"><i class="icon-refresh hide"></i></span>
				</div>
			</div>
		</div>
		<div class="span3">
			<div class="control-group">
				<label class="control-label" for="patronymic"><?php echo __('Отчество')?></label>
				<div class="controls">
					<input type="text" name="patronymic" value="<?php echo $user->patronymic?>" id="patronymic" class="input-xlarge" placeholder="<?php echo __('Введите Ваше отчество')?>">
					<span class="loading"><i class="icon-refresh hide"></i></span>
				</div>
			</div>
		</div>
	</div>
	<div class="row-fluid">
		<div class="span3">
			<div class="control-group">
				<label class="control-label" for="phone"><?php echo __('Контактный телефон')?></label>
				<div class="controls">
					<input type="text" name="phone" value="<?php echo $user->phone?>" id="phone" required="required" class="input-xlarge" placeholder="<?php echo __('+7 987 654-32-10')?>">
					<span class="loading"><i class="icon-refresh hide"></i></span>
				</div>
			</div>
		</div>
		<div class="span3">
			<div class="control-group">
				<label class="control-label" for="email"><?php echo __('Контактный email')?></label>
				<div class="controls">
					<input type="text" name="email" value="<?php echo $user->email?>" id="email" required="required" class="input-xlarge" placeholder="<?php echo __('your@email.com')?>" >
					<span class="loading"><i class="icon-refresh hide"></i></span>
				</div>
			</div>
		</div>
	</div>
</form>