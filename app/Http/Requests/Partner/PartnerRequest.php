<?php

namespace Gentcmen\Http\Requests\Partner;

use Gentcmen\Http\Requests\ApiFormRequest;

class PartnerRequest extends ApiFormRequest
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
    public function rules()
    {
        return [
            'name' => 'required|string',
            'description' => 'required|string',
            'image_link' => 'required|string',
            'site_link' => 'required|string',
        ];
    }
}
