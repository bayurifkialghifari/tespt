<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\V1\GetAllRequest;
use App\Http\Requests\Api\V1\Purchase\CreateUpdateRequest;
use App\Http\Requests\Api\V1\PurchaseApproval\ChangeStatusRequest;
use App\Models\Purchase;
use App\Models\PurchaseApproval;

class PurchaseApprovalController extends Controller
{
    public function changeStatus(ChangeStatusRequest $request) {
        $request->validated();

        // Check if purchase approval is exist
        $purchaseApproval = PurchaseApproval::where('purchase_id', $request->purchase_id)->first();

        // Update or create if not exist
        if(!$purchaseApproval) {
            $purchaseApproval = PurchaseApproval::create([
                'purchase_id' => $request->purchase_id,
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
            Purchase::where('id', $request->purchase_id)->update([
                'status' => 2,
                'reject_reason' => $request->reject_reason
            ]);
        } else {
            Purchase::where('id', $request->purchase_id)->update([
                'status' => 1
            ]);

            // Add stok to goods
            $purchase = Purchase::find($request->purchase_id);
            foreach($purchase->purchaseDetails as $purchaseDetail) {
                $purchaseDetail->goods()->update([
                    'quantity' => $purchaseDetail->goods->quantity + $purchaseDetail->quantity
                ]);
            }
        }

        return $this->respondWithSuccess();
    }

    public function generateCode() {
        return 'WH-IN-' . date('d-m-Y') . '-' . PurchaseApproval::count() + 1;
    }
}
