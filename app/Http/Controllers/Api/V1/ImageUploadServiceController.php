<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\V1\ImageUploadRequest;
use App\Traits\WithSaveFile;

class ImageUploadServiceController extends Controller
{
    use WithSaveFile;

    public function upload(ImageUploadRequest $request) {
        $request->validated();

        return $this->responseWithCreated($this->saveFile($request->file('image'), $request->save_to, $request->save_to));
    }
}
