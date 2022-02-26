<?php

namespace App\Http\Requests;

use JetBrains\PhpStorm\ArrayShape;

class SignupRequest extends BaseRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */

    #[ArrayShape([
        'name'              => "string",
        'email'             => "string",
        'phone'             => "string",
        'password'          => "string",
        'confirm_password'  => "string"
    ])] public function rules(): array
    {
        return [
            'name'              => 'required|string|max:255',
            'email'             => 'required|string|email|unique:users,email',
            'phone'             => 'required|string|unique:users,phone',
            'password'          => 'required|string|min:6',
            'confirm_password'  => 'required|same:password',
        ];
    }
}
