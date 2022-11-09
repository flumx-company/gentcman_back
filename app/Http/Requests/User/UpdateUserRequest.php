<?php

namespace Gentcmen\Http\Requests\User;

use Gentcmen\Http\Requests\ApiFormRequest;

class UpdateUserRequest extends ApiFormRequest
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
            'name' => 'required',
            'surname' => 'required',
            //'avatar' => 'required',
            'phone' => 'required',
            'country' => 'required',
            'city' => 'required',
            //'state' => 'required',
            'apartment' => 'required',
            'house' => 'required',
            'street' => 'required',
            'email' => 'unique:users'
        ];
    }
}
