<?php

namespace Gentcmen\Http\Controllers\API;

use Gentcmen\Http\Controllers\ApiController;
use Gentcmen\Http\Requests\ReferralProgram\CreateReferralProgramRequest;
use Gentcmen\Models\ReferralProgram;

class ReferralProgramController extends ApiController
{
    /**
     * @OA\Get(
     *     path="/api/v1/client/referral-programs",
     *     operationId="Fetch referral programs",
     *     tags={"Referral programs"},
     *     summary="Fetch referral programs",
     *     @OA\Response(
     *         response="200",
     *         description="Referral programs fetched successfully"
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
        $referralPrograms = ReferralProgram::with('programSteps')->get();

        return $this->respondOk($referralPrograms);
    }

    /**
     * @OA\Get(
     *     path="/api/v1/client/referral-programs/{referralProgram}",
     *     operationId="Fetch referral program by id",
     *     tags={"Referral programs"},
     *     summary="Fetch referral program by id",
     *     @OA\Parameter(
     *        name="referralProgram",
     *        description="Program id",
     *        required=true,
     *        in="path",
     *        example="1",
     *        @OA\Schema(
     *            type="integer"
     *        )
     *     ),
     *     @OA\Response(
     *         response="200",
     *         description="Referral program fetched successfully"
     *     ),
     *    @OA\Response(
     *         response="404",
     *         description="Program not found"
     *     ),
     *     @OA\Response(
     *         response="400",
     *         description="Something went wrong"
     *     )
     * )
     *
     * Display a listing of the resource.
     *
     * @param ReferralProgram $referralProgram
     * @return \Illuminate\Http\JsonResponse
     */

    public function getById(ReferralProgram $referralProgram): \Illuminate\Http\JsonResponse
    {
        $referralProgram->load('programSteps');

        return $this->respondOk($referralProgram );
    }

    /**
     * @OA\Post(
     *     path="/api/v1/admin/referral-programs",
     *     operationId="Create referral program",
     *     tags={"Referral programs"},
     *     summary="Create referral program",
     *     security={ {"bearerAuth": {}} },
     *     @OA\Response(
     *         response="201",
     *         description="Referral program is created successfully"
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
     *         @OA\JsonContent(ref="#/components/schemas/CreateReferralProgramVirtualBody")
     *     )
     * )
     *
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\JsonResponse
     */

    public function store(CreateReferralProgramRequest $request): \Illuminate\Http\JsonResponse
    {
        $referralProgram = ReferralProgram::create([
            'name' => $request->name,
            'uri' => $request->uri,
            'lifetime_minutes' => $request->lifetime_minutes,
            'reward' => $request->reward,
        ]);

        return $this->respondCreated(['id' => $referralProgram->id]);
    }

    /**
     * @OA\Put(
     *     path="/api/v1/admin/referral-programs/{program}",
     *     operationId="Update referral program by id",
     *     tags={"Referral programs"},
     *     summary="Update referral program by id",
     *     @OA\Parameter(
     *        name="program",
     *        description="Program id",
     *        required=true,
     *        in="path",
     *        example="1",
     *        @OA\Schema(
     *            type="integer"
     *        )
     *     ),
     *     @OA\Response(
     *         response="200",
     *         description="Referral program updated successfully"
     *     ),
     *    @OA\Response(
     *         response="404",
     *         description="Program not found"
     *     ),
     *     @OA\Response(
     *         response="400",
     *         description="Something went wrong"
     *     ),
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(ref="#/components/schemas/CreateReferralProgramVirtualBody")
     *     )
     * )
     *
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\JsonResponse
     */

    public function update(CreateReferralProgramRequest $request, ReferralProgram $program)
    {
        $program->update([
            'name' => $request->name,
            'uri' => $request->uri
        ]);

        if ($request->lifetime_minutes)
        {
            $program->update([
               'lifetime_minutes' => $request->lifetime_minutes
            ]);
        }

        return $this->respondOk(['id' => $program->id]);
    }

    /**
     * @OA\Delete(
     *     path="/api/v1/admin/referral-programs/{program}",
     *     operationId="Delete referral program by id",
     *     tags={"Referral programs"},
     *     summary="Delete referral program by id",
     *     @OA\Parameter(
     *        name="program",
     *        description="Program id",
     *        required=true,
     *        in="path",
     *        example="1",
     *        @OA\Schema(
     *            type="integer"
     *        )
     *     ),
     *     @OA\Response(
     *         response="204",
     *         description="Referral program deleted successfully"
     *     ),
     *    @OA\Response(
     *         response="404",
     *         description="Program not found"
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

    public function destroy(ReferralProgram $program): \Illuminate\Http\JsonResponse
    {
        $program->delete();

        return $this->respondNoContent();
    }
}
