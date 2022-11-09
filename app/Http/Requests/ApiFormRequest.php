<?php


namespace Gentcmen\Http\Requests;


use Gentcmen\Http\Controllers\ApiController;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Http\Response;

class ApiFormRequest extends FormRequest
{
    protected ApiController $apiController;

    public function __construct(ApiController $apiController)
    {
        $this->apiController = $apiController;

        parent::__construct();
    }

    /**
     * Returns validations errors.
     *
     * @param Validator $validator
     * @throws  HttpResponseException
     */
    protected function failedValidation(Validator $validator)
    {
        throw new HttpResponseException(
            $this->apiController->respondUnprocessableEntity(
                'Validation Error.',
                $validator->errors(),
            )
        );
    }
}
