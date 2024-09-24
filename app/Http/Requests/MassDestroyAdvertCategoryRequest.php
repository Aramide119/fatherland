<?php

namespace App\Http\Requests;

use App\Models\AdvertCategory;
use Gate;
use Illuminate\Foundation\Http\FormRequest;
use Symfony\Component\HttpFoundation\Response;

class MassDestroyAdvertCategoryRequest extends FormRequest
{
    public function authorize()
    {
        abort_if(Gate::denies('advert_category_delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return true;
    }

    public function rules()
    {
        return [
            'ids'   => 'required|array',
            'ids.*' => 'exists:advert_categories,id',
        ];
    }
}
