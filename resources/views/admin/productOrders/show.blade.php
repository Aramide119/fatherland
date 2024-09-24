@extends('layouts.admin')
@section('content')
    <div class="container mt-5">


        <div class="form-group">
            <div class="form-group">
                <a class="btn btn-default" href="{{ route('admin.product.order') }}">
                    {{ trans('global.back_to_list') }}
                </a>
            </div>

        @foreach ($orders as $item)
        <div class="card order-card">
            <div class="card-body d-flex align-items-center">
                <div class="mr-3">
                    <div id="carouselExampleIndicators{{ $item->id }}" class="carousel slide" data-ride="carousel">
                        <ol class="carousel-indicators">
                            @foreach($item->product->getMedia('images') as $key => $image)
                                <li data-target="#carouselExampleIndicators{{ $item->id }}" data-slide-to="{{ $key }}" class="{{ $key === 0 ? 'active' : '' }}"></li>
                            @endforeach
                        </ol>
                        <div class="carousel-inner">
                            @foreach($item->product->getMedia('images') as $key => $image)
                                <div class="carousel-item {{ $key === 0 ? 'active' : '' }}">
                                    <img src="{{ $image->getUrl() }}" class="d-block w-100 order-image" alt="...">
                                </div>
                            @endforeach
                        </div>
                        {{-- <a class="carousel-control-prev" href="#carouselExampleIndicators{{ $item->id }}" role="button" data-slide="prev">
                            <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                            <span class="sr-only">Previous</span>
                        </a>
                        <a class="carousel-control-next" href="#carouselExampleIndicators{{ $item->id }}" role="button" data-slide="next">
                            <span class="carousel-control-next-icon" aria-hidden="true"></span>
                            <span class="sr-only">Next</span>
                        </a> --}}
                    </div>
                     {{-- @foreach($item->product->getMedia('images') as $image)
                                               <img src="{{ $image->getUrl() }}" alt="" class="order-image">
                                           @endforeach --}}
                </div>
                <div class="flex-grow-1">
                    <h5>{{ $item->product->name }}</h5>
                    <p class="order-details">
                        Size:
                            {{ $item->size->name }} |
                        Color:
                            {{ $item->color->name }}
                    </p>
                </div>
                <div class="text-center mr-5">
                    <p>Quantity</p>
                    <p class="order-amount">{{ $item['quantity'] }}</p>
                </div>
                <div class="text-center mr-5">
                    <p>Amount</p>
                    <p class="order-amount">${{ $item->product->price }}</p>
                </div>
                <div class="text-center mr-5">
                    <p>Order Date</p>
                    <p class="order-date order-amount">{{ date('d F, Y.', strtotime($item->created_at)) }}</p>
                </div>
                <div class="text-center mr-5">
                    <p>Order Status</p>
                    <p class="order-amount">{{ $item->order->status }}</p>
                </div>
                <div class="text-center mr-4">
                    <p>Seller Status</p>
                    <p class="order-amount">Pending</p>
                </div>
                <div class="text-center">
                    <p></p>
                    <a class="btn btn-xs btn-primary" href="">
                        {{ trans('global.edit') }}
                    </a>
                </div>
            </div>
        </div>
        @endforeach
    </div>
@endsection
