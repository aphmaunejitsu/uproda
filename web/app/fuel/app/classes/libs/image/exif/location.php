<?php
use \lsolesen\pel\PelJpeg;
use \lsolesen\pel\PelTag;
use \lsolesen\pel\PelIfd;
use \lsolesen\pel\PelExif;
use \lsolesen\pel\PelTiff;
use \lsolesen\pel\PelEntryUserComment;
use \lsolesen\pel\PelEntryAscii;
use \lsolesen\pel\PelEntryByte;
use \lsolesen\pel\PelEntryRational;

class Libs_Image_Exif_Location
{
	protected $file;
	protected $longitude;
	protected $latitude;
	protected $altitude;

	public function __construct($file, $longitude, $latitude, $altitude)
	{
		$this->file = $file;
		$this->longitude = $longitude;
		$this->latitude = $latitude;
		$this->altitude = $altitude;
	}

	protected function convertDecimalToDMS($degree)
	{
    if ($degree > 180 || $degree < - 180) {
        return null;
    }
    $degree = abs($degree); // make sure number is positive
                            // (no distinction here for N/S
                            // or W/E).
    $seconds = $degree * 3600; // Total number of seconds.
    $degrees = floor($degree); // Number of whole degrees.
    $seconds -= $degrees * 3600; // Subtract the number of seconds
                                 // taken by the degrees.
    $minutes = floor($seconds / 60); // Number of whole minutes.
    $seconds -= $minutes * 60; // Subtract the number of seconds
                               // taken by the minutes.
    $seconds = round($seconds * 100, 0); // Round seconds with a 1/100th
                                         // second precision.
    return [
        [
            $degrees,
            1
        ],
        [
            $minutes,
            1
        ],
        [
            $seconds,
            100
        ]
    ];
	}

	function execute()
	{
		$filename = $this->file->path.$this->file->saved_as;
		$latitude = $this->latitude;
		$longitude = $this->longitude;
		$altitude = $this->altitude;

		try {
			$jpeg = new PelJpeg($filename);

			if (($exif = $jpeg->getExif()) === null)
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


			$gps_ifd = new PelIfd(PelIfd::GPS);
			$ifd->addSubIfd($gps_ifd);
			list ($hours, $minutes, $seconds) = $this->convertDecimalToDMS($latitude);
			$latitude_ref = ($latitude < 0) ? 'S' : 'N';
			$gps_ifd->addEntry(new PelEntryAscii(PelTag::GPS_LATITUDE_REF, $latitude_ref));
			$gps_ifd->addEntry(new PelEntryRational(PelTag::GPS_LATITUDE, $hours, $minutes, $seconds));
			list ($hours, $minutes, $seconds) = $this->convertDecimalToDMS($longitude);
			$longitude_ref = ($longitude < 0) ? 'W' : 'E';
			$gps_ifd->addEntry(new PelEntryAscii(PelTag::GPS_LONGITUDE_REF, $longitude_ref));
			$gps_ifd->addEntry(new PelEntryRational(PelTag::GPS_LONGITUDE, $hours, $minutes, $seconds));
			$gps_ifd->addEntry(new PelEntryRational(PelTag::GPS_ALTITUDE, [
					abs($altitude),
					1
			]));
			$gps_ifd->addEntry(new PelEntryByte(PelTag::GPS_ALTITUDE_REF, (int) ($altitude < 0)));
			file_put_contents($filename, $jpeg->getBytes());
		} catch (\Exception $e) {
			// エラーは無視
			\Log::debug($e->getMessage());
		}
	}

}
