<?php

namespace App\Http\Requests\Post\Catalogue;

use App\Http\Requests\BaseRequest;
use App\Repositories\Post\Catalogue\PostCatalogueRepository;

class DeleteMultipleRequest extends BaseRequest {
    
    private $post_catalogueRepository;

    public function __construct() {
        $this->post_catalogueRepository = app(PostCatalogueRepository::class);
    }

    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'ids' => 'required|array',
        ];
    }

    public function withValidator($validator) {
        $validator->after(function ($validator) {
            $ids = $this->input('ids');
            if (!empty($ids)) {
                foreach ($ids as $id) {
                    if (!is_numeric($id)) {
                        $validator->errors()->add('post_catalogue', 'PostCatalogue id must be numeric');
                    }
                    $post_catalogue = $this->post_catalogueRepository->findByld($id);
                    if (!$post_catalogue) {
                        $validator->errors()->add('post_catalogue', 'PostCatalogue not found with id: '.$id);
                    }
                }
            }
        });
    }
}
