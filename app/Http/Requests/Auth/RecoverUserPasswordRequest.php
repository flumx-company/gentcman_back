<?php

namespace Gentcmen\Http\Requests\Auth;

use Gentcmen\Http\Requests\ApiFormRequest;

class RecoverUserPasswordRequest extends ApiFormRequest
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
            'password' => 'required|min:8',
            'confirm_password' => 'required|required_with:password|same:password',
            'hash' => 'required|string',
        ];
    }
}
