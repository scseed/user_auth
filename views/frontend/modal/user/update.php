<?php defined('SYSPATH') or die('No direct access allowed.');?>
<div id="userEdit" class="modal hide fade" xmlns="http://www.w3.org/1999/html">
	<div class="modal-header">
		<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
		<h3>Карточка пользователя</h3>
	</div>
	<div class="modal-body">
		<form>
			<div class="row-fluid">
				<div class="span4">
					<div class="control-group">
						<label class="control-label" for="lastName">Фамилия</label>
						<div class="controls">
							<input type="text" id="lastName" required="required" class="input-large" placeholder="Введите Вашу фамилию">
							<span class="loading"><i class="icon-refresh hide"></i></span>
						</div>
					</div>
				</div>
				<div class="span4">
					<div class="control-group">
						<label class="control-label" for="firstName">Имя</label>
						<div class="controls">
							<input type="text" id="firstName" required="required" class="input-large" placeholder="Введите Ваше имя">
							<span class="loading"><i class="icon-refresh hide"></i></span>
						</div>
					</div>
				</div>
				<div class="span4">
					<div class="control-group">
						<label class="control-label" for="patronymic">Отчество</label>
						<div class="controls">
							<input type="text" id="patronymic" class="input-large" placeholder="Введите Ваше отчество">
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
							<input type="text" id="phone" required="required" class="input-large" placeholder="+7 987 654-32-10">
							<span class="loading"><i class="icon-refresh hide"></i></span>
						</div>
					</div>
				</div>
				<div class="span4">
					<div class="control-group">
						<label class="control-label" for="email">Контактный email</label>
						<div class="controls">
							<input type="text" id="email" required="required" class="input-large" placeholder="your@email.com" >
							<span class="loading"><i class="icon-refresh hide"></i></span>
						</div>
					</div>
				</div>
				<div class="span4" style="padding-top: 30px;">
					<div class="control-group">
						<label class="checkbox">
							<input type="checkbox" value="" checked> Активен
						</label>
					</div>
				</div>
			</div>
			<div class="row-fluid">
				<div>
					<h4>Настройка оповещений</h4>
					<div class="control-group">
						<label class="checkbox">
							<input type="checkbox" value="" checked> email оповещения
						</label>
					</div>
					<div class="control-group">
						<label class="checkbox">
							<input type="checkbox" value=""> sms оповещения
						</label>
					</div>
				</div>
			</div>
		</form>
	</div>
	<div class="modal-footer">
		<button class="btn btn-success">Сохранить</button>
		<a href="#" data-dismiss="modal" class="btn">Отмена</a>
	</div>
</div>