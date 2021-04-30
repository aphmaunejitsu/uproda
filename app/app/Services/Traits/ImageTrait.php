<?php

namespace App\Services\Traits;

use Illuminate\Http\UploadedFile;

trait ImageTrait
{

    public function getHash(UploadedFile $file): string
    {
        $md5 = md5_file($file->path());
        $ng_char = [
          '0', '1', '2', '3', '4', '5', '6', '7', '8', '9',
          'A', 'B', 'C', 'D', 'E', 'F', 'G', 'H', 'I', 'J',
          'K', 'L', 'M', 'N', 'O', 'P', 'Q', 'R', 'S', 'T',
          'U', 'V',
        ];

        $md5_arr = [];
        preg_match_all('/../', $md5, $md5_arr);

        $md5 = [];
        foreach (reset($md5_arr) as $index => $value) {
            $md5[$index] = intval($value, 16);
        }

        $md5[] = 0;
        $hash = '';
        for ($i = 0; $i < 26; $i++) {
            $num = ~~(5 * $i / 8);
            $index2 = ($md5[$num] + ($md5[$num + 1] << 8)) >> (5 * $i % 8) & 31;
            $hash .= $ng_char[$index2];
        }

        return $hash;
    }

    public function generateBasename(int $length = 8): string
    {
        return substr(str_shuffle('1234567890abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ'), 0, $length);
    }
}
