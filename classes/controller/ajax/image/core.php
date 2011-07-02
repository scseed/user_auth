<?php defined('SYSPATH') or die('No direct access allowed.');

/**
 * Ajax Controller image
 *
 * @package User_Auth
 * @author Sergei Gladkovskiy <smgladkovskiy@gmail.com>
 */
abstract class Controller_Ajax_Image_Core extends Controller_Ajax_Template {

	protected $_user;
	protected $_allowed_image_types;
	protected $_allowed_image_ext;
	protected $_max_image_size;
	protected $_max_thumb_width;
	protected $_large_image_path;
	protected $_thumb_image_path;

	protected $_avatars_src;
	protected $_thumbs_src;

	const SUCCESS = 'success';
	const ERROR   = 'error';

	/**
	 * Setting up images properties
	 *
	 * @throws HTTP_Exception_401
	 * @return void
	 */
	public function before()
	{
		parent::before();

		if (! Auth::instance()->logged_in())
		{
			throw new HTTP_Exception_401('Unauthorized access');
		}
		elseif(Auth::instance()->logged_in() OR Auth::instance()->logged_in())
		{
			$this->_user = Auth::instance()->get_user();
		}

		$this->_allowed_image_types = array(
			'image/pjpeg' => 'jpg',
			'image/jpeg'  => 'jpg',
			'image/jpg'   => 'jpg',
			'image/png'   => 'png',
			'image/x-png' => 'png',
			'image/gif'   => 'gif'
		);
		$this->_allowed_image_ext = array_unique($this->_allowed_image_types);

		$this->_max_image_size   = 3;   // In MB
		$this->_max_thumb_width  = 600; // In px
		$this->_max_avatar_width = 300; // In px

		$user_id = $this->request->param('id');

		if($this->request->action() == 'save' AND $this->request->post('profile_id'))
			$user_id = $this->request->post('profile_id');

		$user = NULL;
		if($user_id AND is_object($this->_user) AND $this->_user->has_role('admin'))
		{
			$user = (int) $user_id;
		}
		else
		{
			$user = $this->_user->id;
		}


		$this->_avatars_src = 'media/images/avatars/'.$user.'/';
		$this->_thumbs_src  = 'media/cache/thumbs/'.Session::instance()->id().'/';

		$avatars_path = DOCROOT . str_replace('/', DIRECTORY_SEPARATOR, $this->_avatars_src);
		$thmbs_path   = DOCROOT . str_replace('/', DIRECTORY_SEPARATOR, $this->_thumbs_src);

		$this->_large_image_path = $avatars_path;
		$this->_thumb_image_path = $thmbs_path;
	}

	/**
	 * Ajax image uploading and preparing.
	 * @return void
	 */
	public function action_upload()
	{
		//Get the file information
		$image         = Arr::get($_FILES, 'image', NULL);
		$userfile_tmp  = Arr::get($image, 'tmp_name', NULL);
		$userfile_size = Arr::get($image, 'size', NULL);
		$userfile_type = Arr::get($image, 'type', NULL);
		$file_ext      = mb_strtolower(pathinfo(Arr::get($image, 'name'), PATHINFO_EXTENSION));
		$errors        = NULL;
		$status        = self::ERROR;
		$message       = NULL;

		//Only process if the file is a JPG and below the allowed limit
		if(( ! empty($image)) AND (Arr::get($image, 'error', 0) == 0))
		{
			foreach($this->_allowed_image_types as $mime_type => $ext)
			{
				//loop through the specified image types and if they match the extension then break out
				//everything is ok so go and check file size
				if($userfile_type == $mime_type AND $file_ext == $ext)
				{
					$status = self::SUCCESS;
					break;
				}
			}

			if($status === self::ERROR)
			{
				$errors[] = __(
					'Only :image_types images are accepted for upload',
					array(':image_types' => mb_strtoupper(implode(', ', array_values($this->_allowed_image_ext))))
				);
			}

			if($status === self::SUCCESS)
			{
				//check if the file size is above the allowed limit
				if ($userfile_size > ($this->_max_image_size*1048576))
				{
					$status = self::ERROR;
					$errors[] = __(
						'Image must be under :image_size MB in size!',
						array(':image_size' => $this->_max_image_size)
					);
				}
			}
		}
		else
		{
			$errors[] = __('Please select an image for upload');
		}

		//Everything is ok, so we can upload the image.
		if ($status === self::SUCCESS)
		{
			//this file could now has an unknown file extension (we hope it's one of the ones set above!)
			$thumb_name  = substr(Session::instance()->id(), 0, 8).'.'.$file_ext;
			$thumb_image_file = $this->_thumb_image_path.$thumb_name;
			$thumb_src        = '/'.$this->_thumbs_src.$thumb_name;

			if( ! file_exists($this->_large_image_path))
			{
				mkdir($this->_large_image_path, 0755, TRUE);
			}
			if( ! file_exists($this->_thumb_image_path))
			{
				mkdir($this->_thumb_image_path, 0755, TRUE);
			}

			//put the file ext in the session so we know what file to look for once its uploaded
			if(Session::instance()->get('user_file_ext') != $file_ext)
			{
				Session::instance()->set('user_file_ext', $file_ext);
			}

			$image = Image::factory($userfile_tmp);

			$width = $image->width;
			//Scale the image if it is greater than the width set above
			if ($width > $this->_max_thumb_width)
			{
				$image->resize($this->_max_thumb_width, $this->_max_thumb_width);
			}

			$image->save($thumb_image_file, 100);

			$message = array(
				'thumb_src' => $thumb_src.'?'.time(),
				'width'     => $image->width,
				'height'    => $image->height
			);
		}
		else
		{
			$message = implode('<br/>', $errors);
		}

		$response[] = array(
			'status' => $status,
			'message' => $message
		);

		$this->response->body(json_encode($response));
	}

	/**
	 * Saving croped image
	 *
	 * @return void
	 */
	public function action_save()
	{
		//Get the new coordinates to crop the image.
		$x1 = $this->request->post('x1');
		$y1 = $this->request->post('y1');
//		$x2 = $this->request->post('x2');
//		$y2 = $this->request->post('y2');
		$w = $this->request->post('w');
		$h = $this->request->post('h');
		$user_id = $this->request->post('profile_id');

		//Scale the image to the thumb_width set above
		$file_ext             = Session::instance()->get('user_file_ext');
		$avatar_name          = 'avatar.'.$file_ext;
		$thumb_name           = substr(Session::instance()->id(), 0, 8).'.'.$file_ext;
		$large_image_location = $this->_large_image_path.$avatar_name;
		$thumb_image_location = $this->_thumb_image_path.$thumb_name;
		$avatar_src           = '/'.$this->_avatars_src.'avatar.'.$file_ext;

		// Create big avatar
		$avatar = Image::factory($thumb_image_location);
		$avatar->crop($w, $h, $x1, $y1);

		if($avatar->width > $this->_max_avatar_width)
			$avatar->resize($this->_max_avatar_width,$this->_max_avatar_width);

		$avatar->save($large_image_location);

		// Create thumb for comments
		$avatar = Image::factory($large_image_location);
		$avatar->resize(65,65);
		$avatar->save($this->_large_image_path.'thumb.'.$file_ext);

		Session::instance()->delete('user_file_ext');
		unlink($thumb_image_location);

		$response = array(
			'status' => self::SUCCESS,
			'message' => array(
				'large_image_src' => $avatar_src.'?'.time()
			)
		);

		try
		{
			if($user_id AND is_object($this->_user) AND $this->_user->has_role('admin'))
			{
				// Update user `has_avatar` parameter
				Jelly::query('user', (int) $user_id)->set(array('has_avatar' => TRUE))->update();
			}
			else
			{
				// Update user `has_avatar` parameter
				Jelly::query('user', $this->_user->id)->set(array('has_avatar' => TRUE))->update();
			}

		}
		catch(Jelly_Validation_Exception $e)
		{
			$response = array(
				'status' => self::ERROR,
				'message' => $e->errors('common_validation')
			);
		}

		$this->response->body(json_encode($response));
	}

} // End Controller_Image_Core