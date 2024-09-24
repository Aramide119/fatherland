@extends('layouts.admin')
@section('content')
@section('styles')
    <style>
        .img-thumbnail {
            max-width: 100px;
            max-height: 100px;
            object-fit: cover;
        }
    </style>

@endsection
<div class="card">
    <div class="card-header">
        {{ trans('global.show') }} {{ trans('cruds.restaurant.title') }}
    </div>

    <div class="card-body">
        <div class="form-group">
            <div class="form-group">
                <a class="btn btn-default" href="{{ route('admin.restaurants.index') }}">
                    {{ trans('global.back_to_list') }}
                </a>
            </div>
            <table class="table table-bordered table-striped">
                <tbody>
                    <tr>
                        <th>
                            {{ trans('cruds.restaurant.fields.id') }}
                        </th>
                        <td>
                            {{ $restaurant->id }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.restaurant.fields.name') }}
                        </th>
                        <td>
                            {{ $restaurant->name }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.restaurant.fields.location') }}
                        </th>
                        <td>
                            {{ $restaurant->location }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.restaurant.fields.phone_number') }}
                        </th>
                        <td>
                            {{ $restaurant->phone_number }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.restaurant.fields.email') }}
                        </th>
                        <td>
                            {{ $restaurant->email }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.restaurant.fields.website_link') }}
                        </th>
                        <td>
                            {{ $restaurant->website_link }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.restaurant.fields.description') }}
                        </th>
                        <td>
                            {!! $restaurant->description !!}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            Images
                        </th>
                        <td>
                            <a href="#" data-toggle="modal" data-target="#imagesModal-{{ $restaurant->id }}">
                                    {{ trans('global.view_file') }}
                            </a>
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.restaurant.fields.status') }}
                        </th>
                        <td>
                            {{ App\Models\Restaurant::STATUS_SELECT[$restaurant->status] ?? '' }}
                        </td>
                    </tr>
                </tbody>
            </table>
            <div class="form-group">
                <a class="btn btn-default" href="{{ route('admin.restaurants.index') }}">
                    {{ trans('global.back_to_list') }}
                </a>
            </div>
        </div>

         <!-- Modal to show images -->
                <div class="modal fade" id="imagesModal-{{ $restaurant->id }}" tabindex="-1" role="dialog" aria-labelledby="imagesModalLabel-{{ $restaurant->id }}" aria-hidden="true">
                    <div class="modal-dialog modal-lg" role="document">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title" id="imagesModalLabel-{{ $restaurant->id }}">Restaurant Images</h5>
                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                </button>
                            </div>
                            <div class="modal-body">
                                <div class="d-flex flex-wrap justify-content-center">
                                    @foreach($restaurant->getMedia('images') as $image)
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
