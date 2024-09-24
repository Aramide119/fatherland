<?php

namespace App\Http\Requests;

use App\Models\AdvertCategory;
use Gate;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Response;

class StoreAdvertCategoryRequest extends FormRequest
{
    public function authorize()
    {
        return Gate::allows('advert_category_create');
    }

    public function rules()
    {
        return [
            'name' => [
                'string',
                'required',
            ],
        ];
    }
}
