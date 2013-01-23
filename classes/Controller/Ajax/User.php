<?php defined('SYSPATH') or die('No direct access allowed.');

/**
 * Template Controller User
 *
 * @author Sergei Gladkovskiy <smgladkovskiy@gmail.com>
 * @copyrignt
 */
class Controller_Ajax_User extends Controller_Ajax_Template {

	public function action_update()
	{
		$redirect = NULL;
		$message  = NULL;

		$user        = Jelly::query('user',$this->_user->id)->select();
		$force_login = Session::instance()->get_once('auth_forced');

		$status = FALSE;
		$errors = NULL;
		$post   = array(
			'name'  => NULL,
			'value' => NULL,
			'oldvalue' => NULL,
		);

		$post_data = Arr::extract($this->request->post(), array_keys($post));

		$fields = $user->meta()->fields();
		$post_data['name'] = HTML::chars(trim($post_data['name']));

		$field_exists = FALSE;
		foreach($fields as $field)
		{
			if($field->name == $post_data['name'] OR $post_data['name'] == 'old_password' OR $post_data['name'] == 'new_password')
				$field_exists = TRUE;
		}


		$value = HTML::chars(trim($post_data['value']));
		if($field_exists)
		{
			switch($post_data['name'])
			{
				case 'phone':
					$value = preg_replace('/([-\(\)\s\+]?)/', '', $value);
					break;
				case 'last_name':
				case 'first_name':
				case 'patronymic':
				case 'old_password':
				case 'new_password':
					$value = HTML::chars(trim($value));
					break;
			}
			$value = ($value == '') ? NULL : $value;

			if($post_data['name'] == 'old_password')
			{
				if(Auth::instance()->hash($value) != $user->password)
				{
					$value = Auth::instance()->hash($value).' = '.$user->password;
					$errors[] = array('password' => __('Текущий пароль неверен!'));
				}
			}
			elseif($post_data['oldvalue'] AND $post_data['name'] == 'new_password')
			{
				if(Auth::instance()->hash($post_data['oldvalue']) != $user->password)
				{
					$errors[] = array('password' => __('Текущий пароль неверен!'));
				}
				if(Auth::instance()->hash($value) == $user->password)
				{
					$errors[] = array('password' => __('Новый пароль такой же как старый. Изменений не произведено'));
				}
				else
				{
					$user->password = $value;
				}
			}
			elseif($force_login AND $post_data['name'] == 'new_password')
			{
				$user->password = $value;
				$redirect = Route::url('default', array('lang' => I18n::$lang));
			}
			else
			{
				$user->$post_data['name'] = $value;
			}
		}
		else
		{
			$errors[] = array('name' => __('Поля не существует!'));
		}

		if( ! $value)
		{
			$errors = array();
			$errors[] = array('value' => __('Поле оставлено пустым! Необходимо его заполнить!'));
		}

		if($user->changed())
		{
			try
			{
				$user->save();
				$message  = array(
					'head'   => __('Успех!'),
					'text'   => __('Пароль успешно изменён'),
					'button' => __('Отлично!'),
				);
				$redirect = ($redirect)
					? $redirect
					: Route::url('user', array('lang' => I18n::$lang, 'action' => 'change_pass'));
			}
			catch(Jelly_Validation_Exception $e)
			{
				$errors[] = $e->errors('validate');
			}
		}

		if(!$errors)
			$status = TRUE;

		$this->response->body(json_encode(array(
			'status'   => $status,
			'errors'   => $errors,
			'message'  => $message,
			'redirect' => $redirect,
			'value'    => $value,
		)));
	}

} // End Controller_User