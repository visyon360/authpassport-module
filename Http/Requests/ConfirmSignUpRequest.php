<?php

namespace Modules\AuthPassport\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ConfirmSignUpRequest extends FormRequest
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
            'token' => 'exists:App\Models\RegistrationToken,token',
        ];
    }
}
