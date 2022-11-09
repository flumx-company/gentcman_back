<?php

namespace Gentcmen\Http\Requests\DevelopmentIdea;

use Gentcmen\Http\Requests\ApiFormRequest;

class DevelopmentIdeaRequest extends ApiFormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
	    'user_name' => 'required|string',
            'email' => 'required|string|email',
            'idea' => 'required|string',
	    'theme' => 'required|string'
        ];
    }
}
