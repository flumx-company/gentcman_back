<?php


namespace Gentcmen\Http\Controllers;

use Illuminate\Http\Response;

/**
 * @OA\Info(
 *     title="Laravel Swagger Gentcmen API documentation",
 *     version="1.0.0",
 *     @OA\Contact(
 *         email="nikitosnov@gmail.com"
 *     ),
 *     @OA\License(
 *         name="Apache 2.0",
 *         url="http://www.apache.org/licenses/LICENSE-2.0.html"
 *     )
 * )
 *
 */

/**
 * @OA\SecurityScheme(
 *      securityScheme="bearerAuth",
 *      in="header",
 *      name="bearerAuth",
 *      type="http",
 *      scheme="bearer",
 *      bearerFormat="JWT",
 * )
 */

class ApiController extends Controller
{
    private int $statusCode = Response::HTTP_OK;

    /**
     * @return int
     */

    public function getStatusCode(): int
    {
        return $this->statusCode;
    }

    /**
     * @param int $statusCode
     */

    public function setStatusCode(int $statusCode): static
    {
        $this->statusCode = $statusCode;
        return $this;
    }

    public function respondOk($response = null, $message = 'Successful request'): \Illuminate\Http\JsonResponse
    {
        return $this->setStatusCode(Response::HTTP_OK)
                    ->respondWithSuccess($response, $message);
    }

    public function respondCreated($response = null, $message = 'Created successfully'): \Illuminate\Http\JsonResponse
    {
        return $this->setStatusCode(Response::HTTP_CREATED)
                    ->respondWithSuccess($response, $message);
    }

    public function respondNoContent(): \Illuminate\Http\JsonResponse
    {
        return $this->setStatusCode(Response::HTTP_NO_CONTENT)
                    ->respond([]);
    }

    public function respondBadRequest($details = null, $message = 'Bad Request'): \Illuminate\Http\JsonResponse
    {
        return $this->setStatusCode(Response::HTTP_BAD_REQUEST)
                    ->respondWithError($message, $details);
    }

    public function respondForbidden($details = null, $message = 'Forbidden'): \Illuminate\Http\JsonResponse
    {
        return $this->setStatusCode(Response::HTTP_FORBIDDEN)
                    ->respondWithError($message, $details);
    }

    public function respondNotFound($details = null, $message = 'Entity not found'): \Illuminate\Http\JsonResponse
    {
        return $this->setStatusCode(Response::HTTP_NOT_FOUND)
                    ->respondWithError($message, $details);
    }

    public function respondUnprocessableEntity($details = null, $message = 'Unprocessable entity'): \Illuminate\Http\JsonResponse
    {
        return $this->setStatusCode(Response::HTTP_UNPROCESSABLE_ENTITY)
                    ->respondWithError($message, $details);
    }

    public function respondWithSuccess($result, $message): \Illuminate\Http\JsonResponse
    {
        return $this->respond([
            'success' => true,
            'data'    => $result,
            'message' =>  $message
        ]);
    }

    public function respondWithError($message, $details = null): \Illuminate\Http\JsonResponse
    {
        return $this->respond([
            'success' => false,
            'error' => [
                'message' => $message,
                'details' => $details,
            ]
        ]);
    }

    public function respond(array $array): \Illuminate\Http\JsonResponse
    {
        return response()->json($array, $this->getStatusCode());
    }
}
