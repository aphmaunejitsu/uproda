<?php
class Libs_Image
{
	/**
	 * ファイルIDからファイルを見つける
	 * @param $id string
	 * @return mix success file_path, fail null
	 **/
	public static function search($id)
	{
		try {
			if (empty($id))
			{
				return null;
			}

			$v = \Validation::forge();
			$v->add_field('image', 'image file', 'required|valid_string[alpha,numeric]');
			if ( ! $v->run(['image' => $id], true))
			{
				throw new \Exception('validate error:'.$id);
			}

			$path = Str::tr(':dir/:id.*',[
				'dir' => Libs_Config::get('board.dir'),
				'id'  => $id,
			]);

			foreach (glob($path) as $file)
			{
				//IDは一意なので、1つでも見つかった場合はリターン
				Log::debug(__FILE__.' '.$file);
				return $file;
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
				$count = 1;
				$image = Model_Image::find([
					'select'   => ['id', 'name'],
					'order_by' => ['id' => 'desc'],
					'limit'    => 1,
				]);

				if ($image)
				{
					$count = reset($image)->id + 1;
				}

				$file['basename'] = str_pad($count, Libs_Config::get('board.pad'), '0', STR_PAD_LEFT);
			});

			\Upload::process([
				'auto_process'   => false,
				'path'           => DOCROOT.Libs_Config::get('board.dir'),
				'ext_whitelist'  => explode(',', Libs_Config::get('board.ext')),
				'type_whitelist' => explode(',', Libs_Config::get('board.type')),
				'max_size'       => Libs_Config::get('board.maxsize') * 1024 * 1024,
				'prefix'         => Libs_Config::get('board.prefix'),
			]);

			if (\Upload::is_valid())
			{
				\Upload::save();
				$files = \Upload::get_files();
				$file = reset($files);

				$image = Model_Image::forge()->set([
					'name'       => $file['saved_as'],
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
				\Log::debug(print_r(\Upload::get_errors(),1));
				return null;
			}
		} catch (\Exception $e) {
			\Log::error(__FILE__.': '.$e);
			return null;
		}
	}
}
