<?php

namespace App\Home\Concerns\Support;

use Illuminate\Support\Facades\Storage;
use App\Home\Helpers\Uploader;

trait Imageable
{
    /**
     * will return the asset url for the image
     * @param $attr
     * @return null|string
     */
    public function asset($attr)
    {
        if(empty($this->{$attr}))
        {
            return null;
        }

        return asset($this->imageAttrs[$attr]). '/'.$this->{$attr};
    }

    /**
     * will return the file path for the image
     * @param $attr
     * @return string
     */
    public function path($attr)
    {
        return public_path($this->imageAttrs[$attr]) .'/' . $this->{$attr};
    }

    /**
     * remove the old picture from the attribute directory
     * and uploads a new image
     * @param $attr
     * @param $requestFile
     * @return mixed
     */
    public function upload($attr,$requestFile)
    {
        //this removes the old picture from directory if exists
        if (!is_null($this->{$attr}) && file_exists($this->path($attr)))
        {
            unlink($this->path($attr));
        }

        return Uploader::upload($requestFile,public_path($this->imageAttrs[$attr]));
    }

    public function storagePath($attr)
    {
        return $this->imageAttrs[$attr] . '/' . $this->{$attr};
    }

    public function uploadToStorage($attr, $requestFile)
    {
        //this removes the old picture from directory if exists
        if (!is_null($this->{$attr}) && Storage::exists($this->storagePath($attr)))
        {
            Storage::delete($this->storagePath($attr));
        }

        return Uploader::uploadToStorage($requestFile, $this->imageAttrs[$attr]);
    }
}