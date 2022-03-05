<?php

namespace App\Http\Requests;

class UpdateProfileRequest extends BaseRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */

    public function rules(): array
    {
        return [
            'first_name'        => 'required|string|max:255',
            'last_name'         => 'required|string|max:255',
            'email'             => 'required|string|email|unique:users,email',
            'phone_number'      => 'required|string|unique:users,phone_number',
            'password'          => 'required|string|min:6',
        ];
    }
}
