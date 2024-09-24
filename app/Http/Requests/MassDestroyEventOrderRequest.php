<?php

namespace App\Http\Requests;

use App\Models\EventOrder;
use Gate;
use Illuminate\Foundation\Http\FormRequest;
use Symfony\Component\HttpFoundation\Response;

class MassDestroyEventOrderRequest extends FormRequest
{
    public function authorize()
    {
        abort_if(Gate::denies('event_order_delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return true;
    }

    public function rules()
    {
        return [
            'ids'   => 'required|array',
            'ids.*' => 'exists:event_orders,id',
        ];
    }
}
