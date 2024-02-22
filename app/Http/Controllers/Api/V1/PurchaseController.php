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

            if($request->status != 'all') {
                $model->where('purchases.status', $request->status ?? 0);
            }

            $model->where('purchases.user_id', $request->user()->id);
            $model->leftJoin('purchase_approvals', 'purchases.id', '=', 'purchase_approvals.purchase_id');
            $model->join('users', 'purchases.user_id', '=', 'users.id');
            $model->select('purchases.*', 'users.name as user_name', 'purchase_approvals.code as approval_code');

            return $model;
        }, searchAble: [
            'users.name',
            'purchases.total_price',
            'purchases.total_items',
            'purchases.created_at',
            'purchase_approvals.code',
        ]));
    }

    public function getDetail($id) {
        $good = Purchase::with('purchaseDetails', 'purchaseDetails.goods', 'user', 'purchaseApprovals')->find($id);

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
