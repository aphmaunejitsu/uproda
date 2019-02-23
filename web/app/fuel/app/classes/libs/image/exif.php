<?php

class Libs_Image_Exif
{
	public static function __callstatic($method, $args)
	{
		try {
			$class = \Str::tr('Libs_Image_Exif_:method', ['method' => $method]);
			$ref = new ReflectionClass($class);
			$obj = $ref->newInstanceArgs($args);
			return $obj->execute();
		} catch (\Exception $e) {
			\Log::debug($e);
			return null;
		}
	}
}
