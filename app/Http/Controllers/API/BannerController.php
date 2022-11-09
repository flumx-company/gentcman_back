<?php

namespace Gentcmen\Http\Controllers\API;

use Gentcmen\Http\Controllers\ApiController;
use Gentcmen\Http\Requests\Banner\CreateBannerRequest;
use Gentcmen\Http\Requests\General\DestroyItemsRequest;
use Illuminate\Http\Request;
use Illuminate\Pipeline\Pipeline;
use Gentcmen\Models\Banner;

class BannerController extends ApiController
{
    /**
     * @OA\Get(
     *     path="/api/v1/client/banners",
     *     operationId="Get all banners",
     *     tags={"Banners"},
     *     summary="Get all banners",
     *     @OA\Parameter(
     *         name="length",
     *         in="query",
     *         description="Length of data",
     *         required=false,
     *         @OA\Schema(
     *             type="integer",
     *         )
     *     ),
     *     @OA\Parameter(
     *         name="random_banner",
     *         in="query",
     *         description="Fetch random banner",
     *         required=false,
     *         @OA\Schema(
     *             type="integer",
     *         )
     *     ),
     *     @OA\Parameter(
     *         name="published",
     *         in="query",
     *         description="Fetch published banners or unpublished",
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
     *     )
     * )
     *
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\JsonResponse
     */

    public function index(): \Illuminate\Http\JsonResponse
    {
        $query = app(Pipeline::class)
            ->send(Banner::query())
            ->through([
                \Gentcmen\Http\Controllers\API\BannerQueryFilters\RandomBanner::class,
                \Gentcmen\Http\Controllers\API\BannerQueryFilters\Length::class,
                \Gentcmen\Http\Controllers\API\BannerQueryFilters\Published::class,
            ])
            ->thenReturn();

        $banners = $query->get();

        return $this->respondOk($banners);
    }

    /**
     * @OA\Get(
     *     path="/api/v1/client/banners/{banner}",
     *     operationId="Get single banner",
     *     tags={"Banners"},
     *     summary="Get single banner by id",
     *     @OA\Parameter(
     *         name="banner",
     *         in="path",
     *         description="Banner id ",
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
     * @return \Illuminate\Http\JsonResponse
     */

    public function getById(Request $request, Banner $banner): \Illuminate\Http\JsonResponse
    {
        if (in_array($request->query('published'), ["1", "0"]))
        {
            $banner = $banner->published === intval($request->query('published')) ? $banner : [];
        }

        return $this->respondOk($banner);
    }

    /**
     * @OA\Post (
     *     path="/api/v1/admin/banners",
     *     operationId="Create banner",
     *     tags={"Banners"},
     *     summary="Create banner",
     *     security={ {"bearerAuth": {}} },
     *     @OA\Response(
     *         response="201",
     *         description="Banner is created successfully"
     *     ),
     *     @OA\Response(
     *         response="400",
     *         description="Something went wrong"
     *     ),
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(ref="#/components/schemas/BannerVirtualBody")
     *     )
     * )
     *
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\JsonResponse
     */

    public function store(CreateBannerRequest $request): \Illuminate\Http\JsonResponse
    {
        $banner = new Banner([
            'link_desktop' => $request->link_desktop,
	    'link_mobile' => $request->link_mobile,
	    'description' => $request->description,
	    'button_link' => $request->button_link,
	    'button_text' => $request->button_text
        ]);

        $banner->published = $request->published ?? false;
        $banner->save();

        return $this->respondCreated(['id' => $banner->id]);
    }

    /**
     * @OA\Put (
     *     path="/api/v1/admin/banners/{banner}",
     *     operationId="Update single banner",
     *     tags={"Banners"},
     *     summary="Update single banner by id",
     *     security={ {"bearerAuth": {}} },
     *     @OA\Parameter(
     *         name="banner",
     *         in="path",
     *         description="Banner id ",
     *         required=true,
     *         @OA\Schema(
     *             type="integer",
     *         )
     *     ),
     *     @OA\Response(
     *         response="200",
     *         description="Banner updated successfully"
     *     ),
     *     @OA\Response(
     *         response="400",
     *         description="Something went wrong"
     *     ),
     *     @OA\Response(
     *         response="404",
     *         description="Not found"
     *     ),
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(ref="#/components/schemas/BannerVirtualBody")
     *     )
     * )
     *
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\JsonResponse
     */

    public function update(CreateBannerRequest $request, Banner $banner): \Illuminate\Http\JsonResponse
    {
        $banner->update([
            'link_desktop' => $request->link_desktop,
	    'link_mobile' => $request->link_mobile,
	    'description' => $request->description,
	    'button_link' => $request->button_link,
	    'button_text' => $request->button_text
        ]);

        $banner->published =  $request->published ?? false;
        $banner->save();

        return $this->respondOk(['id' => $banner->id]);
    }

    /**
     * @OA\Delete(
     *     path="/api/v1/admin/banners",
     *     operationId="Delete some banners items",
     *     tags={"Banners"},
     *     summary="Delete some banners items",
     *     security={ {"bearerAuth": {}} },
     *     @OA\Response(
     *         response="204",
     *         description="Banner items were deleted successfully"
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
        Banner::whereIn('id', $request->ids)->delete();

        return $this->respondNoContent();
    }

    /**
     * @OA\Delete (
     *     path="/api/v1/admin/banners/{banner}",
     *     operationId="Delete single banner",
     *     tags={"Banners"},
     *     summary="Delete single banner by id",
     *     security={ {"bearerAuth": {}} },
     *     @OA\Parameter(
     *         name="banner",
     *         in="path",
     *         description="Banner id ",
     *         required=true,
     *         @OA\Schema(
     *             type="integer",
     *         )
     *     ),
     *     @OA\Response(
     *         response="201",
     *         description="Banner deleted successfully"
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
     * @return \Illuminate\Http\JsonResponse
     */

    public function destroy(Banner $banner): \Illuminate\Http\JsonResponse
    {
        $banner->delete();
        return $this->respondNoContent();
    }
}
