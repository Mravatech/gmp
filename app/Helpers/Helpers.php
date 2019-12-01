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
    public static function uploadFile($file, $destination = '/user_data')
    {
        $filename = $file->getClientOriginalName();
        $filename = uniqid('', true) . '_' . $filename;
        $path = $destination . DIRECTORY_SEPARATOR . 'user_files' . DIRECTORY_SEPARATOR . 'cnic' . DIRECTORY_SEPARATOR;
        $destinationPath = base_path('public') . $path; // upload path
        File::makeDirectory($destinationPath, 0777, true, true);
        $file->move($destinationPath, $filename);
        return $path;
    }


        /**
         * Get the path to the public folder.
         *
         * @param  string $path
         * @return string
         */
         public static function public_path($path = '')
         {
             return env('PUBLIC_PATH', base_path('public')) . ($path ? '/' . $path : $path);
         }
}
