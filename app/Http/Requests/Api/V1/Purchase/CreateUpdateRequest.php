<?php

namespace App\Http\Requests\Api\V1\Purchase;

use App\Traits\isApiRequest;
use Illuminate\Foundation\Http\FormRequest;

class CreateUpdateRequest extends FormRequest
{
    use isApiRequest;

    public function rules(): array
    {
        return [
            'user_id' => 'required|exists:users,id',
            'good_id' => 'required|exists:goods,id',
            'quantity' => 'required|integer|min:1',
            'price' => 'required|numeric|min:0',
        ];
    }

    // Before validation
    public function prepareForValidation()
    {
        $this->merge([
            'user_id' => $this->user()->id
        ]);
    }
}
