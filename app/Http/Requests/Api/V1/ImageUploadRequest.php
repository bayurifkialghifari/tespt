<?php

namespace App\Http\Requests\Api\V1;

use App\Traits\isApiRequest;
use Illuminate\Foundation\Http\FormRequest;

class ImageUploadRequest extends FormRequest
{
    use IsApiRequest;

    public function rules(): array
    {
        return [
            'save_to' => 'required|string',
            'image' => 'required|image:jpeg,png,jpg,gif,svg|max:2048',
        ];
    }
}
