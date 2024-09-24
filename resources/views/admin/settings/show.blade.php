@extends('layouts.admin')
@section('content')

<div class="card">
    <div class="card-header">
        {{ trans('global.show') }} {{ trans('cruds.resource.title') }}
    </div>

    <div class="card-body">
        <div class="form-group">
            <div class="form-group">
                <a class="btn btn-default" href="{{ route('admin.settings.index') }}">
                    {{ trans('global.back_to_list') }}
                </a>
            </div>
            <table class="table table-bordered table-striped">
                <tbody>
                    <tr>
                        <th>
                            {{ trans('cruds.resource.fields.id') }}
                        </th>
                        <td>
                            {{ $setting->id }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            Favicon
                        </th>
                        <td>
                            @if (isset($setting) && $setting->favicon)
                            <div>
                                <img src="{{ asset($setting->favicon) }}" style="max-width: 70px; max-height: 70px;">
                            </div>
                            @endif
                        </td>
                    </tr>
                    <tr>
                        <th>
                            Fatherland Logo
                        </th>
                        <td>
                            @if (isset($setting) && $setting->fatherland_logo)
                            <div>
                                <img src="{{ asset($setting->fatherland_logo) }}" alt="fatherland_logo" style="max-width: 70px; max-height: 70px;">
                            </div>
                            @endif
                        </td>
                    </tr>
                    <tr>
                        <th>
                           Instagram Logo
                        </th>
                        <td>
                            @if (isset($setting) && $setting->instagram_logo)
                                    <div>
                                        <img src="{{ asset($setting->instagram_logo) }}" alt="instagram_logo" style="max-width: 70px; max-height: 70px;">
                                    </div>
                             @endif
                        </td>
                    </tr>
                    <tr>
                        <th>
                            Instagram Url
                        </th>
                        <td>
                            {{$setting->instagram_url }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            Facebook Logo
                        </th>
                        <td>
                            @if (isset($setting) && $setting->facebook_logo)
                            <div>
                                <img src="{{ asset($setting->facebook_logo) }}" alt="facebook_logo" style="max-width: 70px; max-height: 70px;">
                            </div>
                            @endif
                        </td>
                    </tr>
                    <tr>
                        <th>
                            Facebook Url
                        </th>
                        <td>
                            {{ $setting->facebook_url }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            LinkedIn Logo
                        </th>
                        <td>
                            @if (isset($setting) && $setting->linkedin_logo)
                                    <div>
                                        <img src="{{ asset($setting->linkedin_logo) }}" alt="fatherland_logo" style="max-width: 70px; max-height: 70px;">
                                    </div>
                                @endif
                        </td>
                    </tr>
                    <tr>
                        <th>
                            LinkedIn Url
                        </th>
                        <td>
                            {{ $setting->linkedin_url ?? '' }}
                        </td>
                    </tr>
                </tbody>
            </table>
            <div class="form-group">
                <a class="btn btn-default" href="{{ route('admin.settings.index') }}">
                    {{ trans('global.back_to_list') }}
                </a>
            </div>
        </div>
    </div>
</div>



@endsection