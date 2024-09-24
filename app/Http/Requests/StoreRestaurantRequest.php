<?php

namespace App\Http\Requests;

use App\Models\Restaurant;
use Gate;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Response;

class StoreRestaurantRequest extends FormRequest
{
    public function authorize()
    {
        return Gate::allows('restaurant_create');
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
                'unique:restaurants',
            ],
            'website_link' => [
                'string',
                'nullable',
            ],
            'photo.*' => [
                'nullable',
                'image',
                'max:2048',
            ],
        ];
    }
}
