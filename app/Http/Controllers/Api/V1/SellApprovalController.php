<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\V1\SellApproval\ChangeStatusRequest;
use App\Models\Sell;
use App\Models\SellApproval;

class SellApprovalController extends Controller
{
    public function changeStatus(ChangeStatusRequest $request) {
        $request->validated();

        // Check if purchase approval is exist
        $purchaseApproval = SellApproval::where('sell_id', $request->sell_id)->first();

        // Update or create if not exist
        if(!$purchaseApproval) {
            $purchaseApproval = SellApproval::create([
                'sell_id' => $request->sell_id,
                'user_id' => $request->user_id,
                'code' => $request->is_approved ? $this->generateCode() : '-',
                'is_approved' => $request->is_approved,
            ]);
        } else {
            $purchaseApproval->update([
                'is_approved' => $request->is_approved,
            ]);
        }

        if($request->is_approved == 0) {
            Sell::where('id', $request->sell_id)->update([
                'status' => 2,
                'reject_reason' => $request->reject_reason
            ]);
        } else {
            Sell::where('id', $request->sell_id)->update([
                'status' => 1
            ]);

            // Add stok to goods
            $purchase = Sell::find($request->sell_id);
            foreach($purchase->sellDetails as $sellDetails) {
                $sellDetails->goods()->update([
                    'quantity' => $sellDetails->goods->quantity - $sellDetails->quantity
                ]);
            }
        }

        return $this->respondWithSuccess();
    }

    public function generateCode() {
        return 'WH-OUT-' . date('d-m-Y') . '-' . SellApproval::count() + 1;
    }
}
