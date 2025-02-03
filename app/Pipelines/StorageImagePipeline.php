<?php
namespace App\Pipelines;
use Illuminate\Support\Facades\Storage;

class StorageImagePipeline extends AbstractPipeline {
    public function handle($image, \Closure $next) {
        // Ensure $image is an instance of ImageWrapper
        if (!$image instanceof ImageWrapper) {
            throw new \InvalidArgumentException('Expected instance of ImageWrapper');
        }

        $disk = $this->options['disk'] ?? config('upload.image.disk');
        $path = trim($this->options['path'] . '/' . $image->fileName, '/');
        
        // Save the image to the specified disk
        Storage::disk($disk)->put($path, (string)$image->image->encode());

        // Set the path and URL on the ImageWrapper object
        $image->path = $path;
        $image->url = Storage::disk($disk)->url($path);

        return $next($image);
    }
}