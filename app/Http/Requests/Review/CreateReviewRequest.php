<?php

namespace Gentcmen\Http\Requests\Review;

use Gentcmen\Http\Requests\ApiFormRequest;

class CreateReviewRequest extends ApiFormRequest
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
            'review' => 'required|string',
            'rating' => 'required|numeric|min:0|max:5',
        ];
    }
}
