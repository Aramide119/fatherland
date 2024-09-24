@extends('layouts.admin')
@section('content')

<div class="row">
  
    <div class="col">
        <div class="card">
            <div class="card-header">
                Settings
            </div>

            <div class="card-body">
                <form method="POST" action="{{ route("admin.settings.store") }}" enctype="multipart/form-data">
                    @csrf
                    <div class="card-body">
                            <div class="row">
                                <div class="form-group col-6">
                                    <label for="favicon">Favicon</label>
                                    @if (isset($setting) && $setting->favicon)
                                    <div>
                                        <img src="{{ asset($setting->favicon) }}" style="max-width: 100px; max-height: 100px;">
                                    </div>
                                    @endif
                                    <input type="file" name="favicon" id="" class="form-control {{ $errors->has('favicon') ? 'is-invalid' : '' }}" value="">
                                    @if ($errors->has('favicon'))
                                        <span class="invalid-feedback">{{ $errors->first('`favicon') }}</span>
                                    @endif
                                </div>
                                <div class="form-group col-6">
                                    <label for="fatherland_logo">Fatherland Logo</label>
                                    @if (isset($setting) && $setting->fatherland_logo)
                                        <div>
                                            <img src="{{ asset($setting->fatherland_logo) }}" alt="fatherland_logo" style="max-width: 100px; max-height: 100px;">
                                        </div>
                                    @endif
                                    <input type="file" name="fatherland_logo" id="" class="form-control {{ $errors->has('fatherland_logo') ? 'is-invalid' : '' }}">
                                    @if ($errors->has('fatherland_logo'))
                                        <span class="invalid-feedback">{{ $errors->first('fatherland_logo') }}</span>
                                    @endif
                                </div>
                            </div>
                            <div class="row">
                                <div class="form-group col-md-6">
                                    <label for="linkedin_logo">LinkedIn Logo</label>
                                    @if (isset($setting) && $setting->linkedin_logo)
                                    <div>
                                        <img src="{{ asset($setting->linkedin_logo) }}" alt="fatherland_logo" style="max-width: 100px; max-height: 100px;">
                                    </div>
                                @endif
                                    <input type="file" name="linkedin_logo" id="" class="form-control {{ $errors->has('linkedin_logo') ? 'is-invalid' : '' }}">
                                    @if ($errors->has('linkedin_logo'))
                                        <span class="invalid-feedback">{{ $errors->first('linkedin_logo') }}</span>
                                    @endif
                                </div>
                                <div class="form-group col-md-6">
                                    <label for="linkedin_url">LinkedIn Url</label>
                                    <input type="text" name="linkedin_url" id="" class="form-control {{ $errors->has('linkedin_url') ? 'is-invalid' : '' }}" value="@if (isset($setting)){{ $setting->linkedin_url }}@endif">
                                    @if ($errors->has('linkedin_url'))
                                        <span class="invalid-feedback">{{ $errors->first('linkedin_url') }}</span>
                                    @endif
                                </div>
                            </div>
                            <div class="row">
                                <div class="form-group col-md-6">
                                    <label for="facebook_logo">Facebook Logo</label>
                                    @if (isset($setting) && $setting->facebook_logo)
                                    <div>
                                        <img src="{{ asset($setting->facebook_logo) }}" alt="facebook_logo" style="max-width: 100px; max-height: 100px;">
                                    </div>
                                    @endif
                                    <input type="file" name="facebook_logo" id="" class="form-control {{ $errors->has('facebook_logo') ? 'is-invalid' : '' }}">
                                    @if ($errors->has('facebook_logo'))
                                        <span class="invalid-feedback">{{ $errors->first('facebook_logo') }}</span>
                                    @endif
                                </div>
                                <div class="form-group col-md-6">
                                    <label for="facebook_url">Facebook Url</label>
                                    <input type="text" name="facebook_url" id="" class="form-control {{ $errors->has('facebook_url') ? 'is-invalid' : '' }}" value="@if (isset($setting)){{ $setting->facebook_url }}@endif">
                                    @if ($errors->has('facebook_url'))
                                        <span class="invalid-feedback">{{ $errors->first('facebook_url') }}</span>
                                    @endif
                                </div>
                            </div>
                            <div class="row">
                                <div class="form-group col-md-6">
                                    <label for="instagram_logo">Instagram Logo</label>
                                    @if (isset($setting) && $setting->instagram_logo)
                                    <div>
                                        <img src="{{ asset($setting->instagram_logo) }}" alt="instagram_logo" style="max-width: 100px; max-height: 100px;">
                                    </div>
                                    @endif
                                    <input type="file" name="instagram_logo" id="" class="form-control {{ $errors->has('instagram_logo') ? 'is-invalid' : '' }}">
                                    @if ($errors->has('instagram_logo'))
                                        <span class="invalid-feedback">{{ $errors->first('instagram_logo') }}</span>
                                    @endif
                                </div>
                                <div class="form-group col-md-6">
                                    <label for="instagram_url">Instagram Url</label>
                                    <input type="text" name="instagram_url" id="" class="form-control {{ $errors->has('instagram_url') ? 'is-invalid' : '' }}" value="@if (isset($setting)){{ $setting->instagram_url }}@endif">
                                    @if ($errors->has('instagram_url'))
                                        <span class="invalid-feedback">{{ $errors->first('instagram_url') }}</span>
                                    @endif
                                </div>
                            </div>
                            
                            <button class="btn btn-danger mt-3" type="submit">{{ trans('global.save') }}</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

</div>
@endsection