<?php

namespace App\Http\Requests\Api\V1\SellApproval;

use App\Traits\isApiRequest;
use Illuminate\Foundation\Http\FormRequest;

class ChangeStatusRequest extends FormRequest
{
    use IsApiRequest;

    public function rules(): array
    {
        return [
            'sell_id' => 'required|exists:sells,id',
            'user_id' => 'required|exists:users,id',
            'is_approved' => 'required|in:0,1,2',
            'reject_reason' => 'nullable|string',
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
