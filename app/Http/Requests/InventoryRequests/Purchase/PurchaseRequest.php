<?php

namespace App\Http\Requests\InventoryRequests\Purchase;

use Illuminate\Foundation\Http\FormRequest;

class PurchaseRequest extends FormRequest
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
            'cart_total_quantity' => 'required|numeric',
            'cart_total_amount' => 'required|numeric',
            'company_id' => 'required|integer',
            'user_id' => 'required|integer',
        ];
    }
}
