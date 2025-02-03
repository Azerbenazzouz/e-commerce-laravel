<?php
namespace App\Pipelines;

use Intervention\Image\Image;
use Illuminate\Http\UploadedFile;

class ImageWrapper {
    public $image; // Intervention\Image\Image instance
    public $originalFile; // Illuminate\Http\UploadedFile instance
    public $originalName;
    public $fileName;
    public $path;
    public $url;

    public function __construct(Image $image, UploadedFile $originalFile = null) {
        $this->image = $image;
        $this->originalFile = $originalFile;
    }
}