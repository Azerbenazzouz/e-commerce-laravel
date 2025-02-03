<?php
namespace App\Pipelines;

use App\Pipelines\Interfaces\PipelineInterface;

abstract class AbstractPipeline implements PipelineInterface {
    protected $options;

    public function __construct(array $options = []) {
        $this->options = $options;
    }
}