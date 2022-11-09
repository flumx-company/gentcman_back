<?php

namespace Gentcmen\Http\Requests\Payment;

use Gentcmen\Http\Requests\ApiFormRequest;

class PaymentRequest extends ApiFormRequest
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
            'billing_email' => 'required|string',
            'billing_phone' => 'required|string',
            'billing_delivery_type' => 'required|integer',
            'billing_payment_type' => 'required|integer',
	    'billing_city' => 'required|string',
            'billing_user_name' =>'required|string',
            'coupon_id' => 'nullable|numeric',
            'products' => 'required|array',
            'products.*.id' => 'required|integer',
            'products.*.quantity' => 'required|integer',
            'products.*.coupon_id' => 'nullable|integer',
        ];
    }
}
