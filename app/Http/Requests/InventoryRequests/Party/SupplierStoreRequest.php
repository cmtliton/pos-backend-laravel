<?php

namespace App\Http\Requests\InventoryRequests\Party;

use App\Models\InventoryModels\Supplier;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class SupplierStoreRequest extends FormRequest
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
            'name' => 'max:255',
            //'mobileno' => ['required', 'string', Rule::unique(Supplier::class, 'mobileno')],
            'mobileno' => ['required', 'string'],
        ];
    }
}
