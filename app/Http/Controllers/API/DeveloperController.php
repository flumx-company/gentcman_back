<?php

namespace Gentcmen\Http\Controllers\API;

use Gentcmen\Http\Controllers\ApiController;
use Gentcmen\Http\Requests\Developer\CreateDeveloperRequest;
use Gentcmen\Http\Requests\General\DestroyItemsRequest;
use Gentcmen\Http\Services\ImageService;
use Gentcmen\Models\Developer;

class DeveloperController extends ApiController
{
    protected $imageService;
    protected $storageType;

    public function __construct(ImageService $imageService)
    {
        $this->imageService = $imageService;
        $this->storageType = 'public';
    }

    /**
     * @OA\Get(
     *     path="/api/v1/client/developers",
     *     operationId="Fetch developers",
     *     tags={"Developers"},
     *     summary="Fetch developers with cover image",
     *     @OA\Response(
     *         response="200",
     *         description="Data fetched successfully"
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
        $developers = Developer::get()->groupBy('position');

        return $this->respondOk($developers);
    }

    /**
     * @OA\Get(
     *     path="/api/v1/client/developers/{developer}",
     *     operationId="Fetch developer by id",
     *     tags={"Developers"},
     *     summary="Fetch developer by id",
     *     @OA\Parameter(
     *         name="developer",
     *         in="path",
     *         description="Developer's id",
     *         required=true,
     *         @OA\Schema(
     *             type="integer",
     *         )
     *     ),
     *     @OA\Response(
     *         response="200",
     *         description="Developer data fetched successfully"
     *     ),
     *     @OA\Response(
     *         response="404",
     *         description="Developer not found with provided it"
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

    public function getById(Developer $developer): \Illuminate\Http\JsonResponse
    {
        $developer->load('coverImage');

        return $this->respondOk($developer);
    }

    /**
     * @OA\Post(
     *     path="/api/v1/admin/developers",
     *     operationId="Create developer",
     *     tags={"Developers"},
     *     summary="Create a new developer",
     *     security={ {"bearerAuth": {}} },
     *     @OA\Response(
     *         response="201",
     *         description="Developer is created successfully"
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
     *                type="object",
     *                ref="#/components/schemas/CreateDeveloperVirtualBody"
     *              )
     *         )
     *     )
     * )
     *
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\JsonResponse
     */

    public function store(CreateDeveloperRequest $request): \Illuminate\Http\JsonResponse
    {
        try {
            $developer = Developer::create([
               'position' => $request->position,
               'first_name' => $request->first_name,
               'last_name' => $request->last_name,
               'image_link' => $request->image_link,
	       'resource_link' => $request->resource_link,
	       'email' => $request->email,
               'information' => $request->information,
            ]);
        } catch (\Exception $e) {
            return $this->sendError($e, $e->getMessage());
        }

       return $this->respondCreated(['id' => $developer->id]);
    }

    /**
     * @OA\Post(
     *     path="/api/v1/admin/developers/{developer}",
     *     operationId="Update developer",
     *     tags={"Developers"},
     *     summary="Update developer",
     *     security={ {"bearerAuth": {}} },
     *     @OA\Parameter(
     *         name="developer",
     *         in="path",
     *         description="Developer's id",
     *         required=true,
     *         @OA\Schema(
     *             type="integer",
     *         )
     *     ),
     *     @OA\Parameter(
     *         name="image_reset",
     *         in="query",
     *         description="Reset image or not. send any value that is true",
     *         required=false,
     *         @OA\Schema(
     *             type="integer",
     *         )
     *     ),
     *     @OA\Response(
     *         response="200",
     *         description="Developer is updated successfully"
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
     *                type="object",
     *                ref="#/components/schemas/CreateDeveloperVirtualBody"
     *              )
     *         )
     *     )
     * )
     *
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\JsonResponse
     */

    public function update(CreateDeveloperRequest $request, Developer $developer): \Illuminate\Http\JsonResponse
    {
        $developer->update([
            'position' => $request->position,
            'first_name' => $request->first_name,
            'last_name' => $request->last_name,
	    'image_link' => $request->image_link,
	    'resource_link' => $request->resource_link,
	    'email' => $request->email,
            'information' => convertToDbJson($request->information),
        ]);

        return $this->respondOk(['developer_id' => $developer->id]);
    }

    /**
     * @OA\Delete(
     *     path="/api/v1/admin/developers",
     *     operationId="Delete some developer items",
     *     tags={"Developers"},
     *     summary="Delete some developer items",
     *     security={ {"bearerAuth": {}} },
     *     @OA\Response(
     *         response="204",
     *         description="Developers were deleted successfully"
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
        Developer::whereIn('id', $request->ids)->delete();

        return $this->respondNoContent();
    }

    /**
     * @OA\Delete(
     *     path="/api/v1/admin/developers/{developer}",
     *     operationId="Delete developer by id",
     *     tags={"Developers"},
     *     summary="Delete developer by id",
     *     security={ {"bearerAuth": {}} },
     *     @OA\Parameter(
     *         name="developer",
     *         in="path",
     *         description="Developer's id",
     *         required=true,
     *         @OA\Schema(
     *             type="integer",
     *         )
     *     ),
     *     @OA\Response(
     *         response="204",
     *         description="Developer deleted successfully"
     *     ),
     *     @OA\Response(
     *         response="404",
     *         description="Developer not found with provided it"
     *     ),
     *     @OA\Response(
     *         response="400",
     *         description="Something went wrong"
     *     )
     * )
     *
     * Display a listing of the resource.
     *
     * @param Developer $developer
     * @return \Illuminate\Http\JsonResponse
     */

    public function destroy(Developer $developer): \Illuminate\Http\JsonResponse
    {
        $developer->delete();
        return $this->respondNoContent();
    }
}
