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