<?php defined('SYSPATH') OR die('No direct access allowed.');

/**
 * User_data Model for Jelly ORM
 *
 * @package User_Auth
 * @author  Sergei Gladkovskiy <smgladkovskiy@gmail.com>
 */
class Model_Core_User_Data extends Jelly_Model {

	/**
	 * Initializating model meta information
	 *
	 * @param Jelly_Meta $meta
	 */
	public static function initialize(Jelly_Meta $meta)
	{
		$meta->table('user_data')
			->fields(array(
				'id' => Jelly::field('Primary'),
				'last_name' => Jelly::field('String', array(
					'rules' => array(
						array('not_empty')
					),
					'label' => __('Фамилия'),
				)),
				'first_name' => Jelly::field('String', array(
					'rules' => array(
						array('not_empty')
					),
					'label' => __('Имя'),
				)),
				'patronymic' => Jelly::field('String', array(
					'convert_empty' => TRUE,
					'allow_null'    => TRUE,
					'label'         => __('Отчество'),
				)),
				'user' => Jelly::field('BelongsTo', array(
					'in_form'    => FALSE,
				)),
			));
	}
} // End Model_User_data