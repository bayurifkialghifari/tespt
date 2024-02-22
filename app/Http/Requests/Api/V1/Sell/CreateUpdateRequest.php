<?php

namespace App\Http\Requests\Api\V1\Sell;

use App\Traits\isApiRequest;
use Illuminate\Foundation\Http\FormRequest;

class CreateUpdateRequest extends FormRequest
{
    use isApiRequest;

    public function rules(): array
    {
        return [
            'user_id' => 'required|exists:users,id',
        ];
    }

    // Before validation
    public function prepareForValidation()
    {
        $this->merge([
            'user_id' => $this->user()->id,
        ]);
    }
}
