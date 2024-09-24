<?php

namespace App\Http\Requests;

use App\Models\Content;
use Gate;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Response;

class UpdateContentRequest extends FormRequest
{
    public function authorize()
    {
        return Gate::allows('content_edit');
    }

    public function rules()
    {
        return [
            'title' => [
                'string',
                'required',
            ],
            'blog_content' => [
                'required',
            ],
            'blog_image' => [
                'required',
            ],
            'content_type_id' => [
                'required',
                'integer',
            ],
            'content_category_id' => [
                'required',
                'integer',
            ],
        ];
    }
}
