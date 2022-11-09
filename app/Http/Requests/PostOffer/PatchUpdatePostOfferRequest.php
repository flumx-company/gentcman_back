<?php

namespace Gentcmen\Http\Requests\PostOffer;

use Gentcmen\Http\Requests\ApiFormRequest;

class PatchUpdatePostOfferRequest extends ApiFormRequest
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
    public function rules()
    {
        return [
            'status' => 'required|string|in:received,read,for consideration,taken for implementation,rejected,implemented',
            'message' => 'nullable|string',
        ];
    }
}
