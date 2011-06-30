<?php defined('SYSPATH') or die('No direct access allowed.');

/**
 * Template Controller Core User
 *
 * @package User
 * @author Sergei Gladkovskiy <smgladkovskiy@gmail.com>
 */
abstract class Controller_Core_User extends Controller_Template {

	protected $_email = NULL;

	public function before()
	{
		parent::before();

		if($this->request->action() == 'login_panel')
		{
			if(Auth::instance()->logged_in() AND ! $this->_ajax)
			{
				$this->request->redirect(Route::url('default', array('controller' => '', 'action' => '', 'lang' => I18n::lang())));
			}
			elseif(Auth::instance()->logged_in() AND $this->_ajax)
			{
				$this->request->action('panel');
			}
		}

		$this->_email = Kohana::config('email');

//		StaticCss::instance()
//			->add('/css/auth.css')
//			;
	}

	/**
	 * User login panel
	 *
	 * @return void
	 */
	public function action_login_panel()
	{
		$post = array(
			'email'    => NULL,
			'password' => NULL
		);
		$errors = NULL;
		if($this->request->method() === HTTP_Request::POST)
		{
			$post = Arr::extract($this->request()->post(), array('email', 'password', 'remember'));
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
				$errors = array('common' => 'Неверное имя пользователя или пароль');
			}
		}

		$this->template->title = __('Вход');
		$this->template->content = View::factory('frontend/form/auth/login')
			->bind('userdata', $post)
			->bind('is_ajax', $this->_ajax)
			->set('errors', $errors);
	}

	/**
	 * User panel
	 *
	 * @return void
	 */
	public function action_panel()
	{
		$user = Jelly::query('user', Auth::instance()->get_user()->id)->select();

		$this->template->content = View::factory('frontend/content/user/panel')
			->bind('user', $user)
		;
	}

	/**
	 * User private cabinet
	 *
	 * @return void
	 */
	public function action_cabinet()
	{
        if( ! Auth::instance()->logged_in())
            $this->request->redirect(Route::url('default', array('lang' => I18n::lang())));

		$user = Jelly::query('user', Auth::instance()->get_user()->id)->select();
		if( ! $user OR ! $user->loaded())
		{
			$this->request->redirect(Route::url('default', array('lang' => I18n::lang())));
		}

		$user_data = $user->user_data;
		$avatar = ($user->has_avatar)
			? 'media/images/avatars/'.$user->id.'/avatar.jpg'
			: 'i/stubs/avatar_comment.png';

		StaticCss::instance()->add('css/imgareaselect-animated.css');
		StaticJs::instance()
			->add('js/jquery.imgareaselect.min.js')
			->add('js/jquery.ocupload-1.1.2.js')
//			->add('js/jquery.ocupload-1.1.2.packed.js')
			->add('js/user_cabinet.js')
		;
		$this->page_title = __('Личный кабинет');
		$this->template->content = View::factory('frontend/content/user/cabinet')
			->bind('profile', $user)
			->bind('user_data', $user_data)
			->bind('users', $users)
			->bind('avatar', $avatar)
			->bind('enrollments', $enrollments)
		;
	}

	public function action_delete_avatar()
	{
		Jelly::query('user', $this->_user->id)->set(array('has_avatar' => FALSE))->update();

		$avatar_path = DOCROOT.'media'.DIRECTORY_SEPARATOR.
		               'images'.DIRECTORY_SEPARATOR.
		               'avatars'.DIRECTORY_SEPARATOR.
		               $this->_user->id.DIRECTORY_SEPARATOR;
		$avatar = $avatar_path.'avatar.jpg';
		$thumb  = $avatar_path.'thumb.jpg';

		if(file_exists($avatar))
			unlink($avatar);

		if(file_exists($thumb))
			unlink($thumb);

		$this->request->redirect($this->request->referrer());
	}

	public function action_profile_request()
	{

	}

	public function action_profile_confirm()
	{

	}

	public function action_profile()
	{

	}

	/**
	 * User password changing
	 *
	 * @return void
	 */
	public function action_change_password()
	{
		$user = Auth::instance()->get_user();

		if( ! $user OR ! $user->loaded())
		{
			$this->request->redirect(Route::url('default', array('lang' => I18n::lang())));
		}

		$errors = NULL;
		$message = NULL;

		if($this->request->method() === HTTP_Request::POST)
		{
			if($this->request->post('password') == '')
			{
				$errors['password'] = __('Необходимо ввести новый пароль');
			}
			else
			{
				try
				{
					$user = Jelly::factory('user', $user->id)
					->update_user($this->request->post(), array('password','password_confirm'));
				}
				catch(Jelly_Validation_Exception $e)
				{
					$errors = $e->errors('common_validation');
					$errors = $errors['_external'];
				}
			}

			if( ! $errors)
			{
				$message = __('register.password_changed');
			}
		}

		$this->page_title = __('Личный кабинет').' / '.__('Смена пароля');
		$this->template->content = View::factory('frontend/form/auth/password/change')
			->bind('user', $user)
			->bind('errors', $errors)
			->bind('message', $message)
		;
	}

	public function action_edit()
	{
		StaticJs::instance()
			->add('/js/jquery.maskedinput.min.js')
			->add('/js/jquery.tooltips.min.js')
			->add('/js/form.js')
			;
//		StaticCss::instance()
//			->add('/css/auth.css')
//			;

		$user = Jelly::query('user', Auth::instance()->get_user()->id)->select();
		$user_data = $user->user_data;

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
			'nickname' => $user->nickname,
			'last_name' => $user_data->last_name,
			'first_name' => $user_data->first_name,
			'patronymic' => $user_data->patronymic,
			'team' => $user_data->team,
			'country' => $user_data->country->name,
			'city' => $user_data->city->name,
			'language' => $user_data->language->name,
			'birthdate' => $user_data->birthdate,
			'phone' => $user_data->phone,
			'vk_id' => ($user_data->vk_id) ? $user_data->vk_id : __('Отсутствует'),
			'fb_id' => ($user_data->fb_id) ? $user_data->fb_id : __('Отсутствует'),
		);

		if($this->request->method() === HTTP_Request::POST)
		{
			$post = Arr::extract($this->request->post(), array_keys($fields), NULL);
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
					case 'nickname':
						$_user_data[$name] = $value;
						break;
				}
			}

			$country = Jelly::query('country')->where('name', '=', HTML::chars(trim($post['country'])))->limit(1)->select();
			if( ! $country->loaded())
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
			if( ! $city->loaded())
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
			if( ! $language->loaded())
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

				$user_data->set($_user_info);
				try
				{
					$user_data->save();
					$_user_data['fullname'] = $user_data->last_name .' '. $user_data->first_name;
				}
				catch(Jelly_Validation_Exception $e)
				{
					$errors += $e->errors('common_validation');
				}

			}

			if( ! $errors)
			{
				try
				{
					$user->update_user($_user_data, array_keys($_user_data));
				}
				catch(Jelly_Validation_Exception $e)
				{
					$errors += $e->errors('common_validation');
				}
			}

		    if( ! $errors)
			{
				$this->request->redirect(Route::url('user', array('lang' => I18n::lang(), 'action' => 'cabinet')));
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

		$this->page_title        = __('Изменение регистрационных данных');
		$this->template->content = View::factory('frontend/form/user/edit')
			->bind('post', $fields)
			->bind('cards', $cards)
			->bind('languages', $languages)
			->bind('countries', $countries)
			->bind('cities', $cities)
			->bind('classes', $classes)
			->bind('errors', $errors)
			->bind('phone_mask', $phone_mask)
		;
	}

} // End Controller_Core_User