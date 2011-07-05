<?php defined('SYSPATH') or die('No direct access allowed.');

/**
 * Template Controller Core Auth
 *
 * @package Auth
 * @author Sergei Gladkovskiy <smgladkovskiy@gmail.com>
 */
abstract class Controller_Core_Auth extends Controller_Template {

	protected $_email = NULL;

	public function before()
	{
		parent::before();

		$this->_email = Kohana::config('email');

//		StaticCss::instance()->add('/css/auth.css');
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
		if(Auth::instance()->logged_in())
			$this->request->redirect('');

		$post = array(
			'email' => NULL,
			'password' => NULL
		);
		$errors = NULL;

		if($this->request->method() === HTTP_Request::POST)
		{
			$post = Arr::extract($this->request->post(), array('email', 'password', 'remember'));

			$post['remember'] = TRUE;

			if(Auth::instance()->login(
				$post['email'],
				$post['password'],
				! isset($post['remember']) ? TRUE : FALSE))
			{
				$this->request->redirect(Request::initial()->referrer());
			}
			else
			{
				$errors = 'Неверное имя пользователя или пароль';
			}
		}

		$this->page_title = __('Авторизация');
		$this->template->content = View::factory('frontend/form/auth/login')
			->bind('userdata', $post)
			->bind('is_ajax', $this->_ajax)
			->set('errors', $errors)
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
			throw new HTTP_Exception_404();
		}
		else
		{
			// присваивание login роли, так как старые записи могут не иметь роли для входа на сайт
			if( ! $user->has_role('login'))
			{
				$user->add('roles', Jelly::query('role')->where('name', '=', 'login')->limit(1)->execute());
				$user->save();
			}

			// генерация нового пароля, чтобы вход был успешным и обновление учётки
			$new_password = Text::random();
			$user = Jelly::factory('user', $user->id)
				 ->update_user(
					array(
						'password'         => $new_password,
						'password_confirm' => $new_password,
					),
					array(
						'password',
						'password_confirm',
					)
				);

			// вход с новым паролем
			Auth::instance()->login($user->email, $new_password, TRUE);

			// удаление хэша, дабы устранить повторное использование
			$hash->delete();

			// редирект на страницу смены пароля
			$this->request->redirect(Route::url('user', array('action' => 'change_password', 'lang' => I18n::lang())));
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
		$partner_registration = ($this->request->param('is_partner') == 'partner');

		if($partner_registration)
		{
			$partner = Jelly::query('user', Auth::instance()->get_user()->id)->select();

			if( ! $partner OR ! $partner->loaded() OR !$partner->is_partner)
			{
				throw new HTTP_Exception_404();
			}
		}

		if(Auth::instance()->logged_in() AND ! $partner_registration)
			$this->request->redirect(Route::url('user', array('action' => 'cabinet', 'lang' => I18n::lang())));

		StaticJs::instance()
			->add('/js/jquery.maskedinput.min.js')
			->add('/js/jquery.tooltips.min.js')
			->add('/js/form.js')
			;
//		StaticCss::instance()
//			->add('/css/auth.css')
//			;

		$lang       = $this->request->param('lang');
		$_languages = Jelly::query('language')->select();
		$_countries = Jelly::query('country')->select();
		$_cities = Jelly::query('city')->select();
		foreach($_languages as $item)
		{
			$languages[] = $item->name;
			$languages[$item->name] = $item->id;
		}
		foreach($_countries as $item)
		{
			$countries[] = $item->name;
			$countries[$item->name] = $item->id;
		}
		foreach($_cities as $item)
		{
			$cities[] = $item->name;
			$cities[$item->name] = $item->id;
		}

		$errors = array();
		$fields = $classes = array(
			'last_name' => NULL,
			'first_name' => NULL,
			'patronymic' => NULL,
			'team' => NULL,
			'country' => NULL,
			'city' => NULL,
			'language' => NULL,
			'birthdate' => NULL,
			'phone' => NULL,
			'vk_id' => __('Отсутствует'),
			'fb_id' => __('Отсутствует'),
			'email' => NULL,
			'user_data' => NULL,
			'user' => NULL,
		);

		if($this->request->method() === HTTP_Request::POST)
		{
			$post = Arr::extract($this->request->post(), array_keys($fields), NULL);
			$_user_info = array();
			$_user_data = array();
			foreach($post as $name => $value)
			{
				switch($name)
				{
					case 'last_name':
					case 'first_name':
					case 'patronymic':
					case 'team':
					case 'country':
					case 'city':
					case 'language':
					case 'birthdate':
					case 'phone':
					case 'vk_id':
					case 'fb_id':
						$_user_info[$name] = ($value == 'Отсутствует' OR $value == 'None') ? NULL : $value;
						break;
					case 'email':
					case 'user';
					case 'user_data';
						$_user_data[$name] = $value;
						break;
				}
			}

			if($partner_registration)
			{
				$_user_data['partner'] = $partner->id;
			}


			$country = Jelly::query('country')->where('name', '=', HTML::chars(trim($post['country'])))->limit(1)->select();
			if($post['country'] AND ! $country->loaded())
			{
				$country = Jelly::factory('country');
			    $country->name = HTML::chars(trim($post['country']));
			    try
			    {
				    $country->save();
			    }
			    catch(Jelly_Validation_Exception $e)
			    {
				    $error = $e->errors('common_validation');
				    $errors['country'] = $error['name'];
			    }
			}

			$city = Jelly::query('city')->where('name', '=', HTML::chars(trim($post['city'])))->limit(1)->select();
			if($post['city'] AND ! $city->loaded())
			{
				$city = Jelly::factory('city');
			    $city->name = HTML::chars(trim($post['city']));
			    try
			    {
				    $city->save();
			    }
			    catch(Jelly_Validation_Exception $e)
			    {
				    $error = $e->errors('common_validation');
				    $errors['city'] = $error['name'];
			    }
			}

			$language = Jelly::query('language')->where('name', '=', HTML::chars(trim($post['language'])))->limit(1)->select();
			if($post['language'] AND ! $language->loaded())
			{
				$language = Jelly::factory('language');
			    $language->name = HTML::chars(trim($post['language']));
			    try
			    {
				    $language->save();
			    }
			    catch(Jelly_Validation_Exception $e)
			    {
				    $error = $e->errors('common_validation');
				    $errors['language'] = $error['name'];
			    }
			}

			if( ! $errors)
			{
				$_user_info['country'] = $country->id;
				$_user_info['city'] = $city->id;
				$_user_info['language'] = $language->id;

				if( ! $post['user_data'])
				{
					$user_data = Jelly::factory('user_data');
					$user_data->set($_user_info);
					try
					{
						$user_data->save();
						$post['user_data'] = $_user_data['user_data'] = $user_data->id;
						$_user_data['fullname'] = $user_data->last_name .' '. $user_data->first_name;
					}
					catch(Jelly_Validation_Exception $e)
					{
						$errors += $e->errors('common_validation');
					}
				}
			}

			if( ! $errors)
			{
				if( ! $post['user'])
				{
					$user = Jelly::factory('user');
					$_user_data['password'] = $_user_data['password_confirm'] = text::random(NULL, 8);
					$user->set($_user_data);
					try
					{
						$user->save();
					}
					catch(Jelly_Validation_Exception $e)
					{
						$errors += $e->errors('common_validation');
					}
				}
			    else
			    {
				    $user = Jelly::query('user', (int) $post['user'])->select();
			    }

			}

		    if( ! $errors)
			{
				$this->_send_confirmation($user);
			}

		    $fields = Arr::overwrite($fields, $post);
		}

		if($errors)
		{
			foreach($errors as $name => $text)
			{
				$classes[$name] = 'error';
			}
		}

		$phone_mask = ($lang == 'ru') ? '+9 (999) 999-99-99'	: '+99999999999';
		$this->page_title = ($partner_registration) ? __('Регистрация УЗЧП') : __('Регистрация');
		$this->template->content = View::factory('frontend/form/auth/registration')
			->bind('post', $fields)
			->bind('cards', $cards)
			->bind('languages', $languages)
			->bind('countries', $countries)
			->bind('cities', $cities)
			->bind('classes', $classes)
			->bind('errors', $errors)
			->bind('is_partner', $partner->is_partner)
			->bind('phone_mask', $phone_mask);
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
				$errors = $post->errors('common_validation');
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

		$this->page_title = __('Напоминание пароля');
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
				'Подтверждения регистрации | danceville.ru',
				$message,
				TRUE
			);

			if($user->partner->id)
			{
				// Сообщение в CRM
				$message_crm = View::factory('frontend/content/email/crm/registration_by_partner')
					->bind('user', $user)
					->bind('partner', $user->partner)
				;

				Email::send(
					$this->_email->email_registration,
					$this->_email->email_noreply,
					'Регистрация через партнёра',
					$message_crm,
					FALSE
				);
			}

			$this->request->redirect(
				Route::url('page', array(
					'lang' => $this->request->param('lang'),
					'page_alias' => 'registration',
					'subpages' => ($user->partner->id) ? 'partner/success' : 'success',
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
				'Напоминание пароля | danceville.ru',
				$message,
				TRUE
			);

			$this->request->redirect(
				Route::url('messages', array(
					'lang'   => $this->request->param('lang'),
					'type'   => 'password_remind',
					'status' => 'send',
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

		// Сообщение в CRM
		$message_crm = View::factory('frontend/content/email/crm/auth_confirmation')
			->bind('user', $user);

		Email::connect();

		Email::send(
			$user->email,
			$this->_email->email_noreply,
			'Регистрация прошла успешно | DANCEVILLE.ru',
			$message_user,
			TRUE
		);

		Email::send(
			$this->_email->email_registration,
			$this->_email->email_noreply,
			'Регистрация',
			$message_crm,
			FALSE
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

					'lang' => $this->request->param('lang'),
					'action' => 'fail',
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
			Route::url('page', array(
				'lang' => I18n::lang(),
				'page_alias' => 'registration',
				'subpages' => 'confirmed',
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

	/**
	 * Session end fail message
	 *
	 * @return void
	 */
	public function action_fail()
	{
		$this->page_title = __('Время сессии истекло!');
		$this->template->content = View::factory('frontend/content/auth/fail');
	}

	/**
	 * Registration success message
	 *
	 * @return void
	 */
	public function action_success()
	{
		$this->page_title = __('Регистрация подтверждена!');
		$this->template->content = View::factory('frontend/content/auth/success');
	}

} // End Controller_Core_Auth