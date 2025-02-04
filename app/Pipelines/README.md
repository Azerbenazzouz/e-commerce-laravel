# Documentation for app/Pipelines

This directory contains classes related to image processing pipelines.

## AbstractPipeline.php

```php
<?php
namespace App\Pipelines;

use App\Pipelines\Interfaces\PipelineInterface;

abstract class AbstractPipeline implements PipelineInterface {
    protected $options;

    public function __construct(array $options = []) {
        $this->options = $options;
    }
}
```

Provides a base abstract class for pipelines, implementing the `PipelineInterface`.  It initializes with an optional array of `$options`.

## GenerateFileNamePipeline.php

```php
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
```

This pipeline generates a unique filename for the image using `Str::uuid()` if one hasn't already been set. It expects an `ImageWrapper` instance.

## ImagePipelineManager.php

```php
<?php
namespace App\Pipelines;

class ImagePipelineManager {
    protected $defaultPipeline = [
        'generate_filename' => GenerateFileNamePipeline::class,
        'storage' => StorageImagePipeline::class,
    ];

    public function process($image, string $configKey = 'default', array $overrideOptions = []) {
        // Ensure $image is an instance of ImageWrapper
        if (!$image instanceof ImageWrapper) {
            throw new \InvalidArgumentException('Expected instance of ImageWrapper');
        }

        $pipelineConfig = config("upload.image.pipelines.{$configKey}");
        
        $pipes = array_reverse(array_keys($pipelineConfig));
        $pipeline = array_reduce(
            $pipes,
            function($stack , $pipe) use($pipelineConfig, $overrideOptions) {
                if(!$pipelineConfig[$pipe]['enabled']) {
                    return $stack;
                }
                $pipelineClass = $this->defaultPipeline[$pipe] ?? null;
                if(!$pipelineClass) return $stack;

                return function ($passable) use ($stack, $pipelineClass, $pipelineConfig, $pipe, $overrideOptions) {
                    $options = array_merge(
                        $pipelineConfig[$pipe] ?? [],
                        $overrideOptions[$pipe] ?? []
                    );
                    $pipeline = new $pipelineClass($options);
                    return $pipeline->handle($passable, function($result) use ($stack) {
                        return $stack($result);
                    });
                };
            },
            function($passable){
                return $passable;
            }
        );

        return $pipeline($image);
    }
}
```

Manages the execution of image processing pipelines.  It retrieves pipeline configurations from the `upload.image.pipelines` config file.  It processes the image through the defined pipelines, allowing for configuration overrides.

## ImageWrapper.php

```php
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
```

A wrapper class for image data, holding the Intervention Image instance, the original uploaded file, and other relevant information like filename, path, and URL.

## StorageImagePipeline.php

```php
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

        return $next($image);
    }
}
```

This pipeline handles saving the image to storage.  It uses the configured disk and path, or defaults to the `upload.image.disk` configuration.  It sets the `$path` on the `ImageWrapper` instance.


## Interfaces/PipelineInterface.php

Declares the interface for pipeline classes.  Not included here as the content was not provided.