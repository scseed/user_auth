<?php defined('SYSPATH') or die('No direct access allowed.');

/**
 * Template Controller Auth
 *
 * @author Sergei Gladkovskiy <smgladkovskiy@gmail.com>
 * @copyrignt
 */
class Controller_Core_Ajax_Auth extends Controller_Ajax_Template {

	public function before()
	{
		$this->_email  = Kohana::$config->load('email');
		$this->_config = Kohana::$config->load('user_auth');

		if($this->request->action() == 'register' AND ! $this->_config->open_registration)
		{
			$this->_auth_required = TRUE;
		}

		parent::before();


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
		$post     = array(
			'email'    => NULL,
			'password' => NULL,
			'remember' => FALSE
		);

		if($this->request->method() === HTTP_Request::POST)
		{
			$post_data = Arr::extract($this->request->post(), array_keys($post));

			if(Auth::instance()->login(
				$post_data['email'],
				$post_data['password'],
				(bool) $post_data['remember']))
			{
				$this->response_body = array(
					'status' => 1,
					'redirect' => (Session::instance()->get('url'))
						? Session::instance()->get('url')
						: Route::url('default', array('lang' => I18n::$lang))
				);
			}
			else
			{
				$this->response_body = array('status' => 0, 'message' => __('Неверное имя пользователя или пароль'));
			}
		}
	}


	/**
	 * Напоминание пароля
	 *
	 * @return void
	 */
	public function action_remember()
	{
		if($this->request->method() === HTTP_Request::POST)
		{
			$post = Validation::factory(Arr::extract($this->request->post(), array('email')))
				->rule('email', 'not_empty')
				->rule('email', 'email')
				->label('email', __('Эл. адрес'))
			;

			if( ! $post->check())
			{
				$this->response_body['message'] = $post->errors('validate');
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
						$this->response_body = array('status' => 1);
				}
				else
				{
					$this->response_body['message'] = __('Email адрес не зарегистрирован!');
				}
			}
		}
	}

	public function action_register()
	{
		$errors   = NULL;

		if($this->request->method() === HTTP_Request::POST)
		{
			$post_data = Arr::extract($this->request->post(), array('email', 'companies_projects', 'role'));

			$post = Validation::factory(Arr::extract($this->request->post(), array_keys($post_data)))
				->rule('email', 'not_empty')
				->rule('role', 'not_empty')
				->rule('role', 'range', array(':value', 0, 100))
				->rule('email', 'email')
				->label('email', __('Эл. адрес'))
				->label('role', __('Роль пользователя'))
			;

			$companies = array();
			$projects  = array();
			if($post_data['companies_projects'])
			{
				foreach($post_data['companies_projects'] as $company_project)
				{
					$company_project_arr = explode('-', $company_project);
					$company = (int) $company_project_arr[0];
					$project = (int) $company_project_arr[1];

					if($company)
						$companies[$company] = $company;

					if($project)
						$projects[$company] = $project;
				}
			}

			$user_data = array(
				'role'      => (int) $post_data['role'],
				'companies' => $companies,
				'projects'  => $projects,
			);

			$emails = Jelly::query('user')->where('email', '=', $post_data['email'])->count();

			if( ! $post->check() OR $emails)
			{
				$this->response_body['message'] = ($post->errors('validate'))
					? $post->errors('validate')
					: __('Пользователь с таким email существует!')
				;
			}
			else
			{
				$this->_send_confirmation($post_data['email'], $user_data);
				$this->response_body = array('status' => 1, 'redirect' => $this->request->referrer());
			}
		}
	}

	/**
	 * Отправка письма с ссылкой для подтверждения аккаунта
	 *
	 * @throws HTTP_Exception_500
	 * @return void
	 */
	protected function _send_confirmation($email, $params)
	{
		$hash = Jelly::factory('hash');
		$hash->set(array(
			'object' => 'new_user',
			'object_id' => $email,
			'object_params' => $params,
			'hash' => md5(Text::random()),
			'date_valid_end' => time() + 3600*24,
		));

		try
		{
			$hash->save();

			// отправка пользователю письма с ссылкой для подтверждения аккаунта
			$message = View::factory('frontend/content/auth/mail/confirm')
				->set('lang', $this->request->param('lang'))
				->set('hash', $hash->hash);


			Email::factory(
				__('Подтверждения регистрации | :site_name', array(':site_name' => $this->_config->site->site_name)),
				$message,
				'text/html'
			)
				->to($email)
				->from($this->_email->email_noreply)
				->bcc('smgladkovskiy@gmail.com')
				->send()
			;

			$this->response_body['status'] = 1;
		}
		catch(Jelly_Validation_Exception $he)
		{
			throw new HTTP_Exception_500();
		}
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
			'hash'           => md5(Text::random()),
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

			Email::factory(__('Восстановление пароля'), $message, 'text/html')
				->from($this->_email->email_noreply)
				->to($user->email)
				->bcc('smgladkovskiy@gmail.com')
				->send()
			;
		}
		catch(Jelly_Validation_Exception $e)
		{
			return FALSE;
		}

		return TRUE;
	}

} // End Controller_Ajax_Auth