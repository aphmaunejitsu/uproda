<?php
class Controller_Captcha extends \Controller
{
	public function action_index()
	{
			throw new HttpNotFoundException();
	}

	public function get_image()
	{
		return \Captcha::forge('simplecaptcha')->image();
	}
}
