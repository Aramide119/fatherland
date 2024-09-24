<?php

namespace App\Http\Requests;

use App\Models\Member;
use Gate;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Response;

class StoreMemberRequest extends FormRequest
{
    public function authorize()
    {
        return Gate::allows('member_create');
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
            'email' => [
                'required',
                'unique:members',
            ],
            'dob' => [
                'required',
                'date_format:' . config('panel.date_format'),
            ],
            'country' => [
                'string',
                'required',
            ],
            'city_state_province' => [
                'string',
                'nullable',
            ],
            'phone_no' => [
                'string',
                'required',
            ],
            'password' => [
                'required',
            ],
        ];
    }
}
