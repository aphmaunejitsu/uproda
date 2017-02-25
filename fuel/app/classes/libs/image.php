<?php
class Libs_Image_Exception extends \Exception {}

class Libs_Image extends \Image
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

	public static function exif($filename)
	{
		try {
			return exif_read_data($filename);
		} catch (\Exception $e) {
			\Log::warning($e->getMessage());
			return FALSE;
		}
	}

	/**
	 * exif情報を元に、画像の上下左右を修正する
	 *
	 **/
	public static function fixed($filename)
	{
		try {
			if (($exif = self::exif($filename)) === FALSE)
			{
				return;
			}

			if (($orientation = \Arr::get($exif, 'Orientation', null)) === null)
			{
				return;
			}

			$image = self::load($filename);
			switch ($orientation)
			{
				case 0:
				case 1:
					return;
				break;

				case 2: //水平方向に反転
					$image->flip('horizontal');
				break;

				case 3:
					$image->rotate(180);
				break;

				case 4:
					$image->flip('vertical');
				break;

				case 5:
					$image->rotate(-90);
					$image->flip('vertical');
				break;

				case 6:
					$image->rotate(90);
				break;

				case 7:
					$image->rotate(90);
					$image->flip('vertical');
				break;

				case 8:
					$image->rotate(-90);
				break;

				default:
					return;
				break;
			}

			$image->save($filename);
		} catch (\Exception $e) {
			\Log::warning($e->getMessage());
			return;
		}
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

			$image = self::load($image_path)->crop_resize(Libs_Config::get('board.thumbnail.width'), Libs_Config::get('board.thumbnail.height'));
			$save_path = self::build_real_thumbnail_path($basename);
			$image->save($save_path);

		} catch (\Exception $e) {
			\Log::error($e->getMessage());
			throw new Libs_Image_Exception();
		}
	}

	/**
	 * 画像IDのフォーマットチェック
	 *
	 * @param string $id 画像ID
	 * @throws Libs_Image_Exception 画像IDが形式と合わない場合
	 *
	 **/
	public static function check_id($id)
	{
		$id = \Security::clean($id, ['strip_tags', 'htmlentities']);
		$v = \Validation::forge();
		$v->add_field('image', 'image file', 'required|valid_string[alpha,numeric,dashes]');
		if ( ! $v->run(['image' => $id], true))
		{
			throw new Libs_Image_Exception();
		}
	}

  /**
	 * 画像の情報を取得
	 *
	 * @param string $id 画像ID
	 * @return Model_Image 画像モデルのオブジェクト
	 * @throws Libs_Image_Exception 画像が見つからない場合
	 **/
	public static function get($id)
	{
		try {
			$image = Model_Image::find_one_by('basename', $id);
		} catch (\Exception $e) {
			throw new Libs_Image_Exception();
		}

		if ($image)
		{
			return $image;
		}
		else
		{
			throw new Libs_Image_Exception();
		}
	}

	/**
	 * 画像をアップロードする
	 *
	 * @return array アップロードした画像情報
	 * @throws Libs_Image_Exception アップロードに失敗した場合
	 **/
	public static function upload()
	{
		self::delete_captcha_session();
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
			Libs_Image::fixed($file->path.$file->saved_as);
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
				'delkey'     => \Security::clean(\Input::post('pass'), ['strip_tags', 'htmlentities']),
				'mimetype'   => $file['mimetype'],
				'size'       => $file['size'],
				'comment'    => \Security::clean(\Input::post('comment'), ['strip_tags', 'htmlentities']),
				'ip'         => \Input::real_ip(),
				'ng'         => 0,
			];
			$image = Model_Image::forge()->set($image_info);

			//保存失敗
			if ( ! ($result = $image->save()))
			{
				//ゴミ掃除
				unlink(DOCROOT.Libs_Config::get('board.dir').'/'.$file['saved_as']);
				throw new Libs_Image_Exception();
			}

			return $image_info;
		}
		else
		{
			\Log::warning(print_r(\Upload::get_errors(),1));
			throw new Libs_Image_Exception();
		}
	}


	/**
	 * 画像数を取得する
	 * @return int 画像数
	 **/
	public static function count_all()
	{
		try {
			return Model_Image::count(
				'id',
				false
			);
		} catch (\Exception $e) {
			\Log::error($e->getMessage());
			return 0;
		}
	}

	/**
	 * 画像数を取得する
	 * @param int $ng デフォルト:0
	 * @return int 画像数
	 **/
	public static function count($ng = 0)
	{
		try {
			return Model_Image::count(
				'id',
				false,
				[['ng', '=', $ng]]
			);
		} catch (\Exception $e) {
			\Log::error($e->getMessage());
			return 0;
		}
	}

	/**
	 * 削除する画像を取得する
	 *
	 * @return array
	 **/
	public static function get_images_for_delete()
	{
		try {
			$temp = Model_Image::find([
				'order_by' => ['created_at' => 'desc'],
				'limit'    => \Libs_Config::get('board.maxfiles'),
				'offset'   => 0,
			]);

			if (count($temp) < intval(\Libs_Config::get('board.maxfiles')))
			{
				return [];
			}

			$last = end($temp);
			return Model_Image::find([
				'where' => [['id', '<', $last->id]]
			]);
		} catch (\Exception $e) {
			\Log::error($e->getMessage());
			return [];
		}
	}

	/**
	 * 画像を取得する
	 *
	 * @param int $offset 取得するオフセット
	 * @param int $limit 取得する数
	 *
	 * @return array
	 *
	 **/
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
			\Log::error($e->getMessage());
			return [];
		}
	}

	/**
	 * 画像を削除する
	 * 処理は全部失敗してもいい
	 */
	public static function delete_by_images($images)
	{
		if (empty($images))
		{
			return true;
		}

		foreach ($images as $image)
		{
			try {
				//サムネイル削除
				$thumb_path = self::build_real_thumbnail_path($image->basename);
				\File::delete($thumb_path);
			} catch (\Exception $e) {
				\Log::warning($e->getMessage());
			}

			try {
				//本体削除
				$image_path = self::build_real_image_path($image->basename, $image->ext);
				\File::delete($image_path);
			} catch (\Exception $e) {
				\Log::warning($e->getMessage());
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
				\Log::warning($e->getMessage());
			}

			if ( ! $image->delete() )
			{
				\Log::warning('fail delete image. id: '.$image->id);
			}

		}

		return true;
	}

  /**
	 * 指定されたハッシュから画像を削除する
	 *
	 * @param string $hash ハッシュ
	 * @param string $delkey 削除キー
	 *
	 **/
	public static function delete_by_hash($hash, $delkey)
	{
		try {
			if ( ! ($images = Model_Image::find(['where' => ['sha1(concat('."'".Libs_Config::get('board.key')."'".',id))' => $hash]])))
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

			return self::delete_by_images($images);
		} catch (\Exception $e) {
			\Log::error($e->getMessage());
			return false;
		}
	}

	/**
	 * キャプチャのセッションを削除する
	 **/
	protected static function delete_captcha_session()
	{
		//\Config::load('simplecaptcha');
		$skn = \Config::get('simplecaptcha.session_key_name');
		\Session::delete($skn);
	}

	/**
	 * ファイルの存在チェック
	 * @param $image_path string
	 * @return string success file_path, fail null
	 **/
	public static function exists($basename, $ext)
	{
		$real_path = self::build_real_image_path($basename, $ext);
		if (\File::exists($real_path))
		{
			return $real_path;
		}

		return null;
	}
}
