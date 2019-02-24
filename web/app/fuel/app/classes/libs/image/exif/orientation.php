<?php
use \lsolesen\pel\PelJpeg;
use \lsolesen\pel\PelTag;
use \lsolesen\pel\PelIfd;
use \lsolesen\pel\PelEntryShort;

class Libs_Image_Exif_Orientation
{
	protected $file;
	protected $orientation;
	public function __construct($file, $orientation = 1)
	{
		$this->file = $file;
		$this->orientation = $orientation;
	}

	public function execute() {
		$filename = $this->file->path.$this->file->saved_as;

		try {
			$image = new PelJpeg($filename);

			if (($exif = $image->getExif()) === null)
			{
				return;
			}

			if (($tiff = $exif->getTiff()) === null)
			{
				return;
			}

			if (($ifd = $tiff->getIfd()) === null)
			{
				return;
			}

			if (($entry = $ifd->getEntry(PelTag::ORIENTATION)))
			{
				$entry->setValue(1);
			}
			else
			{
				return;
			}

			file_put_contents($filename, $image->getBytes());
		} catch (\Exception $e) {
			// エラーは無視
			\Log::debug($e->getMessage());
		}
	}

}
