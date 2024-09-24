<?php

namespace App\Http\Requests;

use App\Models\AdvertInquiry;
use Gate;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Response;

class UpdateAdvertInquiryRequest extends FormRequest
{
    public function authorize()
    {
        return Gate::allows('advert_inquiry_edit');
    }

    public function rules()
    {
        return [
            'first_name' => [
                'string',
                'required',
            ],
            'last_name' => [
                'string',
                'required',
            ],
            'location' => [
                'string',
                'required',
            ],
            'email' => [
                'required',
            ],
            'company_name' => [
                'string',
                'required',
            ],
        ];
    }
}
