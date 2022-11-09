<?php

namespace Gentcmen\Http\Requests\Coupon;

use Gentcmen\Http\Requests\ApiFormRequest;

class CreateCouponRequest extends ApiFormRequest
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
            'name' => 'required|string',
            'cost' => 'required|integer',
            'discount' => 'required|integer',
            'entity_type' => 'nullable',
            'entity_id' => 'nullable',
        ];
    }
}
