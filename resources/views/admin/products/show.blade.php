@extends('layouts.admin')
@section('content')

<div class="card">
    <div class="card-header">
        {{ trans('global.show') }} {{ trans('cruds.product.title') }}
    </div>

    <div class="card-body">
        <div class="form-group">
            <div class="form-group">
                <a class="btn btn-default" href="{{ route('admin.products.index') }}">
                    {{ trans('global.back_to_list') }}
                </a>
            </div>
            <table class="table table-bordered table-striped">
                <tbody>
                    <tr>
                        <th>
                            {{ trans('cruds.product.fields.id') }}
                        </th>
                        <td>
                            {{ $product->id }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.product.fields.name') }}
                        </th>
                        <td>
                            {{ $product->name }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.product.fields.price') }}
                        </th>
                        <td>
                            {{ $product->price }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.product.fields.description') }}
                        </th>
                        <td>
                            {!! $product->description !!}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.product.fields.specification') }}
                        </th>
                        <td>
                            {!! $product->specification !!}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.product.fields.delivery_amount') }}
                        </th>
                        <td>
                            {{ $product->delivery_amount }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.product.fields.discount') }}
                        </th>
                        <td>
                            {{ $product->discount }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.product.fields.category') }}
                        </th>
                        <td>
                            {{ $product->category->name }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.product.fields.sub_category') }}
                        </th>
                        <td>
                            {{ $product->sub_category->name }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            Images
                        </th>
                        <td>
                            <a href="#" data-toggle="modal" data-target="#imagesModal-{{ $product->id }}">
                                {{ trans('global.view_file') }}
                            </a>
                        </td>
                    </tr>
                    <tr>
                        <th>
                            Sizes
                        </th>
                        <td>
                            @foreach ($product->sizes as $size )
                                {{ $size->name}} {{ $loop->last ? '' : ', ' }}
                            @endforeach
                        </td>
                    </tr>
                    <tr>
                        <th>
                            Colors
                        </th>
                        <td>
                            @foreach ($product->colors as $color )
                                {{ $color->name}} {{ $loop->last ? '' : ', ' }}
                            @endforeach
                        </td>
                    </tr>
                </tbody>
            </table>
            <div class="form-group">
                <a class="btn btn-default" href="{{ route('admin.products.index') }}">
                    {{ trans('global.back_to_list') }}
                </a>
            </div>
        </div>

        <!-- Modal to show images -->
                        <div class="modal fade" id="imagesModal-{{ $product->id }}" tabindex="-1" role="dialog" aria-labelledby="imagesModalLabel-{{ $product->id }}" aria-hidden="true">
                           <div class="modal-dialog modal-lg" role="document">
                               <div class="modal-content">
                                   <div class="modal-header">
                                       <h5 class="modal-title" id="imagesModalLabel-{{ $product->id }}">Product Images</h5>
                                       <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                           <span aria-hidden="true">&times;</span>
                                       </button>
                                   </div>
                                   <div class="modal-body">
                                       <div class="d-flex flex-wrap justify-content-center">
                                           @foreach($product->getMedia('images') as $image)
                                               <img src="{{ $image->getUrl() }}" alt="Image" class="img-thumbnail m-2">
                                           @endforeach
                                       </div>
                                   </div>
                               </div>
                           </div>
                       </div>
    </div>
</div>



@endsection
