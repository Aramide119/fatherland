@extends('layouts.admin')
@section('content')

<div class="card">
    <div class="card-header">
        {{ trans('global.create') }} {{ trans('cruds.travelOrder.title_singular') }}
    </div>

    <div class="card-body">
        <form method="POST" action="{{ route("admin.travel-orders.store") }}" enctype="multipart/form-data">
            @csrf
            <div class="form-group">
                <label class="required" for="travel_id">{{ trans('cruds.travelOrder.fields.travel') }}</label>
                <select class="form-control select2 {{ $errors->has('travel') ? 'is-invalid' : '' }}" name="travel_id" id="travel_id" required>
                    @foreach($travel as $id => $entry)
                        <option value="{{ $id }}" {{ old('travel_id') == $id ? 'selected' : '' }}>{{ $entry }}</option>
                    @endforeach
                </select>
                @if($errors->has('travel'))
                    <div class="invalid-feedback">
                        {{ $errors->first('travel') }}
                    </div>
                @endif
                <span class="help-block">{{ trans('cruds.travelOrder.fields.travel_helper') }}</span>
            </div>
            <div class="form-group">
                <label class="required" for="member_id">{{ trans('cruds.travelOrder.fields.member') }}</label>
                <select class="form-control select2 {{ $errors->has('member') ? 'is-invalid' : '' }}" name="member_id" id="member_id" required>
                    @foreach($members as $id => $entry)
                        <option value="{{ $id }}" {{ old('member_id') == $id ? 'selected' : '' }}>{{ $entry }}</option>
                    @endforeach
                </select>
                @if($errors->has('member'))
                    <div class="invalid-feedback">
                        {{ $errors->first('member') }}
                    </div>
                @endif
                <span class="help-block">{{ trans('cruds.travelOrder.fields.member_helper') }}</span>
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