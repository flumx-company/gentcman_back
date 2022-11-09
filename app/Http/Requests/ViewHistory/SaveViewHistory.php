<?php

namespace Gentcmen\Http\Requests\ViewHistory;

use Gentcmen\Http\Requests\ApiFormRequest;

class SaveViewHistory extends ApiFormRequest
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
            'product_ids' => 'required|array',
            'product_ids.*' => 'integer',
        ];
    }
}
