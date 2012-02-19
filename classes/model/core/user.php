<?php defined('SYSPATH') OR die('No direct access allowed.');

/**
 * User Model for Jelly ORM
 *
 * @package User_Auth
 * @author  Sergei Gladkovskiy <smgladkovskiy@gmail.com>
 */
abstract class Model_Core_User extends Model_Auth_User {

	/**
	 * Initializating model meta information
	 *
	 * @param Jelly_Meta $meta
	 */
	public static function initialize(Jelly_Meta $meta)
	{
		parent::initialize($meta);
		$meta->name_key('email');
		$meta->fields(array(
			'is_active' => Jelly::field('Boolean', array(
					'label'       => __('Статус'),
					'label_true'  => __('Активен'),
					'label_false' => __('Отключён'),
					'default'     => TRUE
				)),
			'date_create' => Jelly::field('Timestamp', array(
					'auto_now_create' => TRUE,
					'in_form'         => FALSE,
					'label'           => __('Дата создания'),
				)),
			'date_update' => Jelly::field('Timestamp', array(
					'auto_now_update' => TRUE,
					'in_form'         => FALSE,
					'in_table'        => FALSE,
					'label'           => __('Дата обновления'),
				)),
			'user_data'   => Jelly::field('HasOne', array(
					'allow_null' => TRUE,
					'default'    => NULL,
					'label'      => __('Данные пользователя'),
					'in_table'   => FALSE,
				)),
			// Disable 'username' field
			'username'   => Jelly::field('String', array(
//						'in_db' => FALSE,
//						'in_form'  => FALSE,
//						'in_table' => FALSE,
				)),
			'email'      => Jelly::field('Email', array(
					'label' => __('Email'),
					'rules' => array(
						array('not_empty'),
					),
					'unique' => TRUE,
				)),
			'password' => Jelly::field('Password', array(
					'in_table' => FALSE,
					'label'    => __('Пароль'),
					'rules'    => array(
						array('min_length', array(':value', 4)),
					),
					'hash_with' => array(Auth::instance(), 'hash'),
					'in_form'  => FALSE,
				)),
			'logins'  => Jelly::field('Integer', array(
					'in_form'  => FALSE,
					'in_table' => FALSE,
					'default'  => 0,
				)),

			'user_session'  => Jelly::field('String', array(
					'allow_null' => TRUE,
					'default'    => NULL,
					'in_form'  => FALSE,
					'in_table' => FALSE,
				)),

			'roles'  => Jelly::field('ManyToMany', array(
					'label' => __('Роли пользователя'),
				)),
			'user_tokens'  => Jelly::field('HasMany', array(
					'in_form'  => FALSE,
					'in_table' => FALSE,
				)),
			'last_login' => Jelly::field('Timestamp', array(
					'in_form'  => FALSE,
					'in_table' => FALSE,
				)),
		));
	}

	public function unique_key($value)
	{
		return 'email';
	}

	/**
	 * Password validation for plain passwords.
	 *
	 * @param array $values
	 * @return Validation
	 */
	public static function get_password_validation($values)
	{
		return Validation::factory($values)
			->labels(array(
				'password' => __('Пароль'),
				'password_confirm' => __('Подтверждение пароля'),
			))
			->rule('password', 'min_length', array(':value', 4))
			->rule('password_confirm', 'matches', array(':validation', ':field', 'password'));
	}

	/**
	 * Loads a user based on unique key.
	 *
	 * @param   string  $unique_key
	 * @return  Jelly_Model
	 */
	public function get_user($unique_key)
	{
		return Jelly::query('user')->where($this->unique_key($unique_key), '=', $unique_key)->limit(1)->select();
	}

	/**
	 * Is the model has specified role
	 *
	 * @param string|null $role_name
	 * @return bool
	 */
	public function has_role($role_name = NULL)
	{
		$roles = $this->roles->as_array('name', 'id');

		return array_key_exists($role_name, $roles);
	}

} // End Model_User