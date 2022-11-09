<?php

namespace Gentcmen\Http\Controllers\API;

use Gentcmen\Http\Controllers\ApiController;
use Gentcmen\Http\Requests\FaqSubCategory\FaqSubCategoryRequest;
use Gentcmen\Http\Requests\General\DestroyItemsRequest;
use Gentcmen\Models\FaqCategory;
use Gentcmen\Models\FaqSubCategory;
use Illuminate\Http\Request;

class FaqSubCategoryController extends ApiController
{
    /**
     * @OA\Get(
     *     path="/api/v1/client/faq-subcategories",
     *     operationId="Fetch Faq SubCategories",
     *     tags={"Faq Sub Categories"},
     *     summary="Fetch Faq Sub Categories",
     *     @OA\Response(
     *         response="200",
     *         description="Faq Sub Categories fetched successfully"
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
        $faq_category = $request->faq_category_id;

        $faqSubCategories = FaqSubCategory::when($faq_category , function ($query) use ($faq_category) {
            return $query ->where('faq_category_id', '=', $faq_category);
        })->get();

        return $this->respondOk($faqSubCategories);
    }

    /**
     * @OA\Get(
     *     path="/api/v1/client/faq-subcategories/{entity}",
     *     operationId="Fetch Faq Sub Category by id",
     *     tags={"Faq Sub Categories"},
     *     summary="Fetch Faq Sub Category by id",
     *     @OA\Parameter(
     *          name="entity",
     *          description="Faq Sub Category id",
     *          required=true,
     *          in="path",
     *          example="1",
     *          @OA\Schema(
     *              type="integer"
     *          )
     *      ),
     *     @OA\Response(
     *         response="200",
     *         description="Faq Sub Category fetched successfully"
     *     ),
     *     @OA\Response(
     *         response="404",
     *         description="Faq Sub Category not found"
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
    public function getById(FaqSubCategory $entity): \Illuminate\Http\JsonResponse
    {
        return $this->respondOk($entity);
    }

     /**
     * @OA\Post(
     *     path="/api/v1/admin/faq-categories/{category}/faq-subcategory",
     *     operationId="Create faq sub category",
     *     tags={"Faq Categories"},
     *     summary="Create faq sub category",
     *     security={ {"bearerAuth": {}} },
      *    @OA\Parameter(
      *        name="category",
      *        description="Faq category id",
      *        required=true,
      *        in="path",
      *        example="1",
      *        @OA\Schema(
      *            type="integer"
      *        )
      *    ),
     *     @OA\Response(
     *         response="201",
     *         description="Faq sub category is created successfully"
     *     ),
     *     @OA\Response(
     *         response="400",
     *         description="Something went wrong"
     *     ),
     *     @OA\Response(
     *         response="404",
     *         description="Faq category not found with provided id"
     *     ),
     *     @OA\Response(
     *         response="422",
     *         description="Validation error"
     *     ),
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *          ref="#/components/schemas/CreateFaqSubCategoryVirtualBody"
     *         )
     *     )
     * )
     *
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\JsonResponse
      */

    public function store(FaqSubCategoryRequest $request, FaqCategory $category): \Illuminate\Http\JsonResponse
    {
        $faqSubCategory = FaqSubCategory::create([
            'question' => $request->question,
            'answer' => $request->answer,
            'faq_category_id' => $request->faq_category_id,
        ]);

        return $this->respondCreated(['id' => $faqSubCategory->id]);
    }

    /**
     * @OA\Put(
     *     path="/api/v1/admin/faq-subcategories/{id}",
     *     operationId="Update faq sub category by id",
     *     tags={"Faq Sub Categories"},
     *     summary="Update faq sub category by id",
     *     security={ {"bearerAuth": {}} },
     *     @OA\Parameter(
     *          name="id",
     *          description="faq sub category id",
     *          required=true,
     *          in="path",
     *          example="1",
     *          @OA\Schema(
     *              type="integer"
     *          )
     *      ),
     *     @OA\Response(
     *         response="200",
     *         description="Faq sub category is updated successfully"
     *     ),
     *     @OA\Response(
     *         response="404",
     *         description="Faq sub Category not found"
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
     *         @OA\JsonContent(
     *          ref="#/components/schemas/CreateFaqSubCategoryVirtualBody"
     *         )
     *     )
     * )
     *
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\JsonResponse
     */

    public function update(FaqSubCategoryRequest $request, FaqSubCategory $entity)
    {
        $entity->update([
            'question' => $request->question,
            'answer' => $request->answer,
            'faq_category_id' => $request->faq_category_id,
        ]);

        return $this->respondOk(['id' => $entity->id]);
    }

    /**
     * @OA\Delete(
     *     path="/api/v1/admin/faq-subcategories",
     *     operationId="Delete some faq sub category items",
     *     tags={"Faq Sub Categories"},
     *     summary="Delete some faq sub category items",
     *     security={ {"bearerAuth": {}} },
     *     @OA\Response(
     *         response="204",
     *         description="Faq sub category items deleted successfully"
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
     */

    public function destroySomeItems(DestroyItemsRequest $request): \Illuminate\Http\JsonResponse
    {
        FaqSubCategory::whereIn('id', $request->ids)->delete();

        return $this->respondNoContent();
    }

    /**
     * @OA\Delete(
     *     path="/api/v1/admin/faq-subcategories/{entity}",
     *     operationId="Delete faq sub category by id",
     *     tags={"Faq Sub Categories"},
     *     summary="Delete faq sub category by id",
     *     security={ {"bearerAuth": {}} },
     *     @OA\Parameter(
     *          name="entity",
     *          description="Faq sub category id",
     *          required=true,
     *          in="path",
     *          example="1",
     *          @OA\Schema(
     *              type="integer"
     *          )
     *      ),
     *     @OA\Response(
     *         response="204",
     *         description="Faq sub category is deleted successfully"
     *     ),
     *     @OA\Response(
     *         response="400",
     *         description="Something went wrong"
     *     ),
     *     @OA\Response(
     *         response="404",
     *         description="Faq sub Category not found"
     *     )
     * )
     *
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroy(FaqSubCategory $entity)
    {
        $entity->delete();

        return $this->respondNoContent();
    }
}
