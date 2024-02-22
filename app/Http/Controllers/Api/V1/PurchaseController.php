<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\V1\GetAllRequest;
use App\Http\Requests\Api\V1\Purchase\CreateUpdateRequest;
use App\Models\Purchase;

class PurchaseController extends Controller
{
    public function get(GetAllRequest $request) {
        return $this->respondWithSuccess($this->getDataWithFilter(new Purchase, $request, callback: function ($model, $request) {
            $model->where('user_id', $request->user()->id);
            $model->join('users', 'purchases.user_id', '=', 'users.id');
            $model->select('purchases.*', 'users.name as user_name');

            return $model;
        }, searchAble: [
            'users.name',
            'purchases.total_price',
            'purchases.total_items',
            'purchases.created_at',
        ]));
    }

    public function getDetail($id) {
        $good = Purchase::with('purchaseDetails', 'purchaseDetails.goods', 'user')->find($id);

        if (!$good) return $this->respondNotFound();

        return $this->respondWithSuccess($good);
    }

    public function create(CreateUpdateRequest $request) {
        return $this->responseWithCreated(Purchase::create($request->validated()));
    }

    public function delete($id) {
        $good = Purchase::find($id);

        if (!$good) return $this->respondNotFound();

        return $this->respondWithSuccess($good->delete());
    }
}
