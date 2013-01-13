<?php defined('SYSPATH') or die('No direct access allowed.');

/**
 * Template Controller Core Auth
 *
 * @package Auth
 * @author Sergei Gladkovskiy <smgladkovskiy@gmail.com>
 */
abstract class Controller_Core_Auth extends Controller_Template {

	protected $_email         = NULL;
	protected $_config        = NULL;
	protected $_auth_required = FALSE;

	public function before()
	{
		parent::before();

		$this->_email  = Kohana::$config->load('email');
		$this->_config = Kohana::$config->load('user_auth');
	}

	/**
	 * Old path redirect
	 *
	 * @return void
	 */
	public function action_user()
	{
		$this->request->redirect(Route::url('user', array('action' => 'cabinet', 'lang' => I18n::lang())));
	}

	/**
	 * Login routine
	 *
	 * @return void
	 */
	public function action_login()
	{
		$registration = $this->_config->open_registration;
		if(Auth::instance()->logged_in())
			$this->request->redirect('');

		$post = array(
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
				$this->request->redirect(Request::initial()->referrer());
			}
			else
			{
				$errors = 'Неверное имя пользователя или пароль';
			}

			$post = Arr::merge($post, $post_data);
		}

		$this->template->title      = __('Авторизация');
		$this->template->page_title = __('Авторизация');
		$this->template->content    = View::factory('frontend/form/auth/login')
			->bind('registration', $registration)
			->bind('post', $post)
			->bind('errors', $errors)
			->set('can_remember', $this->_config->remember_functional)
		;
	}

	/**
	 * Вход с использованием авторизационного хэша
	 *
	 * @throws HTTP_Exception_404
	 * @return void
	 */
	public function action_hash_login()
	{
		$hash = HTML::chars($this->request->param('hash'));

		if( ! $hash)
			throw new HTTP_Exception_404();

		$hash = Jelly::query('hash')
			->select_column('object_id', 'id')
			->where('hash', '=', $hash)
			->where('date_valid_end', '>=', time())
			->limit(1);

		// выбор пользователя по хэшу
		$user = Jelly::query('user', $hash)->select();

		if( ! $user->loaded())
		{
			$this->request->redirect(
				Route::url('auth', array(
					'action' => 'message',
					'hash' => 'hash_expired',
				))
			);
		}
		else
		{
//			// присваивание login роли, так как старые записи могут не иметь роли для входа на сайт
//			if( ! $user->has_role('login'))
//			{
//				$user->add('roles', Jelly::query('role')->where('name', '=', 'login')->limit(1)->execute());
//				$user->save();
//			}

			// Форсированный вход
			Auth::instance()->force_login($user);

			// удаление хэша, дабы устранить повторное использование
			$hash->delete();

			// редирект на страницу смены пароля
			$this->request->redirect(
				Route::url(
					'user',
					array(
						'action' => 'change_password',
					)
				)
			);
		}
	}

	/**
	 * User Logout
	 *
	 * @return void
	 */
	public function action_logout()
	{
		if(Auth::instance()->logout())
			$this->request->redirect(Request::initial()->referrer());
	}

	/**
	 * User registration
	 *
	 * @throws HTTP_Exception_404
	 * @return void
	 */
	public function action_registration()
	{
		$registration = $this->_config->open_registration;

		if( ! $registration)
			$this->request->redirect('');

		if(Auth::instance()->logged_in())
			$this->request->redirect(Route::url('user', array('action' => 'cabinet', 'lang' => I18n::lang())));

		$errors    = array();
		$post_user = array();
		$post_user_data = array();
		$post      = array(
			'id'        => NULL,
			'user'      => array(
				'email'     => NULL,
			),
			'user_data' => array(
				'last_name'  => NULL,
				'first_name' => NULL,
				'patronymic' => NULL,
			),
		);

		if($this->request->method() === HTTP_Request::POST)
		{
			$session_id = Session::instance()->id();
			$post_data = Arr::extract($this->request->post(), array_keys($post), NULL);
			$post_user = Arr::extract(Arr::get($post_data,'user'), array_keys($post['user']));
			$post_user_data = Arr::extract(Arr::get($post_data,'user_data'), array_keys($post['user_data']));

			if($post_data['id'])
			{
				$user = Jelly::query('user')->where('user_session', '=', md5($post_data['id']))->limit(1)->select();
			}
			else
			{
				$user = Jelly::factory('user');
			}

//			exit(Debug::vars($user) . View::factory('profiler/stats'));

			$user->set($post_user);
			$user->user_session = md5($session_id);
			try
			{
				$user->save();
			}
			catch(Jelly_Validation_Exception $e)
			{
				$errors['user'] = $e->errors('validate');
			}

			$post['id'] = $session_id;

		    if( ! $errors)
			{
				$user_data = Jelly::factory('user_data');
				$user_data->set($post_user_data);
				$user_data->user = $user;
				try
				{
					$user_data->save();
				}
				catch(Jelly_Validation_Exception $e)
				{
					$errors['user_data'] = $e->errors('validate');
				}
			}

			if( ! $errors)
			{
				$this->_send_confirmation($user);
			}
		}

		$post['user'] = Arr::merge($post['user'], $post_user);
		$post['user_data'] = Arr::merge($post['user_data'], $post_user_data);

		$this->page_title = __('Регистрация');
		$this->template->content = View::factory('frontend/form/auth/registration')
			->bind('post', $post)
			->bind('errors', $errors)
			;
	}

	/**
	 * Напоминание пароля
	 *
	 * @return void
	 */
	public function action_pass_remind()
	{
		$post = array('email' => NULL);

		if($this->request->method() === HTTP_Request::POST)
		{
			$post = Validation::factory(Arr::extract($this->request->post(), array('email')))
				->rule('email', 'not_empty')
				->rule('email', 'email')
				->label('email', __('Эл. адрес'))
			;

			if( ! $post->check())
			{
				$errors = $post->errors('validate');
			}
			else
			{
				$user = Jelly::query('user')
					->where('email', '=', HTML::chars($post['email']))
					->limit(1)
					->select();

				if($user->loaded())
				{
					$this->_send_new_password($user);
				}
				else
				{
					$errors[] = __('Вы не были зарегистрированы на нашем сайте. ') . HTML::anchor(
						Route::url('auth', array('action' => 'registration', 'lang' => I18n::lang())),
						__('Зарегистрируйтесь!'),
						array('class' => 'button')
					);
				}
			}
		}

		$this->template->title = __('Напоминание пароля');
		$this->template->content = View::factory('frontend/form/auth/password/remind')
			->bind('post', $post)
			->bind('errors', $errors)
		;
	}

	/**
	 * Отправка письма с ссылкой для подтверждения аккаунта
	 *
	 * @throws HTTP_Exception_500
	 * @param Jelly_Model $user
	 * @return void
	 */
	protected function _send_confirmation(Jelly_Model $user)
	{
		$hash = Jelly::factory('hash');
		$hash->set(array(
			'object' => 'user',
			'object_id' => $user->id,
			'hash' => md5(text::random()),
			'date_valid_end' => time() + 3600*24,
		));

		try
		{
			$hash->save();

			// отправка пользователю письма с ссылкой для подтверждения аккаунта
			$message = View::factory('frontend/content/auth/mail/confirm')
				->set('lang', $this->request->param('lang'))
				->set('hash', $hash->hash);


			Email::connect();
			Email::send(
				$user->email,
				$this->_email->email_noreply,
				__('Подтверждения регистрации | :site_name', array(':site_name' => $this->_config->site->site_name)),
				$message,
				TRUE
			);

			$user->user_session = NULL;
			$user->save();

			$this->request->redirect(
				Route::url('auth', array(
					'action' => 'message',
					'hash' => 'reg_success',
				))
			);
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
	 * @return void
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
				'Напоминание пароля',
				$message,
				TRUE
			);

			$this->request->redirect(
				Route::url('auth', array(
					'action' => 'message',
					'hash' => 'password_remind_send',
				))
			);
		}
		catch(Jelly_Validation_Exception $e)
		{
			throw new HTTP_Exception_500('Ошибка сохранения хэша в напоминании пароля');
		}
	}

	/**
	 * Отправка писем пользователю о успешном подтверждении регистрации и в CRM его данные
	 *
	 * @todo переделать email на xml-rpc запрос непосредственно в CRM
	 * @param Model_User $user
	 * @param string     $password
	 * @return void
	 */
	public function _send_registration_emails(Model_User $user, $password)
	{
		// Сообщение пользователю
		$message_user = View::factory('frontend/template/email')
			->set('content', View::factory('frontend/content/auth/mail/credentials')
				->bind('password', $password)
				->bind('user', $user)
			);

		Email::connect();
		Email::send(
			$user->email,
			$this->_email->email_noreply,
			'Регистрация прошла успешно',
			$message_user,
			TRUE
		);

	}

	/**
	 * Confirmation to password remind (change) request
	 *
	 * @return void
	 */
	public function action_confirmation()
	{
		$hash = Jelly::query('hash')
			->where('hash', '=', HTML::chars($this->request->param('hash')))
			->limit(1)
			->select();

	    if( ! $hash->loaded() OR $hash->date_valid_end < time())
	    {
			$this->request->redirect(
				Route::url('auth', array(
					'action' => 'message',
					'hash' => 'conf_fail',
				))
			);
	    }

		$password = Text::random();

		$user = Jelly::query($hash->object, $hash->object_id)->select();
		$user->is_active = TRUE;
		$user->password = $password;
		$user->add('roles', Jelly::query('role')->where('name', '=', 'login')->limit(1)->execute());
		$user->save();

		$this->_send_registration_emails($user, $password);

		// Залогинивание пользователя
		if(Auth::instance()->login($user->email, $password, TRUE))
		{
			$hash->delete();
		}

		$this->request->redirect(
			Route::url('auth', array(
				'action' => 'message',
				'hash' => 'reg_confirmed',
			))
		);
	}

	public function action_update_hash()
	{
		$_hash = $this->request->param('hash');
		if($_hash != NULL)
		{
			$hash = Jelly::query('hash')
			->where('hash', '=', HTML::chars($_hash))
			->limit(1)
			->select();

			if($hash->loaded())
			{
				$new_hash = Jelly::factory('hash');
				$new_hash->set(array(
					'object' => $hash->object,
					'object_id' => $hash->object_id,
					'hash' => md5(text::random()),
					'date_valid_end' => time() + 3600*24,
				));
				$new_hash->save();

				$user = Jelly::query($hash->object, $hash->object_id)->select();

				// отправка пользователю письма с ссылкой для подтверждения аккаунта
				$message = View::factory('frontend/content/auth/mail/confirm')
					->set('lang', $this->request->param('lang'))
					->set('hash', $new_hash->hash);
				Email::connect();
				Email::send(
					$user->email,
					$this->_email->email_noreply,
					'Обновление сессии подтверждения регистрации | danceville.com',
					$message,
					TRUE
				);

				$hash->delete();
				$this->request->redirect(
					Route::url('auth', array(
						'lang' => $this->request->param('lang'),
						'action' => 'send',
					))
				);
			}
			else
			{
				throw new HTTP_Exception_404();
			}
		}
	    else
	    {
		    throw new HTTP_Exception_404();
	    }
	}

	public function action_message()
	{
		$message = $this->request->param('hash');

		$this->page_title = __($message);
		$this->template->content = View::factory('frontend/content/auth/'.$message);
	}

} // End Controller_Core_Auth