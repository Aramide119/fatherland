<?php

namespace App\Http\Requests;

use App\Models\ContentType;
use Gate;
use Illuminate\Foundation\Http\FormRequest;
use Symfony\Component\HttpFoundation\Response;

class MassDestroyContentTypeRequest extends FormRequest
{
    public function authorize()
    {
        abort_if(Gate::denies('content_type_delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return true;
    }

    public function rules()
    {
        return [
            'ids'   => 'required|array',
            'ids.*' => 'exists:content_types,id',
        ];
    }
}
