<?php

namespace App\Http\Requests;

use App\Models\EventCategory;
use Gate;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Response;

class UpdateEventCategoryRequest extends FormRequest
{
    public function authorize()
    {
        return Gate::allows('event_category_edit');
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
