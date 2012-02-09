<?php defined('SYSPATH') or die('No direct access allowed.');

/**
 * Card Model for Jelly ORM
 *
 * @package User_Auth
 * @author  Sergei Gladkovskiy <smgladkovskiy@gmail.com>
 */
class Model_Core_Hash extends Jelly_Model {

	/**
	 * Initializating model meta information
	 *
	 * @param Jelly_Meta $meta
	 */
	public static function initialize(Jelly_Meta $meta)
	{
		$meta->table('hashes')
			->fields(array(
				'id' => Jelly::field('Primary'),
				'object' => Jelly::field('String'),
				'object_id' => Jelly::field('String'),
				'hash' => Jelly::field('String'),
				'date_valid_end' => Jelly::field('Timestamp'),
			));
	}
} // End Model_Card