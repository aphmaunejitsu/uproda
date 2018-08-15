<?php
class Libs_Image_Exception extends \Libs_Exception {}

class Libs_Image
{
	const NO_ERROR = 0;
	const IMAGE_NOT_FOUND = 1;
	const IMAGE_FAILED_CREATE = 2;
	const IMAGE_OVER_MAXSIZE =4;
	const IMAGE_UPLOAD_NG = 5;
	const IMAGE_FAILED_CREATE_HASH = 8;

	public static function hash($id)
	{
		return \Libs_Hash::crypt($id);
	}

	public static function get_one_char_from_basename($basename)
	{
		//フラグメントが起こりうだから、1文字
		return \Str::lower(\Str::sub($basename, 0, 1));
	}

	public static function build_image_url($basename)
	{
		return \Uri::create('image/:basename', ['basename' => $basename]);
	}

	public static function build_real_image_dir($basename)
	{
		return \Str::tr(DOCROOT.':savedir/:onechardir/', [
			'savedir'    => Libs_Config::get('board.dir'),
			'onechardir' => self::get_one_char_from_basename($basename),
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

	public static function build_real_thumbnail_path($basename, $ext = 'jpg')
	{
		return \Str::tr(':thumbnaildir:basename.:ext', [
			'thumbnaildir' => self::build_real_thumbnail_dir($basename),
			'basename'     => $basename,
			'ext'          => $ext,
		]);
	}

	public static function exif($filename)
	{
		try {
			return exif_read_data($filename);
		} catch (\Exception $e) {
			\Log::warning($e->getMessage());
			return false;
		}
	}

	/**
	 * exif情報を元に、画像の上下左右を修正する
	 *
	 **/
	public static function fixed($filename)
	{
		try {
			if (($exif = self::exif($filename)) === false)
			{
				return;
			}

			if (($orientation = \Arr::get($exif, 'Orientation', null)) === null)
			{
				return;
			}

			$image = \Image::load($filename);
			switch ($orientation)
			{
			    case 0:
			    case 1:
			    return;

			    case 2:
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
			}

			$image->save($filename);
		} catch (\Exception $e) {
			\Log::warning($e->getMessage());
			return;
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
			throw new \Libs_Image_Exception('image not found: '.__LINE__, self::IMAGE_NOT_FOUND);
		}
	}

	/**
	 * 画像の情報を取得
	 *
	 * @param string $id 画像ID
	 * @return Model_Image 画像モデルのオブジェクト
	 * @throws Libs_Image_Exception 画像が見つからない場合
	 * @see Libs_Image::_get
	 **/
	public static function get($id)
	{
		return Libs_Cache::cached('Libs_Image-get', ['Libs_Image', '_get'], [$id]);
	}

	/**
	 * Libs_image::get
	 **/
	public static function _get($id)
	{
		try {
			$image = \Model_Image::find_one_by('basename', $id);
		} catch (\Exception $e) {
			throw new \Libs_Image_Exception('image not found: '.__LINE__, self::IMAGE_NOT_FOUND);
		}

		if ($image)
		{
			return $image;
		}
		else
		{
			throw new \Libs_Image_Exception('image not found: '.__LINE__, self::IMAGE_NOT_FOUND);
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
		\Upload::register('validate', function(&$file) {
			$file['basename'] = \Str::random('alnum', 8);
			$file['hash'] = \Libs_Image_Hash::create_by_file($file['tmp_name']);
		});

		\Upload::register('before', function(&$file) {
			$file['path'] = $file['path'].self::get_one_char_from_basename($file['basename']).'/';
			$file['extension'] = \Str::lower($file['extension']);
			$file['saved_as']  = $file['basename'].'.'.$file['extension'];
			$file['filename']  = $file['saved_as'];
		});

		\Upload::register('after', function(&$file) {
			\Libs_Image::fixed($file->path.$file->saved_as);
		});

		umask(0);
		\Upload::process([
			'auto_process'   => false,
			'path'           => DOCROOT.\Libs_Config::get('board.dir'),
			'ext_whitelist'  => explode(',', \Libs_Config::get('board.ext')),
			'type_whitelist' => explode(',', \Libs_Config::get('board.type')),
			'max_size'       => \Libs_Config::get('board.maxsize') * 1024 * 1024, //バイトに変換
			'path_chmod'     => 0777,
			'file_chmod'     => 0666,
		]);

		if (\Upload::is_valid())
		{
			\Upload::save();
			$files = \Upload::get_files();
			$file = reset($files);
			$image_path = self::build_real_image_path($file['basename'], $file['extension']);

			//ハッシュチェック
			$hash = \Libs_Image_Hash::check($file['basename'], $file['extension']);

			try {
				//ハッシュ値の保存
				if ($hash === null)
				{
					$image_id = \Libs_Image_Hash::save_by_hash($file['hash']);
				}
				else
				{
					$image_id = $hash->id;
				}
			} catch (\Exception $e) {
				unlink($image_path);
				throw new \Libs_Image_Exception('fail upload image [hash]', self::IMAGE_FAILED_CREATE_HASH);
			}

      $ext = $file['extension'];
      if ($ext == 'jpeg')
      {
        $ext = 'jpg';
      }

			$image_info = [
				'basename'      => $file['basename'],
				'ext'           => $ext,
				'original'      => $file['name'],
				'delkey'        => \Security::clean(\Input::post('pass'), ['strip_tags', 'htmlentities']),
				'mimetype'      => $file['mimetype'],
				'size'          => $file['size'],
				'comment'       => \Security::clean(\Input::post('comment'), ['strip_tags', 'htmlentities']),
				'ip'            => \Input::real_ip(),
				'image_hash_id' => $image_id,
			];
			$image = \Model_Image::forge()->set($image_info);

			//保存失敗
			if ( ! ($result = $image->save()))
			{
				//ゴミ掃除
				unlink($image_path);
				throw new \Libs_Image_Exception('fail upload image [mysql]', self::IMAGE_FAILED_CREATE);
			}

			return $image_info;
		}
		else
		{
			//エラーチェック
			foreach (\Upload::get_errors() as $info)
			{
				if ($info['error'] !== \Upload::UPLOAD_ERR_OK)
				{
					foreach ($info['errors'] as $error)
					{
						//ファイルサイズオーバーはエラーメッセージを変更する
						if ($error['error'] === \Upload::UPLOAD_ERR_INI_SIZE or $error['error'] === \Upload::UPLOAD_ERR_FORM_SIZE or $error['error'] === \Upload::UPLOAD_ERR_MAX_SIZE)
						{
							throw new \Libs_Image_Exception('fail upload image [Max Size]', self::IMAGE_OVER_MAXSIZE);
						}
					}
				}
			}

			// 基本は汎用的なメッセージにしておく
			throw new \Libs_Image_Exception('fail upload image', self::IMAGE_FAILED_CREATE);
		}
	}


	/**
	 * 画像数を取得(全て)する
	 * @return int 画像数
	 **/
	public static function count_all()
	{
		try {
			return \Model_Image::count(
				'id',
				false
			);
		} catch (\Exception $e) {
			\Log::error($e->getMessage());
			return 0;
		}
	}

	/**
	 * 画像数を取得(デフォルトngなし)する
	 * @return int 画像数
	 **/
	public static function count($ng = 0)
	{
		try {
			return \Model_Image::count(
				'images.id',
				false,
				function ($query) use($ng) {
					$query->join('image_hash')
								->on('images.image_hash_id', '=', 'image_hash.id')
								->where('image_hash.ng', $ng);
				}
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
			$temp = \Model_Image::find([
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
	 * @see Libs_Image::_get_images
	 **/
	public static function get_images($offset, $limit, $ng = 0)
	{
		//5秒だけキャッシュ
		return Libs_Cache::cached('Libs_Image-get_images', ['Libs_Image', '_get_images'], [$offset, $limit, $ng], 5);
	}

	public static function _get_images($offset, $limit, $ng = 0)
	{
		try {
			$images = \Model_Image::find([
				'where'    => function($query) use($ng) {
					$query->join('image_hash')
					      ->on('images.image_hash_id', '=', 'image_hash.id')
					      ->where('image_hash.ng', $ng);
				},
				'order_by' => ['created_at' => 'desc'],
				'limit'    => $limit,
				'offset'   => $offset
			]);

			return $images;
		} catch (\Exception $e) {
			\Log::error($e->getMessage());
			return null;
		}
	}

	public static function get_all_images($offset, $limit)
	{
		try {
			$images = \Model_Image::find(function($query) use($offset, $limit) {
				$query->select('images.*', 'image_hash.hash', 'image_hash.ng')
						->join('image_hash')
			      ->on('images.image_hash_id', '=', 'image_hash.id')
						->order_by('images.created_at', 'desc')
						->limit($limit)
						->offset($offset);
			});

			return $images;
		} catch (\Exception $e) {
			\Log::error($e->getMessage());
			return null;
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

		$fail_data = [];
		$delete_flg = true;

		foreach ($images as $image)
		{
			$error = new stdclass();
			$error->is_error = false;
			$error->thumbnail = true;
			$error->image = true;
			$error->data = true;

			try {
				//サムネイル削除
				$thumb_path = self::build_real_thumbnail_path($image->basename);
				\File::delete($thumb_path);
			} catch (\Exception $e) {
				$error->is_error = true;
				$error->thumbnail = false;
				$delete_flg = false;
				\Log::warning($e->getMessage());
			}

			try {
				//本体削除(サムネイルが削除されていたら)
				if ($delete_flg)
				{
					$image_path = self::build_real_image_path($image->basename, $image->ext);
					\File::delete($image_path);
				}
			} catch (\Exception $e) {
				$error->is_error = true;
				$error->image = false;
				$delete_flg = false;
				\Log::warning($e->getMessage());
			}

			//ディレクトリ掃除(ここは失敗しても無視)
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

			//削除フラグがtrue(サムネイル、画像本体とも消えている)ならデータ削除
			if ($delete_flg)
			{
				if ( ! $image->delete())
				{
					\Log::warning('fail delete image. id: '.$image->id);
					$delete_flg = false;
					$error->is_error = false;
					$error->data = false;
				}
			}

			$delete_flg = true;
			if ($error->is_error)
			{
				$fail_data[$image->id] = $error;
			}
		}

		return $fail_data;
	}

	/**
	 * 指定されたハッシュから画像を削除する
	 *
	 * @param string $hash ハッシュ
	 * @param string $delkey 削除キー
	 *
	 **/
	public static function delete_by_hash($hash, $delkey, $admin = false)
	{
		try {
			$images = \Model_Image::find(function (&$query) use ($hash) {
				$key = \Libs_Config::get('board.key');
				return $query->where(\DB::expr('sha1(concat('."'".$key."'".',id))'), $hash);
			});

			if ( ! $images)
			{
				return false;
			}

			$image = reset($images);

			//管理者以外はパスチェック
			if ( ! $admin)
			{
				$pass = $image->delkey;
				if (empty($pass))
				{
					$pass = \Libs_Config::get('board.del');
				}

				if ($pass !== $delkey)
				{
					return true;
				}
			}

			return self::delete_by_images($images);
		} catch (\Exception $e) {
			\Log::error($e);
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

	public static function get_image_hash($hash)
	{
		try {
			return \Model_Image_Hash::find_one_by_hash($hash);
		} catch (\Exception $e) {
			\Log::error($e);
			return null;
		}
	}

	public static function save_image_hash($hash, $ng = 0, $comment = null)
	{
		try {
			if (($result = \Model_Image_Hash::forge()->set(['hash' => $hash, 'ng' => $ng, 'comment' => $comment])->save()))
			{
				return reset($result);
			}

			return null;
		} catch (\Exception $e) {
			\Log::error($e);
			throw new \Libs_Image_Exception('fail create Hash', __LINE__);
		}
	}

	public static function get_by_image_hash($image_hash)
	{
		$images = \Model_Image::find(function($query) use($image_hash) {
			$query->select('images.*')
					->join('image_hash')
					->on('images.image_hash_id', '=', 'image_hash.id')
					->where('image_hash.hash', '=', $image_hash);
		});

		return $images;
	}

	public static function get_images_by_image_hash($image_hash, $limit, $offset)
	{
		$images = \Model_Image::find(function($query) use($image_hash, $limit, $offset) {
			$query->select('images.*')
					->join('image_hash')
					->on('images.image_hash_id', '=', 'image_hash.id')
					->where('image_hash.hash', '=', $image_hash)
					->limit($limit)
					->offset($offset);
		});

		return $images;
	}
}
