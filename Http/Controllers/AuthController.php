<?php

namespace Modules\AuthPassport\Http\Controllers;

use App\Http\Resources\UserResource;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use App\Http\Controllers\AppBaseController;

/**
 * Class AuthController
 *
 * @package Modules\AuthPassport\Http\Controllers
 */
class AuthController extends AppBaseController
{


    /**
     * @param Request $request
     * @return JsonResponse
     *
     * @SWG\Post(
     *      path="/login",
     *      summary="Login a user",
     *      tags={"Auth"},
     *      produces={"application/json"},
     *      @SWG\Parameter(
     *          name="body",
     *          in="body",
     *          description="User to log in",
     *          required=true,
     *          @SWG\Schema(
     *              @SWG\Property(
     *                  property="email",
     *                  description="email",
     *                  type="string",
     *                  format="email"
     *              ),
     *              @SWG\Property(
     *                  property="password",
     *                  description="password",
     *                  type="string"
     *              )
     *          )
     *      ),
     *      @SWG\Response(
     *          response=200,
     *          description="successful operation",
     *          @SWG\Schema(
     *              type="object",
     *              @SWG\Property(
     *                  property="success",
     *                  type="boolean"
     *              ),
     *              @SWG\Property(
     *                  property="data",
     *                  type="array",
     *                  @SWG\Items(
     *                      @SWG\Property(
     *                          property="user",
     *                          type="array",
     *                          @SWG\Items(ref="#/definitions/UserResource")
     *                      ),
     *                  )
     *              ),
     *              @SWG\Property(
     *                  property="message",
     *                  type="string"
     *              )
     *          )
     *      ),
     *      @SWG\Response(
     *          response=401,
     *          description="Unauthorized access",
     *          @SWG\Schema(
     *              type="object",
     *              @SWG\Property(
     *                  property="message",
     *                  type="string",
     *                  example="Unauthorized access"
     *              )
     *          )
     *      )
     * )
     */
    public function login(): JsonResponse
    {
        $login_credentials = request(['email', 'password']);
        if (auth()->attempt($login_credentials)) {
            $user_login_token = createAccessToken(auth()->user())->accessToken;
            return $this->successResponse([
                'token' => $user_login_token,
                'user' => auth()->user(),
            ], 'Login successful');
        }

        return $this->successResponse([],'Unauthorized access', 401);
    }


    /**
     * @param Request $request
     * @return JsonResponse
     *
     * @SWG\Get(
     *      path="/logout",
     *      summary="Logout a user",
     *      tags={"Auth"},
     *      produces={"application/json"},
     *     @SWG\Parameter(
     *         name="Authorization",
     *         in="header",
     *         description="Bearer {access-token}",
     *         required=true,
     *         type="string",
     *         @SWG\Schema(
     *              type="bearerAuth"
     *         )
     *     ),
     *      @SWG\Response(
     *          response=200,
     *          description="successful operation",
     *          @SWG\Schema(
     *              type="object",
     *              @SWG\Property(
     *                  property="success",
     *                  type="boolean"
     *              ),
     *              @SWG\Property(
     *                  property="data",
     *                  type="array",
     *                  @SWG\Items(
     *                      @SWG\Property(
     *                          property="revoked",
     *                          type="boolean",
     *                          @SWG\Items(ref="#/definitions/UserResource")
     *                      ),
     *                  )
     *              ),
     *              @SWG\Property(
     *                  property="message",
     *                  type="string"
     *              )
     *          )
     *      )
     * )
     */
    public function logout(): JsonResponse
    {
        $token = request()->user()->token();

        return $this->successResponse([
            'revoked' => $token->revoke(),
        ], 'Logout successful');
    }

    /**
     * @param Request $request
     * @return JsonResponse
     *
     * @SWG\Get(
     *      path="/user",
     *      summary="Get auth user",
     *      tags={"Auth"},
     *      produces={"application/json"},
     *     @SWG\Parameter(
     *         name="Authorization",
     *         in="header",
     *         description="Bearer {access-token}",
     *         required=true,
     *         type="string",
     *     ),
     *      @SWG\Response(
     *          response=200,
     *          description="successful operation",
     *          @SWG\Schema(
     *              type="object",
     *              @SWG\Property(
     *                  property="success",
     *                  type="boolean"
     *              ),
     *              @SWG\Property(
     *                  property="data",
     *                  type="array",
     *                  @SWG\Items(
     *                      @SWG\Property(
     *                          property="user",
     *                          type="array",
     *                          @SWG\Items(ref="#/definitions/UserResource")
     *                      ),
     *                  )
     *              ),
     *              @SWG\Property(
     *                  property="message",
     *                  type="string"
     *              )
     *          )
     *      )
     * )
     */
    public function getUser(): JsonResponse
    {
        return $this->successResponse([
            'user' => new UserResource(auth()->user()),
        ], 'User retrieved successfully');
    }
}
