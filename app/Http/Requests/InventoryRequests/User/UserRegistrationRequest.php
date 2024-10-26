<?php

namespace App\Http\Requests\InventoryRequests\User;

use App\Models\InventoryModels\User;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UserRegistrationRequest extends FormRequest
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
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'name' => 'required|string|max:255',
            'username' => ['required', 'email', 'string', Rule::unique(User::class, 'username')],
            'password' => 'required|string|min:8|confirmed',
            'mobileno' => ['required', 'string', Rule::unique(User::class, 'mobileno')],
        ];
    }
}
