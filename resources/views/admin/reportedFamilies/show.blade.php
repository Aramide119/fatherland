@extends('layouts.admin')
@section('content')

<div class="card">
    <div class="card-header">
        {{ trans('global.show') }} Family Details
    </div>

    <div class="card-body">
        <div class="form-group">
            <div class="form-group">
                <a class="btn btn-default" href="{{ route('admin.reportedPosts.index') }}">
                    {{ trans('global.back_to_list') }}
                </a>
            </div>
            <table class="table table-bordered table-striped">
                <tbody>
                    <tr>
                        <th>
                            Family Id
                        </th>
                        <td>
                            {{$showFamily->id ?? ''}}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            Name
                        </th>
                        <td>
                            {{ $showFamily->family->name ?? '' }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                        Location
                        </th>
                        <td>
                            {{ $showFamily->family->location ?? '' }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                        Current Location
                        </th>
                        <td>
                            {{ $showFamily->family->current_location ?? '' }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                        Notable Individual
                        </th>
                        <td>
                            {{ $showFamily->family->notable_individual ?? '' }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                        About
                        </th>
                        <td>
                            {{ $showFamily->family->about ?? '' }}
                        </td>
                    </tr>
                </tbody>
            </table>
            <div class="form-group">
                <a class="btn btn-default" href="{{ route('admin.reportedFamilies.index') }}">
                    {{ trans('global.back_to_list') }}
                </a>
            </div>
        </div>
    </div>
</div>



@endsection