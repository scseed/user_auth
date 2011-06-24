<?php defined('SYSPATH') or die('No direct access allowed.');?>
<h4><?php echo __('Внимание! Все поля обязательны к заполнению!')?></h4>
<script type="text/javascript">
	var phone_mask = '<?php echo $phone_mask?>';
	var languages = ["<?php echo implode('", "', $languages)?>"];
	var countries = ["<?php echo implode('", "', $countries)?>"];
	var cities = ["<?php echo implode('", "', $cities)?>"];
</script>
<?php if($errors): foreach($errors as $error):?>
<div class="error"><?php echo $error?></div>
<?php endforeach; endif;?>
<div id="registration">
	<?php echo Form::open(Request::current())?>
	<ul>
		<li>
			<div class="form-item">
				<?php echo Form::label('nickname', __('Никнейм'), array('class' => $classes['nickname']))?>
				<?php echo Form::input('nickname', $post['nickname'], array('id' => 'nickname'))?>
			</div>
		</li>
		<li><div class="form-item">
	<?php echo Form::label('last_name', __('Фамилия'), array('class' => $classes['last_name']))?>
	<?php echo Form::input('last_name', $post['last_name'], array('id' => 'last_name'))?>
		</div><div class="form-item">
	<?php echo Form::label('first_name', __('Имя'), array('class' => $classes['first_name']))?>
	<?php echo Form::input('first_name', $post['first_name'], array('id' => 'first_name'))?>
		</div><div class="form-item">
	<?php echo Form::label('patronymic', __('Отчество'), array('class' => $classes['patronymic']))?>
	<?php echo Form::input('patronymic', $post['patronymic'], array('id' => 'patronymic'))?>
		</div></li>
		<li><div class="form-item">
	<?php echo Form::label('birthdate', __('Дата рождения'), array('class' => $classes['birthdate']))?>
	<?php echo Form::input('birthdate', $post['birthdate'], array('id' => 'birthdate', 'title' => __('birthdate_tooltip')))?>
		</div>
		<div class="form-item">
	<?php echo Form::label('language', __('Язык общения'), array('class' => $classes['language']))?>
	<?php echo Form::input('language', $post['language'], array('id' => 'language'))?>
		</div></li>
		<li><div class="form-item">
	<?php echo Form::label('phone', __('Мобильный'), array('class' => $classes['phone']))?>
	<?php echo Form::input('phone', $post['phone'], array('id' => 'phone', 'title' => __('phone_tooltip')))?>
		</div><div class="form-item">
	<?php echo Form::label('vk_id', __('ID ВКонтакте'), array('class' => $classes['vk_id']))?>
	<?php echo Form::input('vk_id', $post['vk_id'], array('id' => 'vk_id', 'title' => __('vk_tooltip')))?>
		</div><div class="form-item">
	<?php echo Form::label('fb_id', __('ID FaceBook'), array('class' => $classes['fb_id']))?>
	<?php echo Form::input('fb_id', $post['fb_id'], array('id' => 'fb_id', 'title' => __('fb_tooltip')))?>
		</div></li>
		<li><div class="form-item">
	<?php echo Form::label('team', __('Танцевальный коллектив'), array('class' => $classes['team']))?>
	<?php echo Form::input('team', $post['team'], array('id' => 'team'))?>
		</div><div class="form-item">
	<?php echo Form::label('country', __('Страна'), array('class' => $classes['country']))?>
	<?php echo Form::input('country', $post['country'], array('id' => 'country'))?>
		</div><div class="form-item">
	<?php echo Form::label('city', __('Город'), array('class' => $classes['city']))?>
	<?php echo Form::input('city', $post['city'], array('id' => 'city'))?>
		</div></li>
		<li><div class="form-item"><?php echo Form::button(NULL, __('Изменение'))?></div></li>
	</ul>
	<?php echo Form::close()?>
</div>

<h3 class="back">
	<?php echo HTML::anchor(
		Route::url('user', array('lang' => I18n::lang(), 'action' => 'cabinet')),
		'&larr; '.__('Вернуться в личный кабинет')
	)?>
</h3>