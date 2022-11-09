<?php

namespace Gentcmen\Http\Requests\ReportProblem;

use Gentcmen\Http\Requests\ApiFormRequest;

class ReportProblemPatchUpdate extends ApiFormRequest
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
            'status' => 'required|string|in:received,read,consideration,in the process of fixing,fixed,rejected',
	    'message' => 'required|string'
        ];
    }
}
