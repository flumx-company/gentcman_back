<?php

namespace Gentcmen\Http\Controllers\API;

use Gentcmen\Http\Controllers\ApiController;
use Gentcmen\Http\Requests\BlogCategory\CreateBlogCategoryRequest;
use Gentcmen\Http\Requests\General\DestroyItemsRequest;
use Gentcmen\Models\BlogCategory;

class BlogCategoryController extends ApiController
{
    /**
     * @OA\Get(
     *     path="/api/v1/client/blog-categories",
     *     operationId="examplesAll",
     *     tags={"Blog Categories"},
     *     summary="Display a listing of the blog categories",
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
    public function index(): \Illuminate\Http\JsonResponse
    {
        $blogCategories = BlogCategory::all();
        return $this->respondOk($blogCategories);
    }

    /**
     * @OA\Post(
     *     path="/api/v1/admin/blog-categories",
     *     operationId="store",
     *     tags={"Blog Categories"},
     *     summary="Store a BlogCategory",
     *     security={ {"bearer": {}} },
     *     @OA\Response(
     *         response="201",
     *         description="BlogCategory created successfully"
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
     *         @OA\JsonContent(ref="#/components/schemas/BlogCategoryVirtualBody")
     *     )
     * )
     *
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\JsonResponse
     */

    public function store(CreateBlogCategoryRequest $request): \Illuminate\Http\JsonResponse
    {
        $blogCategory = BlogCategory::create($request->validated());

        return $this->respondOk(['id' => $blogCategory->id]);
    }

    /**
     * @OA\Get(
     *     path="/api/v1/client/blog-categories/{blogCategory}",
     *     operationId="examplesOne",
     *     tags={"Blog Categories"},
     *     summary="Display a BlogCategory by id",
     *     @OA\Parameter(
     *         name="blogCategory",
     *         in="path",
     *         description="Id BlogCategory",
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
     *         response="400",
     *         description="Something went wrong"
     *     ),
     *     @OA\Response(
     *         response="404",
     *         description="Not found"
     *     )
     * )
     *
     * Display a listing of the resource.
     *
     * @param BlogCategory $blogCategory
     * @return \Illuminate\Http\JsonResponse
     */

    public function getById(BlogCategory $blogCategory): \Illuminate\Http\JsonResponse
    {
        return $this->respondOk($blogCategory);
    }

    /**
     * @OA\Put(
     *     path="/api/v1/admin/blog-categories/{blogCategory}",
     *     operationId="put",
     *     tags={"Blog Categories"},
     *     summary="Update a BlogCategory by id",
     *     security={ {"bearer": {}} },
     *     @OA\Parameter(
     *         name="blogCategory",
     *         in="path",
     *         description="Id BlogCategory",
     *         required=true,
     *         @OA\Schema(
     *             type="integer",
     *         )
     *     ),
     *     @OA\Response(
     *         response="200",
     *         description="BlogCategory updated successfully"
     *     ),
     *     @OA\Response(
     *         response="400",
     *         description="Something went wrong"
     *     ),
     *     @OA\Response(
     *         response="404",
     *         description="Not found"
     *     ),
     *     @OA\Response(
     *         response="422",
     *         description="Validation error"
     *     ),
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(ref="#/components/schemas/BlogCategoryVirtualBody")
     *     )
     * )
     *
     * Display a listing of the resource.
     *
     * @param CreateBlogCategoryRequest $request
     * @param BlogCategory $blogCategory
     * @return \Illuminate\Http\JsonResponse
     */

    public function update(CreateBlogCategoryRequest $request, BlogCategory $blogCategory): \Illuminate\Http\JsonResponse
    {
        $blogCategory->update($request->validated());

        return $this->respondOk(['id' => $blogCategory->id]);
    }

    /**
     * @OA\Delete(
     *     path="/api/v1/admin/blog-categories",
     *     operationId="Delete some blog category items",
     *     tags={"Blog Categories"},
     *     summary="Delete some blog category items",
     *     security={ {"bearerAuth": {}} },
     *     @OA\Response(
     *         response="204",
     *         description="Blog category items deleted successfully"
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
        BlogCategory::whereIn('id', $request->ids)->delete();

        return $this->respondNoContent();
    }

     /**
     * @OA\Delete(
     *     path="/api/v1/admin/blog-categories/{blogCategory}",
     *     operationId="delete one",
     *     tags={"Blog Categories"},
     *     summary="Delete a BlogCategory by id",
     *     security={ {"bearer": {}} },
     *     @OA\Parameter(
     *         name="blogCategory",
     *         in="path",
     *         description="Id BlogCategory",
     *         required=true,
     *         @OA\Schema(
     *             type="integer",
     *         )
     *     ),
     *     @OA\Response(
     *         response="204",
     *         description="BlogCategory deleted successfully"
     *     ),
     *     @OA\Response(
     *         response="400",
     *         description="Something went wrong"
     *     ),
     *     @OA\Response(
     *         response="404",
     *         description="Not found"
     *     ),
     * )
     *
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\JsonResponse
      */

    public function destroy(BlogCategory $blogCategory): \Illuminate\Http\JsonResponse
    {
        $blogCategory->delete();

        return $this->respondNoContent();
    }
}
