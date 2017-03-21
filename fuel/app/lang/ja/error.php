<?php
return [
	'Libs_Image_Exception' => [
		\Libs_Image::IMAGE_NOT_FOUND          => '画像が見つかりません',
		\Libs_Image::IMAGE_FAILED_CREATE      => 'アップロードに失敗したっぽい',
		\Libs_Image::IMAGE_OVER_MAXSIZE       => 'アップロードするサイズが大きいっぽい',
		\Libs_Image::IMAGE_UPLOAD_NG          => 'きもい画像とかアップロードさせませんし',
		\Libs_Image::IMAGE_FAILED_CREATE_HASH => 'アップロードに失敗したっぽい',
	],
	'Libs_Captcha_Exception' => [
		\Libs_Captcha::CAPTCHA_ERROR          => 'アップロードに失敗したっぽい',
	]
];
