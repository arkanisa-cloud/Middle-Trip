<?php

namespace App\Http\Requests;

use App\Models\User;
use App\Rules\ValidEmailAddress;
use App\Rules\ValidNik;
use App\Rules\ValidWhatsAppNumber;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class ProfileUpdateRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'name' => ['required', 'string', 'max:255'],
            'email' => [
                'required',
                'string',
                'lowercase',
                'email',
                'max:255',
                Rule::unique(User::class)->ignore($this->user()->id),
                new ValidEmailAddress,
            ],
            'phone' => ['nullable', 'string', 'max:25', new ValidWhatsAppNumber],
            'nik' => ['nullable', 'string', 'max:20', new ValidNik],
            'avatar' => ['nullable', 'image', 'mimes:jpeg,png,jpg,webp', 'max:2048'],
            'remove_avatar' => ['nullable', 'boolean'],
        ];
    }
}
