<?php

declare(strict_types=1);

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rules\Password;

class LoginUserRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'email' => ['required', 'string', 'email', 'max:255'],
            'password' => ['required', 'string', Password::default()],
        ];
    }

    public function messages(): array
    {
        return [
            'email.max' => 'El campo email debe tener como maximo 255 caracteres.',
            'email.email' => 'El campo email debe tener formato correcto.',
            'email.unique' => 'El campo email ya esta registrado.',
            'password.required' => 'El campo password es obligatorio.',
            'password.default' => 'El campo password debe tener como minimo 8 caracteres.',
        ];
    }
}
