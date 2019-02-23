<?php

class Libs_Image_Exif_Info
{
	protected $file;
	public function __construct($file)
	{
		$this->file = $file;
	}

	public function execute()
	{
		$filename = $this->file->path.$this->file->saved_as;
		try {
			return exif_read_data($filename);
		} catch (\Exception $e) {
			\Log::warning($e->getMessage());
			return false;
		}
	}
}

