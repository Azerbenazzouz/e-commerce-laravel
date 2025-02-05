<?php

namespace App\Http\Requests\Post\Catalogue;

use App\Http\Requests\BaseRequest;
use App\Repositories\Post\Catalogue\PostCatalogueRepository;

class DeleteRequest extends BaseRequest {

    private $post_catalogueRepository;

    public function __construct() {
        $this->post_catalogueRepository = app(PostCatalogueRepository::class);
    }

    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array {
        return [];
    }

    public function withValidator($validator) {
        $validator->after(function ($validator) {
            $post_catalogue = $this->post_catalogueRepository->findByld($this->route('post_catalogue'));
            if (!$post_catalogue) {
                $validator->errors()->add('post_catalogue', 'PostCatalogue not found');
            }
        });
    }
}
