<?php

namespace Modules\AuthPassport\Http\Requests\API;

use App\Http\Requests\API\ApiBaseFormRequest;

class LoginFormRequest extends ApiBaseFormRequest
{

    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            $this->username() => 'required|string|email',
            'password'        => 'required|string',
        ];
    }


    /**
     * Get the login username to be used by the controller.
     *
     * @return string
     */
    public function username(): string
    {
        return config('authpassport.username');
    }
}
