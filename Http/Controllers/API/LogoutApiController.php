<?php

namespace Modules\AuthPassport\Http\Controllers\API;

use Illuminate\Http\JsonResponse;

/**
 * Class Auth/LogoutControllerController
 *
 * @package App\Http\Controllers\Api\v1\Auth
 */
class LogoutApiController extends ModuleApiController
{

    /**
     * Invalidate the token, so user cannot use it anymore
     *
     * @OA\Post(
     *     path="/logout",
     *     summary="Logout User",
     *     description="Log the user out (Invalidate the token).",
     *     operationId="logout",
     *     tags={"Auth"},
     *
     *     @OA\Header(header="api_key",description="Api key header",required=false,@OA\Schema(type="string")),
     *     @OA\Response(response=200,description="Successfully logged out",@OA\JsonContent(type="string")),
     *     @OA\Response(response="401",description="Unauthorized"),
     *     security={{"bearerAuth": {}}}
     * )
     *
     * @return JsonResponse
     */
    public function __invoke(): JsonResponse
    {
        auth()->user()->token()->revoke();

        return $this->successResponse([], 'Successfully logged out');
    }
}
