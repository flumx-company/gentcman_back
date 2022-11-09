<?php

namespace Gentcmen\Http\Requests\SendIdea;

use Gentcmen\Http\Requests\ApiFormRequest;

class SendIdeaRequest extends ApiFormRequest
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
            'theme' => 'required',
            'content' => 'required',
            'user_email' => 'required',
            'user_name' => 'required',
        ];
    }
}
