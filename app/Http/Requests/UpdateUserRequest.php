<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateUserRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'name' => ['required', 'min:3', 'max:255'],
            'email' => [
                'required', 'max:255', 'email:filter,strict,spoof',
                Rule::unique('users')
                    ->ignore($this->user()->id),
            ],
        ];
    }

}
