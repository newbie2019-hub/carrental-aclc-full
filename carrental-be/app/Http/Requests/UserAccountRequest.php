<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Validation\Rules\Password;

class UserAccountRequest extends FormRequest
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
            'first_name' => 'bail|required|max:120',
            'last_name' => 'bail|required|max:120',
            'gender' => 'bail|required',
            'address' => 'bail|required|min:10',
            'contact_number' => 'bail|required|min:11',
            'birthday' => 'bail|required|date',
            'email' => 'bail|required|unique:users,email',
            'password' => ['bail','required', Password::min(5)],
            'valid_id_uploaded' => 'bail|required',
            'profile_img_uploaded' => 'bail|required',
        ];
    }

    public function failedValidation(Validator $validator){
        throw new HttpResponseException(response()->json($validator->errors(), 422));
    }
}
