<?php

namespace Gentcmen\Http\Controllers\API;

use Gentcmen\Http\Controllers\ApiController;
use Gentcmen\Http\Requests\Favorite\CreateFavoriteItemsRequest;
use Gentcmen\Http\Requests\General\DestroyItemsRequest;
use Gentcmen\Models\Favorite;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;

class FavoriteController extends ApiController
{
     /**
     * @OA\Get(
     *     path="/api/v1/client/favorites",
     *     operationId="Fetch bucket data",
     *     tags={"Favorites"},
     *     summary="Fetch favorites data",
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
     *         description="Favorites data fetched successfully"
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
	$category_id = $request->query('category_id');
	$fieldValue = $request->query('text');
	$sortField = $request->query('sort-field');
	$sortBy = $request->query('sort-by');

        $favoriteData = Favorite::where('user_id', auth()->id())
	    ->with(['product' => function ($query) {
		$query->withCount('reviews');
	    }])
	    ->whereHas('product.productCategories', function ($query) use ($category_id, $fieldValue){
    		$query
		    ->when($category_id, function ($query) use ($category_id) {
			$query->where('product_category_id', $category_id);
		    })
		    ->when($fieldValue, function ($query) use ($fieldValue) {
                        $query->where('name', 'like', '%' . $fieldValue . '%')
			    ->orWhere('description', 'like', '%' . $fieldValue . '%');
            	    });
	    })
	    ->when($sortField && $sortBy, function ($query) use ($sortField, $sortBy) {
		$query->orderBy($sortField, $sortBy);
	    })
	    ->paginate(10);

        return $this->respondOk($favoriteData);
    }

    /**
     * @OA\Get(
     *     path="/api/v1/client/favorites/identifiers",
     *     operationId="Fetch favorite identifiers",
     *     tags={"Favorites"},
     *     summary="Fetch favorite identifiers",
     *     security={ {"bearerAuth": {}} },
     *     @OA\Response(
     *         response="200",
     *         description="Favorite identifiers fetched successfully"
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

    public function favoriteIdentifiers(): \Illuminate\Http\JsonResponse
    {
        $favoriteIdentifiers = Favorite::where('user_id', auth()->id())
	    ->select('id', 'user_id', 'product_id')
	    ->with(['product' => function ($query) {
		$query->select('id');
	    }])
            ->get()->pluck('id', 'product.id');

        return $this->respondOk($favoriteIdentifiers);
    }

    /**
     * @OA\Post(
     *     path="/api/v1/client/favorites",
     *     operationId="Store some favorite items",
     *     tags={"Favorites"},
     *     summary="Store some favorite items",
     *     security={ {"bearerAuth": {}} },
     *     @OA\Response(
     *         response="201",
     *         description="Favorite data added successfully"
     *     ),
     *     @OA\Response(
     *         response="400",
     *         description="Something went wrong"
     *     ),
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(ref="#/components/schemas/StoreFavoriteItemsVirtualBody")
     *     )
     * )
     *
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\JsonResponse
     */

    public function store(CreateFavoriteItemsRequest $request): \Illuminate\Http\JsonResponse
    {
        $result = Favorite::create([
	    'user_id' => auth()->id(),
	    'product_id' => $request->product_id 
	]);

        return $this->respondCreated(['id' => $result->id]);
    }

    /**
     * @OA\Delete(
     *     path="/api/v1/client/favorites",
     *     operationId="Delete favorite items by id",
     *     tags={"Favorites"},
     *     summary="Delete favorite items by id",
     *     security={ {"bearerAuth": {}} },
     *     @OA\Response(
     *         response="204",
     *         description="Favorite items deleted successfully"
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

    public function destroy(DestroyItemsRequest $request): \Illuminate\Http\JsonResponse
    {
        Favorite::whereIn('id', $request->ids)->delete();

        return $this->respondNoContent();
    }
}
