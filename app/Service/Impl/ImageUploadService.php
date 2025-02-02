<?php
namespace App\Service\Impl;

use Illuminate\Http\UploadedFile;
use Intervention\Image\ImageManager;
use Intervention\Image\Drivers\Imagick\Driver;

class ImageUploadService {

    private $auth;
    private $config;
    protected $uploadedFiles = [];
    protected $errors = [];


    public function __construct() {  
        /**
         * @var \Tymon\JWTAuth\JWTGaurd
         */
        $this->auth = auth('api');
        $this->config = config('upload.image');
    }

    // public function handleFileUpload($files, $folder = null, $pipelineKey = 'default', array $overrideOptions = []){
    //     $this->handleFileUpload = [];
    //     $this->errors = [];

    //     if(!is_array($files)) {
    //         return $this->handleSingleUpload($files, $folder, $pipelineKey, $overrideOptions);
    //     }

    //     return $this->handleMultipleUpload($files, $folder, $pipelineKey, $overrideOptions);
    // }

    public function handleSingleUpload(UploadedFile $file, $folder = null, $pipelineKey = 'default', array $overrideOptions = []){
        try {
            $result = $this->handleFileUpload2($file, $folder, $pipelineKey, $overrideOptions);
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


    
    protected function validateFile(UploadedFile $file) {
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
    }
    
    protected function handleFileUpload(UploadedFile $file, $folder = null, $pipelineKey = 'default', array $overrideOptions = []){
        $this->validateFile($file);
        $overrideOptions['storage'] = array_merge(
            $overrideOptions['storage'] ?? [],
            ['path' => $this->buildPath($folder)]
        );

        $image = ImageManager::imagick()->read($file);
        $image->originalFile = $file;
    }

    protected function buildPath($folder = null) {
        return trim($this->config['base_path']. '/' . $folder, '/');
    }
}