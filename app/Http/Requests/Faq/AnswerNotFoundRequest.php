<?php

namespace Gentcmen\Http\Requests\Faq;

use Gentcmen\Http\Requests\ApiFormRequest;

class AnswerNotFoundRequest extends ApiFormRequest
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
    public function rules(): array
    {
        return [
            'theme' => 'required|string',
            'content' => 'required',
            'email' => 'required|string|email',
            'name' => 'required|string'
        ];
    }
}
