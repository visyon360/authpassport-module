<?php

namespace Modules\AuthPassport\Http\Controllers;

use App\Http\Requests\ResetPasswordRequest;
use Modules\AuthPassport\Services\ResetPasswordService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use App\Http\Controllers\AppBaseController;
use Modules\AuthPassport\Http\Requests\ForgotPasswordRequest;

/**
 * Class PasswordController
 *
 * @package Modules\AuthPassport\Http\Controllers
 */
class PasswordController extends AppBaseController
{

    /**
     * @param Request $request
     * @return JsonResponse
     *
     * @SWG\Post(
     *      path="/forgot-password",
     *      summary="Request forgot password email",
     *      tags={"Password"},
     *      produces={"application/json"},
     *      @SWG\Parameter(
     *          name="body",
     *          in="body",
     *          description="Request forgot password email",
     *          required=true,
     *          @SWG\Schema(
     *              @SWG\Property(
     *                  property="email",
     *                  description="email",
     *                  type="string",
     *                  format="email"
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
     *                  property="message",
     *                  type="string",
     *                  example="link sent successfully"
     *              )
     *          )
     *      ),
     *      @SWG\Response(
     *          response=401,
     *          description="Invalid user",
     *          @SWG\Schema(
     *              type="object",
     *              @SWG\Property(
     *                  property="success",
     *                  type="boolean"
     *              ),
     *              @SWG\Property(
     *                  property="message",
     *                  type="string",
     *                  example="invalid user"
     *              )
     *          )
     *      )
     * )
     */
    public function forgotPassword(ForgotPasswordRequest $request)
    {
        $resetPasswordService = (new ResetPasswordService($request));
        $resetPasswordService->makeForgot();
        $response = $resetPasswordService->response;

        return $this->successResponse([], $response['message'], $response['code']);
    }

    /**
     * @param Request $request
     * @return JsonResponse
     *
     * @SWG\Post(
     *      path="/reset-password",
     *      summary="Reset password endpoint",
     *      tags={"Password"},
     *      produces={"application/json"},
     *      @SWG\Parameter(
     *          name="body",
     *          in="body",
     *          description="Reset password",
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
     *                  type="string",
     *                  format="string"
     *              ),
     *              @SWG\Property(
     *                  property="password_confirmation",
     *                  description="password_confirmation",
     *                  type="string",
     *                  format="string"
     *              ),
     *              @SWG\Property(
     *                  property="token",
     *                  description="token",
     *                  type="string",
     *                  format="string"
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
     *                  type="boolean",
     *              ),
     *              @SWG\Property(
     *                  property="message",
     *                  type="string",
     *                  example="password reset successfully"
     *              )
     *          )
     *      ),
     *      @SWG\Response(
     *          response=401,
     *          description="Invalid token",
     *          @SWG\Schema(
     *              type="object",
     *              @SWG\Property(
     *                  property="success",
     *                  type="boolean"
     *              ),
     *              @SWG\Property(
     *                  property="message",
     *                  type="string",
     *                  example="invalid token"
     *              )
     *          )
     *      ),
     *      @SWG\Response(
     *          response=429,
     *          description="throttled reset attempt",
     *          @SWG\Schema(
     *              type="object",
     *              @SWG\Property(
     *                  property="success",
     *                  type="boolean"
     *              ),
     *              @SWG\Property(
     *                  property="message",
     *                  type="string",
     *                  example="throttled reset attempt"
     *              )
     *          )
     *      )
     * )
     */
    public function resetPassword(ResetPasswordRequest $request)
    {
        $resetPasswordService = (new ResetPasswordService($request));
        $resetPasswordService->makeReset();
        $response = $resetPasswordService->response;

        return $this->successResponse([], $response['message'], $response['code']);
    }
}
