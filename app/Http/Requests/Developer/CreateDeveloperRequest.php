<?php

namespace Gentcmen\Http\Requests\Developer;

use Gentcmen\Http\Requests\ApiFormRequest;

class CreateDeveloperRequest extends ApiFormRequest
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
            'position' => 'required|in:developer,designer,owner,founder,analyst,pm,qa',
            'first_name' => 'required|string',
            'last_name' => 'required|string',
            'information' => 'nullable',
            'image_link' => 'required|string',
	    'resource_link' => 'string',
	    'email' => 'email|string'
        ];
    }
}
