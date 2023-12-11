<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class ChangeUserPasswordRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'current_password' => ['required', 'min:5', 'max:100', 'current_password'],
            'password' => ['required', 'min:5', 'max:100', 'confirmed'],
        ];
    }
}
