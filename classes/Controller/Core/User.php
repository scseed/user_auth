<?php defined('SYSPATH') or die('No direct access allowed.');

/**
 * Template Controller Core User
 *
 * @package User
 * @author Sergei Gladkovskiy <smgladkovskiy@gmail.com>
 */
abstract class Controller_Core_User extends Controller_Template {

	protected $_email = NULL;
	public $_auth_required = TRUE;

	public function before()
	{
		if($this->request->action() == 'login_panel')
			$this->_auth_required = FALSE;

		parent::before();

		if($this->request->action() == 'login_panel')
		{
			if(Auth::instance()->logged_in())
			{
				$this->request->action('panel');
			}
		}

		$this->_email = Kohana::$config->load('email');

	}

	/**
	 * User private cabinet
	 *
	 * @return void
	 */
	public function action_mydata()
	{
		if( ! Auth::instance()->logged_in())
			HTTP::redirect(Route::url('default', array('lang' => I18n::lang())));

		$user = Jelly::query('user', Auth::instance()->get_user()->id)->select();
		if( ! $user OR ! $user->loaded())
			HTTP::redirect(Route::url('default', array('lang' => I18n::lang())));

		StaticJs::instance()
			->add_modpath('js/jquery.maskedinput-1.3.min.js')
			->add_modpath('js/userdata.js')
		;

		$this->template->modals .= View::factory('frontend/modal/user/inputError');

		$this->page_title = __('Личный данные');
		$this->template->content = View::factory('frontend/form/user/mydata')
			->bind('user', $user)
		;
	}


	/**
	 * User password changing
	 *
	 * @return void
	 */
	public function action_change_pass()
	{
		if( ! Auth::instance()->logged_in())
			HTTP::redirect(Route::url('default', array('lang' => I18n::lang())));

		$user = Jelly::query('user', Auth::instance()->get_user()->id)->select();
		if( ! $user OR ! $user->loaded())
			HTTP::redirect(Route::url('default', array('lang' => I18n::lang())));

		$force_login  = Session::instance()->get('auth_forced');

		StaticJs::instance()
			->add_modpath('js/userdata.js')
		;

		$this->template->modals .= View::factory('frontend/modal/user/inputError');

		$this->template->title = __('Смена пароля');
		$this->template->content = View::factory('frontend/form/auth/password/change')
			->set('force_login', $force_login)
		;
	}

	public function action_list()
	{
		$users = Jelly::query('user')->select();

		$this->template->modals .= View::factory('frontend/modal/user/create');
		$this->template->modals .= View::factory('frontend/modal/user/update');
		$this->template->modals .= View::factory('frontend/modal/user/activity');
		$this->template->modals .= View::factory('frontend/modal/auth/registrationEmailSend');

		StaticJs::instance()
			->add_modpath('js/jquery.maskedinput-1.3.min.js')
			->add('js/user.js')
		;

		$this->template->title = __('Пользователи Системы');
		$this->template->content = View::factory('frontend/content/user/list')
		->bind('users', $users)
		;
	}

} // End Controller_Core_User