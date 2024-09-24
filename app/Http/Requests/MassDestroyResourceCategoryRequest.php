<?php

namespace App\Http\Requests;

use App\Models\ResourceCategory;
use Gate;
use Illuminate\Foundation\Http\FormRequest;
use Symfony\Component\HttpFoundation\Response;

class MassDestroyResourceCategoryRequest extends FormRequest
{
    public function authorize()
    {
        abort_if(Gate::denies('resource_category_delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return true;
    }

    public function rules()
    {
        return [
            'ids'   => 'required|array',
            'ids.*' => 'exists:resource_categories,id',
        ];
    }
}
