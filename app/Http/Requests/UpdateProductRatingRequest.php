<?php

namespace App\Http\Requests;

use App\Models\ProductRating;
use Gate;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Response;

class UpdateProductRatingRequest extends FormRequest
{
    public function authorize()
    {
        return Gate::allows('product_rating_edit');
    }

    public function rules()
    {
        return [];
    }
}
