<?php
class Libs_Exception extends \Exception
{
	public function __toString()
	{
		return get_class($this).' ['.$this->code.'] '.$this->message;
	}
}
