<?php

namespace Gentcmen\Http\Requests\ManageCoupon;

use Gentcmen\Http\Requests\ApiFormRequest;

class DeleteCouponRequest extends ApiFormRequest
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
            'ids' => 'nullable|array',
            'forceDelete' => 'nullable|array'
        ];
    }
}
