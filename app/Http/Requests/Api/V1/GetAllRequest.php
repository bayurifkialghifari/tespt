<?php

namespace App\Http\Requests\Api\V1;

use Illuminate\Foundation\Http\FormRequest;

class GetAllRequest extends FormRequest
{
    use IsApiRequest;

    public function rules(): array
    {
        return [
            'page' => 'nullable|integer|min:1',
            'per_page' => 'nullable|integer|min:1|max:100',
            'search' => 'nullable|string',
            'order' => 'nullable|in:asc,desc',
            'sort' => 'nullable|in:id,name,price,created_at,updated_at',
        ];
    }
}
