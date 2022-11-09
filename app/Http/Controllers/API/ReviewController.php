<?php

namespace Gentcmen\Http\Controllers\API;

use Gentcmen\Http\Controllers\ApiController;
use Gentcmen\Http\Requests\Review\CreateReviewRequest;
use Gentcmen\Http\Services\ImageService;
use Gentcmen\Models\Product;
use Gentcmen\Models\Review;
use Gentcmen\Models\ReviewImage;
use Illuminate\Http\UploadedFile;

class ReviewController extends ApiController
{
    protected ImageService $imageService;

    public function __construct(ImageService $imageService)
    {
        $this->imageService = $imageService;
    }

    /**
     * @OA\Get(
     *     path="/api/v1/client/products/{product}/reviews",
     *     operationId="Fetch product reviews",
     *     tags={"Product Reviews"},
     *     summary="Fetch product reviews",
     *     @OA\Parameter(
     *          name="product",
     *          description="Product id",
     *          required=true,
     *          in="path",
     *          example="1",
     *          @OA\Schema(
     *              type="integer"
     *          )
     *      ),
     *     @OA\Response(
     *         response="200",
     *         description="Product reviews is fetched successfully"
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

    public function index(int $productId)
    {
        $productReviews = Review::with('reviewImages', 'user')
            ->where('product_id', $productId);
	    //->get();
	    //->orderBy('created_at', 'desc');

        $countProductReviews = count($productReviews->get());

	$avg = $productReviews->get()->avg('rating');


        $reviewPoints = $productReviews
            ->select('rating', \DB::raw('count(*) as total'))
            ->groupBy('rating')
            ->get()
            ->pluck('total', 'rating');
//dd($reviewPoints);
//dd($countProductReviews, $reviewPoints->avg('rating'));
        foreach ($reviewPoints as $key => $item) {
            $reviewPoints[$key] = ($item * 100) / $countProductReviews;
        }

        //$countReviews = $reviewPoints->keys()->reduce(function ($acc, $item) {
        //    return $acc + $item;
        //}, 0);

        //$totalReviews = count($reviewPoints) ? floor($countReviews / count($reviewPoints)) : 0;
	$paginatedData = Review::with('reviewImages', 'user')
            ->where('product_id', $productId)
	    ->orderBy('created_at', 'desc')
	    ->paginate(10);
        return $this->respondOk([
            'reviews' => $paginatedData,
            'review_points' => [
                'total' => $avg,
                'ration' => $reviewPoints,
            ]
        ]);
    }

    /**
     * @OA\Post(
     *     path="/api/v1/client/products/{product}/reviews",
     *     operationId="Create product review",
     *     tags={"Product Reviews"},
     *     summary="Create product review",
     *     security={ {"bearerAuth": {}} },
     *     @OA\Parameter(
     *          name="product",
     *          description="Product id",
     *          required=true,
     *          in="path",
     *          example="1",
     *          @OA\Schema(
     *              type="integer"
     *          )
     *      ),
     *     @OA\Response(
     *         response="201",
     *         description="Product review is created successfully"
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
     *         @OA\MediaType(
     *          mediaType="multipart/form-data",
     *              @OA\Schema(
     *                  type="object",
     *                  ref="#/components/schemas/CreateProductReviewVirtualBody"
     *              )
     *         )
     *     )
     * )
     *
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */

    /**
     * Store a newly created resource in storage.
     *
     * @param CreateReviewRequest $request
     * @param Product $product
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(CreateReviewRequest $request, Product $product): \Illuminate\Http\JsonResponse
    {
        $review = new Review;
        $review->review = $request->review;
        $review->rating = $request->rating;
        $review->user_id = auth()->id();

        $product->reviews()->save($review);

         if ($request->file('images') && is_array($request->file('images'))) {
             foreach ($request->file('images') as $image) {
                 $this->uploadReviewImage($review, $image);
             }
        }

        return $this->respondCreated($review);
    }

    /**
     * @OA\Post (
     *     path="/api/v1/client/products/{product}/reviews/{review}",
     *     operationId="Update product review",
     *     tags={"Product Reviews"},
     *     summary="Update product review",
     *     security={ {"bearerAuth": {}} },
     *     @OA\Parameter(
     *          name="product",
     *          description="Product id",
     *          required=true,
     *          in="path",
     *          example="1",
     *          @OA\Schema(
     *              type="integer"
     *          )
     *      ),
     *      @OA\Parameter(
     *          name="review",
     *          description="Review id",
     *          required=true,
     *          in="path",
     *          example="1",
     *          @OA\Schema(
     *              type="integer"
     *          )
     *      ),
     *     @OA\Response(
     *         response="201",
     *         description="Product review is updated successfully"
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
     *         @OA\MediaType(
     *          mediaType="multipart/form-data",
     *              @OA\Schema(
     *                  type="object",
     *                  ref="#/components/schemas/CreateProductReviewVirtualBody"
     *              )
     *         )
     *     )
     * )
     *
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */

    /**
     * Update the specified resource in storage.
     *
     * @param CreateReviewRequest $request
     * @param Product $product
     * @param Review $review
     * @return \Illuminate\Http\JsonResponse
     */
    public function update(CreateReviewRequest $request, Product $product, Review $review): \Illuminate\Http\JsonResponse
    {
        $this->authorize('update-review', $review);

        $review->review = $request->review;
        $review->rating = $request->rating;
        $review->save();

        if ($request->has('image') && is_array($request->image)) {
            foreach ($request->image as $image) {
                if ($image instanceof UploadedFile) {
                    $this->uploadReviewImage($review, $image);
                } else if ($image['is_delete']) {
                    $this->imageService->destroy($image);
                }
            }
        }

        return $this->respondOk(['id' => $review->id]);
    }

    /**
     * @OA\Delete (
     *     path="/api/v1/client/products/{product}/reviews/{review}",
     *     operationId="Destroy product review",
     *     tags={"Product Reviews"},
     *     summary="Destroy product review",
     *     security={ {"bearerAuth": {}} },
     *     @OA\Parameter(
     *          name="product",
     *          description="Product id",
     *          required=true,
     *          in="path",
     *          example="1",
     *          @OA\Schema(
     *              type="integer"
     *          )
     *      ),
     *      @OA\Parameter(
     *          name="review",
     *          description="Review id",
     *          required=true,
     *          in="path",
     *          example="1",
     *          @OA\Schema(
     *              type="integer"
     *          )
     *      ),
     *     @OA\Response(
     *         response="204",
     *         description="Product review is deleted successfully"
     *     ),
     *     @OA\Response(
     *         response="400",
     *         description="Something went wrong"
     *     ),
     *     @OA\Response(
     *         response="422",
     *         description="Validation error"
     *     )
     * )
     *
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */


    /**
     * Remove the specified resource from storage.
     *
     * @param Product $product
     * @param Review $review
     * @return \Illuminate\Http\JsonResponse
     * @throws \League\Flysystem\Exception
     */
    public function destroy(Product $product, Review $review): \Illuminate\Http\JsonResponse
    {
        $this->authorize('update-review', $review);

        if (count($review->reviewImages))
        {
            foreach ($review->reviewImages as $reviewImage) {
                $this->imageService->destroy($reviewImage);
            }
        }

        $review->delete();
        return $this->respondNoContent();
    }

    private function uploadReviewImage(Review $review, UploadedFile $image)
    {
        $reviewImage = new ReviewImage();
        $coverImage = $this->imageService->saveNewFromUpload($image, $reviewImage->coverImageTypeKey());

        if (count($coverImage)) {
            $model = assignImage($reviewImage, $coverImage);
            $model->review_id = $review->id;
            $model->save();
        }
    }
}
