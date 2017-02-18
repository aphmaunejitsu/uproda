<?php
class Libs_Image_Util
{
	public static function exif($filename)
	{
		try {
			return exif_read_data($filename);
		} catch (\Exception $e) {
			\Log::error($e->getMessage());
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

			$image = \Image::load($filename);
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
			\Log::warning($e);
			return;
		}
	}
}
