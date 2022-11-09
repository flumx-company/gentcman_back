<?php

namespace Gentcmen\Http\Requests\DevelopmentIdea;

use Gentcmen\Http\Requests\ApiFormRequest;

class PatchUpdateDevelopmentIdeaRequest extends ApiFormRequest
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
            'status' => 'required|string|in:received,read,communication with the customer,consideration,taken for implementation,rejected,implemented',
            'message' => 'nullable|string',
        ];
    }
}
