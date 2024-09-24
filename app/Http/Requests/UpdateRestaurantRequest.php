<?php

namespace App\Http\Requests;

use App\Models\Restaurant;
use Gate;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Response;

class UpdateRestaurantRequest extends FormRequest
{
    public function authorize()
    {
        return Gate::allows('restaurant_edit');
    }

    public function rules()
    {
        return [
            'name' => [
                'string',
                'required',
            ],
            'location' => [
                'string',
                'required',
            ],
            'phone_number' => [
                'string',
                'required',
            ],
            'email' => [
                'required',
                'unique:restaurants,email,' . request()->route('restaurant')->id,
            ],
            'website_link' => [
                'string',
                'nullable',
            ],
        ];
    }
}
