<?php

namespace App\Http\Controllers\Api\V1;

use App\Models\Good;
use App\Http\Controllers\Controller;
use App\Http\Requests\Api\V1\GetAllRequest;
use App\Http\Requests\Api\V1\Good\CreateUpdateRequest;
use Illuminate\Http\Request;

class GoodsController extends Controller
{
    public function get(GetAllRequest $request) {
        return $this->respondWithSuccess($this->getDataWithFilter(new Good, $request));
    }

    public function getListGoods(Request $request) {
        $good = Good::where('name', 'like', '%' . $request->name . '%');
        $good->where('quantity', '>', 0);

        return $this->respondWithSuccess($good->get());
    }

    public function getDetail($id) {
        $good = Good::find($id);

        if (!$good) return $this->respondNotFound();

        return $this->respondWithSuccess($good);
    }

    public function create(CreateUpdateRequest $request) {
        return $this->responseWithCreated(Good::create($request->validated()));
    }

    public function update($id, CreateUpdateRequest $request) {
        $good = Good::find($id);

        if (!$good) return $this->respondNotFound();

        return $this->respondWithSuccess($good->update($request->validated()));
    }

    public function delete($id) {
        $good = Good::find($id);

        if (!$good) return $this->respondNotFound();

        return $this->respondWithSuccess($good->delete());
    }
}
