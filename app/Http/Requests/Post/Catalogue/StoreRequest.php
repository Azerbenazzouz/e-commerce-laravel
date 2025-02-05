<?php

namespace App\Http\Requests\Post\Catalogue;

use App\Http\Requests\BaseRequest;

class StoreRequest extends BaseRequest {

    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array {
        return [
            'name' => 'required|unique:post_catalogues,name',
            'canonical' => 'required|unique:post_catalogues,canonical',
            'parent_id' => 'nullable|integer|exists:post_catalogues,id',
            'publish' => 'required|gt:0',
            // 'description' => 'nullable|string',
            // 'meta_title' => 'nullable|string|max:255',
            // 'meta_keywords' => 'nullable|string',
            // 'meta_description' => 'nullable|string',
            // 'image' => 'nullable|string',
            // 'icon' => 'nullable|string',
            // 'album' => 'nullable|string',
            // 'order' => 'nullable|integer',
        ];
    }
}
