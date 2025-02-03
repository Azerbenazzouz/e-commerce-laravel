<?php
namespace App\Service\Impl;

use App\Pipelines\ImagePipelineManager;
use App\Pipelines\ImageWrapper;
use Intervention\Image\ImageManager;

class ImageUploadService {

    private $auth;
    private $config;
    protected $uploadedFiles = [];
    protected $errors = [];
    protected $pipelineManager;


    public function __construct(
        ImagePipelineManager $pipelineManager
    ) {  
        /**
         * @var \Tymon\JWTAuth\JWTGaurd
         */
        $this->auth = auth('api');
        $this->config = config('upload.image');
        $this->pipelineManager = $pipelineManager;
    }

    public function upload($files, $folder = null, $pipelineKey = 'default', array $overrideOptions = []){
        $this->uploadedFiles = [];
        $this->errors = [];
        if(!is_array($files)) {
            return $this->handleSingleUpload($files, $folder, $pipelineKey, $overrideOptions);
        }
        return $this->handleMultipleUpload($files, $folder, $pipelineKey, $overrideOptions);
    }

    public function handleSingleUpload($file, $folder = null, $pipelineKey = 'default', array $overrideOptions = []){

        try {
            $result = $this->handleFileUpload($file, $folder, $pipelineKey, $overrideOptions);
            // dd($result);
            $this->uploadedFiles[] = $result;
            return $this->generateResponse();
            
        } catch (\Exception $e) {
            $this->errors[] = [
                'file' => $file->getClientOriginalName(),
                'message' => $e->getMessage()
            ];
            return $this->generateResponse();
        }
    }

    public function handleMultipleUpload($files, $folder = null, $pipelineKey = 'default', array $overrideOptions = []){

    }

    protected function handleFileUpload($file, $folder = null, $pipelineKey = 'default', array $overrideOptions = []) {
        $this->validateFile($file);

        $overrideOptions['storage'] = array_merge(
            $overrideOptions['storage'] ?? [],
            ['path' => $this->buildPath($folder)]
        );

        // Create an Intervention Image instance
        $image = ImageManager::gd()->read($file);

        // Wrap the image in the ImageWrapper class and set the original file
        $wrappedImage = new ImageWrapper($image, $file);

        // Process the image through the pipeline
        $processImage = $this->pipelineManager->process($wrappedImage, $pipelineKey, $overrideOptions);

        return [
            'original_name' => $processImage->originalName,
            'name' => $processImage->fileName,
            'path' => $processImage->path
        ];
    }

    protected function buildPath($folder = null) {
        return trim($this->config['base_path']. '/' . $folder, '/');
    }


    protected function validateFile($file) {
        // Check if file is valid
        if(!$file->isValid()) {
            throw new \Exception('File is not valid');
        }
        // Check file size
        if($file->getSize() > ($this->config['max_size'] * 1024)) {
            throw new \Exception("File size exceeds {$this->config['max_size']} limit");
        }
        // Check mime type
        if(!in_array($file->getMimeType(), $this->config['allowed_mime_types'])) {
            throw new \Exception('File type not allowed');
        }
        
    }
    
    protected function generateResponse() {
        $response = [
            'succes' => count($this->errors) === 0,
            'files' => $this->uploadedFiles,
            'total_uploaded' => count($this->uploadedFiles),
        ];
        
        if(!empty($this->errors)) {
            $response['errors'] = $this->errors;
        }

        return $response;
    }
    
    
}