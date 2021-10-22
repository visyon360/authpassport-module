<?php

namespace Modules\AuthPassport\Http\Controllers;

use App\Http\Controllers\AppBaseController;
use App\Http\Resources\UserResource;
use Modules\AuthPassport\Services\ConfirmUserRegisterService;
use Modules\AuthPassport\Services\RegisterUserService;
use Modules\AuthPassport\Services\RegistrationTokenStatusService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Modules\AuthPassport\Http\Requests\ConfirmSignUpRequest;
use Modules\AuthPassport\Http\Requests\SignUpRequest;

class RegisterController extends AppBaseController
{
    /**
     * @param Request $request
     * @return JsonResponse
     *
     * @SWG\Post(
     *      path="/register",
     *      summary="Register a user",
     *      tags={"Register"},
     *      produces={"application/json"},
     *      @SWG\Parameter(
     *          name="body",
     *          in="body",
     *          description="User that should be stored",
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
     *              ),
     *              @SWG\Property(
     *                  property="langIsoCode",
     *                  description="langIsoCode",
     *                  type="string"
     *              ),
     *              @SWG\Property(
     *                  property="district",
     *                  description="district",
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
     *      )
     * )
     */
    public function register(SignUpRequest $request): JsonResponse
    {
        $register_user_service = (new RegisterUserService($request))->make();
        $register_user_service->assignUserRole();

        return $this->successResponse([
            'user' => new UserResource($register_user_service->user),
        ], 'register successful');
    }

    /**
     * @param Request $request
     * @return JsonResponse
     *
     * @SWG\Get(
     *      path="/confirm-register{token}",
     *      summary="Confirm register",
     *      tags={"Register"},
     *      produces={"application/json"},
     *      @SWG\Parameter(
     *          name="token",
     *          description="confirmation token",
     *          type="string",
     *          required=true,
     *          in="path"
     *      ),
     *      @SWG\Response(
     *          response=200,
     *          description="successful operation",
     *          @SWG\Schema(
     *              type="object",
     *              @SWG\Property(
     *                  property="message",
     *                  type="string"
     *              ),
     *              @SWG\Property(
     *                  property="data",
     *                  type="array",
     *                  @SWG\Items(
     *                      @SWG\Property(
     *                          property="token",
     *                          type="string"
     *                      ),
     *                  )
     *              ),
     *          )
     *      )
     * )
     */
    public function confirmRegister(ConfirmSignUpRequest $request): JsonResponse
    {
        $registrationStatusService = (new RegistrationTokenStatusService($request->token))->make();
        $token_status_message = $registrationStatusService->getAccountStatusMessage();
        $status_code = $registrationStatusService->getStatusCode($token_status_message);

        $confirmUserRegisterService = (new ConfirmUserRegisterService($request))->make();
        $bearer_token = $confirmUserRegisterService->getAccessToken($token_status_message);

        return $this->successResponse([
            'token' => $bearer_token,
        ], $token_status_message, $status_code);
    }
}
