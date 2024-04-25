<?php

namespace App\Home\Helpers;

use Illuminate\Support\Facades\Storage;
use Illuminate\Http\File;

class Uploader
{
    public static function upload($file,$path)
    {
        $extension = $file->getClientOriginalExtension();

        $oldFileName = pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME);

        $fileName = $oldFileName .'-'. str_random(10).'.'.$extension;

        $file->move($path,$fileName);

        return $fileName;
    }

    public static function uploadToStorage($file, $path)
    {
    	$fileName = pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME) .'-'. str_random(10).'.'. $file->getClientOriginalExtension();
        Storage::put($fileName, file_get_contents($file->getPathname()));
        $filePath = storage_path() . '/app/' . $fileName;
        Storage::putFileAs($path, new File($filePath), $fileName);
        Storage::delete($fileName);
        return $fileName;
    }
}