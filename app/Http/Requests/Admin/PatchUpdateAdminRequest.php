<?php

namespace Gentcmen\Http\Requests\Admin;

use Gentcmen\Http\Requests\ApiFormRequest;

class PatchUpdateAdminRequest extends ApiFormRequest
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
            'name' => 'nullable|string',
            'email' => 'nullable|string|email',
            'password' => 'nullable|min:8',
        ];
    }
}
