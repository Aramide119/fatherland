@extends('layouts.admin')
@section('content')

<div class="container-fluid">
    <div class="row justify-content-center">
        <div class="col-md-10">
            <div class="card" style="width: 100%;">
                <div class="card-header">
                    <a class="btn btn-default" href="{{ route('admin.families.index') }}">
                        {{ trans('global.back_to_list') }}
                    </a>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="card mb-3" style="width: 100%;">
                                <div class="text-center">
                                    @if (empty($user->profile_picture))
                                        <img class="card-img-top img-fluid" src="{{ asset('image/avatar.jpg') }}" alt="{{ $user->name }}" style="height: 200px; object-fit: cover;">
                                    @else
                                        <img class="card-img-top img-fluid" src="{{ asset($user->profile_picture) }}" alt="{{ $user->name }}" style="height: 500px; object-fit: cover;">
                                    @endif
                                </div>
                                <div class="card-body">
                                    <h5 class="card-title text-center">{{ $user->name }}</h5>
                                    <p class="card-text">Email: {{ $user->email }}</p>
                                    <p class="card-text">Location: {{ $user->location }}</p>
                                    <p class="card-text">Profession: {{ $user->profession }}</p>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="card mb-3" style="width: 100%;">
                                <div class="card-body">
                                    <h5 class="card-title">About</h5>
                                    <p class="card-text">{{ $user->about }}</p>
                                    <a href="#" class="btn btn-primary">{{ $user->account_type }}</a>
                                </div>
                            </div>
                            <div class="card" style="width: 100%;">
                                <div class="card-body">
                                    <h5 class="card-title text-center">Other Info</h5>
                                    <ul class="list-group list-group-flush">
                                        <li class="list-group-item">University: {{ $user->university }}</li>
                                        <li class="list-group-item">Education: {{ $user->education }}</li>
                                        <li class="list-group-item">Contact: {{ $user->phone_number }}</li>
                                    </ul>
                                </div>
                            </div>
                        </div>
                    </div>
                    <a class="btn btn-default mt-3" href="{{ route('admin.families.index') }}">
                        {{ trans('global.back_to_list') }}
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>

@endsection
