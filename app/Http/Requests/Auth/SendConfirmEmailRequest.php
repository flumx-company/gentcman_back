<?php

namespace Gentcmen\Http\Requests\Auth;

use Gentcmen\Http\Requests\ApiFormRequest;

class SendConfirmEmailRequest extends ApiFormRequest
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
            'user_id' => 'required|integer',
	    'backend_url' => 'required|string',
	    'redirect_url' => 'required|string'
        ];
    }
}

