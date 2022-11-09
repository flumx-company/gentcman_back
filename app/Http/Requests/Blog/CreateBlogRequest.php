<?php

namespace Gentcmen\Http\Requests\Blog;

use Gentcmen\Http\Requests\ApiFormRequest;

class CreateBlogRequest extends ApiFormRequest
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
            'title' => 'required',
            'short_content' => 'required',
            'content' => 'required',
            'type_id' => 'required',
            'image_title' => 'required',
            'category_id' => 'required',
            'meta_title' => 'required',
            'meta_description' => 'required',
            'meta_keywords' => 'required',
            'published' => 'nullable|boolean'
        ];
    }
}
