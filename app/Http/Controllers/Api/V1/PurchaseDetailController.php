<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\V1\PurchaseDetail\CreateUpdateRequest;
use App\Models\Purchase;
use App\Models\PurchaseDetail;
use Illuminate\Support\Facades\DB;

class PurchaseDetailController extends Controller
{
    public function getDetail($id) {
        $good = PurchaseDetail::find($id);

        if (!$good) return $this->respondNotFound();

        return $this->respondWithSuccess($good);
    }

    public function create(CreateUpdateRequest $request) {
        $data = PurchaseDetail::create($request->validated());

        // Update purchase info
        $this->updatePurchaseInfo($request->purchase_id);

        return $this->responseWithCreated($data);
    }

    public function update($id, CreateUpdateRequest $request) {
        $good = PurchaseDetail::find($id);

        if (!$good) return $this->respondNotFound();

        $good = $good->update($request->validated());

        // Update purchase info
        $this->updatePurchaseInfo($request->purchase_id);

        return $this->respondWithSuccess();
    }

    public function delete($id) {
        $good = PurchaseDetail::find($id);

        if (!$good) return $this->respondNotFound();

        // Update purchase info
        $this->updatePurchaseInfo($good->purchase_id);

        return $this->respondWithSuccess($good->delete());
    }

    public function updatePurchaseInfo($purchase_id) {
        $get_total = PurchaseDetail::where('purchase_id', $purchase_id)->select(DB::raw(
            'SUM(price) as total_price, COUNT(*) as total_items'
        ))->first();

        $purchase = Purchase::find($purchase_id);
        $purchase->total_price = $get_total->total_price;
        $purchase->total_items = $get_total->total_items;
        $purchase->save();
    }
}
