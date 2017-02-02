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
			if ( empty($id) )
			{
				return null;
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
			return null;
		}
	}
}
