<?php

namespace Gentcmen\Http\Controllers\API;

use Gentcmen\Http\Controllers\ApiController;
use Gentcmen\Http\Requests\General\DestroyItemsRequest;
use Gentcmen\Http\Requests\ReferralProgramStep\ReferralProgramStepRequest;
use Gentcmen\Models\ReferralProgram;
use Gentcmen\Models\ReferralProgramStep;

class ReferralProgramStepController extends ApiController
{
    /**
     * @OA\Post(
     *     path="/api/v1/admin/referral-programs/{referralProgram}/referral-program-step",
     *     operationId="Create referral program step",
     *     tags={"Referral programs"},
     *     summary="Create referral program step",
     *     security={ {"bearer": {}} },
     *     @OA\Parameter(
     *        name="referralProgram",
     *        description="Entity id",
     *        required=true,
     *        in="path",
     *        example="1",
     *        @OA\Schema(
     *            type="integer"
     *        )
     *     ),
     *     @OA\Response(
     *         response="201",
     *         description="Referral program step created successfully"
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
     *         @OA\JsonContent(ref="#/components/schemas/CreateReferralProgramStepVirtualBody")
     *     )
     * )
     *
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\JsonResponse
     */

    public function store(ReferralProgramStepRequest $request, ReferralProgram $referralProgram): \Illuminate\Http\JsonResponse
    {
        $referralProgramStep = ReferralProgramStep::create([
            'name' => $request->name,
            'goal' => $request->goal,
            'reward' => $request->reward,
            'referral_program_id' => $referralProgram->id,
        ]);

        return $this->respondCreated(['id' => $referralProgramStep->id]);
    }

    /**
     * @OA\Put(
     *     path="/api/v1/admin/referral-program-steps/{step}",
     *     operationId="Update program step",
     *     tags={"Referral program steps"},
     *     summary="Update program step",
     *     security={ {"bearer": {}} },
     *     @OA\Parameter(
     *        name="step",
     *        description="Entity id",
     *        required=true,
     *        in="path",
     *        example="1",
     *        @OA\Schema(
     *            type="integer"
     *        )
     *     ),
     *     @OA\Response(
     *         response="200",
     *         description="Referral program step is updated successfully"
     *     ),
     *     @OA\Response(
     *         response="400",
     *         description="Something went wrong"
     *     ),
     *     @OA\Response(
     *         response="404",
     *         description="Entity not found"
     *     ),
     *     @OA\Response(
     *         response="422",
     *         description="Validation error"
     *     ),
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(ref="#/components/schemas/CreateReferralProgramStepVirtualBody")
     *     )
     * )
     *
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\JsonResponse
     */

    public function update(ReferralProgramStepRequest $request, ReferralProgramStep $step): \Illuminate\Http\JsonResponse
    {
        $step->update($request->validated());

        return $this->respondOk(['id' => $step->id]);
    }

    /**
     * @OA\Delete(
     *     path="/api/v1/admin/referral-program-steps",
     *     operationId="Delete some referral program step items",
     *     tags={"Referral program steps"},
     *     summary="Delete some referral program step items",
     *     security={ {"bearerAuth": {}} },
     *     @OA\Response(
     *         response="204",
     *         description="Referral program steps were deleted successfully"
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
        ReferralProgramStep::whereIn('id', $request->ids)->delete();

        return $this->respondNoContent();
    }

    /**
     * @OA\Delete (
     *     path="/api/v1/admin/referral-program-steps/{program}",
     *     operationId="Delete program step",
     *     tags={"Referral program steps"},
     *     summary="Delete program step",
     *     security={ {"bearer": {}} },
     *     @OA\Parameter(
     *        name="program",
     *        description="Entity id",
     *        required=true,
     *        in="path",
     *        example="1",
     *        @OA\Schema(
     *            type="integer"
     *        )
     *     ),
     *     @OA\Response(
     *         response="204",
     *         description="Referral program step is deleted successfully"
     *     ),
     *     @OA\Response(
     *         response="400",
     *         description="Something went wrong"
     *     ),
     *     @OA\Response(
     *         response="404",
     *         description="Entity not found"
     *     )
     * )
     *
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\JsonResponse
     */

    public function destroy(ReferralProgramStep $step): \Illuminate\Http\JsonResponse
    {
        $step->delete();

        return $this->respondNoContent();
    }
}
