<?php

namespace Gentcmen\Http\Controllers\API;

use Gentcmen\Http\Controllers\ApiController;
use Gentcmen\Http\Requests\Blog\CreateBlogRequest;
use Gentcmen\Http\Requests\General\DestroyItemsRequest;
use Gentcmen\Models\Blog;
use Illuminate\Pipeline\Pipeline;
use Illuminate\Http\Request;

class BlogController extends ApiController
{
    /**
     * @OA\Get(
     *     path="/api/v1/client/blog",
     *     operationId="Fetch by query params",
     *     tags={"Blog"},
     *     summary="Fetch the blogs query params",
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
     *         name="published",
     *         in="query",
     *         description="Fetch published or unpublished",
     *         required=false,
     *         @OA\Schema(
     *             type="integer",
     *         )
     *     ),
     *     @OA\Parameter(
     *         name="author_name",
     *         in="query",
     *         description="Fetch data by author name",
     *         required=false,
     *         @OA\Schema(
     *             type="string",
     *         )
     *     ),
     *     @OA\Parameter(
     *         name="author_email",
     *         in="query",
     *         description="Fetch data by author email",
     *         required=false,
     *         @OA\Schema(
     *             type="string",
     *         )
     *     ),
     *     @OA\Parameter(
     *         name="blog_type_id",
     *         in="query",
     *         description="Blog type id",
     *         required=false,
     *         @OA\Schema(
     *             type="string",
     *         )
     *     ),
     *     @OA\Parameter(
     *         name="category_id",
     *         in="query",
     *         description="Category id",
     *         required=false,
     *         @OA\Schema(
     *             type="string",
     *         )
     *     ),
     *     @OA\Parameter(
     *         name="actual",
     *         in="query",
     *         description="Get actual data",
     *         required=false,
     *         @OA\Schema(
     *             type="string",
     *         )
     *     ),
     *     @OA\Parameter(
     *         name="excludeIds",
     *         in="query",
     *         description="Excluded ids",
     *         required=false,
     *         @OA\Schema(
     *             type="string",
     *         )
     *     ),
     *     @OA\Parameter(
     *         name="random",
     *         in="query",
     *         description="Fetch random records",
     *         required=false,
     *         @OA\Schema(
     *             type="string",
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
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */

    public function index(Request $request): \Illuminate\Http\JsonResponse
    {
        $limit = $request->query('limit');
	
        $query = app(Pipeline::class)
            ->send(Blog::query())
            ->through([
                \Gentcmen\Http\Controllers\API\BlogQueryFilters\BlogTypeId::class,
                \Gentcmen\Http\Controllers\API\BlogQueryFilters\Actual::class,
                \Gentcmen\Http\Controllers\API\BlogQueryFilters\Random::class,
                \Gentcmen\Http\Controllers\API\BlogQueryFilters\CategoryId::class,
                \Gentcmen\Http\Controllers\API\BlogQueryFilters\ExcludedIds::class,
                \Gentcmen\Http\Controllers\API\BlogQueryFilters\Published::class,
                \Gentcmen\Http\Controllers\API\BlogQueryFilters\AuthorEmail::class,
                \Gentcmen\Http\Controllers\API\BlogQueryFilters\AuthorName::class,
		\Gentcmen\Http\Controllers\API\BlogQueryFilters\Title::class,
            ])
            ->thenReturn();

        $blogData = $query->with('author.roles', 'type', 'category')->paginate(empty($limit) ? 8 : intval($limit));

        return $this->respondOk($blogData);
    }

    /**
     * @OA\Get(
     *     path="/api/v1/client/blog/{blog}",
     *     operationId="examplesOne",
     *     tags={"Blog"},
     *     summary="Display a Blog  by id",
     *     @OA\Parameter(
     *         name="blog",
     *         in="path",
     *         description="Blog id ",
     *         required=true,
     *         @OA\Schema(
     *             type="integer",
     *         )
     *     ),
     *     @OA\Parameter(
     *         name="published",
     *         in="query",
     *         description="Fetch published or unpublished",
     *         required=false,
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
     * @param Request $request
     * @param Blog $blog
     * @return \Illuminate\Http\JsonResponse
     */

    public function getById(Request $request, Blog $blog): \Illuminate\Http\JsonResponse
    {
        $blog->load('author.roles', 'type', 'category');

        if (in_array($request->query('published'), ["1", "0"]))
        {
            $blog = $blog->published === intval($request->query('published')) ? $blog : null;
        }

        return $this->respondOk($blog);
    }

    /**
     * @OA\Post(
     *     path="/api/v1/admin/blog",
     *     operationId="store",
     *     tags={"Blog"},
     *     summary="Store a blog",
     *     security={ {"bearer": {}} },
     *     @OA\Response(
     *         response="201",
     *         description="Blog created successfully"
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
     *         @OA\JsonContent(ref="#/components/schemas/BlogVirtualBody")
     *     )
     * )
     *
     * Display a listing of the resource.
     *
     * @param CreateBlogRequest $request
     * @return \Illuminate\Http\JsonResponse
     */

    public function store(CreateBlogRequest $request): \Illuminate\Http\JsonResponse
    {
        $newBlog = Blog::create([
            'title' => $request->title,
            'short_content' => $request->short_content,
            'content' => $request->content,
            'user_id' => auth()->id(),
            'type_id' => $request->type_id,
            'image_title' => $request->image_title,
            'category_id' => $request->category_id,
            'meta_title' => $request->meta_title,
            'meta_description' => $request->meta_description,
            'meta_keywords' => $request->meta_description,
            'published' => $request->published ?? false,
        ]);

        return $this->respondOk(['id' => $newBlog->id]);
    }

    /**
     * @OA\Put(
     *     path="/api/v1/admin/blog/{blog}",
     *     operationId="put",
     *     tags={"Blog"},
     *     summary="Update a blog by id",
     *     security={ {"bearer": {}} },
     *     @OA\Parameter(
     *         name="blog",
     *         in="path",
     *         description="Blog id",
     *         required=true,
     *         @OA\Schema(
     *             type="integer",
     *         )
     *     ),
     *     @OA\Response(
     *         response="200",
     *         description="Blog updated successfully"
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
     *         @OA\JsonContent(ref="#/components/schemas/BlogVirtualBody")
     *     )
     * )
     *
     * Display a listing of the resource.
     *
     * @param CreateBlogRequest $request
     * @param Blog $blog
     * @return \Illuminate\Http\JsonResponse
     */

    public function update(CreateBlogRequest $request, Blog $blog): \Illuminate\Http\JsonResponse
    {
        $blog->update([
            'title' => $request->title,
            'short_content' => $request->short_content,
            'content' => $request->content,
            'image_title' => $request->image_title,
            'type_id' => $request->type_id,
            'category_id' => $request->category_id,
            'meta_title' => $request->meta_title,
            'meta_description' => $request->meta_description,
            'meta_keywords' => $request->meta_description,
            'published' => $request->published ?? false,
        ]);

        return $this->respondOk(['id' => $blog->id]);
    }

    /**
     * @OA\Delete(
     *     path="/api/v1/admin/blog",
     *     operationId="Delete some blog items",
     *     tags={"Blog"},
     *     summary="Delete some blog items",
     *     security={ {"bearerAuth": {}} },
     *     @OA\Response(
     *         response="204",
     *         description="Blog items were deleted successfully"
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
        Blog::whereIn('id', $request->ids)->delete();

        return $this->respondNoContent();
    }

    /**
     * @OA\Delete(
     *     path="/api/v1/admin/blog/{blog}",
     *     operationId="delete one",
     *     tags={"Blog"},
     *     summary="Delete a blog by id",
     *     security={ {"bearer": {}} },
     *     @OA\Parameter(
     *         name="blog",
     *         in="path",
     *         description="Blog id",
     *         required=true,
     *         @OA\Schema(
     *             type="integer",
     *         )
     *     ),
     *     @OA\Response(
     *         response="204",
     *         description="Blog deleted successfully"
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
     * @param Blog $blog
     * @return \Illuminate\Http\JsonResponse
     */

    public function destroy(Blog $blog): \Illuminate\Http\JsonResponse
    {
        $blog->delete();

        return $this->respondNoContent();
    }
}
