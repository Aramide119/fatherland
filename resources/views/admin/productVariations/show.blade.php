@extends('layouts.admin')
@section('content')

<div class="card">
    <div class="card-header">
        {{ trans('global.show') }} {{ trans('cruds.productVariation.title') }}
    </div>

    <div class="card-body">
        <div class="form-group">
            <div class="form-group">
                <a class="btn btn-default" href="{{ route('admin.product-variations.index') }}">
                    {{ trans('global.back_to_list') }}
                </a>
            </div>
            <table class="table table-bordered table-striped">
                <tbody>
                    <tr>
                        <th>
                            {{ trans('cruds.productVariation.fields.id') }}
                        </th>
                        <td>
                            {{ $productVariation->id }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.productVariation.fields.size') }}
                        </th>
                        <td>
                            {{ App\Models\ProductVariation::SIZE_SELECT[$productVariation->size] ?? '' }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.productVariation.fields.color') }}
                        </th>
                        <td>
                            {{ $productVariation->color }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.productVariation.fields.quantity') }}
                        </th>
                        <td>
                            {{ $productVariation->quantity }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.productVariation.fields.product') }}
                        </th>
                        <td>
                            {{ $productVariation->product->name ?? '' }}
                        </td>
                    </tr>
                </tbody>
            </table>
            <div class="form-group">
                <a class="btn btn-default" href="{{ route('admin.product-variations.index') }}">
                    {{ trans('global.back_to_list') }}
                </a>
            </div>
        </div>
    </div>
</div>



@endsection