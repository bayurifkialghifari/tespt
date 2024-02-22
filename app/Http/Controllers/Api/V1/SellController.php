<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\V1\GetAllRequest;
use App\Http\Requests\Api\V1\Sell\CreateUpdateRequest;
use App\Models\Sell;

class SellController extends Controller
{
    public function get(GetAllRequest $request) {
        return $this->respondWithSuccess($this->getDataWithFilter(new Sell, $request, callback: function ($model, $request) {

            if($request->status != 'all') {
                $model->where('sells.status', $request->status ?? 0);
            }

            if($request->user()->hasRole('staff')) {
                $model->where('sells.user_id', $request->user()->id);
            }

            $model->leftJoin('sell_approvals', 'sells.id', '=', 'sell_approvals.sell_id');
            $model->join('users', 'sells.user_id', '=', 'users.id');
            $model->select('sells.*', 'users.name as user_name', 'sell_approvals.code as approval_code');

            return $model;
        }, searchAble: [
            'users.name',
            'sells.total_price',
            'sells.total_items',
            'sells.created_at',
            'sell_approvals.code',
        ]));
    }

    public function getDetail($id) {
        $good = Sell::with('sellDetails', 'sellDetails.goods', 'user', 'sellApproval')->find($id);

        if (!$good) return $this->respondNotFound();

        return $this->respondWithSuccess($good);
    }

    public function create(CreateUpdateRequest $request) {
        return $this->responseWithCreated(Sell::create($request->validated()));
    }

    public function delete($id) {
        $good = Sell::find($id);

        if (!$good) return $this->respondNotFound();

        return $this->respondWithSuccess($good->delete());
    }
}
