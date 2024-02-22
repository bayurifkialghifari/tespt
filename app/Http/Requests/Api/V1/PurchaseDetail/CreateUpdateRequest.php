<?php

namespace App\Http\Requests\Api\V1\PurchaseDetail;

use App\Traits\isApiRequest;
use Illuminate\Foundation\Http\FormRequest;

class CreateUpdateRequest extends FormRequest
{
    use isApiRequest;

    public function rules(): array
    {
        return [
            'purchase_id' => 'required|exists:purchases,id',
            'good_id' => 'required|exists:goods,id',
            'quantity' => 'required|integer|min:1',
            'price' => 'required|integer|min:1',
            'total' => 'required|integer|min:1',
        ];
    }
}
