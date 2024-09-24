<?php

namespace App\Http\Requests;

use App\Models\TravelOrder;
use Gate;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Response;

class UpdateTravelOrderRequest extends FormRequest
{
    public function authorize()
    {
        return Gate::allows('travel_order_edit');
    }

    public function rules()
    {
        return [
            'travel_id' => [
                'required',
                'integer',
            ],
            'member_id' => [
                'required',
                'integer',
            ],
        ];
    }
}
