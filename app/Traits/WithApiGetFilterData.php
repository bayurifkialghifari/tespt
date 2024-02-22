<?php

namespace App\Traits;

use Illuminate\Http\Request;
use Illuminate\Contracts\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

trait WithApiGetFilterData {

    public function getDataWithFilter(Model|Builder $model, Request $request) {
        $new_model = clone $model;

        // Get list field available in the model
        $list_field = $new_model->getFillable();
        $search = $request->input('search');

        // If search is not null
        if($search) {
            $model = $model->where(function ($query) use ($list_field, $search) {
                foreach ($list_field as $key => $field) {
                    if ($key == 0) {
                        $query->where($field, 'like', "%$search%");
                    } else {
                        $query->orWhere($field, 'like', "%$search%");
                    }
                }
            });
        }

        $model = $model->orderBy($request->input('sort', 'created_at'), $request->input('order', 'desc'));

        return $model->paginate($request->input('per_page', 10));
    }
}
