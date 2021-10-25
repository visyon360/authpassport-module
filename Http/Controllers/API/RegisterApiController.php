<?php


namespace Modules\AuthPassport\Http\Controllers\API;

use Illuminate\Auth\Events\Registered;
use Illuminate\Database\Eloquent\Model;
use Modules\AuthPassport\Traits\AuthUser;
use Modules\AuthPassport\Http\Requests\API\RegisterRequest;
use Illuminate\Http\JsonResponse;
use Modules\AuthPassport\Entities\AuthPassportUser;
use Modules\Users\Entities\User;

class RegisterApiController extends ModuleApiController
{

    use AuthUser;

    /**
     * API Register, on success return JWT Auth Token with User.
     *
     * @OA\Post(
     *     path="/register",
     *     summary="Register User",
     *     description="Register User",
     *     operationId="register",
     *     tags={"Auth"},
     *     @OA\Parameter(name="name",in="query",description="Name of user",required=true,@OA\Schema(type="string")),
     *     @OA\Parameter(name="lastname",in="query",description="Lastname of user",required=true,@OA\Schema(type="string")),
     *     @OA\Parameter(name="email",in="query",description="The user mail for login",required=true,@OA\Schema(type="string")),
     *     @OA\Parameter(name="password",in="query",description="The password in clear text. Min 6 characters.",required=true,@OA\Schema(type="string")),
     *     @OA\Parameter(name="password_confirmation",in="query",description="Confirm password",required=true,@OA\Schema(type="string")),
     *     @OA\Response(response=200,description="User created successfully",@OA\Schema(type="array",ref="#/definitions/User"))
     * )
     * @param  RegisterRequest  $request
     *
     * @return JsonResponse
     */
    public function __invoke(RegisterRequest $request): JsonResponse
    {
        event(new Registered($user = $this->create($request->all())));

        auth()->login($user);

        [$user, $token] = $this->getToken();
        return $this->withToken($token, $user, 'User registered successfully');
    }

    /**
     * Create a new user instance after a valid registration.
     *
     * @param  array  $data
     *
     * @return Model|User
     */
    protected function create(array $data)
    {
        return User::create(
            [
                'name'     => $data['name'],
                'email'    => $data['email'],
                'password' => $data['password'],
            ]
        );
    }
}
