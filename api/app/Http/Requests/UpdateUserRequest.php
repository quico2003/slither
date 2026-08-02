<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class UpdateUserRequest extends FormRequest
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
        $userId = $this->route('user')?->id;

        return [
            'name' => 'sometimes|required|string|max:255',
            'email' => ['sometimes', 'required', 'email', 'unique:users,email,'.$userId],
            'password' => 'sometimes|nullable|string|min:8',
            'role' => 'sometimes|required|in:admin,user',
        ];
    }
}
