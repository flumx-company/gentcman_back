<?php

namespace Gentcmen\Http\Requests\Banner;

use Gentcmen\Http\Requests\ApiFormRequest;

class CreateBannerRequest extends ApiFormRequest
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
	    'button_link' => 'required|string',
	    'button_text' => 'required|string',
	    'link_desktop' => 'required|string',
	    'link_mobile' => 'required|string',
	    'description' => 'required|string',
            'published' => 'nullable|boolean',
        ];
    }
}
