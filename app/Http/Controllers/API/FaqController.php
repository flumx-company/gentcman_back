<?php

namespace Gentcmen\Http\Controllers\API;

use Gentcmen\Http\Controllers\ApiController;
use Gentcmen\Http\Requests\Faq\AnswerNotFoundRequest;
use Gentcmen\Http\Requests\Faq\FaqPatchUpdate;
use Gentcmen\Http\Requests\General\DestroyItemsRequest;
use Gentcmen\Jobs\SendFaqAnswerNotFoundJob;
use Gentcmen\Models\Faq;
use Gentcmen\Models\User;
use Gentcmen\Notifications\AnswerNotFoundNotification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Notification;
use Illuminate\Pipeline\Pipeline;

class FaqController extends ApiController
{
    /**
     * @OA\Get(
     *     path="/api/v1/client/user/faq-history",
     *     operationId="Fetch user faq history",
     *     tags={"User"},
     *     summary="Fetch user faq history",
     *     security={ {"bearerAuth": {}} },
     *     @OA\Parameter(
     *         name="page",
     *         in="query",
     *         description="The page number",
     *         required=false,
     *         @OA\Schema(
     *             type="integer",
     *         )
     *     ),
     *     @OA\Parameter(
     *         name="limit",
     *         in="query",
     *         description="Limit records",
     *         required=false,
     *         @OA\Schema(
     *             type="integer",
     *         )
     *     ),
     *     @OA\Response(
     *         response="200",
     *         description="User's faq history data fetched successfully"
     *     ),
     *     @OA\Response(
     *         response="400",
     *         description="Something went wrong"
     *     )
     * )
     *
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\JsonResponse
     */

    public function fetchFaqHistory(Request $request): \Illuminate\Http\JsonResponse
    {
        $faqHistory = Faq::where('user_id', auth()->id())
            ->paginate($request->query('limit') ? $request->query('limit') : 10);

        return $this->respondOk($faqHistory);
    }

     /**
     * @OA\Get(
     *     path="/api/v1/client/faqs",
     *     operationId="Fetch user's Faqs",
     *     tags={"Faqs"},
     *     summary="Fetch user's Faqs",
     *     security={ {"bearerAuth": {}} },
     *     @OA\Parameter(
     *         name="page",
     *         in="query",
     *         description="The page number",
     *         required=false,
     *         @OA\Schema(
     *             type="integer",
     *         )
     *     ),
     *     @OA\Response(
     *         response="200",
     *         description="Product category fetched successfully"
     *     ),
     *     @OA\Response(
     *         response="404",
     *         description="Product category not found"
     *     ),
     *     @OA\Response(
     *         response="400",
     *         description="Something went wrong"
     *     )
     * )
     *
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\JsonResponse
     */

    public function index(): \Illuminate\Http\JsonResponse
    {
        $faqs = Faq::where('user_id', auth('api')->id())->paginate(10);

        return $this->respondOk($faqs);
    }


    /**
     * @OA\Get(
     *     path="/api/v1/admin/faqs",
     *     operationId="Fetch by query params",
     *     tags={"Faqs"},
     *     summary="Fetch faqs by query params",
     *     @OA\Parameter(
     *         name="page",
     *         in="query",
     *         description="The page number",
     *         required=false,
     *         @OA\Schema(
     *             type="integer",
     *         )
     *     ),
     *     @OA\Parameter(
     *         name="theme",
     *         in="query",
     *         description="Faq theme",
     *         required=false,
     *         @OA\Schema(
     *             type="string",
     *         )
     *     ),
     *     @OA\Parameter(
     *         name="user_name",
     *         in="query",
     *         description="user name goes here",
     *         required=false,
     *         @OA\Schema(
     *             type="string",
     *         )
     *     ),
     *     @OA\Parameter(
     *         name="email",
     *         in="query",
     *         description="user's email",
     *         required=false,
     *         @OA\Schema(
     *             type="string",
     *         )
     *     ),
     *     @OA\Parameter(
     *         name="status",
     *         in="query",
     *         description="status of faq",
     *         required=false,
     *         example="new|active|closed",
     *         @OA\Schema(
     *             type="string",
     *         )
     *     ),
     *     @OA\Parameter(
     *         name="created_at",
     *         in="query",
     *         description="date",
     *         required=false,
     *         @OA\Schema(
     *             type="date",
     *         )
     *     ),
     *     @OA\Response(
     *         response="200",
     *         description="Data received successfully"
     *     ),
     *     @OA\Response(
     *         response="400",
     *         description="Something went wrong"
     *     )
     * )
     *
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\JsonResponse
     */

    public function adminIndex(Request $request): \Illuminate\Http\JsonResponse
    {
        $query = app(Pipeline::class)
            ->send(Faq::query())
            ->through([
                \Gentcmen\Http\Controllers\API\FaqQueryFilters\Theme::class,
                \Gentcmen\Http\Controllers\API\FaqQueryFilters\UserName::class,
                \Gentcmen\Http\Controllers\API\FaqQueryFilters\Email::class,
                \Gentcmen\Http\Controllers\API\FaqQueryFilters\Status::class,
                \Gentcmen\Http\Controllers\API\FaqQueryFilters\CreatedAt::class,
            ])
            ->thenReturn();

        $faqs = $query->paginate(10);

        return $this->respondOk($faqs);
    }

    /**
     * @OA\Get(
     *     path="/api/v1/admin/faqs/{faq}",
     *     operationId="Fetch faq by id",
     *     tags={"Faqs"},
     *     summary="Fetch faqs by id",
     *     @OA\Parameter(
     *         name="faq",
     *         in="path",
     *         description="Faq's id",
     *         required=true,
     *         @OA\Schema(
     *             type="integer",
     *         )
     *     ),
     *     @OA\Response(
     *         response="200",
     *         description="Data received successfully"
     *     ),
     *     @OA\Response(
     *         response="404",
     *         description="Entity not found"
     *     ),
     *     @OA\Response(
     *         response="400",
     *         description="Something went wrong"
     *     )
     * )
     *
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\JsonResponse
     */

    public function getById(Faq $faq): \Illuminate\Http\JsonResponse
    {
        return $this->respondOk($faq);
    }

    /**
     * @OA\Patch (
     *     path="/api/v1/admin/faqs/{faq}",
     *     operationId="Update faq",
     *     tags={"Faqs"},
     *     summary="Update faq",
     *     security={ {"bearerAuth": {}} },
     *     @OA\Parameter(
     *         name="faq",
     *         in="path",
     *         description="Faq's id",
     *         required=true,
     *         @OA\Schema(
     *             type="integer",
     *         )
     *     ),
     *     @OA\Response(
     *         response="200",
     *         description="Faq is updated successfully"
     *     ),
     *     @OA\Response(
     *         response="400",
     *         description="Something went wrong"
     *     ),
     *     @OA\Response(
     *         response="404",
     *         description="Faq not found with provided id"
     *     ),
     *     @OA\Response(
     *         response="422",
     *         description="Validation error"
     *     ),
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *              @OA\Property(
     *                  property="status",
     *                  title="Status of faq",
     *                  description="Status of faq",
     *                  example="active"
     *              )
     *         )
     *     )
     * )
     *
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\JsonResponse
     */

    public function patchUpdate(FaqPatchUpdate $request, Faq $faq): \Illuminate\Http\JsonResponse
    {
        $faq->update($request->validated());
        return $this->respondOk(['id' => $faq->id]);
    }

    /**
     * @OA\Delete(
     *     path="/api/v1/admin/faqs",
     *     operationId="Delete some faq items",
     *     tags={"Faqs"},
     *     summary="Delete some faq items",
     *     security={ {"bearerAuth": {}} },
     *     @OA\Response(
     *         response="204",
     *         description="Faq items were deleted successfully"
     *     ),
     *     @OA\Response(
     *         response="400",
     *         description="Something went wrong"
     *     ),
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(ref="#/components/schemas/DestroySomeItemsVirtualBody")
     *     )
     * )
     *
     * Display a listing of the resource.
     *
     * @param DestroyItemsRequest $request
     * @return \Illuminate\Http\JsonResponse
     */

    public function destroySomeItems(DestroyItemsRequest $request): \Illuminate\Http\JsonResponse
    {
        Faq::whereIn('id', $request->ids)->delete();

        return $this->respondNoContent();
    }

    /**
     * @OA\Delete (
     *     path="/api/v1/admin/faqs/{id}",
     *     operationId="Delete faq",
     *     tags={"Faqs"},
     *     summary="Delete faq",
     *     security={ {"bearerAuth": {}} },
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         description="Faq's id",
     *         required=true,
     *         @OA\Schema(
     *             type="integer",
     *         )
     *     ),
     *     @OA\Response(
     *         response="204",
     *         description="Faq is deleted successfully"
     *     ),
     *     @OA\Response(
     *         response="400",
     *         description="Something went wrong"
     *     ),
     *     @OA\Response(
     *         response="404",
     *         description="Faq not found with provided id"
     *     )
     * )
     *
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\JsonResponse
     */

    public function destroy(Faq $faq): \Illuminate\Http\JsonResponse
    {
        $faq->delete();
        return $this->respondNoContent();
    }

    /**
     * @OA\Post (
     *     path="/api/v1/client/faqs",
     *     operationId="Faq not found",
     *     tags={"Faqs"},
     *     summary="Create faq",
     *     security={ {"bearerAuth": {}} },
     *     @OA\Response(
     *         response="201",
     *         description="Faq is created successfully"
     *     ),
     *     @OA\Response(
     *         response="400",
     *         description="Something went wrong"
     *     ),
     *     @OA\Response(
     *         response="422",
     *         description="Validation error"
     *     ),
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(ref="#/components/schemas/AnswerNotFoundVirtualBody")
     *     )
     * )
     *
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\JsonResponse
     */

    public function answerNotFound(AnswerNotFoundRequest $request): \Illuminate\Http\JsonResponse
    {

        Faq::create([
            'user_id' => auth('api')->check() ? auth('api')->user()->id : null,
            'email' => $request->email,
            'user_name' => $request->name,
            'theme' => $request->theme,
            'content' => $request->content,
            'message' => '',
        ]);

        SendFaqAnswerNotFoundJob::dispatch($request->validated());

        Notification::send(User::fetchAdmins(), new AnswerNotFoundNotification($request->validated()));

        return $this->respondCreated();
    }
}
