<?php

namespace Gentcmen\Http\Controllers\API;

use Gentcmen\Http\Controllers\ApiController;
use Gentcmen\Http\Requests\FaqCategory\FaqCategoryRequest;
use Gentcmen\Http\Requests\General\DestroyItemsRequest;
use Illuminate\Http\Request;
use Gentcmen\Models\FaqCategory;

class FaqCategoryController extends ApiController
{
    /**
     * @OA\Get(
     *     path="/api/v1/client/faq-categories",
     *     operationId="Fetch Faq Categories by query params",
     *     tags={"Faq Categories"},
     *     summary="Fetch Faq Categories by query params",
     *     @OA\Parameter(
     *          name="question",
     *          description="Answer",
     *          in="query",
     *          example="some question goes here",
     *          @OA\Schema(
     *              type="string"
     *          )
     *      ),
     *     @OA\Response(
     *         response="200",
     *         description="Faq Categories by query params fetched successfully"
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

    public function index(Request $request): \Illuminate\Http\JsonResponse
    {

        $question = $request->query('question');
// return $this->respondOk(['a' => $question]);
        $faqCategories = FaqCategory::with(['faqSubCategories' => function ($query)  use ($question) {
		$query->when($question, function ($query) use ($question) {
            	    return $query->where('question', 'like', '%' . $question . '%')
			->orWhere('answer', 'like', '%' . $question . '%');
        	});
	    }])
            ->get();

        return $this->respondOk($faqCategories);
    }

    /**
     * @OA\Get(
     *     path="/api/v1/client/faq-categories/{faqCategory}",
     *     operationId="Fetch Faq Category by id",
     *     tags={"Faq Categories"},
     *     summary="Fetch Faq Category by id",
     *     @OA\Parameter(
     *          name="faqCategory",
     *          description="Faq Category id",
     *          required=true,
     *          in="path",
     *          example="1",
     *          @OA\Schema(
     *              type="integer"
     *          )
     *      ),
     *     @OA\Response(
     *         response="200",
     *         description="Faq Category fetched successfully"
     *     ),
     *     @OA\Response(
     *         response="404",
     *         description="Faq Category not found"
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
    public function getById(FaqCategory $faqCategory): \Illuminate\Http\JsonResponse
    {
        $faqCategory->load('faqSubCategories');

        return $this->respondOk($faqCategory);
    }

     /**
     * @OA\Post(
     *     path="/api/v1/admin/faq-categories",
     *     operationId="Create faq category",
     *     tags={"Faq Categories"},
     *     summary="Create faq category",
     *     security={ {"bearerAuth": {}} },
     *     @OA\Response(
     *         response="201",
     *         description="Faq category is created successfully"
     *     ),
     *     @OA\Response(
     *         response="400",
     *         description="Something went wrong"
     *     ),
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *          ref="#/components/schemas/CreateFaqCategoryVirtualBody"
     *         )
     *     )
     * )
     *
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\JsonResponse
      */

    public function store(FaqCategoryRequest $request): \Illuminate\Http\JsonResponse
    {
        $faqCategory = FaqCategory::create([
            'name' => $request->name,
        ]);

        return $this->respondCreated(['id' => $faqCategory->id]);
    }

    /**
     * @OA\Put(
     *     path="/api/v1/admin/faq-categories/{category}",
     *     operationId="Update faq category by id",
     *     tags={"Faq Categories"},
     *     summary="Update faq category by id",
     *     security={ {"bearerAuth": {}} },
     *     @OA\Parameter(
     *          name="category",
     *          description="faq category id",
     *          required=true,
     *          in="path",
     *          example="1",
     *          @OA\Schema(
     *              type="integer"
     *          )
     *      ),
     *     @OA\Response(
     *         response="200",
     *         description="Faq category is updated successfully"
     *     ),
     *     @OA\Response(
     *         response="404",
     *         description="Faq Category not found"
     *     ),
     *     @OA\Response(
     *         response="400",
     *         description="Something went wrong"
     *     ),
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *          ref="#/components/schemas/CreateFaqCategoryVirtualBody"
     *         )
     *     )
     * )
     *
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\JsonResponse
     */

    public function update(FaqCategoryRequest $request, FaqCategory $category): \Illuminate\Http\JsonResponse
    {
        $category->update([
            'name' => $request->name
        ]);

        return $this->respondOk(['id' => $category->id]);
    }

    /**
     * @OA\Delete(
     *     path="/api/v1/admin/faq-categories",
     *     operationId="Delete some faq category items",
     *     tags={"Faq Categories"},
     *     summary="Delete some faq category items",
     *     security={ {"bearerAuth": {}} },
     *     @OA\Response(
     *         response="204",
     *         description="Faq category items deleted successfully"
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
     * @return \Illuminate\Http\JsonResponse
     */

    public function destroySomeItems(DestroyItemsRequest $request): \Illuminate\Http\JsonResponse
    {
        FaqCategory::whereIn('id', $request->ids)->delete();

        return $this->respondNoContent();
    }

    /**
     * @OA\Delete(
     *     path="/api/v1/admin/faq-categories/{category}",
     *     operationId="Delete faq category by id",
     *     tags={"Faq Categories"},
     *     summary="Delete faq category by id",
     *     security={ {"bearerAuth": {}} },
     *     @OA\Parameter(
     *          name="category",
     *          description="faq category id",
     *          required=true,
     *          in="path",
     *          example="1",
     *          @OA\Schema(
     *              type="integer"
     *          )
     *      ),
     *     @OA\Response(
     *         response="204",
     *         description="Faq category is deleted successfully"
     *     ),
     *     @OA\Response(
     *         response="400",
     *         description="Something went wrong"
     *     ),
     *     @OA\Response(
     *         response="404",
     *         description="Faq Category not found"
     *     )
     * )
     *
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroy(FaqCategory $category): \Illuminate\Http\JsonResponse
    {
        $category->delete();
        return $this->respondNoContent();
    }
}
