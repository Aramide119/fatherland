<?php

namespace App\Http\Requests;

use App\Models\Member;
use Gate;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Response;

class UpdateMemberRequest extends FormRequest
{
    public function authorize()
    {
        return Gate::allows('member_edit');
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
                'unique:members,email,' . request()->route('member')->id,
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
        ];
    }
}
