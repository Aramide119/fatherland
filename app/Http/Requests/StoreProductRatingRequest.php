<?php

namespace App\Http\Requests;

use App\Models\ProductRating;
use Gate;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Response;

class StoreProductRatingRequest extends FormRequest
{
    public function authorize()
    {
        return Gate::allows('product_rating_create');
    }

    public function rules()
    {
        return [];
    }
}
