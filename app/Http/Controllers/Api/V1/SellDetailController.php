<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\V1\SellDetail\CreateUpdateRequest;
use App\Models\Sell;
use App\Models\SellDetail;
use Illuminate\Support\Facades\DB;

class SellDetailController extends Controller
{
    public function getDetail($id) {
        $good = SellDetail::find($id);

        if (!$good) return $this->respondNotFound();

        return $this->respondWithSuccess($good);
    }

    public function create(CreateUpdateRequest $request) {
        $data = SellDetail::create($request->validated());

        // Update purchase info
        $this->updatePurchaseInfo($request->sell_id);

        return $this->responseWithCreated($data);
    }

    public function update($id, CreateUpdateRequest $request) {
        $good = SellDetail::find($id);

        if (!$good) return $this->respondNotFound();

        $good = $good->update($request->validated());

        // Update purchase info
        $this->updatePurchaseInfo($request->sell_id);

        return $this->respondWithSuccess();
    }

    public function delete($id) {
        $good = SellDetail::find($id);

        if (!$good) return $this->respondNotFound();

        // Update purchase info
        $this->updatePurchaseInfo($good->sell_id);

        return $this->respondWithSuccess($good->delete());
    }

    public function updatePurchaseInfo($sell_id) {
        $get_total = SellDetail::where('sell_id', $sell_id)->select(DB::raw(
            'SUM(price) as total_price, COUNT(*) as total_items'
        ))->first();

        $purchase = Sell::find($sell_id);
        $purchase->total_price = $get_total->total_price;
        $purchase->total_items = $get_total->total_items;
        $purchase->save();
    }
}
