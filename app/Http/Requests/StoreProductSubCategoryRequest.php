<?php

namespace App\Http\Requests;

use App\Models\ProductSubCategory;
use Gate;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Response;

class StoreProductSubCategoryRequest extends FormRequest
{
    public function authorize()
    {
        return Gate::allows('product_sub_category_create');
    }

    public function rules()
    {
        return [
            'name' => [
                'string',
                'required',
            ],
            'product_category_id' => [
                'string',
                'required',
            ],
        ];
    }
}
