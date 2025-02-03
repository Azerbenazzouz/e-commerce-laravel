<?php
namespace App\Pipelines;
use Illuminate\Support\Str;

class GenerateFileNamePipeline extends AbstractPipeline {
    public function handle($image, \Closure $next) {
        // Ensure $image is an instance of ImageWrapper
        if (!$image instanceof ImageWrapper) {
            throw new \InvalidArgumentException('Expected instance of ImageWrapper');
        }

        // Generate a unique filename if it doesn't exist
        if (!isset($image->fileName)) {
            $originalName = $image->originalFile->getClientOriginalName();
            $extension = $image->originalFile->getClientOriginalExtension();

            $image->fileName = Str::uuid() . '.' . $extension;
            $image->originalName = $originalName;
        }

        return $next($image);
    }
}