<?php

namespace App\Http\Requests;

use App\Models\EventOrder;
use Gate;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Response;

class StoreEventOrderRequest extends FormRequest
{
    public function authorize()
    {
        return Gate::allows('event_order_create');
    }

    public function rules()
    {
        return [
            'event_id' => [
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
