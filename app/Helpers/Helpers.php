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



    public static function generateShortCode($strength = 16)
    {
        $permitted_chars = '0123456789abcdefghijklmnopqrstuvwxyz';

        $input_length = strlen($permitted_chars);
        $random_string = '';
        for ($i = 0; $i < $strength; $i++) {
            $random_character = $permitted_chars[mt_rand(0, $input_length - 1)];
            $random_string .= $random_character;
        }

        return $random_string;
    }
}
