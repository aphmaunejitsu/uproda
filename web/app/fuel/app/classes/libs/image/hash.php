<?php
class Libs_Image_Hash
{
	const HASH_NO_ERROR = 0;
	const HASH_NG = 1;

	public static function create($basename, $ext)
	{
		try {
			return self::create_by_file(\Libs_Image::build_real_image_path($basename, $ext));
		} catch (\Exception $e) {
			\Log::error($e);
			return null;
		}
	}

	public static function create_by_file($path)
	{
		$md5 = md5_file($path);
		$ng_char = [
		  '0', '1', '2', '3', '4', '5', '6', '7', '8', '9',
		  'A', 'B', 'C', 'D', 'E', 'F', 'G', 'H', 'I', 'J',
		  'K', 'L', 'M', 'N', 'O', 'P', 'Q', 'R', 'S', 'T',
		  'U', 'V',
		];

		$md5_arr = [];
		preg_match_all('/../', $md5, $md5_arr);

		$md5 = [];
		foreach (reset($md5_arr) as $index => $value)
		{
			$md5[$index] = intval($value, 16);
		}

		$md5[] = 0;
		$hash = '';
		for ($i = 0; $i < 26; $i++)
		{
			$num = ~~(5 * $i / 8);
			$index2 = ($md5[$num] + ($md5[$num + 1] << 8)) >> (5 * $i % 8) & 31;
			$hash .= $ng_char[$index2];
		}

		return $hash;
	}

	public static function get_all($limit, $offset)
	{
		$hash = \Model_Image_Hash::find(function($query) use($limit, $offset) {
			return $query->select('image_hash.id', 'image_hash.hash', 'image_hash.ng', 'image_hash.comment', [\DB::expr('count(images.id)'), 'image_count'])
						->join('images', 'left')
						->on('image_hash.id', '=', 'images.image_hash_id')
						->group_by('image_hash.id', 'image_hash.hash', 'image_hash.ng', 'image_hash.comment')
						->limit($limit)
						->offset($offset)
						->order_by(\DB::expr('count(images.id)'), 'desc');
		});

		return $hash;
	}

	/**
	 * ハッシュチェック
	 * NGなら例外になる
	 *
	 * @param string $basename
	 * @param string $ext
	 *
	 * @return success: \Model_Image_Hash, 取得なし: null
	 * @throws Libs_image_hash_exception (NG画像)
	 **/
	public static function check($basename, $ext)
	{
		if (($hash = self::get($basename, $ext)) === null)
		{
			return null;
		}

		if ($hash->ng == 1)
		{
			throw new \Libs_Image_Hash_Exception('Image is NG', self::HASH_NG);
		}

		return $hash;
	}

	public static function get($basename, $ext)
	{
		return self::get_by_hash(self::create($basename, $ext));
	}

	public static function get_by_hash($hash)
	{
		try {
			return Model_Image_Hash::find_one_by_hash($hash);
		} catch (\Exception $e) {
			\Log::error($e);
			return null;
		}
	}

	public static function get_with_image_by_hash($hash)
	{
		$image = \Model_Image_Hash::find(function($query) use($hash) {
			return $query->select('image_hash.id', 'image_hash.hash', 'image_hash.ng', 'image_hash.comment', 'images.basename', 'images.ext')
				->join('images', 'left')
				->on('image_hash.id', '=', 'images.image_hash_id')
				->where('image_hash.hash', $hash)
				->limit(1)->offset(0);
		});

		if (empty($image))
		{
			return null;
		}
		else
		{
			return reset($image);
		}
	}

	public static function save($basename, $ext, $ng = 0, $comment = null)
	{
		if (($hash_key = self::create($basename, $ext)) === null)
		{
			return null;
		}

		if (($hash = \Model_Image_Hash::find_one_by('hash', $hash_key)) === null)
		{
			//新規作成
			return self::save_by_hash($hash_key, $ng, $comment);
		}
		else
		{
			//更新
			$hash->set([
				'ng'      => $ng,
				'comment' => $comment,
			]);
			$hash->save();

			return $hash->id;
		}

		return null;
	}

	public static function update_by_hash($hash, $ng = 0, $comment = null)
	{
		try {
			if (($hash = \Model_Image_Hash::find_one_by('hash', $hash)) !== null)
			{
				$hash->set([
					'ng' => $ng,
					'comment' => $comment,
				]);
				if (($result = $hash->save()))
				{
					return $result;
				}
			}

			return null;
		} catch (\Exception $e) {
			\Log::error($e);
			throw new \Libs_Image_Hash_Exception('fail create Hash', __LINE__);
		}
	}

	public static function save_by_hash($hash, $ng = 0, $comment = null)
	{
		try {
			if (($result = \Model_Image_Hash::forge()->set(['hash' => $hash, 'ng' => 0, 'comment' => null])->save()))
			{
				return reset($result);
			}

			return null;
		} catch (\Exception $e) {
			\Log::error($e);
			throw new \Libs_Image_Hash_Exception('fail create Hash', __LINE__);
		}
	}
}

class Libs_Image_Hash_Exception extends Libs_Exception {}

