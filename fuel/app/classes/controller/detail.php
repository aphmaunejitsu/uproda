<?php
/**
 * 画像表示用のクラス
 * 利用すると少し重くなる
 * 利用する場合は以下をすること
 *   .htaccessの35-37, 40-42行を有効
 *   routes.phpの10,11行目を有効
 **/
class Controller_Detail extends Controller
{
	public function before()
	{
		parent::before();
	}

	public function action_index($basename = null)
	{
		try {
			Libs_Image::check_id($basename);
			$image_data = Libs_Image::get($basename);
			//TODO 画像アクセスのロギング
			\Image::load(Libs_Image::build_real_image_path($image_data->basename, $image_data->ext))->output();
		} catch (\Exception $e) {
			\Log::error($e);
			\Image::load('/assets/global/img/404.jpg ')->output();
		}
	}

	public function action_thumbnail($basename = null)
	{
		try {
			Libs_Image::check_id($basename);
			$image_data = Libs_Image::get($basename);
			//TODO 画像アクセスのロギング
			\Image::load(Libs_Image::build_real_thumbnail_path($image_data->basename))->output();
		} catch (\Exception $e) {
			\Log::error($e);
			\Image::load('/assets/global/img/404.jpg ')->output();
		}
	}
}
