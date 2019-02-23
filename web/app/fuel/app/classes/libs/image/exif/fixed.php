<?php

class Libs_Image_Exif_Fixed
{
	protected $file;
	protected $exif;
	public function __construct($file, $exif)
	{
		$this->file = $file;
		$this->exif = $exif;
	}

	public function execute() {
		$filename = $this->file->path.$this->file->saved_as;
		$exif = $this->exif;
		if ($exif === false)
		{
			return;
		}

		if (($orientation = \Arr::get($exif, 'Orientation', null)) === null)
		{
			return;
		}

		try {

			$image = \Image::load($filename);
			switch ($orientation)
			{
			    case 0:
			    case 1:
			    return;

			    case 2:
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
			}

			return $image->save($filename);
		} catch (\Exception $e) {
			\Log::warning($e->getMessage());
			return;
		}
	}
}
