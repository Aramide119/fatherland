<?php

namespace App\Http\Requests;

use App\Models\Advert;
use Gate;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Response;

class StoreAdvertRequest extends FormRequest
{
    public function authorize()
    {
        return Gate::allows('advert_create');
    }

    public function rules()
    {
        return [
            'title' => [
                'string',
                'required',
            ],
            'content' => [
                'required',
            ],
            'description' => [
                'required',
            ],
            'business_name' => [
                'string',
                'required',
            ],
            'location' => [
                'string',
                'required',
            ],
            'advert_category_id' => [
                'string',
                'required',
            ],
            'status' => [
                'required',
            ],
        ];
    }
}
