<?php
class Libs_Image
{
	public static function build_thumbnail_path($saved_to, $thumbnail_dir, $basename)
	{
		//サムネイルはjpg固定
		return \Str::tr(':to:thumbnail/:basename.jpg', [
			'to'        => $saved_to,
			'thumbnail' => $thumbnail_dir,
			'basename'  => $basename,
		]);
	}

	public static function build_image_path($saved_to, $saved_as)
	{
		return \Str::tr(':to:as', [
			'to' => $saved_to,
			'as' => $saved_as,
	    ]);
	}

	public static function get_two_char_from_basename($basename)
	{
		return \Str::lower(\Str::sub($basename, 0, 2));
	}


	public static function thumbnail($file)
	{
		try {
			$thumbnail = Libs_Config::get('board.thumbnail.dir');
			if ( ! file_exists(\Arr::get($file, 'saved_to', null).$thumbnail.'/'))
			{
				\File::create_dir(\Arr::get($file, 'saved_to'), $thumbnail, 0777);
			}

			$image_path = self::build_image_path(\Arr::get($file, 'saved_to', null), \Arr::get($file, 'saved_as', null));

			$image = \Image::load($image_path)->crop_resize(Libs_Config::get('board.thumbnail.width'), Libs_Config::get('board.thumbnail.height'));
			$save_path = self::build_thumbnail_path(\Arr::get($file, 'saved_to', null), $thumbnail, \Arr::get($file, 'basename', null));
			$image->save($save_path);

		} catch (\Exception $e) {
			\Log::error(__FILE__.'('.__LINE__.'): '.$e->getMessage());
			throw new \Exception('fail create thumbnail');
		}
	}

	/**
	 * ファイルIDからファイルを見つける
	 * @param $id string
	 * @return string success file_path, fail null
	 **/
	public static function search($id)
	{
		try {
			if (empty($id))
			{
				return null;
			}

			$id = htmlspecialchars($id);

			$v = \Validation::forge();
			$v->add_field('image', 'image file', 'required|valid_string[alpha,numeric,dashes]');
			if ( ! $v->run(['image' => $id], true))
			{
				throw new \Exception('validate error: '.$id);
			}

			if ( ! ($image = Model_Image::find_one_by('basename', $id)))
			{
				throw new \Exception('image not fond: '.$id);
			}

			$path = Str::tr(':dir/:subdir/:basename.:ext',[
				'dir'      => Libs_Config::get('board.dir'),
				'subdir'   => \Str::lower(\Str::sub($image->basename, 0, 2)),
				'basename' => $image->basename,
				'ext'      => $image->ext,
			]);

			if (\File::exists(DOCROOT.$path))
			{
				return $path;
			}

			return null;
		} catch (\Exception $e) {
			\Log::warning(__FILE__.':'.$e->getMessage());
			return null;
		}
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

				$image = Model_Image::forge()->set([
					'basename'   => $file['basename'],
					'ext'        => $file['extension'],
					'original'   => $file['name'],
					'delkey'     => htmlspecialchars(\Input::post('delky')),
					'mimetype'   => $file['mimetype'],
					'size'       => $file['size'],
					'comment'    => htmlspecialchars(\Input::post('comment')),
					'ip'         => \Input::real_ip(),
					'ng'         => 0,
				]);

				//保存失敗
				if ( ! ($result = $image->save()))
				{
					//ゴミ掃除
					unlink(DOCROOT.Libs_Config::get('board.dir').'/'.$file['saved_as']);
					throw new \Exception('faild save data');
				}

				return $file;
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
				false,
				[['ng', '=', 0]]
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
}
