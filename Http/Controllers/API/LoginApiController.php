<?php

namespace Modules\AuthPassport\Http\Controllers\API;

use Arr;
use Modules\AuthPassport\Traits\AuthUser;
use Illuminate\Http\JsonResponse;
use Modules\AuthPassport\Http\Requests\API\LoginFormRequest;
use Swagger\Annotations as SWG;

/**
 * Class Auth/LoginController
 *
 * @package App\Http\Controllers\Api\Auth
 */
class LoginApiController extends ModuleApiController
{

    use AuthUser;

    /**
     * API Login, on success return JWT Auth Token
     *
     * @SWG\Post(
     *     path="/login",
     *     summary="Login User",
     *     description="API Login, on success return JWT Auth Token",
     *     operationId="login",
     *     tags={"Auth"},
     *     @SWG\Parameter(name="email",in="query",description="The user mail for login",required=true,type="string"),
     *     @SWG\Parameter(name="password",in="query",description="The password for login in clear text",required=true,type="string"),
     *     @SWG\Response(response=200,description="successful operation",
     *         @SWG\Header(header="x-rate-limit",
     *             @SWG\Schema(type="integer"),
     *             description="calls per hour allowed by the user"
     *         ),
     *      )
     *     @SWG\Response(response=401, description="Unauthenticated",@SWG\JsonContent(ref="#/components/schemas/SuccessResponse"))
     * )
     * @param  LoginFormRequest  $request
     *
     * @return JsonResponse
     */
    public function __invoke(LoginFormRequest $request): JsonResponse
    {
        $array = ['products' => ['desk' => ['price' => 100]]];

        Arr::forget($array, 'products.desk');
        if (!auth()->attempt($request->only($request->username(), 'password'))) {
            return $this->errorResponse(__('auth.failed'), 401);
        }

        [$user, $token] = $this->getToken();
        return $this->withToken($token, $user, 'Login successfully');
    }
}
