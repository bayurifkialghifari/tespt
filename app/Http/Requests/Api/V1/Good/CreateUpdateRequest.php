<?php

namespace App\Http\Requests\Api\V1\Good;

use App\Traits\isApiRequest;
use Illuminate\Foundation\Http\FormRequest;

class CreateUpdateRequest extends FormRequest
{
    use isApiRequest;

    public function rules(): array
    {
        return [
            'name' => 'required',
            'price' => 'required|numeric',
            'quantity' => 'required|numeric',
            'description' => 'required',
            'image' => 'nullable|string',
        ];
    }
}
