<?php
class Libs_Image_Exception extends \Libs_Exception {}

class Libs_Image extends \Image
{
	const MAGICCODE = 'desushiosushi';
	const IMAGE_NOT_FOUND = 0;

	public static function hash($id)
	{
		$mc = Libs_Config::get('board.key', self::MAGICCODE);
		return sha1($mc.$id);
	}

	public static function get_two_char_from_basename($basename)
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

			$image = self::load($filename);
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
	 **/
	public static function get($id)
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
		self::delete_captcha_session();
		\Upload::register('validate', function(&$file) {
			$file['basename'] = \Str::random('alnum', 8);
			$file['hash'] = \Libs_Image_Hash::create_by_file($file['tmp_name']);
		});

		\Upload::register('before', function(&$file) {
			$file['path'] = $file['path'].self::get_two_char_from_basename($file['basename']).'/';
			//保存する拡張子は全て小文字変換
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
			'path'           => DOCROOT.Libs_Config::get('board.dir'),
			'ext_whitelist'  => explode(',', Libs_Config::get('board.ext')),
			'type_whitelist' => explode(',', Libs_Config::get('board.type')),
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

			//ハッシュ値が登録されているか
			$hash = $file['hash'];
			if (($image_hash = self::get_image_hash($hash)) === null)
			{
				if ( ! ($image_id = self::save_image_hash($hash)))
				{
					unlink($image_path);
					throw new \Libs_Image_Exception('fail upload image [hash]', __LINE__);
				}
			}
			else
			{
				$image_id = $image_hash->id;
			}

			$image_info = [
				'basename'      => $file['basename'],
				'ext'           => $file['extension'],
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
				throw new \Libs_Image_Exception('fail upload image', __LINE__);
			}

			return $image_info;
		}
		else
		{
			\Log::warning(print_r(\Upload::get_errors(),1));
			throw new \Libs_Image_Exception('fail upload image', __LINE__);
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
	 *
	 **/
	public static function get_images($offset, $limit, $ng = 0)
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

			if ( ! $image->delete())
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
}
