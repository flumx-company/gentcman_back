<?php

namespace Gentcmen\Http\Requests\Product;

use Gentcmen\Http\Requests\ApiFormRequest;

class CreateProductRequest extends ApiFormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize(): bool
    {
        return auth()->check();
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules(): array
    {
        return [
            'name' => 'required|string',
            'cost' => 'required|integer',
            'status_id' => 'required|integer',
            'category_ids' => 'required|array',
            'amount' => 'required|integer',
            'description' => 'required|string',
            'meta_title' => 'required|string',
            'meta_description' => 'required|string',
            'meta_keywords' => 'required|string',
            'content' => 'required', // |json
            'images_content' => 'required', // |json
            'banner_image' => 'required|string',
	    'product_info_image' => 'required|string',
            'published' => 'nullable'
        ];
    }
}
