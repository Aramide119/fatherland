<?php

namespace App\Http\Requests;

use App\Models\Record;
use Gate;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Response;

class UpdateRecordRequest extends FormRequest
{
    public function authorize()
    {
        return Gate::allows('record_edit');
    }

    public function rules()
    {
        return [
            'name' => [
                'string',
                'required',
            ],
            'lineage' => [
                'required',
            ],
            'location' => [
                'string',
                'required',
            ],
            'notable_individual' => [
                'required',
            ],
            'about' => [
                'required',
            ],
            
            'reference_link' => [
                'string',
                'nullable',
            ],
            'reference' => [
                'array',
            ],
        ];
    }
}
