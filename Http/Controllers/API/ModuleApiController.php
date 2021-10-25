<?php

namespace Modules\AuthPassport\Http\Controllers\API;

use Carbon\Carbon;
use Illuminate\Http\JsonResponse;
use App\Http\Controllers\API\ApiBaseController;

class ModuleApiController extends ApiBaseController
{

    /**
     * Return json structure with token
     *
     * @param $personalAccessToken
     * @param  array  $data
     * @param  string|null  $message
     * @param  int  $code
     *
     * @return JsonResponse
     */
    protected function withToken(
        $personalAccessToken,
        array $data = [],
        string $message = null,
        int $code = 200
    ): JsonResponse
    {
        $tokenData = [
            'access_token' => $personalAccessToken->accessToken,
            'token_type'   => 'Bearer',
            'expires_at'   => Carbon::parse($personalAccessToken->token->expires_at)->toDateTimeString(),
        ];

        return $this->successResponse(array_merge($tokenData, $data), $message, $code);
    }
}
