<?php defined('SYSPATH') or die('No direct access allowed.');

/**
 * Template Controller User
 *
 * @author Sergei Gladkovskiy <smgladkovskiy@gmail.com>
 * @copyrignt
 */
class Controller_Ajax_User extends Controller_Ajax_Template {

	public function action_update()
	{
		$redirect        = NULL;

		$user = Jelly::query('user',$this->_user->id)->select();

		$status = FALSE;
		$errors = NULL;
		$post   = array(
			'name'  => NULL,
			'value' => NULL,
		);

		$post_data = Arr::extract($this->request->post(), array_keys($post));

		$fields = $user->meta()->fields();
		$post_data['name'] = HTML::chars(trim($post_data['name']));

		$field_exists = FALSE;
		foreach($fields as $field)
		{
			if($field->name == $post_data['name'])
				$field_exists = TRUE;
		}


		$value = HTML::chars(trim($post_data['value']));
		if($field_exists)
		{
			switch($post_data['name'])
			{
				case 'phone':
					$value = preg_replace('/([-\(\)\s\+]?)/', '', $value);
					break;
				case 'last_name':
				case 'first_name':
				case 'patronymic':
					break;
			}

			$user->$post_data['name'] = ($value == '') ? NULL : $value;
		}
		else
		{
			$errors[] = array('name' => __('Поля не существует!'));
		}

		if( ! $value)
			$errors[] = array('value' => __('Поле оставлено пустым! Необходимо его заполнить!'));

		try
		{
			$user->save();
		}
		catch(Jelly_Validation_Exception $e)
		{
			$errors[] = $e->errors('validate');
		}

		if(!$errors)
			$status = TRUE;

		$this->response->body(json_encode(array(
			'status'     => $status,
			'errors'     => $errors,
			'redirect'   => $redirect,
			'value'   => $value,
		)));
	}

} // End Controller_User