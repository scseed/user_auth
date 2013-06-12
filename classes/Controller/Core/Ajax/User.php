<?php defined('SYSPATH') or die('No direct access allowed.');

/**
 * Template Controller User
 *
 * @author Sergei Gladkovskiy <smgladkovskiy@gmail.com>
 * @copyrignt
 */
class Controller_Core_Ajax_User extends Controller_Ajax_Template {

	protected $_auth_required = TRUE;

	public function action_update()
	{
		$redirect = NULL;
		$message  = NULL;

		$user         = Jelly::query('user',$this->_user->id)->select();
		$force_login  = Session::instance()->get_once('auth_forced');
		$registration = Session::instance()->get_once('registration');

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
					if($value)
					{
						$user->password = $value;
						$message  = array(
							'head'   => __('Успех!'),
							'text'   => __('Пароль успешно изменён'),
							'button' => __('Отлично!'),
						);
					}
				}
			}
			elseif($force_login AND $post_data['name'] == 'new_password')
			{
				$user->password = $value;

				$message  = array(
					'head'   => __('Успех!'),
					'text'   => __('Пароль успешно изменён'),
					'button' => __('Отлично!'),
				);
			}
			else
			{
				$user->$post_data['name'] = $value;
			}

			$redirect = ($registration)
				? Route::url('user',    array('lang' => I18n::$lang, 'action' => 'mydata'))
				: Route::url('default', array('lang' => I18n::$lang));
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
			}
			catch(Jelly_Validation_Exception $e)
			{
				$errors[] = $e->errors('validate');
			}
		}

		if(!$errors)
			$status = TRUE;

		$this->response_body = array(
			'status'   => $status,
			'errors'   => $errors,
			'message'  => $message,
			'redirect' => $redirect,
			'value'    => $value,
		);
	}

	public function action_edit()
	{
		$post = array(
			'id'                 => NULL,
			'last_name'          => NULL,
			'first_name'         => NULL,
			'patronymic'         => NULL,
			'phone'              => NULL,
			'email'              => NULL,
			'is_active'          => FALSE,
			'notification_email' => FALSE,
			'notification_sms'   => FALSE,
			'companies'          => array(),
			'projects'           => array(),
			'roles'              => array(),
		);

		if($this->request->method() === Request::POST)
		{
			$post_data = Arr::extract($this->request->post(), array_keys($post));

			$user_id = (int) $post_data['id'];

			if( ! $user_id)
				throw new HTTP_Exception_404;

			//@todo: проверка на возможность править данные. Либо это сам пользователь, либо админ, либо супер пользователь + соответствующий проект.

			$user = Jelly::query('user', $user_id)->select();

			if( ! $user->loaded())
				throw new HTTP_Exception_404;

			foreach($post_data as $id => $val)
			{
				switch($id)
				{
					case 'phone':
						$post_data[$id] =  preg_replace('/([-\(\)\s\+]?)/', '', $val);
						break;
					case 'is_active':
					case 'notification_email':
					case 'notification_sms':
						$post_data[$id] = (isset($_POST[$id])) ? (bool) $val : $user->{$id};
						break;
					case 'companies':
					case 'projects':
					case 'roles':
						if(is_array($val))
						{
							foreach($val as $unit_id => $unit)
								$post_data[$id][$unit_id] = (int) $unit;
						}

						if(!isset($_POST[$id]) AND $id != 'projects')
							$post_data[$id] = $user->{$id}->as_array('id', 'id');

						break;
					default:
						$post_data[$id] = HTML::chars(trim($val));
						break;
				}
			}

			$user->set($post_data);

			try
			{
				$user->save();
				$this->response_body['status']   = TRUE;
				$this->response_body['json']     = $this->_render_json;
				$this->response_body['message']  = __('Данные пользователя изменены');
				$this->response_body['redirect'] = Route::url('user', array('lang' => I18n::$lang, 'action' => 'list'));
			}
			catch(Jelly_Validation_Exception $e)
			{
				$this->response_body['errors'] = $e->errors('validate');
			}
		}
	}
	public function action_activity()
	{
		$post = array(
			'id'                 => NULL,
		);

		if($this->request->method() === Request::POST)
		{
			$post_data = Arr::extract($this->request->post(), array_keys($post));

			$user_id = (int) $post_data['id'];

			if( ! $user_id)
				throw new HTTP_Exception_404;

			//@todo: проверка на возможность править данные. Либо это сам пользователь, либо админ, либо супер пользователь + соответствующий проект.

			$user = Jelly::query('user', $user_id)->select();

			if( ! $user->loaded())
				throw new HTTP_Exception_404;

			$user->is_active = (bool) ! $user->is_active;

			try
			{
				$user->save();
				$this->response_body['status']   = TRUE;
				$this->response_body['json']     = $this->_render_json;
				$this->response_body['message']  = __('Данные пользователя изменены');
				$this->response_body['redirect'] = Route::url('user', array('lang' => I18n::$lang, 'action' => 'list'));
			}
			catch(Jelly_Validation_Exception $e)
			{
				$this->response_body['errors'] = $e->errors('validate');
			}
		}
	}

	public function action_edit_body()
	{
		$user_id = (int) $this->request->param('id');

		if(!$user_id)
			throw new HTTP_Exception_404;

		$user = Jelly::query('user', $user_id)->select();

		if( ! $user->loaded())
			throw new HTTP_Exception_404;

		$this->_render_json  = FALSE;
		$this->response_body = View::factory('frontend/modal/user/update/body')->bind('user', $user);
	}

	public function action_activity_body()
	{
		$user_id = (int) $this->request->param('id');

		if(!$user_id)
			throw new HTTP_Exception_404;

		$user = Jelly::query('user', $user_id)->select();

		if( ! $user->loaded())
			throw new HTTP_Exception_404;

		$this->_render_json  = FALSE;
		$this->response_body = View::factory('frontend/modal/user/update/activityBody')->bind('user', $user);
	}

} // End Controller_User