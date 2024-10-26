<?php

namespace App\Traits;

use Intervention\Image\Facades\Image;
// use Image;
use Illuminate\Support\Facades\Log;


trait  HandleUploadImageTrait
{
    protected $path = 'upload/';
    public function verify($request)
    {
        return $request->has('image');
    }

    public function saveImage($request)
    {
        if ($this->verify($request)) {
            $image = $request->file('image');
            $name = time() . '.' . $image->getClientOriginalExtension();
            Image::make($image)->resize(300, 300)->save($this->path . $name);
            return $name;
        }
    }

    public function updateImage($request, $currentImage)
    {
        if ($this->verify($request)) {
            if (!empty($currentImage)) {
                $this->deleteImage($currentImage);
            }
            return $this->saveImage($request);
        }
        return $currentImage;
    }

    public function deleteImage($imageName)
    {
        if ($imageName && file_exists($this->path . $imageName)) {
            unlink($this->path . $imageName);
            Log::info('File deleted successfully: ' . $this->path . $imageName);
        } else {
            Log::info('File does not exist or imageName is empty: ' . $this->path . $imageName);
        }
    }
}
