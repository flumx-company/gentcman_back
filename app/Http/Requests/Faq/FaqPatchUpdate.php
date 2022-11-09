<?php

namespace Gentcmen\Http\Requests\Faq;

use Gentcmen\Http\Requests\ApiFormRequest;

class FaqPatchUpdate extends ApiFormRequest
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
            'status' => 'required|string|in:received,read,in the process,closed - successful,closed - problem not solved',
	    'message' => 'required|string'
        ];
    }
}
