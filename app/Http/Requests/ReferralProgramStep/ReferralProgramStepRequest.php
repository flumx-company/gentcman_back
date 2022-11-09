<?php

namespace Gentcmen\Http\Requests\ReferralProgramStep;

use Gentcmen\Http\Requests\ApiFormRequest;

class ReferralProgramStepRequest extends ApiFormRequest
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
            'goal' => 'required|integer',
            'reward' => 'required|integer',
        ];
    }
}
