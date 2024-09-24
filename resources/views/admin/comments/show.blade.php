@extends('layouts.admin')
@section('content')

<div class="card">
    <div class="card-header d-flex justify-content-space-between containerBox">
        <div class="col-3">
            <a class="btn btn-default start " href="{{ route('admin.comments.index') }} ">
                        {{ trans('global.back_to_list') }}
            </a>
        </div>
        <div class="col-6 text-center">
            <p class="mt-2" style="font-weight: bold; font-size:large">
                Comments Details
            </p>
        </div>
    </div>


    <div class="card-body">

            <div class="row">
                <div class="col-1 rounded-circle">
                    <img class="rounded-circle" src="{{ $viewComments->user->profile_picture ? $viewComments->user->profile_picture : asset('image/avatar1.png') }}" alt="Image" width="40" height="40">
                </div>

                <div class="col-6 bg-light rounded-5 shadow">
                    <p style="font-weight: bold; margin-bottom: 0px">
                        {{ $viewComments->user->name }}
                        <span style="font-weight: 100; font-size: small;"><i>{{ $viewComments->created_at->diffForHumans() }}</i></span>
                    </p>
                    <p style="margin-top: 0px;">
                        {{ $viewComments->comment }}
                    </p>
                </div>
            </div>

            <div class="row">
                <div class="col-3 text-center mt-3">
                     <p style="font-weight: bold; font-style:italic">Replies</p>
                </div>
            </div>


                <div class="row">
                @foreach ($viewComments->replies as $reply)
                        <div class="col-1 rounded-circle"  class='mb-4'>
                            @if($reply->admin)
                               <img class="rounded-circle"
                                 src="{{ asset('image/avatar1.png') }}"
                                   alt="Admin Image" width="40" height="40">
                            @elseif($reply->user)
                               <img class="rounded-circle"
                                 src="{{ $reply->user->profile_picture ? $reply->user->profile_picture : asset('image/avatar1.png') }}"
                                  alt="User Image" width="40" height="40">
                            @else
                              <img class="rounded-circle"
                                src="{{ asset('image/avatar.png') }}"
                                  alt="Default Image" width="40" height="40">
                            @endif
                        </div>
                        <div class="col-6 bg-light rounded-5 shadow mb-3">
                            @if($reply->admin)
                              <p style="font-weight: bold; margin-bottom: 0px">
                                {{ $reply->admin->name }}
                                 <span style="font-weight: 100; font-size: small;"><i>{{ $reply->created_at->diffForHumans() }}</i></span>
                              </p>
                            @elseif($reply->user)
                              <p style="font-weight: bold; margin-bottom: 0px">
                                {{ $reply->user->name }}
                                  <span style="font-weight: 100; font-size: small;"><i>{{ $reply->created_at->diffForHumans() }}</i></span>
                              </p>
                            @else
                             <p style="font-weight: bold; margin-bottom: 0px">
                               <i>Unknown User</i>
                                 <span style="font-weight: 100; font-size: small;"><i>{{ $reply->created_at->diffForHumans() }}</i></span>
                             </p>
                           @endif
                            <p style="margin-top: 0px;">
                                {!! $reply->comment !!}
                            </p>
                        </div>
                        <div class='col-5'>

                        </div>
                        @endforeach

                </div>

        </div>
    </div>
</div>



@endsection
