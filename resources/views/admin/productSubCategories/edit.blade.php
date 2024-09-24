@extends('layouts.admin')
@section('content')

<div class="card">
    <div class="card-header">
        {{ trans('global.edit') }} {{ trans('cruds.productSubCategory.title_singular') }}
    </div>

    <div class="card-body">
        <form method="POST" action="{{ route("admin.product-sub-categories.update", [$productSubCategory->id]) }}" enctype="multipart/form-data">
            @method('PUT')
            @csrf
            <div class="form-group">
                <label class="required" for="name">{{ trans('cruds.productSubCategory.fields.name') }}</label>
                <input class="form-control {{ $errors->has('name') ? 'is-invalid' : '' }}" type="text" name="name" id="name" value="{{ old('name', $productSubCategory->name) }}" required>
                @if($errors->has('name'))
                    <div class="invalid-feedback">
                        {{ $errors->first('name') }}
                    </div>
                @endif
                <span class="help-block">{{ trans('cruds.productSubCategory.fields.name_helper') }}</span>
            </div>
            <div class="form-group">
                <label for="product_category_id">{{ trans('cruds.event.fields.category') }}</label>
                <select class="form-control {{ $errors->has('product_category_id') ? 'is-invalid' : '' }}" name="product_category_id" id="product_category_id" required>
                    <option value disabled {{ old('product_category_id', $selectedCategoryId) === null ? 'selected' : '' }}>{{ trans('global.pleaseSelect') }}</option>
                    @foreach ($categories as $category)
                        <option value="{{ $category->id }}" {{ old('product_category_id', $selectedCategoryId) == $category->id ? 'selected' : '' }}>
                            {{ $category->name }}
                        </option>
                    @endforeach
                </select>
                @if($errors->has('category'))
                    <div class="invalid-feedback">
                        {{ $errors->first('category') }}
                    </div>
                @endif
            </div>
            <div class="form-group">
                <button class="btn btn-danger" type="submit">
                    {{ trans('global.save') }}
                </button>
            </div>
        </form>
    </div>
</div>



@endsection
