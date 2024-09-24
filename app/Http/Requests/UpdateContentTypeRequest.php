<?php

namespace App\Http\Requests;

use App\Models\ContentType;
use Gate;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Response;

class UpdateContentTypeRequest extends FormRequest
{
    public function authorize()
    {
        return Gate::allows('content_type_edit');
    }

    public function rules()
    {
        return [
            'name' => [
                'string',
                'required',
            ],
            'status' => [
                'required',
            ],
        ];
    }
}
