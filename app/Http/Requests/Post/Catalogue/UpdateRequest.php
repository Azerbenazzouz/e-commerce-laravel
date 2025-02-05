<?php

namespace App\Http\Requests\Post\Catalogue;

use App\Http\Requests\BaseRequest;
use App\Repositories\Post\Catalogue\PostCatalogueRepository;
use Illuminate\Validation\Rule;

class UpdateRequest extends BaseRequest {

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
            'name' => 'required|unique:post_catalogues,name,'.$this->route('post_catalogue'),
            'canonical' => [
                'required',
                Rule::unique('post_catalogues', 'canonical')->ignore($this->route('post_catalogue')),
            ],
            'parent_id' => [
                'nullable',
                'integer',
                'exists:post_catalogues,id',
                function ($attribute, $value, $fail) {
                    if ($value == $this->route('post_catalogue')) {
                        $fail('Le catalogue parent ne peut pas être le même que le catalogue actuel.');
                    }
                },
            ],
            'publish' => 'required|gt:0',
        ];
    }

    public function withValidator($validator) {
        $validator->after(function ($validator) {
            $existingPostCatalogue = $this->post_catalogueRepository->findByld($this->route('post_catalogue'));
            if (!$existingPostCatalogue) {
                $validator->errors()->add('post_catalogue', 'PostCatalogue non trouvé');
            }
        });
    }
}
