<?php

namespace App\Http\Requests\InventoryRequests\Product;

use Illuminate\Foundation\Http\FormRequest;

class ProductStoreRequest extends FormRequest
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
            'item_id' => 'required|integer',
            'brand_id' => 'required|integer',
            'name' => 'required|string',
            'purchase_price' => 'required|numeric',
            'mrp' => 'required|numeric',
            'measuring_unit' => 'required|string',
            'status' => 'sometimes|boolean',
            'isPublished' => 'sometimes|boolean',
            'company_id' => 'required|integer',
            'user_id' => 'required|integer',

        ];
    }
}
