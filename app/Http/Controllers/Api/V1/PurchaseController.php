<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\V1\GetAllRequest;
use App\Http\Requests\Api\V1\Purchase\CreateUpdateRequest;
use App\Models\Purchase;
use Illuminate\Support\Facades\DB;

class PurchaseController extends Controller
{
    public function get(GetAllRequest $request) {
        return $this->respondWithSuccess($this->getDataWithFilter(new Purchase, $request, callback: function ($model, $request) {
            $model->where('user_id', $request->user()->id);
            $model->join('users', 'purchases.user_id', '=', 'users.id');
            $model->join('goods', 'purchases.good_id', '=', 'goods.id');
            $model->select('purchases.*', DB::raw('purchases.quantity * purchases.price as total'), 'users.name as user_name', 'goods.name as good_name', 'goods.price as good_price');

            return $model;
        }, searchAble: [
            'users.name',
            'purchases.quantity',
            'purchases.created_at',
            'purchases.total',
            'goods.name',
            'goods.price',
        ]));
    }

    public function getDetail($id) {
        $good = Purchase::find($id);

        if (!$good) return $this->respondNotFound();

        return $this->respondWithSuccess($good);
    }

    public function create(CreateUpdateRequest $request) {
        return $this->responseWithCreated(Purchase::create($request->validated()));
    }

    public function update($id, CreateUpdateRequest $request) {
        $good = Purchase::find($id);

        if (!$good) return $this->respondNotFound();

        return $this->respondWithSuccess($good->update($request->validated()));
    }

    public function delete($id) {
        $good = Purchase::find($id);

        if (!$good) return $this->respondNotFound();

        return $this->respondWithSuccess($good->delete());
    }
}
