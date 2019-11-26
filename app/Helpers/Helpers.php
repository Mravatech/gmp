<?php


namespace App\Helpers;


use Illuminate\Support\Facades\File;

class Helpers
{

    /**
     * Upload file
     * @param $file
     * @param $destination
     * @return string
     */
    public static function uploadFile($file, $destination)
    {
        $filename = $file->getClientOriginalName();
        $filename = uniqid('', true) . '_' . $filename;
        $path = $destination . DIRECTORY_SEPARATOR . 'user_files' . DIRECTORY_SEPARATOR . 'cnic' . DIRECTORY_SEPARATOR;
        $destinationPath = public_path($path); // upload path
        File::makeDirectory($destinationPath, 0777, true, true);
        $file->move($destinationPath, $filename);
        return $path;
    }
}
