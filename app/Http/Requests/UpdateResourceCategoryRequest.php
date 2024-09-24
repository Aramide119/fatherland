<?php

namespace App\Http\Requests;

use App\Models\ResourceCategory;
use Gate;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Response;

class UpdateResourceCategoryRequest extends FormRequest
{
    public function authorize()
    {
        return Gate::allows('resource_category_edit');
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
