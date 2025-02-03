<?php
namespace App\Pipelines\Interfaces;

interface PipelineInterface {
    public function handle($image, \Closure $next);
}