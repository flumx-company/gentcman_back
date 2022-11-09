<?php

namespace Gentcmen\Http\Requests\ReportProblem;

use Gentcmen\Http\Requests\ApiFormRequest;
use JetBrains\PhpStorm\ArrayShape;

class ReportProblemRequest extends ApiFormRequest
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
    #[ArrayShape(['theme' => "string", 'content' => "string", 'user_email' => "string"])] public function rules(): array
    {
        return [
            'theme' => 'required|string',
            'content' => 'required|string',
            'user_email' => 'required|string',
        ];
    }
}
