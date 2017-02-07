<?php
class Libs_Image
{
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
				$file['path'] = $file['path'].\Str::lower(\Str::sub($file['basename'],0,2)).'/';
			});

			\Upload::process([
				'auto_process'   => false,
				'path'           => DOCROOT.Libs_Config::get('board.dir'),
				'ext_whitelist'  => explode(',', Libs_Config::get('board.ext')),
				'type_whitelist' => explode(',', Libs_Config::get('board.type')),
				'max_size'       => Libs_Config::get('board.maxsize') * 1024 * 1024, //バイトに変換
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

				return $files[0];
			}
			else
			{
				return null;
			}
		} catch (\Exception $e) {
			\Log::error(__FILE__.': '.$e);
			return null;
		}
	}
}
