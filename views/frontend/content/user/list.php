<?php defined('SYSPATH') or die('No direct access allowed.');?>
<h1>Пользователи Системы</h1>
<table id="users" class="table table-striped table-hover">
	<caption style="text-align: left;">
		<a id="userAddBtn" href="#userAdd" data-toggle="modal" class="btn btn-small pull-right"><i class="icon-plus"></i> Добавить пользователя</a>
		<?php echo Form::open(Route::url('ajax', array('lang' => I18n::$lang, 'controller' => 'selector', 'id' => 'user')))?><form class="form-inline">
			<div class="control-group">
				<label class="control-label" for="companies">Компания</label>
				<div class="controls">
					<select id="companies">
						<option>Все</option>
						<option selected>Василёк</option>
						<option>Навуходоноссер</option>
					</select>
				</div>
			</div>
			<div class="control-group">
				<label class="control-label" for="project">Проект</label>
				<div class="controls">
					<select id="project">
						<option>Все</option>
						<option selected>Проект 1</option>
						<option>Проект 2</option>
					</select>
				</div>
			</div>
		<?php echo Form::close()?>
	</caption>
	<thead>
	<tr>
		<th>ФИО</th>
		<th>Роль</th>
		<th>Компания</th>
		<th>Проект</th>
		<th class="status">Статус</th>
		<th class="actions"></th>
	</tr>
	</thead>
	<tbody>
	<?php foreach($users as $user):
		$status = ($user->is_active) ? __('активен') : __('отключён');
		$status_action = ($user->is_active) ? __('Отключить') : __('Включить');
		$status_icon   = ($user->is_active) ? 'ok-sign' : 'ban-circle';
		$status_color  = ($user->is_active) ? 'green' : 'red';
		$status_icon_change = ( ! $user->is_active) ? 'ok-sign' : 'ban-circle';
		?>
	<tr>
		<td><?php echo $user->last_name.' '.$user->first_name.' '.$user->patronymic?></td>
		<td><?php echo implode(', ', $user->roles->as_array('id', 'name'))?></td>
		<td>—</td>
		<td>Все</td>
		<td class="status">
			<i rel="tooltip" data-title="Пользователь <?php echo $status?>" data-placement="bottom" class="icon-<?php echo $status_icon?> icon-<?php echo $status_color?>"></i>
		</td>
		<td class="actions">
			<div class="btn-group hide">
				<a class="btn btn-small dropdown-toggle" data-toggle="dropdown" href="#">
					<i class="icon-cog"></i> Действия
					<span class="caret"></span>
				</a>
				<ul class="dropdown-menu pull-right">
					<li>
						<a id="user-edit-<?php echo $user->id?>"
						   data-remote="<?php echo Route::url('ajax', array('lang' => I18n::$lang, 'controller' => 'user', 'action' => 'edit_body', 'id' => $user->id))?>"
						   data-target="#userEdit"
						   data-toggle="modal"
						   title="<?php echo __('Редактировать пользователя')?>">
							<i class="icon-edit"></i> <?php echo __('Редактировать')?>
						</a>
					</li>
					<li class="divider"></li>
					<li>
						<a id="user-edit-status-<?php echo $user->id?>"
						   data-remote="<?php echo Route::url('ajax', array('lang' => I18n::$lang, 'controller' => 'user', 'action' => 'activity_body', 'id' => $user->id))?>"
						   data-target="#userActivity"
						   data-toggle="modal"
						   title="<?php echo $status_action?> пользователя">
							<i class="icon-<?php echo $status_icon_change?>"></i> <?php echo $status_action?>
						</a>
					</li>
				</ul>
			</div>
		</td>
	</tr>
	<?php endforeach;?>
	</tbody>
</table>
<!--<div class="pagination pagination-mini">-->
<!--	<ul>-->
<!--		<li class="disabled"><a href="#">«</a></li>-->
<!--		<li class="active"><a href="#">1</a></li>-->
<!--		<li><a href="#">2</a></li>-->
<!--		<li><a href="#">3</a></li>-->
<!--		<li><a href="#">4</a></li>-->
<!--		<li><a href="#">5</a></li>-->
<!--		<li><a href="#">»</a></li>-->
<!--	</ul>-->
<!--</div>-->