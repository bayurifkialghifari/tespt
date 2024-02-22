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
    public function get(GetAllRequest $request) {
        return $this->respondWithSuccess($this->getDataWithFilter(new Purchase, $request, callback: function ($model, $request) {
            $model->join('purchases.users', 'purchases.user_id', '=', 'users.id');
            $model->leftJoin('purchase_approvals', 'purchases.id', '=', 'purchase_approvals.purchase_id');
            $model->where('purchases.status', $request->status ?? 0);
            $model->select('purchases.*', 'users.name as user_name', 'purchase_approvals.code as approval_code');

            return $model;
        }, searchAble: [
            'users.name',
            'purchases.total_price',
            'purchases.total_items',
            'purchases.status',
            'purchases.created_at',
            'purchase_approvals.code',
        ]));
    }

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
        }

        return $this->respondWithSuccess();
    }

    public function generateCode() {
        return 'WH-IN-' . date('d-m-Y') . '-' . PurchaseApproval::count() + 1;
    }
}
