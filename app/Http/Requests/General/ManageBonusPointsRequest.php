<?php

namespace Gentcmen\Http\Requests\General;

use Gentcmen\Http\Requests\ApiFormRequest;

class ManageBonusPointsRequest extends ApiFormRequest
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
            'amount' => 'required|integer',
            'operation' => 'required|string|in:increment,decrement'
        ];
    }
}
