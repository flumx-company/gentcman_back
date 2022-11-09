<?php

namespace Gentcmen\Http\Requests\PostOffer;

use Gentcmen\Http\Requests\ApiFormRequest;
//use Illuminate\Foundation\Http\FormRequest;

class CreatePostOfferRequest extends ApiFormRequest
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
    public function rules()
    {
        return [
            'theme' => 'required|string',
	    'user_name' => 'required|string',
            'text' => 'required|string',
            'email' => 'required|string|email',
        ];
    }
}
