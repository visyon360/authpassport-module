<?php

namespace Modules\AuthPassport\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class SignUpRequest extends FormRequest
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
            'email'       => 'required|email|unique:users',
            'password'    => 'required|min:8',
            'langIsoCode' => 'required|exists:App\Models\Locale,iso',
            'district'    => 'required|string|exists:App\Models\District,slug',
        ];
    }
}
