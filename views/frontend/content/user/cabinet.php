<?php defined('SYSPATH') or die('No direct access allowed.');?>
<?php StaticJs::instance()->add('
	var make_a_selection = "'.__('Make a selection first!').'";
	var saving_thumbnail = "'.__('Saving thumbnail..').'";
	var uploading_image  = "'.__('Uploading image...').'";
	var deleting_image   = "'.__('Deleting image...').'";
	', NULL, 'inline')?>
<div id="personal_cabinet">
	<div id="info">
		<div id="photo">
			<div id="uploaded_image"></div>
			<?php echo HTML::image($avatar, array('alt' => '', 'id'=>'avatar'))?>
			<div>
				<?php echo HTML::anchor('#', __('change'), array('id' => 'change_avatar', 'class' => 'button_black'))?>
				<?php echo ($profile->has_avatar) ? HTML::anchor(
					Route::url('user', array('action' => 'delete_avatar')),
					__('delete'),
					array('id' => 'delete_avatar', 'class' => 'button_black')) : NULL;?>
			</div>
			<span id="loader" style="display:none;">
				<?php echo HTML::image('i/loader.gif', array('alt' => __('Loading...')))?>
			</span>
			<span id="progress"></span>
			<span id="upload_status"></span>
			<div id="uploaded_image"></div>
			<div id="thumbnail_form" style="display:none;">
				<?php echo Form::open(Route::url('ajax', array('controller' => 'image', 'action' => 'save')))?>
					<?php echo Form::hidden('x1', NULL, array('id' => 'x1'))?>
					<?php echo Form::hidden('x2', NULL, array('id' => 'x2'))?>
					<?php echo Form::hidden('y1', NULL, array('id' => 'y1'))?>
					<?php echo Form::hidden('y2', NULL, array('id' => 'y2'))?>
					<?php echo Form::hidden('w', NULL, array('id' => 'w'))?>
					<?php echo Form::hidden('h', NULL, array('id' => 'h'))?>
					<?php echo Form::button(NULL, __('сохранить'), array('id' => 'save_thumb'))?>
				<?php echo Form::close()?>
			</div>
		</div>
		<div id="contacts">
			<h3><?php echo $profile->nickname?></h3>
			<dl>
				<dt><?php echo __('Настоящее имя')?>:</dt>
				<dd>
					<?php echo ($profile->loaded() AND $user_data)
						? $user_data->last_name.' '
						  .$user_data->first_name.' '
						  .$user_data->patronymic
						: $user_data->real_name?>
				</dd>

				<?php if($user_data->country->id):?>
				<dt><?php echo __('Страна').', '.__('Город')?>:</dt>
				<dd><?php echo $user_data->country->name?><?php echo ($user_data->city->id) ? ', '.$user_data->city->name : NULL?></dd>
				<?php endif;?>

				<?php if($user_data->country->id):?>
				<dt><?php echo __('Языки общения')?>:</dt>
				<dd><?php echo $user_data->language->name?></dd>
				<?php endif;?>

				<?php if($user_data->birthdate):?>
				<dt><?php echo __('Дата рождения')?>:</dt>
				<dd><?php echo $user_data->birthdate?></dd>
				<?php endif;?>

			</dl>
		</div>
		<div id="social"></div>
	</div>
	<table class="separator">
		<tr>
			<td class="line"></td>
			<td class="actions">
				<?php echo HTML::anchor(
					Route::url('user', array('action' => 'edit', 'lang' => I18n::lang())),
					__('изменить&nbsp;данные'),
					array('class' => 'button_black')
				)?>
			</td>
		</tr>
	</table>
	<div id="additional_info">
		<dl>
<!--			<dt>--><?php //echo __('Rating')?><!--:</dt>-->
<!--			<dd>--><?php //echo $user->rating?><!--</dd>-->
<!---->
			<dt><?php echo __('Groups')?>:</dt>
			<dd>
			<?php
			    $count = count($profile->roles);
				$i=0;
				foreach($profile->roles as $role):
					$role_name = ($role->name != 'login') ? $role->name : 'public';
			?>
				<?php echo HTML::anchor(
					Route::url('profile', array('action' => 'group', 'group' => $role_name)),
					$role->fullname,
					array('title' => $role->description)
				)?>
				<?php echo ($count > ++$i) ? ', ' : NULL;?>
			<?php endforeach?>
			</dd>
		</dl>
		<table class="separator">
			<tr>
				<td class="line"></td>
				<td class="actions"></td>
			</tr>
		</table>
		<div id="usefull_links">
			<ul>
				<li>
					<?php echo HTML::anchor(
						Route::url('user', array('action' => 'change_password', 'lang' => I18n::lang())),
						__('Change password')
					)?>
				</li>
<!--				<li>-->
<!--					--><?php //echo HTML::anchor(
//						Route::url('user', array('action' => 'blog', 'lang' => I18n::lang())),
//						__('Personal Blog')
//					)?>
<!--				</li>-->
				<li>
					<?php echo HTML::anchor(
						Route::url('profile', array('action' => 'show', 'group' => 'public', 'lang' => I18n::lang(), 'id' => $profile->id)),
						__('Public Profile')
					)?>
				</li>
				<li>
					<?php echo HTML::anchor(
						Route::url('enrollment', array('lang' => I18n::lang(), 'action' => 'self')),
						__('Your own requests to participate in the camp')
					)?>
				</li>
			</ul>
			<?php if($profile->is_partner):?>
			<table class="separator">
				<tr>
					<td class="line"></td>
					<td class="actions"></td>
				</tr>
			</table>
			<ul>
				<li>
					<?php echo HTML::anchor(
						Route::url('auth', array('lang' => I18n::lang(), 'action' => 'registration', 'is_partner' => 'partner')),
						__('New user registration')
					)?>
				</li>
				<li>
					<?php echo HTML::anchor(
						Route::url('enrollment', array('lang' => I18n::lang(), 'action' => 'partner')),
						__('Партнёрские заявки на участие')
					)?>
				</li>

			</ul>
			<?php endif;?>
		</div>
	</div>
</div>