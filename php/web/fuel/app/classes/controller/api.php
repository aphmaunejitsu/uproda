<?php
class Controller_Api extends \Controller_Rest
{
	protected $no_data_status = 404;
	protected $no_method_status = 404;
	protected $format = "json";
	protected $_supported_formats = [
		'html' => 'text/html',
		'json' => 'application/json'
	];

	public function before()
	{
		parent::before();
	}

	public function after($response)
	{
		if (is_array($response))
		{
		    $response = $this->response($response);
		}

		if ( ! $response instanceof Response)
		{
		    $response = $this->response;
		}

		return parent::after($response);
	}
}
