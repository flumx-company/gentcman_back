<?php

namespace Gentcmen\Http\Controllers\API;

use Gentcmen\Http\Controllers\ApiController;
use Gentcmen\Http\Requests\General\DestroyItemsRequest;
use Gentcmen\Http\Requests\ReportProblem\ReportProblemPatchUpdate;
use Gentcmen\Http\Requests\ReportProblem\ReportProblemRequest;
use Gentcmen\Models\ReportProblem;
use Gentcmen\Models\ReportProblemsImage;
use Gentcmen\Models\User;
use Illuminate\Support\Facades\Notification;
use Gentcmen\Notifications\ReportProblemNotification;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class ReportProblemController extends ApiController
{

    /**
     * @OA\Get  (
     *     path="/api/v1/client/report-problem",
     *     operationId="Fetch report problems for client",
     *     tags={"Report a problem"},
     *     summary="Fetch idea report problems for client",
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
     *         description="Report problems fetched successfully"
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
        $reportProblems = ReportProblem::where('user_id', auth('api')->id())->paginate(10);

        return $this->respondOk($reportProblems);
    }

    /**
     * @OA\Get(
     *     path="/api/v1/admin/report-problem",
     *     operationId="Fetch report problems",
     *     tags={"Report a problem"},
     *     summary="Fetch report problems",
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
     *          name="orderBy",
     *          description="orderBy asc or desc",
     *          in="query",
     *          example="asc",
     *          @OA\Schema(
     *              type="string"
     *          )
     *      ),
     *      @OA\Parameter(
     *          name="status",
     *          description="search by status",
     *          in="query",
     *          example="received",
     *          @OA\Schema(
     *              type="string"
     *          )
     *      ),
     *     @OA\Response(
     *         response="200",
     *         description="Report problems fetched successfully"
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
        $orderBy = $request->query('orderBy') ?? 'asc';

        $query = ReportProblem::with('images')->orderBy('created_at', $orderBy);

        if ($request->query('status'))
        {
            $query = $query->where('status', $request->query('status'));
        }

        $reportProblems = $query->paginate(10);

        return $this->respondOk($reportProblems);
    }

    /**
     * @OA\Post(
     *     path="/api/v1/client/report-problem",
     *     operationId="Report a problem",
     *     tags={"Report a problem"},
     *     summary="Report a problem",
     *     @OA\Response(
     *         response="201",
     *         description="Report problem created successfully"
     *     ),
     *     @OA\Response(
     *         response="400",
     *         description="Something went wrong"
     *     ),
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(ref="#/components/schemas/ReportProblemVirtualBody")
     *     )
     * )
     *
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\JsonResponse
     */

    public function store(ReportProblemRequest $request): \Illuminate\Http\JsonResponse
    {
        $reportInformation = [
            'theme' => $request->theme,
            'content' => $request->content,
            'user_email' =>  $request->user_email,
	    'message' => ''
        ];

        $reportProblem = ReportProblem::create($reportInformation);
        $reportInformation['notification_id'] = $reportProblem->id;
//dd($request->file('images'));

//return $this->respondCreated([ 't' =>  $request->file('images')]);
        if($request->file('images')) {
            $this->uploadProductImages($request->file('images'), $reportProblem->id);
        }

        $admins = User::fetchAdmins();

        Notification::send($admins, new ReportProblemNotification((object) $reportInformation));

        return $this->respondCreated(['id' => $reportProblem->id]);
    }

    /**
     * @OA\Patch (
     *     path="/api/v1/admin/report-problem/{problem}",
     *     operationId="Update report problem",
     *     tags={"Report a problem"},
     *     summary="Update report problem",
     *     security={ {"bearerAuth": {}} },
     *     @OA\Parameter(
     *         name="problem",
     *         in="path",
     *         description="Report problem's id",
     *         required=true,
     *         @OA\Schema(
     *             type="integer",
     *         )
     *     ),
     *     @OA\Response(
     *         response="200",
     *         description="Report problem is updated successfully"
     *     ),
     *     @OA\Response(
     *         response="400",
     *         description="Something went wrong"
     *     ),
     *     @OA\Response(
     *         response="404",
     *         description="Report problem not found with provided id"
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
     *                  title="Status of report problem",
     *                  description="Status of report problem",
     *                  example="received"
     *              )
     *         )
     *     )
     * )
     *
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\JsonResponse
     */

    public function patchUpdate(ReportProblemPatchUpdate $request, ReportProblem $problem): \Illuminate\Http\JsonResponse
    {
        $problem->update($request->validated());

        return $this->respondOk(['id' => $problem->id]);
    }

    /**
     * @OA\Delete(
     *     path="/api/v1/admin/report-problem/{problem}",
     *     operationId="Delete report problem by id",
     *     tags={"Report a problem"},
     *     summary="Delete report problem by id",
     *     security={ {"bearerAuth": {}} },
     *     @OA\Parameter(
     *          name="problem",
     *          description="Report problem id",
     *          required=true,
     *          in="path",
     *          example="1",
     *          @OA\Schema(
     *              type="integer"
     *          )
     *      ),
     *     @OA\Response(
     *         response="204",
     *         description="Report problem deleted successfully"
     *     ),
     *     @OA\Response(
     *         response="404",
     *         description="Report problem not found"
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

    public function destroy (ReportProblem $problem): \Illuminate\Http\JsonResponse
    {
        $problem->delete();

        return $this->respondNoContent();
    }

    /**
     * @OA\Delete(
     *     path="/api/v1/admin/report-problem",
     *     operationId="Delete some report-problem items",
     *     tags={"Report a problem"},
     *     summary="Delete some report-problem items",
     *     security={ {"bearerAuth": {}} },
     *     @OA\Response(
     *         response="204",
     *         description="Report-problems were deleted successfully"
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
        ReportProblem::whereIn('id', $request->ids)->delete();

        return $this->respondNoContent();
    }

    protected function uploadProductImages($images, $report_problems_id) {
        foreach($images as $image) {
            $imageName = time() . Str::random(10) . "." . $image->extension();

//dd($image->extension());
            $path = $image->storeAs('uploads/problems', $imageName, 'public');
            $image_path_storage = '/storage/'.$path;
            $image_path_public = '/public/'.$path;
	
            ReportProblemsImage::create([
                'report_problems_id' => $report_problems_id,
                'image_url' =>  $image_path_storage,
            ]);
            // UploadProductImageJob::dispatch($productId, $image_path_public);
        }
    }
}
