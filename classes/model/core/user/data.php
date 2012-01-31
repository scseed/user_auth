<?php defined('SYSPATH') OR die('No direct access allowed.');

/**
 * User_data Model for Jelly ORM
 *
 * @author devolonter <devolonter@enerdesign.ru>
 * @copyright (c) 2010 EnerDesign <http://enerdesign.ru>
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
				'birthdate' => Jelly::field('Timestamp', array(
					'convert_empty' => TRUE,
					'allow_null'    => FALSE,
					'label'         => __('Дата рождения'),
					'format'        => 'Y-m-d',
				)),
				'phone' => Jelly::field('String', array(
					'convert_empty' => TRUE,
					'allow_null'    => TRUE,
					'label'         => __('Телефон'),
				)),
				'user' => Jelly::field('HasOne', array(
					'in_form'    => FALSE,
				)),
			));
	}
} // End Model_User_data