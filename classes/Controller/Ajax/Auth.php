<?php defined('SYSPATH') or die('No direct access allowed.');

/**
 * Template Controller Auth
 *
 * @author Sergei Gladkovskiy <smgladkovskiy@gmail.com>
 * @copyrignt
 */
class Controller_Ajax_Auth extends Controller_Ajax_Template {

	public function before()
	{
		parent::before();

		$this->_email  = Kohana::$config->load('email');
		$this->_config = Kohana::$config->load('user_auth');
	}

	/**
	 * Login routine
	 *
	 * @return void
	 */
	public function action_login()
	{
		if(Auth::instance()->logged_in())
			HTTP::redirect('');

		$errors   = NULL;
		$response = array('status' => 0, 'message' => 'request error');
		$post     = array(
			'email'    => NULL,
			'password' => NULL,
			'remember' => FALSE
		);
		$errors = NULL;

		if($this->request->method() === HTTP_Request::POST)
		{
			$post_data = Arr::extract($this->request->post(), array_keys($post));

			if(Auth::instance()->login(
				$post_data['email'],
				$post_data['password'],
				(bool) $post_data['remember']))
			{
				$response = array('status' => 1);
			}
			else
			{
				$response = array('status' => 0, 'message' => __('Неверное имя пользователя или пароль'));
			}
		}

		$this->response->body(json_encode($response));
	}


	/**
	 * Напоминание пароля
	 *
	 * @return void
	 */
	public function action_pass_remind()
	{
		$response = array('status' => 0, 'message' => 'request error');

		if($this->request->method() === HTTP_Request::POST)
		{
			$post = Validation::factory(Arr::extract($this->request->post(), array('email')))
				->rule('email', 'not_empty')
				->rule('email', 'email')
				->label('email', __('Эл. адрес'))
			;

			if( ! $post->check())
			{
				$response['message'] = $post->errors('validate');
			}
			else
			{
				$user = Jelly::query('user')
				->where('email', '=', HTML::chars($post['email']))
				->limit(1)
				->select();

				if($user->loaded())
				{
					if($this->_send_new_password($user))
						$response = array('status' => 1);
				}
				else
				{
					$response['message'] = __('Вы не были зарегистрированы на нашем сайте. ') . HTML::anchor(
						'#registrationBlock',
						__('Зарегистрируйтесь!'),
						array('data-toggle' => 'modal', 'data-backdrop' => 'static', 'data-dismiss' =>'modal')
					);
				}
			}
		}

		$this->response->body(json_encode($response));
	}

	/**
	 * Отправка письма со ссылкой для смены пароля
	 *
	 * @throws HTTP_Exception_500
	 * @param Model_User $user
	 */
	protected function _send_new_password(Model_User $user)
	{
		$hash = Jelly::factory('hash')
		->set(array(
			'object'         => 'user',
			'object_id'      => $user->id,
			'hash'           => md5(text::random()),
			'date_valid_end' => time() + 3600*24,
		));

		try
		{
			$hash->save();

			// отправка пользователю письма с ссылкой для авторизации
			$message = View::factory('frontend/template/email')
			->set('content', View::factory('frontend/content/auth/mail/password/remind')
				->set('lang', $this->request->param('lang'))
				->set('hash', $hash->hash)
			);

			Email::connect();
			Email::send(
				$user->email,
				$this->_email->email_noreply,
				__('Восстановление пароля'),
				$message,
				TRUE
			);
		}
		catch(Jelly_Validation_Exception $e)
		{
			return FALSE;
		}

		return TRUE;
	}

} // End Controller_Ajax_Auth