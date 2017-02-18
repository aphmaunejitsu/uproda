<?php
class Libs_Image_Exception extends \Exception
{
	public function  __construct($message, $code = 0, Exception $previous = null)
	{
		parent::__construct($message, $code, $previous);
	}

	public function __toString()
	{
		return __CLASS__.': '.$this->message.' ('.$this->code.')';
	}
}

class Libs_Image
{
	public static function get_two_char_from_basename($basename)
	{
		//フラグメントが起こりうだから、1文字
		return \Str::lower(\Str::sub($basename, 0, 1));
	}

	public static function build_real_image_dir($basename)
	{
		return \Str::tr(DOCROOT.':savedir/:twochardir/', [
			'savedir'    => Libs_Config::get('board.dir'),
			'twochardir' => self::get_two_char_from_basename($basename),
		]);
	}

	public static function build_real_image_path($basename, $ext)
	{
		return \Str::tr(':imagedir/:basename.:ext', [
			'imagedir' => self::build_real_image_dir($basename),
			'basename' => $basename,
			'ext'      => $ext,
		]);
	}

	public static function build_real_thumbnail_dir($basename)
	{
		return \Str::tr(':imagedir:thumbnaildir/', [
			'imagedir'     => self::build_real_image_dir($basename),
			'thumbnaildir' => Libs_Config::get('board.thumbnail.dir', 'thumbnail'),
		]);
	}

	public static function build_real_thumbnail_path($basename)
	{
		return \Str::tr(':thumbnaildir:basename.jpg', [
			'thumbnaildir' => self::build_real_thumbnail_dir($basename),
			'basename'     => $basename,
		]);
	}


	public static function thumbnail($file)
	{
		try {
			$basename = \Arr::get($file, 'basename');
			$image_dir = self::build_real_image_dir($basename);
			$thumbnail_dir = self::build_real_thumbnail_dir($basename);

			try {
				\File::read_dir($thumbnail_dir);
			} catch (\Exception $e) {
				\Log::info($e->getMessage());
				$thumbnail = Libs_Config::get('board.thumbnail.dir');
				\File::create_dir($image_dir, $thumbnail, 0777);
			}

			$image_path = self::build_real_image_path($basename, \Arr::get($file, 'ext'));

			$image = \Image::load($image_path)->crop_resize(Libs_Config::get('board.thumbnail.width'), Libs_Config::get('board.thumbnail.height'));
			$save_path = self::build_real_thumbnail_path($basename);
			$image->save($save_path);

		} catch (\Exception $e) {
			\Log::error(__FILE__.'('.__LINE__.'): '.$e->getMessage());
			throw new Libs_Image_Exception('fail create thumbnail');
		}
	}

	public static function check_id($id)
	{
		$id = htmlspecialchars($id);
		$v = \Validation::forge();
		$v->add_field('image', 'image file', 'required|valid_string[alpha,numeric,dashes]');
		if ( ! $v->run(['image' => $id], true))
		{
			throw new Libs_Image_Exception('validate error: '.$id);
		}
	}

	public static function get($id)
	{
		try {
			self::check_id($id);

			if ( ! ($image = Model_Image::find_one_by('basename', $id)))
			{
				throw new Libs_Image_Exception('image not fond: '.$id);
			}

			return $image;
		} catch (Libs_Image_Exception $e) {
			\Log::error($e);
			return null;
		} catch (\Exception $e) {
			\Log::error(__FILE__.'('.__FUNCTION__.'): '.$e->getMessage());
			return null;
		}
	}

	/**
	 * ファイルの存在チェック
	 * @param $image_path string
	 * @return string success file_path, fail null
	 **/
	public static function exists($basename, $ext)
	{
		self::build_real_image_path($basename, $ext);
		if (\File::exists($real_path))
		{
			return $path;
		}

		return null;
	}

	public static function upload()
	{
		try {
			\Upload::register('validate', function(&$file) {
				$file['basename'] = \Str::random('alnum', 8);
			});
			\Upload::register('before', function(&$file) {
				$file['path'] = $file['path'].self::get_two_char_from_basename($file['basename']).'/';
				//保存する拡張子は全て小文字変換
				$file['extension'] = \Str::lower($file['extension']);
				$file['saved_as']  = $file['basename'].'.'.$file['extension'];
				$file['filename']  = $file['saved_as'];
			});

			\Upload::register('after', function(&$file) {
				Libs_Image_Util::fixed($file->path.$file->saved_as);
			});

			umask(0);
			\Upload::process([
				'auto_process'   => false,
				'path'           => DOCROOT.Libs_Config::get('board.dir'),
				'ext_whitelist'  => explode(',', Libs_Config::get('board.ext')),
				'type_whitelist' => explode(',', Libs_Config::get('board.type')),
				'max_size'       => Libs_Config::get('board.maxsize') * 1024 * 1024, //バイトに変換
				'path_chmod'     => 0777,
				'file_chmod'     => 0666,
			]);

			if (\Upload::is_valid())
			{
				\Upload::save();
				$files = \Upload::get_files();
				$file = reset($files);

				$image_info = [
					'basename'   => $file['basename'],
					'ext'        => $file['extension'],
					'original'   => $file['name'],
					'delkey'     => htmlspecialchars(\Input::post('pass')),
					'mimetype'   => $file['mimetype'],
					'size'       => $file['size'],
					'comment'    => htmlspecialchars(\Input::post('comment')),
					'ip'         => \Input::real_ip(),
					'ng'         => 0,
				];
				$image = Model_Image::forge()->set($image_info);

				//保存失敗
				if ( ! ($result = $image->save()))
				{
					//ゴミ掃除
					unlink(DOCROOT.Libs_Config::get('board.dir').'/'.$file['saved_as']);
					throw new \Exception('faild save data');
				}

				return $image_info;
			}
			else
			{
				\Log::warning(print_r(\Upload::get_errors(),1));
				return null;
			}
		} catch (\Exception $e) {
			\Log::error(__FILE__.': '.$e);
			return null;
		}
	}


	public static function count_all()
	{
		try {
			return Model_Image::count(
				'id',
				false
			);
		} catch (\Exception $e) {
			\Log::error(__FILE__.': '.$e);
			return 0;
		}
	}

	public static function count($ng = 0)
	{
		try {
			return Model_Image::count(
				'id',
				false,
				[['ng', '=', $ng]]
			);
		} catch (\Exception $e) {
			\Log::error(__FILE__.': '.$e);
			return 0;
		}
	}

	public static function get_images($offset, $limit )
	{
		try {
			$images = Model_Image::find([
				'where'    => ['ng' => 0],
				'order_by' => ['created_at' => 'desc'],
				'limit'    => $limit,
				'offset'   => $offset,
			]);

			return $images;
		} catch (\Exception $e) {
			\Log::error(__FILE__.': '.$e);
			return null;
		}
	}

	public static function delete_by_image($image)
	{
		try {
			//サムネイル削除
			$thumb_path = self::build_real_thumbnail_path($image->basename);
			\File::delete($thumb_path);
		} catch (\Exception $e) {
			\Log::warning($e);
		}

		try {
			//本体削除
			$image_path = self::build_real_image_path($image->basename, $image->ext);
			\File::delete($image_path);
		} catch (\Exception $e) {
			\Log::warning($e);
		}

		//ディレクトリ掃除
		try {
			$thumbnail_dir = self::build_real_thumbnail_dir($image->basename);
			if ( ! ($contents = \File::read_dir($thumbnail_dir, 1, ['!^\.'])))
			{
				$image_dir = self::build_real_image_dir($image->basename);
				\File::delete_dir($image_dir, true, true);
			}
		} catch (\Exception $e) {
			\Log::warning($e);
		}


		if ( ! $image->delete() )
		{
			return false;
		}

		return true;
	}

	public static function delete_by_hash($hash, $delkey)
	{
		try {
			$h = htmlspecialchars($hash);
			$v = \Validation::forge();
			$v->add_field('hash', 'hash', 'required|valid_string[alpha,numeric]');
			if ( ! $v->run(['hash' => $h], true))
			{
				//\Log::debug(print_r($v->error(),1));
				throw new Libs_Image_Exception('validate error: '.$h);
			}

			if ( ! ($images = Model_Image::find(['where' => ['sha1(concat('."'".Libs_Config::get('board.key')."'".',id))' => $h]])))
			{
				return false;
			}

			$image = reset($images);

			$pass = $image->delkey;
			if (empty($pass))
			{
				$pass = Libs_Config::get('board.del');
			}

			if ( $pass !== $delkey )
			{
				return true;
			}

			return self::delete_by_image($image);
		} catch (\Exception $e) {
			\Log::error(__FILE__.'('.__FUNCTION__.'): '.$e->getMessage());
			return false;
		}
	}
}
