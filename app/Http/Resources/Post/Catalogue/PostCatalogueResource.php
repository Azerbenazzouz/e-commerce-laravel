<?php

namespace App\Http\Resources\Post\Catalogue;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class PostCatalogueResource extends JsonResource {
    
    public function toArray(Request $request): array {
        // dd($this);
        return [
            'id' => $this['id'],
            'name' => $this['name'],
            'slug' => $this['slug'],
            'publish' => $this['publish'],
        ];
    }
}
